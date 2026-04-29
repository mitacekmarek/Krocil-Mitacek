<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\EtapaI;

class Etapy extends BaseController
{
    private $EtapaI;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->EtapaI = new EtapaI();
    }

    public function index()
    {
        $data = [
            "etapy" = $model->getEtapy();
            'title' -> 
        ];
        return view('1stranka/index', $data);
    }
}
