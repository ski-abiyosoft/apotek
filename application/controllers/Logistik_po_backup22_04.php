<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logistik_po extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_po','M_logistik_po');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4101');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('username');		
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 4101);		
		  $this->load->helper('url');		
		  $data['modul'] = 'LOGISTIK';
		  $data['submodul'] = 'Purchase Order/PO';
		  $data['link'] = 'Purchase Order/PO';
		  $data['url'] = 'logistik_po';
		  $data['tanggal'] = date('d-m-Y');
		  $data['akses']= $akses;	
		  $this->load->view('logistik/v_logistik_po',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function add()
	{
		$cek = $this->session->userdata('username');		
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 4101);		
		  $this->load->helper('url');		
		  $data['modul'] = 'LOGISTIK';
		  $data['submodul'] = 'Purchase Order/PO';
		  $data['link'] = 'Purchase Order/PO';
		  $data['url'] = 'logistik_po';
		  $data['tanggal'] = date('d-m-Y');
		  $data['akses']= $akses;	
		  $data['nomorpo']=urut_transaksi('SETUP_APO', 19);
		  $this->load->view('logistik/v_logistik_po_add',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	public function ajax_list( $param )
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 4101);			
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_logistik_po->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_logistik_po->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			
			 if ($rd->closed=='0') {
                $status = '<span class="label label-sm label-warning">Open</span>';
			 } else {
				$status = '<span class="label label-sm label-danger">Closed</span> '; 
			 }	
	         									   
			$row   = array();
			$row[] = $rd->koders;
			$row[] = $rd->po_no;
			$row[] = date('d-m-Y',strtotime($rd->po_date));
			$row[] = $rd->vendor_id;
			$row[] = date('d-m-Y',strtotime($rd->ship_date));
			$row[] = $rd->dibuatoleh;
			$row[] = $status;
			$row[] = $rd->username;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("logistik_po/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".",'".$rd->po_no."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("logistik_po/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';				  
			} else 	
			if($akses->uedit==0 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 	{
			$row[] = '';	
			}
				
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_logistik_po->count_all( $dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_logistik_po->count_filtered( $dat[0],  $bulan, $tahun ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function getinfobarang()
	{
		$kode = $this->input->get('kode');		
		$data = $this->M_global->_data_barang_log( $kode );
		echo json_encode($data);
	}
	
	public function ajax_edit($id)
	{
		$data = $this->M_akuntansi_sa->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		
		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');
		
		
		//$nomorpo = urut_transaksi('SETUP_APO', 19);
		$nomorpo = $this->input->post('nomorbukti');
		//$this->_validate();
		$data = array(
				'koders' => $cabang,
				'po_no'  => $nomorpo,
				'ref_no' => $this->input->post('noref'),
				'po_date' => $this->input->post('tanggal'),
				'ship_date' => $this->input->post('tanggalkirim'),
				'vendor_id' => $this->input->post('supp'),
				'username' => $userid,
				'kurs' => $this->input->post('kurs'),
				'kursrate' => $this->input->post('rate'),
				
			);
		$insert = $this->db->insert('tbl_apohpolog',$data);
		
		
		$kode = $this->input->post('kode');
		$qty = $this->input->post('qty');
		$sat = $this->input->post('sat');
		$harga = $this->input->post('harga');
		$disc = $this->input->post('disc');
		$tax = $this->input->post('tax');
		$jumlah = $this->input->post('jumlah');
		
		$jumdata = count($kode);
		for($i=0;$i<=$jumdata-1;$i++)
		{
			$_harga  =  str_replace(',','',$harga[$i]);
			$_disc =  str_replace(',','',$disc[$i]);
			$_jumlah =  str_replace(',','',$jumlah[$i]);
			
			if($tax[$i]){
			  $_tax=1;	
			} else {
			  $_tax=0;	
			}
			$data_rinci = array(
			  'koders' => $cabang,
			  'po_no' => $nomorpo,
			  'kodebarang' => $kode[$i],
			  'qty_po' => $qty[$i],
			  'price_po' => $_harga,
			  'satuan' => $sat[$i],
			  'discount' => $_disc,
			  'vat' => $_tax,
			  'total' => $_jumlah,
			  
			);
			
			if($kode[$i]!=""){
				$insert_detil = $this->db->insert('tbl_apodpolog',$data_rinci);
			}
			
		}
		
		
		echo json_encode(array("status" => TRUE,"nomor" => $nomorpo));
	}
	

	public function ajax_delete()
	{
		$cabang = $this->session->userdata('unit');	
		$id = $this->input->post('id');
		$data = $this->db->get_where('tbl_apohpolog',array('id' => $id))->row();
		$nopo = $data->po_no;
		$this->db->delete('tbl_apohpolog', array('id' => $id));
		$this->db->delete('tbl_apodpolog', array('po_no' => $nopo,'koders' => $cabang));
		echo json_encode(array("status" => 1));
	}
	
	
	
    public function getentry($nomor)
	{
		if(!empty($nomor))
		{			
			$data = $this->db->select('tr_jurnal.nomor, tr_jurnal.kodeakun, ms_akun.namaakun, tr_jurnal.debet, tr_jurnal.kredit')->join('ms_akun','ms_akun.kodeakun=tr_jurnal.kodeakun')->order_by('nomor')->get_where('tr_jurnal',array('novoucher'=>$nomor))->result();			
			//$data = $this->db->get_where('tr_jurnal',array('novoucher' => $nomor))->result();
			?>			
			<div>
			<form action="#" id="formx" class="form-horizontal">                    
            <div class="form-body">
			<div id="tableContainer" class="tableContainer">
            <!--table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable tables table-stripeds table-bordereds"-->
			
							
           			
			<!--tbody class="scrollContent"-->  
			<?php							
			$i=1;
			foreach($data as $row)
			{ 
			   $id     = $row->nomor;			   
			    ?>			   
			   <tr>
			     <td align="center" width="10%">					
					<?php echo $row->kodeakun;?></a>					
				 </td>	     
				 <td width="19%"><?php echo $row->namaakun;?></td>
				 <td width="10%" align="right"><?php echo number_format($row->debet,0,',','.');?></td>
				 <td width="10%" align="right"><?php echo number_format($row->kredit,0,',','.');?></td>
				 <td width="2%"><a class="btn btn-sm btn-danger" onclick="delete_data(<?php echo $id;?>)"><i class="glyphicon glyphicon-trash"></i></a></td>
				 <td width="32%"></td>				 				 			 				 
			   </tr>
			   
			   <?php
			  $i++;
			}
			echo "</tbody>";
			echo "</table>";
			echo "</div>";
			echo "</form>";
			echo "</div>";
			
		} else
        {
		  echo "";	
		}			
	}
	
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nomorbukti') == '')
		{
			$data['inputerror'][] = 'nomorbukti';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}
		
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */