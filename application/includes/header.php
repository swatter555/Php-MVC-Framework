<?php
// Turn on output buffering.
ob_start();

// Start session.
LoginHelpers::getInstance()->secureSessionStart();

// Check if user is logged in.
LoginHelpers::getInstance()->loginCheck() ? $logged = true : $logged = false;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge,chrome=1">
    <meta name="Viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <title>
        <?php
        isset($title) ? print(htmlspecialchars($title)) : print(SITE_NAME);
        ?>
    </title>

    <script type="text/JavaScript" src="/js/functions.js"></script>
    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
</head>

<body>

<header class="siteHeader">
<br>
This is the Header.
<br>
</header>

<main class="siteContent">
    <!-- BEGIN CHANGEABLE CONTENT -->