<?php

namespace controller;

use helper\Header;
use model\Article;
use model\User;

class AdminController extends Controller
{
    const PAGE_DEFAULT = 0;
    const PAGE_USERS = 1;
    const PAGE_ARTICLES = 2;
    const PAGE_DELETE_ARTICLE = 3;
    const PAGE_DELETE_USER = 4;
    const PAGE_EDIT_USER = 5;

    private static function getPage(array $params)
    {
        if (empty($params)) {
            return self::PAGE_DEFAULT;
        }

        switch ($params[0]) {
            case "users":
                return self::PAGE_USERS;
            case "articles":
                return self::PAGE_ARTICLES;
            case "delarticle":
                return self::PAGE_DELETE_ARTICLE;
            default:
                return self::PAGE_DEFAULT;
        }
    }

    private function prepareArticles()
    {
        $model = new Article();
        $articles = $model->getAll();
        $articles = array_map(function ($article) {
            $article->date = strftime("%d. %B %G", strtotime($article->date));
            return $article;
        }, $articles);

        $this->add("articles", $articles);
    }

    private function deleteArticle()
    {
        $model = new Article();
        if (isset($params[1])) {
            $model->delete($params[1]);
        }
    }

    public function execute($params = array())
    {
        if (!User::isLoggedIn() || User::getData()[User::SESSION_ROLE] < 2) {
            return;
        }

        switch (self::getPage($params)) {
            case self::PAGE_DEFAULT: {
                    $this->setTitle("Administrace");
                    $this->loadTemplate("admin.twig");
                }
                break;

            case self::PAGE_ARTICLES: {
                    $this->prepareArticles();
                    $this->setTitle("Správa článků");
                    $this->loadTemplate("admin_articles.twig");
                }
                break;

            case self::PAGE_USERS: {
                    $this->setTitle("Správa uživatelů");
                    $this->loadTemplate("admin_users.twig");
                }
                break;
            case self::PAGE_DELETE_ARTICLE: {
                    $this->deleteArticle();
                    Header::redirect("/admin/articles");
                }
                break;
        }
    }
}
