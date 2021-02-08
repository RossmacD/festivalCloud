<?php

class Connection
{
    private static $host = 'festival-cloud-db.coqgvfohjb77.us-east-1.rds.amazonaws.com';
    private static $database = 'festival_cloud_db';
    private static $username = 'admin';
    private static $password = 'secretRDS';

    public static function getInstance()
    {
        $dsn = 'mysql:host='.Connection::$host.';dbname='.Connection::$database;

        $connection = new PDO($dsn, Connection::$username, Connection::$password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}
