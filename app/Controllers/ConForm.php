<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Uploader; // Přidáme naši novou třídu pro upload

class ConForm extends BaseController
{
    // ==========================================
    // PŘIDÁVÁNÍ NOVÉ ETAPY
    // ==========================================
    public function add()
    {
        helper(['form']);
        $model = new \App\Models\EtapaI();
        
        $data['nazev'] = "Vytvoření nové etapy";
        
        // Zjistíme jaké má být další číslo etapy
        $vsechna_cisla = $model->findColumn('number');
        if (empty($vsechna_cisla)) {
            $data['dalsi_etapa'] = 1;
        } else {
            $data['dalsi_etapa'] = max($vsechna_cisla) + 1;
        }

        return view('3stranka/add', $data);
    }

    public function create()
    {
        $model = new \App\Models\EtapaI();
        $uploader = new Uploader(); // Načteme naši knihovnu

        $dbData = [
            'number'    => $this->request->getPost('number'),
            'departure' => $this->request->getPost('departure'),
            'arrival'   => $this->request->getPost('arrival'),
            'date'      => $this->request->getPost('date'),
            'distance'  => $this->request->getPost('distance'),
            'note'      => $this->request->getPost('note'),
        ];

        // Zpracování Uploadu Obrázku
        $file = $this->request->getFile('profile_image');
        
        if ($file->isValid() && !$file->hasMoved()) {
            // Nastavíme cestu kam se to uloží (složka public/obrazky/stages/profiles/)
            $path = FCPATH . 'obrazky/stages/profiles/';
            // Název souboru (např. profile-1)
            $name = 'profile-' . $dbData['number'];
            
            // Zavoláme naši metodu z návodu
            $uploadResult = $uploader->uploadFile($file, $path, $name);
            
            if ($uploadResult['uploaded']) {
                $dbData['profile'] = $uploadResult['name']; // Uložíme název s příponou do DB
            }
        }

        $model->insert($dbData);
        return redirect()->to(base_url('/'))->with('success', 'Nová etapa byla vytvořena.');
    }

    // ==========================================
    // ÚPRAVA EXISTUJÍCÍ ETAPY
    // ==========================================
    public function edit($id)
    {
        helper(['form']);
        $model = new \App\Models\EtapaI();
        
        $etapa = $model->find($id);
        
        // Pokud etapa neexistuje, hodíme ho pryč
        if ($etapa === null) {
            return redirect()->to(base_url('/'))->with('error', 'Etapa nenalezena.');
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

        // Zpracování Uploadu Obrázku při úpravě
        $file = $this->request->getFile('profile_image');
        
        // Kontrola, jestli uživatel nahrál nový obrázek
        if ($file->isValid() && !$file->hasMoved()) {
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
    // SMAZÁNÍ
    // ==========================================
    public function delete($id)
    {
        if ($id !== null) {
            $model = new \App\Models\EtapaI();
            $model->delete($id); 
        }
        return redirect()->to(base_url('/'))->with('success', 'Etapa byla smazána.');
    }
}