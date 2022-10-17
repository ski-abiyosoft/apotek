<?php
$nmunit= $this->m_global->_namaunit($unit);
$pdf=new pdf();
$pdf->addpage();
$pdf->setfont('Times','I',8);
$pdf->SetFont('');

$pdf->userdata($nama_usaha,$motto,$alamat,$nmunit,$tgl1,$tgl2);


class PDF extends FPDF
{

var $widths;
var $aligns;
var $CI;

    function __construct($orientation='L', $unit='mm', $size='A4')
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
	$h=4*$nb;
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
		$this->MultiCell($w,4,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function RowWL($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=4*$nb;
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
		$this->MultiCell($w,4,$data[$i],0,$a);
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
   

   $this->cell(0,5,'REKAP UANG MUKA DAN BIAYA DIBAYAR DIMUKA',0,1,'C');
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
  
   
   $this->ln();
   $this->SetFont('Times','B',7);
   
   $this->SetWidths(array(155,20,75,25));
   $this->SetAligns(array('C','C','C','C'));

   $judul=array('PERMINTAAN/PENGELUARAN UANG MUKA/BIAYA DIBAYAR DIMUKA','SALDO','DATA PENGEMBALIAN','');
   $this->row($judul);
   
   $this->SetWidths(array(10,10,15,100,20,20,15,20,20,20,25));
   $this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

   $judul=array('NO URUT','NO. BKK','TANGGAL','URAIAN','JUMLAH','TOTAL (4-9)','NO. BKK','TANGGAL','JUMLAH','TOTAL','PPIC');
  
   $this->row($judul);
   $judul=array('','1','2','3','4','5','6','7','8','9','10');
  
   $this->row($judul);
   $this->SetWidths(array(10,10,15,100,20,20,15,20,20,20,25));
   $this->SetAligns(array('C','C','C','L','R','R','C','R','R','R','L'));

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

$_tanggal1_awal  = date('Y-m-d',strtotime($tanggal1)-1);

$_tanggal1  = date('Y-m-d',strtotime($tanggal1));
$_tanggal2  = date('Y-m-d',strtotime($tanggal2));

$_tanggal_awal = $tahun_awal."-".$bulan_awal."-1";

$periode = date('d',strtotime($tanggal1)).' '.$this->m_global->_namabulan($bulan_awal).' '.$tahun_awal;

$nourut      = 2;

$query  = 
"select * from tr_bdd inner join tr_bddd on tr_bdd.nomor_register=tr_bddd.nomor_register inner join ms_bidang on tr_bdd.bidang=ms_bidang.kode
 where tr_bdd.tanggal between '$_tanggal1' and '$_tanggal2'";
 
if ($ppic!="NONE")
{
  $query .="and bidang = '$ppic'";
} 

$tot=0;
$query.="order by tr_bdd.tanggal";
$hasil = $this->db->query($query)->result();
$nourut = 1;
foreach($hasil as $row)
{
  $tot=$tot+$row->jumlah;
  $jumawal=$row->jumlah;
  $nobdd=$row->nomor_register;
  
  $qrinci=" select keluar_nomor, keluar_tanggal, sum(keluard_jumlah) as jumlah 
			from tr_pengeluaran inner join tr_pengeluarand
			on tr_pengeluaran.keluar_register=tr_pengeluarand.keluard_register
			where
			   keluard_nobdd=$nobdd";
			   
  $drinci=$this->db->query($qrinci)->row();  
  $jumreal=$drinci->jumlah;
  
  if($jumreal>0)
  {
	if($jumreal>$jumawal)  
	{
	$saldo=$jumawal;  
	$sisa=0;
	} else
	{
	  $saldo=$jumreal;  
	  $sisa =$jumawal-$jumreal;	
	}
  } else
  {
	$saldo=$jumawal;  
	$sisa =0;
  }
    
  if($drinci->keluar_tanggal!='')
  {
	  $tglreal =  date('d-m-Y',strtotime($drinci->keluar_tanggal));
  } else
  {
	  $tglreal='';
  }
  
  $pdf->row(array(
  $nourut,
  $row->nomor_bukti,
  date('d-m-y',strtotime($row->tanggal)),
  $row->uraian,
  number_format($row->jumlah,0,'.',','),
  number_format($saldo,0,'.',','),
  $drinci->keluar_nomor,
  $tglreal,
  number_format($sisa,0,'.',','),
  number_format($sisa,0,'.',','),
  $row->nama
  ));
  

  $nourut++;

}



$pdf->setfont('Times','B',8);
$pdf->SetWidths(array(155,20,100));
$pdf->SetAligns(array('C','R','R'));
$pdf->row(array('JUMLAH',number_format($tot,0,'.',','),''));

$pdf->setfont('Times','',8);
$pdf->ln(5);
$pdf->SetWidths(array(50,170,50));
$pdf->SetAligns(array('C','C','C'));

$pdf->rowWL(array('','','Bandung, '.$periode));
$pdf->rowWL(array('','','Dibuat Oleh,'));
$pdf->rowWL(array('','','Pelaksana Sub Bidang Keuangan'));
$pdf->ln(10);
//$pdf->rowWL(array('','','Asep Ramdani SE.'));



$pdf->AliasNbPages();
$pdf->output('LAP_BDD','I');


?>


