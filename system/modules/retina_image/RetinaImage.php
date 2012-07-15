<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Lingo4you 2012
 * @author     Mario Müller <http://www.lingo4u.de/>
 * @package    RetinaImage
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class RetinaImage extends Controller
{

	public function parseFrontendTemplateHook($strContent, $strTemplate)
	{
		if (substr($strTemplate, 0, 3) == 'fe_')
		{
			$startPos = 0;
			$endPos = 0;

			while (($startPos = strpos($strContent, '<img ', $endPos)) !== FALSE)
			{
				if (($endPos = strpos($strContent, '>', $startPos+1)) !== FALSE)
				{
					$strTag = substr($strContent, $startPos, $endPos-$startPos+1);

					if (preg_match('~ src=["\']([^"\']+)\.([a-zA-Z0-9]+)["\']~i', $strTag, $matches))
					{
						if (file_exists(TL_ROOT.'/'.urldecode($matches[1]).'@2x.'.$matches[2]))
						{
							$strTag = preg_replace('~( class=["\'][^"\']+)(["\'])~i', '$1 at2x$2', $strTag, 1, $count);

							if ($count == 0)
							{
								$strTag = str_replace('<img', '<img class="at2x"', $strTag);
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
}
