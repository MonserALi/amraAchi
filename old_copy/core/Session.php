<?php
class Session
{
  public static function init()
  {
    if (version_compare(phpversion(), '7.2.0', '>=')) {
      if (session_status() == PHP_SESSION_NONE) {
        session_start([
          'cookie_httponly' => true,
          'cookie_secure' => true,
          'cookie_samesite' => 'Strict'
        ]);
      }
    } else {
      if (session_id() == '') {
        session_start();
      }
    }
  }

  public static function set($key, $val)
  {
    $_SESSION[$key] = $val;
  }

  public static function get($key)
  {
    if (isset($_SESSION[$key])) {
      return $_SESSION[$key];
    } else {
      return false;
    }
  }

  public static function checkSession()
  {
    self::init();
    if (self::get("login") == false) {
      self::destroy();
      header("Location: login");
    }
  }

  public static function checkLogin()
  {
    self::init();
    if (self::get("login") == true) {
      header("Location: dashboard");
    }
  }

  public static function destroy()
  {
    session_destroy();
    header("Location: login");
  }

  public static function checkRole($role)
  {
    self::init();
    if (self::get("user_role") != $role) {
      header("Location: dashboard");
    }
  }
}
