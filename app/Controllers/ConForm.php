<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Uploader;

class ConForm extends BaseController
{
    // ==========================================
    // SEZNAM ETAP (HLAVNÍ STRÁNKA SPRÁVY)
    // ==========================================
    public function index()
    {
        $model = new \App\Models\EtapaI();

        $data['etapy'] = $model->findAll();
        $data['nazev'] = "Správa etap";

        return view('3stranka/index', $data);
    }

    // ==========================================
    // PŘIDÁVÁNÍ NOVÉ ETAPY
    // ==========================================
    public function add()
    {
        helper(['form']);
        $model = new \App\Models\EtapaI();
        
        $data['nazev'] = "Vytvoření nové etapy";
        
        $vsechna_cisla = $model->findColumn('number');
        $data['dalsi_etapa'] = empty($vsechna_cisla) ? 1 : max($vsechna_cisla) + 1;

        return view('3stranka/add', $data);
    }

    public function create()
    {
        $model = new \App\Models\EtapaI();
        $uploader = new Uploader();

        $dbData = [
            'number'    => $this->request->getPost('number'),
            'departure' => $this->request->getPost('departure'),
            'arrival'   => $this->request->getPost('arrival'),
            'date'      => $this->request->getPost('date'),
            'distance'  => $this->request->getPost('distance'),
            'note'      => $this->request->getPost('note'),
        ];

        // Upload obrázku
        $file = $this->request->getFile('profile_image');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $path = FCPATH . 'obrazky/stages/profiles/';
            $name = 'profile-' . $dbData['number'];
            
            $uploadResult = $uploader->uploadFile($file, $path, $name);
            
            if ($uploadResult['uploaded']) {
                $dbData['profile'] = $uploadResult['name'];
            }
        }

        $model->insert($dbData);
        return redirect()->to(base_url('/sprava'))->with('success', 'Nová etapa byla vytvořena.');
    }

    // ==========================================
    // ÚPRAVA ETAPY
    // ==========================================
    public function edit($id)
    {
        helper(['form']);
        $model = new \App\Models\EtapaI();
        
        $etapa = $model->find($id);
        
        if ($etapa === null) {
            return redirect()->to(base_url('/sprava'))->with('error', 'Etapa nenalezena.');
        }

        $data['etapa'] = $etapa;
        $data['nazev'] = "Úprava etapy č. " . $etapa->number;

        return view('3stranka/edit', $data);
    }

    public function update($id)
    {
        $model = new \App\Models\EtapaI();
        $uploader = new Uploader();

        $dbData = [
            'number'    => $this->request->getPost('number'),
            'departure' => $this->request->getPost('departure'),
            'arrival'   => $this->request->getPost('arrival'),
            'date'      => $this->request->getPost('date'),
            'distance'  => $this->request->getPost('distance'),
            'note'      => $this->request->getPost('note'),
        ];

        // Upload nového obrázku
        $file = $this->request->getFile('profile_image');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $path = FCPATH . 'obrazky/stages/profiles/';
            $name = 'profile-' . $dbData['number'];
            
            $uploadResult = $uploader->uploadFile($file, $path, $name);
            
            if ($uploadResult['uploaded']) {
                $dbData['profile'] = $uploadResult['name'];
            }
        }

        $model->update($id, $dbData);
        return redirect()->to(base_url('/sprava'))->with('success', 'Etapa byla úspěšně upravena.');
    }

    // ==========================================
    // SMAZÁNÍ ETAPY
    // ==========================================
    public function delete($id)
    {
        if ($id !== null) {
            $model = new \App\Models\EtapaI();
            $model->delete($id);
        }

        return redirect()->to(base_url('/sprava'))->with('success', 'Etapa byla smazána.');
    }
}
