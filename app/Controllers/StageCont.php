<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StageModel;

class StageCont extends BaseController
{
    private $StageModel;

    public function __construct()
    {
        $this->StageModel = new StageModel();
    }

    public function index()
    {
        $etapy = $this->StageModel->where('id_race_year', 646)->orderBy('number', 'ASC')->findAll();
        $db = \Config\Database::connect();
        
        foreach ($etapy as $etapa) {
            $vitez = $db->table('km_result')
                        ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") 
                        ->join('km_rider', 'km_rider.id = km_result.id_rider')
                        ->where('km_result.id_stage', $etapa->id)
                        ->where('km_result.rank', 1)
                        ->get()
                        ->getRow();
            
            $etapa->vitez_jmeno = $vitez ? $vitez->cele_jmeno : '—';
        }

        return view('1.EtapyStranka/index', [
            'nazev' => 'La Tropicale Amissa Bongo 2023',
            'etapy' => $etapy
        ]);
    }
}