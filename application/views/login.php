<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 12/6/2016
 * Time: 3:04 AM
 */
?>
<br>
<br>
You are in login controller.
<br>
<form action="?action=test" method="post">
<input type="submit" value="Test">
</form>
<br>
<?php UrlParser::getInstance()->renderUrlInfo(); ?>