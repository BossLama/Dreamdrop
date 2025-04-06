<?php

    header('Content-Type: application/json; charset=utf-8');

    $password       = $_POST['password'] ?? null; // Password for the group

    if (empty($password))
    {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Bitte gebe ein Passwort an.';
        echo json_encode($response);
        exit();
    }

    if (strlen($password) < 12)
    {
        $response                   = [];
        $response['status']         = 'error';
        $response['message']        = 'Das Passwort muss mindestens 12 Zeichen lang sein.';
        echo json_encode($response);
        exit();
    }

    $group_uuid = createGroup($password);

    // Set a secure httpOnly cookie with the group UUID
    setSecureCookie('group_uuid', $group_uuid);
    setSecureCookie('group_password', $password);

    $response                   = [];
    $response['status']         = 'success';
    $response['message'] = 'Gruppe erfolgreich erstellt.';
    $response['group_uuid']     = $group_uuid;

    echo json_encode($response);
    exit();


    // Set a secure httpOnly cookie
    function setSecureCookie($name, $value)
    {
        setcookie($name, $value, time() + (365 * 24 * 60 * 60), '/', '', true, true);
    }

    // Create a new group and save it to storage
    function createGroup($password)
    {
        // Format XXXX-XXXX-XXXX
        $group_uuid = sprintf('%04X-%04X-%04X', mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF));

        if (file_exists('./../storage/'. $group_uuid .'.json')) 
        {
            return createGroup($password);
        }

        $dreamdrop_storage = './../storage/'. $group_uuid .'.json';
        $group_array = [
            'uuid'          => $group_uuid,
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'created_at'    => date('Y-m-d H:i:s'),
            'dreamdrops'    => []
        ];
        file_put_contents($dreamdrop_storage, json_encode($group_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $group_uuid;
    }

?>