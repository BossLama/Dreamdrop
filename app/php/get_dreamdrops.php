<?php

    header('Content-Type: application/json; charset=utf-8');

    $uuid = $_COOKIE['group_uuid'] ?? null; // UUID of the group
    $password = $_COOKIE['group_password'] ?? null; // Password of the group

    if ($uuid === null || $password === null) {
        $response = [];
        $response['status'] = 'error';
        $response['message'] = 'Du bist nicht eingeloggt.';
        echo json_encode($response);
        exit();
    }

    // Load existing dreamdrops from storage
    $dreamdrop_storage = './../storage/'. $uuid .'.json';
    if (!file_exists($dreamdrop_storage)) {
        $response = [];
        $response['status'] = 'error';
        $response['message'] = 'Gruppe existiert nicht mehr.';
        echo json_encode($response);
        exit();
    }

    $dreamdrop_json = json_decode(file_get_contents($dreamdrop_storage), true);
    $dreamdrops = $dreamdrop_json['dreamdrops'] ?? [];

    // Decrypt the dreamdrops
    foreach ($dreamdrops as $key => $dreamdrop) {
        $dreamdrops[$key]['title'] = decrypt($dreamdrop['title'], $password);
        $dreamdrops[$key]['location'] = decrypt($dreamdrop['location'], $password);
        $dreamdrops[$key]['price'] = decrypt($dreamdrop['price'], $password);
        $dreamdrops[$key]['url'] = decrypt($dreamdrop['url'], $password);
        $dreamdrops[$key]['author'] = decrypt($dreamdrop['author'], $password);
        $dreamdrops[$key]['created_at'] = decrypt($dreamdrop['created_at'], $password);
    }


    // Return the decrypted dreamdrops as JSON
    $response = [];
    $response['status'] = 'success';
    $response['message'] = 'Dreamdrops erfolgreich geladen.';
    $response['data'] = $dreamdrops;
    echo json_encode($response);


    function decrypt($data, $password) {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $password, 0, $iv);
    }
?>