<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link href="includes/css/oxd_openid_style.css" rel="stylesheet">
</head>
<body>
<div id="dokuwiki__site">

    <div id="dokuwiki__top" class="dokuwiki site mode_show  ">
        <div class="">
            <div class="pad group">
                        <a href="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/gluu_sso.zip">Download extension and install.</a><br/>
                <div class="page group">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/plugin.jpg">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/docu/1.png">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/docu/2.png">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/docu/3.png">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/docu/4.png">
                   <img src="https://github.com/GluuFederation/gluu-wordpress-sso-login-plugin/blob/master/docu/5.png">
                   <table width="100%">
                   		<tbody>
                   			<tr>
                   			    <td>
                   					<h3><a  id="openid_question_login" class="oxd_openid_title_panel" >Gluu SSO Plugin</a></h3>
                   					<div class="oxd_openid_help_desc" hidden="" id="openid_question_login_desc">
                                           <h4><a  id="openid_question2"  >How to add login icons to frontend login page?</a></h4>
                                           <div   id="openid_question2_desc">
                                               You can add icons to the front-end login page which enables a user to login 
                                               using an OpenID Connect Provider. This is done by referencing the 
                                               shortcode [gluu_login]. An OpenID Connect Provider (OP) may support several
                                               work flows for authentication. A client may request a different type of 
                                               authentication be specifying the
                                               <a href="http://openid.net/specs/openid-connect-core-1_0.html#AuthRequest">
                                               acr_values</a> parameter. Thus, an OP may support password authentication,
                                               Google authentication, Facebook authentication, and so on. So by mapping
                                               different acr_values, that provide the hint to the OP which authentication
                                               mechanisms to use, you can offer a number of different alternatives to
                                               user authentication. Refer to 'Shortcode' tab to customize the behavior 
                                               of these login icons.
                                           </div>
                                           <hr>
                                           <h4><a  id="openid_question4"  >How can I put gluu and social login icons on 
                                           a page without using widgets?</a></h4>
                                           <div  id="openid_question4_desc">
                                               Yes, you can login icons to any page or custom login page 
                                               using 'gluu and social login shortcode' [gluu_login]. Refer to the 
                                               'Shortcode' tab to add customizations.
                                           </div>
                                           <hr>
                                           <h4><a  id="openid_question12" >Gluu and Social Login icons are not added to 
                                           login/registration form.</a></h4>
                                           <div  id="openid_question12_desc">
                                               Your login/registration form may not be Wordpress's default 
                                               login/registration form. In this case you can add icons to yoour custom 
                                               the login/registration. Refer to the 'Shortcode' tab to add customizations.
                                           </div>
                                           <hr>
                                           <h4><a  id="openid_question3"  >How can I redirect to my blog page after login?</a></h4>
                                           <div  id="openid_question3_desc">
                                           You can set the <b>Redirect URL after login</b> in the <b>Display Option</b> 
                                           section under <b>Gluu and Social Login config</b> tab. <br>
                                           1. A landing page<br>
                                           2. Home page <br>
                                           3. Account Dashboard <br>
                                           4. Custom URL - Example: https://www.example.com <br>
                   					</div>
                   				    </div>
                   					<hr>
                   		        </td>
                   		    </tr>
                               <tr>
                                   <td>
                                   <h3><a  id="openid_question_logout" class="oxd_openid_title_panel" >Logout Redirection</a></h3>
                                           <div class="oxd_openid_help_desc" hidden="" id="openid_question_logout_desc">
                                           <h4><a  id="openid_question11"  >After logout I am redirected to blank page</a></h4>
                                           <div  id="openid_question11_desc">
                                           Your theme and the Gluu SSO plugin may conflict during logout. To resolve it 
                                           you need to uncheck <b>Enable Logout Redirection</b> checkbox under 
                                           <b>Display Option</b> of <b>Social Login</b> tab.
                                           </div>
                                       </div>
                                           <hr>
                               </td>
                               </tr>
                   		</tbody>
                   		</table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
