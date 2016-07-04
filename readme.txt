=== Gluu SSO (v 2.4.2) ===
Contributors: dollar007, nynymike, willow9886, yuriyz, yurem, tecoholic, ganesh-at-wiw
Tags: shortcodes, widgets, social login, google+, u2f token, fido, duo, gluu basic login
Requires at least: 4.3
Tested up to: 4.5
Stable tag: 2.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use OpenID Connect to login by leveraging the oxd client service demon.

== Description ==

Protect your applications with oxD to enable single sign-on and API access management with OpenID Connect.

Plugin gives access for login to your Wordpress site, with the help of GLUU server.

Use OpenID Connect to login by leveraging the oxd client service demon.

== Installation ==

![image](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-oxd-login-plugin/master/plugin.jpg)

WP-GLUU-SSO plugin gives access for login to your wordpress site, with the help of GLUU server.

Plugin will not be working if your host does not have https://.

= Step 1. Install Gluu-server =

(version 2.4.3)

If you want to use external gluu server, You can not do this step.

[Gluu-server installation gide](https://www.gluu.org/docs/deployment/).

= Step 2. Download oxD-server =

[Download oXD-server-2.4.3.Final](https://ox.gluu.org/maven/org/xdi/oxd-server/2.4.3.Final/oxd-server-2.4.3.Final-distribution.zip).

= Step 3. Unzip and run oXD-server =

1. Unzip your oXD-server.
2. Open the command line and navigate to the extracted folder in the conf directory.
3. Open oxd-conf.json file.
4. If your server is using 8099 port, please change "port" number to free port, which is not used.
5. Set parameter "op_host":"Your gluu-server-url (internal or external)"
6. Open the command line and navigate to the extracted folder in the bin directory.
7. For Linux environment, run sh oxd-start.sh&.
8. For Windows environment, run oxd-start.bat.
9. After the server starts, go to Step 4.

= Step 4. Plugin configuration =

Now in your admin menu panel you can see Gluu SSO {2.4.3 menu} tab, click on it.

= Step 5. General =

![General](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/1.png)

1. Membership: must be checked.
2. New User Default Role: please choose user role.
3. Admin Email: please add your or admin email address for registrating site in Gluu server.
4. Oxd port: choose that port which is using oxd-server (see in oxd-server/conf/oxd-conf.json file).
5. Click next to continue.

If You are successfully registered in gluu server, you will see bottom page.

![Oxd_id](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/2.png)

For making sure go to your gluu server / OpenID Connect / Clients and search  Your oxd id

If you want to reset configurations click on Reset configurations button.

= Step 6. OpenID Connect Configuration =

1. Scopes.
You can look all scopes in your gluu server / OpenID Connect / Scopes and understand the meaning of  every scope.
Scopes are need for getting loged in users information from gluu server.
Pay attention to that, which scopes you are using that are switched on in your gluu server.

![Scopes1](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/3.png)

2. Custom scripts.

![Customscripts](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/5.png)

You can look all custom scripts in your gluu server / Configuration / Manage Custom Scripts / and enable login type, which type you want.
Custom Script represent itself the type of login, at this moment gluu server supports (U2F, Duo, Google +, Basic) types.

- Pay attention to that.

1. Which custom script you enable in your wordpress site in order it must be switched on in gluu server too.
2. Which custom script you will be enable in OpenID Connect Configuration page, after saving that will be showed in Wordpress Configuration page to.
3. When you create new custom script, both fields are required.

= Step 7. Wordpress Configuration =

1 Current Shortcode

Use the shortcode in the content of the required page/post where you want to display login icons.
Example: ```[gluu_login]```
For Icons
You can use request attributes to customize the icons. All attributes are optional.
Example: ```[gluu_login shape="oval" theme="default" space="5" size="40"]```

You can use a shortcode in a PHP file like this:```<?php echo do_shortcode(‘SHORTCODE’) /?>```
Replace SHORTCODE in the above code with the required shortcode like ```[gluu_login theme="default"]```, so the final code looks like following :
```
<?php echo do_shortcode('[gluu_login theme="default"]') ?>
```

2 Customize Login Icons

Pay attention to that, if custom scripts are not enabled, nothing will be showed.
Customize shape, space between icons and size of the login icons.

3 Display Options

1. If you enable Default Login Form,than login icons will be showed in wordpress Default Login page .
2. If you enable Default Registration Form,than login icons will be showed in wordpress Default Registration page .
3. If you enable Comment Form,than login icons will be showed near wordpress Comment Form.
4. If you enable WooCommerce Login Form,than login icons will be showed in wordpress WooCommerce Login page.

![WordpressConfiguration](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/6.png)

= Step 8. Gluu SSO Widget =

You can use plugin also as widget.
In your widget page (https://{site-base-url}/wp-admin/widgets.php) find Gluu SSO Widget and use.

= Step 9. Show icons in frontend =

![frontend](https://raw.githubusercontent.com/GluuFederation/gluu-wordpress-sso-login-plugin/master/docu/7.png)

== Screenshots ==

1. General.
2. Oxd id.
3. OpenID Connect Configuration (Scopes).
4. OpenID Connect Configuration (Custom scripts).
5. Wordpress Configuration.
6. Show icons in frontend.

