<?php

namespace model;

use RuntimeException;

class Review extends Model
{
    public function get($articleId)
    {
        return $this->db->join(
            "reviews",
            "articles",
            "reviews.article = articles.id",
            ["reviews.id", "reviews.author", "reviews.text", "articles.approved"],
            "reviews.article=?",
            [$articleId]
        );
    }

    public function add($author, $articleId, $text)
    {
        if(empty($text)) {
            throw new RuntimeException("Recenze nemůže být prázdná");
        }

        $this->db->insert("reviews", [
            "author" => $author,
            "article" => $articleId,
            "text" => $text
        ]);
    }
}
