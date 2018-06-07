<?php

function at_get_all_point() {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
        mysqli_set_charset($db,"utf8");
    $points = mysqli_query($db, 
    "SELECT points.id,points.name,towns.name as ville_name, towns.wilaya as wilaya, points.type, points.description,longitude,latitude FROM points,towns 
        Where points.town_id = towns.id");
    
    if(!$points)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    $tmp_points = array();
    while($point = mysqli_fetch_assoc($points)) {
        $point_rank = mysqli_query($db, "SELECT (SUM(rating) / COUNT(rating)) as point_rating  FROM opinions WHERE point_id = '$point[id]'");
       
        if(!$point_rank){
            $point['point_rating'] = '0.0';
        }else {
            $point_rating = mysqli_fetch_assoc($point_rank)['point_rating'];
            if ( $point_rating != ''){
                $point['point_rating'] = $point_rating;
            }else{
                $point['point_rating'] = '0.0';
            }
        }
        
        array_push($tmp_points,  $point);
    }

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Points retrieved successfully', 'points'=>$tmp_points);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
        echo json_encode(at_get_all_point());

?>