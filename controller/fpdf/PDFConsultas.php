<?php
require('fpdf.php');
class PDFConsultas extends FPDF{

  function Header(){
    //logo
    $this->Image('templates/img/logo.png',10,8,75);
    $this->SetY(20);
    // Títulos de las columnas
    $header = array('Documento', 'Historia Clinica', 'Fecha', 'Motivo', 'Internacion');
    //Margenes
    $this->setLeftMargin(5);
    //Titulo
    $this->Cell(0,7,'Consultas - Hospital Dr.Alejandro Korn',0,0,'C',false);
    $this->Ln();
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(90,90,90);
    $this->SetTextColor(255);
    $this->SetDrawColor(50,50,50);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Cabecera
    $w = array(40, 40, 30, 60, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
  }

  // Pie de página
  function Footer(){
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
  }

  // Tabla coloreada
  function FancyTable($data){
    //Ancho de las columnas
    $w = array(40, 40, 30, 60, 30);
    // Colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    $estado = array('No','Si');
    foreach($data as $row){
      $fecha = new DateTime($row["fecha"]);
      $fecha = $fecha->format('d-m-Y');

      $this->Cell($w[0],6,($row["tipo_documento"] . " " . $row["documento"]),'LR',0,'C',$fill);
      $this->Cell($w[1],6,$row["historia_clinica"],'LR',0,'C',$fill);
      $this->Cell($w[2],6,$fecha,'LR',0,'C',$fill);
      $this->Cell($w[3],6,$row["motivo"],'LR',0,'C',$fill);
      $this->Cell($w[4],6,$estado[$row["internacion"]],'LR',0,'C',$fill);
      $this->Ln();
      $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
  }
}
