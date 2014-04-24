<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package RetinaImage
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Lingo\Retina',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Lingo\Retina\RetinaImage'	=> 'system/modules/retina_image/classes/RetinaImage.php',
	'Lingo\Retina\Controller'	=> 'system/modules/retina_image/classes/Controller.php'
));
