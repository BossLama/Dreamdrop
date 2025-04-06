<?php

    // ERROR REPORTING
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
    ini_set('display_errors', 1);

    header('Content-Type: application/json; charset=utf-8');

    $title          = $_POST['name'] ?? null;           // Name of the dreamdrop
    $location       = $_POST['location'] ?? null;       // Location of the dreamdrop
    $price          = $_POST['price'] ?? null;          // Price of the dreamdrop
    $url            = $_POST['url'] ?? null;            // URL of the dreamdrop
    $author         = $_POST['author'] ?? null;         // Author of the dreamdrop

    $group_uuid     = $_COOKIE['group_uuid'] ?? null;   // UUID of the group
    $group_password = $_COOKIE['group_password'] ?? null; // Password of the group

    if ($group_uuid === null || $group_password === null) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Du bist nicht eingeloggt.';
        echo json_encode($response);
        exit();
    }

    if (!isset($title) || !isset($location) || !isset($author)) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Bitte gebe alle Dreamdrop-Informationen an.';
        echo json_encode($response);
        exit();
    }

    $dreamdrop_array = [
        'title'         => encrypt($title, $group_password),
        'location'      => encrypt($location, $group_password),
        'price'         => encrypt($price, $group_password),
        'url'           => encrypt($url, $group_password),
        'author'        => encrypt($author, $group_password),
        'created_at'    => encrypt(date('Y-m-d H:i:s'), $group_password),
    ];



    // Load existing dreamdrops from storage
    $dreamdrop_storage = './../storage/'. $group_uuid .'.json';
    if (!file_exists($dreamdrop_storage)) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Gruppe existiert nicht mehr.';
        echo json_encode($response);
        exit();
    }

    $dreamdrops = json_decode(file_get_contents($dreamdrop_storage), true);
    if ($dreamdrops === null) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Dreamdrops konnten nicht geladen werden.';
        echo json_encode($response);
        exit();
    }

    $dreamdrops["dreamdrops"][] = $dreamdrop_array;
    $dreamdrops = json_encode($dreamdrops, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($dreamdrops === false) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Dreamdrops konnten nicht gespeichert werden.';
        echo json_encode($response);
        exit();
    }
    file_put_contents($dreamdrop_storage, $dreamdrops);


    $response = [];
    $response['status'] = 'success';
    $response['message'] = 'Dreamdrop erfolgreich erstellt.';
    $response['dreamdrop'] = $dreamdrop_array;
    echo json_encode($response);
    exit();



    // Function for encrypting data with a password
    function encrypt($data, $password) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $password, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    // Function for decrypting data with a password
    function decrypt($data, $password) {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $password, 0, $iv);
    }
?>