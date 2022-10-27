<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_user_profile extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen bank (CRUD master bank)
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->model('master/M_user','M_user');
		$this->load->library('session');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('username');		
		if(!empty($cek))
		{
			$query = "select * from userlogin where uidlogin = '$uid'";			   
			$data  = $this->db->query($query)->result();			   
			foreach($data as $row){
			$d['nama']   = $row->username;
			$d['email']  = $row->email;
			$d['hp']     = $row->mobilephone;
			$d['website']= $row->website;
			$d['desc']   = $row->descs;
			$d['avatar'] = $row->avatar;
            }  
			$this->session->set_flashdata("pesan", "");				
			$this->load->view('master/user/v_master_user_profil',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}

    public function savebio()
	{
	    $cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('username');		
		if(!empty($cek))
		{			
			$nama = $this->input->post('nama');
			$nohp = $this->input->post('nohp');
			$email= $this->input->post('email');
			$website= $this->input->post('website');
			$desc = $this->input->post('desc');						
			$query="update userlogin set username= '$nama', mobilephone='$nohp', email='$email', website='$website',
			        descs='$desc' where uidlogin = '$uid'";
			$this->db->query($query);
			
		} else
		{
			header('location:'.base_url());
		}
	}

    public function savepsw()
	{
	    $cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('username');		
		if(!empty($cek))
		{				      
			$passwl = trim($this->input->post('passw'));
			$passwb = trim($this->input->post('passwn'));
			
			$query="select pwdlogin as pwd from  userlogin where uidlogin = '$uid'";
			$dat=$this->db->query($query)->result();
			foreach($dat as $row){
			$pwd = $row->pwd;	
			}
			if($pwd!=md5($passwl))
			{
			    echo "not ok";
			} else
			{
				$query = "update userlogin set pwdlogin = md5('$passwb') where uidlogin = '$uid'";
				$this->db->query($query);
				echo "ok";
			}						
		} else
		{
			header('location:'.base_url());
		}
	}	
	
	public function upload()
	{
	    $cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('username');		
		if(!empty($cek))
		{				      				
			$this->load->library('upload');
			//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path'] = './assets/puser/'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size'] = '2048'; //maksimum besar file 2M
			$config['max_width']  = '1288'; //lebar maksimum 1288 px
			$config['max_height']  = '768'; //tinggi maksimu 768 px
			//$config['file_name'] = $nmfile; //nama yang terupload nantinya

			$this->upload->initialize($config);			
			
			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					
					$nf = $_FILES['filefoto']['name'];
					$this->db->query("update userlogin set avatar = '$nf' where uidlogin='$uid'");
					$query = "select * from userlogin where uidlogin = '$uid'";			   
					$data  = $this->db->query($query)->result();			   
					foreach($data as $row){
					$d['nama']   = $row->username;
					$d['email']  = $row->email;
					$d['hp']     = $row->mobilephone;
					$d['website']= $row->website;
					$d['desc']   = $row->descs;
					$d['avatar'] = $row->avatar;
					}  
					$this->session->set_flashdata("pesan", "<div class=\"col-md-12\"><div class=\"alert alert-success\" id=\"alert\">Upload gambar berhasil !!</div></div>");										
					$this->load->view('master/user/v_master_user_profil',$d);					
					
				}else{
										
					$this->session->set_flashdata("pesan", "<div class=\"col-md-12\"><div class=\"alert alert-danger\" id=\"alert\">Gagal upload gambar !!</div></div>");                    
					$query = "select * from userlogin where uidlogin = '$uid'";			   
					$data  = $this->db->query($query)->result();			   
					foreach($data as $row){
					$d['nama']   = $row->username;
					$d['email']  = $row->email;
					$d['hp']     = $row->mobilephone;
					$d['website']= $row->website;
					$d['desc']   = $row->descs;
					$d['avatar'] = $row->avatar;
					}  
					$this->load->view('master/user/v_master_user_profil',$d);					
				}
			}
	
			
		} else
		{
			header('location:'.base_url());
		}
	}
	
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */