<?php
namespace app\database;

class Select
{
    private string $sql;
    private bool $where = false;
    private array $binds = [];

    public function query(string $query)
    {
        $this->sql = $query;

        return $this;
    }

    public function where(string $field, string $operator, mixed $value, ?string $type = null)
    {
        (!$this->where) ?
            $this->sql.= " where {$field} {$operator} :{$field} {$type}":
            $this->sql.= " {$field} {$operator} :{$field}";

        $this->sql = rtrim($this->sql);

        $this->binds[$field] = $value;

        $this->where = true;

        return $this;
    }

    public function order(string $order)
    {
        $this->sql.= " {$order}";

        return $this;
    }

    public function get()
    {
        return (object)[
            'sql' => $this->sql,
            'binds' => $this->binds
        ];
    }
}
