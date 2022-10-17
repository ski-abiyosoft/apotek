<?php
$nmunit= $this->m_global->_namaunit($unit);
$pdf=new pdf("P","mm","A4");
$pdf->addpage();
$pdf->setfont('Times','I',8);
$pdf->SetFont('');

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
      

   $this->cell(0,5,'LAPORAN MUTASI KAS/BANK HARIAN',0,1,'C');   
   $periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Periode : '.$periode,0,1,'C');
   $this->cell(0,5,'Kas/Bank : '.$this->CI->session->userdata('bank'),0,1,'C');

   
   $this->ln();
   $this->SetFont('Times','B',7);
   
   $this->SetWidths(array(10,10,15,50,20,15,15,20,20,20));
   $this->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));

  
   $judul=array('NO','NO. BKK','TANGGAL','URAIAN/KETERANGAN','BIDANG/SUB BIDANG','NO. CEK','KODE ACC','DEBET','KREDIT','SALDO');
  
   $this->row($judul);
   $this->SetWidths(array(10,10,15,50,20,15,15,20,20,20));
   $this->SetAligns(array('C','C','C','L','C','C','C','R','R','R'));

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



  $q1 = "select saldo_awal from ms_banksaldo where bank_kode = '$bank' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
  $data=$this->db->query($q1)->row();
  $saldo_awal = $data->saldo_awal;
  

  $q2 = "select sum(terimad_jumlah) as jumlah from tr_penerimaan inner join tr_penerimaand on tr_penerimaan.terima_register=tr_penerimaand.terimad_register
         and tr_penerimaan.terima_nomor <> ''  
         where tr_penerimaan.terima_kasbank = '$bank' and tr_penerimaan.terima_tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";		 
		 
  $r2 = $this->db->query($q2)->row();
  $saldo_awal = $saldo_awal + $r2->jumlah;

  $q3 = "select sum(keluard_jumlah) as jumlah from tr_pengeluaran inner join tr_pengeluarand on tr_pengeluaran.keluar_register=tr_pengeluarand.keluard_register
         and tr_pengeluaran.keluar_nomor<>''
         where tr_pengeluaran.keluar_kasbank = '$bank' and tr_pengeluaran.keluar_tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
		 
  $r3 = $this->db->query($q3)->row();
  $saldo_awal = $saldo_awal - $r3->jumlah;
  
  $q3 = "select sum(jumlah) as jumlah from tr_bdd inner join tr_bddd on tr_bdd.nomor_register=tr_bddd.nomor_register
         and tr_bdd.nomor_bukti<>''
         where tr_bdd.kode_bank = '$bank' and tr_bdd.tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
		 
  $r3 = $this->db->query($q3)->row();
  $saldo_awal = $saldo_awal - $r3->jumlah;
  
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_tujuan = '$bank' and tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
  $rt = $this->db->query($qt)->row();
  $saldo_awal = $saldo_awal + $rt->jumlah;
  
  $qt = "select sum(jumlah) as jumlah from tr_transfer where bank_sumber = '$bank' and tanggal between '$_tanggal_awal' and '$_tanggal1_awal'";
  $rt = $this->db->query($qt)->row();
  $saldo_awal = $saldo_awal - $rt->jumlah;
  

$nourut = 1;
$pdf->row(array(
$nourut,
  '',
  '',  
  'Saldo Periode Sebelumnya',
  '',
  '',
  '',
  '',
  '',  
  number_format($saldo_awal,0,'.',',')));
  
$pdf->setfont('Times','',8);  
$nourut      = 2;
$totdebet    = 0;
$totkredit   = 0;
$saldo_akhir =$saldo_awal;

$query  = 
"select * from tr_penerimaan inner join tr_penerimaand on tr_penerimaan.terima_register=tr_penerimaand.terimad_register
 where tr_penerimaan.terima_tanggal between '$_tanggal1' and '$_tanggal2'
 and tr_penerimaan.terima_nomor <>''
 and tr_penerimaan.terima_kasbank='$bank' order by tr_penerimaan.terima_tanggal";

$data = $this->db->query($query)->result(); 
foreach ($data as $row)
{
  $totdebet=$totdebet+$row->terimad_jumlah;
  $saldo_akhir=$saldo_akhir+$row->terimad_jumlah;

  $pdf->row(array(
  $nourut,
  $row->terima_nomor,
  date('d-m-y',strtotime($row->terima_tanggal)),
  $row->terimad_uraian,
  $row->terima_bidang,
  '',
  $row->terimad_akun,
  number_format($row->terimad_jumlah,0,'.',','),
  0,
  number_format($saldo_akhir,0,'.',',')));
  

  $nourut++;

}

