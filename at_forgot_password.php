<?php

function at_forgot_password($username, $email) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $username = mysqli_real_escape_string($db, $username);
    $email = mysqli_real_escape_string($db, $email);

    $users = mysqli_query($db, "SELECT password FROM users WHERE username= '$username' AND email='$email'");
    if(!$users)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    if(!mysqli_num_rows($users))
        return array('success'=>0, 'message'=>'Invalid user');
    
    $password = mysqli_fetch_assoc($users)['password'];

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Password retrieved successfully', 'password'=>$password);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['username']) && isset($_POST['email']))
        echo json_encode(at_forgot_password($_POST['username'], $_POST['email']));

?>