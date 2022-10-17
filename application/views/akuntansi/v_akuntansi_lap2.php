<?php

$nmunit= $this->m_global->_namaunit($unit);
$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));

$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','',9);
$pdf->userdata($nama_usaha,$motto,$alamat,$nmunit,$tgl1,$tgl2);

class PDF extends FPDF
{

var $widths;
var $aligns;
var $CI;

    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
    }

    function userdata($nama,$moto,$alamat,$unit,$tgl1, $tgl2){
        $this->CI->session->set_userdata('nama', $nama);
		$this->CI->session->set_userdata('moto', $moto);
		$this->CI->session->set_userdata('alamat', $alamat);
        $this->CI->session->set_userdata('kode_unit', $unit);
		$this->CI->session->set_userdata('tgl1', $tgl1);
		$this->CI->session->set_userdata('tgl2', $tgl2);
    }

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

function Header()
{
   global $tgl1;
   global $tgl2;   
   global $nmunit;
   global $nama;
   
   //$this->ln(5);
   $this->SetFont('Times','BI',10);
   $this->image(base_url().'assets/img/logo_pdf.jpg',10,10,10,10);
   $this->cell(10);
   $this->cell(0,3,$this->CI->session->userdata('nama'),0,1);   
   $this->cell(10);
   $this->cell(0,3,$this->CI->session->userdata('moto'),0,1);
   $this->cell(10);
   $this->cell(0,3,$this->CI->session->userdata('alamat'),0,1);
   
   
   $this->ln(2);
   $this->SetFont('Times','BI',10);
   $this->SetTextColor(128);  
   $nmunit=$this->CI->session->userdata('kode_unit');   
   if($nmunit!='NONE')
   {
	 $this->cell(0,5,strtoupper($nmunit),0,1,'L');
   }
   $this->ln(5);
   $this->SetTextColor(0);
  
   $this->SetFont('Times','B',10);

   $_tgl1 = date('Y-m-d',strtotime($tgl1));
   $_tgl2 = date('Y-m-d',strtotime($tgl2));

   $__tgl1 = date('Y-m-d',strtotime('-1 day',strtotime($tgl1)));

   $bulan = date('m',strtotime($tgl1));
   $tahun = date('Y',strtotime($tgl1));

   $bulan_awal=$bulan;
   $tahun_awal=$tahun;

   $tawal   = $tahun_awal."-".$bulan_awal."-1";

   $tglawal = date('Y-m-d',strtotime($tawal));

   $this->cell(0,5,'LAPORAN BUKU BESAR',0,1,'C');
   $this->SetFont('Times','',10);
   //$periode = date('d-m-Y',strtotime($tgl1))." s/d ".date('d-m-Y',strtotime($tgl2));
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
  
}


function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Times italic 8
	$this->SetFont('Times','I',8);
	
	//Text color in gray
	//$this->SetTextColor(128);
	//Page number
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb} '.date('d-m-Y'),0,0,'C');
}

}


$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));


$_nmbulan = array('','Januari','Pebruari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');

 

   $_tgl1 = date('Y-m-d',strtotime($tgl1));
   $_tgl2 = date('Y-m-d',strtotime($tgl2));

   $__tgl1 = date('Y-m-d',strtotime('-1 day',strtotime($tgl1)));

   $bulan = date('m',strtotime($tgl1));
   $tahun = date('Y',strtotime($tgl1));


   $bulan_awal=$bulan;
   $tahun_awal=$tahun;

   $tawal   = $tahun_awal."-".$bulan_awal."-1";

   $tglawal = date('Y-m-d',strtotime($tawal));

