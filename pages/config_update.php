<?php

form_security_validate( 'plugin_GoogleAuth_config_update' );
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

plugin_config_set( 'client_id',         gpc_get_string( 'client_id',     '' ) );
plugin_config_set( 'client_secret',     gpc_get_string( 'client_secret', '' ) );
plugin_config_set( 'login_button_text', gpc_get_string( 'login_button_text', '' ) );
plugin_config_set( 'hide_login',        gpc_get_string( 'hide_login', '' ) === 'true' ? 'true' : 'false' );
plugin_config_set( 'auto_login',        gpc_get_string( 'auto_login', '' ) === 'true' ? 'true' : 'false' );

form_security_purge( 'plugin_GoogleAuth_config_update' );
print_successful_redirect( plugin_page( 'config', /* absolute= */ true ) );
