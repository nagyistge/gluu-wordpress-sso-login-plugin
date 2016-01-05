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
            <a class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Gluu and social login config</a>
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
        </div>
    </div>
	<?php
}
function oxd_openid_show_new_registration_page() {
	update_option ( 'oxd_openid_new_registration', 'true' );
	global $current_user;
		get_currentuserinfo();
	?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_connect_register_site_oxd" />
        <div class="oxd_openid_table_layout">
            <?php if(!is_oxd_registered()) { ?>
                <div class="mess_red">
                    Please enter gluu and oxd configuration for continue.
                </div>
            <?php } ?>
            <h3>Enter gluu and oxd configuration.</h3>
            <div class="mess_red">1. If You have not installed gluu server (<a target="_blank" href="http://www.gluu.org/docs////admin-guide/deployment/ubuntu/">how to install and run</a>) in Your web server, Please download, install and run gluu server. For more information <a target="_blank" href="http://www.gluu.org/">click me</a>.</div>
            <div class="mess_red">2. Please download, install and run OXD server (<a target="_blank" href="http://ox.gluu.org/doku.php?id=oxd:home&s[]=mvn">how to install and run</a>) in Your web server. For more information <a target="_blank" href="http://ox.gluu.org/doku.php?id=oxd:rp">click me</a>.</div>
            <hr>
            <table class="oxd_openid_settings_table">
                    <tr>
                        <td><b><font color="#FF0000">*</font>Membership:</b></td>
                        <td>
                            <fieldset><legend class="screen-reader-text"><span><b><font color="#FF0000">*</font>Membership:</b></span></legend><label for="users_can_register">
                                    <input name="users_can_register" type="checkbox" id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                   <b>Anyone can register:</b> </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="default_role"><b><font color="#FF0000">*</font>New User Default Role:</b></label></td>
                        <td>
                            <select name="default_role" id="default_role">
                                <option <?php if(get_option('default_role') == "author"){ echo "selected='selected'";} ?> value="author">Author</option>
                                <option <?php if(get_option('default_role') == "subscriber"){ echo "selected='selected'";} ?> value="subscriber">Subscriber</option>
                                <option <?php if(get_option('default_role') == "contributor"){ echo "selected='selected'";} ?> value="contributor">Contributor</option>
                                <option <?php if(get_option('default_role') == "editor"){ echo "selected='selected'";} ?> value="editor">Editor</option>
                                <option <?php if(get_option('default_role') == "administrator"){ echo "selected='selected'";} ?> value="administrator">Administrator</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="default_role"><b>Application Type:</b></label></td>
                        <td>
                            <select name="app_type" id="application_type">
                                <option value="web">Web</option>
                                <option value="native">Native</option>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Email:</b></td>
                        <td><input class="oxd_openid_table_textbox" type="email" name="email"
                            required placeholder="person@example.com"
                            value="<?php echo $current_user->user_email;?>" /></td>
                    </tr>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Gluu server url:</b>

                        </td>
                        <td>
                            <br/>
                            <input class="oxd_openid_table_textbox" required type="url" name="gluu_server_url" placeholder="Please enter gluu server url (for example https://ce-dev.gluu.org)" />
                            <p style="color:red" class="description">It must be the same with oxd server's in oxd-conf.json file's with parameter "op_host".</p>
                        </td>
                    </tr>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Oxd host ip:</b></td>
                        <td>
                            <br/>
                            <input class="oxd_openid_table_textbox" required type="text" name="oxd_host_ip" value="127.0.0.1" placeholder="Please enter oxd host ip address (for example 127.0.0.1) ." />
                        </td>
                    </tr>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Oxd host port:</b></td>
                        <td>
                            <br/>
                            <input class="oxd_openid_table_textbox" required type="number" name="oxd_host_port" value="8099" placeholder="Please enter oxd open port (for example 8099). (Min. number 0, Max. number 65535)" />
                            <p style="color:red" class="description">It must be the same with oxd server's in oxd-conf.json file's with parameter "port".</p>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><br /><input type="submit" name="submit" value="Next" style="width:100px;"
                            class="button button-primary button-large" /></td>
                    </tr>
                </table>
        </div>
    </form>
    <?php
}
function oxd_openid_apps_config() {
	?>
    <form id="form-apps" name="form-apps" method="post" action="">
        <input type="hidden" name="option" value="oxd_openid_enable_apps" />
        <div class="oxd_openid_table_layout">
            <?php if(!is_oxd_registered()) { ?>
                <div class="mess_red">
                    Please enter gluu and oxd configuration for continue.
                </div>
            <?php } ?>
            <table>
                <tr>
                    <td colspan="2">
                        <h4 style="margin-bottom:0 !important">Current Shortcode</h4>
                        <?php if(get_option('oxd_openid_login_theme') != 'longbutton'){?>
                            <code>[gluu_login  shape="<?php echo get_option('oxd_openid_login_theme');?>" theme="<?php echo get_option('oxd_openid_login_custom_theme');?>" space="<?php echo get_option('oxd_login_icon_space')?>" size="<?php echo get_option('oxd_login_icon_custom_size')?>"]</code><br>
                        <?php }else{?>
                            <code>[gluu_login  shape="<?php echo get_option('oxd_openid_login_theme');?>" theme="<?php echo get_option('oxd_openid_login_custom_theme');?>" space="<?php echo get_option('oxd_login_icon_space')?>" width="<?php echo get_option('oxd_login_icon_custom_width')?>" height="<?php echo get_option('oxd_login_icon_custom_height')?>"]</code><br>
                        <?php }?>
                        <h3>Gluu login config
                            <input type="submit" name="submit" value="Save" style="float:right; margin-right:2%; margin-top: -3px;width:100px;" <?php if(!is_oxd_registered()) echo 'disabled'?> class="button button-primary button-large" />
                        </h3>
                        <b>Select applications to enable login for your users. Customize your login icons using a range of shapes, themes and sizes. You can choose different places to display these icons and also customize redirect url after login.</b>
                    </td>
                </tr>
            </table>
            <table>
                <h3>Select Apps</h3>
                <p>Select applications to enable social login</p>
                <div class="mess_red">
                    If you have not enable custom script in gluu server, please go to Your gluu server tab Manage Custom Scripts and enable Custom Scripts.
                </div>
                <tr>
                    <td>
                        <table style="width:100%">
                            <tr>
                                <?php
                                $custom_scripts = get_option('oxd_openid_custom_scripts');
                                foreach($custom_scripts as $custom_script){
                                    ?>
                                    <td>
                                        <input type="checkbox" <?php if(!is_oxd_registered()) echo 'disabled'?> id="<?php echo $custom_script['value'];?>_enable" class="app_enable" name="<?php echo 'oxd_openid_'.$custom_script['value'].'_enable';?>" value="1" onchange="previewLoginIcons();"
                                            <?php checked( get_option('oxd_openid_'.$custom_script['value'].'_enable') == 1 );?> /><strong><?php echo $custom_script['name'];?></strong>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td>
                        <br>
                        <hr>
                        <h3>Customize Login Icons</h3>
                        <p>Customize shape, theme and size of gluu and social login config icons</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Shape</b>
                        <b style="margin-left:130px; display: none">Theme</b>
                        <b style="margin-left:130px;">Space between Icons</b>
                        <b style="margin-left:86px;">Size of Icons</b>
                    </td>
                </tr>
                <tr>
                    <td class="oxd_openid_table_td_checkbox">
                        <input type="radio"    name="oxd_openid_login_theme" value="circle" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_size').value ,'circle',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)"
                            <?php if(!is_oxd_registered()) echo 'disabled'?>
                                    <?php checked( get_option('oxd_openid_login_theme') == 'circle' );?> />Round
                        <span style="margin-left:106px; display: none">
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
                        <span style="margin-left:50px; display: none">
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
                        <span style="margin-left:113px; display: none">
                            <input id="oxd_login_icon_custom_color" style="width:135px;" name="oxd_login_icon_custom_color"  class="color" value="<?php echo get_option('oxd_login_icon_custom_color')?>" onchange="oxdLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value)" <?php if(!is_oxd_registered()) echo 'disabled'?>>
                        </span>
                    </td>
                </tr>
                <tr style="display: none">
                    <td class="oxd_openid_table_td_checkbox">
                        <input type="radio" id="iconwithtext"   name="oxd_openid_login_theme" value="longbutton" onclick="checkLoginButton();oxdLoginPreview(document.getElementById('oxd_login_icon_width').value ,'longbutton',setLoginCustomTheme(),document.getElementById('oxd_login_icon_custom_color').value,document.getElementById('oxd_login_icon_space').value,document.getElementById('oxd_login_icon_height').value)"
                        <?php if(!is_oxd_registered()) echo 'disabled';  checked( get_option('oxd_openid_login_theme') == 'longbutton' );?> />Long Button with Text
                   </td>
                </tr>
                <tr>
                    <td>
                    <br><b>Preview : </b>
                    <br/><span hidden id="no_apps_text">No apps selected</span>
                    <div>
                        <?php
                        $custom_scripts = get_option('oxd_openid_custom_scripts');
                        foreach($custom_scripts as $custom_script){
                            ?>
                                <img class="oxd_login_icon_preview" id="oxd_login_icon_preview_<?php echo $custom_script['value'];?>" src="<?php echo $custom_script['image'];?>" />
                            <?php
                        }
                        ?>
                    </div>
                    <div>
                        <?php
                        foreach($custom_scripts as $custom_script){
                            ?>
                            <a id="oxd_login_button_preview_<?php echo $custom_script['value'];?>" class="btn btn-block btn-defaulttheme btn-social btn-facebook btn-custom-size"> <i class="fa <?php echo $custom_script['icon_class'];?>"></i><?php
                                echo get_option('oxd_openid_login_button_customize_text'); 	?> <?php echo $custom_script['name'];?></a>
                            <?php
                        }
                        ?>

                    </div>
                    <div>
                        <?php
                        foreach($custom_scripts as $custom_script){
                            ?>
                            <i class="oxd_custom_login_icon_preview fa <?php echo $custom_script['icon_class'];?>" id="oxd_custom_login_icon_preview_<?php echo $custom_script['value'];?>"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
                            <?php
                        }
                        ?>
                    </div>
                    <div>
                        <?php
                        foreach($custom_scripts as $custom_script){
                            ?>
                            <a id="oxd_custom_login_button_preview_<?php echo $custom_script['value'];?>" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa <?php echo $custom_script['icon_class'];?>"></i><?php
                                echo get_option('oxd_openid_login_button_customize_text'); 	?> <?php echo $custom_script['name'];?></a>
                            <?php
                        }
                        ?>
                    </div>
                </td>
                </tr>
                <tr style="display: none">
                    <td>
                        <br><strong>*NOTE:</strong><br/>Custom background: This will change the background color of login icons.
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
                        <?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_login_enable') == 1 );?> />Default Login Form
                    </td>
                </tr>
                <tr>
                    <td class="oxd_openid_table_td_checkbox">
                        <input type="checkbox" id="default_register_enable" name="oxd_openid_default_register_enable" value="1"
                        <?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_register_enable') == 1 );?> />Default Registration Form
                    </td>
                </tr>
                <tr>
                    <td class="oxd_openid_table_td_checkbox">
                        <input type="checkbox" id="default_comment_enable" name="oxd_openid_default_comment_enable" value="1"
                        <?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_default_comment_enable') == 1 );?> />Comment Form
                    </td>
                </tr>
                <tr>
                    <td class="oxd_openid_table_td_checkbox">
                        <input type="checkbox" id="woocommerce_login_form" name="oxd_openid_woocommerce_login_form" value="1"
                        <?php if(!is_oxd_registered()) echo 'disabled'?>	<?php checked( get_option('oxd_openid_woocommerce_login_form') == 1 );?> />WooCommerce Login Form
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
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
                <tr>
                    <td>&nbsp;</td>
                </tr>
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
                        If Auto-register users is unchecked, users will not be able to register using Gluu and  Social Login. The users who already have an account will be able to login.  This setting stands true only when users are registering using Gluu and Social Login. This will not interfere with users registering through the regular WordPress.
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
                                            'oxd_openid_custom_scripts'=>get_option('oxd_openid_custom_scripts'),
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
                <td><input type="checkbox" id="oxdOpenId_gluu_login_avatar" name="oxdOpenId_gluu_login_avatar" value="1" <?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxdOpenId_gluu_login_avatar') == 1 );?> /><b>Set Display Picture for User</b>
                </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                <td><input type="checkbox" id="oxdOpenId_user_attributes" name="oxdOpenId_user_attributes" value="1" <?php if(!is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('oxdOpenId_user_attributes') == 1 );?> /><b>Collect User Attributes</b>
                </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <hr>
                        <h3>Customize Text For Gluu and Social Login Buttons / Icons</h3>
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
                    <td><input class="oxd_openid_table_textbox" type="text" name="oxd_openid_login_button_customize_text" <?php if(!is_oxd_registered()) echo 'disabled'?> value="<?php echo get_option('oxd_openid_login_button_customize_text'); ?>"  /></td>
                </tr>
                <tr>
                    <td>
                        <br />
                        <input type="submit" name="submit" value="Save" style="width:100px;" <?php if(!is_oxd_registered()) echo 'disabled'?> class="button button-primary button-large" />
                    </td>
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
                                will find Gluu and Social Login Widget, drag it to the widget area where
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
function oxd_openid_login_config_info(){
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    wp_enqueue_script( 'oxd_scope_custom_script',plugins_url('includes/js/oxd_scope_custom_script.js', __FILE__), array('jquery'));
    ?>
	<div class="oxd_openid_table_layout">
        <?php
         $options = get_option('oxd_config');
         if(!is_oxd_registered()) {
         ?>
            <div class="mess_red">
            Please enter gluu and oxd configuration for continue.
            </div>
        <?php } ?>

        <div>
            <div class="about">
                <img src="<?php echo plugins_url('includes/images/logo.png', __FILE__)?>" />
                    <h1>Oxd server config</h1>
            </div>
            <form action="" method="post">
                <input type="hidden" name="option" value="oxd_openid_config_info_hidden" />
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                Oxd Host ip
                            </th>
                            <td>
                                <input <?php  echo 'disabled'?> type="text" placeholder="Please enter oxd host ip address (for example 127.0.0.1) ." name="oxd_host_ip" value="<?php echo esc_attr($options['oxd_host_ip']); ?>" size="100%">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Oxd Port number
                            </th>
                            <td>
                                <input <?php  echo 'disabled'?> type="text" placeholder="Please enter oxd open port (for example 8099)." name="oxd_host_port" value="<?php echo esc_attr($options['oxd_host_port']); ?>" size="100%">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Gluu Server Url
                            </th>
                            <td>
                                <input <?php echo 'disabled'?> type="text" placeholder="Please enter gluu server url (for example https://ce-dev.gluu.org) ." name="gluu_server_url" value="<?php echo esc_attr($options['gluu_server_url']); ?>" size="100%" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Your site oxd id
                            </th>
                            <td>
                                <input <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('oxd_id'); ?>" size="100%" />
                            </td>
                        </tr>
                        <tr  style="display: none">
                            <th scope="row">
                                Response Types
                            </th>
                            <td>
                                <div >
                                    <div>
                                        <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="checkbox" name="response_types[]" <?php if($options && in_array("code", $options['response_types'])){ echo "checked";} ?> id="code" value="code">
                                        <label  for="code">Code</label>
                                        <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="checkbox" name="response_types[]" <?php if($options && in_array("id_token", $options['response_types'])){ echo "checked";} ?> id="id_token" value="id_token">
                                        <label  for="id_token">Id_Token</label>
                                        <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="checkbox" name="response_types[]" <?php if($options && in_array("token", $options['response_types'])){ echo "checked";} ?> id="token" value="token">
                                        <label  for="token">Token</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Scopes
                            </th>
                            <?php $get_scopes = get_option('oxd_openid_scops');
                            ?>
                            <td>
                                <div >
                                    <div>
                                        <?php foreach($get_scopes as $scop) :?>
                                            <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="checkbox" name="scope[]" <?php if($options && in_array($scop, $options['scope'])){ echo "checked";} ?> id="<?php echo $scop;?>" value="<?php echo $scop;?>">
                                            <label  for="<?php echo $scop;?>"><?php echo $scop;?></label>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">

                            </th>
                            <td>
                                <div class="mess_red">
                                    You can modificate current scopes or add new custom scope in your Gluu Server - OpenID Connect - Scopes.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Add multiple Scope
                            </th>
                            <td>
                                <div >
                                    <div id="p_scents">
                                        <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="button" id="add_new_scope" value="Add row">
                                        <p>
                                        <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="text" name="new_scope[]" placeholder="Input scope name" />
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">

                            </th>
                            <td>
                                <div class="mess_red">
                                    You can modificate current scripts or add new custom scripts in your Gluu Server - Configuration - Menage Custom Scripts.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <p>Add multiple custom scripts</p>
                                <p style="color:red" class="description">Necessary to fill the hole row</p>
                            </th>
                            <td>
                                <div >

                                    <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="button" id="add_new_suctom_script"  value="Add row"/>
                                    <input type="hidden" name="count_scripts" value="1" id="count_scripts">
                                    <div id="p_scents_script">
                                        <p>
                                            <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="text" style="margin-right: 5px " name="new_custom_script_name_1" size="30" placeholder="Input name (example Google+)" />
                                            <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="text" style="margin-right: 5px " name="new_custom_script_value_1" size="40" placeholder="Input name in gluu server (example gplus)" />
                                            <input type="hidden" name="image_url_1" id="image_url_1" class="regular-text">
                                            <input <?php if(!is_oxd_registered()) echo 'disabled'?> type="button" name="upload-btn" id="upload-btn_1" onclick="upload_this(1)" class="button-secondary" value="Upload app image (120x120) ">
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div style="border-radius: 10px;  background-color: #01ffa0; width: 100%;height: 30px; font-size: 30px; wcursor: pointer; text-align: center;">
                    <a style="font-size: 20px; color: black; font-weight: bold; cursor: pointer; text-align: center;" id="show_script_table">Show or hide all custom scripts for delete</a>
                </div>
                <br/>
                <table id="custom_script_table" class="form-table" style="display: none; text-align: center">
                    <tr>
                        <th> <h3>N</h3> </th>
                        <th><h3>Name</h3></th>
                        <th><h3>Name in gluu server</h3></th>
                        <th><h3>Image</h3></th>
                        <th><h3>Delete</h3></th>
                    </tr>
                    <?php
                    $custom_scripts = get_option('oxd_openid_custom_scripts');
                    $n = 0;
                    foreach($custom_scripts as $custom_script){
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n;?></td>
                            <td><?php echo $custom_script['name'];?></td>
                            <td><?php echo $custom_script['value'];?></td>
                            <td><img src="<?php echo $custom_script['image'];?>" width="40px" height="40px"/></td>
                            <td><input type="button" onclick="delete_custom_script('<?php echo $custom_script['value'];?>')" class="button button-primary button-large" <?php if(!is_oxd_registered()) echo 'disabled'?> value="Delete" name="set_oxd_config" /></td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
                <br/>
                <input type="submit" class="button button-primary button-large" <?php if(!is_oxd_registered()) echo 'disabled'?> value="Save" name="set_oxd_config" />
            </form>
        </div>
	</div>
<?php
}
function oxd_openid_shortcode_info(){
?>
	<div class="oxd_openid_table_layout">
	    <?php if(!is_oxd_registered()) { ?>
		<div class="mess_red">
		    Please enter gluu and oxd configuration for continue.
		</div>
	<?php } ?>
		<table>
            <tr>
                <td colspan="2">
                    <h3>Shortcode</h3>
                    <p>If any section is not opening, press CTRL + F5 to clear cache.<p>
                </td>
            </tr>
            <tr>
                <td>
                    <h3><a id="openid_login_shortcode_title"  aria-expanded="false" >Gluu and Social Login Shortcode</a></h3>
                    <div hidden="" id="openid_login_shortcode" style="font-size:13px !important">
                        Use gluu and social login Shortcode in the content of required page/post where you want to display Gluu and Social Login Icons.<br>
                        <b>Example:</b> <code>[gluu_login]</code>
                        <h4 style="margin-bottom:0 !important">For Icons</h4>
                        You can use  different attribute to customize gluu and social login icons. All attributes are optional.<br>
                        <b>Example:</b> <code>[gluu_login  shape="<?php echo get_option('oxd_openid_login_theme');?>" theme="<?php echo get_option('oxd_openid_login_custom_theme');?>" space="<?php echo get_option('oxd_login_icon_space')?>" size="<?php echo get_option('oxd_login_icon_custom_size')?>"]</code><br>
                        <div style="display: none">
                            <h4 style="margin-bottom:0 !important">For Long-Buttons</h4>
                            You can use different attribute to customize gluu and social login buttons. All attributes are optional.<br>
                            <b>Example:</b> <code>[gluu_login  shape="longbuttonwithtext" theme="default" space="<?php echo get_option('oxd_login_icon_space')?>" width="300" height="50"]</code>
                            <br>
                            <h4 style="margin-bottom:0 !important">Available values for attributes</h4>
                            <b>shape</b>: round, roundededges, square, longbuttonwithtext<br>
                            <b>theme</b>: default, custombackground<br>
                            <b>size</b>: Any value between 20 to 100<br>
                            <b>space</b>: Any value between 0 to 100<br>
                            <b>width</b>: Any value between 200 to 1000<br>
                            <b>height</b>: Any value between 35 to 50<br>
                        </div>
                    </div>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <h3><a id="openid_shortcode_inphp_title">Shortcode in php file</a></h3>
                    <div hidden="" id = "openid_shortcode_inphp" style="font-size:13px !important">
                    You can use shortcode in PHP file as following: &nbsp;&nbsp;
                    &nbsp;
                    <code>&lt;&#63;php echo do_shortcode(‘SHORTCODE’) /&#63;&gt;</code>
                    <br>
                    Replace SHORTCODE in above code with the required shortcode like [gluu_login theme="default"], so the final code looks like following :
                    <br>
                    <code>&lt;&#63;php echo do_shortcode('[gluu_login theme="default"]') &#63;&gt;</code>
                    </div>
                    <hr>
                </td>
            </tr>
        </table>
	</div>
<?php
}
function oxd_openid_troubleshoot_info(){ ?>
	<div class="oxd_openid_table_layout">
	    <?php if(!is_oxd_registered()) { ?>
		<div class="mess_red">
		    Please enter gluu and oxd configuration for continue.
		</div>
	    <?php } ?>
		<table width="100%">
		    <tbody>
                <tr>
                    <td>
                        <h3><a  id="openid_question_login" class="oxd_openid_title_panel" >Gluu and Social Login</a></h3>
                        <div class="oxd_openid_help_desc" hidden="" id="openid_question_login_desc">
                            <h4><a  id="openid_question2"  >How to add login icons to frontend login page?</a></h4>
                            <div   id="openid_question2_desc">
                                You can add social and gluu login icons to frontend login page using our shortcode [gluu_login].
                                Refer to 'Shortcode' tab to add customizations to Shortcode.
                            </div>
                            <hr>
                            <h4><a  id="openid_question4"  >How can I put gluu and social login icons on a page without using widgets?</a></h4>
                            <div  id="openid_question4_desc">
                                You can add social and gluu login icons to any page or custom login page using 'gluu and social login shortcode' [gluu_login].
                                Refer to 'Shortcode' tab to add customizations to Shortcode.
                            </div>
                            <hr>
                            <h4><a  id="openid_question12" >Gluu and Social Login icons are not added to login/registration form.</a></h4>
                            <div  id="openid_question12_desc">
                                Your login/registration form may not be wordpress's default login/registration form.
                                In this case you can add social and gluu login icons to custom login/registration form using 'gluu and social login shortcode' [gluu_login].
                                Refer to 'Shortcode' tab to add customizations to Shortcode.
                            </div>
                            <hr>
                            <h4><a  id="openid_question3"  >How can I redirect to my blog page after login?</a></h4>
                            <div  id="openid_question3_desc">
                            You can select one of the options from <b>Redirect URL after login</b> of <b>Display Option</b> section under <b>Gluu and Social Login config</b> tab. <br>
                            1. Same page where user logged in <br>
                            2. Homepage <br>
                            3. Account Dsahboard <br>
                            4. Custom URL - Example: https://www.example.com <br>
                        </div>
                        </div>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>
                            <a  id="openid_question_logout" class="oxd_openid_title_panel" >Logout Redirection</a>
                        </h3>
                            <div class="oxd_openid_help_desc" hidden="" id="openid_question_logout_desc">
                                <h4><a  id="openid_question11"  >After logout I am redirected to blank page</a></h4>
                                <div  id="openid_question11_desc">
                                    Your theme and Gluu and Social Login plugin may conflict during logout. To resolve it you need to uncheck <b>Enable Logout Redirection</b> checkbox under <b>Display Option</b> of <b>Social Login</b> tab.
                                </div>
                            </div>
                        <hr>
                    </td>
                </tr>
            </tbody>
		</table>
	</div>
    <?php
}



