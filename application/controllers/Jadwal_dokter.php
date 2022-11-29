<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_dokter extends CI_Controller
{

  public function __construct()
	{
		parent::__construct();

		$this->session->set_userdata('menuapp', '2000');
    // set link aktived
		$this->session->set_userdata('submenuapp', '2102');
  }

  public function index()
  {
    $cek = $this->session->userdata('username');
		if(!empty($cek)){
      $koders = $this->session->userdata('unit');
      $data = [
        'koders' => $koders,
        'rs' => $this->db->get('tbl_namers')->result(),
        'namapos' => $this->db->get('tbl_namapos')->result(),
        'namers' => $this->db->get('tbl_namers')->result(),
      ];
      $this->load->view('JadwalDokter/v_jadwal_dokter', $data);
		} else {
      header('location:'.base_url());
		}	
  }

  public function get_dokter(){
    $unit = $this->input->post('unit');
    $data = $this->db->query('select d.kodokter, d.nadokter from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where p.kopoli = "'.$unit.'" and koders = "'.$this->session->userdata('unit').'"')->result();
    echo json_encode($data, JSON_PRETTY_PRINT);
  }

  public function tambah_data(){
    $koders   = $this->input->post('koders');
    $kodokter = $this->input->post('kodokter');
    $unit     = $this->input->post('unit');
    $tanggal  = $this->input->post('tanggal');
    $shift    = $this->input->post('shift');
    $dari     = $this->input->post('dari');
    $sampai   = $this->input->post('sampai');
    $jeniss    = $this->input->post('jeniss');
    if($jeniss == 0){
      $jenis = 1;
    } else {
      $jenis = $jeniss;
    }
    $quota    = $this->input->post('quota');

    $data = [
      'koders' => $koders, 'kodokter' => $kodokter, 'tglpraktek' => $tanggal, 'shif' => $shift, 'jammulai' => $dari, 'jamselesai' => $sampai, 'qouta_pasien' => $quota, 'jenipraktek' => $jenis
    ];

    if($tanggal != null){
      if($jenis == 1){
        $cek = $this->db->query('select * from tbl_drpraktek where koders = "'.$koders.'" and kodokter = "'.$kodokter.'" and shif = "'.$shift.'"')->row_array();
        if($cek){
          $this->db->set('jammulai', $dari);
          $this->db->set('jamselesai', $sampai);
          $this->db->set('qouta_pasien', $quota);
          $this->db->set('jenipraktek', $jenis);
          $this->db->set('shif', $shift);
          $this->db->where('tglpraktek', $tanggal);
          $this->db->where('koders', $koders);
          $this->db->where('kodokter', $kodokter);
          $this->db->update('tbl_drpraktek');
          echo json_encode(['status' => 2]);
        } else {
          $this->db->insert('tbl_drpraktek', $data);
          echo json_encode(['status' => 0]);
        }
      } else if ($jenis == 2 || $jenis == 3) {
          $this->db->insert('tbl_drpraktek', $data);
          echo json_encode(['status' => 0]);
      } else {
        echo json_encode(['status' => 1]);
      }
    } else {
      echo json_encode(['status' => 1]);
    }
  }

  public function hapusdata(){
    $id = $this->input->post('id');
    $this->db->delete("tbl_drpraktek", ["id"=>$id]);
    echo json_encode(['status'=>1]);
  }

  public function read(){
    $id = $this->input->post('id');
    $table = $this->db->query('select * from tbl_drpraktek where koders = "'.$this->session->userdata('unit').'"')->result();
    foreach($table as $t){
      $sql = $this->db->get_where('tbl_dokter', ['kodokter' => $t->kodokter])->row_array();
      $color = '';
      if($t->jenipraktek == 2){
        $color = '#FFD700';
      } else if($t->jenipraktek == 3){
        $color = '#32CD32';
      }
      if($t->jenipraktek == 2){
        $data[] = [
          'title' => 'Cuti',
          'id' => $t->id,
          'start' => $t->tglpraktek,
          'color' => $color,
        ];
      } else if($t->jenipraktek == 3){
        $data[] = [
          'title' => 'Libur',
          'id' => $t->id,
          'start' => $t->tglpraktek,
          'color' => $color,
        ];
      } else {
        $data[] = [
          'title' => 'Jam '.$t->jammulai.'-'.$t->jamselesai,
          'id' => $t->id,
          'start' => $t->tglpraktek,
          'color' => $color,
        ];
      }
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
  }

  public function info(){
    $id = $this->input->post('id');
    $table = $this->db->query('select * from tbl_drpraktek where koders = "'.$this->session->userdata('unit').'" and id = "'.$id.'"')->result();
    foreach($table as $t){
      $sql = $this->db->get_where('tbl_dokter', ['kodokter' => $t->kodokter])->row_array();
      if($t->jenipraktek == 2){
        $data = [
          'id' => $t->id,
          'status' => 'Cuti',
          'nadokter' => $sql['nadokter'],
          'nosip' => $sql['nosip'],
          'hp' => $sql['hp'],
        ];
      } else if($t->jenipraktek == 3){
        $data = [
          'id' => $t->id,
          'status' => 'Libur',
          'nadokter' => $sql['nadokter'],
          'nosip' => $sql['nosip'],
          'hp' => $sql['hp'],
        ];
      } else {
        $data = [
          'id' => $t->id,
          'status' => 'Jam '.$t->jammulai.'-'.$t->jamselesai,
          'nadokter' => $sql['nadokter'],
          'nosip' => $sql['nosip'],
          'hp' => $sql['hp'],
        ];
      }
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
  }
}
?>