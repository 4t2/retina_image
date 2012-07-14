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
