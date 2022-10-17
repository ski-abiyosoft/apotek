<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_param extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen param
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_param','M_param');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1113');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->helper('url');			
			$data['profil'] = $this->db->get('tbl_formathms')->row();
			$this->load->view('master/param/v_master_param', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
    public function update($idx)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
		
			if($idx==1)
			{
				$data = array(
				'kodecbg' => $this->input->post('_kodecbg'),
				'nama_usaha' => $this->input->post('_nama'),
				'telpon' => $this->input->post('_telpon'),
				'fax' => $this->input->post('_fax'),
				'hp' => $this->input->post('_hp'),
				'email' => $this->input->post('_email'),
				'pwdemail' => $this->input->post('_pwdemail'),
				'smtp_host' => $this->input->post('_smtp_host'),
				'smtp_port' => $this->input->post('_smtp_port'),
				'website' => $this->input->post('_website'),
				'alamat1' => $this->input->post('_alamat1'),
				'alamat2' => $this->input->post('_alamat2'),
				'kota' => $this->input->post('_kota'),
				'kodepos' => $this->input->post('_kodepos'),
				);
																
				$this->M_param->updatedata_id($data);

				
			} else			
			if($idx==3)
			{
				$data = array(
				'periode_tahun' => $this->input->post('_tahun'),
				'periode_bulan' => $this->input->post('_bulan'));
				$this->M_param->updatedata_id($data);
			} else
			if($idx==4)
			{
				$data = array(
				'akunlrberjalan' => $this->input->post('_akunlrberjalan'),
				'akunlrlalu' => $this->input->post('_akunlrlalu'),
				'akun_persediaan_transit' => $this->input->post('_akunpersediaantransit'),
				'akun_persediaan' => $this->input->post('_akunpersediaan'),
				'akun_biaya_kerugian_lain' => $this->input->post('_akunbiayakerugianlain'),
				'akun_pendapatan_lain' => $this->input->post('_akunpendapatanlain'),
				'akun_penjualan' => $this->input->post('_akunpenjualan'),
				'akun_ppn' => $this->input->post('_akunppn'),
				'akun_ongkir' => $this->input->post('_akunongkir'),
				'akun_uangmuka' => $this->input->post('_akunuangmuka'),
				'akun_hpp' => $this->input->post('_akunhpp'),
				'akun_kas' => $this->input->post('_akunkas'),
				'akun_uangmukajual' => $this->input->post('_akunuangmukajual'),
				'akun_piutang' => $this->input->post('_akunpiutang'),
				'akun_ongkirjual' => $this->input->post('_akunongkirjual'),
				'akun_retjual' => $this->input->post('_akunretjual'),
				'akun_hutang' => $this->input->post('_akunhutang'),
				'akun_hutangbeli' => $this->input->post('_akunhutangbeli'));
				
				
				$this->M_param->updatedata_id($data);
				
							
			} 
			
			}
	    }
	
	
	
	
	
	
	
}

