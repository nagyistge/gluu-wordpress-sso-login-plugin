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




