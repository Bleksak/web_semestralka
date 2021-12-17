<?php

namespace controller;

use model\Review;
use model\User;
use RuntimeException;

class ReviewController extends Controller
{
    public function execute($params = array())
    {
        if (!isset($params[0])) {
            return;
        }

        try {
            $model = new Review();
            $user = new User();

            $reviews = array_map(function ($review) use ($user) {
                $review->author = $user->find($review->author);
                return $review;
            }, $model->get($params[0]));

            if(!User::isAdmin() && sizeof($reviews) > 0 && !$reviews[0]->approved) {
                return;
            }

            $this->add("reviews", $reviews);
            $this->loadTemplate("reviews.twig");
            $this->setTitle("Reviews");
        } catch (RuntimeException $e) {
        }
    }
}
