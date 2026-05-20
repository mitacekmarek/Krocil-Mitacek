<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ConForm extends BaseController
{
    // Otevře formulář (prázdný pro přidání, nebo vyplněný pro úpravu)
    public function edit($id = null)
    {
        helper(['form']);
        $model = new \App\Models\EtapaI();
        
        // Najdeme konkrétní etapu, pokud bylo v URL zadáno ID
        $data['etapa'] = ($id) ? $model->find($id) : null;
        $data['nazev'] = ($id) ? "Úprava etapy č. " . $data['etapa']->number : "Vytvoření nové etapy";
        
        // Pro ten vnitřní input "Číslo etapy"
        $data['cisla_etap'] = range(1, 21);

        return view('3stranka/index', $data);
    }

    // Uloží novou nebo upravenou etapu
    public function save()
    {
        $model = new \App\Models\EtapaI();
        $id = $this->request->getPost('id');

        $dbData = [
            'number'    => $this->request->getPost('number'),
            'departure' => $this->request->getPost('departure'),
            'arrival'   => $this->request->getPost('arrival'),
            'date'      => $this->request->getPost('date'),
            'distance'  => $this->request->getPost('distance'),
            'profile'   => $this->request->getPost('profile'),
            'note'      => $this->request->getPost('note'),
        ];

        if ($id) {
            $model->update($id, $dbData); // Zde se upravuje
        } else {
            $model->insert($dbData); // Zde se vytváří nová
        }

        return redirect()->to(base_url('/'))->with('success', 'Etapa byla úspěšně uložena.');
    }

    // Provede soft delete rovnou z tabulky
    public function delete($id)
    {
        if ($id) {
            $model = new \App\Models\EtapaI();
            $model->delete($id); 
        }
        // Po smazání tě to vrátí zpět na hlavní stránku (tabulku)
        return redirect()->to(base_url('/'))->with('success', 'Etapa byla smazána.');
    }
}