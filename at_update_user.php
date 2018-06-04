<?php

function at_updateuser($username, $email, $password) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    mysqli_set_charset($db,"utf8");
    $username = mysqli_real_escape_string($db, $username);
    $email = mysqli_real_escape_string($db, $email);
    $password = mysqli_real_escape_string($db, $password);

    $users = mysqli_query($db, "SELECT id FROM users WHERE username='$username'");
    if(!$users)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    if(mysqli_num_rows($users) < 1)
        return array('success'=>-1, 'message'=>'User not found');

    $message = '';
    if($email) {
        if(!mysqli_query($db, "UPDATE users SET email='$email' WHERE username='$username'"))
            return array('success'=>-1, 'message'=>'Database update error');
        else
            $message = 'Email';
    }
    
    if($password) {
        if(!mysqli_query($db, "UPDATE users SET password='$password' WHERE username='$username'"))
            return array('success'=>-1, 'message'=>'Database update error');
        else if($message)
            $message = $message . ' and password';
        else
            $message = 'Password';
    }
    
    if($message)
        $message = $message . ' updated';

    mysqli_close($db);

    if($message)
        return array('success'=>1, 'message'=>$message);
    else
        return array('success'=>0, 'message'=>'No updates');
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
        echo json_encode(at_updateuser($_POST['username'], $_POST['email'], $_POST['password']));
        
?>