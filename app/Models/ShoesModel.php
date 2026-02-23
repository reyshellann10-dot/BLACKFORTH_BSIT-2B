<?php

namespace App\Models;

use CodeIgniter\Model;

class ShoesModel extends Model
{
    protected $table = 'shoes';
    protected $primaryKey = 'id';

    protected $allowedFields = ['uuid','name', 'size','color'];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('name', $searchValue)
                ->orLike('size', $searchValue)
                ->orLike('color', $searchValue)
                ->groupEnd();
        }

        // Clone builder for filtered count before applying limit
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
