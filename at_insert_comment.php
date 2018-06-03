<?php

function at_add_comment($user_id, $point_id, $ratting, $comment) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $user_id = mysqli_real_escape_string($db, $user_id);
    $point_id = mysqli_real_escape_string($db, $point_id);
    $ratting = mysqli_real_escape_string($db, $ratting);
    $comment = mysqli_real_escape_string($db, $comment);

    $insert = mysqli_query($db, "INSERT INTO opinions(id, user_id, point_id, rating, comment, add_date) VALUE
        (NULL, '$user_id', '$point_id', '$ratting','$comment', NOW())");
    if(!$insert || !mysqli_affected_rows($db))
        return array('success'=>-1, 'message'=>'Database insert error');

    mysqli_close($db);

    return array('success'=>1, 'message'=>'comment inserted successfully');
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['user_id']) && isset($_POST['point_id']) && isset($_POST['rating']) && isset($_POST['comment']))
        echo json_encode(at_add_comment($_POST['user_id'], $_POST['point_id'], $_POST['rating'],$_POST['comment']));

?>