<?php

namespace App\Models; // Definuje jmenný prostor pro modely aplikace

use CodeIgniter\Model; // Importuje základní třídu Modelu z frameworku CodeIgniter

class ResultModel extends Model // Hlavní třída modelu pro práci s výsledky závodníků
{
    protected $table            = 'km_result'; // Nastavuje správný název tabulky v DB (podle tvého kontroleru)
    protected $primaryKey       = 'id'; // Určuje název sloupce, který slouží jako primární klíč
    protected $useAutoIncrement = true; // Říká, že databáze bude ID generovat automaticky od jedničky nahoru
    protected $returnType       = 'object'; // Nastavuje, že výsledky se z databáze budou vracet jako objekty
    protected $useSoftDeletes   = true; 
    protected $protectFields    = true; // Zapíná ochranu polí před neoprávněným hromadným vložením dat
    protected $allowedFields    = ['id_stage', 'id_rider', 'rank']; // Seznam sloupců, do kterých je povoleno zapisovat data

    protected bool $allowEmptyInserts = false; // Zakazuje vkládání úplně prázdných řádků do databáze
    protected bool $updateOnlyChanged = true; // Při úpravě pošle do DB pouze ty hodnoty, které se reálně změnily

    protected array $casts = []; // Slouží pro automatické přetypování sloupců při načtení z DB
    protected array $castHandlers = []; // Definuje vlastní pokročilá pravidla pro přetypování dat

    // Dates
    protected $useTimestamps = true; 
    protected $dateFormat    = 'datetime'; // Nastavuje formát ukládání datumu a času v databázi
    protected $createdField  = 'created_at'; // Určuje název sloupce pro automatický čas vytvoření záznamu
    protected $updatedField  = 'updated_at'; // Určuje název sloupce pro automatický čas poslední úpravy
    protected $deletedField  = 'deleted_at'; // Určuje název sloupce pro čas smazání u soft-delete

    // Validation
    protected $validationRules      = []; // Pole pro definici validačních pravidel (např. povinná pole)
    protected $validationMessages   = []; // Pole pro vlastní chybové hlášky, pokud validace dat selže
    protected $skipValidation       = false; // Říká, zda se má validace dat před zápisem přeskočit
    protected $cleanValidationRules = true; // Zajišťuje pročištění validačních pravidel po každém ověření

    // Callbacks
    protected $allowCallbacks = true; // Povoluje automatické spouštění vlastního kódu (hooků) během akcí
    protected $beforeInsert   = []; // Funkce, které se spustí těsně před vložením řádku
    protected $afterInsert    = []; // Funkce, které se spustí ihned po vložení řádku
    protected $beforeUpdate   = []; // Funkce, které se spustí těsně před úpravou řádku
    protected $afterUpdate    = []; // Funkce, které se spustí ihned po úpravě řádku
    protected $beforeFind     = []; // Funkce, které se spustí těsně před vyhledáváním dat
    protected $afterFind      = []; // Funkce, které se spustí ihned po vyhledání dat
    protected $beforeDelete   = []; // Funkce, které se spustí těsně před smazáním řádku
    protected $afterDelete    = []; // Funkce, které se spustí ihned po smazání řádku
}