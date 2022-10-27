<?php
$nmunit= $this->m_global->_namaunit($unit);
$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));

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
   
   
   $_nmbulan = array('','JANUARI','PEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOPEMBER','DESEMBER');
   

   $this->cell(0,5,'NERACA',0,1,'C');
   $this->SetFont('Times','',10);

   $tanggal = date('d',strtotime($this->CI->session->userdata('tgl2')));
   $bulan   = date('n',strtotime($this->CI->session->userdata('tgl2')));
   $tahun   = date('Y',strtotime($this->CI->session->userdata('tgl2')));
   $periode = $tanggal." ".ucwords(strtolower($_nmbulan[$bulan]))." ".$tahun;
   //$periode = date('d-m-Y',strtotime($this->CI->session->userdata('tgl1')))." s/d ".date('d-m-Y',strtotime($this->CI->session->userdata('tgl2')));
   $this->cell(0,5,'Per '.$periode,0,1,'C');
   //$this->cell(0,5,'( Dalam Rupiah )',0,1,'C');
   
   $this->ln();
   $this->SetFont('Times','B',8);
}


function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Times','I',8);	
	$this->Cell(0,10,'Halaman : '.$this->PageNo().' dari : {nb} '.date('d-m-Y H:m:s'),0,0,'C');
}

}

 
$nourut = 1;
$tot1 = 0;
$tot2 = 0;


$__tgl1 = date('Y-m-d',strtotime('-1 day',strtotime($tgl1)));

$bulan = date('n',strtotime($tgl1));
$tahun = date('Y',strtotime($tgl1));

$bulan_awal = $bulan;
$tahun_awal = $tahun;

$tawal   = $tahun_awal."-".$bulan_awal."-1";
$tglawal = date('Y-m-d',strtotime($tawal));


$pdf->SetWidths(array(7,90,10,40,10,40));
$pdf->SetAligns(array('L','L','C','R','C','R'));
$pdf->setfont('Times','B',10);
$pdf->row(array('','A K T I V A','','','',''));

$qjudul ="select nomor, nama  from ms_akun_kelompok where lap='N' and tipe='A' order by nomor";
$djudul = $this->db->query($qjudul);

$tot_aktiva = 0;
foreach ($djudul->result() as $rowjudul)
{
    $kelompok=$rowjudul->nomor;
     
	$pdf->setfont('Times','B',10);
	$pdf->row(array('',$rowjudul->nama,'','','',''));
	$pdf->setfont('Times','',10);

	$query =
			" select nomor, judul_lap  from ms_formatd where kode='$format' and kelompok=$kelompok order by nourut";

	$hasil = $this->db->query($query);

	
	$tot_kelompok = 0;	
	foreach ($hasil->result() as $rowheder)
	{
	   $nolap     = $rowheder->nomor;
	   $judul_lap = $rowheder->judul_lap;
	   	   
	   $qdetil = "select akun, jenis from ms_formatdd inner join ms_akun on ms_formatdd.akun=ms_akun.kodeakun where nomorlap=$nolap";
	   $ddetil = $this->db->query($qdetil);
	   
	   
	   $tot_rinci = 0;
	   foreach ($ddetil->result() as $rowdetil)
	   {
			$akun  = $rowdetil->akun;
			$jenis = $rowdetil->jenis;
			
			$query = "select sum(debet) as debet, sum(kredit) as kredit from ms_akunsaldo where kodeakun= '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
			if($unit!='NONE')
			{
			  $query.="and pasar = '$unit'";   
			}
			   
			$data  = $this->db->query($query);
			foreach($data->result() as $row){
			 $saldo_awal = $row->debet+$row->kredit;	
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

			if($jenis=='D')
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
   
			$data  = $this->db->query($query);
			foreach($data->result() as $row){
			$debet = $row->debet;
			$kredit= $row->kredit;
			}

			if($jenis=='D')
			{
			   $saldo_akhir = ($saldo_awal + $debet - $kredit);
			} else
			{
			   $saldo_akhir = ($saldo_awal - $debet + $kredit);	
			}				
			
			if($jenis=='K')
			{
			   $saldo_akhir=$saldo_akhir*-1;	
			}
				
			$tot_rinci   = $tot_rinci + $saldo_akhir;
	              		   
	   }
	   //if($tot_rinci!=0)
		 {
		    $pdf->row(array('','     '.$judul_lap,'',number_format($tot_rinci,0,',','.'),'',''));
		 }
	   
	   
		  
	   $tot_kelompok = $tot_kelompok + $tot_rinci;
	}
    $tot_aktiva=$tot_aktiva+$tot_kelompok;	
	$pdf->setfont('Times','B',10);
    $pdf->row(array('','TOTAL '.$rowjudul->nama,'',number_format($tot_kelompok,0,',','.'),'',''));
    $pdf->ln();
}

$pdf->setfont('Times','B',10);
$pdf->row(array('','TOTAL AKTIVA ','',number_format($tot_aktiva,0,',','.'),'',''));
$pdf->ln();


//passiva

//menghitung l/r berjalan

$query = "select sum(tr_jurnald.kredit-tr_jurnald.debet) as jumlah from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal inner join ms_akun
          on tr_jurnald.kodeakun=ms_akun.kodeakun
          where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun like '4%' and tr_jurnald.kodeakun<'403'";

if($unit!='NONE')
{
   $query.="and tr_jurnalh.kodepasar = '$unit'";   
}

$data  = $this->db->query($query);
foreach($data->result() as $row){
$pendapatan = $row->jumlah;
}
			

$query = "select sum(tr_jurnald.debet-tr_jurnald.kredit) as jumlah from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal inner join ms_akun
          on tr_jurnald.kodeakun=ms_akun.kodeakun
          where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun like '5%' and tr_jurnald.kodeakun<'503'";

if($unit!='NONE')
{
   $query.="and tr_jurnalh.kodepasar = '$unit'";   
}

$data  = $this->db->query($query);
foreach($data->result() as $row){
$biaya = $row->jumlah;
}

$query = "select sum(tr_jurnald.kredit-tr_jurnald.debet) as jumlah from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal inner join ms_akun
          on tr_jurnald.kodeakun=ms_akun.kodeakun
          where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun like '4%' and tr_jurnald.kodeakun>'403'";

if($unit!='NONE')
{
   $query.="and tr_jurnalh.kodepasar = '$unit'";   
}

$data  = $this->db->query($query);
foreach($data->result() as $row){
$pendapatanL = $row->jumlah;
}

$query = "select sum(tr_jurnald.debet-tr_jurnald.kredit) as jumlah from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal inner join ms_akun
          on tr_jurnald.kodeakun=ms_akun.kodeakun
          where tr_jurnalh.tanggal between '$_tgl1' and '$_tgl2' and tr_jurnald.kodeakun like '5%' and tr_jurnald.kodeakun>'503'";

if($unit!='NONE')
{
   $query.="and tr_jurnalh.kodepasar = '$unit'";   
}

$data  = $this->db->query($query);
foreach($data->result() as $row){
$biayaL = $row->jumlah;
}


$lrj   = $pendapatan-$biaya+$pendapatanL-$biayaL;

$pdf->SetWidths(array(7,90,10,40,10,40));
$pdf->SetAligns(array('L','L','C','R','C','R'));
$pdf->setfont('Times','B',10);
$pdf->row(array('','K E W A J I B A N','','','',''));

$qjudul ="select nomor, nama  from ms_akun_kelompok where lap='N' and tipe='P' order by nomor";
$djudul = $this->db->query($qjudul);

$tot_pasiva = 0;
foreach ($djudul->result() as $rowjudul)
{
    $kelompok=$rowjudul->nomor;
     
	$pdf->setfont('Times','B',10);
	$pdf->row(array('',$rowjudul->nama,'','','',''));
	$pdf->setfont('Times','',10);

	$query =
			" select nomor, judul_lap  from ms_formatd where kode='$format' and kelompok=$kelompok order by nourut";

	$hasil = $this->db->query($query);

	
	$tot_kelompok = 0;	
	foreach ($hasil->result() as $rowheder)
	{
	   $nolap     = $rowheder->nomor;
	   $judul_lap = $rowheder->judul_lap;
	   
	   $qdetil = "select akun, jenis from ms_formatdd inner join ms_akun on ms_formatdd.akun=ms_akun.kodeakun where nomorlap=$nolap";
	   $ddetil = $this->db->query($qdetil);
	   
	   
	   $tot_rinci = 0;
	   foreach ($ddetil->result() as $rowdetil)
	   {
			$akun  = $rowdetil->akun;
			$jenis = $rowdetil->jenis;
			
			if($akun==$__akunlrberjalan)
			{
				$saldo_akhir = $lrj;				
				$tot_rinci = $lrj;
			} else
			{
				
				$query = "select sum(debet) as debet, sum(kredit) as kredit from ms_akunsaldo where kodeakun= '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
				if($unit!='NONE')
				{
				  $query.="and pasar = '$unit'";   
				}
			
				$data  = $this->db->query($query);
				foreach($data->result() as $row){
					$saldo_awal = $row->kredit-$row->debet;
				}
				
				
				$query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
				where tr_jurnalh.tanggal between '$tglawal' and '$__tgl1' and tr_jurnald.kodeakun = '$akun'";
				 
				if($unit!='NONE')
				{
					$query.="and tr_jurnalh.kodepasar = '$unit'";   
				}
			
			    $data  = $this->db->query($query);
				/*foreach($data->result() as $row){
					$debet = $row->debet;
				    $kredit= $row->kredit;
				}
				*/
				$row = array_shift($data->result_array());        
		        $debet= $row['debet'];
                $kredit= $row['kredit'];				
			

				if($jenis=='D')
				{
				  $saldo_awal = $saldo_awal + $debet - $kredit;
				} else  
				{
				  $saldo_awal = $saldo_awal - $debet + $kredit;
				}
								
				
				$query = "select sum(tr_jurnald.debet) as debet, sum(tr_jurnald.kredit) as kredit from tr_jurnald inner join tr_jurnalh on tr_jurnald.nojurnal=tr_jurnalh.nojurnal
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
			

				if($jenis=='D')
				{
				   $saldo_akhir = ($saldo_awal + $debet - $kredit);
				} else
				{
				   $saldo_akhir = ($saldo_awal - $debet + $kredit);	
				}		
								
			
                $tot_rinci   = $tot_rinci + $saldo_akhir;				
							
			}
						
	              		   
	   }
	   //if($tot_rinci!=0)
		 {
		    $pdf->row(array('','     '.$judul_lap,'',number_format($tot_rinci,0,',','.'),'',''));
			
		 }
	   
	   
		  
	   $tot_kelompok = $tot_kelompok + $tot_rinci;
	}
    $tot_pasiva=$tot_pasiva+$tot_kelompok;	
	$pdf->setfont('Times','B',10);
    $pdf->row(array('','TOTAL '.$rowjudul->nama,'',number_format($tot_kelompok,0,',','.'),'',''));
    $pdf->ln();
}

$pdf->setfont('Times','B',10);
$pdf->row(array('','KEWAJIBAN DAN EKUITAS ','',number_format($tot_pasiva,0,',','.'),'',''));
$pdf->ln();

$pdf->AliasNbPages();
$pdf->output('LAP_NERACA','I');

?>

