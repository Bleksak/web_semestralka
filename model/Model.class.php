<?php

namespace model;

abstract class Model {
    protected \core\Database $db;

    public function __construct() {
        $this->db = \core\Database::getInstance();
    }
}
