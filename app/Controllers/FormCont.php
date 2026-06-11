<?php

namespace App\Controllers; 

use App\Controllers\BaseController; 
use App\Libraries\Uploader; 
use App\Libraries\Alert; 
use App\Models\StageModel; 
use App\Models\ResultModel; 

class FormCont extends BaseController
{
    private function getCislaEtap() 
    {
        return range(1, 25); // Vytvoří a vrátí pole s čísly od 1 do 25 pro výběr ve formuláři
    }

    private function getOrCreateRider($celeJmeno) 
    {
        $celeJmeno = trim($celeJmeno); 
        if (empty($celeJmeno)) return null; 

        // Půjčíme si databázové připojení z existujícího StageModelu
        $stageModel = new StageModel();
        $db = $stageModel->db; 
        
        // Hledání jezdce v tabulce km_rider přes zapůjčené připojení
        $jezdec = $db->query(" 
            SELECT id FROM km_rider 
            WHERE LOWER(TRIM(CONCAT(first_name, ' ', last_name))) = LOWER(TRIM(?)) 
        ", [$celeJmeno])->getRow(); 

        if ($jezdec) { 
            return $jezdec->id; // Pokud existuje, vrátíme jeho ID
        }

        // Pokud neexistuje, vložíme ho do tabulky km_rider
        $casti = explode(' ', $celeJmeno, 2); 
        $db->table('km_rider')->insert([ 
            'first_name' => $casti[0], 
            'last_name'  => $casti[1] ?? '' 
        ]);

        return $db->insertID(); // Vrátí ID nově vloženého jezdce
    }

    private function saveStageWinner($idStage, $idRider) 
    {
        $resultModel = new ResultModel(); 

        // Soft-delete starého vítěze této etapy (využívá ResultModel)
        $resultModel->where(['id_stage' => $idStage, 'rank' => 1])->delete(); 

        if ($idRider) { 
            // Vloží nového vítěze přes ResultModel (automaticky vyplní created_at)
            $resultModel->insert([ 
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
        return view('3.AdminStranka/add', [ 
            'nazev' => "Vytvoření nové etapy", 
            'mozna_cisla' => $this->getCislaEtap() 
        ]);
    }

    public function create() 
    {
        $model = new StageModel(); 
        $uploader = new Uploader(); 
        $alert = new Alert(); 
        
        $dbData = [ 
            'id_race_year'    => 646, 
            'number'          => $this->request->getPost('number'), 
            'departure'       => $this->request->getPost('departure'), 
            'arrival'         => $this->request->getPost('arrival'), 
            'date'            => $this->request->getPost('date') ?: null, 
            'distance'        => (float)$this->request->getPost('distance'), 
            'vertical_meters' => (int)$this->request->getPost('vertical_meters'), 
            'note'            => $this->request->getPost('note') ?? '', 
            'description'     => $this->request->getPost('description'), 
        ];

        $file = $this->request->getFile('profile_image'); 
        if ($file?->isValid() && !$file->hasMoved()) { 
            $path = FCPATH . 'obrazky/stages/profiles/'; 
            $name = 'profile-' . $dbData['number']; 
            $uploadResult = $uploader->uploadFile($file, $path, $name); 
            
            if ($uploadResult['uploaded']) { 
                $dbData['profile'] = $uploadResult['name']; 
            }
        }

        $idStage = $model->insert($dbData); 

        $vitezJmeno = trim((string)$this->request->getPost('vitez_jmeno')); 
        $idRider = $this->getOrCreateRider($vitezJmeno); 
        $this->saveStageWinner($idStage, $idRider); 

        $alertType = $idStage ? 'success' : 'danger'; 
        $alert->set($alertType, 'recordCreated', ['id' => $idStage ?: 0]); 

        return redirect()->to(base_url('/')); 
    }

    // ==========================================
    // ÚPRAVA ETAPY
    // ==========================================
    public function edit($id) 
    {
        helper(['form']); 
        $model = new StageModel(); 
        $etapa = $model->find($id); 
        
        if (!$etapa) { 
            (new Alert())->set('danger', 'saveFailed'); 
            return redirect()->to(base_url('/')); 
        }

        $etapa->vitez_jmeno = ''; 

        // Využijeme ResultModel a propojíme ho s tabulkou jezdců
        $resultModel = new ResultModel();
        
        $vitez = $resultModel->asObject()
                             ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") 
                             ->join('km_rider', 'km_rider.id = km_result.id_rider') 
                             ->where(['km_result.id_stage' => $id, 'km_result.rank' => 1]) 
                             ->first(); 

        if ($vitez) { 
            $etapa->vitez_jmeno = $vitez->cele_jmeno; 
        }

        return view('3.AdminStranka/edit', [ 
            'etapa' => $etapa, 
            'nazev' => "Úprava etapy č. " . $etapa->number, 
            'mozna_cisla' => $this->getCislaEtap() 
        ]);
    }

    public function update($id) 
    {
        $model = new StageModel(); 
        $uploader = new Uploader(); 
        $alert = new Alert(); 

        $dbData = [ 
            'number'          => $this->request->getPost('number'), 
            'departure'       => $this->request->getPost('departure'), 
            'arrival'         => $this->request->getPost('arrival'), 
            'date'            => $this->request->getPost('date') ?: null, 
            'distance'        => (float)$this->request->getPost('distance'), 
            'vertical_meters' => (int)$this->request->getPost('vertical_meters'), 
            'note'            => $this->request->getPost('note') ?? '', 
            'description'     => $this->request->getPost('description'), 
        ];

        $file = $this->request->getFile('profile_image'); 
        if ($file?->isValid() && !$file->hasMoved()) { 
            $path = FCPATH . 'obrazky/stages/profiles/'; 
            $name = 'profile-' . $dbData['number']; 
            $uploadResult = $uploader->uploadFile($file, $path, $name); 
            
            if ($uploadResult['uploaded']) { 
                $dbData['profile'] = $uploadResult['name']; 
            }
        }

        $updated = $model->update($id, $dbData); 

        $vitezJmeno = trim((string)$this->request->getPost('vitez_jmeno')); 
        $idRider = $this->getOrCreateRider($vitezJmeno); 
        $this->saveStageWinner($id, $idRider); 
        
        $alertType = $updated ? 'success' : 'danger'; 
        $alert->set($alertType, 'recordUpdated', ['id' => $id]); 

        return redirect()->to(base_url('/')); 
    }

    // ==========================================
    // SMAZÁNÍ ETAPY
    // ==========================================
    public function delete($id) 
    {
        $model = new StageModel(); 
        $resultModel = new ResultModel(); 
        $alert = new Alert(); 

        if ($model->find($id)) { 
            // Smazání přes modely (zajistí bezpečné soft-delete u výsledků i etapy)
            $resultModel->where('id_stage', $id)->delete(); 
            $model->delete($id); 

            $alert->set('success', 'recordDeleted', ['id' => $id]); 
        } else { 
            $alert->set('danger', 'recordDeleted', ['id' => $id]); 
        }

        return redirect()->to(base_url('/')); 
    }
}