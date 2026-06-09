<?php

namespace App\Controllers; // Definuje jmenný prostor pro tento kontroler

use App\Controllers\BaseController; // Importuje základní kontroler z CodeIgniteru
use App\Libraries\Uploader; // Importuje knihovnu pro nahrávání souborů
use App\Models\StageModel; // Importuje model pro práci s etapami v databázi
use App\Libraries\Alert; // Importuje knihovnu pro zobrazování hlášek

class FormCont extends BaseController // Hlavní třída kontroleru pro správu etapových formulářů
{
    private function getCislaEtap() { // Funkce pro vygenerování rozsahu čísel etap
        return range(1, 25); // Vytvoří a vrátí pole s čísly od 1 do 25 pro výběr ve formuláři
    }

    private function getOrCreateRider($celeJmeno) // Funkce najde existujícího jezdce nebo vytvoří nového
    {
        $celeJmeno = trim($celeJmeno); // Odstraní prázdné mezery na začátku a konci jména
        if (empty($celeJmeno)) return null; // Pokud je jméno úplně prázdné, vrátí hodnotu null

        $db = \Config\Database::connect(); // Otevře aktivní připojení k databázi
        
        $jezdec = $db->query(" 
            SELECT id FROM km_rider 
            WHERE LOWER(TRIM(CONCAT(first_name, ' ', last_name))) = LOWER(TRIM(?)) 
        ", [$celeJmeno])->getRow(); // Spustí SQL dotaz, který hledá ID jezdce podle jména bez ohledu na velikost písmen

        if ($jezdec) { // Pokud byl jezdec v databázi úspěšně nalezen
            return $jezdec->id; // Vrátí z databáze jeho stávající ID
        }

        $casti = explode(' ', $celeJmeno, 2); // Rozdělí jméno podle mezery na dvě části (křestní a zbytek)
        $db->table('km_rider')->insert([ // Začne vkládat nový řádek do tabulky jezdců
            'first_name' => $casti[0], // Uloží první část textu jako křestní jméno
            'last_name'  => $casti[1] ?? '' // Uloží zbytek textu jako příjmení, pokud neexistuje, dá prázdný řetězec
        ]);

        return $db->insertID(); // Vrátí ID, které databáze novému jezdci automaticky přidělila
    }

    private function saveStageWinner($idStage, $idRider) // Funkce pro uložení vítěze konkrétní etapy
    {
        $db = \Config\Database::connect(); // Otevře aktivní připojení k databázi
        $resultTable = $db->table('km_result'); // Připraví si práci s tabulkou výsledků km_result

        $resultTable->where(['id_stage' => $idStage, 'rank' => 1])->delete(); // Smaže starého vítěze této etapy, aby tam nebyli dva

        if ($idRider) { // Pokud máme k dispozici platné ID jezdce
            $resultTable->insert([ // Vloží nový řádek do tabulky výsledků
                'id_stage' => $idStage, // Propojí výsledek s ID dané etapy
                'id_rider' => $idRider, // Propojí výsledek s ID daného jezdce
                'rank'     => 1 // Nastaví mu umístění na 1. místo (vítěz)
            ]);
        }
    }

    // ==========================================
    // PŘIDÁVÁNÍ NOVÉ ETAPY
    // ==========================================
    public function add() // Funkce pro zobrazení formuláře pro založení nové etapy
    {
        helper(['form']); // Načte systémové pomocníky CodeIgniteru pro práci s formuláři
        return view('3.AdminStranka/add', [ // Vyrenderuje a vrátí pohled s formulářem
            'nazev' => "Vytvoření nové etapy", // Předá do pohledu nadpis stránky
            'mozna_cisla' => $this->getCislaEtap() // Předá do pohledu pole čísel 1-25 pro výběr etapy
        ]);
    }

    public function create() // Funkce pro uložení dat z nově odeslaného formuláře
    {
        $model = new StageModel(); // Vytvoří instanci modelu pro ukládání etap
        $uploader = new Uploader(); // Vytvoří instanci knihovny pro nahrávání souborů
        $alert = new Alert(); // Vytvoří instanci knihovny pro zobrazení vyskakovacích hlášek
        
        $dbData = [ // Připraví pole s daty, která se uloží do tabulky etap
            'id_race_year'    => 646, // Nastaví pevné ID ročníku závodu
            'number'          => $this->request->getPost('number'), // Načte číslo etapy z odeslaného formuláře
            'departure'       => $this->request->getPost('departure'), // Načte místo startu z formuláře
            'arrival'         => $this->request->getPost('arrival'), // Načte místo cíle z formuláře
            'date'            => $this->request->getPost('date') ?: null, // Načte datum, pokud chybí, vloží do databáze null
            'distance'        => (float)$this->request->getPost('distance'), // Načte vzdálenost a převede ji na číslo (v případě prázdna dá 0)
            'vertical_meters' => (int)$this->request->getPost('vertical_meters'), // Načte převýšení a převede na celé číslo (v případě prázdna dá 0)
            'note'            => $this->request->getPost('note') ?? '', // Načte poznámku, pokud chybí, pojistí ji prázdným textem
            'description'     => $this->request->getPost('description'), // Načte podrobný popis etapy
        ];

        $file = $this->request->getFile('profile_image'); // Načte nahraný soubor s profilovým obrázkem etapy
        if ($file?->isValid() && !$file->hasMoved()) { // Zkontroluje, zda soubor v pořádku dorazil a ještě nebyl přesunut
            $path = FCPATH . 'obrazky/stages/profiles/'; // Definuje cílovou složku na serveru pro uložení obrázku
            $name = 'profile-' . $dbData['number']; // Vytvoří název souboru podle čísla etapy
            $uploadResult = $uploader->uploadFile($file, $path, $name); // Spustí samotné nahrání a uložení souboru na disk
            
            if ($uploadResult['uploaded']) { // Pokud nahrávání obrázku na server proběhlo úspěšně
                $dbData['profile'] = $uploadResult['name']; // Přidá název obrázku do pole dat pro uložení do DB
            }
        }

        $idStage = $model->insert($dbData); // Vloží připravená data do databáze a uloží si ID nové etapy

        $vitezJmeno = trim((string)$this->request->getPost('vitez_jmeno')); // Načte jméno vítěze z formuláře a ořízne prázdné znaky
        $idRider = $this->getOrCreateRider($vitezJmeno); // Získá ID jezdce (najde ho, nebo založí nového)
        $this->saveStageWinner($idStage, $idRider); // Zavolá funkci pro zápis tohoto jezdce jako vítěze etapy

        $alertType = $idStage ? 'success' : 'danger'; // Určí typ hlášky (úspěch/chyba) podle toho, zda se etapa uložila
        $alert->set($alertType, 'recordCreated', ['id' => $idStage ?: 0]); // Uloží hlášku do systému s ID nové etapy

        return redirect()->to(base_url('/')); // Přesměruje uživatele zpět na úvodní stránku webu
    }

    // ==========================================
    // ÚPRAVA ETAPY
    // ==========================================
    public function edit($id) // Funkce pro načtení a zobrazení formuláře pro úpravu stávající etapy
    {
        helper(['form']); // Načte systémové pomocníky pro práci s formuláři
        $model = new StageModel(); // Vytvoří instanci modelu etap
        $etapa = $model->find($id); // Vyhledá v databázi etapu podle jejího ID
        
        if (!$etapa) { // Pokud etapa s tímto ID v databázi neexistuje
            (new Alert())->set('danger', 'saveFailed'); // Vytvoří červenou chybovou hlášku
            return redirect()->to(base_url('/')); // Přesměruje uživatele na hlavní stranu
        }

        $etapa->vitez_jmeno = ''; // Připraví si prázdnou vlastnost pro jméno vítěze

        $db = \Config\Database::connect(); // Připojí se k databázi
        $vitez = $db->table('km_result') // Začne pracovat s tabulkou výsledků
                    ->select("CONCAT(km_rider.first_name, ' ', km_rider.last_name) AS cele_jmeno") // Spojí křestní jméno a příjmení jezdce do jednoho textu
                    ->join('km_rider', 'km_rider.id = km_result.id_rider') // Propojí tabulku výsledků s tabulkou jezdců přes ID
                    ->where(['km_result.id_stage' => $id, 'km_result.rank' => 1]) // Vyfiltruje pouze vítěze (rank 1) pro tuto konkrétní etapu
                    ->get() // Spustí a provede tento databázový dotaz
                    ->getRow(); // Získá první nalezený řádek výsledku

        if ($vitez) { // Pokud byl vítěz pro tuto etapu v databázi nalezen
            $etapa->vitez_jmeno = $vitez->cele_jmeno; // Přiřadí spojené jméno do objektu etapy pro formulář
        }

        return view('3.AdminStranka/edit', [ // Vyrenderuje a vrátí pohled pro úpravu etapy
            'etapa' => $etapa, // Pošle do formuláře načtená data etapy včetně jména vítěze
            'nazev' => "Úprava etapy č. " . $etapa->number, // Pošle do pohledu dynamický nadpis stránky
            'mozna_cisla' => $this->getCislaEtap() // Pošle do pohledu pole čísel 1-25 pro výběr čísla etapy
        ]);
    }

    public function update($id) // Funkce pro uložení upravených dat z editačního formuláře
    {
        $model = new StageModel(); // Vytvoří instanci modelu etap
        $uploader = new Uploader(); // Vytvoří instanci knihovny pro nahrávání souborů
        $alert = new Alert(); // Vytvoří instanci knihovny pro zobrazení hlášek

        $dbData = [ // Připraví pole s aktualizovanými daty pro přepsání v DB
            'number'          => $this->request->getPost('number'), // Načte upravené číslo etapy z formuláře
            'departure'       => $this->request->getPost('departure'), // Načte upravené místo startu
            'arrival'         => $this->request->getPost('arrival'), // Načte upravené místo cíle
            'date'            => $this->request->getPost('date') ?: null, // Načte upravené datum, při prázdnu dá null
            'distance'        => (float)$this->request->getPost('distance'), // Načte vzdálenost a převede na desetinné číslo (při prázdnu dá 0)
            'vertical_meters' => (int)$this->request->getPost('vertical_meters'), // Načte převýšení a převede na celé číslo (při prázdnu dá 0)
            'note'            => $this->request->getPost('note') ?? '', // Načte poznámku, při absenci ji nahradí prázdným textem
            'description'     => $this->request->getPost('description'), // Načte upravený podrobný popis etapy
        ];

        $file = $this->request->getFile('profile_image'); // Načte případný nově nahraný profilový obrázek
        if ($file?->isValid() && !$file->hasMoved()) { // Pokud byl nahrán nový platný soubor a ještě se nepřesouval
            $path = FCPATH . 'obrazky/stages/profiles/'; // Nastaví cestu do složky s obrázky na serveru
            $name = 'profile-' . $dbData['number']; // Vygeneruje název obrázku podle čísla etapy
            $uploadResult = $uploader->uploadFile($file, $path, $name); // Nahraje a přepíše soubor na serveru
            
            if ($uploadResult['uploaded']) { // Pokud nahrání nového obrázku proběhlo úspěšně
                $dbData['profile'] = $uploadResult['name']; // Přidá název nového souboru do dat pro uložení do DB
            }
        }

        $updated = $model->update($id, $dbData); // Aktualizuje řádek v databázi podle ID etapy odeslanými daty

        $vitezJmeno = trim((string)$this->request->getPost('vitez_jmeno')); // Načte upravené jméno vítěze z formuláře
        $idRider = $this->getOrCreateRider($vitezJmeno); // Získá ID jezdce podle jména (najde ho, nebo založí)
        $this->saveStageWinner($id, $idRider); // Aktualizuje vítěze této etapy ve výsledkové tabulce
        
        $alertType = $updated ? 'success' : 'danger'; // Určí zelenou hlášku při úspěchu, červenou při selhání aktualizace
        $alert->set($alertType, 'recordUpdated', ['id' => $id]); // Uloží aktualizační hlášku s ID etapy do systému

        return redirect()->to(base_url('/')); // Přesměruje uživatele zpět na úvodní stránku webu
    }

    // ==========================================
    // SMAZÁNÍ ETAPY
    // ==========================================
    public function delete($id) // Funkce pro kompletní smazání etapy z databáze
    {
        $model = new StageModel(); // Vytvoří instanci modelu etap
        $alert = new Alert(); // Vytvoří instanci knihovny pro zobrazení hlášek

        if ($model->find($id)) { // Podívá se do databáze, zda etapa s tímto ID vůbec existuje
            $db = \Config\Database::connect(); // Otevře aktivní připojení k databázi
            $db->table('km_result')->where('id_stage', $id)->delete(); // Smaže všechny výsledky navázané na tuto etapu, ať nevznikne nepořádek
            $model->delete($id); // Vymaže samotný záznam etapy z hlavní tabulky etap

            $alert->set('success', 'recordDeleted', ['id' => $id]); // Nastaví zelenou hlášku o úspěšném smazání záznamu
        } else { // Pokud se etapu podle ID nepodařilo v databázi vůbec najít
            $alert->set('danger', 'recordDeleted', ['id' => $id]); // Nastaví červenou chybovou hlášku, že smazání selhalo
        }

        return redirect()->to(base_url('/')); // Přesměruje uživatele zpět na hlavní stranu
    }
}