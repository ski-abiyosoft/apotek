<?php
$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($unit);
$pdf->setjudul('DAFTAR KODE AKUN');
$pdf->setsubjudul('');
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->SetWidths(array(10,25,100,55));
$pdf->SetAligns(array('C','C','C','C'));
$judul=array('NO','KODE PERKIRAAN','NAMA','TIPE AKUN');
$pdf->setfont('Times','B',10);
$pdf->row($judul);
$pdf->SetWidths(array(10,25,100,55));
$pdf->SetAligns(array('C','L','L','L'));
$pdf->setfont('Times','',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
	
$nourut = 1;

foreach($master_akun->result_array() as $db)
{
  $pdf->row(array($nourut, $db['kodeakun'], $db['namaakun'], $db['kel']));
  $nourut++;
}


$pdf->AliasNbPages();
$pdf->output('master_akun.PDF','I');

?>
