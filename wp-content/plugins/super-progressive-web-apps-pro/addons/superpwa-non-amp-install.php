<?php
if ( ! defined( 'ABSPATH' ) || !function_exists('superpwa_get_settings') ) exit;

$superpwa_settings = superpwa_get_settings();
$superpwa_cta_settings = get_option( 'superpwa_call_to_action_settings');

$iOS_chrome_message = ( isset($superpwa_cta_settings['ios_chrome_msg']) && !empty( $superpwa_cta_settings['ios_chrome_msg'] ) ) ? $superpwa_cta_settings['ios_chrome_msg'] : 'Currently PWA is not supported in iOS Chrome So follow below steps:';

$iOS_chrome_hscrn = ( isset($superpwa_cta_settings['ios_chrome_hscrn']) && !empty( $superpwa_cta_settings['ios_chrome_hscrn'] ) ) ? $superpwa_cta_settings['ios_chrome_hscrn'] : 'Add to Home Screen';

function superpwa_detect_user_agent( ){
      $user_agent_name ='others';           
      if     (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') || strpos($_SERVER['HTTP_USER_AGENT'], 'OPR/')) $user_agent_name = 'opera';
      elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge'))    $user_agent_name = 'edge';            
      elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) $user_agent_name ='firefox';
      elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) $user_agent_name = 'internet_explorer';                        
      elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPod')) $user_agent_name = 'ipod';
      elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) $user_agent_name = 'iphone';
      elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPad')) $user_agent_name = 'ipad';
      elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Android')) $user_agent_name = 'android';
      elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'webOS')) $user_agent_name = 'webos';
      elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))  $user_agent_name = 'chrome';
      elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari'))  $user_agent_name = 'safari';
            
  return $user_agent_name;
}

?> 

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=2,user-scalable=yes">
  <title><?php echo esc_attr($superpwa_settings['app_short_name']); ?></title>
  <?php do_action( 'wp_head'); ?>
<style>
*,*:after,*:before{
  box-sizing: border-box;
}
body{
  align-items: center;
  justify-content: center;
  display: flex;
  font-family: Segoe UI;
  color: #333;
  font-size: 16px;
  line-height: 1.5;
}
.superpwa-cta-ov{
    background: #37474F;
    -webkit-background: #37474F;
    cursor: default;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: scroll;
}    
.superpwa-wrap{
    position: absolute;
    padding: 0px 15px 10px;
    max-width: 500px;
    border-radius: 4px;
    left: 20%;
    right: 20%;
    background: #fff;
    max-height: 80vh;
    z-index: 1;
    margin:0 auto;
    text-align: center;
    box-shadow: 0px 1px 3px 1px #100f0f;
    top: 10%;
    display: flex;
    display: -webkit-box;
    display: -webkit-flex;
    height:80%;
}
.superpwa-warp-align{
    display: flex;
    display: -webkit-box;
    display: -webkit-flex;
    flex-direction: column;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    align-items: center;
    -webkit-box-align: center;
   -webkit-align-items: center;
    justify-content: center;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    width:100%;
    font-family: sans-serif;
}
.superpwa-wrap .icon_img{
    width: 150px;
    border-radius: 15px;
}

.superpwa-wrap .superpwa-btn img{
    width: 60px;
    border-radius: 15px;
}
.superpwa-wrap h2{
  font-size: 28px;
  font-weight: bold;
  letter-spacing: 0.5px;
  line-height: 1.4;
  color: #111;
  margin: 15px 0px 10px 0px;
}
.superpwa-wrap p{
    font-size: 18px;
    color: #222;
    line-height: 1.5;
    display: inline-block;
    margin: 0;
}
.superpwa-btn {
  width: 100%;
  display: inline-block;
  text-align: center;
  margin-top: 25px;
}
.superpwa-btn small{
  display: inline-flex;
  display: -webkit-inline-flex;
  align-items: center;
  -webkit-box-align: center;
  -webkit-align-items: center;
  font-size: 13px;
}
.superpwa-btn small img{  
    padding: 0px 5px 0px 5px;
    cursor: pointer;
    width: 34px;
    height: 24px;
    position: relative;
    top: 5px;
    margin-left: -5px;
}
.superpwa-wrap .close{
  position: absolute;
  right: -1px;
  top: -40px;
  color: #fff;
  text-decoration: none;
  font-size: 30px;
  line-height: 1;
}
.superpwa-btn span{
 display:inline-block;    
 margin-bottom:20px;    
}

