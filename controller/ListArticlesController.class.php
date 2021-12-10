<?php

namespace controller;

use model\Article;

class ListArticlesController extends Controller
{

    const DEFAULT_LIMIT = 20;
    const MAX_PREVIEW_LENGTH = 100;

    public static function createPreviewText($article): string
    {
        $text = $article->abstract;

        if (strlen($article->abstract) > self::MAX_PREVIEW_LENGTH) {
            $text = substr($text, 0, self::MAX_PREVIEW_LENGTH) . "...";
        }

        return $text;
    }

    public function execute($params = array())
    {
        $this->setTitle("");
        $this->loadTemplate("articles.twig");

        $article = new Article();
        $name = urldecode(join('/', $params));

        $this->add("search_value", $name);

        $articles = empty($params) ? $article->getAllApproved() : $article->search($name);
        $articles = array_map(function ($article) {

            $article->abstract = self::createPreviewText($article);
            $article->date = strftime("%d. %B %G", strtotime($article->date));

            return $article;
        }, $articles);

        $this->add("articles", $articles);
    }
}
