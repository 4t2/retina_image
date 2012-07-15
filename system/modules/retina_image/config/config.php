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


$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('RetinaImage', 'parseFrontendTemplateHook');
#$GLOBALS['TL_HOOKS']['getContentElement'][] = array('RetinaImage', 'getContentElementHook');
$GLOBALS['TL_HOOKS']['getImage'][] = array('RetinaImage', 'getImageHook');


if (TL_MODE == 'FE')
{
	if (!is_array($GLOBALS['TL_JAVASCRIPT']))
	{
		$GLOBALS['TL_JAVASCRIPT'] = array();
	}
	$GLOBALS['TL_JAVASCRIPT'][] = strstr(dirname(__DIR__), 'system/modules').'/scripts/retina.js';
}