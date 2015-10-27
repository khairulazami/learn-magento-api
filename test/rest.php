<?php

$callbackUrl = 'http://learnmagentoapi.local/test/rest.php';
//$hostname = 'http://rest.bilna.dev/';
//$hostname = 'http://stagez.bilna.com/';
$hostname = 'http://learnmagentoapi.local/';
//$hostname = 'http://logan.backend.dev/';
$temporaryCredentialsRequestUrl = $hostname . 'oauth/initiate?oauth_callback=' . urlencode($callbackUrl);
$adminAuthorizationUrl = $hostname . 'admin/oauth_authorize';
//$adminAuthorizationUrl = $hostname . 'admin/oauth_authorize';
$accessTokenRequestUrl = $hostname . 'oauth/token';
$apiUrl = $hostname . 'api/rest/';
//$consumerKey = 'bf7b4651d32388ea9d9869c6e45a4977';
//$consumerSecret = '551165a68df879cecda049780ac783ba';

//$consumerKey = 'ef193c6ba5f03f9a065c205c2c24e250';
//$consumerSecret = '6780ed1071ef6b3ddd3705d8986b04ec';

//--slipi.bilna.com
//$consumerKey = 'b6402ba02973c7a925cd54c7ca018a0e';
//$consumerSecret = '03cd41aa7f5905305b7492445e3f907d';

//--akses ke org baru
$consumerKey = '03b89fe090bc0b5f7b3995bfa1239b3a';
$consumerSecret = '2e5be39fdc5f06bfcbd070f6f85d7377';

//oauth token: dcd33843fd953cdd0c4308186e967d83
//oauth token secreet: 5c9d49466621183eb873672e5c6087ec


session_start();

if (!isset ($_GET['oauth_token']) && isset ($_SESSION['state']) && $_SESSION['state'] == 1) {
    $_SESSION['state'] = 0;
}

try {
    $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
    $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
    $oauthClient->enableDebug();

    if (!isset ($_GET['oauth_token']) && !$_SESSION['state']) {
        $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
        $_SESSION['secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['state'] = 1;
        header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
        exit;
    }
    elseif ($_SESSION['state'] == 1) {
        $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
        $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
        $_SESSION['state'] = 2;
        $_SESSION['token'] = $accessToken['oauth_token'];
        $_SESSION['secret'] = $accessToken['oauth_token_secret'];
        header('Location: ' . $callbackUrl);
        exit;
    }
    else {
        echo json_encode($_SESSION);exit;
        $accessToken = 'b9611bfcef5f3fcf2d0d1b1275854a06';
        $accessSecret = '5380178875df4ab3856e54badd4af76c';
        $oauthClient->setToken($accessToken, $accessSecret);
        //$oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
        $resourceUrl = $apiUrl . 'products';
        $params = array ('limit' => 5);
        $headers = array ('Content-Type' => 'application/json');
        $oauthClient->fetch($resourceUrl, $params, OAUTH_HTTP_METHOD_GET, $headers);
        print_r($oauthClient->getLastResponseInfo());
    }
}
catch (OAuthException $e) {
    print_r($e);
}
