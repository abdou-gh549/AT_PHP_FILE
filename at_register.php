<?php

function at_register($username, $password, $email) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);
    $email = mysqli_real_escape_string($db, $email);

    $users = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    if(!$users)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    if(mysqli_num_rows($users) >= 1)
        return array('success'=>0, 'message'=>'User already exists');

    $emails = mysqli_query($db, "SELECT * FROM users WHERE email='$email'");
    if(!$emails)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    if(mysqli_num_rows($emails) >= 1)
        return array('success'=>0, 'message'=>'Email already used');

    if(!mysqli_query($db, "INSERT INTO users (id, username, password, email, join_date) VALUE
        (NULL, '$username', '$password', '$email', CURRENT_DATE())"))
        return array('success'=>-1, 'message'=>'Register failed');
    $isert_id = mysqli_insert_id($db);

    $users = mysqli_query($db, "SELECT * FROM users WHERE id='$isert_id'");
    if(!$users)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    if(mysqli_num_rows($users) < 1)
        return array('success'=>-1, 'message'=>'Database retrieve error');

    $result = array('success'=>1, 'message'=>'Register success', 'user'=>mysqli_fetch_assoc($users));
    
    mysqli_close($db);

    return $result;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
        echo json_encode(at_register($_POST['username'], $_POST['password'], $_POST['email']));

?>