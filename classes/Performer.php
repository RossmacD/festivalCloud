<?php

require_once 'Connection.php';

require_once 'StaticFile.php';
use FestivalCloud\StaticFile;

class Performer
{
    public $id;
    public $title;
    public $description;
    public $contact_email;
    public $contact_phone;
    public $image_path;

    public function __construct()
    {
    }

    public function save()
    {
        $params = [
            'title' => $this->title,
            'description' => $this->description,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'image_path' => $this->image_path,
        ];

        if (null === $this->id) {
            $sql = 'INSERT INTO performers(
                        title, description, contact_email, contact_phone, image_path
                    ) VALUES (
                        :title, :description, :contact_email, :contact_phone, :image_path
                    )';
        } elseif (null !== $this->id) {
            $params['id'] = $this->id;

            $sql = 'UPDATE performers SET
                        title = :title,
                        description = :description,
                        contact_email = :contact_email,
                        contact_phone = :contact_phone,
                        image_path = :image_path
                    WHERE id = :id';
        }

        $conn = Connection::getInstance();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to save performer');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error saving performer');
        }
        if (null === $this->id) {
            $this->id = $conn->lastInsertId('performers');
        }
    }

    public function delete()
    {
        if (empty($this->id)) {
            throw new Exception('Unsaved performer cannot be deleted');
        }
        $params = [
            'id' => $this->id,
        ];
        $sql = 'DELETE FROM performers WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to delete performer');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error deleting performer');
        }
    }

    public static function all()
    {
        $sql = 'SELECT * FROM performers';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception('Failed to retrieve performers');
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Performer');
    }

    public static function find($id)
    {
        $params = [
            'id' => $id,
        ];
        $sql = 'SELECT * FROM performers WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to retrieve performer');
        }

        $performer = $stmt->fetchObject('Performer');
        if (!strpos($performer->image_path, 'placeimg')) {
            $files = new StaticFile();
            $performer->image_path = $files->getFileLink($performer->image_path);
        }

        return $performer;
    }
}
