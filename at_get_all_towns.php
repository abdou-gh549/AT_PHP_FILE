<?php

function at_get_all_towns() {
    require_once('at_config.php');

    $db = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if(!$db)
        return array('success'=>-1, 'message'=>'Database connexion error');
    $towns = mysqli_query($db, "SELECT id, name, wilaya, description FROM towns");
    if(!$towns)
        return array('success'=>-1, 'message'=>'Database retrieve error');

    $tmp_towns = array();
    while($town = mysqli_fetch_assoc($towns)) {
        unset($town['image_id']);
        
        // get ville ratting
        $town_rank = mysqli_query($db, "SELECT  (SUM(point_ratting) / COUNT(town_id)) as town_ratting
                From (
                    SELECT point_id, (SUM(rating) / COUNT(rating)) as point_ratting  FROM opinions GROUP BY point_id
                    ) as rating_point,points 
                WHERE rating_point.point_id = points.id AND points.town_id = '$town[id]'");
        // set ville ratting
        if(!$town_rank){
            $town['town_ratting'] = '0.0';
        }else {
            $town_rating = mysqli_fetch_assoc($town_rank)['town_ratting'];
            if ( $town_rating != ''){
                $town['town_ratting'] = $town_rating;
            }else{
                $town['town_ratting'] = '0.0';
            }
        }
           

        array_push($tmp_towns, array_map('utf8_encode', $town));
    }
    
    mysqli_close($db);
    
   return array('success'=>1, 'message'=>'Towns retrieved successfully', 'towns'=>$tmp_towns);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target']) && $_POST['target'] == 'external')
    echo json_encode(at_get_all_towns());
?>