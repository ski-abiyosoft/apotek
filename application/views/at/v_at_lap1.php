<?php
$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($namaunit);
$pdf->setjudul('JENIS AKTIVA TETAP');
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->SetFont('Times','B',10);     
$pdf->SetWidths(array(10,15,70,30,30,30));
$pdf->SetAligns(array('C','C','C','C','C','C'));
$judul=array('NO','KODE','NAMA','AKUN AKTIVA','AKUN PENYUSUTAN','AKUN BIAYA PENYUSUTAN');
$pdf->row($judul);
$pdf->SetWidths(array(10,15,70,30,30,30));
$pdf->SetAligns(array('C','C','L','C','C','C'));
$pdf->SetFont('Times','',10);     
	
$nourut = 1;
foreach($jenisat as $row)
{
  $pdf->row(array($nourut, $row->kode, $row->nama,$row->akun_aktiva,$row->akun_penyusutan,$row->akun_biaya));
  $nourut++;
}

$pdf->AliasNbPages();
$pdf->output('jenisat','I');

?>
