<?php

    header('Content-Type: application/json; charset=utf-8');

    // Get group UUID from the cookie
    $group_uuid =  $_COOKIE['group_uuid'] ?? null;
    $group_password = $_COOKIE['group_password'] ?? null;

    if ($group_uuid === null || $group_password === null) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Bitte gebe ein Passwort an.';
        echo json_encode($response);
        exit();
    }
    
    $group_storage = './../storage/'. $group_uuid .'.json';
    if (!file_exists($group_storage)) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Gruppe existiert nicht.';
        echo json_encode($response);
        exit();
    }

    $group = json_decode(file_get_contents($group_storage), true);
    // Check if the group password is correct
    if (!password_verify($group_password, $group['password'])) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Passwort ist falsch.';
        echo json_encode($response);
        exit();
    }

    $response                   = [];
    $response['status']         = 'success';
    $response['message']        = 'Gruppe existiert.';
    $response['group']          = $group;
    echo json_encode($response);
    exit();

?>