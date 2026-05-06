<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\EtapaI;
use App\Models\ResultModel;

class Etapy extends BaseController
{
    private $EtapaI;
    private $ResultModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->EtapaI = new EtapaI();
        $this->ResultModel = new ResultModel();
    }

    public function index()
    {
        $data = [
            'nazev' => 'La Tropicale Amissa Bongo 2023',
            'etapy' => $this->EtapaI->where('id_race_year', 646)
                ->orderBy('number', 'ASC')
                ->findAll()
        ];

        return view('1stranka/index', $data);
    }

    public function detail($id)
    {
        $etapaModel = new \App\Models\EtapaI();
        $resultModel = new \App\Models\ResultModel();
    
        // Načtení dat - použijeme find(), je to nejrychlejší
        $data['etapa']   = $etapaModel->find($id);
        $data['ranking'] = $resultModel->where('id_stage', $id)->orderBy('rank', 'ASC')->findAll();
        $data['nazev']   = "Detail etapy";
    
        // Pokud je etapa prázdná, vypíšeme chybu přímo na obrazovku pro ladění
        if (!$data['etapa']) {
            return "Chyba: Etapa s ID $id nebyla v databázi nalezena! Zkontroluj tabulku 'stage'.";
        }
    
        return view('2stranka/index', $data);
    }
}

