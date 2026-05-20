<?php

namespace App\Libraries;

use \HTMLPurifier;

class StringLib
{
    private HTMLPurifier $purifier;

    public function __construct() {
        $this->purifier = new HTMLPurifier();
    }

    public function cleanHtml(string $string) {
        $newString = $this->purifier->purify($string);

        return $newString;
    }

    public function cleanArray(array $array, string $item){
        $result = [];

        foreach($array as $row){
            $row->$item = $this->cleanHtml($row->$item);
            $result[] = $row;
        }

        return $result;
    }

    public function deleted(object $row, string $column = 'deleted_at'){
        if(is_null($row->deleted_at)){
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }
}
