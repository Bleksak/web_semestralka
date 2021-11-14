<?php

namespace model;

use \helper\Session;
use \helper\Hash;

class User extends Model
{
    const TABLE = "users";
    const ROLES = "roles";

    const SESSION = "user";
    const SESSION_ID = "id";
    const SESSION_FIRSTNAME = "firstname";
    const SESSION_LASTNAME = "lastname";
    const SESSION_MAIL = "email";
    const SESSION_ROLE = "role";
    const SESSION_LOGGED = "logged";

    public function register($email, $firstname, $lastname, $password)
    {
        $role = $this->db->select(self::ROLES, ["id"], ["name", "=", "user"])[0]->id;
        $dummy = $this->db->select(self::TABLE, ["id"], ["email", "=", $email]);

        if(sizeof($dummy) > 0) {
            throw new \RuntimeException("Uživatel se zadaným emailem už existuje.");
        }

        $password = Hash::hash($password);

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
        $dbresult = $this->db->select("users", [], ["email", "=", $email]);

        if(sizeof($dbresult) == 0) {
            throw new \RuntimeException("Uživatel nebyl nalezen");
        }

        if(!Hash::verify($password, $dbresult[0]->password)) {
            throw new \RuntimeException("Neplatné heslo");
        }

        Session::set(self::SESSION, [
            self::SESSION_ID => $dbresult[0]->id,
            self::SESSION_FIRSTNAME => $dbresult[0]->firstname,
            self::SESSION_LASTNAME => $dbresult[0]->lastname,
            self::SESSION_MAIL => $dbresult[0]->email,
            self::SESSION_ROLE => $dbresult[0]->role,
            self::SESSION_LOGGED => true
        ]);
    }

    public function logout()
    {
        Session::unset(self::SESSION);
    }

    public function update($user, $params = array())
    {
        return $this->db->update(self::TABLE, $params, ["id", '=', $user]);
    }

    public static function isLoggedIn() {
        return Session::exists(self::SESSION);
    }

    public function isAdmin() {
        return Session::get(self::SESSION)[self::SESSION_ROLE] >= 2;
    }

    public static function getData() {
        if(!self::isLoggedIn()) {
            return [self::SESSION_LOGGED => false];
        }

        return Session::get(self::SESSION);
    }
}
