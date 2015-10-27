<?php

require '../app/Mage.php'; //Path to Magento
Mage::app();

// $callbackUrl is a path to your file with OAuth authentication example for the Admin user
$callbackUrl = "http://magento.local/tests/groupInsert.php";
$temporaryCredentialsRequestUrl = "http://magento.local/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
$adminAuthorizationUrl = 'http://magento.local/admin/oauth_authorize';
$accessTokenRequestUrl = 'http://magento.local/oauth/token';
$apiUrl = 'http://magento.local/api/rest';
$consumerKey = 'c195916aadaf1f1cc1d7704ca8639d45';
$consumerSecret = '721477ba87aadbdec0f2221b76041f44';
session_start();
if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) && $_SESSION['state'] == 1) {
    $_SESSION['state'] = 0;
}
try {
    $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
    $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
    $oauthClient->enableDebug();

    if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
        $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
        $_SESSION['secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['state'] = 1;
        header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
        exit;
    } else if ($_SESSION['state'] == 1) {
        $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
        $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
        $_SESSION['state'] = 2;
        $_SESSION['token'] = $accessToken['oauth_token'];
        $_SESSION['secret'] = $accessToken['oauth_token_secret'];
        header('Location: ' . $callbackUrl);
        exit;
    } else {
        $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
        $resourceUrl = "$apiUrl/groups/";
        $productData = Mage::helper('core')->jsonEncode(array(
            'name'      => 'test_group'
        ));
        $headers = array('Content-Type' => 'application/json');
        $oauthClient->fetch($resourceUrl, $productData, 'POST', array('Content-Type' => 'application/json'));
        echo $oauthClient->getLastResponse();
    }
} catch (OAuthException $e) {
    print_r($e);
}