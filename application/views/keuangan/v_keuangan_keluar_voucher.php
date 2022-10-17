<?php
$pdf=new simkeu_vou();
$pdf->addpage("P","A4");   
$pdf->setsize("P","A4");
$pdf->setfont('Times','',8);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');



   $keterangan = 'BUKTI PENGELUARAN KAS & BANK';



   $pdf->SetFont('Times','B',12);
   //$pdf->image(base_url().'assets/img/logo_pdf.jpg',170,15,20,20); 
   
   $pdf->cell(0,5,strtoupper($nama_usaha),0,1,'C');   
   $pdf->SetFont('Times','',9);
   
   $pdf->SetWidths(array(190));
   $pdf->SetAligns(array('C'));
   $border = array('');	
   $align = array('C');
   $pdf->FancyRow(array($alamat),$border,$align);
   
   $pdf->ln(2);
  
   $pdf->SetFont('Times','BU',10);
   //$pdf->settextcolor(10,20,200);
   
   $pdf->cell(0,10,$keterangan,0,0,'C');
   $pdf->ln();
   //$pdf->line(10,15,120,15);
   
   
    $pdf->SetFont('Times','',9);
	$pdf->SetWidths(array(35,5,80,20,5,45));
	$pdf->SetAligns(array('L','C','L','L','C','L'));	
	$pdf->row(array('Dibayarkan Kepada',':',$penerima,'Nomor',':',$nomor));	
	$pdf->row(array('Jumlah (Rp)',':',number_format($jumlah,2,',','.'),'Tanggal',':',$tanggal));
	
	$pdf->ln(5);
	$pdf->SetFont('Times','I',10);
	$pdf->SetWidths(array(190));
	$pdf->rowL(array('Terbilang : '.ucwords($terbilang)),8);
	$pdf->SetFont('Times','',10);
	$pdf->SetWidths(array(120,30,40));
	$pdf->SetAligns(array('C','C','C'));
	$pdf->rowL(array('Uraian  ','Kode Perk','Jumlah (Rp)'),8);
	$pdf->SetAligns(array('L','C','R'));
   
  
	
	$tot=0;
	
	$widths = array(120,30,40);
	$border = array('LR','LR','LR');	
	$align = array('L','C','R');
	$style = array('','','');
	$empty = array('','','');
	$pdf->SetWidths($widths);
	
	foreach ($detil as $row)
	{	   
	   $pdf->FancyRow(array($row->keluard_uraian,$row->keluard_akun,number_format($row->keluard_jumlah,2,'.',',')),$border,$align);
	   $tot=$tot+$row->keluard_jumlah;
	} 
	
	$pdf->SetFont('Times','',10);
	$pdf->SetWidths(array(70,60,20,40));
	$pdf->SetAligns(array('L','L','L','R'));
	$border = array('LRTB','TB','TB','LRTB');	
	$align = array('L','L','L','R');
	$pdf->FancyRow(array('Dibayar dengan Tunai/Cek/Giro ',$pembayaran.' No:'.$nogiro,'',''),$border,$align);
	$pdf->FancyRow(array('Bank : '.$namabank,'Tanggal : '.$tglgiro,'TOTAL Rp',number_format($tot,2,'.',',')),$border,$align);
	
	
    $pdf->SetWidths(array(140,50));
	$pdf->SetFont('Times','',9);
	$pdf->SetAligns(array('C','C'));
	$pdf->ln(5);
	$pdf->row(array('','Disetujui Oleh, '));
	$pdf->ln(1);
	$pdf->ln(15);
	$pdf->SetWidths(array(140,50));
	$pdf->SetFont('Times','',8);
	$pdf->SetAligns(array('C','C'));
	$border = array('','B');	
	$pdf->FancyRow(array('',''),$border,$align);
	$border = array('','');	
	$pdf->FancyRow(array('','Tgl.'),$border,$align);
	$pdf->SetFont('Times','',7);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('R'));
	$pdf->row(array($unit.",".$nomor.','.date('d-m-Y')));
                  
   $pdf->Ln();

//$pdf->AliasNbPages();
$pdf->output('KEUANGAN_BUKTI_KELUAR.PDF','I');

