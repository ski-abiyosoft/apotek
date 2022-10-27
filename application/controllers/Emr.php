<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emr extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_emr','M_emr');
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2201');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_emr');
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_kasirkonsul_add');
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri_konsul()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_biayakonsul_add');
		} else
		{
			header('location:'.base_url());
		}			
	}

    public function getinfotindakan()
	{
		$kode = $this->input->get('kode');		
		$data = $this->M_global->_data_tindakan( $kode );
		echo json_encode($data);
	}
	
	public function getdataemr()
	{
		$kode = $this->input->get('rekmed');		
		$data = $this->M_emr->_dataemr( $kode );
		echo json_encode($data);
	}
	
	public function getdataemr_detil()
	{
		$kode = $this->input->get('rekmed');
		$tgl  = $this->input->get('tanggal');
		$data = $this->M_emr->_dataemr_detil( $kode, $tgl );
		echo json_encode($data);
	}
	
	public function getuangmuka()
	{
		$kode = $this->input->get('noreg');		
		$data = $this->M_global->_data_uangmuka( $kode );
		echo json_encode($data);
	}
	
	public function getdatakonsul(){
		$noreg = $this->input->get('rekmed');		
		echo $this->M_emr->_datakonsul($noreg);
	}
	
	public function ajax_list()
	{
		$filter = '';
		$list = $this->M_emr->get_datatables( $filter );
		$data = array();
		$no = $_POST['start'];
		$jenisbayar = array('','Cash','Credit Card','Debet Card','Transfer','Online');
		
		foreach ($list as $unit) {
			$sisa = $unit->totalsemua-$unit->totalbayar;
			$data_pasien = $this->M_global->_datapasien($unit->rekmed);
			$namapas = $data_pasien->namapas;; 
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->noreg;
			$row[] = $unit->nokwitansi;
			$row[] = $namapas;
			$row[] = $unit->rekmed;			
			$row[] = date('d-m-Y',strtotime($unit->tglbayar));
			$row[] = $jenisbayar[$unit->jenisbayar];			
			$row[] = $unit->totalsemua;
			$row[] = $unit->username;
						
			if($sisa>0){			
			  $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
			} else {
			  $row[] = '';	
			}	  
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_emr->count_all( $filter ),
						"recordsFiltered" => $this->M_emr->count_filtered( $filter ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_emr->get_by_id($id);		
		echo json_encode($data);
	}
   
	public function ajax_add()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		//$tanggal = date('Y-m-d',strtotime($this->input->post('tanggal')));
		//$jam     = date('H:i:s',strtotime($this->input->post('tanggal')));
		
		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');
		
		$noreg = $this->input->post('noreg');
		$poli  = $this->input->post('reg_klinik');
		
		$this->_validate();
		$data = array(
				'koders' => $cabang,
				'noreg'  => $noreg,
				'rekmed' => $this->input->post('pasien'),
				'tglperiksa' => $tanggal,
				'jam' => $jam,
				'username' => $userid,
				
			);
		$insert = $this->db->insert('tbl_hpoli',$data);
		
		
		$kodetarif = $this->input->post('kode');
		$dokter = $this->input->post('dokter');
		$perawat = $this->input->post('perawat');
		$harga = $this->input->post('harga');
		$disc1 = $this->input->post('disc1');
		$disc2 = $this->input->post('disc2');
		$jumlah = $this->input->post('jumlah');
		
		$jumdata = count($kodetarif);
		for($i=0;$i<=$jumdata-1;$i++)
		{
			$_harga  =  str_replace(',','',$harga[$i]);
			$_disc1 =  str_replace(',','',$disc1[$i]);
			$_disc2 =  str_replace(',','',$disc2[$i]);
			$_jumlah =  str_replace(',','',$jumlah[$i]);
			
			if($dokter[$i]){
			  $_dokter= $dokter[$i];
			} else {
			  $_dokter= '';	
			}
			
			if($perawat[$i]){
			  $_perawat= $perawat[$i];
			} else {
			  $_perawat= '';	
			}
			
			$data_rinci = array(
			  'clientid' => $cabang,
			  'koders' => $cabang,
			  'noreg' => $noreg,
			  'kodetarif' => $kodetarif[$i],
			  'kodokter' => $_dokter,
			  'koperawat' => $_perawat,
			  'pos' => $poli,
			  'tarifrs' => $_harga,
			  'rsnet' => $_jumlah,
			  'diskpr' => $_disc1,
			  'diskrp' => $_disc2,
			  
			  
			);
			
			if($kodetarif[$i]!=""){
				$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);
			}
			
		}
		
		
		echo json_encode(array("status" => TRUE,"nomor" => $noreg));
	}
	
	public function ajax_add_bayar()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		
		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');
		
		$noreg = $this->input->post('noreg');
		$fakturpajak = $this->input->post('fakturpajak');
		$totalpoli = str_replace(',','',$this->input->post('tindakanrp'));
		$totalresep = str_replace(',','',$this->input->post('reseprp'));
		$adm = str_replace(',','',$this->input->post('admrp'));
		$totalrp = str_replace(',','',$this->input->post('totalrp'));
		$diskon = str_replace(',','',$this->input->post('diskonpr'));
		$diskonrp = str_replace(',','',$this->input->post('diskonrp'));
		$uangmuka = str_replace(',','',$this->input->post('uangmuka'));
		$refund = str_replace(',','',$this->input->post('refund'));
		$voucherrp = str_replace(',','',$this->input->post('voucherrp'));
		$totalnet = str_replace(',','',$this->input->post('totalnet'));
		$bayarcash = str_replace(',','',$this->input->post('totaltunairp'));
		$bayarcard = str_replace(',','',$this->input->post('totalelectronicrp'));
		$totalbayar = str_replace(',','',$this->input->post('uangpasienrp'));
		$kembali = str_replace(',','',$this->input->post('kembalirp'));
		$admcredit = 0;
		
		if(empty($fakturpajak)){
		  $fakturpajak = '';	
		}
		
		$kwitansi = urut_transaksi('URUTKWT', 19);
				
		//$this->_validate();
		$data = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'fakturpajak' => $fakturpajak,
				'rekmed' => $this->input->post('pasien'),
				'noreg' => $noreg,
				'tglbayar' => $tanggal,
				'jambayar' => $jam,
				'totalpoli' => $totalpoli,
				'totalresep' => $totalresep,
				'uangmuka' => $uangmuka,
				'adm' => $adm,
				'diskon' => $diskon,
				'diskonrp' => $diskonrp,
				'admcredit' => $admcredit,
				'totalsemua' => $totalnet,
				'bayarcash' => $bayarcash,
				'bayarcard' => $bayarcard,
				'refund' => $refund,
				'voucherrp' => $voucherrp,
				'novoucher' => $this->input->post('vouchercode'),
				'totalbayar' => $totalbayar,
				'kembali' => $kembali,
				'posbayar' => 'RAWAT_JALAN',
				'dibayaroleh' => '',
				'jenisbayar' => 1,
				'username' => $userid,
				
			);
		$insert = $this->db->insert('tbl_kasir', $data);
		
		
		$bayar_bank = $this->input->post('bayar_bank');
		if($bayar_bank){
		$bayar_tipe = $this->input->post('bayar_tipe');
		$bayar_nokartu = $this->input->post('bayar_nokartu');
		$bayar_trvalid = $this->input->post('bayar_trvalid');
		$bayar_nilai = $this->input->post('bayar_nilai');
		$bayar_adm = $this->input->post('bayar_adm');
		$bayar_jumlah = $this->input->post('bayar_total');
		$jumdata_bayar = count($bayar_bank);
		
		for($i=0;$i<=$jumdata_bayar-1;$i++)
		{
			$total  = str_replace(',','',$bayar_nilai[$i]);
			$adm    = str_replace(',','',$bayar_adm[$i]);
			$gtotal = str_replace(',','',$bayar_jumlah[$i]);
			$admrp  = ($adm/100) * $total;
			
			$data_detil   = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'tanggal' => $tanggal,
				'kodebank' => $bayar_bank[$i],
				'nocard' => $bayar_nokartu[$i],
				'cardtype' => $bayar_tipe[$i],
				'nootorisasi' => $bayar_trvalid[$i],
				'jumlahbayar' => $total,
				'admrp' => $admrp,
				'admpersen' => $adm,
				'totalcardrp' => $gtotal,
				
			);
		    if($gtotal>0)
			{
			  $insertx = $this->db->insert('tbl_kartukredit', $data_detil);
			}					
		  }		
		}
		
		$this->db->query("update tbl_regist set keluar=1 where koders = '$cabang' and noreg = '$noreg'");
		$this->db->query("update tbl_hpoli set nokwitansi='$kwitansi' where koders = '$cabang' and noreg = '$noreg'");
		
		echo json_encode(array("status" => TRUE,"nomor" => $kwitansi));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'koders' => $this->input->post('kode'),
				'namars' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'kota' => $this->input->post('kota'),
				'phone' => $this->input->post('telpon'),
			);
		$this->M_emr->update(array('koders' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	
	public function pembatalan($id)
	{
		$data = array(
		  'batal' => 1,
		  'keluar' => 1,
		  'batalkarena' => $this->input->post('alasan'),
		);
		
		$result = $this->M_emr->update(array('id' => $id), $data);
		if($result){
		  echo json_encode(array("status" => 1));
		} else {
		  echo json_encode(array("status" => 0));	
		}
	}
	


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('noreg') == '')
		{
			$data['inputerror'][] = 'pasien';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}

		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');
          $d['unit'] = '';          
          $d['master'] = $this->db->get("ms_unit")->result();
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_prn',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master'] = $this->db->get("ms_unit");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}