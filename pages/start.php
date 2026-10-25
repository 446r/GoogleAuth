<?php

require_once 'functions.php';

$client_id = plugin_config_get('client_id');
$client_secret = plugin_config_get('client_secret');

if (empty($client_id) || empty($client_secret)) {
    print_header_redirect('login_page.php');
}

$_SESSION['plugin_GoogleAuth_get_stash'] = $_GET;

$client = new GoogleAuthClient($client_id, $client_secret);
$client->setRedirectUrl(config_get('path') . plugin_page('redirect', /* absolute= */ true));
// $client->setScope( ['email', 'profile' ] );
$client->requestAuthorization();
