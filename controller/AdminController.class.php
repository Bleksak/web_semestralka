<?php

namespace controller;

use helper\Header;
use model\Article;
use model\Role;
use model\User;

class AdminController extends Controller
{
    const PAGE_INVALID = -1;
    const PAGE_DEFAULT = 0;
    const PAGE_USERS = 1;
    const PAGE_ARTICLES = 2;
    const PAGE_DELETE_ARTICLE = 3;
    const PAGE_APPROVE_ARTICLE = 4;
    const PAGE_EDIT_USER = 5;

    private static function getPage(array $params)
    {
        if (empty($params)) {
            return self::PAGE_INVALID;
        }

        switch ($params[0]) {
            case "users":
                return self::PAGE_USERS;
            case "articles":
                return self::PAGE_ARTICLES;
            case "delarticle":
                return self::PAGE_DELETE_ARTICLE;
            case "approve":
                return self::PAGE_APPROVE_ARTICLE;
            case "update":
                return self::PAGE_EDIT_USER;
            default:
                return self::PAGE_INVALID;
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

    private function deleteArticle($params)
    {
        $model = new Article();
        if (isset($params[1])) {
            $model->delete($params[1]);
        }
    }

    private function approveArticle($params)
    {
        $model = new Article();
        if (isset($params[1])) {
            $model->approve($params[1]);
        }
    }

    private function prepareUsers()
    {
        $user = new User();
        $role = new Role();

        $users = array_filter($user->getAll(), function ($u) {
            if ($u->id == User::getData()[User::SESSION_ID]) {
                return false;
            }

            if ($u->role_id > User::getData()[User::SESSION_ROLE]) {
                return false;
            }

            return true;
        });

        $roles = array_filter($role->getAll(), function ($r) {
            if ($r->id > User::getData()[User::SESSION_ROLE]) {
                return false;
            }

            // superadmina nemuze nastavit nikdo
            if ($r->name == "superadmin") {
                return false;
            }

            return true;
        });

        $this->add("users", $users);
        $this->add("roles", $roles);
    }

    private function updateUser($params)
    {
        if (!isset($params[1]) || !isset($params[2])) {
            return;
        }

        if (!is_numeric($params[2])) {
            return;
        }

        if ($params[2] > User::getData()[User::SESSION_ROLE] || $params[2] == 4) {
            return;
        }

        $user = new User();
        $u = $user->find($params[1]);
        if ($u->id == User::getData()[User::SESSION_ID] || $u->role_id > User::getData()[User::SESSION_ROLE]) {
            return;
        }

        $user->update($params[1], [
            "role" => $params[2]
        ]);
    }

    public function execute($params = array())
    {
        if (!User::isLoggedIn() || User::getData()[User::SESSION_ROLE] < 3) {
            return;
        }

        switch (self::getPage($params)) {
                // case self::PAGE_DEFAULT: {
                //         $this->setTitle("Administrace");
                //         $this->loadTemplate("admin.twig");
                //     }
                //     break;

            case self::PAGE_ARTICLES: {
                    $this->prepareArticles();
                    $this->setTitle("Správa článků");
                    $this->loadTemplate("admin_articles.twig");
                }
                break;

            case self::PAGE_USERS: {
                    $this->prepareUsers();
                    $this->setTitle("Správa uživatelů");
                    $this->loadTemplate("admin_users.twig");
                }
                break;

            case self::PAGE_DELETE_ARTICLE: {
                    $this->deleteArticle($params);
                    Header::redirect("/admin/articles");
                }
                break;

            case self::PAGE_APPROVE_ARTICLE: {
                    $this->approveArticle($params);
                    Header::redirect("/admin/articles");
                }
                break;

            case self::PAGE_EDIT_USER: {
                    $this->updateUser($params);
                    Header::redirect("/admin/users");
                }
                break;
        }
    }
}
