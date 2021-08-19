<?php namespace App\ClasesPersonalizadas;

use Codedge\Fpdf\Fpdf\Fpdf;

class Pdf extends FPDF
{
    //MultiCell with bullet
    function MultiCellBlt($w, $h, $blt, $txt, $border=0, $align='J', $fill=false)
    {
        //Get bullet width including margins
        $blt_width = $this->GetStringWidth($blt)+$this->cMargin*2;

        //Save x
        $bak_x = $this->x;

        //Output bullet
        $this->Cell($blt_width,$h,$blt,0,'',$fill);

        //Output text
        $this->MultiCell($w-$blt_width,$h,$txt,$border,$align,$fill);

        //Restore x
        $this->x = $bak_x;
    }

    function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=false)
    {
        if (!is_array($blt_array))
        {
            die('MultiCellBltArray requires an array with the following keys: bullet,margin,text,indent,spacer');
            exit;
        }
                
        //Save x
        $bak_x = $this->x;
        
        for ($i=0; $i<sizeof($blt_array['text']); $i++)
        {
            //Get bullet width including margin
            $blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$this->cMargin*2;
            
            // SetX
            $this->SetX($bak_x);
            
            //Output indent
            if ($blt_array['indent'] > 0)
                $this->Cell($blt_array['indent']);
            
            //Output bullet
            $this->Cell($blt_width,$h,$blt_array['bullet'] . $blt_array['margin'],0,'',$fill);
            
            //Output text
            $this->MultiCell($w-$blt_width,$h,$blt_array['text'][$i],$border,$align,$fill);
            
            //Insert a spacer between items if not the last item
            if ($i != sizeof($blt_array['text'])-1)
                $this->Ln($blt_array['spacer']);
            
            //Increment bullet if it's a number
            if (is_numeric($blt_array['bullet']))
                $blt_array['bullet']++;
        }
    
        //Restore x
        $this->x = $bak_x;
    }

    function Header(){
       
        $tituloCabecera = "Cruce de Ambientes - Estrategias";
        $w = $this->GetStringWidth($tituloCabecera)+6;
        
        $this->SetFont('Arial','B',12);
        $this->SetX((210-$w)/2);
        //Color borde
        $this->SetDrawColor(0,80,180);
        //Color relleno
        $this->SetFillColor(92, 230, 110);
        //Color Texto
        $this->SetTextColor(138,135,135);
        // Ancho del borde (1 mm)
        //$pdf->SetLineWidth(0.1);
        // true si queremos color relleno
        $this->Cell($w,9,utf8_decode($tituloCabecera),0,1,'C',false);
        $this->Ln(10);
        
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
    }
    
}