<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_tarif2 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('M_tarif','M_tarif');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1103');
	}

	public function index() {
		$cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');
    $cabangx  = $this->input->get("cabang");
		if(!empty($cek)) {
      if ($cabangx == '' || $cabangx == null) {
        $cabang   = $unit;
        $kondisi  = "WHERE koders = '$cabang'";
      } else {
        $cabang   = $cabangx;
        $kondisi  = "WHERE koders = '$cabang'";
      }
      $data["cabang"] = $cabang;
			$data['poli']   = $this->db->get('tbl_namapos')->result();
      $data['tarif']  = $this->db->query("SELECT h.*, t.koders FROM tbl_tarifh h JOIN tbl_tarif t ON h.kodetarif=t.kodetarif $kondisi GROUP BY h.kodetarif")->result();
			$this->load->view('master/v_mastertarif', $data);
		} else {
			header('location:'.base_url());
		}			
	}

  public function v_add() {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $level = $this->session->userdata('level');
      $akses = $this->M_global->cek_menu_akses($level, 3102);
      $this->load->helper('url');
      $data['modul']    = 'TARIF';
      $data['submodul'] = 'Master Tarif';
      $data['link']     = 'Master Tarif Entri';
      $data['url']      = 'Master_tarif2';
      $data['tanggal']  = date('d-m-Y');
      $data['akses']    = $akses;
      $this->load->view('master/v_add_mastertarif', $data);
    } else {
      header('location:' . base_url());
    }
  }

  public function f_add(){
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $kodetarif  = urut_tarif("URUT_TARIF", 13);
      $tindakan   = $this->input->post("tindakan");
      $kodepos    = $this->input->post("kodepos");
      $tidakaktif = $this->input->get("tidakaktif");
      $accountno  = $this->input->post("accountno");
      $data = [
        'kodetarif' => $kodetarif,
        'tindakan' => $tindakan,
        'kodepos' => $kodepos,
        'tidakaktif' => $tidakaktif,
        'accountno' => $accountno,
      ];
      $cek = $this->db->insert("tbl_tarifh", $data);
      if($cek){
        echo json_encode(['status' => 1, 'kodetarif' => $kodetarif]);
      } else {
        echo json_encode(['status' => 0]);
      }
    } else {
      header('location:' . base_url());
    }
  }

  public function fd_add() {
    $kodetarif    = $this->input->get("kodetarif");
    $cabang       = $this->input->get("cabang");
    $keltarif     = $this->input->get("keltarif");
    $jasars       = $this->input->get("jasars");
    $jasadr       = $this->input->get("jasadr");
    $jasaperawat  = $this->input->get("jasaperawat");
    $bhp          = $this->input->get("bhp");
    $total        = $this->input->get("total");
    $data = [
      'kodetarif'     => $kodetarif,
      'koders'        => $cabang,
      'cust_id'       => $keltarif,
      'tarifrspoli'   => $jasars,
      'tarifdrpoli'   => $jasadr,
      'feemedispoli'  => $jasaperawat,
      'obatpoli'      => $bhp,
    ];
    $this->db->insert('tbl_tarif', $data);
    // echo json_encode($data);
  }

  public function fdc_add(){
    $kodetarif = $this->input->get("kodetarif");
    $kodebarang = $this->input->get("kodebarang");
    $qty = $this->input->get("qty");
    $satuan = $this->input->get("satuan");
    $harga = $this->input->get("harga");
    $jumlah = $this->input->get("jumlah");
    $data = [
      'kodetarif' => $kodetarif,
      'kodeobat' => $kodebarang,
      'qty' => $qty,
      'satuan' => $satuan,
      'harga' => $harga,
      'totalharga' => $jumlah,
    ];
    $this->db->insert('tbl_masterbhp', $data);
    // echo json_encode($data);
  }

  public function v_edit($id) {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $this->load->helper('url');
      $data['modul']      = 'TARIF';
      $data['submodul']   = 'Master Tarif';
      $data['link']       = 'Master Tarif Edit';
      $data['url']        = 'Master_tarif2';
      $data['tanggal']    = date('d-m-Y');
      $tarifh             = $this->db->get_where("tbl_tarifh", ["id" => $id])->row();
      $data['header']     = $tarifh;
      $data['detail']     = $this->db->query("SELECT * FROM tbl_tarif WHERE kodetarif = '$tarifh->kodetarif'")->result();
      $data['jumdetail']  = $this->db->query("SELECT * FROM tbl_tarif WHERE kodetarif = '$tarifh->kodetarif'")->num_rows();
      $data['bhp']        = $this->db->query("SELECT * FROM tbl_masterbhp WHERE kodetarif = '$tarifh->kodetarif'")->result();
      $data['jumbhp']     = $this->db->query("SELECT * FROM tbl_masterbhp WHERE kodetarif = '$tarifh->kodetarif'")->num_rows();
      $this->load->view('master/v_edit_mastertarif', $data);
    } else {
      header('location:' . base_url());
    }
  }

  public function f_edit() {
    $cek      = $this->session->userdata('level');
    if (!empty($cek)) {
      $kodetarif      = $this->input->post('kodetarif');
      $tindakan       = $this->input->post("tindakan");
      $kodepos        = $this->input->post("kodepos");
      $accountno      = $this->input->post("accountno");
      $tidakaktif     = $this->input->get("tidakaktif");
      $data = [
        'kodetarif'   => $kodetarif,
        'tindakan'    => $tindakan,
        'kodepos'     => $kodepos,
        'accountno'   => $accountno,
        'tidakaktif'  => $tidakaktif,
      ];
      $this->db->where("kodetarif", $kodetarif);
      $this->db->delete("tbl_tarifh");

      $this->db->where("kodetarif", $kodetarif);
      $this->db->delete("tbl_tarif");

      $this->db->where("kodetarif", $kodetarif);
      $this->db->delete("tbl_masterbhp");

      $cek = $this->db->insert("tbl_tarifh", $data);
      $cek = 1;
      if ($cek) {
        echo json_encode(['status' => 1, 'kodetarif' => $kodetarif]);
      } else {
        echo json_encode(['status' => 0]);
      }
    } else {
      header('location:' . base_url());
    }
  }

  public function delete($kodetarif) {
    $this->db->where("kodetarif", $kodetarif);
    $this->db->delete("tbl_tarifh");

    $this->db->where("kodetarif", $kodetarif);
    $this->db->delete("tbl_tarif");

    $this->db->where("kodetarif", $kodetarif);
    $this->db->delete("tbl_masterbhp");

    echo json_encode(['status' => 1]);
  }
}