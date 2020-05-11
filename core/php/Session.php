<?php

/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 13/03/2016
 * Time: 10:05 PM
 */

class session {
    function __construct() {
        session_start ();
    }
    public function set($name, $valor) {
        $_SESSION [$name] = $valor;
    }
    public function get($name) {
        if (isset ( $_SESSION [$name] )) {
            return $_SESSION [$name];
        } else {
            return false;
        }
    }
    public function delete_var($name) {
        unset ( $_SESSION [$name] );
    }
    public function session_finish() {
        $_SESSION = array();
        session_destroy ();
    }
}