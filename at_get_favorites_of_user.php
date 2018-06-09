<?php

function at_get_favorites_of_user($user_id) {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    mysqli_set_charset($db,"utf8");
    $user_id = mysqli_real_escape_string($db, $user_id);

    $favorites = mysqli_query($db, "SELECT * FROM favorites WHERE user_id='$user_id'");
    if(!$favorites)
        return array('success'=>-1, 'message'=>'Database retrieve error');
    
    $tmp_favorites = array();
    while($favorite = mysqli_fetch_assoc($favorites)) {
        $point_id = $favorite['point_id'];

        $points = mysqli_query($db, "SELECT * FROM points WHERE id='$point_id'");
        if(!$points || mysqli_num_rows($points) != 1)
            return array('success'=>-1, 'message'=>'Database retrieve error');
        $point = mysqli_fetch_assoc($points);

        $town_id = $point['town_id'];
        $towns = mysqli_query($db, "SELECT name, wilaya FROM towns WHERE id='$town_id'");
        if(!$towns || mysqli_num_rows($towns) != 1)
            return array('success'=>-1, 'message'=>'Database retrieve error');
        $town = mysqli_fetch_assoc($towns);

        $favorite['town'] = $town['name'];
        $favorite['wilaya'] = $town['wilaya'];
        $favorite['point'] = $point['name'];
        $favorite['longitude'] = $point['longitude'];
        $favorite['latitude'] = $point['latitude'];
        $favorite['point'] = $point['name'];
        $favorite['type'] = $point['type'];
        $favorite['description'] = $point['description'];

        $point_rank = mysqli_query($db, "SELECT ROUND(SUM(rating) / COUNT(rating),1) as point_rating  FROM opinions WHERE point_id = '$point[id]'");
       
        if(!$point_rank){
            $favorite['point_rating'] = '0.0';
        }else {
            $point_rating = mysqli_fetch_assoc($point_rank)['point_rating'];
            if ( $point_rating != ''){
                $favorite['point_rating'] = $point_rating;
            }else{
                $favorite['point_rating'] = '0.0';
            }
        }
        array_push($tmp_favorites, $favorite);
    }

    mysqli_close($db);

    return array('success'=>1, 'message'=>'Favorites retrieved successfully', 'favorites'=>$tmp_favorites);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    if(isset($_POST['user_id']))
        echo json_encode(at_get_favorites_of_user($_POST['user_id']));

?>