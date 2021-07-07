<?php


namespace vajbon3\mvc;


class Session
{
    protected const FLASH_KEY = "flash_messages";
    public function __construct()
    {
        session_start();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage) {
            // mark to be removed
            $flashMessage["remove"] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = [
            "remove" => false,
            "value" => $message,
        ];
    }

    public function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key] ?? false;
    }

    public function get($key) {
        return $_SESSION[$key] ?? false;
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function remove($key) {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        // Iterate over marked to be removed messages

        // get a copy of the session
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        // iterate and delete marked messages
        foreach($flashMessages as $key => &$flashMessage) {
            if($flashMessage["remove"]) {
                unset($flashMessages[$key]);
            }
        }

        // update the session superglobal
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}