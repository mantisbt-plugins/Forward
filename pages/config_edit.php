<?php

# authenticate
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

# Read results
form_security_validate( 'plugin_forward_config_update' );
$f_forward_threshold = gpc_get_int( 'forward_threshold', [DEVELOPER] );
$f_forward_address = gpc_get_string( 'forward_address', 'whoever@domain.com' );
//$f_forward_customfields = gpc_get_string( 'forward_cf', '' );
//$f_forward_attachments = gpc_get_int( 'forward_at', 0 );

# update results
plugin_config_set( 'forward_threshold', $f_forward_threshold );
plugin_config_set( 'forward_address', $f_forward_address );
//plugin_config_set( 'forward_customfields', $f_forward_customfields );
//plugin_config_set( 'forward_attachments', $f_forward_attachments );


form_security_purge( 'plugin_forward_config_update' );

# redirect
print_successful_redirect( plugin_page( 'config', true ) );