//transfer masuk

$query  = "select * from tr_transfer a inner join ms_bank b
on a.bank_tujuan=b.bank_kode
where tanggal between '$_tanggal1' and '$_tanggal2'
and a.bank_tujuan='$bank' order by a.nomor";
$data = $this->db->query($query)->result(); 
foreach ($data as $row)
{
  $totdebet=$totdebet+$row->jumlah;
  $saldo_akhir=$saldo_akhir+$row->jumlah;
  
  $pdf->row(array(
  $nourut,
  $row->nomor,
  date('d-m-y',strtotime($row->tanggal)),
  $row->uraian,
  $row->bidang,
  '',
  '',  
  number_format($row->jumlah,0,'.',','),  
  0,
  number_format($saldo_akhir,0,'.',',')));

  $nourut++;

}

$query  = 
"select * from tr_pengeluaran inner join tr_pengeluarand on tr_pengeluaran.keluar_register=tr_pengeluarand.keluard_register
 where tr_pengeluaran.keluar_tanggal between '$_tanggal1' and '$_tanggal2'
 and tr_pengeluaran.keluar_nomor<>''
 and tr_pengeluaran.keluar_kasbank='$bank' order by tr_pengeluaran.keluar_tanggal";
$data = $this->db->query($query)->result(); 
foreach ($data as $row)
{
  $totkredit=$totkredit+$row->keluard_jumlah;
  $saldo_akhir=$saldo_akhir-$row->keluard_jumlah;

  $pdf->row(array(
  $nourut,
  $row->keluar_nomor,
  date('d-m-y',strtotime($row->keluar_tanggal)),
  $row->keluard_uraian,
  $row->keluar_bidang,
  $row->keluar_cekgironomor,
  $row->keluard_akun,
  0,
  number_format($row->keluard_jumlah,0,'.',','),  
  number_format($saldo_akhir,0,'.',',')));
  

  $nourut++;

}

$query  = "select * from tr_transfer a inner join ms_bank b
on a.bank_tujuan=b.bank_kode
where tanggal between '$_tanggal1' and '$_tanggal2'
and a.bank_sumber='$bank' order by a.nomor";
$data = $this->db->query($query)->result(); 
foreach ($data as $row)
{
  $totkredit=$totkredit+$row->jumlah;
  $saldo_akhir=$saldo_akhir-$row->jumlah;
  
  $pdf->row(array(
  $nourut,
  $row->nomor,
  date('d-m-y',strtotime($row->tanggal)),
  $row->uraian,
  $row->bidang,
  '',
  '',  
  0,
  number_format($row->jumlah,0,'.',','),    
  number_format($saldo_akhir,0,'.',',')));

  $nourut++;

}

$query  = 
"select * from tr_bdd inner join tr_bddd on tr_bdd.nomor_register=tr_bddd.nomor_register
 where tr_bdd.tanggal between '$_tanggal1' and '$_tanggal2'
 and tr_bdd.nomor_bukti<>''
 and tr_bdd.kode_bank='$bank' order by tr_bdd.tanggal";
$data = $this->db->query($query)->result(); 
foreach ($data as $row)
{
  $totkredit=$totkredit+$row->jumlah;
  $saldo_akhir=$saldo_akhir-$row->jumlah;

  $pdf->row(array(
  $nourut,
  $row->nomor_bukti,
  date('d-m-y',strtotime($row->tanggal)),
  $row->uraian,
  $row->bidang,
  $row->nomor_cekgiro,
  $row->kode_akun,
  0,
  number_format($row->jumlah,0,'.',','),  
  number_format($saldo_akhir,0,'.',',')));
  

  $nourut++;

}


$pdf->setfont('Times','B',8);
$pdf->SetWidths(array(85,20,15,15,20,20,20));
$pdf->SetAligns(array('C','C','C','C','R','R','R'));
$pdf->row(array('JUMLAH','','','',number_format($totdebet,0,'.',','),number_format($totkredit,0,'.',','),number_format($saldo_akhir,0,'.',',')));

$pdf->setfont('Times','',8);
$pdf->ln(5);
$pdf->SetWidths(array(50,90,50));
$pdf->SetAligns(array('C','C','C'));

$pdf->rowWL(array('Mengetahui','','Bandung, '.$periode));
$pdf->rowWL(array('Kepala Bidang Keuangan','','Bendahara Pengeluaran'));
$pdf->ln(10);
//$pdf->rowWL(array($__keuangan,'',$__bendahara));



$pdf->AliasNbPages();
$pdf->output('LAP_DAFKASIR','I');


?>


