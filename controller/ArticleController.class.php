<?php

namespace controller;

use model\Article;
use model\User;
use RuntimeException;

class ArticleController extends Controller
{
    public function execute($params = array())
    {

        if (!isset($params[0])) {
            return;
        }

        if (!filter_var($params[0], FILTER_VALIDATE_INT)) {
            return;
        }

        try {
            $model = new Article();
            $article = $model->get($params[0]);
            if ($article !== false && ($article->approved || User::isAdmin() || User::getData()[User::SESSION_ID] == $article->author)) {
                $article->date = strftime("%d. %B %G", strtotime($article->date));
                $this->loadTemplate("article.twig");
                $this->setTitle($article->title);
                $this->add("article", $article);
            }
        } catch (RuntimeException $e) {
        }
    }
}
