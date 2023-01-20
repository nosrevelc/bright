var spwapbrowserclientDetector  = function (){
	var browserClient = '';

	// Opera 8.0+
	var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

	// Firefox 1.0+
	var isFirefox = typeof InstallTrigger !== 'undefined';

	// Safari 3.0+ "[object HTMLElementConstructor]" 
	var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));

	// Internet Explorer 6-11
	var isIE = /*@cc_on!@*/false || !!document.documentMode;

	// Edge 20+
	var isEdge = !isIE && !!window.StyleMedia;

	// Chrome 1 - 71
	var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

	// Blink engine detection
	var isBlink = (isChrome || isOpera) && !!window.CSS;


	if(navigator.userAgent.match('CriOS')){
		browserClient = 'Chrome ios';
		return browserClient;
	}
	var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);
	var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
	if (isSafari && iOS) {
		browserClient = 'Safari ios';
		return browserClient;
	} else if(isSafari) {
		browserClient = 'Safari';
		return browserClient;
	}else if(isFirefox){
		browserClient = 'Firefox';
		return browserClient;
	}else if(isChrome){
		browserClient = 'Chrome';
		return browserClient;
	}else if(isOpera){
		browserClient = 'Opera';
		return browserClient;
	}else if(isIE ){
		browserClient = 'IE';
		return browserClient;
	}else if(isEdge ){
		browserClient = 'Edge';
		return browserClient;
	}else if( isBlink ){
		browserClient = 'Blink';
		return browserClient;
	}


}

var spwapGetOS = function() {
  var userAgent = window.navigator.userAgent,
      platform = window.navigator.platform,
      macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
      windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
      iosPlatforms = ['iPhone', 'iPad', 'iPod'],
      os = null;

  if (macosPlatforms.indexOf(platform) !== -1) {
    os = 'Mac OS';
  } else if (iosPlatforms.indexOf(platform) !== -1) {
    os = 'iOS';
  } else if (windowsPlatforms.indexOf(platform) !== -1) {
    os = 'Windows';
  } else if (/Android/.test(userAgent)) {
    os = 'Android';
  } else if (!os && /Linux/.test(platform)) {
    os = 'Linux';
  }

  return os;
}

var addReportdata = function(params){
	var http = new XMLHttpRequest();
	var url = SuperPwaAnalyticsData.ajax_url;
	var params = params;
	http.open('POST', url, true);

	//Send the proper header information along with the request
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	http.onreadystatechange = function() {//Call a function when the state changes.
	    if(http.readyState == 4 && http.status == 200) {
	        console.log(http.responseText);
	    }
	}
	http.send(params);
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}



//(function($){
	if (window.matchMedia('(display-mode: standalone)').matches) {
		console.log('display-mode is standalone');
		var callCounting = true;
		if(getCookie('superpwa_app_installed')=='hurray'){
			callCounting = false;
		}else if( localStorage.getItem("superpwa_app_installed")=='hurray' ){
			callCounting = false;
		}
		if( callCounting ){
			var networkclient = spwapbrowserclientDetector();
			var Osname = spwapGetOS();
			var params = 'networkclient='+networkclient+'&os='+Osname+'&action=superpwa_data_analytics_action_handle&csrf='+SuperPwaAnalyticsData.nonce_csrf;
			addReportdata(params);
			localStorage.setItem("superpwa_app_installed", "hurray");
			document.cookie = "superpwa_app_installed=hurray;";
		}
	}
	if (window.navigator.standalone === true) {
		console.log('IOS Sfari runs display-mode is standalone');
		var callCounting = true;
		if(getCookie('superpwa_app_installed')=='hurray'){
			callCounting = false;
		}else if( localStorage.getItem("superpwa_app_installed")=='hurray' ){
			callCounting = false;
		}
		if( callCounting ){
		  	var networkclient = spwapbrowserclientDetector();
			var Osname = spwapGetOS();
			var params = 'networkclient='+networkclient+'&os='+Osname+'&action=superpwa_data_analytics_action_handle&csrf='+SuperPwaAnalyticsData.nonce_csrf;
			addReportdata(params);
			localStorage.setItem("superpwa_app_installed", "hurray");
			document.cookie = "superpwa_app_installed=hurray;";
		}
	}
//});

