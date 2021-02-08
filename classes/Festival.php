<?php

require_once 'Connection.php';

require_once 'StaticFile.php';
use FestivalCloud\StaticFile;

class Festival
{
    public $id;
    public $title;
    public $description;
    public $city;
    public $start_date;
    public $end_date;
    public $image_path;

    public function __construct()
    {
    }

    public function save()
    {
        $params = [
            'title' => $this->title,
            'description' => $this->description,
            'city' => $this->city,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image_path' => $this->image_path,
        ];

        if (null === $this->id) {
            $sql = 'INSERT INTO festivals(
                        title, description, city, start_date, end_date, image_path
                    ) VALUES (
                        :title, :description, :city, :start_date, :end_date, :image_path
                    )';
        } elseif (null !== $this->id) {
            $params['id'] = $this->id;

            $sql = 'UPDATE festivals SET
                        title = :title,
                        description = :description,
                        city = :city,
                        start_date = :start_date,
                        end_date = :end_date,
                        image_path = :image_path
                    WHERE id = :id';
        }

        $conn = Connection::getInstance();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to save festival');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error saving festival');
        }
        if (null === $this->id) {
            $this->id = $conn->lastInsertId('festivals');
        }
    }

    public function delete()
    {
        if (empty($this->id)) {
            throw new Exception('Unsaved festival cannot be deleted');
        }
        $params = [
            'id' => $this->id,
        ];
        $sql = 'DELETE FROM festivals WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to delete festival');
        }

        $rowCount = $stmt->rowCount();
        if (1 !== $rowCount) {
            throw new Exception('Error deleting festival');
        }
    }

    public static function all()
    {
        $sql = 'SELECT * FROM festivals';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception('Failed to retrieve festivals');
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Festival');
    }

    public static function find($id)
    {
        $params = [
            'id' => $id,
        ];
        $sql = 'SELECT * FROM festivals WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception('Failed to retrieve festival');
        }

        $festival = $stmt->fetchObject('Festival');
        if (!strpos($festival->image_path, 'placeimg')) {
            $files = new StaticFile();
            $festival->image_path = $files->getFileLink($festival->image_path);
        }

        return $festival;
    }
}
