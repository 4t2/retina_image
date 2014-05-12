<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * PHP version 5
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    RetinaImage
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/* Hooks */
if (version_compare(VERSION, '3.1', '<'))
{
	$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] = array('RetinaImage', 'outputFrontendTemplateHook');
}
// see https://github.com/4t2/retina_image/issues/1
else
{
	$GLOBALS['TL_HOOKS']['modifyFrontendPage'][] = array('RetinaImage', 'modifyFrontendPageHook');
}

$GLOBALS['TL_HOOKS']['getImage'][] = array('RetinaImage', 'getImageHook');

/* InsertTags */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('RetinaImage', 'replaceInsertTagsHook');


if (TL_MODE == 'FE')
{
	if (!is_array($GLOBALS['TL_JAVASCRIPT']))
	{
		$GLOBALS['TL_JAVASCRIPT'] = array();
	}

	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/retina_image/assets/scripts/retina.js|static';

	# PHP 5.3 only
	#$GLOBALS['TL_JAVASCRIPT'][] = strstr(dirname(__DIR__), 'system/modules').'/scripts/retina.js';
}