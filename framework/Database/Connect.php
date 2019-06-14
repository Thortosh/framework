<?php


namespace Anton\Database;


use Anton\Exceptions\ConnectException;

class Connect
{
    protected $pdo = null;

    public function __construct()
    {
        $config = config('db.connect');

        if (!in_array($config['driver'], \PDO::getAvailableDrivers())) {
            throw new \PDOException ("Cannot work without a proper database setting up");
        }

        $connect = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        $this->pdo = new \PDO($connect, $config['user'], $config['password']);
    }

    /**
     * @param $sql
     * @return array
     * @throws \Exception
     */
    public function execute($sql)
    {
        $result = [];
        $data = $this->pdo->query($sql);

        if ($data === false) {
            throw new ConnectException($this->getSqlError());
        }

        foreach ($data as $item) {
            $result[] = array_filter($item, function ($field) {
                return is_string($field);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $result;
    }

    protected function getSqlError()
    {
        $error = $this->pdo->errorInfo();
        return "SQLSTATE " . $error[0] . ": " . $error[2];
    }

}