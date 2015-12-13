<?php

function is_oxd_registered() {
    $email 			= get_option('oxd_openid_admin_email');
    $oxd_id 	= get_option('oxd_id');
    if( ! $email || ! $oxd_id ) {
        return 0;
    } else {
        return 1;
    }
}
function oxd_register_openid() {
	if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register' ) {
		$active_tab = $_GET[ 'tab' ];
	} else if(is_oxd_registered()) {
		$active_tab = 'login_config';
	} else {
		$active_tab = 'register';
	}
?>
<div id="tab">
	<h2 class="nav-tab-wrapper">
		<?php if(!is_oxd_registered()) { ?>
			<a class="nav-tab <?php echo $active_tab == 'register' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Gluu and oxd config</a>
		<?php } ?>
		<a class="nav-tab <?php echo $active_tab == 'login_config' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login_config'), $_SERVER['REQUEST_URI'] ); ?>">Oxd config</a>
		<a class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Gluu login config</a>
		<a class="nav-tab <?php echo $active_tab == 'shortcode' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'shortcode'), $_SERVER['REQUEST_URI'] ); ?>">Shortcode</a>
		<a class="nav-tab <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">Help & Troubleshooting</a>
	</h2>
</div>

<div id="oxd_openid_settings">

	<div class="oxd_container">
		<div id="oxd_openid_msgs"></div>
			<table style="width:100%;">
				<tr>
					<td style="vertical-align:top;width:65%;">

						<?php
							if ( $active_tab == 'register') {
								if (! is_oxd_registered()) {
									oxd_openid_show_new_registration_page();
								}
                            }else if($active_tab == 'login_config') {
                                oxd_openid_login_config_info();
							} else if($active_tab == 'login'){
								oxd_openid_apps_config();
							}else if($active_tab == 'shortcode') {
								oxd_openid_shortcode_info();
							}else if($active_tab == 'help') {
								oxd_openid_troubleshoot_info();
							}
						?>
					</td>
				</tr>
			</table>
		<?php

}
function oxd_openid_show_new_registration_page() {
	update_option ( 'oxd_openid_new_registration', 'true' );
	global $current_user;
		get_currentuserinfo();
	?>

					<form name="f" method="post" action="" id="register-form">
								<input type="hidden" name="option" value="oxd_openid_connect_register_customer" />



								<div class="oxd_openid_table_layout">
									<?php if(!is_oxd_registered()) { ?>
										<div style="font-weight:bold ;display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                                            Please enter gluu and oxd configuration for continue.
                                        </div>
									<?php } ?>

										<h3>Enter gluu and oxd configuration.</h3>

										<p>Please enter a valid email that you have access to. Login using your gluu credentials.
										</p>
										<table class="oxd_openid_settings_table">
											<tr>
												<td><b><font color="#FF0000">*</font>Email:</b></td>
												<td><input class="oxd_openid_table_textbox" type="email" name="email"
													required placeholder="person@example.com"
													value="<?php echo $current_user->user_email;?>" /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Gluu server url:</b></td>
												<td><input class="oxd_openid_table_textbox" required type="url"
													name="gluu_server_url" placeholder="Enter your gluu server url." /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Oxd host ip:</b></td>
												<td><input class="oxd_openid_table_textbox" required type="text"
													name="oxd_host_ip" value="127.0.0.1" placeholder="Enter your oxd host ip." /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Oxd host port:</b></td>
												<td><input class="oxd_openid_table_textbox" required type="number"
													name="oxd_host_port" value="8099" placeholder="Enter your oxd host port (Min. number 0, Max. number 65535)" /></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td><br /><input type="submit" name="submit" value="Next" style="width:100px;"
													class="button button-primary button-large" /></td>
											</tr>
										</table>

								</div>
		</form>
				<script>
						//jQuery("#phone").intlTelInput();
						var text = "&nbsp;&nbsp;We will call only if you need support."
						jQuery('.intl-number-input').append(text);

				</script>
		<?php
}
function oxd_openid_apps_config() {
	?>
		<!-- Google configurations -->
				<form id="form-apps" name="form-apps" method="post" action="">
					<input type="hidden" name="option" value="oxd_openid_enable_apps" />


					<div class="oxd_openid_table_layout">

						<?php if(!is_oxd_registered()) { ?>
							<div style="font-weight:bold ;display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
		Please enter gluu and oxd configuration for continue.
		</div>
						<?php } ?>

							<table>
									<tr>
										<td colspan="2">
											<h3>Gluu login config
											<input type="submit" name="submit" value="Save" style="float:right; margin-right:2%; margin-top: -3px;width:100px;" <?php if(!is_oxd_registered()) echo 'disabled'?>
													class="button button-primary button-large" />
												</h3>

												<b>Select applications to enable login for your users. Customize your login icons using a range of shapes, themes and sizes. You can choose different places to display these icons and also customize redirect url after login.</b>
										</td>

									</tr>
								</table>

							<table>


								<h3>Select Apps</h3>
								<p>Select applications to enable social login</p>

								<tr>
									<td>
										<table style="width:100%">
											<tr>
												<td><input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?> id="facebook_enable" class="app_enable" name="oxd_openid_facebook_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('oxd_openid_facebook_enable') == 1 );?> /><strong>Facebook</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?> id="google_enable" class="app_enable" name="oxd_openid_google_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('oxd_openid_google_enable') == 1 );?> /><strong>Google</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?>
												id="U2F_enable" class="app_enable" name="oxd_openid_U2F_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('oxd_openid_U2F_enable') == 1 );?> /><strong>U2F</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?>
												id="Mobile_enable" class="app_enable" name="oxd_openid_Mobile_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('oxd_openid_Mobile_enable') == 1 );?> /><strong>Mobile</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?>
												id="SMS_enable" class="app_enable" name="oxd_openid_SMS_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('oxd_openid_SMS_enable') == 1 );?> /><strong>SMS</strong>
												</td>
											</tr>
										</table>
									</td>
								<tr>



					<td>
						<br>
							<hr>
							<h3>Customize Login Icons</h3>
							<p>Customize shape, theme and size of social login icons</p>
							</td>
		</tr>
		<tr>
		<td>
			<b>Shape</b>
			<b style="margin-left:130px;">Theme</b>
			<b style="margin-left:130px;">Space between Icons</b>
			<b style="margin-left:86px;">Size of Icons</b>
			</td>
		</tr>
		<tr>

				<td class="oxd_openid_table_td_checkbox">
					<input type="radio"    name="oxd_openid_login_theme" value="circle" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_size').value ,'circle',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)"
						<?php if(!is_oxd_registered()) echo 'disabled'?>
								<?php checked( get_option('oxd_openid_login_theme') == 'circle' );?> />Round

				<span style="margin-left:106px;">
					<input type="radio" id="oxd_openid_login_default_radio"  name="oxd_openid_login_custom_theme" value="default" onclick="checkLoginButton();oxdLoginPreview(setSizeOfIcons(), setLoginTheme(),'default',document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)"
								<?php checked( get_option('oxd_openid_login_custom_theme') == 'default' );?> <?php if(!is_oxd_registered()) echo 'disabled'?>/>Default

				</span>

				<span  style="margin-left:111px;">
						<input style="width:50px" onkeyup="oxdLoginSpaceValidate(this)" id="oxd_login_icon_space" name="oxd_login_icon_space" type="text" value="<?php echo get_option('oxd_login_icon_space')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>/>
						<input id="oxd_login_space_plus" type="button" value="+" onmouseup="oxdLoginPreview(setSizeOfIcons() ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>/>
						<input id="oxd_login_space_minus" type="button" value="-" onmouseup="oxdLoginPreview(setSizeOfIcons()  ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>/>
				</span>


				<span id="commontheme" style="margin-left:115px">
				<input style="width:50px" id="oxd_login_icon_size" onkeyup="oxdLoginSizeValidate(this)" name="oxd_login_icon_custom_size" type="text" value="<?php echo get_option('oxd_login_icon_custom_size')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_size_plus" type="button" value="+" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_size_minus" type="button" value="-" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>

				</span>
				<span style="margin-left:115px" class="longbuttontheme">Width:&nbsp;
				<input style="width:50px" id="oxd_login_icon_width" onkeyup="oxdLoginWidthValidate(this)" name="oxd_login_icon_custom_width" type="text" value="<?php echo get_option('oxd_login_icon_custom_width')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_width_plus" type="button" value="+" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_width_minus" type="button" value="-" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>

				</span>


				</td>
		</tr>

		<tr>
				<td class="oxd_openid_table_td_checkbox">
				<input type="radio"   name="oxd_openid_login_theme"  value="oval" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_size').value,'oval',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_size').value )"
						<?php if(!is_oxd_registered()) echo 'disabled'?>
								<?php checked( get_option('oxd_openid_login_theme') == 'oval' );?> />Rounded Edges

				<span style="margin-left:50px;">
						<input type="radio" id="oxd_openid_login_custom_radio"  name="oxd_openid_login_custom_theme" value="custom" onclick="checkLoginButton();oxdLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>
								<?php checked( get_option('oxd_openid_login_custom_theme') == 'custom' );?> />Custom Background*

						</span>

					<span style="margin-left:249px" class="longbuttontheme" >Height:
				<input style="width:50px" id="oxd_login_icon_height" onkeyup="oxdLoginHeightValidate(this)" name="oxd_login_icon_custom_height" type="text" value="<?php echo get_option('oxd_login_icon_custom_height')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_height_plus" type="button" value="+" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>
				<input id="oxd_login_height_minus" type="button" value="-" onmouseup="oxdLoginPreview(document.getElementById('oxd_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>

				</span>
				</td>
		</tr>

		<tr>
				<td class="oxd_openid_table_td_checkbox">
						<input type="radio"   name="oxd_openid_login_theme" value="square" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_size').value ,'square',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_size').value )"
						<?php if(!is_oxd_registered()) echo 'disabled'?>
								<?php checked( get_option('oxd_openid_login_theme') == 'square' );?> />Square

						<span style="margin-left:113px;">
						<input id="oxd_login_icon_custom_color" style="width:135px;" name="oxd_login_icon_custom_color"  class="color" value="<?php echo get_option('oxd_login_icon_custom_color')?>" onchange="oxdLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>
						</span>
				</td>
		</tr>
		<tr>
				<td class="oxd_openid_table_td_checkbox">
						<input type="radio" id="iconwithtext"   name="oxd_openid_login_theme" value="longbutton" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_width').value ,'longbutton',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)"
						<?php if(!is_oxd_registered()) echo 'disabled'?>
								<?php checked( get_option('oxd_openid_login_theme') == 'longbutton' );?> />Long Button with Text</td>
		</tr>
		<tr>
			<td>	<br><b>Preview : </b><br/><span hidden id="no_apps_text">No apps selected</span>
				<div>
					<img class="oxd_login_icon_preview" id="oxd_login_icon_preview_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
					<img class="oxd_login_icon_preview" id="oxd_login_icon_preview_google" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" />
					<img class="oxd_login_icon_preview" id="oxd_login_icon_preview_U2F" src="<?php echo plugins_url( 'includes/images/icons/U2F.png', __FILE__ )?>" />
					<img class="oxd_login_icon_preview" id="oxd_login_icon_preview_Mobile" src="<?php echo plugins_url( 'includes/images/icons/Mobile.png', __FILE__ )?>" />
					<img class="oxd_login_icon_preview" id="oxd_login_icon_preview_SMS" src="<?php echo plugins_url( 'includes/images/icons/SMS.png', __FILE__ )?>" />
				</div>

				<div>
					<a id="oxd_login_button_preview_facebook" class="btn btn-block btn-defaulttheme btn-social btn-facebook btn-custom-size"> <i class="fa fa-facebook"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Facebook</a>
					<a id="oxd_login_button_preview_google" class="btn btn-block btn-defaulttheme btn-social btn-google btn-custom-size"> <i class="fa fa-google-plus"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Google</a>
					<a id="oxd_login_button_preview_U2F" class="btn btn-block btn-defaulttheme btn-social btn-U2F btn-custom-size"> <i class="fa fa-external-link"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> U2F</a>
					<a id="oxd_login_button_preview_Mobile" class="btn btn-block btn-defaulttheme btn-social btn-Mobile btn-custom-size"> <i class="fa fa-mobile"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Mobile</a>
					<a id="oxd_login_button_preview_SMS" class="btn btn-block btn-defaulttheme btn-social btn-SMS btn-custom-size"> <i class="fa fa-commenting-o"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> SMS</a>

				</div>

				<div>
					<i class="oxd_custom_login_icon_preview fa fa-facebook" id="oxd_custom_login_icon_preview_facebook"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="oxd_custom_login_icon_preview fa fa-google-plus" id="oxd_custom_login_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="oxd_custom_login_icon_preview fa fa-external-link" id="oxd_custom_login_icon_preview_U2F"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="oxd_custom_login_icon_preview fa fa-mobile" id="oxd_custom_login_icon_preview_Mobile" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="oxd_custom_login_icon_preview fa fa-commenting-o" id="oxd_custom_login_icon_preview_SMS" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
				</div>

				<div>
					<a id="oxd_custom_login_button_preview_facebook" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-facebook"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Facebook</a>
					<a id="oxd_custom_login_button_preview_google" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-google-plus"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Google</a>
					<a id="oxd_custom_login_button_preview_U2F" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-external-link"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> U2F</a>
					<a id="oxd_custom_login_button_preview_Mobile" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-mobile"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> Mobile</a>
					<a id="oxd_custom_login_button_preview_SMS" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-commenting-o"></i><?php
									echo get_option('oxd_openid_login_button_customize_text'); 	?> SMS</a>

				</div>
		</td>
	</tr>
	<tr>
		<td><br>
		<strong>*NOTE:</strong><br/>Custom background: This will change the background color of login icons.
		</td>
	</tr>
	<tr>
									<td>
									<br>
										<hr>
										<h3>Display Options</h3>

											<b>Select the options where you want to display the social login icons</b>
										</td>
								</tr>


											<tr>
												<td class="oxd_openid_table_td_checkbox">
												<input type="checkbox" id="default_login_enable" name="oxd_openid_default_login_enable" value="1"
													<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_login_enable') == 1 );?> />Default Login Form</td>
											</tr>
											<tr>
												<td class="oxd_openid_table_td_checkbox">
												<input type="checkbox" id="default_register_enable" name="oxd_openid_default_register_enable" value="1"
												<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_register_enable') == 1 );?> />Default Registration Form</td>
											</tr>
											<tr>
												<td class="oxd_openid_table_td_checkbox">
													<input type="checkbox" id="default_comment_enable" name="oxd_openid_default_comment_enable" value="1"
													<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_comment_enable') == 1 );?> />Comment Form</td>
											</tr>
											<tr>
												<td class="oxd_openid_table_td_checkbox">
													<input type="checkbox" id="woocommerce_login_form" name="oxd_openid_woocommerce_login_form" value="1"
													<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_woocommerce_login_form') == 1 );?> />WooCommerce Login Form</td>
											</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<b>Redirect URL after login:</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_same_page" name="oxd_openid_login_redirect" value="same"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_login_redirect') == 'same' );?> />Same page where user logged in
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_homepage" name="oxd_openid_login_redirect" value="homepage"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_login_redirect') == 'homepage' );?> />Homepage
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_dashboard" name="oxd_openid_login_redirect" value="dashboard"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_login_redirect') == 'dashboard' );?> />Account dashboard
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_customurl" name="oxd_openid_login_redirect" value="custom"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_login_redirect') == 'custom' );?> />Custom URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="url" id="login_redirect_url" style="width:50%" name="oxd_openid_login_redirect_url" value="<?php echo get_option('oxd_openid_login_redirect_url')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>/>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<input type="checkbox" id="logout_redirection_enable" name="oxd_openid_logout_redirection_enable" value="1"
												<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_logout_redirection_enable') == 1 );?> />
										<b>Enable Logout Redirection</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_home" name="oxd_openid_logout_redirect" value="homepage"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_logout_redirect') == 'homepage' );?> />Home Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_current" name="oxd_openid_logout_redirect" value="currentpage"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_logout_redirect') == 'currentpage' );?> />Current Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_login" name="oxd_openid_logout_redirect" value="login"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_logout_redirect') == 'login' );?> />Login Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_customurl" name="oxd_openid_logout_redirect" value="custom"
										<?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxd_openid_logout_redirect') == 'custom' );?> />Relative URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php echo site_url();?>
										<input type="text" id="logout_redirect_url" style="width:50%" name="oxd_openid_logout_redirect_url" value="<?php echo get_option('oxd_openid_logout_redirect_url')?>" <?php if(!is_oxd_registered()) echo 'disabled'?>/>
									</td>
								</tr>
								<tr>
									<td>
										<br>
										<hr>
										<h3>Registration Options</h3>
									</td>
								</tr>
								<tr>
									<td>
										If Auto-register users is unchecked, users will not be able to register using Social Login. The users who already have an account will be able to login.  This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress.
										<br/><br/>
										<input type="checkbox" id="auto_register_enable" name="oxd_openid_auto_register_enable" value="1"
										<?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_auto_register_enable') == 1 );?> /><b>Auto-register users</b>
										<br/><br/>
										<b>Registration disabled message: </b>
										<textarea id="auto_register_disabled_message" style="width:80%" name="oxd_openid_register_disabled_message" <?php if(!is_oxd_registered()) echo 'disabled'?>><?php echo get_option('oxd_openid_register_disabled_message')?></textarea>
									</td>
								</tr>
								<?php
								    $script_option = array(
								                            'oxd_login_icon_custom_size'=>get_option('oxd_login_icon_custom_size'),
								                            'oxd_openid_login_theme'=>get_option('oxd_openid_login_theme'),
								                            'oxd_openid_login_custom_theme'=>get_option('oxd_openid_login_custom_theme'),
								                            'oxd_login_icon_custom_color'=>get_option('oxd_login_icon_custom_color'),
								                            'oxd_login_icon_space'=>get_option('oxd_login_icon_space'),
								                            'oxd_login_icon_custom_height'=>get_option('oxd_login_icon_custom_height')
								    );
                                    wp_enqueue_script( 'gluu_oxd_scripts',plugins_url('includes/js/gluu_oxd_scripts.js', __FILE__), array('jquery'));
                                    wp_localize_script( 'gluu_oxd_scripts', 'option', $script_option );
								?>

		<tr>
			<td>
				<br/>
				<hr>
				<h3>Advanced Settings</h3>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" id="oxdOpenId_social_login_avatar" name="oxdOpenId_social_login_avatar" value="1" <?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxdOpenId_social_login_avatar') == 1 );?> /><b>Set Display Picture for User</b>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><input type="checkbox" id="oxdOpenId_user_attributes" name="oxdOpenId_user_attributes" value="1" <?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxdOpenId_user_attributes') == 1 );?> /><b>Collect User Attributes</b>
			</td>
		</tr>
		<tr>
					<td>
						<br>
							<hr>
							<h3>Customize Text For Social Login Buttons / Icons</h3>
							</td>
		</tr>
		</table>
		<table class="oxd_openid_display_table">
		<tr>
			<td><b>Enter text to show above login widget:</b></td>
			<td><input class="oxd_openid_table_textbox" type="text" name="oxd_openid_login_widget_customize_text" <?php if(!is_oxd_registered()) echo 'disabled'?> value="<?php echo get_option('oxd_openid_login_widget_customize_text'); ?>" /></td>
		</tr>


			<tr>
												<td><b>Enter text to show on your login buttons (If you have selected shape 4 from 'Customize Login Icons' section):</b></td>
												<td><input class="oxd_openid_table_textbox" type="text" name="oxd_openid_login_button_customize_text"
													<?php if(!is_oxd_registered()) echo 'disabled'?> value="<?php echo get_option('oxd_openid_login_button_customize_text'); ?>"  /></td>
											</tr>

							<tr>

											<td><br /><input type="submit" name="submit" value="Save" style="width:100px;" <?php if(!is_oxd_registered()) echo 'disabled'?>
													class="button button-primary button-large" /></td>
											</tr>


								<tr>
									<td colspan="2">
										<hr>
										<p>
											<h3>Add Login Icons</h3>
											You can add login icons in the following areas from <strong>Display Options</strong>. For other areas(widget areas), use Login Widget.
										<ol>
											<li>Default Login Form: This option places login icons below the default login form on wp-login.</li>
											<li>Default Registration Form: This option places login icons below the default registration form.</li>
											<li>Comment Form: This option places login icons above the comment section of all your posts.</li>
										</ol>

											<h3>Add Login Icons as Widget</h3>

										<ol>
											<li>Go to Appearance->Widgets. Among the available widgets you
												will find gluu Social Login Widget, drag it to the widget area where
												you want it to appear.</li>
											<li>Now logout and go to your site. You will see app icon for which you enabled login.</li>
											<li>Click that app icon and login with your existing app account to wordpress.</li>
										</ol>
										</p>
									</td>
								</tr>
		</table>



						</div>

		</form>

<?php
}