if($akun!="NONE")
{
   $query = "select namaakun, jenis from ms_akun where kodeakun = '$akun'";
   $data  = $this->db->query($query);
   foreach($data->result() as $row){
   $nama_akun = $row->namaakun;
   $jenisakun = $row->jenis;
   }

   $query = "select debet+kredit as saldo from ms_akunsaldo where kodeakun = '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
   if($unit!='NONE')
   {
	$query.="and pasar = '$unit'";   
   }
   $hasil  = $this->db->query($query);
   foreach($hasil->result() as $row){   
   $saldo_awal = $row->saldo;}

   
   
   $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
             where tr_jurnalh.tanggal between '$tglawal' and '$__tgl1' and tr_jurnald.kodeakun = '$akun'";
   if($unit!='NONE')
   {
	$query.="and tr_jurnalh.kodepasar = '$unit'";   
   }		 
			 
   $hasil  = $this->db->query($query);
   foreach($hasil->result() as $row){  
   $debet =  $row->debet;
   $kredit=  $row->kredit;
   }

   if ($jenisakun=='D')
   {
     $saldo_awal = $saldo_awal + $debet - $kredit;
   } else
   {
     $saldo_awal = $saldo_awal - $debet + $kredit;
   }

   $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnalh.nojurnal=tr_jurnald.nojurnal
             where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun = '$akun'";
   if($unit!='NONE')
   {
	$query.="and tr_jurnalh.kodepasar = '$unit'";   
   }
   $hasil  = $this->db->query($query);
   $row    = $hasil->row();  
   $debet =  $row->debet;
   $kredit=  $row->kredit;

   if ($jenisakun=='D')
   {
     $saldo_akhir = $saldo_awal + $debet - $kredit;
   } else
   {
     $saldo_akhir = $saldo_awal - $debet + $kredit;
   }

  
   
$query =
        "select * from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
		 where tr_jurnald.kodeakun='$akun'
         and tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2'";
		 
if($unit!='NONE')
{
	$query.="and tr_jurnalh.kodepasar = '$unit'";   
}		 

$query .= "order by tr_jurnalh.tanggal, tr_jurnalh.nojurnal, tr_jurnald.nourut";


 $hasil  = $this->db->query($query);


   $pdf->ln();
   $pdf->cell(25,5,'Kode Akun',0,0,'L');$pdf->cell(5,5,':',0,0,'C');$pdf->cell(30,5,$akun,0,1,'L');
   $pdf->cell(25,5,'Nama Akun',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,$nama_akun,0,1,'L');
   $pdf->cell(25,5,'Saldo Awal',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,number_format($saldo_awal,2,',','.'),0,1,'R');
   $pdf->cell(25,5,'Saldo Akhir',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,number_format($saldo_akhir,2,',','.'),0,1,'R');

   $pdf->ln();
   $pdf->SetFont('Times','B',8);

   $pdf->SetWidths(array(10,17,30,65,25,25,25));
   $pdf->SetAligns(array('C','C','C','C','C','C','C'));


   $judul=array('NO','TANGGAL','NO. BUKTI','URAIAN','DEBET','KREDIT','SALDO');

   $pdf->row($judul);
   $pdf->SetWidths(array(10,17,30,65,25,25,25));
   $pdf->SetAligns(array('C','C','C','L','R','R','R'));
   
$nourut = 1;
$tot1 = 0;
$tot2 = 0;

$saldo = $saldo_awal;
$pdf->SetFont('');
foreach($hasil->result() as $row)
{
  if($jenisakun=='D')
  {
    $saldo=$saldo+$row->debet-$row->kredit;
  } else
  {
    $saldo=$saldo+$row->kredit-$row->debet;
  }

  $tot1=$tot1+$row->debet;
  $tot2=$tot2+$row->kredit;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->tanggal)),
  $row->novoucher,
  $row->keterangan,
  number_format($row->debet,2,'.',','),
  number_format($row->kredit,2,'.',','),
  number_format($saldo,2,'.',',')));

  $nourut++;

}

$pdf->setfont('Times','B',8);

$pdf->SetWidths(array(122,25,25,25));

$pdf->SetAligns(array('R','R','R'));

