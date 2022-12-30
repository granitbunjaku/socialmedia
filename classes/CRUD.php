<?php

include 'Database.php';

class CRUD
{
    private $mysqli = null;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->mysqli = $db->getConnection();
    }

    public function create($table, $data)
    {
        $sql = "INSERT INTO `" . $table . "` SET ";

        if (count($data)) {
            $count = 1;

            foreach ($data as $column => $value) {
                if (count($data) > $count) {
                    $sql .= "`" . $column . "`='" . $this->mysqli->real_escape_string($value) . "',";
                } else {
                    $sql .= "`" . $column . "`='" . $this->mysqli->real_escape_string($value) . "'";
                }

                $count++;
            }

            return $this->mysqli->query($sql) ? true : $this->mysqli->error;
        }
    }

    public function read($table, $conditions = [], $limit = null)
    {
        $sql = "SELECT * FROM `" . $table . "`";

        $results = [];

        if (count($conditions)) {
            $is_first_time = true;
            foreach ($conditions as $column => $value) {
                if ($is_first_time) {
                    $sql .= " WHERE `" . $column . "`='" . $value . "'";
                    $is_first_time = false;
                } else {
                    $sql .= " AND `" . $column . "`='" . $value . "'";
                }
            }
        }


        if (!is_null($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        if ($query = $this->mysqli->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $results[] = $row;
                }
            }

            return $results;
        } else {
            return $this->mysqli->error;
        }
    }

    public function update($table, $data, $conditions = [])
    {
        $sql = "UPDATE `" . $table . "` SET ";

        if (count($data)) {
            $count = 1;

            foreach ($data as $column => $value) {
                if (count($data) > $count) {
                    $sql .= "`" . $column . "`='" . $this->mysqli->real_escape_string($value) . "',";
                } else {
                    $sql .= "`" . $column . "`='" . $this->mysqli->real_escape_string($value) . "'";
                }

                $count++;
            }
        }

        if (count($conditions)) {
            $sql .= " WHERE `" . $conditions['column'] . "`='" . $conditions['value'] . "'";
        }

        return $this->mysqli->query($sql) ? true : $this->mysqli->error;
    }

    public function delete($table, $conditions = [], $limit = null)
    {
        $sql = "DELETE FROM `" . $table . "`";

        if (count($conditions)) {
            $is_first_time = true;
            foreach ($conditions as $column => $value) {
                if ($is_first_time) {
                    $sql .= " WHERE `" . $column . "`='" . $value . "'";
                    $is_first_time = false;
                } else {
                    $sql .= " AND `" . $column . "`='" . $value . "'";
                }
            }
        }

        if (!is_null($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        return $this->mysqli->query($sql) ? true : $this->mysqli->error;
    }

    public function search($table, $column, $value)
    {
        $sql = "SELECT * FROM `" . $table . "` WHERE `" . $column . "` LIKE '%" . $value . "%'";

        $results = [];

        if ($query = $this->mysqli->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $results[] = $row;
                }
            }

            return $results;
        } else {
            return $this->mysqli->error;
        }
    }
}
