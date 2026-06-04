<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
    protected $table            = 'km_stage'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'number', 
        'departure', 
        'arrival', 
        'date', 
        'distance', 
        'vertical_meters', 
        'profile', 
        'note',
        'link',
        'id_race_year',
        'parcour_type'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
}