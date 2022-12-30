<?php

class Comments
{
    private $mysqli = null;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->mysqli = $db->getConnection();
    }

    public function readComments()
    {
        $sql = "SELECT c.id as comment_id, c.post_id, c.content, u.id, u.fullname, u.pfp FROM comments as c
        JOIN users as u ON u.id = c.user_id";

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

    public function readLastComment($post_id)
    {
        $sql = "SELECT c.id FROM comments as c WHERE c.post_id = $post_id ORDER BY c.id DESC LIMIT 1";

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
