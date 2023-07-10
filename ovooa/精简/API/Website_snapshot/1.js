/*
 * desc: get snapshot from url
 * example: phantomjs snap.js http://www.baidu.com baidu.png
 */

var page = require('webpage')
	.create();
var args = require('system')
	.args;
var pageW = args[3];
var pageH = args[4];
page.viewportSize = {
	width: pageW,
	height: pageH
};

var url = args[1];
var filename = args[2];
page.open(url, function(status) {
	if (status !== 'success') {
		console.log('Unable to load ' + url + ' !');
		phantom.exit();
	} else {
		page.evaluate(function() {
			if (getComputedStyle(document.documentElement, null).backgroundColor === 'rgba(0, 0, 0, 0)') {
				console.log('document.documentElement backgroundColor is transparent, setting color to white');
				var style = document.createElement('style'),
				text = document.createTextNode('body { background: #fff }');
				style.setAttribute('type', 'text/css');
				style.appendChild(text);
				document.documentElement.insertBefore(style, document.documentElement.firstChild);
			}
		});
		window.setTimeout(function() {
			page.clipRect = {
				left: 0,
				top: 0,
				width: pageW,
				height: pageH
			};
			page.render(filename);
			console.log('success');
			phantom.exit();
		}, 1000);
	}
});