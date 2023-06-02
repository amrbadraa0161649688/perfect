<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function findOrFail($id);

    public function bulkDelete(array $ids);

    public function getAll();

    public function findWhere($column, $value);

    public function getAllOrderBy($column, $sort);

    public function findWhereIn($column, array $values);

    public function getWhereOperand($column, $operand, $value);

    public function findWhereFirst($column, $value);

    public function create(array $data);

    public function update(array $data, $model);

    public function changeStatus(array $data, $model);

    public function bulkRestore(array $ids);

    public function Inputs(array $data);

    public function Paginate(array $input, array $wheres);

    public function whereOptions(array $input, array $columns);
}
