<?php

class User extends Model
{
    private $username, $id;

    public function register()
    {
    }

    public function login($username, $password)
    {
        $dbresult = $this->db->select("users", ["id", "username", "password"], ["username", "=", $username]);
        // $dbresult->username
        // $dbresult->password

        if(sizeof($dbresult) == 0) {
            // username not found
        }

        if(Hash::verify($password, $dbresult[0]->password)) {
            // Set session id
        }
    }

    public function logout()
    {
    }

    public function update()
    {
    }
}
