<?php

class GoogleAuthPlugin extends MantisPlugin {
	private $login_pages;
	private $current_page;

	function register() {
		$this->name        = 'GoogleAuth';
		$this->description = 'Sign in with Google';
		$this->page        = 'config';
		$this->version  = '0.1';
		$this->author  = '';
		$this->contact = '';
		$this->url     = '';
	}

	function init() {
		$this->login_pages  = array( 'login_page.php', 'login_password_page.php' );
		$this->current_page = basename( $_SERVER['PHP_SELF'] );
	}

	function hooks() {
		return array(
			'EVENT_LAYOUT_RESOURCES' => 'resources',
		);
	}

	// デフォルト設定値
	function config() {
		return array(
			'client_id'         => '',
			'client_secret'     => '',
			'login_button_text' => '',
			'hide_login'        => 'false',
			'auto_login'        => 'false',
		);
	}

	// ログインページへの HTML/JS 注入
	function resources( $p_event )
    {
		// ログインページ以外では何もしない
		if ( !in_array( $this->current_page, $this->login_pages ) ) {
			return '';
		}

		// 自動ログインが有効なら直接 start.php へリダイレクト
		if ( 'true' === plugin_config_get( 'auto_login' ) ) {
			$_SESSION['plugin_GoogleAuth_get_stash'] = $_GET;
			print_header_redirect( plugin_page( 'start' ) );
		}

		$button_text = plugin_config_get( 'login_button_text' );
		if ( empty( $button_text ) ) {
			$button_text = 'Sign in with Google';
		}

		$injected = '
			<meta name="ga2_start_url"  content="' . plugin_page( 'start' ) . '" />
			<meta name="ga2_hide_login" content="' . plugin_config_get( 'hide_login' ) . '" />

			<style>
				#ga2_separator {
					display: flex;
					align-items: center;
					text-align: center;
					margin-block: 1rem;
					color: #889;
				}
				#ga2_separator::before,
				#ga2_separator::after {
					content: "";
					flex: 1;
					border-bottom: 1px solid #889;
				}
				#ga2_separator:not(:empty)::before { margin-right: .25em; }
				#ga2_separator:not(:empty)::after  { margin-left:  .25em; }

				#ga2_login_button {
					display: flex;
					align-items: center;
					justify-content: center;
					gap: .5rem;
					padding: .75rem 1.5rem;
					background-color: #fff;
					border: 1px solid #dadce0;
					border-radius: 4px;
					color: #3c4043;
					font-size: 14px;
					font-weight: 500;
					cursor: pointer;
					text-decoration: none;
				}
				#ga2_login_button:hover {
					background-color: #f8f9fa;
					text-decoration: none;
					border-color: #c6c6c6;
				}
				#ga2_login_button img {
					width: 18px;
					height: 18px;
				}
			</style>

			<script type="text/javascript" src="' . plugin_file( 'plugin.js' ) . '"></script>
';

		return $injected;
	}
}