$pdf->row(array('TOTAL ',
          number_format($tot1,2,'.',','),
		  number_format($tot2,2,'.',','),''));
} else
{
   $query = "select kodeakun, namaakun, jenis from ms_akun where tipe=5 order  by kodeakun";
   $bb = $this->db->query($query);
   foreach($bb->result() as $rbb)
   {
     $kode_akun = $rbb->kodeakun;
     $nama_akun = $rbb->namaakun;
     $jenisakun = $rbb->jenis;

     $query = "select debet+kredit as saldo from ms_akunsaldo where kodeakun = '$kode_akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
	 if($unit!='NONE')
	 {
		$query.="and pasar = '$unit'";   
	 }
	 
     $hasil  = $this->db->query($query);
     foreach($hasil->result() as $row)
	 {
     $saldo_awal = $row->saldo;
     }
	 
     $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
	           where tr_jurnalh.tanggal between '$tglawal' and '$__tgl1' and tr_jurnald.kodeakun = '$kode_akun'";
	 
	 if($unit!='NONE')
	 {
		$query.="and tr_jurnalh.kodepasar = '$unit'";   
	 }	
	 
     $hasil  = $this->db->query($query);
     foreach($hasil->result()as $row){  
     $debet =  $row->debet;
     $kredit=  $row->kredit;
	 }

     if ($jenisakun=='D')
     {
         $saldo_awal = $saldo_awal + $debet - $kredit;
     } else
     {
         $saldo_awal = $saldo_awal - $debet + $kredit;
     }
       
	   $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
	             where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun = '$kode_akun'";
	   
	   if($unit!='NONE')
	 {
		$query.="and tr_jurnalh.kodepasar = '$unit'";   
	 }	
       $hasil  = $this->db->query($query);
       foreach($hasil->result() as $row){  
       $debet =  $row->debet;
       $kredit=  $row->kredit;
	   }

       if ($jenisakun=='D')
       {
         $saldo_akhir = $saldo_awal + $debet - $kredit;
       } else
       {
         $saldo_akhir = $saldo_awal - $debet + $kredit;
       }


   if($saldo_awal!=0 || $saldo_akhir!=0)
   {
   $pdf->ln(10);
   $pdf->setfont('Times','',11);
   $pdf->cell(25,5,'Kode Akun',0,0,'L');$pdf->cell(5,5,':',0,0,'C');$pdf->cell(30,5,$kode_akun,0,1,'L');
   $pdf->cell(25,5,'Nama Akun',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,$nama_akun,0,1,'L');
   $pdf->cell(25,5,'Saldo Awal',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,number_format($saldo_awal,2,',','.'),0,1,'R');
   $pdf->cell(25,5,'Saldo Akhir',0,0,'L');$pdf->cell(5,5,':',0,0,'C'); $pdf->cell(30,5,number_format($saldo_akhir,2,',','.'),0,1,'R');

   $pdf->ln();
   $pdf->SetFont('Times','B',8);

   $pdf->SetWidths(array(10,17,30,65,25,25,25));
   $pdf->SetAligns(array('C','C','C','C','C','C','C'));


   $judul=array('NO','TANGGAL','NO. BUKTI','URAIAN','DEBET','KREDIT','SALDO');

   $pdf->row($judul);
   $pdf->SetWidths(array(10,17,30,65,25,25,25));
   $pdf->SetAligns(array('C','C','C','L','R','R','R'));
   
   
   $query =
        "select * from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
		 where tr_jurnald.kodeakun='$kode_akun'
         and tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2'";
		 
	if($unit!='NONE')
	{
		$query.="and tr_jurnalh.kodepasar = '$unit'";   
	}		 

	$query .= "order by tr_jurnalh.tanggal, tr_jurnalh.nojurnal, tr_jurnald.nourut";

    
     $hasil  = $this->db->query($query);
     


  $nourut = 1;
  $tot1 = 0;
  $tot2 = 0;

 $saldo = $saldo_awal;
 $pdf->SetFont('Times','',8);
 foreach($hasil->result() as $row)
 {
  if($jenisakun=='D')
  {
    $saldo=$saldo+$row->debet-$row->kredit;
  } else
  {
    $saldo=$saldo+$row->kredit-$row->debet;
  }

  $tot1=$tot1+$row->debet;
  $tot2=$tot2+$row->kredit;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->tanggal)),
  $row->novoucher,
  $row->keterangan,
  number_format($row->debet,2,'.',','),
  number_format($row->kredit,2,'.',','),
  number_format($saldo,2,'.',',')));

  $nourut++;

 }

  $pdf->setfont('Times','B',8);
  $pdf->SetWidths(array(122,25,25,25));
  $pdf->SetAligns(array('R','R','R'));
  $pdf->row(array('TOTAL ',
          number_format($tot1,2,'.',','),
		  number_format($tot2,2,'.',','),''));
}
}
  $pdf->ln(5);
		  
}

$pdf->AliasNbPages();
$pdf->output('LAP_BUKUBESAR','I');

?>
