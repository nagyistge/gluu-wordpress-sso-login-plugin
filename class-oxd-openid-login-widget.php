<?php
include_once dirname( __FILE__ ) . '/oxd-rp/Register_site.php';
include_once dirname( __FILE__ ) . '/oxd-rp/Get_authorization_url.php';
include_once dirname( __FILE__ ) . '/oxd-rp/Get_tokens_by_code.php';
include_once dirname( __FILE__ ) . '/oxd-rp/Get_user_info.php';
include_once dirname( __FILE__ ) . '/oxd-rp/Logout.php';
if(is_oxd_registered()) {
	/*
    * Login Widget
    *
    */
	class oxd_openid_login_wid extends WP_Widget {

		public function __construct() {
			parent::__construct(
					'oxd_openid_login_wid',
					'gluu Login Widget',
					array( 'description' => __( 'Login using Social Apps like Google, Facebook, U2F, Mobile, SMS.', 'flw' ), )
			);
		}


		public function widget( $args, $instance ) {
			extract( $args );

			//$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );

			echo $args['before_widget'];
			//if ( ! empty( $wid_title ) )
			//	echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->openidloginForm();

			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
			return $instance;
		}


		public function openidloginForm(){
			global $post;
			$this->error_message();
			$selected_theme = get_option('oxd_openid_login_theme');
			$appsConfigured = get_option('oxd_openid_google_enable') | get_option('oxd_openid_facebook_enable')  | get_option('oxd_openid_SMS_enable')  | get_option('oxd_openid_Mobile_enable') | get_option('oxd_openid_U2F_enable');
			$spacebetweenicons = get_option('oxd_login_icon_space');
			$customWidth = get_option('oxd_login_icon_custom_width');
			$customHeight = get_option('oxd_login_icon_custom_height');
			$customSize = get_option('oxd_login_icon_custom_size');
			$customBackground = get_option('oxd_login_icon_custom_color');
			$customTheme = get_option('oxd_openid_login_custom_theme');
			$customTextofTitle = get_option('oxd_openid_login_button_customize_text');
			if( ! is_user_logged_in() ) {

				if( $appsConfigured ) {
					$this->oxd_openid_load_login_script();
					?>

					<a href="http://gluu.com/single-sign-on-sso" hidden></a>

					<div class="oxd-openid-app-icons">

						<p><?php   echo get_option('oxd_openid_login_widget_customize_text'); ?>
						</p>
						<?php
						if($customTheme == 'default'){
							if( get_option('oxd_openid_facebook_enable') ) {
								if($selected_theme == 'longbutton'){
									?> <a  onClick="oxdOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Facebook</a>
								<?php }
								else{ ?>

									<a title="<?php echo $customTextofTitle ?> Facebook" onClick="oxdOpenIdLogin('facebook');"><img style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>

								<?php }

							}
							if( get_option('oxd_openid_google_enable') ) {
								if($selected_theme == 'longbutton'){
									?>

									<a  onClick="oxdOpenIdLogin('google');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important' class='btn btn-block btn-social btn-google btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-google-plus'></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Google</a>
								<?php }
								else{ ?>
									<a onClick="oxdOpenIdLogin('google');"  title="<?php echo $customTextofTitle ?> Google" ><img style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
									<?php
								}
							}

							if( get_option('oxd_openid_U2F_enable') ) {
								if($selected_theme == 'longbutton'){
									?>

									<a  onClick="oxdOpenIdLogin('U2F');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important' class='btn btn-block btn-social btn-U2F btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-external-link'></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> U2F</a>
								<?php }
								else{ ?>
									<a onClick="oxdOpenIdLogin('U2F');"  title="<?php echo $customTextofTitle ?> U2F" ><img style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/U2F.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
									<?php
								}
							}


							if( get_option('oxd_openid_Mobile_enable') ) {
								if($selected_theme == 'longbutton'){
									?> <a  onClick="oxdOpenIdLogin('Mobile');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important" class="btn btn-block btn-social btn-Mobile btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-mobile"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Mobile</a>
								<?php }
								else{ ?>


									<a title="<?php echo $customTextofTitle ?> Mobile" onClick="oxdOpenIdLogin('Mobile');"><img style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/Mobile.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
								<?php }
							}

							if( get_option('oxd_openid_SMS_enable') ) {
								if($selected_theme == 'longbutton'){	?>
									<a  onClick="oxdOpenIdLogin('SMS');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important" class="btn btn-block btn-social btn-SMS btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-commenting-o"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> SMS</a>
								<?php }
								else{ ?>


									<a title="<?php echo $customTextofTitle ?> SMS" onClick="oxdOpenIdLogin('SMS');"><img style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/SMS.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
								<?php }
							}

						}
						?>



						<?php
						if($customTheme == 'custom'){
							if( get_option('oxd_openid_facebook_enable') ) {
								if($selected_theme == 'longbutton'){
									?> <a  onClick="oxdOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Facebook</a>
								<?php }
								else{ ?>

									<a  onClick="oxdOpenIdLogin('facebook');" title="<?php echo $customTextofTitle ?> Facebook"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;" class="fa fa-facebook custom-login-button <?php echo $selected_theme; ?>" ></i></a>

								<?php }

							}

							if( get_option('oxd_openid_google_enable') ) {
								if($selected_theme == 'longbutton'){
									?>

									<a   onClick="oxdOpenIdLogin('google');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-google-plus"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Google</a>
								<?php }
								else{ ?>
									<a  onClick="oxdOpenIdLogin('google');" title="<?php echo $customTextofTitle ?> Google"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-google-plus custom-login-button <?php echo $selected_theme; ?>" ></i></a>
									<?php
								}
							}

							if( get_option('oxd_openid_U2F_enable') ) {
								if($selected_theme == 'longbutton'){
									?>

									<a   onClick="oxdOpenIdLogin('U2F');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-external-link"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> U2F</a>
								<?php }
								else{ ?>
									<a  onClick="oxdOpenIdLogin('U2F');" title="<?php echo $customTextofTitle ?> U2F"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-external-link custom-login-button <?php echo $selected_theme; ?>" ></i></a>
									<?php
								}
							}

							if( get_option('oxd_openid_Mobile_enable') ) {
								if($selected_theme == 'longbutton'){
									?>

									<a   onClick="oxdOpenIdLogin('Mobile');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-mobile"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> Mobile</a>
								<?php }
								else{ ?>
									<a  onClick="oxdOpenIdLogin('Mobile');" title="<?php echo $customTextofTitle ?> Mobile"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-mobile custom-login-button <?php echo $selected_theme; ?>" ></i></a>
									<?php
								}
							}
							if( get_option('oxd_openid_SMS_enable') ) {
								if($selected_theme == 'longbutton'){	?>
									<a  onClick="oxdOpenIdLogin('SMS');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;background:<?php echo "#".$customBackground?> !important" class="btn btn-block btn-social btn-SMS btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-commenting-o"></i><?php
										echo get_option('oxd_openid_login_button_customize_text'); 	?> SMS</a>
								<?php }
								else{ ?>


									<a  onClick="oxdOpenIdLogin('SMS');" title="<?php echo $customTextofTitle ?> SMS"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class="fa fa-commenting-o custom-login-button <?php echo $selected_theme; ?>"></i></a>
								<?php }
							}


						}
						?>
						<br>
					</div> <br>
					<?php


				} else {
					?>
					<div>No apps configured. Please contact your administrator.</div>
					<?php
				}
			}else {
				global $current_user;
				get_currentuserinfo();
				$link_with_username = __('Howdy, ', 'flw') . $current_user->display_name;
				?>
				<div id="logged_in_user" class="oxd_openid_login_wid">
					<li><?php echo $link_with_username;?> | <a href="<?php echo wp_logout_url( site_url() ); ?>" title="<?php _e('Logout','flw');?>"><?php _e('Logout','flw');?></a></li>
				</div>
				<?php
			}


		}


		public function openidloginFormShortCode( $atts ){
			global $post;
			$html = '';
			$this->error_message();
			$selected_theme = isset( $atts['shape'] )? $atts['shape'] : get_option('oxd_openid_login_theme');
			$appsConfigured = get_option('oxd_openid_google_enable') | get_option('oxd_openid_facebook_enable')  | get_option('oxd_openid_SMS_enable') |get_option('oxd_openid_Mobile_enable') | get_option('oxd_openid_U2F_enable');
			$spacebetweenicons = isset( $atts['space'] )? $atts['space'] : get_option('oxd_login_icon_space');
			$customWidth = isset( $atts['width'] )? $atts['width'] : get_option('oxd_login_icon_custom_width');
			$customHeight = isset( $atts['height'] )? $atts['height'] : get_option('oxd_login_icon_custom_height');
			$customSize = isset( $atts['size'] )? $atts['size'] : get_option('oxd_login_icon_custom_size');
			$customBackground = isset( $atts['background'] )? $atts['background'] : get_option('oxd_login_icon_custom_color');
			$customTheme = isset( $atts['theme'] )? $atts['theme'] : get_option('oxd_openid_login_custom_theme');
			$customText = get_option('oxd_openid_login_widget_customize_text');
			$buttonText = get_option('oxd_openid_login_button_customize_text');
			$customTextofTitle = get_option('oxd_openid_login_button_customize_text');
			$logoutUrl = wp_logout_url( site_url() );

			if($selected_theme == 'longbuttonwithtext'){
				$selected_theme = 'longbutton';
			}
			if($customTheme == 'custombackground'){
				$customTheme = 'custom';
			}

			if( ! is_user_logged_in() ) {

				if( $appsConfigured ) {
					$this->oxd_openid_load_login_script();
					$html .= "<div class='oxd-openid-app-icons'>

						 <p> $customText</p>";

					if($customTheme == 'default'){
						if( get_option('oxd_openid_facebook_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important' class='btn btn-block btn-social btn-facebook btn-custom-dec login-button' onClick='oxdOpenIdLogin(" . '"facebook"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-facebook'></i>" . $buttonText . " Facebook</a>"; }
							else{
								$html .= "<a title= ' ".$customTextofTitle." Facebook' onClick='oxdOpenIdLogin(" . '"facebook"' . ");' ><img style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/facebook.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
							}

						}

						if( get_option('oxd_openid_google_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important' class='btn btn-block btn-social btn-google btn-custom-dec login-button' onClick='oxdOpenIdLogin(" . '"google"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-google-plus'></i>" . $buttonText . " Google</a>";
							}
							else{

								$html .= "<a  onClick='oxdOpenIdLogin(" . '"google"' . ");' title= ' ".$customTextofTitle." Google'><img style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/google.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";

							}
						}

						if( get_option('oxd_openid_U2F_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important' class='btn btn-block btn-social btn-U2F btn-custom-dec login-button' onClick='oxdOpenIdLogin(" . '"U2F"' . ");'> <img src='' style='padding-top:\" . ($customHeight-35) . \"px !important' class='fa'/>" . $buttonText . " U2F Token</a>";
							}
							else{

								$html .= "<a  onClick='oxdOpenIdLogin(" . '"U2F"' . ");' title= ' ".$customTextofTitle." U2F'><img style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/U2F.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";

							}
						}

						if( get_option('oxd_openid_Mobile_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important' class='btn btn-block btn-social btn-Mobile btn-custom-dec login-button' onClick='oxdOpenIdLogin(" . '"Mobile"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-mobile'></i>" . $buttonText . " Mobile</a>"; }
							else{
								$html .= "<a title= ' ".$customTextofTitle." Mobile' onClick='oxdOpenIdLogin(" . '"Mobile"' . ");' ><img style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/Mobile.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
							}

						}
						if( get_option('oxd_openid_SMS_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important' class='btn btn-block btn-social btn-SMS btn-custom-dec login-button' onClick='oxdOpenIdLogin(" . '"SMS"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-commenting-o'></i>" . $buttonText . " SMS</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." SMS' onClick='oxdOpenIdLogin(" . '"SMS"' . ");' ><img style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/SMS.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
							}
						}
					}



					if($customTheme == 'custom'){
						if( get_option('oxd_openid_facebook_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a   onClick='oxdOpenIdLogin(" . '"facebook"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-facebook'></i> " . $buttonText . " Facebook</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." Facebook' onClick='oxdOpenIdLogin(" . '"facebook"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-facebook custom-login-button  " . $selected_theme . "' ></i></a>";
							}

						}

						if( get_option('oxd_openid_google_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a   onClick='oxdOpenIdLogin(" . '"google"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-google-plus'></i> " . $buttonText . " Google</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." Google' onClick='oxdOpenIdLogin(" . '"google"' . ");' title= ' ". $customTextofTitle."  Google'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-google-plus custom-login-button  " . $selected_theme . "' ></i></a>";

							}
						}

						if( get_option('oxd_openid_U2F_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a   onClick='oxdOpenIdLogin(" . '"U2F"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-external-link'></i> " . $buttonText . " U2F</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." U2F' onClick='oxdOpenIdLogin(" . '"U2F"' . ");' title= ' ". $customTextofTitle."  U2F'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-external-link custom-login-button  " . $selected_theme . "' ></i></a>";

							}
						}

						if( get_option('oxd_openid_Mobile_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a   onClick='oxdOpenIdLogin(" . '"Mobile"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-mobile'></i> " . $buttonText . " Mobile</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." Mobile' onClick='oxdOpenIdLogin(" . '"Mobile"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-mobile custom-login-button  " . $selected_theme . "' ></i></a>";
							}

						}
						if( get_option('oxd_openid_SMS_enable') ) {
							if($selected_theme == 'longbutton'){
								$html .= "<a   onClick='oxdOpenIdLogin(" . '"SMS"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-commenting-o'></i> " . $buttonText . " SMS</a>";
							}
							else{
								$html .= "<a title= ' ".$customTextofTitle." SMS' onClick='oxdOpenIdLogin(" . '"SMS"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-commenting-o custom-login-button  " . $selected_theme . "' ></i></a>";
							}
						}
					}
					$html .= '</div> <br>';


				} else {

					$html .= '<div>No apps configured. Please contact your administrator.</div>';

				}
			}else {
				global $current_user;
				get_currentuserinfo();
				$link_with_username = __('Howdy, ', 'flw') . $current_user->display_name;
				$flw = __("Logout","flw");

				$html .= '<div id="logged_in_user" class="oxd_openid_login_wid">	' . $link_with_username . ' | <a href=' . $logoutUrl .' title=" ' . $flw . '"> ' . $flw . '</a></div>';

			}

			return $html;

		}

		private function oxd_openid_load_login_script() {
			?>
			<script type="text/javascript">
				function oxdOpenIdLogin(app_name) {
					<?php
                        if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
                            $http = "https://";
                        } else {
                            $http =  "http://";
                        }
                        if ( strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE){
                                $redirect_url = site_url() . '/wp-login.php?option=getOxdSocialLogin&app_name=';

                        }else{
                                $redirect_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                if(strpos($redirect_url, '?') !== false) {
                                    $redirect_url .= '&option=getOxdSocialLogin&app_name=';
                                } else {
                                    $redirect_url .= '?option=getOxdSocialLogin&app_name=';
                                }
                        }
                    ?>
					window.location.href = '<?php echo $redirect_url; ?>' + app_name;
				}
			</script>
			<?php
		}

		public function error_message(){
			if(isset($_SESSION['msg']) and $_SESSION['msg']){
				echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION['msg'].'</div>';
				unset($_SESSION['msg']);
				unset($_SESSION['msg_class']);
			}
		}

	}

	function oxd_openid_start_session() {
		if( !session_id() ) {
			session_start();
		}
	}

	function oxd_openid_end_session() {
		$config_option = get_option( 'oxd_config' );
		$logout = new Logout();
		$logout->setRequestOxdId(get_option('user_oxd_id'));
		$logout->setRequestPostLogoutRedirectUri($config_option['logout_redirect_uri']);
		$logout->setRequestIdToken(get_option('user_oxd_access_token'));
		$logout->request();
		delete_option('user_oxd_id');
		delete_option('user_oxd_id_token');
		delete_option('user_oxd_access_token');

		if( session_id() ) {
			session_destroy();
		}
	}

	function oxd_openid_login_validate(){
		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'getOxdSocialLogin' ) !== false ) {

			$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://";

			$parts = parse_url($http . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			parse_str($parts['query'], $query);
			$post = isset( $query['p'] ) ? '?p=' . $query['p'] : '';
			$app = '';
			if($_REQUEST['app_name']=='google'){
				$app = 'gplus';
			}elseif($_REQUEST['app_name']=='facebook'){
				$app = 'facebook';
			}elseif($_REQUEST['app_name']=='U2F'){
				$app = 'U2F';
			}elseif($_REQUEST['app_name']=='Mobile'){
				$app = 'Mobile';
			}elseif($_REQUEST['app_name']=='SMS'){
				$app = 'SMS';
			}
			$base_return_url =  $http . $_SERVER["HTTP_HOST"] . strtok($_SERVER["REQUEST_URI"],'?') . $post;
			$return_url = strpos($base_return_url, '?') !== false ? $base_return_url . '&option=oxdOpenId': $base_return_url . '?option=oxdOpenId';
			$email = get_option('oxd_openid_admin_email');
			if(get_option('user_oxd_id')){
				delete_option('user_oxd_id');
			}
			$config_option = get_option( 'oxd_config' );
			$register_site = new Register_site();

			$register_site->setRequestAcrValues(["gplus" ]);
			$register_site->setRequestAuthorizationRedirectUri($return_url);
			$register_site->setRequestRedirectUris([ $return_url ]);
			$register_site->setRequestLogoutRedirectUri($config_option['logout_redirect_uri']);
			$register_site->setRequestContacts([$email]);

			$register_site->setRequestClientJwksUri("");
			$register_site->setRequestClientRequestUris([]);
			$register_site->setRequestClientTokenEndpointAuthMethod("");


			$register_site->request();

			add_option('user_oxd_id',$register_site->getResponseOxdId());
			$get_authorization_url = new Get_authorization_url();
			$get_authorization_url->setRequestOxdId($register_site->getResponseOxdId());
			$get_authorization_url->setRequestAcrValues([$app]);
			$get_authorization_url->request();
			wp_redirect( $get_authorization_url->getResponseAuthorizationUrl() );
			exit;
		}

		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'oxdOpenId' ) !== false ){

			$get_tokens_by_code = new Get_tokens_by_code();

			$get_tokens_by_code->setRequestOxdId(get_option('user_oxd_id'));
			$get_tokens_by_code->setRequestCode($_GET['code']);
			$get_tokens_by_code->setRequestState($_GET['state']);
			$get_tokens_by_code->setRequestScopes(["openid", "profile"]);

			$get_tokens_by_code->request();
			if(get_option('user_oxd_id_token')){
				delete_option('user_oxd_id_token');
				delete_option('user_oxd_access_token');
			}else{
				add_option('user_oxd_id_token',$get_tokens_by_code->getResponseIdToken());
				add_option('user_oxd_access_token',$get_tokens_by_code->getResponseAccessToken());
			}
			$get_user_info = new Get_user_info();
			$get_user_info->setRequestOxdId(get_option('user_oxd_id'));
			$get_user_info->setRequestAccessToken(get_option('user_oxd_access_token'));
			$get_user_info->request();

			$am_hosts = '';
			if(get_option('oxd_openid_gluu_server_url')){
				$disallowed = array('http://', 'https://');
				foreach($disallowed as $d) {
					if(strpos(get_option('oxd_openid_gluu_server_url'), $d) === 0) {
						$am_hosts = str_replace($d, '', get_option('oxd_openid_gluu_server_url'));
					}
				}
			}
			$user_email = '';
			if($get_user_info->getResponsePreferredUsername() ) {
				$user_email = $get_user_info->getResponsePreferredUsername().'@'.$am_hosts;
			}

			$user_name = $get_user_info->getResponsePreferredUsername();
			$user_picture = $get_user_info->getResponsePicture();
			$first_name = '';
			$last_name = '';
			$user_url = '';
			if($get_user_info->getResponseProfile())
				$user_url = $get_user_info->getResponseProfile();
			if($get_user_info->getResponseGivenName() && $get_user_info->getResponseFamilyName()){

				$user_full_name = $get_user_info->getResponseGivenName().' '.$get_user_info->getResponseFamilyName();
				$first_name = $get_user_info->getResponseGivenName();
				$last_name = $get_user_info->getResponseFamilyName();
			}
			else
				$user_full_name = $user_name;

			//Check if username is equal to full name, set username as email
			if(strcmp($user_name, $user_full_name)){
				$email_split = explode("@", $user_email);
				$username = $email_split[0];
			} else {
				$username = $user_name;
			}

			if( $user_email ) {
				if( email_exists( $user_email ) ) { // user is a member
					$user 	= get_user_by('email', $user_email );
					$user_id 	= $user->ID;
					if(get_option('oxdOpenId_social_login_avatar') && isset($user_picture))
						update_user_meta($user_id, 'oxdOpenId_user_avatar', $user_picture);
					do_action( 'wp_login', $user->user_login, $user );
					wp_set_auth_cookie( $user_id, true );
				} else if( username_exists( $username ) ) { // user is a member
					$user 	= get_user_by('login', $username );
					$user_id 	= $user->ID;
					if(get_option('oxdOpenId_social_login_avatar') && isset($user_picture))
						update_user_meta($user_id, 'oxdOpenId_user_avatar', $user_picture);
					do_action( 'wp_login', $user->user_login, $user );
					wp_set_auth_cookie( $user_id, true );
				} else { // this user is a guest
					if(get_option('oxd_openid_auto_register_enable')) {
						$random_password 	= wp_generate_password( 10, false );
						$userdata = array(
								'user_login'  =>  $username,
								'user_nicename'  =>  $get_user_info->getResponseNickname(),
								'user_email'    =>  $user_email,
								'user_pass'   =>  $random_password,
								'display_name' => $user_full_name,
								'first_name' => $first_name,
								'last_name' => $last_name,
								'user_url' => $user_url,
								'user_status' => 1
						);


						$user_id 	= wp_insert_user( $userdata);
						$user	= get_user_by('email', $user_email );

						if(get_option('oxdOpenId_social_login_avatar') && isset($user_picture)){
							update_user_meta($user_id, 'oxdOpenId_user_avatar', $user_picture);
						}
						do_action( 'wp_login', $user->user_login, $user );
						wp_set_auth_cookie( $user_id, true );
					}
				}
			}

			$redirect_url = oxd_openid_get_redirect_url();
			wp_redirect($redirect_url);
			exit;

		}

		if(isset($_REQUEST['autoregister']) and strpos($_REQUEST['autoregister'],'false') !== false) {
			if(!is_user_logged_in()) {
				oxd_openid_disabled_register_message();
			}
		}
	}

	function oxd_openid_disabled_register_message() {
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script( 'oxd-wp-settings-script',plugins_url('includes/js/settings_popup.js', __FILE__), array('jquery'));
		add_thickbox();
		$script = '<script>
						function getAutoRegisterDisabledMessage() {
							var disabledMessage = "' . get_option('oxd_openid_register_disabled_message') . '";
							return disabledMessage;
						}
					</script>';
		echo $script;
	}

	function oxd_openid_get_redirect_url() {
		$option = get_option( 'oxd_openid_login_redirect' );
		$redirect_url = site_url();
		if( $option == 'same' ) {
			if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
				$http = "https://";
			} else {
				$http =  "http://";
			}
			$redirect_url = urldecode(html_entity_decode(esc_url($http . $_SERVER["HTTP_HOST"] . str_replace('option=oxdOpenId','',$_SERVER['REQUEST_URI']))));
			if(html_entity_decode(esc_url(remove_query_arg('ss_message', $redirect_url))) == wp_login_url() || strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE || strpos($_SERVER['REQUEST_URI'],'wp-admin') !== FALSE){
				$redirect_url = site_url().'/';
			}
		} else if( $option == 'homepage' ) {
			$redirect_url = site_url();
		} else if( $option == 'dashboard' ) {
			$redirect_url = admin_url();
		} else if( $option == 'custom' ) {
			$redirect_url = get_option('oxd_openid_login_redirect_url');
		}
		if(strpos($redirect_url,'?') !== FALSE) {
			$redirect_url .= get_option('oxd_openid_auto_register_enable') ? '' : '&autoregister=false';
		} else{
			$redirect_url .= get_option('oxd_openid_auto_register_enable') ? '' : '?autoregister=false';
		}
		return $redirect_url;
	}

	function oxd_openid_redirect_after_logout($logout_url) {
		if(get_option('oxd_openid_logout_redirection_enable')){
			$option = get_option( 'oxd_openid_logout_redirect' );
			$redirect_url = site_url();
			if( $option == 'homepage' ) {
				$redirect_url = $logout_url . '&redirect_to=' .home_url()  ;
			}
			else if($option == 'currentpage'){
				if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
					$http = "https://";
				} else {
					$http =  "http://";
				}
				$redirect_url = $logout_url . '&redirect_to=' . $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
			}
			else if($option == 'login') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . '/wp-admin' ;
			}
			else if($option == 'custom') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . (null !== get_option('oxd_openid_logout_redirect_url')?get_option('oxd_openid_logout_redirect_url'):'');
			}
			return $redirect_url;
		}else{
			return $logout_url;
		}

	}

	if(get_option('oxd_openid_logout_redirection_enable') == 1){
		add_filter( 'logout_url', 'oxd_openid_redirect_after_logout',0,1);
	}
	add_action( 'widgets_init', create_function( '', 'register_widget( "oxd_openid_login_wid" );' ) );

	add_action( 'init', 'oxd_openid_login_validate' );
	add_action( 'init', 'oxd_openid_start_session' );
	add_action( 'wp_logout', 'oxd_openid_end_session' );
}
?>