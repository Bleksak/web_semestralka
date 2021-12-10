<?php

namespace controller;

use model\Article;
use RuntimeException;

class ArticleController extends Controller
{
    public function execute($params = array())
    {
        $this->loadTemplate("article.twig");

        if (filter_var($params[0], FILTER_VALIDATE_INT)) {
            try {
                $model = new Article();
                $article = $model->get($params[0]);
                if ($article !== false) {
                    $this->setTitle($article->title);
                    $article->date = strftime("%d. %B %G", strtotime($article->date));
                    $this->add("article", $article);
                }
            } catch (RuntimeException $e) {
            }
        }
    }
}
