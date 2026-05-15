<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\EtapaI;
use App\Models\ResultModel;

class Con1 extends BaseController
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

    
}