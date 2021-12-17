<?php

namespace controller;

use model\Article;
use model\User;
use RuntimeException;

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
        try {
            $article = new Article();
            $name = urldecode(join('/', $params));

            $this->add("search_value", $name);

            $articles = null;

            if(User::isLoggedIn() && User::getData()[User::SESSION_ROLE] == 2) {
                $articles = empty($params) || empty($params[0]) ? $article->getAll() : $article->search($name);
            } else {
                $articles = empty($params) || empty($params[0]) ? $article->getAllApproved() : $article->search($name);
            }

            $articles = array_map(function ($article) {

                $article->abstract = self::createPreviewText($article);
                $article->date = strftime("%d. %B %G", strtotime($article->date));

                return $article;
            }, $articles);
        } catch (RuntimeException $e) {
        }

        $this->loadTemplate("articles.twig");
        $this->setTitle("Články");
        $this->add("articles", $articles);
    }
}
