<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use App\Models\StageModel;  
use App\Models\ResultModel;

class StageDetailCont extends BaseController
{
    public function index($id)
    {
        $etapaModel = new StageModel();
        $resultModel = new ResultModel();
    
        // Zachována tvá kompletní logika pro načtení etapy včetně typu profilu
        $data['etapa'] = $etapaModel->select('km_stage.*, km_parcour_type.name AS parcour_name')
                                    ->join('km_parcour_type', 'km_parcour_type.id = km_stage.parcour_type', 'left')
                                    ->where('km_stage.id', $id)
                                    ->first();
                                    
        $data['nazev'] = "Detail etapy"; // zobrazuje název Detail etapy

        // výpis všech výsledků pro danou etapu, včetně jména, příjmení a země jezdce, seřazených podle umístění
        $data['ranking'] = $resultModel->select('km_result.*, km_rider.first_name, km_rider.last_name, km_rider.country') // výběr všech sloupců z km_result a jména, příjmení a země z km_rider
                                       ->join('km_rider', 'km_rider.id = km_result.id_rider') // spojení s tabulkou jezdců pro získání jména, příjmení a země
                                       ->where('km_result.id_stage', $id) // pouze výsledky pro aktuální etapu
                                       ->where('km_result.type_result', 1) // pouze výsledky pro etapu
                                       ->where('km_result.rank >', 0) // pouze jezdci s umístěním větším než 0
                                       ->orderBy('km_result.rank', 'ASC') // řazení podle umístění vzestupně
                                       ->findAll(); //vypíše všechny výsledky, které odpovídají zadaným podmínkám
    
        if (!$data['etapa']) { //Kontrola, zda byla etapa nalezena v databázi
            return "Chyba: Etapa s ID $id nebyla v databázi nalezena! Zkontroluj tabulku 'stage'."; // pokud etapa není nalezena, vypíše chybu
        }

       
        return view('2.DetailStranka/index', $data); //vypíše info na stránku index DetailStranka
    }
    
}