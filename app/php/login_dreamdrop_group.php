<?php

    header('Content-Type: application/json; charset=utf-8');

    $group_uuid     = $_POST['uuid'] ?? null; // UUID of the group
    $password       = $_POST['password'] ?? null; // Password for the group

    if ($group_uuid === null || $password === null)
    {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Bitte gebe ein Passwort an.';
        echo json_encode($response);
        exit();
    }

    $group_storage = './../storage/'. $group_uuid .'.json';
    if (!file_exists($group_storage)) 
    {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Gruppe existiert nicht.';
        echo json_encode($response);
        exit();
    }

    $group = json_decode(file_get_contents($group_storage), true);
    // Check if the group password is correct
    if (!password_verify($password, $group['password'])) 
    {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Passwort ist falsch.';
        echo json_encode($response);
        exit();
    }

    // Set a secure httpOnly cookie with the group UUID
    setSecureCookie('group_uuid', $group_uuid);
    setSecureCookie('group_password', $password);

    $response                   = [];
    $response['status']         = 'success';
    $response['message']        = 'Gruppe existiert.';
    $response['group']          = $group;
    echo json_encode($response);
    exit();

    // Set a secure httpOnly cookie
    function setSecureCookie($name, $value)
    {
        setcookie($name, $value, time() + (365 * 24 * 60 * 60), '/', '', true, true);
    }
?>