<?php

namespace model;

class User extends Model
{
    private $username, $id;
    const TABLE = "users";
    const ROLES = "roles";

    public function register($email, $firstname, $lastname, $password)
    {
        $role = $this->db->select(self::ROLES, ["id"], ["name", "=", "user"])[0]->id;
        $dummy = $this->db->select(self::TABLE, ["id"], ["email", "=", $email]);

        if(sizeof($dummy) > 0) {
            throw new \RuntimeException("User with email {$email} already exists");
        }

        $password = \helper\Hash::hash($password);

        $this->db->insert("users", [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
            "role" => $role
        ]);
    }

    public function login($email, $password)
    {
        $dbresult = $this->db->select("users", ["id", "password"], ["email", "=", $email]);

        if(sizeof($dbresult) == 0) {
            throw new \RuntimeException("User not found");
        }

        if(!\helper\Hash::verify($password, $dbresult[0]->password)) {
            throw new \RuntimeException("Wrong password");
        }

        \helper\Session::set("user_id", $dbresult[0]->id);
    }

    public function logout()
    {
        \helper\Session::unset("user_id");
    }

    public function update($user, $params = array())
    {
        return $this->db->update(self::TABLE, $params, ["id", '=', $user]);
    }

    public static function isLoggedIn() {
        return \helper\Session::exists("user_id");
    }
}
