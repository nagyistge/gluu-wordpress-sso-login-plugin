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



