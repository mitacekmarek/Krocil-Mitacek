<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StageModel;

class StageCont extends BaseController
{
    private $StageModel;

    public function __construct() // konstruktor, který se spustí při vytvoření instance třídy StageCont
    {
        $this->StageModel = new StageModel();
    }

    public function index()
    {
        $etapy = $this->StageModel->where('id_race_year', 646)->orderBy('number', 'ASC')->findAll(); // získá všechny etapy pro závod s ID 646 a seřadí je podle čísla etapy vzestupně
        $db = \Config\Database::connect();
        
        foreach ($etapy as $etapa) { 
            $vitez = $db->table('km_result') // pracuje s tabulkou km_result, která obsahuje výsledky etap
                        ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") // spojí jméno a příjmení jezdce do jednoho pole "cele_jmeno"
                        ->join('km_rider', 'km_rider.id = km_result.id_rider') // joine tabulky km_result s km_rider, aby získal jméno vítěze pro danou etapu
                        ->where('km_result.id_stage', $etapa->id) // hledá výsledky pro danou etapu podle ID
                        ->where('km_result.rank', 1) // hledá vítěze (rank = 1) pro danou etapu
                        ->get() // provede dotaz, který spojí tabulku km_result s km_rider, aby získal jméno vítěze pro danou etapu
                        ->getRow(); // získá jméno vítěze pro každou etapu, pokud existuje
            
            $etapa->vitez_jmeno = $vitez ? $vitez->cele_jmeno : '—'; // pokud není vítěz, zobrazí se pomlčka (? = nebo, : = jinak)
        }

        return view('1.EtapyStranka/index', [ // zobrazí info na úvodní stránce 
            'nazev' => 'La Tropicale Amissa Bongo 2023', // název závodu
            'etapy' => $etapy // vypíše všechny etapy s informací o vítězi
        ]);
    }
}