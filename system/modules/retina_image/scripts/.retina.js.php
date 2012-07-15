<?php
	header('Content-Type: text/javascript; charset=UTF-8');
	
	define('TL_ROOT', strstr(__FILE__, '/system/', true));

	$arrFiles = array();
	
	$dh  = opendir(TL_ROOT.'/system/html');

	while (false !== ($filename = readdir($dh)))
	{
		if (($pos = strpos($filename, '@2x')) !== FALSE)
		{
	    	$arrFiles[] = 'system/html/'.substr($filename, 0, $pos).substr($filename, $pos+3);
	    }
	}
?>

window.onDomReady = initReady;

// Initialize event depending on browser
function initReady(fn)
{
	//W3C-compliant browser
	if (document.addEventListener)
	{
		document.addEventListener("DOMContentLoaded", fn, false);
	}
	else // IE
	{
		document.onreadystatechange = function(){readyState(fn)}
	}
}

//IE execute function
function readyState(func)
{
	// DOM is ready
	if(document.readyState == "interactive" || document.readyState == "complete")
	{
		func();
	}
}

//window.onload = function()
window.onDomReady(function()
{
	var root = (typeof exports == 'undefined' ? window : exports);
	
	if (root.devicePixelRatio > 1)
	{
		var retinaImages = ["<?php echo implode('","', $arrFiles); ?>"];
		var i, len, src, images = document.getElementsByTagName("img");

		for (i = 0, len = images.length; i < len; i++)
		{
			_src = images[i].getAttribute('src');
			_class = images[i].getAttribute('class');

			if (retinaImages.indexOf(_src) > -1 || (_class != null && _class != "" && _class.indexOf("at2x") > -1))
			{
				images[i].setAttribute('src', _src.replace(/(\.[a-zA-Z]+)$/, '@2x$1'));
			}
		}
	}
});
