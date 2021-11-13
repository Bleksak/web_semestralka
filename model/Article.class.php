<?php

namespace model;

class Article extends Model
{
    const TABLE = "articles";

    public function create($author, $title, $abstract, $filename)
    {
        $this->db->insert(self::TABLE, [
            "author" => $author,
            "title" => $title,
            "abstract" => $abstract,
            "file" => $filename
        ]);
    }

    public function get($id)
    {
        return $this->db->select(self::TABLE, [], ["id", '=', $id]);
    }

    public function getFromAuthor($author)
    {
        return $this->db->select(self::TABLE, [], ["author", '=', $author]);
    }

    public function getAll()
    {
        return $this->db->select(self::TABLE);
    }
}
