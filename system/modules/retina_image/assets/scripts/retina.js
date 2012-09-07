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
		document.onreadystatechange = function() {readyState(fn)}
	}
}

//IE execute function
function readyState(func)
{
	// DOM is ready
	if (document.readyState == "interactive" || document.readyState == "complete")
	{
		func();
	}
}

window.onDomReady(function()
{
	var root = (typeof exports == 'undefined' ? window : exports);

	if (root.devicePixelRatio > 1)
	{
		if (document.getElementsByClassName)
		{
			var i, len, el = document.getElementsByClassName("at2x");

			for (i = 0, len = el.length; i < len; i++)
			{
				_src = el[i].getAttribute('src');

				if (_src != null && _src != "")
				{
					el[i].setAttribute('src', _src.replace(/(\.[a-z]+)$/i, '@2x$1'));
				}
				else if (el[i].style.backgroundImage != null && el[i].style.backgroundImage != "" && el[i].style.backgroundSize != null && el[i].style.backgroundSize != "")
				{
					el[i].style.backgroundImage = el[i].style.backgroundImage.replace(/(\.[a-z]+\)?)$/i, '@2x$1');
				}
				else if (getStyle(el[i], "background-image") != "" && getStyle(el[i], "background-size") != "")
				{
					el[i].style.backgroundImage = getStyle(el[i], "background-image").replace(/(\.[a-z]+\)?)$/i, '@2x$1');
				}
			}
		}
		else
		{
			var i, len, images = document.getElementsByTagName("img");
	
			for (i = 0, len = images.length; i < len; i++)
			{
				_src = images[i].getAttribute('src');
				_class = images[i].getAttribute('class');
	
				if ((_class != null && _class != "" && _class.indexOf("at2x") > -1))
				{
					images[i].setAttribute('src', _src.replace(/(\.[a-zA-Z]+)$/, '@2x$1'));
				}
			}
		}
	}
});

// http://stackoverflow.com/questions/11799410/javascript-only-recognizising-inline-style-and-not-style-set-in-head
function getStyle(elem, style)
{
	var a = window.getComputedStyle, b = elem.currentStyle;

	if (a)
	{
		return a(elem).getPropertyValue(style);
	}
	else if (b)
	{
		return b[style];
	}
}
