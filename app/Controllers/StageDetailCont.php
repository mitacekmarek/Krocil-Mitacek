<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use App\Models\StageModel;  // DOPLNĚNO
use App\Models\ResultModel;

class StageDetailCont extends BaseController
{
    // ZMĚNĚNO: Přejmenováno z "detail" na "index", aby to sedělo na Routes.php
    public function index($id)
    {
        $etapaModel = new StageModel();
        $resultModel = new ResultModel();
    
        // Zachována tvá kompletní logika pro načtení etapy včetně typu profilu
        $data['etapa'] = $etapaModel->select('km_stage.*, km_parcour_type.name AS parcour_name')
                                    ->join('km_parcour_type', 'km_parcour_type.id = km_stage.parcour_type', 'left')
                                    ->where('km_stage.id', $id)
                                    ->first();
                                    
        $data['nazev'] = "Detail etapy";
    
        // Zachována tvá kompletní výsledková listina s jezdci a filtry
        $data['ranking'] = $resultModel->select('km_result.*, km_rider.first_name, km_rider.last_name, km_rider.country')
                                       ->join('km_rider', 'km_rider.id = km_result.id_rider') 
                                       ->where('km_result.id_stage', $id)
                                       ->where('km_result.type_result', 1)
                                       ->where('km_result.rank >', 0) 
                                       ->orderBy('km_result.rank', 'ASC')
                                       ->findAll();
    
        if (!$data['etapa']) {
            return "Chyba: Etapa s ID $id nebyla v databázi nalezena! Zkontroluj tabulku 'stage'.";
        }

        // OPRAVENO: Směrování do tvé nové složky pohledů
        return view('2.DetailStranka/index', $data);
    }
    
}