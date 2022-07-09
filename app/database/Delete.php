<?php
namespace app\database;

class Delete
{
    private string $sql;
    private array $where = [];

    public function delete(string $table)
    {
        $this->sql = "delete from {$table}";

        return $this;
    }

    public function where(string $field, string $operator, string $value)
    {
        $this->where['placeholder'] = " where {$field} {$operator} :{$field}";
        $this->where['execute'] = [$field => $value];

        $this->sql.= $this->where['placeholder'];

        return $this;
    }

    public function execute()
    {
        if (empty($this->where)) {
            throw new \Exception("I need where do delete");
        }

        $connection = Connection::getConnection();
        $prepare = $connection->prepare($this->sql);
        return $prepare->execute($this->where['execute']);
    }

    public function getSql()
    {
        if (empty($this->where)) {
            throw new \Exception("I need where do delete");
        }

        return $this->sql;
    }
}
