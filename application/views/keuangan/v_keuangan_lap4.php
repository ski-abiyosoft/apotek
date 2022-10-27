<?php
$nmunit= $this->m_global->_namaunit($unit);
$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','I',8);
$pdf->SetFont('');

$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));
$pdf->userdata($nama_usaha,$motto,$alamat,$nmunit,$tgl1,$tgl2,$bank);

class PDF extends FPDF
{

var $widths;
var $aligns;
var $CI;

    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
    }

    function userdata($nama,$moto,$alamat,$unit,$tgl1, $tgl2,$bank){
        $this->CI->session->set_userdata('nama', $nama);
		$this->CI->session->set_userdata('moto', $moto);
		$this->CI->session->set_userdata('alamat', $alamat);
        $this->CI->session->set_userdata('kode_unit', $unit);
		$this->CI->session->set_userdata('tgl1', $tgl1);
		$this->CI->session->set_userdata('tgl2', $tgl2);
		$this->CI->session->set_userdata('bank', $bank);
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
   $this->SetFont('Times','I',8);
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
   
   
   $this->cell(0,5,'LAPORAN PENGELUARAN KAS/BANK',0,1,'C');
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   
   if($this->CI->session->userdata('bank')!="NONE")
   {    
	$this->cell(0,5,'Kas/Bank : '.$this->CI->session->userdata('bank'),0,1,'C');
   }

   $this->ln();
   $this->ln();
   $this->SetFont('Times','B',10);
   
   $this->SetWidths(array(10,20,30,20,60,20,30));
   $this->SetAligns(array('C','C','C','C','C','C','C'));

  
   $judul=array('NO','TANGGAL','NO. BUKTI','AKUN','URAIAN','JUMLAH','PPIC');
  
   $this->row($judul);
   $this->SetWidths(array(10,20,30,20,60,20,30));
   $this->SetAligns(array('C','C','C','L','L','R','L'));
}


function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Times','I',8);
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb}',0,0,'C');
}

}



$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));


$query =
        "select a.keluar_tanggal, a.keluar_nomor, b.keluard_akun, b.keluard_uraian, b.keluard_jumlah,c.nama from tr_pengeluaran a, tr_pengeluarand b, ms_bidang c
         where a.keluar_register=b.keluard_register and a.keluar_bidang=c.kode and
		 a.keluar_nomor <>''
         and a.keluar_tanggal between '$_tgl1' and '$_tgl2' ";

if ($bank!="NONE")
{
  $query .="and a.keluar_kasbank = '$bank'";
}

if ($ppic!="NONE")
{
  $query .="and a.keluar_bidang = '$ppic'";
}

$query .= "order by a.keluar_acc2, a.keluar_nomor, b.keluard_nourut";


$hasil = $this->db->query($query)->result();

 
$nourut = 1;
$tot1 = 0;
$tot2 = 0;

foreach($hasil as $row)
{

  $tot1=$tot1+$row->keluard_jumlah;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->keluar_tanggal)),
  $row->keluar_nomor,
  $row->keluard_akun,
  $row->keluard_uraian,
  number_format($row->keluard_jumlah,2,'.',','),
  $row->nama));

  $nourut++;

}


//transfer keluar
$query =
        "select a.tanggal, a.nomor, b.bank_kodeakun, a.uraian, a.jumlah
         from tr_transfer a inner join ms_bank b on a.bank_sumber=b.bank_kode
         and a.tanggal between '$_tgl1' and '$_tgl2' ";

if ($bank!="NONE")
{
  $query .="and a.bank_sumber = '$bank'";
}

$query .= "order by a.tanggal, a.nomor";

$hasil = $this->db->query($query)->result();


$tot11 = 0;

foreach($hasil as $row)
{

  $tot11=$tot11+$row->jumlah;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->tanggal)),
  $row->nomor,
  $row->bank_kodeakun,
  $row->uraian,
  number_format($row->jumlah,2,'.',','),''));

  $nourut++;

}


$pdf->setfont('Times','B',8);


$pdf->SetWidths(array(140,20,30));

$pdf->SetAligns(array('C','R','C'));

$pdf->row(array('TOTAL',
          number_format($tot1+$tot11,2,'.',','),
		  ''));
		  
$pdf->AliasNbPages();
$pdf->output('LAP_PENGELUARANKAS','I');



?>


