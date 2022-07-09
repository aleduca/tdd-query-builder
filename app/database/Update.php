<?php
namespace app\database;

class Update {

    private string $sql;
    private array $data;
    private array $where;

    public function update(string $table, array $data, array $where)
    {
        $this->where = $where;
        $this->data = $data;
        // update users set firstName = :firstName, lastName = :lastName where id = :id
        $this->sql = "update {$table} set ";   
        foreach (array_keys($this->data) as $field) {
            $this->sql.= "{$field} = :{$field}, ";
        }

        $this->sql = rtrim($this->sql, ', ');
        $this->sql.= " where {$this->where[0]} = :{$this->where[0]}";

        return $this;
    }

    public function execute()
    {
        $connection = Connection::getConnection();
        $prepare = $connection->prepare($this->sql);

        return $prepare->execute(array_merge($this->data, [$this->where[0] => $this->where[1]]));
    }

    public function getSql()
    {
        return $this->sql;
    }

}