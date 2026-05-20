<?php

namespace App\Libraries;

class Alert
{
    protected array $allowedTypes = [
        'success',
        'danger',
        'warning',
        'info',
        'primary',
        'secondary',
        'light',
        'dark',
    ];
    /**
     *  @param $type - typ alertu  (successm danger apod.)
     *  @param $key - operace (user Created, záznam přidán do databáze apod.)
     *  @param $params - volitelné parametry, pokud cchi nějak upravit hlášku (např. cchi uživatel s id 25 byl smazán)
     *
     */
    public function set(string $type, string $key, array $params = []): void
    {
        $type = $this->normalizeType($type);

        $langKey = "Alert.{$type}.{$key}";
        $message = lang($langKey, $params);

        session()->setFlashdata('alert', [
            'type'    => $type,
            'message' => $message,
        ]);
    }

    protected function normalizeType(string $type): string
    {
        $type = strtolower(trim($type));
        //když zadám něco jiného, než povolené třídy pro alert, tak vrátím info, jinak tu classu pro alert
        if(in_array($type, $this->allowedTypes, true)){
            $result = $type;
        } else {
            $result = 'info';
        }

        return $result;
       
}
}