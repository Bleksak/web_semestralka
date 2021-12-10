<?php

namespace controller;

class NotfoundController extends Controller
{
    public function execute($params = array())
    {
        $this->setTitle("Stránka nenalezena");
        $this->loadTemplate("404.twig");
    }
}
