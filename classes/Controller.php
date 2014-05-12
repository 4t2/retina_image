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
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class Controller extends \Contao\Controller
{

	/**
	 * Add an image to a template
	 *
	 * @param object  $objTemplate   The template object to add the image to
	 * @param array   $arrItem       The element or module as array
	 * @param integer $intMaxWidth   An optional maximum width of the image
	 * @param string  $strLightboxId An optional lightbox ID
	 */
	public static function addImageToTemplate($objTemplate, $arrItem, $intMaxWidth=null, $strLightboxId=null)
	{
		if (stristr($arrItem['singleSRC'], '.svg') !== FALSE)
		{
			$size = deserialize($arrItem['size']);

			$size = self::_getSvgSize($arrItem['singleSRC'], $size);

			$arrItem['size'] = serialize($size);

			@parent::addImageToTemplate($objTemplate, $arrItem, $intMaxWidth, $strLightboxId);

			$imgSize = '';

			$imgSize .= ' width="'.$size[0].'"';
			$imgSize .= ' height="'.$size[1].'"';

			$objTemplate->imgSize = $imgSize;
			$objTemplate->svgImage = TRUE;

			if ($width > 0 && $height > 0)
			{
				$objTemplate->width = $imgSize[0];
				$objTemplate->height = $imgSize[1];
			}

		}
		else
		{
			parent::addImageToTemplate($objTemplate, $arrItem, $intMaxWidth, $strLightboxId);
		}
	}


	protected static function _getSvgSize($strFile, $size)
	{
		$svgFile = simplexml_load_file(TL_ROOT .'/'. $strFile);

		$ratio = 1;
		$width = 0;
		$height = 0;

		if (isset($svgFile['viewBox']) && $svgFile['viewBox'] != '')
		{
			$viewBox = explode(' ', $svgFile['viewBox']);

			$width = preg_replace('#^([\d]+).*$#', '$1', $viewBox[2]);
			$height = preg_replace('#^([\d]+).*$#', '$1', $viewBox[3]);
		}
		elseif (isset($svgFile['width']) && isset($svgFile['height']))
		{
			$width = preg_replace('#^([\d]+).*$#', '$1', $svgFile['width']);
			$height = preg_replace('#^([\d]+).*$#', '$1', $svgFile['height']);
		}

		if ($width > 0 && $height > 0)
		{
			$ratio = $width / $height;
		}

		if ($size[0] && $size[1])
		{
			// nothing do do
		}
		else
		{
			if (!$size[0] && !$size[1])
			{
				$size[0] = $width;
				$size[1] = $height;
			}
			elseif (!$size[0])
			{
				$size[0] = round($size[1] * $ratio);
			}
			elseif (!$size[1])
			{
				$size[1] = round($size[0] / $ratio);
			}
		}

		return $size;
	}

}