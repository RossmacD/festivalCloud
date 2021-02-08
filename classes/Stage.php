<?php

require_once 'Connection.php';

require_once 'StaticFile.php';
use FestivalCloud\StaticFile;

class Stage
{
    public $id;
    public $title;
    public $description;
    public $location;
    public $festival_id;
    public $image_path;

    public function __construct()
    {
    }

    public function save()
    {
        $params = [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'festival_id' => $this->festival_id,
            'image_path' => $this->image_path,
        ];

        if (null === $this->id) {
            $sql = 'INSERT INTO stages(
                        title, description, location, festival_id, image_path
                    ) VALUES (
                        :title, :description, :location, :festival_id, :image_path
                    )';
        } elseif (null !== $this->id) {
            $params['id'] = $this->id;

            $sql = 'UPDATE stages SET
                        title = :title,
                        description = :description,
                        location = :location,
                        festival_id = :festival_id,
                        image_path = :image_path
                    WHERE id = :id';
        }

        $conn = Connection::getInstance();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to save stage');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error saving stage');
        }
        if (null === $this->id) {
            $this->id = $conn->lastInsertId('stages');
        }
    }

    public function delete()
    {
        if (empty($this->id)) {
            throw new Exception('Unsaved stage cannot be deleted');
        }
        $params = [
            'id' => $this->id,
        ];
        $sql = 'DELETE FROM stages WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to delete stage');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error deleting stage');
        }
    }

    public static function all()
    {
        $sql = 'SELECT * FROM stages';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception('Failed to retrieve stages');
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Stage');
    }

    public static function find($id)
    {
        $params = [
            'id' => $id,
        ];
        $sql = 'SELECT * FROM stages WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to retrieve stage');
        }

        $stage = $stmt->fetchObject('Stage');
        if (!strpos($stage->image_path, 'placeimg')) {
            $files = new StaticFile();
            $stage->image_path = $files->getFileLink($stage->image_path);
        }

        return $stage;
    }
}
