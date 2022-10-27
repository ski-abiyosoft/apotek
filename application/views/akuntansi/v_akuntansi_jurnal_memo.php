<?php

$nmunit= $this->M_global->_namaunit($unit);

$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','',8);
$pdf->SetFont('');
$pdf->userdata($nama_usaha,$motto,$alamat,$nmunit,$nojurnal,$tanggal);

class PDF extends FPDF
{

var $widths;
var $aligns;
var $CI;

    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
    }
	
    function userdata($nama,$moto,$alamat,$unit,$nojurnal,$tanggal){
        $this->CI->session->set_userdata('nama', $nama);
		$this->CI->session->set_userdata('moto', $moto);
		$this->CI->session->set_userdata('alamat', $alamat);
        $this->CI->session->set_userdata('kode_unit', $unit);
		$this->CI->session->set_userdata('nojurnal', $nojurnal);
		$this->CI->session->set_userdata('tanggal', $tanggal);		
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

function Row1($data)
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
		//$this->Rect($x,$y,$w,$h);
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
   $this->SetFont('Times','I',8);
   //$this->image(base_url().'assets/img/logo_pdf.jpg',10,10,10,10);    
   //$this->cell(10);
   $this->cell(0,3,$this->CI->session->userdata('nama'),0,1);   
   //$this->cell(10);
   //$this->cell(0,3,$this->CI->session->userdata('moto'),0,1);
   //$this->cell(10);
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
         
   $this->cell(0,5,'MEMO JURNAL',0,1,'C');
   
   
   $this->ln();
   $this->SetFont('Times','B',8);
   $this->SetWidths(array(95,95));
   $this->SetAligns(array('L','R'));
   $this->Row1(array('NOMOR BUKTI : '.$this->CI->session->userdata('nojurnal'),'TANGGAL : '.date('d-m-Y',strtotime($this->CI->session->userdata('tanggal')))));
   $this->ln(1);

   $this->SetWidths(array(10,25,50,55,25,25));
   $this->SetAligns(array('C','C','C','C','C','C'));

  
   $judul=array('NO','KODE AKUN','NAMA AKUN','URAIAN','DEBET','KREDIT');
  
   $this->row($judul);
   $this->SetWidths(array(10,25,50,55,25,25));
   $this->SetAligns(array('C','C','L','L','R','R'));
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
	
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb} '.date('d-m-Y H:m:s'),0,0,'C');

}

}



$nourut = 1;
$tot1 = 0;
$tot2 = 0;
$tot1d = 0;
$tot2d = 0;



 $queryd =
        "select * from tr_jurnal inner join ms_akun on tr_jurnal.kodeakun=ms_akun.kodeakun where novoucher = '$nojurnal'
         order by nourut";
		 
 $hasild = $this->db->query($queryd);
 foreach($hasild->result() as $rowd)
 {
  //$tgl = strtotime($row["tanggal_pengajuan"]);
  $tot1=$tot1+$rowd->debet;
  $tot2=$tot2+$rowd->kredit;

  $pdf->row(array(
  $nourut,
  $rowd->kodeakun,
  $rowd->namaakun,
  $rowd->keterangan,
  number_format($rowd->debet,2,'.',','),
  number_format($rowd->kredit,2,'.',',')));
  
  $tot1d=$tot1d+$rowd->debet;
  $tot2d=$tot2d+$rowd->kredit;

  $nourut++;
 }
 
  $pdf->SetWidths(array(140,25,25));
  $pdf->SetAligns(array('R','R','R'));
 
  $pdf->setfont('Times','B',8);
  $pdf->row(array(
  'JUMLAH  ',
  number_format($tot1d,2,'.',','),
  number_format($tot2d,2,'.',',')));
  $pdf->ln();
  
  
  $pdf->setfont('Times','',8);
  $pdf->SetWidths(array(95, 95));
  $pdf->SetAligns(array('C','C'));
  $pdf->Row1(array('Menyetujui','Bagian Akuntansi'));
  $pdf->ln(20);
  $pdf->setfont('Times','BU',8);
  //$pdf->Row1(array('( '.$kadivkeu.' )','( '.$stafacc.' )'));
  
    
$pdf->AliasNbPages();
$pdf->output('MEMO_JURNAL','I');


?>


