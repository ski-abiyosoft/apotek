<?php
$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($namaunit);
$pdf->setjudul('DAFTAR AKTIVA TETAP');
$pdf->addpage("L","LEGAL");   
$pdf->setsize("L","LEGAL");
$pdf->SetFont('Times','B',10);     
$pdf->SetWidths(array(10,70,25,10,17,25,13,25,25,15,25,20,25,25));
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
$judul=array('NO','KELOMPOK DAN JENIS AKTIVA TETAP','TANGGAL PEROLEHAN','QTY','SATUAN','HARGA PEROLEHAN','TARIF (%)','NILAI RESIDU','NILAI SISA','UMUR EKMS (THN)','AKUM. PENY. SD BLN LALU','PENY. BLN INI','AKUM. PENY. S/D BLN INI','NILAI BUKU');
$judul1=array('1','2','3','4','5','6','7','8 = 6 x 7','9 = 6 - 8','10','11','12 = 9 / 10','13 = 11 + 12','14 = 6 - 13');
$pdf->row($judul);
$pdf->row($judul1);
$pdf->SetWidths(array(10,70,25,10,17,25,13,25,25,15,25,20,25,25));
$pdf->SetAligns(array('C','L','C','C','C','R','C','R','R','C','R','R','R','R'));
$pdf->SetFont('Times','',10);   

$query1= "select kode, nama from ms_atjenis order by kode";
$hasil1=$this->db->query($query1)->result();

$tot1x = 0;
$tot2x = 0;
$tot3x = 0;
$tot4x = 0;
$tot5x = 0;
$tot6x = 0;
$tot7x = 0;

foreach($hasil1 as $row1)
{
    $query= "select * from ms_at_his where tahun='$tahun' and bulan='$bulan' and jenis = '$row1->kode' order by jenis, kode";
    $hasil=$this->db->query($query)->result();
    $baris=$this->db->query($query)->num_rows();
    
    $ada = 0;
    if ($baris>0)
    {
        $pdf->setfont('Times','B',12);
        $pdf->cell(0,10,$row1->nama,0,1,'L');
        $ada = 1;
    }

    $pdf->setfont('Times','',8);

    $nourut = 1;
    
    $tot1 = 0;
    $tot2 = 0;
    $tot3 = 0;
    $tot4 = 0;
    $tot5 = 0;
    $tot6 = 0;
    $tot7 = 0;

    foreach($hasil as $row)
    {
      $nilairesidu = $row->hargaperolehan*($row->tarif/100);
      $hargaperolehan= $row->hargaperolehan;
      $sisa = $hargaperolehan - $nilairesidu;
	  
	  
      $umur = $row->umurekonomis;
	  if(empty($umur)){
		$umur=1;  
	  }
      $akumpenyusutan= $row->akumpenyusutan;
      $penyusutanbln = $sisa / ($umur*12);
      $akumpenyusutanbln = $akumpenyusutan + $penyusutanbln;
      $nilaibuku = $hargaperolehan - $akumpenyusutanbln;

      $tot1=$tot1+$hargaperolehan;
      $tot2=$tot2+$nilairesidu;
      $tot3=$tot3+$sisa;
      $tot4=$tot4+$akumpenyusutan;
      $tot5=$tot5+$penyusutanbln;
      $tot6=$tot6+$akumpenyusutanbln;
      $tot7=$tot7+$nilaibuku;
      
      $tot1x=$tot1x+$hargaperolehan;
      $tot2x=$tot2x+$nilairesidu;
      $tot3x=$tot3x+$sisa;
      $tot4x=$tot4x+$akumpenyusutan;
      $tot5x=$tot5x+$penyusutanbln;
      $tot6x=$tot6x+$akumpenyusutanbln;
      $tot7x=$tot7x+$nilaibuku;

      $pdf->row(array($nourut,
      $row->nama,
      date('d-m-Y',strtotime($row->tanggalbeli)),
      $row->qty,
      $row->satuan,
      number_format($hargaperolehan,0,',','.'),
      $row->tarif,
      number_format($nilairesidu,0,',','.'),
      number_format($sisa,0,',','.'),
      $umur,
      number_format($akumpenyusutan,0,',','.'),
      number_format($penyusutanbln,0,',','.'),
      number_format($akumpenyusutanbln,0,',','.'),
      number_format($nilaibuku,0,',','.')));

      $nourut++;
      
      
    }
    
    //subtotal
    
    $pdf->setfont('Times','B',8);
    if ($tot1+$tot2+$tot3+$tot4+$tot5+$tot6+$tot7 >0)
    {
    $pdf->row(array('',
      'SUB TOTAL',
      '',
      '',
      '',
      number_format($tot1,0,',','.'),
      '',
      number_format($tot2,0,',','.'),
      number_format($tot3,0,',','.'),
      '',
      number_format($tot4,0,',','.'),
      number_format($tot5,0,',','.'),
      number_format($tot6,0,',','.'),
      number_format($tot7,0,',','.')));
    }
    
    if ($ada==1)
    {
      $pdf->ln();
    }
    
}

//grand total
$pdf->row(array('',
      'GRAND TOTAL',
      '',
      '',
      '',
      number_format($tot1x,0,',','.'),
      '',
      number_format($tot2x,0,',','.'),
      number_format($tot3x,0,',','.'),
      '',
      number_format($tot4x,0,',','.'),
      number_format($tot5x,0,',','.'),
      number_format($tot6x,0,',','.'),
      number_format($tot7x,0,',','.')));

$pdf->AliasNbPages();
$pdf->output('keuangan_at.PDF','I');

?>
