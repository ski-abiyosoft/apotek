<?php

$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setjudul('DAFTAR KAS/BANK');
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->SetFont('Times','B',10);   
$pdf->SetWidths(array(10,15,70,20,30,40));
$pdf->SetAligns(array('C','C','C','C','C'));
$judul=array('NO','KODE','NAMA','JENIS','KODE AKUN','NO. REKENING');
$pdf->Row($judul);
$pdf->SetWidths(array(10,15,70,20,30,40));
$pdf->SetAligns(array('C','C','L','C','C'));
$pdf->setfont('Times','',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
	
$nourut = 1;

foreach($master_bank->result_array() as $db)
{
  $pdf->row(array($nourut, $db['bank_kode'], $db['bank_nama'], $db['bank_jenis'], $db['bank_kodeakun'], $db['bank_norek']));
  $nourut++;
}


$pdf->AliasNbPages();
$pdf->output('master_bank.pdf','I');

?>
