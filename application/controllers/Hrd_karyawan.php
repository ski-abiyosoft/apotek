<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrd_karyawan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_karyawan','M_karyawan');
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '701');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$d['uid'] = $this->db->get("ms_unit");
			$d['jab'] = $this->db->get("hrd_jabatan");
			$d['agm'] = $this->db->get("hrd_agama");
			$d['dep'] = $this->db->get("hrd_departemen");
			$this->load->view('hrd/v_hrd_karyawan',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_karyawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$row = array();			
			$row[] = $karyawan->nip;
			$row[] = $karyawan->nama;
			$row[] = $karyawan->noktp;
			//$row[] = $karyawan->namajabatan;
			//$row[] = $karyawan->namacabang;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$karyawan->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$karyawan->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_karyawan->count_all(),
						"recordsFiltered" => $this->M_karyawan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_karyawan->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$gapok = $this->input->post('gapok');
		$tunjanganpph = $this->input->post('tunjanganpph');
		$uanglembur = $this->input->post('lembur');
		$uangtransport = $this->input->post('transport');
		$uangmakan = $this->input->post('makan');
		$uangpulsa = $this->input->post('pulsa');
		$jkm = $this->input->post('jkm');
		$jkk = $this->input->post('jkk');
		$askes = $this->input->post('askes');
		
		$gapok  =  str_replace(',','',$gapok);
		$uanglembur  =  str_replace(',','',$uanglembur);
		$uangtransport  =  str_replace(',','',$uangtransport);
		$uangmakan  =  str_replace(',','',$uangmakan);
		$uangpulsa  =  str_replace(',','',$uangpulsa);
		$jkm  =  str_replace(',','',$jkm);
		$jkk  =  str_replace(',','',$jkk);
		$askes  =  str_replace(',','',$askes);
		
		$this->_validate();
		$data = array(
				'nip' => $this->input->post('nik'),
				'nama' => $this->input->post('nama'),
				'ptkp_id' => $this->input->post('ptkp'),
				'unit_id' => $this->input->post('cabang'),
				'user_id' => $this->session->userdata('username'),
				'noktp' => $this->input->post('noktp'),
				'jabatan_id' => $this->input->post('jabatan'),
				'departemen_id' => $this->input->post('departemen'),
				'agama_id' => $this->input->post('agama'),
				'alamat1' => $this->input->post('alamat1'),
				'alamat2' => $this->input->post('alamat2'),
				'rt' => $this->input->post('rt'),
				'rw' => $this->input->post('rw'),
				'kelurahan' => $this->input->post('kelurahan'),
				'kota' => $this->input->post('kota'),
				'hp' => $this->input->post('hp'),
				'referensi' => $this->input->post('referensi'),
				'tgllahir' => date('Y-m-d',strtotime($this->input->post('tgllahir'))),
				'tglmasuk' => date('Y-m-d',strtotime($this->input->post('tglmasuk'))),				
				'tglkeluar' => date('Y-m-d',strtotime($this->input->post('tglkeluar'))),				
				'kelamin' => $this->input->post('kelamin'),
				'warganegara' => $this->input->post('warganegara'),
				'grossup' => $this->input->post('grossup'),
				'alasanberhenti' => $this->input->post('alasankeluar'),
				'gapok' => $gapok,
				'tunjanganpph' => $tunjanganpph,
				'uanglembur' => $uanglembur,
				'uangtransport' => $uangtransport,
				'uangmakan' => $uangmakan,
				'uangpulsa' => $uangpulsa,
				'jkm' => $jkm,
				'jkk' => $jkk,
				'askes' => $askes,
				
				
			);
		$insert = $this->M_karyawan->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		//$this->_validate();
		$gapok = $this->input->post('gapok');
		$tunjanganpph = $this->input->post('tunjanganpph');
		$uanglembur = $this->input->post('lembur');
		$uangtransport = $this->input->post('transport');
		$uangmakan = $this->input->post('makan');
		$uangpulsa = $this->input->post('pulsa');
		$jkm = $this->input->post('jkm');
		$jkk = $this->input->post('jkk');
		$askes = $this->input->post('askes');
		
		$gapok  =  str_replace(',','',$gapok);
		$uanglembur  =  str_replace(',','',$uanglembur);
		$uangtransport  =  str_replace(',','',$uangtransport);
		$uangmakan  =  str_replace(',','',$uangmakan);
		$uangpulsa  =  str_replace(',','',$uangpulsa);
		$jkm  =  str_replace(',','',$jkm);
		$jkk  =  str_replace(',','',$jkk);
		$askes  =  str_replace(',','',$askes);
		
		$data = array(
				'nip' => $this->input->post('nik'),
				'nama' => $this->input->post('nama'),
				'unit_id' => $this->input->post('cabang'),
				'ptkp_id' => $this->input->post('ptkp'),
				'user_id' => $this->session->userdata('username'),
				'noktp' => $this->input->post('noktp'),
				'jabatan_id' => $this->input->post('jabatan'),
				'departemen_id' => $this->input->post('departemen'),
				'agama_id' => $this->input->post('agama'),
				'alamat1' => $this->input->post('alamat1'),
				'alamat2' => $this->input->post('alamat2'),
				'rt' => $this->input->post('rt'),
				'rw' => $this->input->post('rw'),
				'kelurahan' => $this->input->post('kelurahan'),
				'kota' => $this->input->post('kota'),
				'hp' => $this->input->post('hp'),
				'referensi' => $this->input->post('referensi'),
				'tgllahir' => date('Y-m-d',strtotime($this->input->post('tgllahir'))),
				'tglmasuk' => date('Y-m-d',strtotime($this->input->post('tglmasuk'))),
				'tglkeluar' => date('Y-m-d',strtotime($this->input->post('tglkeluar'))),
				'kelamin' => $this->input->post('kelamin'),
				'warganegara' => $this->input->post('warganegara'),
				'grossup' => $this->input->post('grossup'),
				'alasanberhenti' => $this->input->post('alasankeluar'),
				'gapok' => $gapok,
				'tunjanganpph' => $tunjanganpph,
				'uanglembur' => $uanglembur,
				'uangtransport' => $uangtransport,
				'uangmakan' => $uangmakan,
				'uangpulsa' => $uangpulsa,
				'jkm' => $jkm,
				'jkk' => $jkk,
				'askes' => $askes,
				
				
			);
		$this->M_karyawan->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_karyawan->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'nama  masih kosong';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

