<?php if(!function_exists('add_action')) { exit; }

/* FUNZIONE CHE AGGIUNGE I FILE DEI CSS E DEI JS DEL PLUGIN LATO ADMIN */
add_action( 'admin_enqueue_scripts', 'juicenet_gdpr_wp_admin_style' );
function juicenet_gdpr_wp_admin_style() {
	if ($_GET['page'] == 'juicenet_gdpr-options-page') {
		wp_register_style('juicenet_gdpr_wp_admin_css', JUICENET_GDPR_PLUGIN_URL.'css/admin.css', false, '1.0.0' );
		wp_enqueue_style('juicenet_gdpr_wp_admin_css');
	}
}

/* FUNZIONE CHE AGGIUNGE LA VOCE DEL MENU LATO ADMIN */
add_action('admin_menu', 'juicenet_gdpr_create_menu');
function juicenet_gdpr_create_menu() {
    add_menu_page( 'JuiceNet GDPR', 'JuiceNet GDPR', 'administrator', 'juicenet_gdpr-options-page', 'juicenet_gdpr_update_options_form', JUICENET_GDPR_PLUGIN_URL.'img/icon-admin.png' , 2000 );
}

/* FUNZIONE CHE REGISTRA LE VARIABILI DEL PLUGIN */
add_action( 'admin_init', 'juicenet_gdpr_register_settings' );
function juicenet_gdpr_register_settings() { // whitelist options	
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_nome_sito' );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_titolare_sito'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_email_sito'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_cookie_page'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_privacy_page'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_show_banner'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_google_analytics_code'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_link_blank'  ); 
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_testo_link'  );  
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_testo_deny'  ); 	
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_testo_allow'  );  
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_testo_banner'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_popup_color'  );
	register_setting( 'juicenet_gdpr_settings_group', 'juicenet_gdpr_button_color'  );

}

