<?php

function at_get_opinions_of_point($point_id) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    
    $point_id = mysqli_real_escape_string($db, $point_id);

    $opinions = mysqli_query($db, "SELECT * FROM opinions WHERE point_id='$point_id'");
    if(!$opinions)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    $tmp_opinions = array();
    while($opinion = mysqli_fetch_assoc($opinions)) {
        $user_id = $opinion['user_id'];
        $users = mysqli_query($db, "SELECT username FROM users WHERE id='$user_id'");
        if(!$users || mysqli_num_rows($users) < 1)
            return array('success'=>-1, 'message'=>'Database retrieve error1');

        $user = mysqli_fetch_assoc($users);
      
        $opinion['username'] = $user['username'];
        array_push($tmp_opinions, array_map('utf8_encode', $opinion));
    }

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Opinions retrieved successfully', 'opinions'=>$tmp_opinions);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['point_id']))
        echo json_encode(at_get_opinions_of_point($_POST['point_id']));

?>