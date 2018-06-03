<?php

function at_delete_favorite($id) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $id = mysqli_real_escape_string($db, $id);

    $delete = mysqli_query($db, "DELETE FROM favorites WHERE id= '$id'");
    if(!$delete || !mysqli_affected_rows($db))
        return array('success'=>-1, 'message'=>'Database delete error');

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Favorite deleted successfully');
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['id']))
        echo json_encode(at_delete_favorite($_POST['id']));

?>