<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 12/6/2016
 * Time: 3:04 AM
 */
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
</head>

<body>

<div id="error_page">
    <br>
    <?php
    if (isset($message)) print (htmlspecialchars($message));
    ?>
    <br>
    <br>
</div>

<footer class="siteFooter">
    This is the footer.
</footer>

</body>
</html>