/** safari browser CSS **/
.superpwa-btn.superpwa-add-via-class h4{
  font-size: 16px;
  line-height:1.4;
  color:#111;
  margin: 0px 0px 15px;
}
.superpwa-wrap .superpwa-btn.superpwa-add-via-class ol{
  margin: 0;
  padding:0px 0px 0px 15px;
  text-align: left;
}
.superpwa-wrap .superpwa-btn.superpwa-add-via-class ol li img{
  width: 16px;
  border-radius: 0;
  margin-right: 5px;
  position:relative;
  top:3px;
}
.superpwa-btn.superpwa-add-via-class ol li .ov-txt{
  font-size:15px;
  color:#222;
}
.copytext-msg{
  font-size: 14px;
  padding-left: 5px;
}
.superpwa-btn.superpwa-add-via-class ol li {
  list-style-type: none;
  position: relative;
  counter-increment: step-counter;
  width:100%;
  margin-bottom: 10px;
  display: inline-block;
  padding-left:20px;
}
.superpwa-btn.superpwa-add-via-class ol li::before {
  content: counter(step-counter);
  font-size: 14px;
  color: #666;
  position: absolute;
  left: 0px;
  line-height: 1;
  top: 4px;
}
.superpwa-butn{
  background-color: <?php echo isset($superpwa_cta_settings['bar_btn_bg_color']) ? $superpwa_cta_settings['bar_btn_bg_color'] : '#2b2bff'; ?>;
  color: <?php echo isset($superpwa_cta_settings['bar_btn_text_color']) ? $superpwa_cta_settings['bar_btn_text_color'] : '#ffffff'; ?>;
}


@media(max-width:768px){
  
}

@media(max-width:500px){
  .superpwa-wrap{
    left: 10%;
    right: 10%;
  }
  

} 

</style>
<?php 
$localize = array(
      'url' => parse_url( superpwa_sw( 'src' ), PHP_URL_PATH ),
      'disable_addtohome' => isset($superpwa_settings['disable_add_to_home'])? $superpwa_settings['disable_add_to_home'] : 0,
      'enableOnDesktop'=> false,
    );
  $localize = apply_filters('superpwa_sw_localize_data', $localize);
  $ServiceWorkerUrl = $localize['url'];
  $disable_addtohome = $localize['disable_addtohome'];
  $enableOnDesktop = $localize['enableOnDesktop'];

?>

