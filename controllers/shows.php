<?php

header("Content-Type: application/json");

include_once __DIR__ . '/../models/show.php';

if($_REQUEST['action'] === 'index'){
    echo json_encode(Shows::all());
} else if($_REQUEST['action'] === 'create') {
    $request_body = file_get_contents('php://input');
    $body_object = json_decode($request_body);
    $new_show = new Show(null, $body_object->tmdb_id, $body_object->name, $body_object->image, $body_object->current_season, $body_object->current_episode);
    $all_shows = Shows::create($new_show);

    echo json_encode($all_shows);
} else if($_REQUEST['action'] ==='update'){
    $request_body = file_get_contents('php://input');
    $body_object = json_decode($request_body);
    $updated_show = new Show($_REQUEST['id'], $body_object->tmdb_id, $body_object->name, $body_object->image, $body_object->current_season, $body_object->current_episode);
    $all_shows = Shows::update($updated_show);

    echo json_encode($all_shows);
} else if ($_REQUEST['action'] === 'delete'){
    $all_shows = Shows::delete($_REQUEST['id']);
    echo json_encode($all_shows);
}
 ?>
