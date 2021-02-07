<?php

require 'vendor/autoload.php';

function is_logged_in()
{
    start_session();

    return isset($_SESSION['user']);
}

function start_session()
{
    $id = session_id();
    if ('' === $id) {
        session_start();
    }
}

function old($index, $default = null)
{
    if (isset($_POST) && is_array($_POST) && array_key_exists($index, $_POST)) {
        echo $_POST[$index];
    } elseif (null !== $default) {
        echo $default;
    }
}

function error($index)
{
    global $errors;

    if (isset($errors) && is_array($errors) && array_key_exists($index, $errors)) {
        echo $errors[$index];
    }
}

function dd($value)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';

    exit();
}

function imageFileUpload($name, $required, $maxSize, $allowedTypes, $fileName)
{
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        throw new Exception('Invalid request');
    }

    if ($required && !isset($_FILES[$name])) {
        throw new Exception('File '.$name.' required');
    }
    if (!$required && !isset($_FILES[$name])) {
        return null;
    }

    if (0 !== $_FILES[$name]['error']) {
        // throw new Exception('File upload error');
        return 'uploads/default.png';
    }

    if (!is_uploaded_file($_FILES[$name]['tmp_name'])) {
        throw new Exception('Filename is not an uploaded file');
    }

    $imageInfo = getimagesize($_FILES[$name]['tmp_name']);
    if (false === $imageInfo) {
        throw new Exception('File is not an image.');
    }

    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $sizeString = $imageInfo[3];
    $mime = $imageInfo['mime'];

    $target_dir = '../../uploads/';
    $target_file = $target_dir.basename($_FILES[$name]['name']);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $target_file = $target_dir.'/'.$fileName.'.'.$imageFileType;

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 755, true);
    }
    if (file_exists($target_file)) {
        throw new Exception('Sorry, file already exists.');
    }

    if ($_FILES[$name]['size'] > $maxSize) {
        throw new Exception('Sorry, your file is too large.');
    }

    if (!in_array($imageFileType, $allowedTypes)) {
        throw new Exception('Sorry, only '.implode(',', $allowedTypes).' files are allowed.');
    }

    if (!move_uploaded_file($_FILES[$name]['tmp_name'], $target_file)) {
        throw new Exception('Sorry, there was an error moving your uploaded file.');
    }

    return 'uploads/'.$fileName.'.'.$imageFileType;
}
