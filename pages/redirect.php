<?php

require_once 'functions.php';

$client = new GoogleAuthClient(plugin_config_get('client_id'), plugin_config_get('client_secret'));
$client->setRedirectUrl(config_get('path') . plugin_page('redirect', /* absolute= */ true));
$token = $client->exchangeCodeForToken();

$user_info = null;
if ($token !== null) {
    $user_info = $client->fetchUserInfo($token['access_token']);
}

$login_success = false;
$user_id = null;
if ($user_info !== null) {
    $email = $user_info['email'] ?? '';
    if ( empty( $user_info['email_verified'] ) ) {
        $email = '';
    }
    if (!empty($email)) {
        $user_id = user_get_id_by_email($email);
        if ($user_id !== false) {
            if (user_is_enabled($user_id)
                        && user_is_login_request_allowed($user_id)
                        && !user_is_anonymous($user_id)) {
                $login_success = true;
            }
        }
    }
}

if (!$login_success) {
    layout_login_page_begin();
    echo '
		<div class="col-md-offset-3 col-md-6 col-sm-10 col-sm-offset-1">
			<div class="login-container">
				<div class="space-12 hidden-480"></div>'
                . layout_login_page_logo() . '
				<div class="space-24 hidden-480"></div>
				<div class="alert alert-danger">
					<p>' . plugin_config_get('user_not_available',
                            'There is no account associated with the provided login data.') . '</p>
				</div>
			</div>
		</div>';
    layout_login_page_end();
    return;
}

// login
user_increment_login_count($user_id);
user_reset_failed_login_count_to_zero($user_id);
user_reset_lost_password_in_progress_count_to_zero($user_id);

auth_set_cookies($user_id, false);
auth_set_tokens($user_id);

// redirect
$get_stash = $_SESSION['plugin_GoogleAuth_get_stash'] ?? array();
$redirect_url = '';

if (!empty($get_stash['return'])) {
    $return = $get_stash['return'];
    // 先頭スラッシュがなければ補完
    if ($return[0] !== '/') {
        $redirect_url .= '/';
    }
    $redirect_url .= $return;
    preg_match('#^.*://[^/]+(.*)$#', config_get('path'), $m);
    $redirect_url = substr($redirect_url, strlen($m[1] ?? '') - 1);
} else {
    $redirect_url = '/index.php';
}

unset($_SESSION['plugin_GoogleAuth_get_stash']);

print_header_redirect(string_sanitize_url(rtrim($redirect_url, '/')));
