<?php
$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($namaunit);
$pdf->setjudul('Laporan Jurnal');
$pdf->setsubjudul($_peri);
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->SetWidths(array(10,17,25,20,30,45,24,24));
$pdf->SetAligns(array('C','C','C','C','C','C','C'));
$pdf->row($judul);
$pdf->SetWidths(array(10,17,25,20,30,45,24,24));
$pdf->SetAligns(array('C','C','C','C','L','L','R','R'));
$pdf->setfont('Times','',9);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
	
$nourut = 1;

foreach($lap as $db)
{
  $pdf->row(array($nourut, $db->kodepo, date('d-m-Y',strtotime($db->tglpo)), $db->namapemasok, number_format($db->totalpo,0,'.',',')));
  $nourut++;
}


$pdf->AliasNbPages();
$pdf->output('pembelian_101.PDF','I');
			

