<?php

// Class Name: Session

class Session
{

    // Session Start Method    
    /**
     * init
     *
     * @return void
     */
    public static function init(): void
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Session Set Method    
    /**
     * set
     *
     * @param  string $key
     * @param  mixed $val
     * @return void
     */
    public static function set($key, $val): void
    {

        $_SESSION[$key] = $val;
    }

    // Session Get Method    
    /**
     * get
     *
     * @param  string $key
     * @return mixed
     */
    public static function get($key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    // User logout Method    
    /**
     * destroy
     *
     * @return void
     */
    public static function destroy(): void
    {
        session_destroy();
        session_unset();
        header('Location:login.php');
    }

    // Check Session Method    
    /**
     * checkSession
     *
     * @return void
     */
    public static function checkSession(): void
    {
        if (self::get('login') == false) {
            session_destroy();
            header('Location:login.php');
        }
    }

    // Check Login Method    
    /**
     * checkLogin
     *
     * @return void
     */
    public static function checkLogin(): void
    {
        if (self::get("login") == true) {
            header('Location:index.php');
        }
    }
}
