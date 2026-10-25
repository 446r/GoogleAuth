$(document).ready(function () {
    const startUrl = $("meta[name='ga2_start_url']").attr('content');
    const hideLogin = $("meta[name='ga2_hide_login']").attr('content');

    if (!startUrl) {
        return;
    }

    var getParams = window.location.search.substr(1);
    var href = startUrl + (getParams ? '&' + getParams : '');

    var html = '';

    if ('true' === hideLogin) {
        $('#login-form').hide();
    } else {
        html += '<div id="ga2_separator"> OR </div>';
    }

    html += '<a id="ga2_login_button" href="' + href + '">'
          + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18" height="18">'
          + '<path fill="#EA4335" d="M24 9.5c3.1 0 5.6 1.1 7.5 2.8l5.6-5.6C33.5 3.5 29.1 1.5 24 1.5 14.8 1.5 7 7.4 3.9 15.5l6.6 5.1C12.1 14 17.6 9.5 24 9.5z"/>'
          + '<path fill="#4285F4" d="M46.1 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.4c-.5 2.8-2.1 5.2-4.5 6.8l6.9 5.4c4-3.7 6.3-9.2 6.3-16.2z"/>'
          + '<path fill="#FBBC05" d="M10.5 28.6c-.5-1.5-.8-3-.8-4.6s.3-3.1.8-4.6L3.9 14.3C2.1 17.6 1 21.2 1 24s1.1 6.4 2.9 9.7l6.6-5.1z"/>'
          + '<path fill="#34A853" d="M24 46.5c5.1 0 9.4-1.7 12.5-4.6l-6.9-5.4c-1.6 1.1-3.7 1.8-5.6 1.8-6.4 0-11.9-4.4-13.5-10.2l-6.6 5.1C7 40.6 14.8 46.5 24 46.5z"/>'
          + '</svg>'
          + '<span>Sign in with Google</span>'
          + '</a>';

    $(html).insertAfter('#login-form');
});
