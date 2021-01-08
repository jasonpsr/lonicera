<?php

namespace lonicera\core;

class Db
{
    private $dbLink;
    protected $queryNum = 0;
    private static $instance;
    protected $PDOStatement;
    // 事务数
    protected $transTimes = 0;
    protected $bind = [];
    public $rows = 0;

    public function __construct($config)
    {
        $this->connect($config);
    }

    public static function getInstance($config)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function connect($config)
    {
        try {
            $this->dbLink = new \PDO($config['dsn'], $config['username'], $config['password'], $config['param']);
        } catch (\PDOException $e) {
            throw new BaseException('数据库连接失败', 1000, $e);
        }

        return $this->dbLink;
    }

    public function query($sql, $bind = [], $fetchType = \PDO::FETCH_ASSOC)
    {
        if (!$this->dbLink) {
            throw new \Exception('数据库连接失败');
        }
        $this->PDOStatement = $this->dbLink->prepare($sql);
        $this->PDOStatement->execute($bind);
        $ret = $this->PDOStatement->fetchAll($fetchType);
        $this->rows = count($ret);
        return $ret;
    }

    public function execute($sql, $bind = [])
    {
        if (!$this->dbLink) {
            throw new \Exception('数据库连接失败');
        }
        $this->PDOStatement = $this->dbLink->prepare($sql);
        $ret = $this->PDOStatement->execute($bind);
        $this->rows = $this->PDOStatement->rowCount();
        return $ret;
    }

    public function startTrans()
    {
        ++$this->transTimes;
        if (1 == $this->transTimes) {
            $this->dbLink->beginTransaction(); // 不存在已创建事务才开启新的事务
        } else {
            $this->dbLink->execute("SAVEPOINT tr($this->transTimes)"); // 创建一个savepoint
        }
    }

    public function commit()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->commit();
        }

        --$this->transTimes;
    }

    public function rollback()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->rollback();
        } elseif ($this->transTimes > 1) {
            $this->dbLink->execute("ROLLBACK TO SAVEPOINT tr{$this->transTimes}");
        }
        $this->transTimes = max(0, $this->transTimes - 1);
    }
}