<?php
namespace app\database;

class Update
{
    private string $sql;
    private array $data;
    private array $where = [];

    public function update(string $table, array $data)
    {
        $this->data = $data;
        // update users set firstName = :firstName, lastName = :lastName where id = :id
        $this->sql = "update {$table} set ";
        foreach (array_keys($this->data) as $field) {
            $this->sql.= "{$field} = :{$field}, ";
        }

        $this->sql = rtrim($this->sql, ', ');

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
            throw new \Exception("I need where do update");
        }

        $connection = Connection::getConnection();
        $prepare = $connection->prepare($this->sql);

        return $prepare->execute(array_merge($this->data, $this->where['execute']));
    }

    public function getSql()
    {
        if (empty($this->where)) {
            throw new \Exception("I need where do update");
        }

        return $this->sql;
    }
}
