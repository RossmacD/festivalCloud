<?php

namespace FestivalCloud;

require_once 'Connection.php';

require_once 'User.php';

require_once __DIR__.'/../vendor/autoload.php';
use Aws\S3\S3Client;

class StaticFile
{
    private $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'region' => $_ENV['AWS_REGION'],
            'version' => 'latest',
            // 'profile' => 'default',
            'credentials' => [
                'key' => $_ENV['AWS_KEY_S3'],
                'secret' => $_ENV['AWS_SECRET_S3'],
                'token' => $_ENV['AWS_SESSION_S3'],
            ],
        ]);
    }

    public function getFileLink(string $path)
    {
        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $_ENV['S3_BUCKET'],
            'Key' => $path,
        ]);
        $request = $this->client->createPresignedRequest($command, '+40 minutes');

        return $request->getUri();
    }

    public function uploadFile(string $path, $file)
    {
        $this->client->putObject([
            'Bucket' => $_ENV['S3_BUCKET'],
            'Key' => $path,
            'SourceFile' => $file,
        ]);
    }
}
