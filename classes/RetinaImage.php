<?php

namespace Lingo\Retina;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * PHP version 5
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    RetinaImage
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class RetinaImage extends Controller
{

	public function modifyFrontendPageHook($strContent, $strTemplate)
	{
		if (substr($strTemplate, 0, 3) == 'fe_')
		{
			$strContent = $this->parseHtmlTags($strContent);
		}

		return $strContent;
	}


	public function outputFrontendTemplateHook($strContent, $strTemplate)
	{
		if (substr($strTemplate, 0, 3) == 'fe_')
		{
			$strContent = $this->parseHtmlTags($strContent);
			$strContent = $this->parseInsertTags($strContent);
		}

		return $strContent;
	}


	// necessary because of https://github.com/contao/core/issues/4291
	protected function parseInsertTags($strContent)
	{
		$startPos = 0;
		$endPos = 0;

		while (($startPos = stripos($strContent, '{{image::', $endPos)) !== FALSE)
		{
			if (($endPos = stripos($strContent, '}}', $startPos+1)) !== FALSE)
			{
				$strTag = substr($strContent, $startPos, $endPos-$startPos+2);

				// skip if there are no params given
				if (strpos($strTag, '?') === FALSE)
				{
					continue;
				}

				$strFile = str_replace('../', '', substr($strTag, 9, strpos($strTag, '?')-9));
				$strParams = substr($strTag, strpos($strTag, '?')+1, -2);
				
				$intWidth = 0; $intHeight = 0;

				$this->import('String');
				
				$strParams = $this->String->decodeEntities($strParams);
				$strParams = str_replace('[&]', '&', $strParams);
				$arrParams = explode('&', $strParams);

				// retrieve given width and/or height
				foreach ($arrParams as $strParam)
				{
					list($key, $value) = explode('=', $strParam);
					
					switch ($key)
					{
						case 'width':
							$intWidth = $value;
							break;

						case 'height':
							$intHeight = $value;
							break;
					}
				}

				// skip if no size given or file does not exists
				if (($intWidth > 0 || $intHeight > 0) && file_exists(TL_ROOT.'/'.rawurldecode($strFile)))
				{
					$arrSize = getimagesize(TL_ROOT.'/'.rawurldecode($strFile));

					// skip if image does not have double size of the given width/height
					if (($intWidth > 0 && $arrSize[0] >= $intWidth*2) || ($intHeight > 0 && $arrSize[1] >= $intHeight*2))
					{
						if (strpos($strTag, 'class=') !== FALSE)
						{
							$strTag = str_replace('class=', 'class=at2x ', $strTag);
						}
						else
						{
							$strTag = str_replace('}}', '&amp;class=at2x}}', $strTag);
						}
						
						$strContent = substr($strContent, 0, $startPos).$strTag.substr($strContent, $endPos+2);
					}
				}
			}
			else
			{
				break;
			}
		}
		
		return $strContent;
	}


	protected function parseHtmlTags($strContent)
	{
		$startPos = 0;
		$endPos = 0;

		while (($startPos = stripos($strContent, '<img ', $endPos)) !== FALSE)
		{
			if (($endPos = stripos($strContent, '>', $startPos+1)) !== FALSE)
			{
				$strTag = substr($strContent, $startPos, $endPos-$startPos+1);

				if (preg_match('~ src=["\']([^"\']+)\.([a-zA-Z0-9]+)["\']~i', $strTag, $matches))
				{
					if (file_exists(TL_ROOT.'/'.rawurldecode($matches[1]).'@2x.'.$matches[2]))
					{
						$strTag = preg_replace('~( class=["\'][^"\']+)(["\'])~i', '$1 at2x$2', $strTag, 1, $count);

						if ($count == 0)
						{
							$strTag = str_replace('<img', '<img class="at2x"', $strTag);
						}
						
						$widthPos = strpos($strTag, 'width=');
						$heightPos = strpos($strTag, 'height=');

						// add size attributes if not given
						if ($widthPos === FALSE || $heightPos === FALSE)
						{
							$imageSize = getimagesize(TL_ROOT.'/'.rawurldecode($matches[1]).'.'.$matches[2]);

							if ($heightPos === FALSE)
							{
								$strTag = str_replace('<img', '<img height="'.$imageSize[1].'"', $strTag);
							}

							if ($widthPos === FALSE)
							{
								$strTag = str_replace('<img', '<img width="'.$imageSize[0].'"', $strTag);
							}
						}

						$strContent = substr($strContent, 0, $startPos).$strTag.substr($strContent, $endPos+1);
					}
				}
			}
			else
			{
				break;
			}
		}
		
		return $strContent;
	}
	
/*
	public function getContentElementHook($objElement, $strBuffer)
	{
		$startPos = 0;
		$endPos = 0;

		while (($startPos = strpos($strBuffer, '<img ', $endPos)) !== FALSE)
		{
			if (($endPos = strpos($strBuffer, '>', $startPos+1)) !== FALSE)
			{
				$strTag = substr($strBuffer, $startPos, $endPos-$startPos+1);

				if (preg_match('~ src=["\']([^"\']+)\.([a-zA-Z0-9]+)["\']~i', $strTag, $matches))
				{
					if (file_exists(TL_ROOT.'/'.$matches[1].'@2x.'.$matches[2]))
					{
						$strTag = preg_replace('~( class=["\'][^"\']+)(["\'])~i', '$1 at2x$2', $strTag, 1, $count);
						
						if ($count == 0)
						{
							$strTag = str_replace('<img', '<img class="at2x"', $strTag);
						}
						
						$strBuffer = substr($strBuffer, 0, $startPos).$strTag.substr($strBuffer, $endPos+1);
					}
				}
			}
			else
			{
				break;
			}
		}
		
		return $strBuffer;
	}
*/

	public function getImageHook($image, $width, $height, $mode, $strCacheName, $objFile, $target)
	{
		if ($target)
		{
			return;
		}

		/* get size of original image */
		$arrSize = getimagesize(TL_ROOT.'/'.$image);

		/* skip if size smaller then 2x */
		if ($arrSize[0] < $width*2 || $arrSize[1] < $height*2)
		{
			return;
		}

		/* unset Hook */
		if (count($GLOBALS['TL_HOOKS']['getImage']) == 1)
		{
			$GLOBALS['TL_HOOKS']['getImage'] = array();
		}
		else
		{
			foreach ($GLOBALS['TL_HOOKS']['getImage'] as $key => $value)
			{
				if (isset($value['RetinaImage']))
				{
					unset($GLOBALS['TL_HOOKS']['getImage'][$key]);
					break;
				}
			}
		}

		/* build retina file name: original cache file name + @2x. + extension */
		$strCacheNameRetina = substr($strCacheName, 0, -(strlen('.'.$objFile->extension))).'@2x.'.$objFile->extension;

		/* resize image and rename it */
		$strCacheNameNew = $this->getImage($image, $width*2, $height*2, $mode, $strCacheNameRetina);

		/* add hook */
		$GLOBALS['TL_HOOKS']['getImage'][] = array('RetinaImage', 'getImageHook');

		return;
	}


	public function replaceInsertTagsHook($strTag)
	{
		global $objPage;

		$arrSplit = explode('::', $strTag);

		if ($arrSplit[0] == 'svg')
		{
			if (!empty($arrSplit[1]))
			{
				$width = null;
				$height = null;
				$alt = '';
				$class = '';

				$arrTag = explode('?', $arrSplit[1]);

				$strFile = $arrTag[0];

				if (!empty($arrTag[1]))
				{
					$arrAttributes = explode('&', $arrTag[1]);

					foreach ($arrAttributes as $attribute)
					{
						$arrValue = explode('=', $attribute);

						if (count($arrValue) == 2)
						{
							switch ($arrValue[0])
							{
								case 'width':
									$width = $arrValue[1];
									break;
								case 'height':
									$height = $arrValue[1];
									break;
								case 'alt':
									$alt = $arrValue[1];
									break;
								case 'class':
									$class = $arrValue[1];
									break;
							}
						}
					}
				}

				$size = array($width, $height);

				$size = self::_getSvgSize($strFile, $size);

				$strReturn = '<img src="'.$strFile.'"'.($size[0]?' width="'.$size[0].'"':'').($size[1]?' height="'.$size[1].'"':'').($class?' class="'.$class.'"':'').($alt?' alt="'.$alt.'"':'').'>';

				return $strReturn;
			}
		}

		return false;
	}
}
