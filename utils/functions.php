<?php

require_once __DIR__.'/../vendor/autoload.php';
use Aws\S3\S3Client;

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

function buckets()
{
    try {
        $s3Client = new S3Client([
            'region' => $_ENV['AWS_REGION'],
            'version' => 'latest',
            // 'profile' => 'default',
            'credentials' => [
                'key' => $_ENV['AWS_KEY_S3'],
                'secret' => $_ENV['AWS_SECRET_S3'],
                'token' => $_ENV['AWS_SESSION_S3'],
            ],
        ]);
        $command = $s3Client->getCommand('GetObject', [
            'Bucket' => $_ENV['S3_BUCKET'],
            'Key' => 'uploads/1610928727.png',
        ]);

        $request = $s3Client->createPresignedRequest($command, '+40 minutes');
        echo '<a href="'.$request->getUri().'">Image</a>';
        // $buckets = $s3Client->listBuckets();
        // foreach ($buckets['Buckets'] as $bucket) {
        //     echo $bucket['Name']."\n";
        // }
    } catch (S3Exception $e) {
        echo $e->getMessage()."\n";
    }
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

    // $s3Client = new S3Client([
    //     'region' => $_ENV['AWS_REGION'],
    //     'version' => $_ENV['AWS_VERSION'],
    //     'credentials' => [
    //         'key' => $_ENV['AWS_KEY'],
    //         'secret' => $_ENV['AWS_SECRET'],
    //     ],
    // ]);

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
