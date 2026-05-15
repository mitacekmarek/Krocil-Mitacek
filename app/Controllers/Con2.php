<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use App\Models\ResultModel;

class Con2 extends BaseController
{
    public function detail($id)
    {
        $etapaModel = new \App\Models\EtapaI();
        $resultModel = new \App\Models\ResultModel();
    
        $data['etapa'] = $etapaModel->select('km_stage.*, km_parcour_type.name AS parcour_name')
                                    ->join('km_parcour_type', 'km_parcour_type.id = km_stage.parcour_type', 'left')
                                    ->where('km_stage.id', $id)
                                    ->first();
                                    
        $data['nazev']   = "Detail etapy";
    
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
        return view('2stranka/index', $data);
    }
}
