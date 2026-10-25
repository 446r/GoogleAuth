Features
--------
1. Adds "Sign in with Google" functionality for MantisBT login.
2. Requires an existing MantisBT account with an email address matching the Google account.
3. User signup/account creation is not supported.

How to install
--------------
1. Copy the `GoogleAuth` folder into your `plugins` folder.
2. Open MantisBT in your browser.
3. Log in as an administrator.
4. Go to **Manage** -> **Manage Plugins**.
5. Find **GoogleAuth** in the list.
6. Click **Install**.

How to use
----------
1. Go to **Manage** -> **Manage Plugins** -> **GoogleAuth** (click the plugin name to open its configuration page).
2. Copy the displayed Redirect URL.
3. Open your Google Cloud Console (GCP) and create a new OAuth client ID.
4. Paste the copied Redirect URL into **Authorized redirect URIs**.
5. Copy the Client ID and Client Secret, then paste them into the plugin's configuration page.
6. Click **Save**.

Notes
-----
1. Tested with MantisBT 2.28.4.

