<?php

$filepath = realpath(dirname(__FILE__));
include_once $filepath . "/../lib/Session.php";
Session::init();
Session::checkSession();

spl_autoload_register(function ($classes) {

    include 'src/' . $classes . ".php";
});

$userController = new UserController();
