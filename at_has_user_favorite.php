<?php

function at_has_user_favorite($user_id, $point_id) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $user_id = mysqli_real_escape_string($db, $user_id);
    $point_id = mysqli_real_escape_string($db, $point_id);

    $favorites = mysqli_query($db, "SELECT * FROM favorites WHERE user_id= '$user_id' AND point_id='$point_id'");
    if(!$favorites)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    $has = 0;
    if(mysqli_num_rows($favorites) > 0)
        $has = 1;

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Result bool retrieved successfully', 'has'=>$has);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['user_id']) && isset($_POST['point_id']))
        echo json_encode(at_has_user_favorite($_POST['user_id'], $_POST['point_id']));

?>