/* FORM DELLA PAGINA DI AMMINISTRAZIONE*/
function juicenet_gdpr_update_options_form() {
?> 
<div class="wrap">

	<h1>GDPR WEBSITE DATA</h1>
	<form method="post" action="options.php" class="juiceGDPR"> 
		<?php settings_fields( 'juicenet_gdpr_settings_group' );?>
		<?php 
			$testo_banner = get_option( 'juicenet_gdpr_testo_banner' )?get_option( 'juicenet_gdpr_testo_banner' ): "Questo sito o gli strumenti terzi da questo utilizzati si avvalgono di cookie necessari al funzionamento ed utili alle finalità illustrate nella cookie policy. Se vuoi saperne di più o negare il consenso a tutti o ad alcuni cookie, consulta la:";
			
			$testo_allow = get_option( 'juicenet_gdpr_testo_allow' )?get_option( 'juicenet_gdpr_testo_allow' ): "Accetta";
			$testo_deny = get_option( 'juicenet_gdpr_testo_deny' )?get_option( 'juicenet_gdpr_testo_deny' ): "Rifiuta";
			$testo_link = get_option( 'juicenet_gdpr_testo_link' )?get_option( 'juicenet_gdpr_testo_link' ): "Leggi tutto";
			$popup_color = get_option( 'juicenet_gdpr_popup_color' )?get_option( 'juicenet_gdpr_popup_color' ): "#252e39";
			$button_color = get_option( 'juicenet_gdpr_button_color' )?get_option( 'juicenet_gdpr_button_color' ): "#14a7d0";
		?>
		<table class="form-table">
			<tbody>
		       	<tr valign="top">
		       		<td width="20%"><label>SITE NAME</label></td><td width="80%"><input name="juicenet_gdpr_nome_sito" type="text" id="nome_sito" value="<?php echo get_option('juicenet_gdpr_nome_sito'); ?>" /></td>
				</tr>
		       	<tr valign="top">
		       		<td width="20%"><label>DATA OWNER</label></td><td width="80%"><input name="juicenet_gdpr_titolare_sito" type="text" id="titolare_sito" value="<?php echo get_option( 'juicenet_gdpr_titolare_sito' ); ?>" /></td>
				</tr>
		       	<tr valign="top">
		       		<td width="20%"><label>EMAIL</label></td><td width="80%"><input name="juicenet_gdpr_email_sito" type="text" id="email_sito" value="<?php echo get_option( 'juicenet_gdpr_email_sito' ); ?>" /></td>
				</tr>
		       	<tr valign="top">
		       		<td width="20%"><label>GOOGLE ANALYTICS CODE</label></td><td width="80%"><input name="juicenet_gdpr_google_analytics_code" type="text" id="email_sito" value="<?php echo get_option( 'juicenet_gdpr_google_analytics_code' ); ?>" />
		       		Remember to deactivate other google Analytics plugin</td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>BANNER TEXT</label></td><td width="80%"><input name="juicenet_gdpr_testo_banner" type="text" id="testo_banner" value="<?php echo  $testo_banner; ?>" /></td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>ALLOW BUTTON</label></td><td width="80%"><input name="juicenet_gdpr_testo_allow" type="text" id="testo_allow" value="<?php echo $testo_allow; ?>" /></td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>DENY BUTTON</label></td><td width="80%"><input name="juicenet_gdpr_testo_deny" type="text" id="testo_deny" value="<?php echo $testo_deny; ?>" /></td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>LINK BUTTON</label></td><td width="80%"><input name="juicenet_gdpr_testo_link" type="text" id="testo_link" value="<?php echo $testo_link; ?>" /></td>
				</tr>				 				
		       	<tr valign="top">
		       		<td width="20%"><label>POPUP COLOR</label></td><td width="80%"><input name="juicenet_gdpr_popup_color" type="text" id="testo_policy" value="<?php echo $popup_color; ?>" /></td>
				</tr>
		       	<tr valign="top">
		       		<td width="20%"><label>POPUP BUTTON COLOR</label></td><td width="80%"><input name="juicenet_gdpr_button_color" type="text" id="testo_policy" value="<?php echo $button_color; ?>" /></td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>OPEN PAGE IN BLANK</label></td><td width="80%"><input name="juicenet_gdpr_link_blank" type="checkbox" id="link_blank" <?php if( get_option( 'juicenet_gdpr_link_blank' ) == "on") echo "checked"; ?> /> 
		       		If checked open cookie policy page in blank.</td>
				</tr>				
		       	<tr valign="top">
		       		<td width="20%"><label>SHOW BANNER</label></td><td width="80%"><input name="juicenet_gdpr_show_banner" type="checkbox" id="show_banner" <?php if( get_option( 'juicenet_gdpr_show_banner' ) == "on") echo "checked"; ?> /> 
		       		If checked show banner on website.</td>
				</tr>
				<tr valign="top">
		       		<td width="20%"><label>PRIVACY PAGE PERMALINK</label></td><td width="80%"><input name="juicenet_gdpr_privacy_page" type="text" id="privacy_page" value="<?php echo get_option( 'juicenet_gdpr_privacy_page' ); ?>" /></td>
				</tr>
				<tr valign="top">
		       		<td width="20%"><label>COOKIE PAGE PERMALINK</label></td><td width="80%"><input name="juicenet_gdpr_cookie_page" type="text" id="cookie_page" value="<?php echo get_option( 'juicenet_gdpr_cookie_page' ); ?>" /></td>
				</tr>
			</tbody>
	    </table>
		<?php submit_button(); ?>    
	</form>
   
</div> 
<?php

if(strlen(get_option('juicenet_gdpr_nome_sito')) <=0 || strlen(get_option('juicenet_gdpr_titolare_sito')) <=0 || strlen(get_option('juicenet_gdpr_email_sito')) <=0 || strlen(get_option('juicenet_gdpr_cookie_page')) <=0  || strlen(get_option('juicenet_gdpr_privacy_page')) <=0 ) {
	echo "Please compile all field for show your page shortcode.";
} else {
	echo "Privacy policy page shortcode: [JGDPR_PRIVACY_POLICY]<br>";
	echo "Cookie policy page shortcode: [JGDPR_COOKIE_POLICY]<br>";
	echo "Remember to add an acceptance flag to your contact form for the consent to the processing of personal data.<br>";
}	
	
	
}
