<?php
namespace app\database;

class Delete {

    private string $sql;
    private array $where;

    public function delete(string $table, array $where)
    {
        // delete from users where id = :id
        $this->where = $where;

        $this->sql = "delete from {$table}";
        $this->sql .= " where {$this->where[0]} = :{$this->where[0]}";


        return $this;
    }

    public function execute()
    {
        $connection = Connection::getConnection();
        $prepare = $connection->prepare($this->sql);
        return $prepare->execute([$this->where[0] => $this->where[1]]);
    }

    public function getSql()
    {
     return $this->sql;   
    }
}