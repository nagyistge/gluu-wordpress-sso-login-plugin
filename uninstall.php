<?php
	//delete all stored key-value pairs
	delete_option('oxd_config');
	delete_option('oxd_id');
	delete_option('oxd_openid_host_name');
	delete_option('oxd_openid_new_registration');
	delete_option('oxd_openid_admin_email');
	delete_option('oxd_openid_message');
	delete_option('oxd_openid_google_enable');
	delete_option('oxd_openid_facebook_enable');
	delete_option('oxd_openid_linkedin_enable');
	delete_option('oxd_openid_twitter_enable');
	delete_option('oxd_openid_Duo_enable');
	delete_option('oxd_openid_U2F_enable');
	delete_option('oxd_openid_default_login_enable');
	delete_option('oxd_openid_default_register_enable');
	delete_option('oxd_openid_default_comment_enable');
	delete_option('oxd_openid_woocommerce_login_form');
	delete_option('oxd_openid_login_redirect');
	delete_option('oxd_openid_login_redirect_url');
	delete_option('oxdOpenId_gluu_login_avatar');
	delete_option('oxdOpenId_user_attributes');

	delete_option( 'oxd_openid_login_theme' );
	delete_option( 'oxd_openid_login_button_customize_text');
	delete_option('oxd_openid_Basic_enable');

	delete_option('oxd_login_icon_custom_size');
	delete_option('oxd_login_icon_space' );
	delete_option('oxd_login_icon_custom_width' );
	delete_option('oxd_login_icon_custom_height' );
	delete_option('oxd_openid_login_custom_theme' );
	delete_option( 'oxd_login_icon_custom_color');

	delete_option( 'oxd_openid_message');
	delete_option('oxd_openid_logout_redirect');
	delete_option('oxd_openid_logout_redirection_enable');
	delete_option('oxd_openid_logout_redirect_url');
?>