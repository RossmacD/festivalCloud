<?php
require_once 'vendor/autoload.php';

require_once 'classes/Auth.php';
use FestivalCloud\Auth;

if (!isset($_GET['access_token'])) {
    ?>
    <script>
        var url_str = window.location.href;
        //On successful authentication, AWS Cognito will redirect to Call-back URL and pass the access_token as a request parameter. 
        //If you notice the URL, a “#” symbol is used to separate the query parameters instead of the “?” symbol. 
        //So we need to replace the “#” with “?” in the URL and call the page again.

        if (url_str.includes("#")) {
            var url_str_hash_replaced = url_str.replace("#", "?");
            window.location.href = url_str_hash_replaced;
        }
    </script>
<?php
} else {
        $auth = new Auth();
        $auth->recieveAwsCallback($_GET['access_token']);
    }
