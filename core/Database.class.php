<?php

namespace core;

/**
 * The PDO database connector
 * 
 * @author Jiri Velek
 */

class Database
{
    private static Self $instance;
    private \PDO $connection;
    private \PDOStatement $stmt;

    private function __construct()
    {
        $dbconfig = CONFIG["database"];

        $dbname = $dbconfig["dbname"];
        $hostname = $dbconfig["hostname"];
        $username = $dbconfig["username"];
        $password = $dbconfig["password"];

        $dsn = sprintf("mysql:host=%s;dbname=%s", $hostname, $dbname);
        $this->connection = new \PDO($dsn, $username, $password);
        $this->connection->exec("SET NAMES UTF8");
    }

    /**
     * Returns the singleton instance if it exists, or creates it otherwise
     */
    public static function getInstance(): Self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = array())
    {
        $this->stmt = $this->connection->prepare($sql);

        $values = array_values($params);

        for ($i = 0; $i < count($params); $i++) {
            $this->stmt->bindValue($i + 1, htmlspecialchars($values[$i]));
        }

        if ($this->stmt->execute()) {
            return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
        } else {
            throw new \RuntimeException("PDO Database query error");
        }
    }

    public function select(string $table, array $what = array(), string $where = "", array $params = array())
    {
        $whatString = "";

        if (count($what) == 0) {
            $whatString = "*";
        } else {
            $lastItem = array_pop($what);

            foreach ($what as $item) {
                $whatString .= $item . ", ";
            }

            $whatString .= $lastItem . " ";
        }

        $whereString = empty($where) ? "" : " WHERE " . $where;

        $sql = sprintf("SELECT %s FROM %s%s", $whatString, $table, $whereString);

        return $this->query($sql, $params);
    }

    public function join(string $first, string $second, string $on, array $what = array(), string $where = "", array $params = array(), string $postfix = "")
    {
        $fields = empty($what) ? "*" : join(", ", $what);

        $whereString = empty($where) ? "" : "WHERE " . $where;

        $sql = "SELECT $fields FROM $first INNER JOIN $second ON $on $whereString $postfix";
        return $this->query($sql, $params);
    }

    public function delete(string $table, string $where, array $params)
    {
        $whereString = "WHERE " . $where;

        $sql = "DELETE FROM $table $whereString";
        return $this->query($sql, $params);
    }

    public function insert(string $table, array $data)
    {
        $fields = array_keys($data);
        $values = array_values($data);

        $fieldsString = join(", ", $fields);
        $valuesString = join(", ", array_map(fn (): string => "?", $values));

        $sql = "INSERT INTO $table($fieldsString) VALUES($valuesString)";
        return $this->query($sql, $values);
    }

    public function update(string $table, array $what, string $where, array $params)
    {
        $update = "";
        $updateParams = [];

        foreach ($what as $key => $value) {
            array_push($updateParams, $value);
            $update .= $key . " = ?";

            if (count($updateParams) != count($what)) {
                $update .= ", ";
            }
        }

        $par = array_merge($updateParams, $params);

        $sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $update, $where);
        return $this->query($sql, $par);
    }
}
