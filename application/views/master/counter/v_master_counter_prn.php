<?php
$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($unit);
$pdf->setjudul('DAFTAR CABANG');
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->SetFont('Times','B',8);   
$pdf->SetWidths(array(10,20,50,40,50,20));
$pdf->SetAligns(array('C','C','C','C','C','C'));
$judul=array('NO','KODE','NAMA','PIMPINAN','ALAMAT','TELEPON');
$pdf->Row($judul);
$pdf->SetWidths(array(10,20,50,40,50,20));
$pdf->SetAligns(array('C','C','L','L','L','L'));
$pdf->setfont('Times','',8);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
	
$nourut = 1;

foreach($master as $db)
{
  $pdf->row(array($nourut, 
  $db->kode, 
  $db->nama,
  $db->pimpinan,
  $db->alamat,
  $db->telpon
  ));
  $nourut++;
}


$pdf->AliasNbPages();
$pdf->output('master_cabang.pdf','I');
?>