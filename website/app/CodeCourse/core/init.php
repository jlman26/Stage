<?php 

session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'mysql',
        'username' => 'root',
        'password' => 'root',
        'db' => 'QuizDb'
    ),

    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
    if ($class === 'DB') {
        require_once 'classes/DB.php';
    }
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}

?>