<?php

auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

layout_page_header('GoogleAuth');
layout_page_begin('manage_overview_page.php');
print_manage_menu('manage_plugin_page.php');
?>
<div class="col-md-12 col-xs-12">
    <div class="space-10"></div>
    <div class="form-container">
        <h1><p class="text-center">GoogleAuth</p></h1>
        <div class="well">
            <strong>Google Cloud Console に登録する 承認済みのリダイレクト URI:</strong><br>
            <code><?php echo config_get('path') . plugin_page( 'redirect', /* absolute= */ true ); ?></code>
        </div>
        <div>
            <form class="form-horizontal" role="form" method="post" action="<?php echo plugin_page( 'config_update' ) ?>">
                <?php echo form_security_field( 'plugin_GoogleAuth_config_update' ) ?>

                <div class="form-group">
                    <label for="client_id" class="col-sm-3 control-label label-info label-white">
                        Client ID
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="client_id" name="client_id"
                               value="<?php echo string_html_specialchars( plugin_config_get( 'client_id', '' ) ); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="client_secret" class="col-sm-3 control-label label-info label-white">
                        Client Secret
                    </label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" id="client_secret" name="client_secret"
                               value="<?php echo string_html_specialchars( plugin_config_get( 'client_secret', '' ) ); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="login_button_text" class="col-sm-3 control-label label-info label-white">
                        Text for login button
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="login_button_text" name="login_button_text"
                               placeholder="Sign in with Google"
                               value="<?php echo string_html_specialchars( plugin_config_get( 'login_button_text', '' ) ); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="hide_login" class="col-sm-3 control-label label-info label-white">
                        通常ログインフォームを非表示<br>
                    </label>
                    <div class="col-sm-7">
                        <input type="checkbox" class="form-control" id="hide_login"
                               name="hide_login" value="true"
                               <?php if ( 'true' === plugin_config_get( 'hide_login' ) ) echo 'checked'; ?>>
                    </div>
                </div>

                <div class="form-group">
                    <label for="auto_login" class="col-sm-3 control-label label-info label-white">
                        自動ログイン<br>
                        <span class="smaller-75">ログインページ表示時に即 Google へリダイレクト</span>
                    </label>
                    <div class="col-sm-7">
                        <input type="checkbox" class="form-control" id="auto_login"
                               name="auto_login" value="true"
                               <?php if ( 'true' === plugin_config_get( 'auto_login' ) ) echo 'checked'; ?>>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                        <input id="submit" name="submit" type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
layout_page_end();
