<?php

class GoogleAuthClient
{
    const AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth';
    const TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';
    const USERINFO_ENDPOINT = 'https://www.googleapis.com/oauth2/v3/userinfo';

    private string $client_id;
    private string $client_secret;
    private string $redirect_url = '';
    private array $scopes = array('email', 'profile');

    public function __construct(string $client_id, string $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function setRedirectUrl(string $url): void
    {
        $this->redirect_url = $url;
    }

    public function setScope(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function requestAuthorization(): void
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['plugin_GoogleAuth_oauth_state'] = $state;

        $params = http_build_query(array(
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_url,
            'response_type' => 'code',
            'scope' => implode(' ', $this->scopes),
            'state' => $state,
            'access_type' => 'online',
            // 'prompt'     => 'select_account',
        ));

        header('Location: ' . self::AUTH_ENDPOINT . '?' . $params);
        exit;
    }

    public function exchangeCodeForToken(): ?array
    {
        $received_state = gpc_get_string('state', '');
        $expected_state = $_SESSION['plugin_GoogleAuth_oauth_state'] ?? '';
        if (empty($received_state) || $received_state !== $expected_state) {
            return null;
        }
        unset($_SESSION['plugin_GoogleAuth_oauth_state']);

        $error = gpc_get_string('error', '');
        if (!empty($error)) {
            return null;
        }

        $code = gpc_get_string('code', '');
        if (empty($code)) {
            return null;
        }

        $post_data = http_build_query(array(
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_url,
            'grant_type' => 'authorization_code',
        ));
        $response = $this->httpPost(self::TOKEN_ENDPOINT, $post_data);
        if ($response === null) {
            return null;
        }

        $token = json_decode($response, true);
        if (empty($token['access_token'])) {
            return null;
        }

        return $token;
    }

    public function fetchUserInfo(string $access_token): ?array
    {
        $response = $this->httpGet(self::USERINFO_ENDPOINT, $access_token);
        if ($response === null) {
            return null;
        }

        $info = json_decode($response, true);
        if (empty($info['email'])) {
            return null;
        }

        return $info;
    }

    private function httpPost(string $url, string $post_data): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_TIMEOUT => 10,
        ));
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($response !== false && $http_code === 200) ? $response : null;
    }

    private function httpGet(string $url, string $access_token): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token),
            CURLOPT_TIMEOUT => 10,
        ));
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($response !== false && $http_code === 200) ? $response : null;
    }
}
