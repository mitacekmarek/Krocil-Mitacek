<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Uploader;
use App\Models\StageModel;

class FormCont extends BaseController
{
    // Pomocná metoda pro definici rozsahu čísel etap
    private function getCislaEtap() {
        return range(1, 25);
    }

    // ==========================================
    // PŘIDÁVÁNÍ NOVÉ ETAPY
    // ==========================================
    public function add()
    {
        helper(['form']);
        $data['nazev'] = "Vytvoření nové etapy";
        // Předáváme pole čísel do pohledu
        $data['mozna_cisla'] = $this->getCislaEtap();

        return view('3.AdminStranka/add', $data);
    }

    public function create()
    {
        $model = new StageModel();
        $uploader = new Uploader();

        $dbData = [
            'number'          => $this->request->getPost('number'),
            'departure'       => $this->request->getPost('departure'),
            'arrival'         => $this->request->getPost('arrival'),
            'date'            => $this->request->getPost('date'),
            'distance'        => $this->request->getPost('distance'),
            'vertical_meters' => $this->request->getPost('vertical_meters'),
            'note'            => $this->request->getPost('note'),
        ];

        $file = $this->request->getFile('profile_image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $path = FCPATH . 'obrazky/stages/profiles/';
            $name = 'profile-' . $dbData['number'];
            
            $uploadResult = $uploader->uploadFile($file, $path, $name);
            
            if ($uploadResult['uploaded']) {
                $dbData['profile'] = $uploadResult['name'];
            }
        }

        $model->insert($dbData);
        
        return redirect()->to(base_url('/'))->with('success', 'Nová etapa byla vytvořena.');
    }

    // ==========================================
    // ÚPRAVA ETAPY
    // ==========================================
    public function edit($id)
    {
        helper(['form']);
        $model = new StageModel();
        
        $etapa = $model->find($id);
        
        if ($etapa === null) {
            return redirect()->to(base_url('/'))->with('error', 'Etapa nenalezena.');
        }

        $data['etapa'] = $etapa;
        $data['nazev'] = "Úprava etapy č. " . $etapa->number;
        // Předáváme pole čísel i do editace
        $data['mozna_cisla'] = $this->getCislaEtap();

        return view('3.AdminStranka/edit', $data);
    }

    public function update($id)
    {
        $model = new StageModel();
        $uploader = new Uploader();

        $dbData = [
            'number'          => $this->request->getPost('number'),
            'departure'       => $this->request->getPost('departure'),
            'arrival'         => $this->request->getPost('arrival'),
            'date'            => $this->request->getPost('date'),
            'distance'        => $this->request->getPost('distance'),
            'vertical_meters' => $this->request->getPost('vertical_meters'),
            'note'            => $this->request->getPost('note'),
        ];

        $file = $this->request->getFile('profile_image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $path = FCPATH . 'obrazky/stages/profiles/';
            $name = 'profile-' . $dbData['number'];
            
            $uploadResult = $uploader->uploadFile($file, $path, $name);
            
            if ($uploadResult['uploaded']) {
                $dbData['profile'] = $uploadResult['name'];
            }
        }

        $model->update($id, $dbData);
        
        return redirect()->to(base_url('/'))->with('success', 'Etapa byla úspěšně upravena.');
    }

    // ==========================================
    // SMAZÁNÍ ETAPY
    // ==========================================
    public function delete($id)
    {
        $model = new StageModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to(base_url('/'))->with('success', 'Etapa byla smazána.');
        }

        return redirect()->to(base_url('/'))->with('error', 'Etapa nebyla nalezena, nelze smazat.');
    }
}