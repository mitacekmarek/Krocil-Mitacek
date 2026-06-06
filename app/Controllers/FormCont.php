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

    // Pomocná funkce: Najde jezdce v km_rider nebo vytvoří úplně nového
    private function getOrCreateRider($celeJmeno)
    {
        $celeJmeno = trim($celeJmeno);
        if (empty($celeJmeno)) return null;

        $db = \Config\Database::connect();
        
        // Zkusíme najít jezdce podle jména
        $jezdec = $db->query("
            SELECT id FROM km_rider 
            WHERE LOWER(TRIM(CONCAT(first_name, ' ', last_name))) = LOWER(TRIM(?))
        ", [$celeJmeno])->getRow();

        if ($jezdec) {
            return $jezdec->id;
        }

        // Pokud v DB není, vytvoříme NOVÉHO RIDERA
        $casti = explode(' ', $celeJmeno, 2);
        $db->table('km_rider')->insert([
            'first_name' => $casti[0],
            'last_name'  => isset($casti[1]) ? $casti[1] : ''
        ]);

        return $db->insertID();
    }

    // Pomocná funkce: Uloží vítěze přímo do tvé tabulky km_result (rank = 1)
    private function saveStageWinner($idStage, $idRider)
    {
        $db = \Config\Database::connect();
        $resultTable = $db->table('km_result');

        // Smažeme případného starého vítěze (rank 1) pro tuto etapu, aby tam nebyli dva
        $resultTable->where(['id_stage' => $idStage, 'rank' => 1])->delete();

        // Pokud máme ID jezdce, vložíme ho jako vítěze (rank = 1)
        if ($idRider) {
            $resultTable->insert([
                'id_stage' => $idStage,
                'id_rider' => $idRider,
                'rank'     => 1 
            ]);
        }
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
            'id_race_year'    => 646, 
            'number'          => $this->request->getPost('number'),
            'departure'       => $this->request->getPost('departure'),
            'arrival'         => $this->request->getPost('arrival'),
            'date'            => $this->request->getPost('date') ?: null,
            'distance'        => $this->request->getPost('distance') ?: 0,
            'vertical_meters' => $this->request->getPost('vertical_meters') ?: 0,
            'note'            => $this->request->getPost('note'),
            'description'     => $this->request->getPost('description'), // <-- DOPLNĚNO
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

        // 1. Uložíme etapu do km_stage a získáme její nové ID
        $idStage = $model->insert($dbData);

        // 2. Najdeme/vytvoříme jezdce a propojíme ho do km_result
        $vitezJmeno = trim($this->request->getPost('vitez_jmeno'));
        $idRider = $this->getOrCreateRider($vitezJmeno);
        $this->saveStageWinner($idStage, $idRider);

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

        $etapa->vitez_jmeno = '';

        // Načteme jméno vítěze přesně tak, jak to dělá tvůj StageCont
        $db = \Config\Database::connect();
        $vitez = $db->table('km_result')
                    ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") 
                    ->join('km_rider', 'km_rider.id = km_result.id_rider')
                    ->where('km_result.id_stage', $id)
                    ->where('km_result.rank', 1)
                    ->get()
                    ->getRow();

        if ($vitez) {
            $etapa->vitez_jmeno = $vitez->cele_jmeno;
        }

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
            'description'     => $this->request->getPost('description'), // <-- DOPLNĚNO
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

        // 1. Aktualizujeme data etapy
        $model->update($id, $dbData);

        // 2. Aktualizujeme vítěze v km_result
        $vitezJmeno = trim($this->request->getPost('vitez_jmeno'));
        $idRider = $this->getOrCreateRider($vitezJmeno);
        $this->saveStageWinner($id, $idRider);
        
        return redirect()->to(base_url('/'))->with('success', 'Etapa byla úspěšně upravena.');
    }

    // ==========================================
    // SMAZÁNÍ ETAPY
    // ==========================================
    public function delete($id)
    {
        $model = new StageModel();
        if ($model->find($id)) {
            $db = \Config\Database::connect();
            // Smažeme i řádek z výsledků, ať v DB nezůstane nepořádek
            $db->table('km_result')->where('id_stage', $id)->delete();
            
            $model->delete($id);
            return redirect()->to(base_url('/'))->with('success', 'Etapa byla smazána.');
        }

        return redirect()->to(base_url('/'))->with('error', 'Etapa nebyla nalezena, nelze smazat.');
    }
}