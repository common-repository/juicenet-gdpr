<?php
	
/**
 * @package JuiceNet GDPR
 */
/*
Plugin Name: JuiceNet GDPR
Plugin URI: https://www.juicenet.it/contatti/
Description: GDPR Plugin, Limited service are available: Direct Email Marketing (DEM), Google Analytics, MailChimp, Mailing list o newsletter, Contact module,  Button +1 and social widget of Google+, social widget of Linkedin, social widget of Stumbleupon, social widget of Facebook, social widget of Pinterest, Widget Google Maps, Widget Video YouTube. Don't block cookies.
Author: Fabiano Capobianchi
Version: 1.1.2
Author URI: https://www.juicenet.it/
License: GPLv2 or later
Text Domain: juicenet-gdpr
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2018 Fabiano Capobianchi.
*/



if(!function_exists('add_action')){ exit; }

/* DEFINISCO URL E PATH */
define( 'JUICENET_GDPR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JUICENET_GDPR_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/* INCLUDO TUTTI I FILE */
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/check-plugin.php'); //OK
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/database.php'); //OK
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/adminpage.php'); //OK
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/extra-field.php'); //OK
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/shortcode.php'); //OK
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/autocomplete.php');
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/modal-view.php');
include_once(JUICENET_GDPR_PLUGIN_DIR.'/include/widgets.php');

/* REGISTRA GLI SCRIPT JS ED I CSS PER IL FRONTEND */
add_action( 'wp_enqueue_scripts', 'juicenet_gdpr_script' );
function juicenet_gdpr_script() {
	wp_enqueue_script( 'jquery' );
    wp_register_script( 'juicenet-gdpr-script', JUICENET_GDPR_PLUGIN_URL.'js/gdpr.js', array( 'jquery' ) );
    wp_enqueue_script( 'juicenet-gdpr-script' );
	wp_register_style( 'juicenet-gdpr-style',  JUICENET_GDPR_PLUGIN_URL.'style.css');
	wp_enqueue_style( 'juicenet-gdpr-style' );
}

/* CONTROLLA LA PRESENZA DEGLI IFRAME E NE MODIFICA IL SRC IN BASE ALLO STATUS DELLA COOKIE CONSENT */
add_filter( 'the_content', 'juicenet_gdpr_filter_the_content' );
function juicenet_gdpr_filter_the_content( $content ) {
	if(!isset($_COOKIE['cookieconsent_status']) || $_COOKIE['cookieconsent_status'] == "deny") {
		$content = preg_replace('~<iframe[^>]*\K(?=src)~i','data-',$content);
	}
	return $content;
}

/* INIZIALIZZA E CARICA LA FUNZIONE PER LA GESTIONE DEI COOKIE DELLA CONSENT SOLUTION */
add_action("wp_head", "juicenet_gdpr_cookie_consent_init");
function juicenet_gdpr_cookie_consent_init(){
	if(get_option('juicenet_gdpr_show_banner') != "on") { return; }
	$gaCode = get_option('juicenet_gdpr_google_analytics_code');
	$serverName = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];
	$domain = strstr($serverName,".");
?>
<link rel="stylesheet" type="text/css" href="<?php echo JUICENET_GDPR_PLUGIN_URL.'css/';?>cookieconsent.min.css" />
<script src="<?php echo JUICENET_GDPR_PLUGIN_URL.'js/';?>cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
	window.cookieconsent.initialise({
		"palette": {
			"popup": { "background": "<?php echo get_option('juicenet_gdpr_popup_color'); ?>" },
			"button": { "background": "<?php echo get_option('juicenet_gdpr_button_color'); ?>" }
		},
		"position": "bottom-left",
		"type": "opt-in",
		"content": {
			header: 'Cookies used on the website!',
			message: '<?php echo get_option('juicenet_gdpr_testo_banner'); ?>',
			dismiss: 'Got it!',
			allow: '<?php echo get_option('juicenet_gdpr_testo_allow'); ?>',
			deny: '<?php echo get_option('juicenet_gdpr_testo_deny'); ?>',
			link: '<?php echo get_option('juicenet_gdpr_testo_link'); ?>',
			href: '<?php echo get_site_url()."/".get_option('juicenet_gdpr_cookie_page'); ?>',
			close: '&#x274c;',
			target: '<?php if( get_option('juicenet_gdpr_link_blank') == "on") echo "_blank"; else { echo "_self"; }?>',	
		},
		"revokeBtn":'<div class="cc-revoke {{classes}}"></div>',	
		"animateRevokable": false,
        onStatusChange: function (status) {
			switch (status){
				case "allow":
					window['ga-disable-<?php echo $gaCode; ?>'] = false;
					if(document.getElementsByTagName("iframe").length){
						var y =  document.getElementsByTagName("iframe");
						for (var i = 0; i < y.length; i++) {
							if(y[i].src.indexOf("recaptcha") === -1){
								y[i].src = y[i].dataset.src;
							}
						}						
					}
				break;
				default:
					window['ga-disable-<?php echo $gaCode; ?>'] = true;
					clearCookie('_gat_gtag_<?php echo $gaCode; ?>','<?php echo $domain; ?>','/');
					clearCookie('_gat','<?php echo $domain; ?>','/');
					clearCookie('_ga','<?php echo $domain; ?>','/');
					clearCookie('_gid','<?php echo $domain; ?>','/');
					if(document.getElementsByTagName("iframe").length){
						var y =  document.getElementsByTagName("iframe");
						for (var i = 0; i < y.length; i++) {
							if(y[i].src.indexOf("recaptcha") === -1){
								y[i].src = "";
							}
						}
					}
				break;	          
			}
        }
	})
	
	function clearCookie(d,b,c){try{if(function(h){var e=document.cookie.split(";"),a="",f="",g="";for(i=0;i<e.length;i++){a=e[i].split("=");f=a[0].replace(/^\s+|\s+$/g,"");if(f==h){if(a.length>1)g=unescape(a[1].replace(/^\s+|\s+$/g,""));return g}}return null}(d)){b=b||document.domain;c=c||"/";document.cookie=d+"=; expires="+new Date+"; domain="+b+"; path="+c}}catch(j){}};	
	
});
</script>
<?php	
}

/* AGGIUNGE ALLE PAGINE IL GOOGLE ANALYTICS GESTITO DALLA COOKIE CONSENT COLUTION */
add_action( 'wp_footer', 'juicenet_gdpr_google_analytics' );
function juicenet_gdpr_google_analytics() {
	$statoTagManager = "";
	$gaCode = get_option('juicenet_gdpr_google_analytics_code');
	if(isset($_COOKIE['cookieconsent_status']) && $_COOKIE['cookieconsent_status'] == "allow") {
		$statoTagManager = "window['ga-disable-".$gaCode."'] = false;";
	}	
	else {
		$statoTagManager = "window['ga-disable-".$gaCode."'] = true;";
	}
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gaCode; ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo $gaCode; ?>');
	<?php echo $statoTagManager; ?>
</script>
<?php    
}

/* FUNZIONE FOOTER NON UTILIZZATA */
/* add_action( 'wp_footer', 'juicenet_gdpr_footer_code' );*/
function juicenet_gdpr_footer_code() {
  
}

/* FUNZIONE CHECK SCRIPT NON UTILIZZATA */
/* add_filter('script_loader_tag', 'juicenet_gdpr_check_script_attribute', 10, 2); */
function juicenet_gdpr_check_script_attribute($tag, $handle) { 
	echo "<pre>"; print_r($tag); echo "</pre>";
	echo "<pre>"; print_r($handle); echo "</pre>";
	return $tag; 
} 




	