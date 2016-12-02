<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 12/2/2016
 * Time: 3:08 AM
 */

class Home
{
    /**
     * The default landing page for website.
     */
    public function homeAction()
    {
        Functions::getInstance()->render(VIEW_PATH . "home.php",["title"=>SITE_NAME]);
    }
}