<?php

function at_get_image_of($what, $id) {
    if($what != 'town' && $what != 'point')
        return array('success'=>-1, 'message'=>'Input error');

    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $id = mysqli_real_escape_string($db, $id);
    $what = $what . 's';

    $table = mysqli_query($db, "SELECT image_id FROM $what WHERE id='$id'");
    if(!$table || mysqli_num_rows($table) != 1)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    $row = mysqli_fetch_assoc($table);

    $image_id = $row['image_id'];
    if(!$image_id)
        return array('success'=>0, 'message'=>'Target has no image', 'image'=>'');

    $images = mysqli_query($db, "SELECT data FROM images WHERE id='$image_id'");
    if(!$images)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    $image = mysqli_fetch_assoc($images);
    $image = base64_encode($image['data']);

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Image retrieved successfully', 'image'=>$image);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['what']) && isset($_POST['id']))
        echo json_encode(at_get_image_of($_POST['what'], $_POST['id']));

?>