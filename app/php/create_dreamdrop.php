<?php

    header('Content-Type: application/json; charset=utf-8');

    $title          = $_POST['name'] ?? null;           // Name of the dreamdrop
    $location       = $_POST['location'] ?? null;       // Location of the dreamdrop
    $price          = $_POST['price'] ?? null;          // Price of the dreamdrop
    $url            = $_POST['url'] ?? null;            // URL of the dreamdrop
    $author         = $_POST['author'] ?? null;         // Author of the dreamdrop

    if (!isset($title) || !isset($location) || !isset($author)) {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Bitte gebe alle Dreamdrop-Informationen an.';
        echo json_encode($response);
        exit();
    }

    $dreamdrop_array = [
        'title'         => $title,
        'location'      => $location,
        'price'         => $price,
        'url'           => $url,
        'author'        => $author,
        'created_at'    => date('Y-m-d H:i:s')
    ];

    // Load existing dreamdrops from storage
    $dreamdrop_storage = './../storage/dreamdrops.json';
    $dreamdrop_json = [];
    if (file_exists($dreamdrop_storage)) 
    {
        $dreamdrop_json = json_decode(file_get_contents($dreamdrop_storage), true);
    }

    if (!is_array($dreamdrop_json)) 
    {
        $dreamdrop_json = [];
    }

    // Save the new dreamdrop to storage
    $dreamdrop_json[] = $dreamdrop_array;
    file_put_contents($dreamdrop_storage, json_encode($dreamdrop_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    $response = [];
    $response['status'] = 'success';
    $response['message'] = 'Dreamdrop erfolgreich erstellt.';
    $response['dreamdrop'] = $dreamdrop_array;
    echo json_encode($response);
    exit();
?>