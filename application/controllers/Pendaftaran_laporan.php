<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_laporan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->model('M_dashboard','M_dashboard');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2104');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['cabang']= $this->db->get('tbl_namers')->result();
            
			$this->load->view('klinik/v_pendaftaran_lap',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak()
	{
		ini_set('memory_limit', '-1');
		$cek    = $this->session->userdata('level');
		$unit   = $this->session->userdata('unit');
		$rkm    = $this->input->get('rekmed');
		$bl     = $this->input->get('bl');
		$pb     = $this->input->get('pb');
		if(!empty($cek)){
			$profile       = $this->M_global->_LoadProfileLap();
			$nama_usaha    = $profile->nama_usaha;
			$motto         = '';
			$alamat        = '';
			$namaunit      = $this->M_global->_namaunit($unit);
			$idlp          = $this->input->get('idlap');
			$tgl1          = $this->input->get('tgl1');
			$tgl2          = $this->input->get('tgl2');
			$dokter        = $this->input->get('dokter');
			$unitx         = $this->input->get('unit');
			$cab           = $this->input->get('cab');
			//   $unit  = $this->input->get('unit');
			$_tgl1         = date('Y-m-d',strtotime($tgl1));
			$_tgl2         = date('Y-m-d',strtotime($tgl2));
			$_peri         = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
			//$_peri1= 'Dari . '.date('d',strtotime($tgl2)).' sampai '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  	if($idlp==101){	
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2)); 
				if($dokter !='null'){
					$kondokter = "and tbl_regist.kodokter = '$dokter'";	
				} else {
					$kondokter = "";
				}
				if($unitx !='null' && $unitx !=null){
					$konunit = "and tbl_regist.kodepos = '$unitx'";	
				} else {
					$konunit = "";
				}
				$query = "SELECT tbl_regist.kodepos AS poli, tbl_regist.*, tbl_pasien.* from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where tglmasuk between '$_tgl1' and '$_tgl2' and tbl_regist.koders = '$unit' $kondokter $konunit ";
				// if($unit !='null'){
				// $query.= "and tbl_pasien.koders = '$unit'";	
				// }
				$query.= "ORDER BY tbl_regist.kodepos, tglmasuk,namapas";
				$lap = $this->db->query($query)->result();
				$pdf=new simkeu_rpt();
				$pdf->setID($nama_usaha,$motto,$alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('01 LAPORAN PENDAFTARAN PASIEN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L","A4");   
				$pdf->setsize("L","A4");
				if($unitx != ''){
					$pdf->SetWidths(array(32,20,20,40,40,25,30, 20, 20,35));
					$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
					$judul=array('Noreg ','Rek-med','Tgl. Masuk','Nama Pasien','Alamat','Phone','Dokter', 'Poli', 'No. Antri','Status Bayar');
				} else {
					$pdf->SetWidths(array(32,20,20,50,50,25,30,20,35));
					$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
					$judul=array('Noreg ','Rek-med','Tgl. Masuk','Nama Pasien','Alamat','Phone','Dokter','No. Antri','Status Bayar');
				}
				$pdf->setfont('Arial','B',10);
				$pdf->row($judul);
				if($unitx != ''){
					$pdf->SetWidths(array(32,20,20,40,40,25,30, 20, 20,35));
					$pdf->SetAligns(array('C','C','C','L','L','L','L','L','L','L'));
				} else {
					$pdf->SetWidths(array(32,20,20,50,50,25,30,20,35));
					$pdf->SetAligns(array('C','C','C','C','L','L','L','L','L'));
				}
				$pdf->setfont('Arial','',9);
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				if($unitx == ''){
					foreach($lap as $db){
						if($db->antrino < 2){
							$antrian = $db->antrino." Baru";
						} else {
							$antrian = $db->antrino." Lama";
						}
						$noreg = $db->noreg;
						$dpembayaran = $this->db->query("SELECT * from tbl_kasir where noreg = '$noreg'")->row();
						if($db->batal == "1"){
							$pembayaran	= "BATAL";
						} else {
							if($dpembayaran){
							$pembayaran = $dpembayaran->jenisbayar;     				
							if($pembayaran==1){
								$pembayaran= 'TUNAI';	
							} else if($pembayaran==2){
								$pembayaran= 'CREDIT CARD';	
							} else if($pembayaran==3){
								$pembayaran= 'DEBET CARD';	
							} else if($pembayaran==4){
								$pembayaran= 'TRANSFER';	
							} else if($pembayaran==5){
								$pembayaran= 'ONLINE';	
							}
							
							$pembayaran .= '/'.$dpembayaran->nokwitansi;
							} else {
							$pembayaran = 'batal';   
							}
						}
							$datadokter = data_master('tbl_dokter',array('kodokter' => $db->kodokter));
							if($datadokter){
								$nadokter = $datadokter->nadokter; 
						} else {
							$nadokter = '';
						}
							$pdf->row(
							array(
								$db->noreg, 
								$db->rekmed, 
								tanggal($db->tglmasuk), 
								$db->namapas, 
								$db->alamat, 
								$db->handphone, 
								$nadokter,
								$db->antrino,
								$pembayaran
								)
						);
					}
				} else {
					foreach($lap as $db){
						if($db->antrino < 2){
							$antrian = $db->antrino." Baru";
						} else {
							$antrian = $db->antrino." Lama";
						}
						$noreg = $db->noreg;
						$dpembayaran = $this->db->query("SELECT * from tbl_kasir where noreg = '$noreg'")->row();
						if($db->batal == "1"){
							$pembayaran	= "BATAL";
						} else {
							if($dpembayaran){
							$pembayaran = $dpembayaran->jenisbayar;     				
							if($pembayaran==1){
								$pembayaran= 'TUNAI';	
							} else if($pembayaran==2){
								$pembayaran= 'CREDIT CARD';	
							} else if($pembayaran==3){
								$pembayaran= 'DEBET CARD';	
							} else if($pembayaran==4){
								$pembayaran= 'TRANSFER';	
							} else if($pembayaran==5){
								$pembayaran= 'ONLINE';	
							}
							$pembayaran .= '/'.$dpembayaran->nokwitansi;
							} else {
							$pembayaran = '-';   
							}
						}
							$datadokter = data_master('tbl_dokter',array('kodokter' => $db->kodokter));
							if($datadokter){
								$nadokter = $datadokter->nadokter; 
						} else {
							$nadokter = '';
						}
							$pdf->row(
							array(
								$db->noreg, 
								$db->rekmed, 
								tanggal($db->tglmasuk), 
								$db->namapas, 
								$db->alamat, 
								$db->handphone, 
								$nadokter,
								$db->poli,
								$db->antrino,
								$pembayaran
								)
						);
					}
				}
				$pdf->SetTitle('01 LAPORAN PENDAFTARAN PASIEN');
				$pdf->AliasNbPages();
				$pdf->output('REGISTRASI-01.PDF','I');
		  	} else if($idlp==102){
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2)); 
            		$query = "SELECT tbl_namapos.namapost, sum(case when jenispas='PAS1' then 1 else 0 end) as pu, sum(case when jenispas<>'PAS1' then 1 else 0 end) as jaminan, sum(case when baru=1 then 1 else 0 end) as baru, sum(case when baru=0 then 1 else 0 end) as lama, count(*) as total from tbl_regist inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos where tbl_regist.tglmasuk between '$_tgl1' and '$_tgl2' and tbl_regist.koders = '$unit' AND LENGTH(tbl_regist.rekmed) > 9 and tbl_regist.kodepos = '$unitx'";
            		if($dokter !='null'){
					$query.= " and tbl_regist.kodokter = '$dokter'";	
				}
				$query.= " group by tbl_regist.kodepos";
				$lap = $this->db->query($query)->result();
				$pdf=new simkeu_rpt();
				$pdf->setID($nama_usaha,$motto,$alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('02 LAPORAN PENDAFTARAN PASIEN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("P","A4");   
				$pdf->setsize("P","A4");
				$pdf->SetWidths(array(10,60,22,22,22,22,25));
				$pdf->SetAligns(array('C','C','C','C','C','C','C'));
				$judul=array('No.','Nama Poli/Pos ','Jml. Pasien Perorangan','Jml. Pasien Jaminan','Kunjungan Baru','Kunjungan Lama','Total');
				$pdf->setfont('Arial','B',10);
				$pdf->row($judul);
				$pdf->SetWidths(array(10,60,22,22,22,22,25));
				$pdf->SetAligns(array('C','L','R','R','R','R','R'));
				$pdf->setfont('Arial','',9);
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				$nourut = 1;
				foreach($lap as $db){
					$pdf->row(
						array(
							$nourut, 
							$db->namapost, 
							$db->pu, 
							$db->jaminan, 
							$db->baru, 
							$db->lama, 
							$db->total,
						)
					);
					$nourut++;
				}
            		$pdf->SetTitle('02 LAPORAN PENDAFTARAN PASIEN');
				$pdf->AliasNbPages();
				$pdf->output('REGISTRASI-02.PDF','I');
			} else if($idlp==104){	
				$cabang = $this->session->userdata('unit');
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2)); 
				if($rkm != ''){
					if($rkm == 1){
						if($bl != ''){
							if($bl == 1){
								if($pb == 1){
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=1 AND r.keluar=0 order by p.rekmed, baru asc limit 1000";
								} else if($pb == 0){
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=1 AND r.keluar=1 order by p.rekmed, baru asc limit 1000";
								}
							} else if($bl == 2) {
								if($pb == 1){
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=0 AND r.keluar=0 order by p.rekmed, baru asc limit 1000";
								} else {
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=0 AND r.keluar=1 order by p.rekmed, baru asc limit 1000";
								}
							} else {
								$query = "SELECT * from tbl_pasien where koders = '$cabang' order by rekmed asc limit 1000";
							}
						} else {
							$query = "SELECT * from tbl_pasien where koders = '$cabang' order by rekmed limit asc 1000";
						}
					} else {
						if($bl != ''){
							if($bl == 1){
								if($pb == 1){
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=1 AND r.keluar=0 order by p.namapas, baru asc limit 1000";
								} else {
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=1 AND r.keluar=1 order by p.namapas, baru asc limit 1000";
								}
							} else if($bl == 2){
								if($pb == 1){
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=0 AND r.keluar=0 order by p.namapas, baru asc limit 1000";
								} else {
									$query = "SELECT p.*, r.baru, r.keluar from tbl_pasien p JOIN tbl_regist r ON p.rekmed=r.rekmed where p.koders = '$cabang' AND r.baru=0 AND r.keluar=1 order by p.namapas, baru asc limit 1000";
								}
							} else {
								$query = "SELECT * from tbl_pasien where koders = '$cabang' order by namapas asc limit 1000";
							}
						} else {
							$query = "SELECT * from tbl_pasien where koders = '$cabang' order by namapas limit 1000";
						}
					}
				} else if($rkm == ''){
					$query = "SELECT * from tbl_pasien where koders = '$cabang' limit 1000";
				}
				// $query = "SELECT * from tbl_pasien where koders = '$cabang' order by rekmed limit 1000";
				$lap = $this->db->query($query)->result();
				$pdf=new simkeu_rpt();
				$pdf->setID($nama_usaha,$motto,$alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('04 DAFTAR SEMUA PASIEN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("P","A4");   
				$pdf->setsize("P","A4");
				$pdf->SetWidths(array(10,20,50,22,50,22,22));
				$pdf->SetAligns(array('C','C','C','C','C','C','C'));
				$judul=array('No.','Rek-Med ','Nama Pasien','Tgl. Lahir','Alamat','Phone','Email');
				$pdf->setfont('Arial','B',10);
				$pdf->row($judul);
				$pdf->SetWidths(array(10,20,50,22,50,22,22));
				$pdf->SetAligns(array('C','C','L','C','L','L','L'));
				$pdf->setfont('Arial','',9);
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				$nourut = 1;
				foreach($lap as $db){
					$pdf->row(
						array(
							$nourut, 
							$db->rekmed, 
							$db->namapas, 
							tanggal($db->tgllahir), 
							$db->alamat, 
							$db->handphone, 
							$db->email,
						)
					);
					$nourut++;
				}
				$pdf->SetTitle('04 DAFTAR SEMUA PASIEN');
				$pdf->AliasNbPages();
				$pdf->output('REGISTRASI-04.PDF','I');
		  	} else if($idlp==105){	
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2));
				// $query0 = "SELECT distinct tbl_regist.kodokter, tbl_dokter.nadokter from tbl_regist inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter where tbl_regist.tglmasuk between '$_tgl1' and '$_tgl2' and tbl_regist.koders = '$unit'";
				// $query0 = "SELECT d.kodokter, d.nadokter FROM tbl_dokter d WHERE d.kodokter IN (SELECT r.kodokter FROM tbl_regist r WHERE r.tglmasuk BETWEEN '$_tgl1' AND '$_tgl2' AND r.koders='$unit' OR r.kodepos = '$unitx' OR r.kodokter = '$dokter' order by r.kodokter ASC)";
				$query0 = "SELECT distinct d.kodokter, d.nadokter FROM tbl_dokter d WHERE d.kodokter IN (SELECT r.kodokter FROM tbl_regist r WHERE r.tglmasuk BETWEEN '$_tgl1' AND '$_tgl2' AND r.koders='$unit' OR r.kodokter = '$dokter' OR r.kodepos = '$unitx' order by r.kodokter ASC)";
				// if($dokter !='null'){
				// 	$query0.= "and tbl_regist.kodokter = '$dokter'";	
				// }
				// if($unit !='null'){
				// $query0.= "and tbl_regist.kodepos = '$unit'";	
				// }
				// $query0.= "order by tbl_regist.kodokter";
				$lap0 = $this->db->query($query0)->result();
				$pdf=new simkeu_rpt();
				$pdf->setID($nama_usaha,$motto,$alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('05 LAPORAN PASIEN PER DOKTER PRAKTEK');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L","A4");   
				$pdf->setsize("L","A4");
				$pdf->SetWidths(array(10,32,25,22,40,50,50,40));
				$pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
				$judul=array('No.','Noreg ','Rek-med','Tgl. Masuk','Nama Pasien','Poli','Penjamin','No. Kwitansi');
				$pdf->setfont('Arial','B',10);
				$pdf->row($judul);
				$pdf->SetWidths(array(10,32,25,22,40,50,50,40));
				$pdf->SetAligns(array('C','C','C','C','L','L','L'));
				$pdf->setfont('Arial','',9);
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				foreach($lap0 as $db0){
					$dokter = $db0->kodokter;
					$pdf->ln();
					$pdf->SetWidths(array(0));
					$pdf->SetAligns(array('L'));
					$pdf->cell(0,5,$db0->nadokter,0,1);
					$pdf->SetWidths(array(10,32,25,22,40,50,50,40));
					$pdf->SetAligns(array('C','C','C','C','L','L','L'));
					// $query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where and tbl_regist.kodepos = '$unit' and tglmasuk between '$_tgl1' and '$_tgl2' and tbl_regist.kodokter = '$dokter' order by tbl_regist.kodepos, tbl_regist.noreg";
					// if($dokter != ''){
					// 	if($unitx != ''){
					// 		$query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where tbl_regist.kodokter = '$dokter' AND tbl_regist.kodepos = '$unitx' AND tglmasuk BETWEEN '$_tgl1' and '$_tgl2' AND tbl_regist.koders = '$unit'";
					// 	} else {
					// 		$query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where tbl_regist.kodokter = '$dokter' AND tglmasuk BETWEEN '$_tgl1' and '$_tgl2' AND tbl_regist.koders = '$unit'";
					// 	}
					// } else if($dokter == ''){
					// 	if($unitx != ''){
					// 		$query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where tbl_regist.kodepos = '$unitx' AND tglmasuk BETWEEN '$_tgl1' and '$_tgl2' AND tbl_regist.koders = '$unit'";
					// 	} else {
					// 		$query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where tglmasuk BETWEEN '$_tgl1' and '$_tgl2' AND tbl_regist.koders = '$unit'";
					// 	}

					// }
					$query = "SELECT tbl_regist.*, tbl_pasien.*, tbl_namapos.namapost, tbl_penjamin.cust_nama as penjamin from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos left join tbl_penjamin on tbl_regist.cust_id=tbl_penjamin.cust_id where tbl_regist.kodokter = '$dokter' OR tbl_regist.kodepos = '$unitx' AND tglmasuk BETWEEN '$_tgl1' and '$_tgl2' AND tbl_regist.koders = '$unit'";
					// if($unit !='null'){
					// 	$query.= "and tbl_regist.kodepos = '$unit'";	
					// }
					// $query.= "order by tbl_regist.kodepos, tbl_regist.noreg";
			    		$lap = $this->db->query($query)->result();  
					$nourut = 1;
					foreach($lap as $db){
						$noreg = $db->noreg;
						$dpembayaran = $this->db->query("SELECT * from tbl_kasir where noreg = '$noreg'")->row();
				  		if($dpembayaran){
							$pembayaran = $dpembayaran->jenisbayar;     				
							if($pembayaran==1){
								$pembayaran= 'TUNAI';	
							} else if($pembayaran==2){
								$pembayaran= 'CREDIT CARD';	
							} else if($pembayaran==3){
								$pembayaran= 'DEBET CARD';	
							} else if($pembayaran==4){
								$pembayaran= 'TRANSFER';	
							} else if($pembayaran==5){
								$pembayaran= 'ONLINE';	
							} else {
								$pembayaran = 'undefine';
							}
							$pembayaran = $dpembayaran->nokwitansi;
				  		} else {
							$pembayaran = '-';   
				  		}
						$pdf->row(
							array(
								$nourut, 
								$db->noreg, 
								$db->rekmed, 
								tanggal($db->tglmasuk), 
								$db->namapas, 
								$db->namapost, 
								$db->penjamin,
								$pembayaran
							)
						);
						$nourut++;
					}
					$pdf->ln();
				}
				$pdf->SetTitle('05 LAPORAN PASIEN PER DOKTER PRAKTEK');
				$pdf->AliasNbPages();
				$pdf->output('REGISTRASI-01.PDF','I');
		  	} else if ($idlp==106){	
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2)); 
            		$query = "SELECT tbl_regist.*, tbl_pasien.* from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where tglmasuk between '$_tgl1' and '$_tgl2' and tbl_regist.koders = '$unit'";
            		if($dokter !='null'){
					$query.= "and tbl_regist.kodokter = '$dokter'";	
				}
				// if($unit !='null'){
				// $query.= "and tbl_regist.kodepos = '$unit'";	
				// }
				$query.= "order by tbl_regist.kodepos, tbl_regist.noreg";
				$lap = $this->db->query($query)->result();
				$pdf=new simkeu_rpt();
				$pdf->setID($nama_usaha,$motto,$alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('06 LAPORAN REKAP PASIEN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L","A4");   
				$pdf->setsize("L","A4");
				$pdf->SetWidths(array(10,32,20,20,40,50,25,30,20,35));
				$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
				$judul=array('No.','Noreg ','Rek-med','Tgl. Masuk','Nama Pasien','Alamat','Phone','Dokter','Antri','Status Bayar');
				$pdf->setfont('Arial','B',10);
				$pdf->row($judul);
				$pdf->SetWidths(array(10,32,20,20,40,50,25,30,20,35));
				$pdf->SetAligns(array('C','C','C','C','L','L','L','L','L'));
				$pdf->setfont('Arial','',9);
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				$nourut = 1;
				foreach($lap as $db){
			  		$noreg = $db->noreg;
					$dpembayaran = $this->db->query("SELECT * from tbl_kasir where noreg = '$noreg'")->row();
			  		if($dpembayaran){
						$pembayaran = $dpembayaran->jenisbayar;     				
						if($pembayaran==1){
							$pembayaran= 'TUNAI';	
						} else if($pembayaran==2){
							$pembayaran= 'CREDIT CARD';	
						} else if($pembayaran==3){
							$pembayaran= 'DEBET CARD';	
						} else if($pembayaran==4){
							$pembayaran= 'TRANSFER';	
						} else if($pembayaran==5){
							$pembayaran= 'ONLINE';	
						}
						$pembayaran .= '/'.$dpembayaran->nokwitansi;
			  		} else {
						$pembayaran = '-';   
					}
			  		$datadokter = data_master('tbl_dokter',array('kodokter' => $db->kodokter));
			  		if($datadokter){
				 		$nadokter = $datadokter->nadokter; 
					} else {
						$nadokter = '';
					}
					$pdf->row(
						array(
							$nourut, 
							$db->noreg, 
							$db->rekmed, 
							tanggal($db->tglmasuk), 
							$db->namapas, 
							$db->alamat, 
							$db->handphone, 
							$nadokter,
							$db->antrino.($db->baru==1?' Baru ':' Lama '),
							$pembayaran
						)
					);
					$nourut++;
				}
            		$pdf->SetTitle('06 LAPORAN REKAP PASIEN');
				$pdf->AliasNbPages();
				$pdf->output('REGISTRASI-01.PDF','I');
		  	} else if($idlp==103){	
				$bulan = date('n',strtotime($tgl1)); 
				$tahun = date('Y',strtotime($tgl2));  	 	 		  
				$this->grafik_kunjungan();
          	}		  		  
		} else {
			header('location:'.base_url());
		}
	}
	
	public function grafik_kunjungan()
	{
		$this->load->view('template');
		$data['report']    = $this->M_dashboard->report();
		$data['tahun']     = 'Periode Tahun '.date('Y');
		// $this->load->view('template',$data);
		$this->load->view('klinik/v_grafik_kunjungan_pasien', $data);
	}
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/bank/v_master_bank_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */