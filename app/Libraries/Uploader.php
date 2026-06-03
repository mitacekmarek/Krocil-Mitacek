<?php

namespace App\Libraries;

class Uploader
{
    public function uploadFile($file, $path, $name)
    {
        $extension = $file->getClientExtension();
        $fullName = $name . "." . $extension;
        
        // Přesunutí souboru
        $result = $file->move($path, $fullName);
        
        // Návratové pole podle návodu
        $return['uploaded'] = $result;
        $return['name'] = $fullName;
        
        return $return;
    }
}