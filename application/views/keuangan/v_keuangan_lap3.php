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
   
   $this->SetWidths(array(10,20,30,20,80,30));
   $this->SetAligns(array('C','C','C','C','C','C'));

   $this->cell(0,5,'LAPORAN PENERIMAAN KAS/BANK',0,1,'C');
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   
   if($this->CI->session->userdata('bank')!="NONE")
   {    
	$this->cell(0,5,'Kas/Bank : '.$this->CI->session->userdata('bank'),0,1,'C');
   }
   
   
   $judul=array('NO','TANGGAL','NO. BUKTI','AKUN','URAIAN','JUMLAH');
  
   $this->row($judul);
   $this->SetWidths(array(10,20,30,20,80,30));
   $this->SetAligns(array('C','C','C','L','L','R'));
}


function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Times','I',8);
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb}',0,0,'C');
}

}


$query =
        "select a.terima_tanggal, a.terima_nomor, b.terimad_akun, b.terimad_uraian, b.terimad_jumlah from tr_penerimaan a, tr_penerimaand b 
		 where a.terima_register=b.terimad_register and a.terima_nomor <> ''
         and a.terima_tanggal between '$_tgl1' and '$_tgl2' ";

if ($bank!="NONE")
{
  $query .="and a.terima_kasbank = '$bank'";
}

$query .= "order by a.terima_tanggal, a.terima_nomor, b.terimad_nourut";


$hasil = $this->db->query($query)->result();
$nourut = 1;
$tot1 = 0;
$tot2 = 0;

foreach($hasil as $row)
{

  $tot1=$tot1+$row->terimad_jumlah;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->terima_tanggal)),
  $row->terima_nomor,
  $row->terimad_akun,
  $row->terimad_uraian,
  number_format($row->terimad_jumlah,2,'.',',')));

  $nourut++;

}

//transfer masuk
$query =
        "select a.tanggal, a.nomor, b.bank_kodeakun, a.uraian, a.jumlah
         from tr_transfer a inner join ms_bank b on a.bank_tujuan=b.bank_kode
         and a.tanggal between '$_tgl1' and '$_tgl2' ";

if ($bank!="NONE")
{
  $query .="and a.bank_tujuan = '$bank'";
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
  number_format($row->jumlah,2,'.',',')));

  $nourut++;

}

$pdf->setfont('Times','B',8);


$pdf->SetWidths(array(160,30));

$pdf->SetAligns(array('C','R'));

$pdf->row(array('TOTAL',
          number_format($tot1+$tot11,2,'.',',')));
		  
$pdf->AliasNbPages();
$pdf->output('LAP_PENERIMAANKAS','I');


?>


