<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\StageModel;  // OPRAVENO: Nový název modelu
use App\Models\ResultModel;

class StageCont extends BaseController
{
    private $StageModel;    // OPRAVENO: Výstižnější název vlastnosti
    private $ResultModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->StageModel = new StageModel();   // OPRAVENO
        $this->ResultModel = new ResultModel();
    }

    public function index()
    {
        $data = [
            'nazev' => 'La Tropicale Amissa Bongo 2023',
            'etapy' => $this->StageModel->where('id_race_year', 646)
                                        ->orderBy('number', 'ASC')
                                        ->findAll()
        ];

        // OPRAVENO: Cesta k nové složce pohledů podle tvého screenshotu
        return view('1.EtapyStranka/index', $data);
    }
}