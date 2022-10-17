<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_us extends CI_Controller {

	
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('form_validation'); 
        $this->load->database(); 
		$this->load->model('M_pembelian');
		$this->load->model('M_supplier');
		$this->load->model('M_barang');
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '204');		
	}
	
	public function userdata($nama,$moto,$alamat,$unit,$nojurnal,$tanggal){
        $this->CI->session->set_userdata('nama', $nama);
		$this->CI->session->set_userdata('moto', $moto);
		$this->CI->session->set_userdata('alamat', $alamat);
        $this->CI->session->set_userdata('kode_unit', $unit);
		$this->CI->session->set_userdata('nojurnal', $nojurnal);
		$this->CI->session->set_userdata('tanggal', $tanggal);
				
    }

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select kode, nama from ms_unit where kode = '$unit'"; 
			} else {
			  $qp ="select kode, nama from ms_unit order by kode"; 		
			}
			
			$this->load->helper('url');		
			$d['nous']  = $this->M_global->_autonomor('KK');
			$d['unit']  = $this->db->query($qp);		
			$d['supp']  = $this->db->get('ap_supplier')->result();
            $d['item']  = $this->db->get('inv_barang')->result();			
			$d['addr']  = $this->db->get('ms_identity')->row();		
            $d['bank']  = $this->db->get('ms_bank')->result();
			$d['po']    = $this->db->get('pofile')->result();			
			
			
			
			$this->load->view('pembelian/v_pembelian_us',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function next(){
		$d['nosi']  = $this->M_global->_autonomor('SI');			
		$this->load->view('penjualan/v_penjualan_sl2',$d);
	}
	
	
	public function getlistpo( $supp )
	{
		if(!empty($supp))
		{
		    $po = $this->db->get_where('pofile',array('kodesup' => $supp))->result();	
		    ?>
			
			
			<select name="kodepo" class="form-controls input-small select2me"  onchange="getpo(this.value);">            											
			  <option value="">-- Tanpa PO ---</option>
			  <?php 
				foreach($po  as $row){?>
				<option value="<?php echo $row->kodepo;?>"><?php echo $row->kodepo;?></option>
			  <?php } ?>
			</select>
			<?php											  
			
		} else
        {
		  echo "";	
		}			
	}
	
	
	public function ajax_add_header()
	{
		$kodetrans= $this->input->post('kodetrans');
		$jumlah   = $this->input->post('jumlah');
		$ket      = $this->input->post('ket');
		$tanggal  = date('Y-m-d',strtotime($this->input->post('tanggal')));
		
		$data  = array(
			    'kodecbg'=>$this->session->userdata('unit'),
		        'kodesumber'=> $this->input->post('kodepo'),
				'kodetrans'=> $kodetrans,   
				'kodekasbank'=> $this->input->post('kasbank'),
				'tglbayar'=> date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'dbcr'=> 'C',
				'jumlah'=> $this->input->post('jumlahpo'),
				'jmlbayar'=> $this->input->post('jumlah'),
				'nokartu'=> $this->input->post('rektujuan'),
				'kode'=> $this->input->post('supp'),
				'kodeuser' => $this->session->userdata('username'),
			);
		
		$this->db->insert('kasirtrn', $data);
		
		//update kasir
		$this->db->query('update ms_kasir set saldojln= saldojln - '.$jumlah.' where kdkas = "'.$this->input->post('kasbank').'"');
		
		
		//update supplier
		$this->db->query('update ap_supplier set saldojln= if(saldojln is null, '.$jumlah.',saldojln - '.$jumlah.') where kode = "'.$this->input->post('supp').'"');
		
		//insert to apbyr
		$data  = array(
			    'kodesup'=>$this->input->post('supp'),
		        'kodebyr'=> $kodetrans,
				'tanggal'=> date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'jumlah'=> $this->input->post('jumlah'),
				'sisa'=> $this->input->post('jumlah'),
			);
		
		$this->db->insert('apbyr', $data);
		
		//updte ke filepo
		if($this->input->post('kodepo')!='')
		{
			$this->db->query('update pofile set kodetrans = "'.$kodetrans.'", uangmuka = '.$jumlah.',tgluangmuka = "'.$tanggal.'" ,ketuangmuka = "'.$ket.'" where kodepo = "'.$this->input->post('kodepo').'"');
		}
		
		//buat jurnal um
		
		if($this->input->post('kasbank')!=""){
			$akunkas = $this->db->get_where('ms_bank',array('bank_kode' => $this->input->post('kasbank')))->row()->bank_kodeakun;
			} else {
			$akunkas = "";	
			}
			
		$data=$this->M_global->_LoadProfile();
			foreach($data as $row){			
				$akunuangmuka    = $row->akun_uangmuka;
			}	
		
		
		//create jurnal
		$data_jurnal = array(
			  'novoucher' => $kodetrans,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 1,
			  'kodeakun' => $akunkas,
			  'debet' => 0,
			  'kredit' => $jumlah,
			  'keterangan' => 'UM Supplier ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
        $data_jurnal = array(
			  'novoucher' => $kodetrans,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 2,
			  'kodeakun' => $akunuangmuka,
			  'debet' => $jumlah,
			  'kredit' => 0,
			  'keterangan' => 'UM Supplier ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		
		$this->update_counter('KK');
		
		echo json_encode(array("status" => TRUE));
	}
	
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('kodeitem') == '')
		{
			$data['inputerror'][] = 'kodeitem';
			$data['error_string'][] = 'kode item belum dipilih';
			$data['status'] = FALSE;
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getakunname($kode)
	{
		if(!empty($kode))
		{			
			$query = "select namaakun from ms_akun where kodeakun = '$kode'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['namaakun'];				
			}
		} else
		{
		  echo "";	
		}
	}
	
		
	public function cetak($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  $d['nojurnal']=$param;
		  $d['unit'] = $this->session->userdata('unit');
            
		  $qjurnalh ="select * from tr_jurnalh where nojurnal = '$param'"; 	
		  $data=$this->db->query($qjurnalh);
		  foreach($data->result() as $row){
		  $d['tanggal']=$row->tanggal;
		  }					  
		  
		  $this->load->view('akuntansi/v_akuntansi_jurnal_memo',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function ajax_delete($id)
	{
		$this->M_pembelian->delete_detail_by_id($id);
		
		echo json_encode(array("status" => TRUE));
	}
	
	
	public function ajax_delete_all($id)
	{
		$this->M_pembelian->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_counter($kode){
		$this->M_global->_updatecounter1($kode);
	}
	
	function autonomor($kode){
		echo $this->M_global->_autonomor($kode);
		
	}
	
    public function getsuplier($id)
	{
		$data = $this->M_supplier->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function getpo($po)
	{   
        $data = $this->db->get_where('pofile',array('kodepo' => $po))->row();		
		echo json_encode($data);
	}
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */