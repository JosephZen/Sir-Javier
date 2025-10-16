<?php
require '../config.php';

$clientId = '5QL9WCBPS22XERHZKHIEAIE9MAAE70OU';  // Replace with your actual Client ID
$redirectUri = 'http://localhost/Sir-Javier/Calendar-WorkSpace/oauth/callback.php';  // e.g., http://localhost/team-calendar/oauth/callback.php

$authUrl = 'https://app.clickup.com/api/v2/oauth/authorize?client_id=' . urlencode($clientId) . '&redirect_uri=' . urlencode($redirectUri);

// Redirect the user to ClickUp
header('Location: ' . $authUrl);
exit;
?>