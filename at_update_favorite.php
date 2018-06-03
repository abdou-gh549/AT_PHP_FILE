<?php

function at_update_favorite($id, $note) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $id = mysqli_real_escape_string($db, $id);
    $note = mysqli_real_escape_string($db, $note);

    $update = mysqli_query($db, "UPDATE favorites SET note='$note' WHERE id='$id'");
    if(!$update || !mysqli_affected_rows($db))
        return array('success'=>-1, 'message'=>'Database update error');

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Favorites updated successfully');
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['id']) && isset($_POST['note']))
        echo json_encode(at_update_favorite($_POST['id'], $_POST['note']));

?>