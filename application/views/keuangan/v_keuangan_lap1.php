<?php

$nmunit= $this->m_global->_namaunit($unit);
$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','',8);
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
   

   $this->cell(0,5,'LAPORAN SALDO KAS/BANK',0,1,'C');
   //$periode = $_nmbulan[$bulan]." ".$tahun;
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   
   $this->ln();
   $this->ln();
   $this->SetFont('Times','B',10);
   
   $this->SetWidths(array(10,15,40,25,25,27,30,25));
   $this->SetAligns(array('C','C','C','C','C','C','C','C'));

  
   $judul=array('NO','KODE','NAMA BANK','NOREK','AWAL','DEBET','KREDIT','AKHIR');
  
   $this->row($judul);
   $this->SetWidths(array(10,15,40,25,25,27,30,25));
   $this->SetAligns(array('C','C','L','C','R','R','R','R'));
}


function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Times','I',8);
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb}',0,0,'C');
}

}




$tanggal1  = $tgl1;
$tanggal2  = $tgl2;

$bulan_awal = date('n',strtotime($tanggal1));
$tahun_awal = date('Y',strtotime($tanggal1));

$_tanggal1  = date('Y-m-d',strtotime($tanggal1));
$_tanggal2  = date('Y-m-d',strtotime($tanggal2));

$_tanggal1_awal  = date('Y-m-d',strtotime($tanggal1)-1);

$_tanggal_awal = $tahun_awal."-".$bulan_awal."-1";


$nourut = 1;
$tot1 = 0;
$tot2 = 0;
$tot3 = 0;
$tot4 = 0;

foreach($bank as $row)
{
  $bank = $row->bank_kode;

  $saldo_awal = 0;
  $penerimaan = 0;
  $pengeluaran = 0;
  $saldo_akhir = 0;
  
  $r1 = 0;
  $q1 = "select saldo_awal from ms_banksaldo where bank_kode = '$bank' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
  $d1 = $this->db->query($q1)->result();
  foreach($d1 as $rowd){
    $r1 = $rowd->saldo_awal;
  }
  $saldo_awal = $r1;
    
  
  $r2 = 0;
  $q2 = "select sum(tr_penerimaand.terimad_jumlah) as jumlah from tr_penerimaan inner join tr_penerimaand on tr_penerimaan.terima_register=tr_penerimaand.terimad_register
         where tr_penerimaan.terima_nomor <> ''
         and tr_penerimaan.terima_kasbank = '$bank' and tr_penerimaan.terima_tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";

  $d2 = $this->db->query($q2)->result();
  foreach($d2 as $rowd){
    $r2 = $rowd->jumlah;
  }

  $saldo_awal = $saldo_awal + $r2;
  
  $r3 = 0;
  $q3 = "select sum(tr_pengeluarand.keluard_jumlah) as jumlah from tr_pengeluaran inner join tr_pengeluarand on tr_pengeluaran.keluar_register=tr_pengeluarand.keluard_register
         where tr_pengeluaran.keluar_nomor <>''
         and tr_pengeluaran.keluar_kasbank = '$bank' and tr_pengeluaran.keluar_tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
  
  $d3 = $this->db->query($q3)->result();
  foreach($d3 as $rowd){
    $r3 = $rowd->jumlah;
  }		 
		   
  $saldo_awal = $saldo_awal - $r3;
  
  $rt = 0;
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_tujuan = '$bank' and tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
  $dt = $this->db->query($qt)->result();
  foreach($dt as $rowd){
    $rt = $rowd->jumlah;
  }  
  $saldo_awal = $saldo_awal + $rt;
  
  $rt = 0;
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_sumber = '$bank' and tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
  $dt = $this->db->query($qt)->result();
  foreach($dt as $rowd){
    $rt = $rowd->jumlah;
  }    
  $saldo_awal = $saldo_awal - $rt;
  
  $q2 = "select sum(tr_penerimaand.terimad_jumlah) as jumlah from tr_penerimaan inner join tr_penerimaand on tr_penerimaan.terima_register=tr_penerimaand.terimad_register
         where tr_penerimaan.terima_nomor <> ''
         and tr_penerimaan.terima_kasbank = '$bank' and tr_penerimaan.terima_tanggal between '$_tanggal1' and '$_tanggal2'";
  
  $r2 = 0;
  $dt = $this->db->query($q2)->result();
  foreach($dt as $rowd){
    $r2 = $rowd->jumlah;
  }  
  $penerimaan = $r2;
  
  $q3 = "select sum(tr_pengeluarand.keluard_jumlah) as jumlah from tr_pengeluaran inner join tr_pengeluarand on tr_pengeluaran.keluar_register=tr_pengeluarand.keluard_register
         where tr_pengeluaran.keluar_nomor <>''
         and tr_pengeluaran.keluar_kasbank = '$bank' and tr_pengeluaran.keluar_tanggal between '$_tanggal1' and '$_tanggal2'";

  $r3 = 0;		 
  $dt = $this->db->query($q3)->result();
  foreach($dt as $rowd){
    $r3 = $rowd->jumlah;
  }  
  $pengeluaran = $r3;
  
  //transfer kas/bank
  $rt = 0;
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_tujuan = '$bank' and tanggal between '$_tanggal1' and '$_tanggal2'";
  $dt = $this->db->query($qt)->result();
  foreach($dt as $rowd){
    $rt = $rowd->jumlah;
  }  
  $transfer_masuk = $rt;
  
  
  //transfer kas/bank
  $rt = 0;
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_sumber = '$bank' and tanggal between '$_tanggal1' and '$_tanggal2'";
  $dt = $this->db->query($qt)->result();
  foreach($dt as $rowd){
    $rt = $rowd->jumlah;
  }  
  $transfer_keluar = $rt;
  
  
  
  $saldo_akhir = $saldo_awal + $penerimaan - $pengeluaran + $transfer_masuk - $transfer_keluar;

  $tot1=$tot1+$saldo_awal;
  $tot2=$tot2+$penerimaan+$transfer_masuk;
  $tot3=$tot3+$pengeluaran+$transfer_keluar;
  $tot4=$tot4+$saldo_akhir;

  $pdf->row(array(
  $nourut,
  $row->bank_kode,
  $row->bank_nama,
  $row->bank_norek,
  number_format($saldo_awal,0,'.',','),
  number_format($penerimaan+$transfer_masuk,0,'.',','),
  number_format($pengeluaran+$transfer_keluar,0,'.',','),
  number_format($saldo_akhir,0,'.',',')));

  $nourut++;

}

$pdf->setfont('Times','B',8);


$pdf->SetWidths(array(90,25,27,30,25));

$pdf->SetAligns(array('C','R','R','R','R'));

$pdf->row(array('TOTAL',
          number_format($tot1,0,'.',','),
          number_format($tot2,0,'.',','),
          number_format($tot3,0,'.',','),
          number_format($tot4,0,'.',',')
          ));
		  
$pdf->AliasNbPages();
$pdf->output('LAP_SALDOBANK','I');



?>


