<?php

/**
 * Plugin Name: Gluu oxd login to gluu server
 * Plugin URI: http://gluu.com
 * Description: Allow your users to login with Facebook, Google, Mobile, U2F Token etc using customizable buttons.
 * Version: 1.0
 * Author: gluu
 * Author URI: http://gluu.com
 * License: GPL2
 */


class gluu_OpenID_SSO {



	function __construct() {

		add_action( 'admin_menu', array( $this, 'gluu_openid_menu' ) );
		add_action( 'admin_init',  array( $this, 'gluu_openid_save_settings' ) );

		add_action( 'plugins_loaded',  array( $this, 'oxd_login_widget_text_domain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'oxd_openid_plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'oxd_openid_plugin_settings_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'oxd_openid_plugin_settings_style' ) ,5);

		register_deactivation_hook(__FILE__, array( $this, 'oxd_openid_deactivate'));
		register_activation_hook( __FILE__, array( $this, 'oxd_openid_activate' ) );

		// add social login icons to default login form
		if(get_option('oxd_openid_default_login_enable') == 1){
			add_action( 'login_form', array($this, 'oxd_openid_add_social_login') );
			add_action( 'login_enqueue_scripts', array( $this, 'oxd_custom_login_stylesheet' ) );
		}

		// add social login icons to default registration form
		if(get_option('oxd_openid_default_register_enable') == 1){

			add_action( 'register_form', array($this, 'oxd_openid_add_social_login') );

		}

		//add shortcode
		add_shortcode( 'gluu_social_login', array($this, 'oxd_get_output') );

		// add social login icons to comment form
		if(get_option('oxd_openid_default_comment_enable') == 1 ){
			add_action('comment_form_must_log_in_after', array($this, 'oxd_openid_add_social_login'));
			add_action('comment_form_top', array($this, 'oxd_openid_add_social_login'));
		}

		//add social login to woocommerce
		if(get_option('oxd_openid_woocommerce_login_form') == 1){
			add_action( 'woocommerce_login_form', array($this, 'oxd_openid_add_social_login'));
		}

		if(get_option('oxd_openid_logout_redirection_enable') == 0){
			remove_filter( 'logout_url', 'oxd_openid_redirect_after_logout');
		}

		//custom avatar
		add_filter( 'get_avatar', array( $this, 'oxd_social_login_custom_avatar' ), 10, 5 );

		remove_action( 'admin_notices', array( $this, 'oxd_openid_success_message') );
		remove_action( 'admin_notices', array( $this, 'oxd_openid_error_message') );

		//set default values
		add_option( 'oxd_openid_login_redirect', 'same' );
		add_option( 'oxd_openid_login_theme', 'longbutton' );
		add_option( 'oxd_openid_default_login_enable', '1');
		add_option( 'oxd_openid_login_widget_customize_text', 'Connect with:' );
		add_option( 'oxd_openid_login_button_customize_text', 'Login with' );
		add_option('oxd_login_icon_custom_size','35');
		add_option('oxd_login_icon_space','4');
		add_option('oxd_login_icon_custom_width','200');
		add_option('oxd_login_icon_custom_height','35');
		add_option( 'oxd_openid_login_custom_theme', 'default' );
		add_option( 'oxd_login_icon_custom_color', '2B41FF' );
		add_option( 'oxd_openid_logout_redirection_enable', '0' );
		add_option( 'oxd_openid_logout_redirect', 'currentpage' );
		add_option( 'oxd_openid_auto_register_enable', '1');
		add_option( 'oxd_openid_register_disabled_message', 'Registration is disabled for this website. Please contact the administrator for any queries.' );
		add_option('oxdOpenId_social_login_avatar','1');
		add_option('oxdOpenId_user_attributes','0');
	}

	function oxd_custom_login_stylesheet()
	{

		wp_enqueue_style( 'oxd-wp-style',plugins_url('includes/css/oxd_openid_style.css?version=2.0', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-font-awesome',plugins_url('includes/css/font-awesome.css', __FILE__), false );
	}

	function oxd_openid_plugin_settings_style() {
		wp_enqueue_style( 'oxd_openid_admin_settings_style', plugins_url('includes/css/oxd_openid_style.css?version=2.0', __FILE__));
		wp_enqueue_style( 'oxd_openid_admin_settings_phone_style', plugins_url('includes/css/phone.css', __FILE__));
		wp_enqueue_style( 'oxd-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min-preview.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css', __FILE__), false );
		wp_enqueue_style( 'oxd-wp-font-awesome',plugins_url('includes/css/font-awesome.css', __FILE__), false );
	}

	function oxd_openid_plugin_settings_script() {
		wp_enqueue_script( 'oxd_openid_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ));
		wp_enqueue_script( 'oxd_openid_admin_settings_color_script', plugins_url('includes/jscolor/jscolor.js', __FILE__ ));
		wp_enqueue_script( 'oxd_openid_admin_settings_script', plugins_url('includes/js/settings.js', __FILE__ ), array('jquery'));
		wp_enqueue_script( 'oxd_openid_admin_settings_phone_script', plugins_url('includes/js/bootstrap.min.js', __FILE__ ));
	}

}

new gluu_OpenID_SSO;
?>