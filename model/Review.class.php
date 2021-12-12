<?php

namespace model;

class Review extends Model
{
    public function findForArticle($articleId)
    {
        return $this->db->join(
            "reviews",
            "articles",
            "reviews.article = articles.id",
            [],
            "reviews.article=?",
            [$articleId]
        );
    }

    public function findReview($reviewId)
    {
    }

    public function addReview($author, $articleId, $text)
    {
    }
}
