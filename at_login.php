<?php

function at_login($username, $password) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);

    $users = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if(!$users)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    if(mysqli_num_rows($users) < 1)
        return array('success'=>0, 'message'=>'Invalid username or password');
    
    $user = mysqli_fetch_assoc($users);
    $result = array('success'=>1, 'message'=>'Login success', 'user'=>$user);

    mysqli_close($db);

    return $result;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['username']) && isset($_POST['password']))
        echo json_encode(at_login($_POST['username'], $_POST['password']));
        
?>