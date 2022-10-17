<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_suplier extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_suplier','M_suplier');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1205');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_master_suplier');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_suplier->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->vendor_id;
			$row[] = $unit->kd_lama;
			$row[] = $unit->vendor_name;
			$row[] = $unit->contact;
			$row[] = $unit->alamat;
			$row[] = $unit->phone;
						
			$row[] = '
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
					
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_suplier->count_all(),
						"recordsFiltered" => $this->M_suplier->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_suplier->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'vendor_id' => urut_transaksi('SETUP_VENDOR',10),
				'vendor_name' => $this->input->post('nama'),
				'contact' => $this->input->post('kontak'),
				'alamat' => $this->input->post('alamat'),
				'phone' => $this->input->post('telpon'),
				'fax' => $this->input->post('fax'),
				'email' => $this->input->post('email'),
				'jenis' => $this->input->post('jenis'),
				
			);
		$insert = $this->M_suplier->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'vendor_id' => $this->input->post('kode'),
				'vendor_name' => $this->input->post('nama'),
				'contact' => $this->input->post('kontak'),
				'alamat' => $this->input->post('alamat'),
				'phone' => $this->input->post('telpon'),
				'fax' => $this->input->post('fax'),
				'email' => $this->input->post('email'),
				'jenis' => $this->input->post('jenis'),
				
			);
		$this->M_suplier->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_suplier->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
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

	function ctk()
    {

        $cek    	  = '1';
        $chari   	  = '';
		$cekk         = $this->session->userdata('level');
        $unit   	  = $this->session->userdata('unit');
        $unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		if(!empty($cekk))
		{
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


		$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";
		
		$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
                <tr>
                    <td style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><br><b>LAPORAN DATA SUPPLIER</b></td>
                </tr> 
                 
                <tr>
                    <td style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

        $chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>NO.</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>KODE</b></td>
                <td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>KODE LAMA</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>NAMA SUPPLIER</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>KONTAK	</b></td>
                <td bgcolor=\"#cccccc\" width=\"30%\" align=\"center\"><b>ALAMAT</b></td>
                <td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>TELPON</b></td>
               
            </tr>
            
		</thead>";
		
		$sql="SELECT*from tbl_vendor order by kd_lama-- vendor_id";

			$query1    = $this->db->query($sql);
			$lcno        = 0;
			$vendor_id   = 0;
			$vendor_name = 0;
			$contact     = 0;
			$alamat      = 0;
			$phone       = 0;
			$kd_lama     = 0;

			
			
			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$vendor_id    = $row->vendor_id ;
				$vendor_name  = $row->vendor_name;
				$contact      = $row->contact  ;
				$alamat       = $row->alamat;
				$phone        = $row->phone;
				$kd_lama      = $row->kd_lama;

			
			
			$chari .= "<tr>
				<td align=\"center\">$lcno      </td>
				<td align=\"center\">$vendor_id</td>
				<td align=\"center\">$kd_lama  </td>
				<td align=\"left\">$vendor_name  </td>
				<td align=\"left\">$contact    </td>
				<td align=\"left\">$alamat     </td>
				<td align=\"left\">$phone   </td>
				</tr>";
				
				
			}
	    
        $chari .= "</table>";


        $data['prev'] = $chari;
		$judul          = 'LAPORAN SUPPLIER';
        switch ($cek) {
            case 0;
                echo ("<title>DATA GLOBAL SKI</title>");
                echo ($chari);
                break;

            case 1;
                $this->M_cetak->mpdf('L','A3',$judul, $chari,'KASIR-01.PDF', 10, 10, 10, 1);
                break;
        }

		}else
		{
			
			header('location:'.base_url());
			
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
