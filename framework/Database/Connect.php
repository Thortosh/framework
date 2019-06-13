<?php


namespace Anton\Database;


class Connect
{
    public $pdo = null;

    public function __construct()
    {
        $config = require CONFIG_PATH . 'db.php';

        if (!in_array($config['driver'], \PDO::getAvailableDrivers())) {
            throw new \PDOException ("Cannot work without a proper database setting up");
        }

        $conenct = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        $this->pdo = new \PDO($conenct, $config['user'], $config['password']);
    }

}