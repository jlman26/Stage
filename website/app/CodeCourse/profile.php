<?php 
require_once 'core/init.php';

if(!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
        if($data->username != $user->data()->username) {
            Redirect::to(404);
        }

    }
    ?>

    <h3><?php echo escape($data->username); ?></h3>
    <p>Full Name: <?php echo escape($data->name); ?></p>
    <p>Username: <?php echo escape($data->username); ?></p>
    <p>Joined: <?php echo escape(date('jS M Y', strtotime($data->joined))); ?></p>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Update details</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
    </ul>

    <?php
}

?>