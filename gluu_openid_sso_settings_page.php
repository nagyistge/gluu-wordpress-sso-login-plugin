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


