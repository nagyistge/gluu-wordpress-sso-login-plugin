<?php

/**
 * Plugin Name: Gluu oxd login to gluu server
 * Plugin URI: http://gluu.com
 * Description: Allow your users to login with Facebook, Google, Mobile, U2F Token etc using customizable buttons.
 * Version: 1.0
 * Author: Vlad Karapetyan
 * Author URI: http://gluu.com
 * License: GPL2
 */

require('gluu_openid_sso_settings_page.php');
include_once dirname( __FILE__ ) . '/class-oxd-openid-login-widget.php';
include_once dirname( __FILE__ ) . '/oxd-rp/Register_site.php';


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

	function oxd_openid_deactivate() {
		delete_option('oxd_openid_host_name');
		delete_option('oxd_openid_new_registration');
		delete_option('oxd_openid_message');
	}

	function oxd_openid_activate() {
		add_option('Oxd_Activated_Plugin','Plugin-Slug');
	}


	function oxd_openid_add_social_login(){

		if(!is_user_logged_in() && is_oxd_registered()){
			$oxd_login_widget = new oxd_openid_login_wid();
			$oxd_login_widget->openidloginForm();
		}
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

	function oxd_openid_success_message() {
		$message = get_option('oxd_openid_message'); ?>

		<script>

			jQuery(document).ready(function() {
				var message = "<?php echo $message; ?>";
				jQuery('#oxd_openid_msgs').append("<div class='error notice is-dismissible oxd_openid_error_container'> <p class='oxd_openid_msgs'>" + message + "</p></div>");
			});
		</script>
	<?php }

	function oxd_openid_error_message() {
		$message = get_option('oxd_openid_message'); ?>
		<script>
			jQuery(document).ready(function() {
				var message = "<?php echo $message; ?>";
				jQuery('#oxd_openid_msgs').append("<div class='updated notice is-dismissible oxd_openid_success_container'> <p class='oxd_openid_msgs'>" + message + "</p></div>");
			});
		</script>
	<?php }

	private function oxd_openid_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'oxd_openid_success_message') );
		add_action( 'admin_notices', array( $this, 'oxd_openid_error_message') );
	}

	private function oxd_openid_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'oxd_openid_error_message') );
		add_action( 'admin_notices', array( $this, 'oxd_openid_success_message') );
	}

	public function oxd_openid_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	function  oxd_login_widget_openid_options() {
		global $wpdb;
		oxd_register_openid();
	}

	function oxd_openid_activation_message() {
		$class = "updated";
		$message = get_option('oxd_openid_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function oxd_login_widget_text_domain(){
		load_plugin_textdomain('flw', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}

	function gluu_openid_save_settings(){
		if(is_admin() && get_option('Oxd_Activated_Plugin')=='Plugin-Slug') {

			delete_option('Oxd_Activated_Plugin');
			update_option('oxd_openid_message','Go to plugin <b><a href="admin.php?page=oxd_openid_settings&tab=login">settings</a></b> to enable login by gluu.');
			add_action('admin_notices', array($this, 'oxd_openid_activation_message'));
		}
		if( isset( $_POST['option'] ) and $_POST['option'] == "oxd_openid_connect_register_customer" ) {	//register the admin to gluu

			$illegal = "#$%^*()+=[]';,/{}|:<>?~";
			$illegal = $illegal . '"';
			if( $this->oxd_openid_check_empty_or_null( $_POST['email'] ) || $this->oxd_openid_check_empty_or_null( $_POST['gluu_server_url'] ) || $this->oxd_openid_check_empty_or_null( $_POST['oxd_host_ip']) || $this->oxd_openid_check_empty_or_null( $_POST['oxd_host_port'] ) ) {
				update_option( 'oxd_openid_message', 'All the fields are required. Please enter valid entries.');
				$this->oxd_openid_show_error_message();
				return;
			}else if( !$_POST['users_can_register']){

				update_option( 'oxd_openid_message', 'Need to choose anyone can register checkbox.');
				$this->oxd_openid_show_error_message();
				return;
			} else if( (int)$_POST['oxd_host_port']  > 65535 && (int)$_POST['oxd_host_port']  < 0){

				update_option( 'oxd_openid_message', 'Enter your oxd host port (Min. number 0, Max. number 65535)');
				$this->oxd_openid_show_error_message();
				return;
			}else if( filter_var($_POST['oxd_host_ip'], FILTER_VALIDATE_IP) === false){
				update_option( 'oxd_openid_message', $_POST['oxd_host_ip'].'is not ip. Please valid ip.');
				$this->oxd_openid_show_error_message();
				return;
			}else if(filter_var($_POST['gluu_server_url'], FILTER_VALIDATE_URL) === false  ){
				update_option( 'oxd_openid_message', $_POST['gluu_server_url']. 'is not url address.');
				$this->oxd_openid_show_error_message();
				return;
			} else if(strpbrk($_POST['email'],$illegal)) {
				update_option( 'oxd_openid_message', 'Please match the format of Email. No special characters are allowed.');
				$this->oxd_openid_show_error_message();
				return;
			} else {
				$email = $_POST['email'];
				$oxd_host_port = $_POST['oxd_host_port'];
				$oxd_host_ip = $_POST['oxd_host_ip'];
				$gluu_server_url = $_POST['gluu_server_url'];
			}
			update_option( 'users_can_register', $_POST['users_can_register'] );
			update_option( 'default_role', $_POST['default_role'] );
			update_option( 'oxd_openid_admin_email', $email );
			update_option( 'oxd_openid_oxd_ip', $oxd_host_ip );
			update_option( 'oxd_openid_oxd_port', $oxd_host_port );
			update_option( 'oxd_openid_gluu_server_url', $gluu_server_url );
			update_option( 'oxd_openid_host_name', $gluu_server_url );
			$config_option = array(
					"oxd_host_ip" => $oxd_host_ip,
					"oxd_host_port" =>$oxd_host_port,
					"gluu_server_url" =>$gluu_server_url,
					"authorization_redirect_uri" => home_url().'/wp-login.php',
					"logout_redirect_uri" => wp_logout_url(),
					"scope" => [ "openid", "profile" ],
					"application_type" => "web",
					"redirect_uris" => [ home_url().'/wp-login.php' ],
					"response_types" => ["code"],
					"grant_types" =>"authorization_code",
					"acr_values" => ["gplus" ],
					"am_host" =>"",
					"http_method" =>"DELETE",
					"login_type"=>[ "" ],
					"migrate_users"=>''
			);
			update_option( 'oxd_config', $config_option );

			$register_site = new Register_site();

			$register_site->setRequestAcrValues($config_option['acr_values']);
			$register_site->setRequestAuthorizationRedirectUri($config_option['authorization_redirect_uri']);
			$register_site->setRequestRedirectUris($config_option['redirect_uris']);
			$register_site->setRequestLogoutRedirectUri($config_option['logout_redirect_uri']);
			$register_site->setRequestContacts([$email]);

			$register_site->setRequestClientJwksUri("");
			$register_site->setRequestClientRequestUris([]);
			$register_site->setRequestClientTokenEndpointAuthMethod("");

			$register_site->request();
			if($register_site->getResponseOxdId()){
				if(get_option('oxd_id')){
					update_option( 'oxd_id', $register_site->getResponseOxdId() );
				}else{
					add_option( 'oxd_id', $register_site->getResponseOxdId() );
				}
				update_option( 'oxd_openid_message', 'Your settings are saved successfully.' );
				$this->oxd_openid_show_success_message();
			}else{
				update_option( 'oxd_openid_message', 'Gluu server url, oxd ip or oxd host is not a valid.');
				$this->oxd_openid_show_error_message();
			}


		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "oxd_openid_enable_apps" ) {
			if(is_oxd_registered()) {
				update_option( 'oxd_openid_google_enable', isset( $_POST['oxd_openid_google_enable']) ? $_POST['oxd_openid_google_enable'] : 0);
				update_option( 'oxd_openid_facebook_enable', isset( $_POST['oxd_openid_facebook_enable']) ? $_POST['oxd_openid_facebook_enable'] : 0);
				update_option( 'oxd_openid_SMS_enable', isset( $_POST['oxd_openid_SMS_enable']) ? $_POST['oxd_openid_SMS_enable'] : 0);
				update_option( 'oxd_openid_Mobile_enable', isset( $_POST['oxd_openid_Mobile_enable']) ? $_POST['oxd_openid_Mobile_enable'] : 0);
				update_option( 'oxd_openid_U2F_enable', isset( $_POST['oxd_openid_U2F_enable']) ? $_POST['oxd_openid_U2F_enable'] : 0);

				update_option( 'oxd_openid_default_login_enable', isset( $_POST['oxd_openid_default_login_enable']) ? $_POST['oxd_openid_default_login_enable'] : 0);
				update_option( 'oxd_openid_default_register_enable', isset( $_POST['oxd_openid_default_register_enable']) ? $_POST['oxd_openid_default_register_enable'] : 0);
				update_option( 'oxd_openid_default_comment_enable', isset( $_POST['oxd_openid_default_comment_enable']) ? $_POST['oxd_openid_default_comment_enable'] : 0);
				update_option( 'oxd_openid_woocommerce_login_form', isset( $_POST['oxd_openid_woocommerce_login_form']) ? $_POST['oxd_openid_woocommerce_login_form'] : 0);

				//Redirect URL
				update_option( 'oxd_openid_login_redirect', $_POST['oxd_openid_login_redirect']);
				update_option( 'oxd_openid_login_redirect_url', $_POST['oxd_openid_login_redirect_url'] );

				//Logout Url
				update_option( 'oxd_openid_logout_redirection_enable', isset( $_POST['oxd_openid_logout_redirection_enable']) ? $_POST['oxd_openid_logout_redirection_enable'] : 0);
				update_option( 'oxd_openid_logout_redirect', $_POST['oxd_openid_logout_redirect']);
				update_option( 'oxd_openid_logout_redirect_url', $_POST['oxd_openid_logout_redirect_url'] );

				//auto register
				update_option( 'oxd_openid_auto_register_enable', isset( $_POST['oxd_openid_auto_register_enable']) ? $_POST['oxd_openid_auto_register_enable'] : 0);
				update_option( 'oxd_openid_register_disabled_message', $_POST['oxd_openid_register_disabled_message']);

				update_option('oxd_openid_login_widget_customize_text',$_POST['oxd_openid_login_widget_customize_text'] );
				update_option( 'oxd_openid_login_button_customize_text',$_POST['oxd_openid_login_button_customize_text'] );
				update_option('oxd_openid_login_theme',$_POST['oxd_openid_login_theme'] );
				update_option( 'oxd_openid_message', 'Your settings are saved successfully.' );

				//customization of icons
				update_option('oxd_login_icon_custom_size',$_POST['oxd_login_icon_custom_size'] );
				update_option('oxd_login_icon_space',$_POST['oxd_login_icon_space'] );
				update_option('oxd_login_icon_custom_width',$_POST['oxd_login_icon_custom_width'] );
				update_option('oxd_login_icon_custom_height',$_POST['oxd_login_icon_custom_height'] );
				update_option('oxd_openid_login_custom_theme',$_POST['oxd_openid_login_custom_theme'] );
				update_option( 'oxd_login_icon_custom_color', $_POST['oxd_login_icon_custom_color'] );

				// avatar
				update_option( 'oxdOpenId_social_login_avatar', isset( $_POST['oxdOpenId_social_login_avatar']) ? $_POST['oxdOpenId_social_login_avatar'] : 0);

				//Attribute collection
				update_option( 'oxdOpenId_user_attributes', isset( $_POST['oxdOpenId_user_attributes']) ? $_POST['oxdOpenId_user_attributes'] : 0);


				$this->oxd_openid_show_success_message();

			} else {
				update_option('oxd_openid_message', 'Please register an account before trying to enable any app');
				$this->oxd_openid_show_error_message();
			}
		}
	}


	function gluu_openid_menu() {

		//Add gluu plugin to the menu
		$page = add_menu_page( 'Gluu OpenID Settings ' . __( 'Configure OpenID', 'oxd_openid_settings' ), 'Gluu oxd login', 'administrator',
				'oxd_openid_settings', array( $this, 'oxd_login_widget_openid_options' ),plugin_dir_url(__FILE__) . 'includes/images/gluu_icon.png');
	}

	public function oxd_get_output( $atts ){
		if(is_oxd_registered()){
			$gluu_widget = new oxd_openid_login_wid();
			$html = $gluu_widget->openidloginFormShortCode( $atts );
			return $html;
		}
	}

	function oxd_social_login_custom_avatar( $avatar, $mixed, $size, $default, $alt = '' ) {
		$user = false;

		if ( is_numeric( $mixed ) AND $mixed > 0 ) {	//Check if we have an user identifier
			$user_id = $mixed;
		} elseif ( is_string( $mixed ) AND ( $user = get_user_by( 'email', $mixed )) ) {	//Check if we have an user email
			$user_id = $user->ID;
		} elseif ( is_object( $mixed ) AND property_exists( $mixed, 'user_id' ) AND is_numeric( $mixed->user_id ) ) {		//Check if we have an user object
			$user_id = $mixed->user_id;
		} else {		//None found
			$user_id = null;
		}

		if (  !empty( $user_id ) ) {	//User found?
			$override_avatar = true;	//Override current avatar ?
			$user_meta_thumbnail = get_user_meta( $user_id, 'oxdOpenId_user_avatar', true );		//Read the avatar
			$user_meta_name = get_user_meta( $user_id, 'user_name', true );		//read user details
			// if ( $options['apsl_user_avatar_options'] == 'social' ) {
			$user_picture = (!empty( $user_meta_thumbnail ) ? $user_meta_thumbnail : '');
			if ( $user_picture !== false AND strlen( trim( $user_picture ) ) > 0 ) {	//Avatar found?
				return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
			}
		}
		return $avatar;
	}
}

new gluu_OpenID_SSO;
?>