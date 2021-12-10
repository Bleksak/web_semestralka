<?php

namespace controller;

use model\Article;
use model\User;
use RuntimeException;

class ProfileController extends Controller
{

    private function view(int $id)
    {
        $model = new User();

        try {
            $user = $model->find($id);
            if ($user !== false) {
                $articleModel = new Article();
                $articles = $articleModel->getFromAuthor($id);

                $articles = array_map(function ($article) {
                    $article->date = strftime("%d. %B %G", strtotime($article->date));
                    $article->abstract = ListArticlesController::createPreviewText($article);
                    return $article;
                }, $articles);

                if ($id != User::getData()[User::SESSION_ID]) {
                    $articles = array_filter($articles, function ($article) {
                        return $article->approved;
                    });
                }


                $this->setTitle("Profil uživatele");
                $this->loadTemplate("profile.twig");
                $this->add("profile", $user);
                $this->add("articles", $articles);
            }
        } catch (RuntimeException $e) {
        }
    }

    private function viewSelf()
    {
        $this->view(User::getData()[User::SESSION_ID]);
    }

    public function execute($params = array())
    {
        // $this->loadTemplate("article.twig");

        if (empty($params) && User::isLoggedIn()) {
            $this->viewSelf();
        } else {
            if (filter_var($params[0], FILTER_VALIDATE_INT)) {
                $this->view($params[0]);
            }
        }
    }
}
