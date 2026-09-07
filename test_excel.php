<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFile = 'backups/RIISS/RIISS_especialidades/PRODUCTOS  por establecimientos y especialidad.xlsx';

try {
    $spreadsheet = IOFactory::load($inputFile);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $rows = [];
    foreach ($worksheet->getRowIterator(1, 5) as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $rowData = [];
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        $rows[] = $rowData;
    }
    
    print_r($rows);
} catch (Exception $e) {
    echo 'Error loading file: ',  $e->getMessage(), "\n";
}
