<?php

$nmunit= $this->m_global->_namaunit($unit);
$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));

$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','',7);
$pdf->SetFont('');
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
   
   $_nmbulan = array('','Januari','Pebruari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
   

   $this->cell(0,5,'NERACA LAJUR',0,1,'C');
   $this->SetFont('Times','',10);
   //$periode = date('d-m-Y',strtotime($tgl1))." s/d ".date('d-m-Y',strtotime($tgl2));
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   

   $this->ln();
   $this->SetFont('Times','B',8);
   
   $this->SetWidths(array(10,18,50,40,40,40));
   $this->SetAligns(array('C','C','C','C','C','C'));
   $judul0=array('NO','KODE','NAMA AKUN','SALDO AWAL','MUTASI','SALDO AKHIR');
   $this->row($judul0);
   $this->SetWidths(array(10,18,50,20,20,20,20,20,20));
   $this->SetAligns(array('C','C','C','C','C','C','C','C','C'));

   $judul=array('','','','DEBET','KREDIT','DEBET','KREDIT','DEBET','KREDIT');
  
   $this->row($judul);
   $this->SetWidths(array(10,18,50,20,20,20,20,20,20));
   $this->SetAligns(array('C','L','L','R','R','R','R','R','R'));
}


function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Times','I',8);
	
	//Text color in gray
	//$this->SetTextColor(128);
	//Page number
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb} '.date('d-m-Y'),0,0,'C');
}

}


$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));


$query = "select kodeakun, namaakun, jenis from ms_akun order by kodeakun";

$hasil = $this->db->query($query);

 
$nourut = 1;
$tot1 = 0;
$tot2 = 0;




$__tgl1 = date('Y-m-d',strtotime('-1 day',strtotime($tgl1)));

$bulan = date('m',strtotime($tgl1));
$tahun = date('Y',strtotime($tgl1));

$bulan_awal = $bulan;
$tahun_awal = $tahun;

$tawal   = $tahun_awal."-".$bulan_awal."-1";
$tglawal = date('Y-m-d',strtotime($tawal));

$tot1 = 0;
$tot2 = 0;
$tot3 = 0;
$tot4 = 0;
$tot5 = 0;
$tot6 = 0;

foreach($hasil->result() as $rowD)
{
   $jenisakun = $rowD->jenis;
   $akun = $rowD->kodeakun;
   
   if($jenisakun=='D')
   {
     $query = "select sum(debet-kredit) as saldo from ms_akunsaldo where kodeakun = '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
	 if($unit!='NONE')
	 {
		$query.="and pasar = '$unit'";   
	 }
	   
     $data  =$this->db->query($query);
     foreach($data->result() as $row){
		 $jumdata=$row->saldo;
	 }
     if($jumdata>0)	 
	 {	 
       $saldo_awaldb = $jumdata;
       $saldo_awalkr = 0;
	 } else
	 {
	   $saldo_awaldb = 0;
       $saldo_awalkr = abs($jumdata);
	 }		 
     
   } else
   {
     $query = "select sum(kredit-debet) as saldo from ms_akunsaldo where kodeakun = '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
	 if($unit!='NONE')
	 {
		$query.="and pasar = '$unit'";   
	 }
     $data  = $this->db->query($query);
	 foreach($data->result() as $row){
		 $jumdata=$row->saldo;
	 }
   
	 
	 if($jumdata>0)	 
	 {	 
       $saldo_awaldb = 0;
       $saldo_awalkr = $jumdata;
	 } else
	 {
	   $saldo_awaldb = abs($jumdata);
       $saldo_awalkr = 0;
	 }		 
   }
   
   
   $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
             where tr_jurnalh.tanggal between '$tglawal' and '$__tgl1' and tr_jurnald.kodeakun = '$akun'";
   if($unit!='NONE')
   {
	$query.="and tr_jurnalh.kodepasar = '$unit'";   
   }	
   
   $data  = $this->db->query($query);
   foreach($data->result() as $row){
   $debet = $row->debet;
   $kredit= $row->kredit;
   }

   if ($jenisakun=='D')
   {
     $saldo_awal = ($saldo_awaldb-$saldo_awalkr) + $debet - $kredit;
   } else
   {
     $saldo_awal = ($saldo_awalkr-$saldo_awaldb) - $debet + $kredit;
   }
   
   
   $query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnalh.nojurnal=tr_jurnald.nojurnal
             where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun = '$akun'";
   if($unit!='NONE')
   {
	$query.="and tr_jurnalh.kodepasar = '$unit'";   
   }
   
   $data  = $this->db->query($query);
   foreach($data->result() as $row){
   $debet = $row->debet;
   $kredit= $row->kredit;
   }

   if ($jenisakun=='D')
   {
     $saldo_akhir = ($saldo_awal + $debet - $kredit);
   } else
   {
     $saldo_akhir = $saldo_awal - $debet + $kredit;
   }
   

   if ($jenisakun=='D')
   {
	   if($saldo_akhir>0)
	   {
         $saldo_debet  = $saldo_akhir;
         $saldo_kredit =0;
	   } else
	   {
		 $saldo_debet  = 0;
         $saldo_kredit = abs($saldo_akhir);  
	   }		   

   } else	   
   {
	   if($saldo_akhir>0)
	   {
         $saldo_kredit  = $saldo_akhir;
         $saldo_debet =0;
	   } else
	   {
		 $saldo_kredit  = 0;
         $saldo_debet   = abs($saldo_akhir);
	   }		   
   }

  
  if(($saldo_debet+$saldo_kredit!=0)||($saldoawal!=0)||($debet!=0)||($kredit!=0))
  {

  $pdf->row(array(
  $nourut,
  $rowD->kodeakun,
  $rowD->namaakun,
  number_format($saldo_awaldb,0,'.',','),
  number_format($saldo_awalkr,0,'.',','),
  number_format($debet,0,'.',','),
  number_format($kredit,0,'.',','),
  number_format($saldo_debet,0,'.',','),
  number_format($saldo_kredit,0,'.',',')));
  $nourut++;
  }
  
  $tot1=$tot1+$saldo_awaldb;
  $tot2=$tot2+$saldo_awalkr;
  $tot3=$tot3+$debet;
  $tot4=$tot4+$kredit;
  $tot5=$tot5+$saldo_debet;
  $tot6=$tot6+$saldo_kredit;
  

}

$pdf->SetWidths(array(78,20,20,20,20,20,20));
$pdf->SetAligns(array('R','R','R','R','R','R','R'));
$pdf->setfont('Times','B',7);
$pdf->row(array(
  'TOTAL      ',
  number_format($tot1,0,'.',','),
  number_format($tot2,0,'.',','),
  number_format($tot3,0,'.',','),
  number_format($tot4,0,'.',','),
  number_format($tot5,0,'.',','),
  number_format($tot6,0,'.',',')
  ));

$pdf->AliasNbPages();
$pdf->output('LAP_TRIALBALANCE','I');




