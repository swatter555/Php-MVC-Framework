<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 12/6/2016
 * Time: 3:14 AM
 */

class LoginController
{
    public function login()
    {
        Functions::getInstance()->render(VIEW_PATH . "login.php", ["title" => "Login"]);
    }
}