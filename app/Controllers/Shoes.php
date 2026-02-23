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
 public function save(){
        $name = $this->request->getPost('name');
        $size = $this->request->getPost('size');
        $color = $this->request->getPost('color');

        $userModel = new \App\Models\ShoesModel();
        $logModel = new LogModel();

        $data = [
            'name'       => $name,
            'size'       => $size,
            'color'    => $color
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Shoes has been added: ' . $name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Shoes']);
        }
 }

 public function update(){
    $model = new ShoesModel();
    $logModel = new LogModel();
    $userId = $this->request->getPost('id');
    $name = $this->request->getPost('name');
    $size = $this->request->getPost('size');
    $color = $this->request->getPost('color');

    $userData = [
        'name'       => $name,
        'size'       => $size,
        'color'    => $color
    ];

    $updated = $model->update($userId, $userData);

    if ($updated) {
        $logModel->addLog('New Shoes has been apdated: ' . $name, 'UPDATED');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Shoes updated successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error updating Shoes.'
        ]);
    }
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