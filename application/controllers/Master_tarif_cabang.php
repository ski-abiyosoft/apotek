<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_tarif_cabang extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_tarif_cabang','M_tarif_cabang');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1103');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['poli'] = $this->db->get('tbl_namapos')->result();
			$cabang = $this->session->userdata('unit');				
			if($cabang==""){
			  $cabang = "DPS";	
			}
			$data['cabang' ] = $cabang;
			$this->load->view('master/v_master_tarif_cabang', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function index_tarif( $cabang )
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['poli'] = $this->db->get('tbl_namapos')->result();			
			$data['cabang' ] = $cabang;
			$this->load->view('master/v_master_tarif_cabang', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
		

    function cek_master( $cabang ){
		$data = $this->db->query("select count(*) as jumlah from tbl_tarif where koders = '$cabang'")->row();
		if($data->jumlah<1){
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh");
		} else {
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh
			where kodetarif not in(select kodetarif from tbl_tarif where koders = '$cabang')
			");
		}
		
	}
	public function ajax_list( $cabang )
	{
		$this->cek_master( $cabang );
		$list = $this->M_tarif_cabang->get_datatables( $cabang );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->kodetarif;
			$row[] = $unit->tindakan;
			$row[] = $unit->kodepos;
			$row[] = angka_rp($unit->tarifrspoli,2);
			$row[] = angka_rp($unit->tarifdrpoli,2);
			$row[] = angka_rp($unit->obatpoli,2);
			$row[] = angka_rp($unit->feemedispoli,2);
			$row[] = angka_rp($unit->cost,2);
						
			$row[] = 
			     '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>				  
				  ';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_tarif_cabang->count_all(),
						"recordsFiltered" => $this->M_tarif_cabang->count_filtered( $cabang ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_tarif_cabang->get_by_id($id);	        
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kodetarif' => $this->input->post('kode'),
				'tindakan' => $this->input->post('nama'),
				'kodepos' => $this->input->post('poli'),    				
			);
		$insert = $this->M_tarif_cabang->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		//$this->_validate();
		
		$poli    =  str_replace(',','',$this->input->post('klinik'));
		$dokter  =  str_replace(',','',$this->input->post('dokter'));
		$obat    =  str_replace(',','',$this->input->post('bhp'));
		$perawat =  str_replace(',','',$this->input->post('perawat'));
		
		$total   = $poli+$dokter+$obat+$perawat;
		$data = array(
				'kodetarif' => $this->input->post('kode'),
				'tarifrspoli' => $poli,
				'tarifdrpoli' => $dokter,
				'obatpoli' => $obat,
				'feemedispoli' => $perawat,
				'cost' => $total,
				
			);
		$this->M_tarif_cabang->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_tarif_cabang->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('poli') == '')
		{
			$data['inputerror'][] = 'poli';
			$data['error_string'][] = 'Poli harus diisi';
			$data['status'] = FALSE;
		}
		
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
	
	public function daftar_bhp()
	{
		$kode = $this->input->get('kode');
		
		$datatarif = $this->db->query("
	    select tbl_masterbhp.*,tbl_barang.namabarang from tbl_masterbhp
	    inner join tbl_barang on tbl_masterbhp.kodeobat=tbl_barang.kodebarang 
	    where tbl_masterbhp.kodetarif='$kode'")->result();
	  
		$hasil = 
		'<table class="table">
						  <th>No.</th>
						  <th>Kode</th>
						  <th>Nama Barang</th>
						  <th>Qty</th>
						  <th>Satuan</th>
						  <th style="text-align:right">Harga</th>
						  <th style="text-align:right">Total</th>';
		$no = 1 ;
		$tot = 0;					  
		foreach($datatarif as $row){
			 $hasil .= '
			 
			 <tr>
			 <td>'.$no.'</td>
			 <td>'.$row->kodeobat.'</td>
			 <td>'.$row->namabarang.'</td>
			 <td>'.$row->qty.'</td>
			 <td>'.$row->satuan.'</td>
			 <td style="text-align:right">'.angka_rp($row->harga,2).'</td>
			 <td style="text-align:right">'.angka_rp($row->totalharga,2).'</td>
			 </tr>

			 ';
			 $no++;
			 $tot += $row->totalharga;
			
		}		
        $hasil .=
        '<tfoot>
		  <td colspan="6"><b>Total</b></td>
		  <td style="text-align:right"><b>'.angka_rp($tot,2).'</b></td>
        </tfoot>';  		
		$hasil .=				  
		'</table>';
		
		echo  $hasil;
						
	}
	
	
	
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */