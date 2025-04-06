<?php

    header('Content-Type: application/json; charset=utf-8');

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

    // Sort by created_at newest first
    usort($dreamdrop_json, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    $response = [];
    $response['status'] = 'success';
    $response['message'] = 'Dreamdrops erfolgreich geladen.';
    $response['data'] = $dreamdrop_json;

    echo json_encode($response);
    exit();
?>