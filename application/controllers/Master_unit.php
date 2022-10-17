<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_unit extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_unit','M_unit');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1101');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_master_unit');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_unit->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->namars;
			$row[] = $unit->pejabat1;
			$row[] = $unit->alamat;
			$row[] = $unit->kota;
			$row[] = $unit->phone;
			$row[] = $unit->whatsapp;
			$row[] = '<img alt="" src="assets/img_user/'.$unit->avatar.'" width="100" class="rounded-circle shadow-sm img-thumbnail"/> ';
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->koders."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->koders."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_unit->count_all(),
						"recordsFiltered" => $this->M_unit->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_unit->get_by_id($id);		

		if($data->avatar==null || $data->avatar=='') {
			$url_foto = base_url('assets/img_user/foto.jpg');
		}else{
			$url_foto = base_url('assets/img_user/') . $data->avatar;
		}
		
		
		$data = array(
			'data' => $data,
			'foto_encoded' => $url_foto
		);

		echo json_encode($data);
	}

	public function upload()
	{
		$cek        = $this->session->userdata('level');
		$uid        = $this->input->get('Kode');
		$kodecab    = $this->input->post("kodecab");
		$form_data  = $this->input->post("form_data");
		if(!empty($cek))
		{				      				
			//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path']   = './assets/puser/'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size']      = '2048'; //maksimum besar file 2M
			$config['max_width']     = '1288'; //lebar maksimum 1288 px
			$config['max_height']    = '768'; //tinggi maksimu 768 px
			//$config['file_name'] = $nmfile; //nama yang terupload nantinya

			$this->load->library('upload',$config);
			$this->upload->initialize($config);	
			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					
					$nf    = $_FILES['filefoto']['name'];
					$this->db->query("UPDATE tbl_namers set avatar = '$nf' where koders='55'");
											
					echo json_encode(1);
					// $this->load->view('master/user/v_master_user_profil',$d);					
					
				}else{
										
					$this->session->set_flashdata("pesan", "<div class=\"col-md-12\"><div class=\"alert alert-danger\" id=\"alert\">Gagal upload gambar !!</div></div>");                    
					$query = "SELECT * from tbl_namers where koders='55'";
					
					
					echo json_encode(2);
					// $this->load->view('master/user/v_master_user_profil',$d);					
				}
			}
			
		} else {
			header('location:'.base_url());
		}
	}
	
	public function ajax_add()
	{
		$this->_validate();
		
		$koders   = $this->input->post('kode');
		$cek      = $this->db->query("SELECT * FROM tbl_namers WHERE koders='$koders'")->num_rows();

		if($cek>0){
			echo json_encode(array("status" => "2"));
		}else{ 

			/* LOGO */
			//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path']   = './assets/img_user/'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size']      = '2048'; //maksimum besar file 2M
			$config['max_width']     = '1288'; //lebar maksimum 1288 px
			$config['max_height']    = '768'; //tinggi maksimu 768 px
			//$config['file_name'] = $nmfile; //nama yang terupload nantinya

			$this->load->library('upload',$config);
			$this->upload->initialize($config);

			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					$gbrBukti = $this->upload->data();
					$filefoto = $gbrBukti['file_name'];
					// $filefoto    = $_FILES['filefoto']['name'];
					
				}else{
					$filefoto = 'foto.jpg';
				}
			} else {
				$error = array('error' => $this->upload->display_errors());
				var_dump($error);exit;
			}
			/*END LOGO */
			$d = $this->db->query("SELECT MAX(kodeurut) as kodeurut FROM tbl_namers")->result();
			$kodeurut   = 0;

			if($d[0]->kodeurut != null) $kodeurut = $d[0]->kodeurut + 1;

			$data = array(
				'koders'        => $this->input->post('kode'),
				'namars'        => $this->input->post('nama'),
				'alamat'        => $this->input->post('alamat'),
				'kota'          => $this->input->post('kota'),
				'phone'         => $this->input->post('telpon'),
				'whatsapp'      => $this->input->post('wa'),
				'fax'           => $this->input->post('fax'),
				'pejabat1'      => $this->input->post('pejabat1'),
				'pejabat2'      => $this->input->post('pejabat2'),
				'pejabat3'      => $this->input->post('pejabat3'),
				'pejabat4'      => $this->input->post('pejabat4'),
				'jabatan1'      => $this->input->post('jabatan1'),
				'jabatan2'      => $this->input->post('jabatan2'),
				'jabatan3'      => $this->input->post('jabatan3'),
				'jabatan4'      => $this->input->post('jabatan4'),
				'namaapotik'    => $this->input->post('namaapotek'),
				'apoteker'      => $this->input->post('apoteker'),
				'npwp'          => $this->input->post('npwp'),
				'noijin'        => $this->input->post('noijin'),
				'jabatan'       => $this->input->post('jabatan'),
				'pkpno'         => $this->input->post('pkp'),
				'tglpkp'        => $this->input->post('pkpdate'),
				'ketbank'       => $this->input->post('bank1'),
				'ketbank2'      => $this->input->post('bank2'),
				'ketbank3'      => $this->input->post('bank3'),
				'wahost'        => $this->input->post('wahost'),
				'watoken'       => $this->input->post('watoken'),
				'kodeurut'      => $kodeurut,
				'avatar'      	=> $filefoto,
				
			);
			$insert = $this->M_unit->save($data);
			history_log(0 ,'MASTER_CABANG' ,'ADD' ,$koders ,'-');
			echo json_encode(array("status" => "1"));
		}
		
		
	}

	public function ajax_update()
	{
		$this->_validate();
		/* LOGO */
			//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path']   = './assets/img_user/'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size']      = '2048'; //maksimum besar file 2M
			$config['max_width']     = '1288'; //lebar maksimum 1288 px
			$config['max_height']    = '768'; //tinggi maksimu 768 px
			//$config['file_name'] = $nmfile; //nama yang terupload nantinya

			$this->load->library('upload',$config);
			$this->upload->initialize($config);

			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					$gbrBukti = $this->upload->data();
					$filefoto = $gbrBukti['file_name'];
					// $filefoto    = $_FILES['filefoto']['name'];
					
				}else{
					$filefoto = 'foto.jpg';
				}
			} else {
				$filefoto = null;
			}
			/*END LOGO */
			$data = array(
					'koders'        => $this->input->post('kode'),
					'namars'        => $this->input->post('nama'),
					'alamat'        => $this->input->post('alamat'),
					'kota'          => $this->input->post('kota'),
					'phone'         => $this->input->post('telpon'),
					'whatsapp'      => $this->input->post('wa'),
					'fax'           => $this->input->post('fax'),
					'pejabat1'      => $this->input->post('pejabat1'),
					'pejabat2'      => $this->input->post('pejabat2'),
					'pejabat3'      => $this->input->post('pejabat3'),
					'pejabat4'      => $this->input->post('pejabat4'),
					'jabatan1'      => $this->input->post('jabatan1'),
					'jabatan2'      => $this->input->post('jabatan2'),
					'jabatan3'      => $this->input->post('jabatan3'),
					'jabatan4'      => $this->input->post('jabatan4'),
					'namaapotik'    => $this->input->post('namaapotek'),
					'apoteker'      => $this->input->post('apoteker'),
					'npwp'          => $this->input->post('npwp'),
					'noijin'        => $this->input->post('noijin'),
					'jabatan'       => $this->input->post('jabatan'),
					'pkpno'         => $this->input->post('pkp'),
					'tglpkp'        => $this->input->post('pkpdate'),
					'ketbank'       => $this->input->post('bank1'),
					'ketbank2'      => $this->input->post('bank2'),
					'ketbank3'      => $this->input->post('bank3'),
					'wahost'        => $this->input->post('wahost'),
					'watoken'       => $this->input->post('watoken'),
			);

			if($filefoto != null) {
				$data = array(
					'avatar'        => $filefoto,
				);
			}
		$this->M_unit->update(array('koders' => $this->input->post('kode')), $data);
		history_log(0 ,'MASTER_CABANG' ,'EDIT' ,$this->input->post('kode') ,'-');
		
		echo json_encode(array("status" => "1"));
	}

	public function ajax_delete($id)
	{
		$this->M_unit->delete_by_id($id);
		history_log(0 ,'MASTER_CABANG' ,'DELETE' ,$id ,'-');
		echo json_encode(array("status" => "1"));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kode') == '')
		{
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi';			
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
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

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */