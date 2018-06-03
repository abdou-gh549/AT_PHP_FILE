<?php

function at_add_favorite($user_id, $point_id, $note) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $user_id = mysqli_real_escape_string($db, $user_id);
    $point_id = mysqli_real_escape_string($db, $point_id);
    $note = mysqli_real_escape_string($db, $note);

    $insert = mysqli_query($db, "INSERT INTO favorites(id, user_id, point_id, note, add_date) VALUE
        (NULL, '$user_id', '$point_id', '$note', CURRENT_DATE())");
    if(!$insert || !mysqli_affected_rows($db))
        return array('success'=>-1, 'message'=>'Database insert error');

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Favorite inserted successfully');
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['user_id']) && isset($_POST['point_id']) && isset($_POST['note']))
        echo json_encode(at_add_favorite($_POST['user_id'], $_POST['point_id'], $_POST['note']));

?>