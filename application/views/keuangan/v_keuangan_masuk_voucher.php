<?php

class PDF extends FPDF
{

var $widths;
var $aligns;

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
		//$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function RowL($data,$h)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	//$h=5*$nb;
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
		$this->MultiCell($w,$h,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function RowLF($data,$h)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	//$h=5*$nb;
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
		$this->MultiCell($w,$h+15,$data[$i],0,$a);
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



}

/*

$query = "select dirop, dirut, kadivkeu, stafakunting from ms_identity";
$data  = $this->db->query($query)->result();
foreach($data as $row){
 $diketahui  = '('.strtoupper($row->dirop).')';
 $disetujui  = '('.strtoupper($row->dirut).')';
 $diterima   = '('.strtoupper($row->kadivkeu).')';
 $dibukukan  = '('.strtoupper($row->stafakunting).')';
	
}
*/



if($jenis==1)
{
    foreach($header as $row){
    $tanggal = date('d-m-Y',strtotime($row->terima_tanggal));
    $ket     = $row->terima_uraian;
    $jeniskb = $row->bank_jenis;
    $penerima= $row->terima_penerima;
    }

    $ketditerimadari = 'Diterima dari';
    $diterimadari = $penerima;

    if($jeniskb=='K')
    {
      $judul='BUKTI PENERIMAAN KAS';
    } elseif($jeniskb=='B')
    {
      $judul='BUKTI PENERIMAAN BANK';
    }
}
elseif($jenis==2)
{
    $query = "select * from tr_pengeluaran inner join ms_bank on tr_pengeluaran.keluar_kasbank=ms_bank.bank_kode where keluar_register = '$nomor'";
    $data  = mysqli_query($conn, $query);
    $row   = mysqli_fetch_array($data);
    $tanggal = date('d-m-Y',strtotime($row['keluar_tanggal']));
    $ket     = $row['keluar_uraian'];
    $jeniskb = $row['bank_jenis'];
    $penerima= $row['keluar_penerima'];

    $query = "select sum(keluard_jumlah) as jumlah from tr_pengeluarand where keluard_register = '$nomor'";
    $data  = mysqli_query($conn, $query);
    $row   = mysqli_fetch_array($data);
    $jumlah  = $row['jumlah'];
    
    $ketditerimadari = 'Dibayarkan kepada';
    $diterimadari = $penerima;

    if($jeniskb=='K')
    {
      $judul='BUKTI PENGELUARAN KAS';
    } elseif($jeniskb=='B')
    {
      $judul='BUKTI PENGELUARAN BANK';
    }
}
elseif($jenis==3)
{
    $query = "select * from tr_transfer where nomor = '$nomor'";
    $data  = mysqli_query($conn, $query);
    $row   = mysqli_fetch_array($data);
    $tanggal = date('d-m-Y',strtotime($row['tanggal']));
    $ket     = $row['uraian'];
    $sumber  = $row['bank_sumber'];
    $tujuan  = $row['bank_tujuan'];
    $jumlah  = $row['jumlah'];
    
    $query = "select bank_nama from ms_bank where bank_kode = '$sumber'";
    $data  = mysqli_query($conn, $query);
    $row   = mysqli_fetch_array($data);
    $sumbernm = $row[0];
    
    $query = "select bank_nama from ms_bank where bank_kode = '$tujuan'";
    $data  = mysqli_query($conn, $query);
    $row   = mysqli_fetch_array($data);
    $tujuannm = $row[0];
    
    $ketditerimadari = 'Sumber/Tujuan';
    $diterimadari = $sumbernm." ke ".$tujuannm;

    $judul='BUKTI TRANSFER';

}


$pdf=new pdf("P","mm","A4");
$pdf->AddPage();

$pdf->SetFont('Times','B',16);
//$pdf->image('../img/logo.jpg',170,15,20,20);
$pdf->image(base_url().'assets/img/logo_pdf.jpg',170,15,20,20);   
$pdf->ln(5);
$pdf->cell(0,5,$nama_usaha,0,1,'C');
$pdf->SetFont('Times','',12);

$pdf->SetWidths(array(180));
$pdf->SetAligns(array('C'));

$pdf->row(array('Jalan Jurang Nomor 1 Bandung 40515'));
$pdf->row(array('Telepon 022-2038189, Fax: 022-2033747'));

$pdf->setFont("Times","BU",14);
$pdf->cell(0,10,$judul,0,1,'C');
$pdf->SetFont('Times','',12);
$pdf->SetWidths(array(40,5,145));
$pdf->SetAligns(array('L','C','L'));
$pdf->row(array($ketditerimadari,':',$diterimadari));
$pdf->ln(3);
$pdf->row(array('Jumlah dalam angka',':','Rp '.number_format($jumlah,2,',','.')));
$pdf->ln(3);
$pdf->row(array('Jumlah dalam huruf',':',$terbilang));
$pdf->ln(3);
$pdf->row(array('Perihal',':',$ket));
$pdf->ln(3);

if($jenis!=2)
{
    $pdf->SetWidths(array(45,60,45,40));
    $pdf->SetAligns(array('C','C','C','C'));

    $pdf->rowL(array('DIKETAHUI','DISETUJUI','DITERIMA OLEH','DIBUKUKAN'),10);
    $pdf->SetFont('Times','',10);
    $pdf->rowLF(array('','','',''),25);
} else
{
    $pdf->SetWidths(array(27,27,27,27,27,27,27));
    $pdf->SetAligns(array('C','C','C','C','C','C','C'));

    $pdf->rowL(array('Direktur Utama','Direktur Administrasi','Ka. Bid Keuangan','Ka. Sub Bid Anggaran','Bendaraha Pengeluaran','Penerima','Akuntansi'),10);
    $pdf->SetFont('Times','',8);
    //$pdf->rowLF(array($diterima, $diketahui,$disetujui,$diterima,$penerima,$dibukukan),25);
}


$pdf->AliasNbPages();
$pdf->output('KWITANSI','I');



