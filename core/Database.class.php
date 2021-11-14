<?php

namespace core;

/**
 * The PDO database connector
 * 
 * @author Jiri Velek
 */

class Database
{
    private static self $instance;
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

    private static function buildWhereString(array $where, array &$valuesArray): string
    {
        $whereString = "";

        if (count($where) != 0) {
            $whereString = " WHERE ";
            $first = $where[0];
            $second = $where[2];
            $op = $where[1];

            $opArray = ["=", ">", "<", ">=", "<=", "LIKE"];

            if (!in_array($op, $opArray)) {
                throw new \InvalidArgumentException(sprintf("PDO Database exception: Invalid operator %s", $op));
            }

            array_push($valuesArray, $second);

            $whereString .= sprintf("%s %s ?", $first, $op);
        }

        return $whereString;
    }

    private function query(string $sql, array $params = array())
    {
        $this->stmt = $this->connection->prepare($sql);

        $values = array_values($params);

        for ($i = 0; $i < count($params); $i++) {
            $this->stmt->bindValue($i + 1, $values[$i]);
        }

        if ($this->stmt->execute()) {
            return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
        } else {
            throw new \RuntimeException("PDO Database query error");
        }
    }

    public function select(string $table, array $what = array(), array $where = array())
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

        $valuesArray = [];
        $whereString = self::buildWhereString($where, $valuesArray);

        $sql = sprintf("SELECT %s FROM %s%s", $whatString, $table, $whereString);

        return $this->query($sql, $valuesArray);
    }

    public function delete(string $table, array $where)
    {
        $values = [];
        $whereString = self::buildWhereString($where, $values);

        $sql = sprintf("DELETE FROM %s%s", $table, $whereString);
        return $this->query($sql, $values);
    }

    public function insert(string $table, array $data)
    {
        $fields = array_keys($data);
        $values = array_values($data);

        $fieldsString = join(", ", $fields);
        $valuesString = join(", ", array_map(fn (): string => "?", $values));

        $sql = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $fieldsString, $valuesString);
        return $this->query($sql, $values);
    }

    public function update(string $table, array $what, array $where)
    {
        $valuesArray = [];
        $whereString = self::buildWhereString($where, $valuesArray);

        $update = "";
        $params = [];

        foreach ($what as $key => $value) {
            array_push($params, $value);
            $update .= $key . " = ?";

            if (count($params) != count($what) - 1) {
                $update .= ", ";
            }
        }

        $sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $update, $whereString);
        return $this->query($sql, $params);
    }
}
