<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StageModel;
use App\Models\ResultModel;

class StageCont extends BaseController
{
    private $StageModel;
    private $ResultModel; 

    // Konstruktor, který se spustí při vytvoření instance třídy StageCont
    public function __construct() 
    {
        $this->StageModel = new StageModel();
        $this->ResultModel = new ResultModel(); 
    }

    public function index()
    {
        // Získá všechny aktivní etapy pro závod s ID 646 (CodeIgniter automaticky vynechá soft-smazané)
        $etapy = $this->StageModel->where('id_race_year', 646)->orderBy('number', 'ASC')->findAll(); 
        
        foreach ($etapy as $etapa) { 
           
            $vitez = $this->ResultModel->asObject()
                        ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") // Spojí jméno a příjmení
                        ->join('km_rider', 'km_rider.id = km_result.id_rider') // Propojení s tabulkou jezdců
                        ->where('km_result.id_stage', $etapa->id) // Hledá výsledky pro konkrétní etapu
                        ->where('km_result.rank', 1) // Hledá pouze vítěze (1. místo)
                        ->first(); // Nahrazuje get()->getRow() – vrátí první nalezený řádek jako objekt
            
            $etapa->vitez_jmeno = $vitez ? $vitez->cele_jmeno : '—'; // Pokud není vítěz, zobrazí se pomlčka
        }

        return view('1.EtapyStranka/index', [ // Zobrazí info na úvodní stránce 
            'nazev' => 'La Tropicale Amissa Bongo 2023', // Název závodu
            'etapy' => $etapy // Vypíše všechny etapy s informací o vítězi
        ]);
    }
}