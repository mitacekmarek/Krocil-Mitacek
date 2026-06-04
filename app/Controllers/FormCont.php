<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Uploader;
use App\Models\StageModel;

class FormCont extends BaseController
{
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
        $data['mozna_cisla'] = $this->getCislaEtap();

        return view('3.AdminStranka/add', $data);
    }

    public function create()
    {
        $model = new StageModel();
        $uploader = new Uploader();

        $dbData = [
            'id_race_year'    => 646, // Sjednoceno na 646 podle StageCont
            'number'          => $this->request->getPost('number'),
            'departure'       => $this->request->getPost('departure'),
            'arrival'         => $this->request->getPost('arrival'),
            'date'            => $this->request->getPost('date') ?: null,
            'distance'        => $this->request->getPost('distance') ?: 0,
            'vertical_meters' => $this->request->getPost('vertical_meters') ?: 0,
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
        
        return redirect()->to(base_url('/'))->with('success', 'Etapa byla úspěšně vytvořena.');
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

        $db = \Config\Database::connect();
        
        // ROBUSTNÍ OPRAVA: Použití přímého SQL dotazu pro načtení vítěze, aby ho CodeIgniter nerozbil
        $vitez = $db->query("
            SELECT CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno 
            FROM km_result 
            JOIN km_rider ON km_rider.id = km_result.id_rider 
            WHERE km_result.id_stage = ? AND km_result.rank = 1
        ", [$id])->getRow();

        $etapa->vitez_jmeno = $vitez ? $vitez->cele_jmeno : '';

        $data['etapa'] = $etapa;
        $data['nazev'] = "Úprava etapy č. " . $etapa->number;
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
            'date'            => $this->request->getPost('date') ?: null,
            'distance'        => $this->request->getPost('distance') ?: 0,
            'vertical_meters' => $this->request->getPost('vertical_meters') ?: 0,
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

        // Uložení základních dat etapy do km_stage
        $model->update($id, $dbData);
        
        $db = \Config\Database::connect();
        $vitezJmeno = trim($this->request->getPost('vitez_jmeno')); 

        // Smazání původního vítěze pro tuto etapu
        $db->table('km_result')
           ->where('id_stage', $id)
           ->where('rank', 1)
           ->delete();

        // Uložení nového vítěze (pokud je pole vyplněné)
        if (!empty($vitezJmeno)) {
            // ROBUSTNÍ OPRAVA: Přímý SQL dotaz obchází automatické escapování Query Builderu
            $jezdec = $db->query("SELECT id FROM km_rider WHERE CONCAT(first_name, ' ', last_name) = ?", [$vitezJmeno])->getRow();

            if ($jezdec) {
                $db->table('km_result')->insert([
                    'id_stage'    => $id,
                    'id_rider'    => $jezdec->id,
                    'rank'        => 1,
                    'type_result' => 1
                ]);
            } else {
                // Pokud jméno v databázi vůbec neexistuje, vrátí chybovou hlášku na hlavní stranu
                return redirect()->to(base_url('/'))->with('error', 'Etapa upravena, ale vítěz nebyl uložen! Jezdec jménem "' . $vitezJmeno . '" nebyl v databázi nalezen. Zkontrolujte překlepy a diakritiku.');
            }
        }
        
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