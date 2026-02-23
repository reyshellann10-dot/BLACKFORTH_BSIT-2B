<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ShoesModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Shoes extends Controller
{
    public function index(){
        $model = new ShoesModel();
        $data['shoes'] = $model->findAll();
        return view('shoes/index', $data);
    }



public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\ShoesModel();

    $start = $request->getPost('start') ?? 0;
    $length = $request->getPost('length') ?? 10;
    $searchValue = $request->getPost('search')['value'] ?? '';

    $totalRecords = $model->countAll();
    $result = $model->getRecords($start, $length, $searchValue);

    $data = [];
    $counter = $start + 1;
    foreach ($result['data'] as $row) {
        $row['row_number'] = $counter++;
        $data[] = $row;
    }

    return $this->response->setJSON([
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $result['filtered'],
        'data' => $data,
    ]);
}

}