<script>
  var swsource,disable_addtohome,enableOnDesktop;
   swsource = '<?php echo esc_url($ServiceWorkerUrl); ?>';
   disable_addtohome = '<?php echo $disable_addtohome; ?>';
   enableOnDesktop = '<?php echo $enableOnDesktop; ?>';

    if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
  navigator.serviceWorker.register(swsource)
  .then(function(registration) { console.log('SuperPWA service worker ready'); registration.update(); })
  .catch(function(error) { console.log('Registration failed with ' + error); });


  var deferredPrompt;
  window.addEventListener('beforeinstallprompt', function(e){
    deferredPrompt = e;
    if(deferredPrompt != null || deferredPrompt != undefined){
      if(disable_addtohome==1){
        deferredPrompt.preventDefault();
      }

      var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
      if(a2hsBanner.length){
        deferredPrompt.preventDefault();
        //Disable on desktop
        if(enableOnDesktop!=1 && !window.mobileCheck()){return ;}
        if(typeof super_check_bar_closed_or_not == 'function' && !super_check_bar_closed_or_not()){return ;}
        for (var i = 0; i < a2hsBanner.length; i++) {
          var showbanner = a2hsBanner[i].getAttribute("data-show");
          a2hsBanner[i].style.display="flex";
        }
      }
    }
  })

  window.addEventListener('appinstalled', function(evt){
    var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
    if(a2hsBanner.length){
      for (var i = 0; i < a2hsBanner.length; i++) {
        var showbanner = a2hsBanner[i].getAttribute("data-show");
        a2hsBanner[i].style.display="none";
      }
    }
  });
  
  var a2hsviaClass = document.getElementsByClassName("superpwa-add-via-class");
    if(a2hsviaClass !== null){
        for (var i = 0; i < a2hsviaClass.length; i++) {
          a2hsviaClass[i].addEventListener("click", addToHome); 
      }
    }

    function addToHome(){
    if(!deferredPrompt){return ;}
    deferredPrompt.prompt(); 
    deferredPrompt.userChoice.then(function(choiceResult) {
      if (choiceResult.outcome === "accepted") {
        var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
        if(a2hsBanner){
          for (var i = 0; i < a2hsBanner.length; i++) {
            var showbanner = a2hsBanner[i].getAttribute("data-show");
            a2hsBanner[i].style.display="none";
          }
        }//a2hsBanner if
        console.log("User accepted the prompt");
      }else{
        console.log("User dismissed the prompt");
      }
      deferredPrompt = null;
    });
  } // function closed addToHome


  });
}
window.mobileCheck = function() {
    let check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  };

    function superpwaCloseBanner(){
        window.close();
    }
    
    function copy_password(copyText) {
      var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);
      if (isiOSDevice) {
        var textArea = document.createElement("textarea");
        textArea.value = copyText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
        document.getElementById("copytext-msg").innerText="Copied";
      }
    }
</script>

</head>
<body>       
<div class="superpwa-cta-ov">
  <div class="superpwa-wrap">
    <div class="superpwa-warp-align">
      <img class="icon_img" src="<?php echo esc_url($superpwa_settings['icon']); ?>">
      <h2><?php echo esc_attr($superpwa_settings['app_short_name']); ?></h2>
      <p><?php echo esc_attr($superpwa_settings['description']); ?></p>
      <div class="superpwa-btn superpwa-add-via-class">
          
          <?php
           if( in_array(superpwa_detect_user_agent(), array('safari', 'iphone', 'ipod', 'ipad')) ) { ?>
            <ol >
                <span><?php echo esc_html__($iOS_chrome_message,'super-progressive-web-apps-pro'); ?></span>
                <h4><?php echo esc_html__($iOS_chrome_hscrn,'super-progressive-web-apps-pro'); ?></h4>
                <li><div style="cursor: pointer;" onclick="copy_password('<?php echo home_url()."?iospopup=1"; ?>')">
                    <img src="<?php echo SUPERPWA_PRO_PATH_SRC ?>/assets/img/copy-content.png">
                    <p class="ov-txt">Copy the URL</p>
                    <em id="copytext-msg"></em></div></li>
                <li>
                  <img src="<?php echo SUPERPWA_PRO_PATH_SRC ?>/assets/img/safari.png">
                  <p class="ov-txt">Open in safari</p>
                </li>
                <li>
                  <img src="<?php echo SUPERPWA_PRO_PATH_SRC ?>/assets/img/plus-sign-in-circle.png">
                  <p class="ov-txt"><?php esc_html_e("Add to Home","super-progressive-web-apps-pro"); ?></p>
                </li>
              </ol>
          <?php }else{ ?>
              <span class="superpwa-butn" style="padding: 14px 20px;"><?php echo isset($superpwa_cta_settings['add_to_home_btn_text']) && !empty($superpwa_cta_settings['add_to_home_btn_text'])? esc_html($superpwa_cta_settings['add_to_home_btn_text']) : esc_html__("Add to Home","super-progressive-web-apps-pro"); ?></span>
          <?php } ?>
            
      </div>
      <a onclick="superpwaCloseBanner()" class="close">&times;</a>
    </div>
  </div>
</div>

</body>
</html>
