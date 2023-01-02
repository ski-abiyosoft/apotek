<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lock_so extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_stockopname');
		$this->load->model('M_barang');
		$this->load->model('M_cetak');
		$this->load->model('M_param');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->load->helper('app_global');
		$this->load->model('M_KartuStock');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3309');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$cabang = $this->session->userdata('unit');
			$data['cabang'] = $cabang;
			$data['lv_user'] = $this->session->userdata('level');
			$this->load->view('inventory/v_lock_so', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function validate()
	{
		$password	= $this->input->post("password");

		if (page_permission($password)) {
			// redirect('Inventory_tso/entri');
			echo 'sukses';
		} else {
			// redirect('Inventory_tso');
			echo 'gagal';
		}
	}

	function gudang()
	{
		$str = $this->input->post('searchTerm');
		$sql = $this->db->query("SELECT depocode as id, keterangan as text from tbl_depo where (depocode like '%$str%' or keterangan like '$str%')")->result();
		echo json_encode($sql);
	}

	public function ajax_list()
	{	
		$user_level   = $this->session->userdata('user_level');
		$level        = $this->session->userdata('level');
		$userid       = $this->session->userdata('username');

		$list         = $this->db->query("SELECT * FROM ms_close_app ORDER BY koders")->result();
		$tgl          = date('Y-m-d');
		$data         = array();
		$no           = $_POST['start'];


		foreach ($list as $item) {
					$no++;
					$row = array();
					$row[] = $item->koders;
					$row[] = $item->nm_rs;
					$row[] = date('d/m/Y', strtotime($item->statustgl));
					$row[] = $item->jamm;
					$row[] = $item->jams;
					$row[] = $item->username;

					if ($item->status == 1) {
						$row[] = '<a class="btn btn-sm btn-danger" align="center" title="TERKUNCI" onclick="edit_data(' . "'" . $item->koders . "'" . ')">
						<i class="fa fa-lock"></i></a>';
					} else {
						$row[] = '<a class="btn btn-sm btn-info" title="TERBUKA" onclick="edit_data(' . "'" . $item->koders . "'" . ')">
						<i class="fa fa-unlock"></i></a>';
					}

					$data[] = $row;
			
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_stockopname->count_all2(),
			"recordsFiltered" => $this->M_stockopname->count_filtered2(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function cek_password()
	{
		$username = $this->session->userdata('username');
		$sql = $this->db->get_where('userlogin', ['uidlogin' => $username])->row_array();
		$password = md5($this->input->get('pass'));
		if ($sql['pwdlogin'] == $password) {
			echo json_encode(['status' => 1]);
		} else {
			echo json_encode(['status' => 2]);
		}
	}

	public function edit_data($id)
	{
		$data = $this->db->get_where('tbl_aposesuai', ['id' => $id])->result();
	}

	public function ajax_edit($id)
	{
		$data	= $this->db->query("SELECT * FROM ms_close_app where koders='$id' ORDER BY koders")->row();
		echo json_encode($data);
	}


	public function save()
	{
		$cek = $this->session->userdata('level');

		if (!empty($cek)) {
			$userid    = $this->session->userdata('username');
			$Kode      = $this->input->post('kode');
			$nama      = $this->input->post('nama');
			$tgl_so    = $this->input->post('tgl_so');
			$jamm      = $this->input->post('jamm');
			$jams      = $this->input->post('jams');
			$status    = $this->input->post('status');
			$nourut    = 1;

			$datad = array(
				'koders'    => $Kode,
				'nm_rs'     => $nama,
				'statustgl' => $tgl_so,
				'jamm'      => $jamm,
				'jams'      => $jams,
				'status'    => $status,
				'username'  => $userid,
			);
			
			$q_update = $this->db->update('ms_close_app', $datad, array('koders' => $Kode));

			if($q_update){
				echo json_encode(array("status" => 1,"koders" => $Kode));
			} else {
				echo json_encode(array("status" => 0,"koders" => $Kode));
			}
		} else {
			header('location:' . base_url());
		}
	}

	function close_app()
	{
		$lock   = $this->M_global->close_app();
        echo $lock;
    }
}