<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
    protected $table            = 'km_stage'; //kdykoli je v controlleru zavolán tento model, dívej se do tabulky 'km_stage'
    protected $primaryKey       = 'id'; //získává id z tabulky 'km_stage'
    protected $useAutoIncrement = true; //přidělování id automaticky při novém záznamu
    protected $returnType       = 'object'; //vrací objekt ne pole
    protected $useSoftDeletes   = false; //řádky se z databáze odstraní úplně
    protected $protectFields    = true; //ochrana proti neoprávněnému zápisu do databáze
    
    protected $allowedFields    = [
        'number', //číslo etapy
        'departure', //start
        'arrival', //cíl
        'date', //datum
        'distance', //vzdálenost
        'vertical_meters', //převýšení
        'profile', //profil
        'note', //poznámka
        'link', //odkaz
        'id_race_year', //id ročníku závodu
        'parcour_type', //typ profilu (plochá, kopcovitá, horská)
        'description' //povolení zápisu popisu etapy
    ];

    protected bool $allowEmptyInserts = false; //neumožní vložit prázdný záznam 
    protected bool $updateOnlyChanged = true; //při editu se změní jen změněné pole, ne všechny

    protected $useTimestamps = false; //nebudou se automaticky ukládat časové značky (created_at, updated_at, deleted_at)
}