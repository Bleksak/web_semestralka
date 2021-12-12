<?php

namespace model;

class Role extends Model {
    public function getAll() {
        return $this->db->select("roles");
    }
}
