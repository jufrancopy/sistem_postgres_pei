<?php

namespace App\ClasesPersonalizadas;

use Codedge\Fpdf\Fpdf\Fpdf;

class Pdf extends FPDF
{
    //MultiCell with bullet
    function MultiCellBlt($w, $h, $blt, $txt, $border = 0, $align = 'J', $fill = false)
    {
        //Get bullet width including margins
        $blt_width = $this->GetStringWidth($blt) + $this->cMargin * 2;

        //Save x
        $bak_x = $this->x;

        //Output bullet
        $this->Cell($blt_width, $h, $blt, 0, '', $fill);

        //Output text
        $this->MultiCell($w - $blt_width, $h, $txt, $border, $align, $fill);

        //Restore x
        $this->x = $bak_x;
    }

    function MultiCellBltArray($w, $h, $blt_array, $border = 0, $align = 'J', $fill = false)
    {
        if (!is_array($blt_array)) {
            die('MultiCellBltArray requires an array with the following keys: bullet,margin,text,indent,spacer');
            exit;
        }

        //Save x
        $bak_x = $this->x;

        for ($i = 0; $i < sizeof($blt_array['text']); $i++) {
            //Get bullet width including margin
            $blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin']) + $this->cMargin * 2;

            // SetX
            $this->SetX($bak_x);

            //Output indent
            if ($blt_array['indent'] > 0)
                $this->Cell($blt_array['indent']);

            //Output bullet
            $this->Cell($blt_width, $h, $blt_array['bullet'] . $blt_array['margin'], 0, '', $fill);

            //Output text
            $this->MultiCell($w - $blt_width, $h, $blt_array['text'][$i], $border, $align, $fill);

            //Insert a spacer between items if not the last item
            if ($i != sizeof($blt_array['text']) - 1)
                $this->Ln($blt_array['spacer']);

            //Increment bullet if it's a number
            if (is_numeric($blt_array['bullet']))
                $blt_array['bullet']++;
        }

        //Restore x
        $this->x = $bak_x;
    }

    // function Header()
    // {

    //     $tituloCabecera = "Cruce de Ambientes - Estrategias";
    //     $w = $this->GetStringWidth($tituloCabecera) + 6;


    //     $this->SetFont('Arial', 'B', 12);
    //     $this->SetX((210 - $w) / 2);
    //     //Color borde
    //     $this->SetDrawColor(0, 80, 180);
    //     //Color relleno
    //     $this->SetFillColor(92, 230, 110);
    //     //Color Texto
    //     $this->SetTextColor(138, 135, 135);

    //     // true si queremos color relleno
    //     $this->Cell($w, 9, iconv('UTF-8', 'CP1252', $tituloCabecera), 0, 1, 'C', false);
    //     $this->Ln(10);
    // }

    // function Header()
    // {
    //     // Logo
    //     // $this->Image('logo.png', 10, 8, 33);
    //     // Arial bold 15
    //     $this->SetFont('Arial', 'B', 15);
    //     // Movernos a la derecha
    //     $this->Cell(80);
    //     // Título
    //     $this->Cell(30, 10, 'Title', 1, 0, 'C');
    //     // Salto de línea
    //     $this->Ln(20);
    // }

    function Header()
    {
        // global $title;

        $title = 'SIPLAN - Herramienta de Análisis FODA';

        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Calculamos ancho y posición del título.
        $w = $this->GetStringWidth($title) + 6;
        $this->SetX((210 - $w) / 2);
        // Colores de los bordes, fondo y texto
        $this->SetDrawColor(0, 80, 180);
        $this->SetFillColor(230, 230, 0);
        $this->SetTextColor(220, 50, 50);
        // Ancho del borde (1 mm)
        $this->SetLineWidth(1);
        // Título
        $this->Cell($w, 9, $title, 1, 1, 'C', true);
        // Salto de línea
        $this->Ln(10);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'CP1252', 'Página ') . $this->PageNo(), 0, 0, 'C');
    }

    // Cargar los datos
    function LoadData($file)
    {
        // Leer las líneas del fichero
        $lines = file($file);
        $data = array();
        foreach ($lines as $line)
            $data[] = explode(';', trim($line));
        return $data;
    }

    // Tabla simple
    function BasicTable($header, $data)
    {
        // Cabecera
        foreach ($header as $col)
            $this->Cell(40, 7, $col, 1);
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col)
                $this->Cell(40, 6, $col, 1);
            $this->Ln();
        }
    }

    // Una tabla más completa
    function ImprovedTable($header, $data)
    {
        // Anchuras de las columnas
        $w = array(40, 35, 45, 40);
        // Cabeceras
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR');
            $this->Cell($w[1], 6, $row[1], 'LR');
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R');
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R');
            $this->Ln();
        }
        // Línea de cierre
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    // Tabla coloreada
    function FancyTable($header, $data)
    {
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Cabecera
        $w = array(40, 35, 45, 40);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
