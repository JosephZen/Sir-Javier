<?php
require '../config.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $clientId = '5QL9WCBPS22XERHZKHIEAIE9MAAE70OU';
    $clientSecret = 'R3CKGCA099Y6VPL06S8RH8JQE47DZYEC9DS6EURFBFQWP2EY8ZWR6TQQB4NLAY4G';
    $redirectUri = 'http://localhost/Sir-Javier/Calendar-WorkSpace/oauth/callback.php';  // Match exactly

    $tokenUrl = 'https://api.clickup.com/api/v2/oauth/token';
    $data = [
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($tokenUrl, false, $context);
    $response = json_decode($result, true);

    if (isset($response['access_token'])) {
        $_SESSION['clickup_access_token'] = $response['access_token'];
        header('Location: ../index.php');  // Redirect back to your local app
        exit;
    } else {
        echo "Error: Failed to get access token.";
        error_log("ClickUp OAuth Error: " . json_encode($response));
    }
} else {
    echo "No authorization code provided.";
}
?>