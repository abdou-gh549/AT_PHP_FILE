<?php

function at_send_message($user_id, $object, $content) {
    require_once('at_config.php');
    
    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $user_id = mysqli_real_escape_string($db, $user_id);
    $object = mysqli_real_escape_string($db, $object);
    $content = mysqli_real_escape_string($db, $content);

    $string_query = 
    "   INSERT
        INTO messages (user_id, object, content, send_date)
        VALUES ('$user_id', '$object', '$content', CURRENT_TIME())
    ";
    $query = mysqli_query($db, $string_query);
    if(!$query)
        return array('success'=>-1, 'message'=>'Database insert error');

    mysqli_close($db);
    return array('success'=>1);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['user_id']) && isset($_POST['object']) && isset($_POST['content']))
        echo json_encode(at_send_message($_POST['user_id'], $_POST['object'], $_POST['content']));

?>