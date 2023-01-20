(function() {
	var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);
	var iOS = /iPad|iPhone|iPod|Macintosh/.test(navigator.userAgent) && !window.MSStream;
	var a2hsviashortcode = document.getElementById("superpwa-sticky-bar");
	

	var hideoniOS ,iOSOnly;
	hideoniOS = iOSOnly = true;

      if(superpwa_cta.a2h_sticky_hideon_ios == 1){
      	hideoniOS = false;
      }

	  if(iOS !== true && superpwa_cta.a2h_sticky_show_only_on_ios == 1){
	  	a2hsviashortcode.style="display: none !important;"
		iOSOnly = false;
	  }
      
    if(a2hsviashortcode !== null && super_check_bar_closed_or_not() && superpwa_cta.a2h_banner_without_scroll_cta == 1 && iOSOnly){
		var prestyle_onload = a2hsviashortcode.getAttribute('data-style');
		 a2hsviashortcode.style="display: none;"
		var cookie = document.cookie;
    	if(cookie != 'hidecta=yes'){
			 if(superpwa_cta.a2h_banner_delay_cta == 1){
				var delayTime = superpwa_cta.a2h_banner_delay_sec_cta * 1000;
					setTimeout(function(){ a2hsviashortcode.style="display: flex;"+prestyle_onload;}, delayTime);
			 }else{
				 a2hsviashortcode.style="display: flex;"+prestyle_onload;
			 }
		}
    } 

		if(superpwa_cta.a2h_banner_delay_cta != 1 && superpwa_cta.a2h_banner_without_scroll_cta != 1) {
        	window.addEventListener('scroll', function(){
        		a2hsviashortcode.style="display: none !important;";
        	});  
          }

	if (isSafari && iOS && window.navigator.standalone !== true && hideoniOS) {
		console.log("You are using Safari on iOS!");
		if(a2hsviashortcode !== null && super_check_bar_closed_or_not()){
			var prestyle = a2hsviashortcode.getAttribute('data-style');
			a2hsviashortcode.style="display: flex;"+prestyle; 

			a2hsviashortcode.addEventListener('click', function(){
				document.getElementById("superpwa-iossafari-a2h-banner").style.display = 'block';
				document.getElementById("superpwa-iossafari-a2h-banner").classList.add('iossafari-buzz');
				setTimeout(function(){ document.getElementById("superpwa-iossafari-a2h-banner").classList.remove('iossafari-buzz'); }, 1000);
			});
		}

		//superpwa-add-via-class
		var a2hsviaClass = document.getElementsByClassName("superpwa-add-via-class");
		if(a2hsviaClass !== null && iOSOnly){
			for (var i = 0; i < a2hsviaClass.length; i++) {
			  a2hsviaClass[i].addEventListener("click", function(){
				document.getElementById("superpwa-iossafari-a2h-banner").style.display = 'block';
				document.getElementById("superpwa-iossafari-a2h-banner").classList.add('iossafari-buzz');
				setTimeout(function(){ document.getElementById("superpwa-iossafari-a2h-banner").classList.remove('iossafari-buzz'); }, 1000);
			});
		  }
		}
		

		if(window.innerHeight > window.innerWidth && window.matchMedia('(display-mode: fullscreen)').matches){
		    document.getElementsByTagName("html")[0].style.marginTop = "-5px";
		}

	} else if(isSafari) {
		console.log("You are using Safari.");
	}


	/*When in iOS chrome*/
	if(navigator.userAgent.match('CriOS') && window.navigator.standalone !== true && hideoniOS ){
		if(a2hsviashortcode !== null && super_check_bar_closed_or_not()){
			var prestyle = a2hsviashortcode.getAttribute('data-style');
			a2hsviashortcode.style="display: flex;"+prestyle; 
		}
	}
	//For all other browsers
	if (window.matchMedia('(display-mode: fullscreen)').matches || (navigator.userAgent.match('CriOS') && window.navigator.standalone === true)){
		if(a2hsviashortcode !== null  && super_check_bar_closed_or_not()){
			a2hsviashortcode.style="display: none;"; 
		}
	}

	/**Main banner close**/
	var crossButton = document.getElementsByClassName("superpwa_add_home_close")
	for(i=0; i<crossButton.length; i++ ){
		crossButton[i].addEventListener("click", function(){
			this.parentNode.style.display='none';
			document.cookie = "superpwa_close_banner="+new Date();
			document.getElementsByTagName("html")[0].style.marginTop = 0;
		})
	}
	/**iOS message banner close**/
	var iosMessageBarClose = document.querySelectorAll(".superpwa_ios_message_close");
	if(iosMessageBarClose !== null){
		for (var i = 0; i < iosMessageBarClose.length; i++) {
	    iosMessageBarClose[i].addEventListener("click", safaripopuphide); 
		}
	}
})();
function super_check_bar_closed_or_not(){
	var closedTime = super_pwa_read_cookie_cta("superpwa_close_banner")
    if(closedTime){
      var today = new Date();
      var closedTime = new Date(closedTime);
      var diffMs = (today-closedTime);
      var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
      if(diffMs){//diffMins<4
        return false;
      }
    }
    return true;
}
/**
 * Read the cookie values by given name
 * @param  string name To find the name
 * @return string|null   either return value of cookie or null
 */
function super_pwa_read_cookie_cta(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==" ") c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
/**
 * Close iOS A2H Message bar
 * @param  event
 */
function safaripopuphide(e){
	e.preventDefault();
	document.getElementById("superpwa-iossafari-a2h-banner").style.display = 'none';
}