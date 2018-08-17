<?php

class DB
{
    private $dsn;
    private $sth;
    private $dbh;
    private $user;
    private $charset;
    private $password;

    public $lastSQL = '';

    public function __setup($config = array())
    {
        $this->dsn = $config['dsn'];
        $this->user = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'];
        $this->connect();
    }

    private function connect()
    {
        if(!$this->dbh){
            $options = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $this->charset,
            ];
            $this->dbh = new \PDO($this->dsn, $this->user,
                $this->password, $options);
        }
    }

    // 开启事务
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function inTransaction()
    {
        return $this->dbh->inTransaction();
    }

    // 事务回滚
    public function rollBack()
    {
        return $this->dbh->rollBack();
    }

    // 事务提交
    public function commit()
    {
        return $this->dbh->commit();
    }

    // 检测是否执行有误
    function watchException($execute_state)
    {
        if(!$execute_state){
            throw new MySQLException("SQL: {$this->lastSQL}\n".$this->sth->errorInfo()[2], intval($this->sth->errorCode()));
        }
    }

    // 查列表
    public function fetchAll($sql, $parameters=[])
    {
        $result = [];
        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));

        while($result[] = $this->sth->fetch(\PDO::FETCH_ASSOC)){

        }
        array_pop($result);
        return $result;
    }

    public function fetchColumnAll($sql, $parameters=[], $position=0)
    {
        $result = [];
        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));

        while($result[] = $this->sth->fetch(\PDO::FETCH_COLUMN, $position)){

        }
        array_pop($result);
        return $result;
    }

    public function exists($sql, $parameters=[])
    {
        $this->lastSQL = $sql;
        $data = $this->fetch($sql, $parameters);
        return !empty($data);
    }

    public function query($sql, $parameters=[])
    {
        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));
        return $this->sth->rowCount();
    }

    public function fetch($sql, $parameters=[], $type=\PDO::FETCH_ASSOC)
    {
        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));
        return $this->sth->fetch($type);
    }

    public function fetchColumn($sql, $parameters=[], $position=0)
    {
        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));
        return $this->sth->fetch(\PDO::FETCH_COLUMN, $position);
    }

    // 更新
    public function update($table, $parameters=[], $condition=[])
    {
        $table = $this->format_table_name($table);
        $sql = "UPDATE $table SET ";
        $fields = [];
        $pdo_parameters = [];
        foreach ( $parameters as $field=>$value){
            $fields[] = '`'.$field.'`=:field_'.$field;
            $pdo_parameters['field_'.$field] = $value;
        }
        $sql .= implode(',', $fields);
        $fields = [];
        $where = '';
        if(is_string($condition)) {
            $where = $condition;
        } else if(is_array($condition)) {
            foreach($condition as $field=>$value){
                $parameters[$field] = $value;
                $fields[] = '`'.$field.'`=:condition_'.$field;
                $pdo_parameters['condition_'.$field] = $value;
            }
            $where = implode(' AND ', $fields);
        }
        if(!empty($where)) {
            $sql .= ' WHERE '.$where;
        }
        return $this->query($sql, $pdo_parameters);
    }

    // 添加
    public function insert($table, $parameters=[])
    {
        $table = $this->format_table_name($table);
        $sql = "INSERT INTO $table";
        $fields = [];
        $placeholder = [];
        foreach ( $parameters as $field=>$value){
            $placeholder[] = ':'.$field;
            $fields[] = '`'.$field.'`';
        }
        $sql .= '('.implode(",", $fields).') VALUES ('.implode(",", $placeholder).')';

        $this->lastSQL = $sql;
        $this->sth = $this->dbh->prepare($sql);
        $this->watchException($this->sth->execute($parameters));
        
        $id = $this->dbh->lastInsertId();
        if(empty($id)) {
            return $this->sth->rowCount();
        } else {
            return $id;
        }
    }

    public function errorInfo()
    {
        return $this->sth->errorInfo();
    }

    protected function format_table_name($table)
    {
        $parts = explode(".", $table, 2);

        if(count($parts) > 1) {
            $table = $parts[0].".`{$parts[1]}`";
        } else {
            $table = "`$table`";
        }
        return $table;
    }

    function errorCode()
    {
        return $this->sth->errorCode();
    }
}
 
class MySQLException extends \Exception { }

