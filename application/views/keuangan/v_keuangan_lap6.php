<?php
$nmunit= $this->m_global->_namaunit($unit);
$pdf=new pdf();
$pdf->addpage();
$pdf->setfont('Times','',10);


$pdf->userdata($nama_usaha,$motto,$alamat,$nmunit,$tgl1,$tgl2);


class PDF extends FPDF
{

var $widths;
var $aligns;
var $CI;

    function __construct($orientation='P', $unit='mm', $size='A4')
    {    
       parent::__construct($orientation,$unit,$size);
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
   
   $this->cell(0,5,'LAPORAN REPRESENTASI DIREKSI',0,1,'C');
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   
 
   $this->ln();
   $this->ln();
   $this->SetFont('Times','B',10);
   
   $this->SetWidths(array(10,20,30,25,80,25));
   $this->SetAligns(array('C','C','C','C','C','C'));

  
   $judul=array('NO','TANGGAL','NO. BUKTI','PENERIMA','URAIAN','JUMLAH');
  
   $this->row($judul);
   $this->SetWidths(array(10,20,30,25,80,25));
   $this->SetAligns(array('C','C','C','L','L','R'));
}


function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Times','I',8);
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb}',0,0,'C');
}

}


$nourut = 1;
$tot = 0;
foreach($list as $row)
{
  $tot=$tot+$row->jumlah;

  $pdf->row(array(
  $nourut,
  date('d-m-Y',strtotime($row->tanggal)),
  $row->nomor_bukti,
  $row->penerima,
  $row->keterangan,
  number_format($row->jumlah,0,'.',',')));
  $nourut++;

}


$pdf->setfont('Times','B',10);
$pdf->SetWidths(array(165,25));
$pdf->SetAligns(array('R','R'));
$pdf->row(array('TOTAL  ',
          number_format($tot,0,'.',',')));
$pdf->AliasNbPages();
$pdf->output('LAP_REPRE','I');



?>


