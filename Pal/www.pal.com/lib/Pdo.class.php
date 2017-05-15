<?php

class Mysql
{
    protected $pdo = null;

    public function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME;
        $username = DB_USER;
        $password = DB_PASS;
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::MYSQL_ATTR_FOUND_ROWS => true,
        );

        $this->pdo = new Pdo($dsn, $username, $password, $options);
    }

    public function exec($sql)
    {
        if (1 === func_num_args()) {
            return $this->pdo->exec($sql);
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        return $stmt->rowCount();
    }

    public function getOne($sql)
    {
        if (1 === func_num_args()) {
            $stmt = $this->pdo->query($sql);
            if (false === $stmt) {
                return false;
            }
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        $value = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $value;
    }

    public function getRow($sql, $args = null)
    {
        if (1 === func_num_args()) {
            $stmt = $this->pdo->query($sql);
            if (false === $stmt) {
                return false;
            }
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        $row = $stmt->fetch();
        $stmt->closeCursor();

        return $row;
    }

    public function getAll($sql, $args = null)
    {
        if (1 === func_num_args()) {
            $stmt = $this->pdo->query($sql);
            if (false === $stmt) {
                return false;
            }
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        return $stmt->fetchAll();
    }

    public function getPairs($sql)
    {
        if (1 === func_num_args()) {
            $stmt = $this->pdo->query($sql);
            if (false === $stmt) {
                return false;
            }
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        return $stmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

    public function getColumn($sql)
    {
        if (1 === func_num_args()) {
            $stmt = $this->pdo->query($sql);
            if (false === $stmt) {
                return false;
            }
        } else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function prepareParams($sql, $params)
    {
        if (is_scalar($params) || null === $params) {
            $params = func_get_args();
            array_shift($params);
        }

        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute((array)$params)) {
            return false;
        }

        return $stmt;
    }
}