<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_sa extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen coa (CRUD master coa)
	 **/
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akuntansi_sa','M_akuntansi_sa');
		$this->load->model('M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '212');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
		  $this->load->helper('url');		  
		  $level=$this->session->userdata('level');

		  $akses= $this->M_global->cek_menu_akses($level, 212);
		  $d['akses']= $akses;
		  //$data['tipeakun']= $this->db->order_by('nomor')->get('ms_akun_kelompok')->result_array();	
		  //$data['kodeakun']= $this->db->order_by('kodeakun')->get_where('ms_akun', array('kodeakun < ' => '4' ,'akuninduk != ' => ''))->result_array();	
		  
		  $tahun 		= $this->M_global->_periodetahun();
  
		  $bulan  		= $this->M_global->_periodebulan();
		  $nbulan 		= $this->M_global->_namabulan($bulan);

		  $bln 			= count($bulan) == 1 ? '0'.$bulan : $bulan;
		  $jmlhari 		= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
		  
		  $d['startdate'] 	= $tahun.'-'.$bln.'-01';
		  $d['enddate'] 	= $tahun.'-'.$bln.'-'.$jmlhari;
		  $d['vendorid'] 	= '';
		  $d['periode'] = date('d/m/Y',strtotime($d['startdate']))." - ".date('d/m/Y',strtotime($d['enddate']));

		  $d['kas_masuk'] = 0;
		  $d['kas_keluar'] = 0;
		  $d['saldo_kas'] = 0;

		  $kasperiode = $this->M_akuntansi_sa->kasperiode();
			
		  $kas_masuk = $this->M_akuntansi_sa->total_kas_masuk($d['startdate']);
		  $kas_keluar = $this->M_akuntansi_sa->total_kas_keluar($d['startdate']);
		  
		  $d['kas_masuk'] = $kas_masuk[0]->kas_bank_masuk;
		  $d['kas_keluar'] = $kas_keluar[0]->kas_bank_keluar;
		  $d['mutasi'] = $kas_masuk[0]->kas_bank_masuk - $kas_keluar[0]->kas_bank_keluar;
		  
   		  $d['keu'] = array();
		  $dk = $this->M_akuntansi_sa->getDataKasBank($kasperiode[0]->kasperiode, $d['startdate'], $d['enddate']);
		  if($dk) $d['keu'] = $dk;
		  
		  $this->load->view('akuntansi/v_akuntansi_sa', $d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	
	public function filter($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
			
			$d['startdate'] = '';
			$d['enddate'] 	= '';
			$d['vendorid'] 	= '';

			$data  = explode("~",$param);
			$jns   = $data[0];		
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d',strtotime($tgl1));
			$_tgl2 = date('Y-m-d',strtotime($tgl2));
			
			
			if(!empty($jns))
			{			
				$kasperiode = $this->M_akuntansi_sa->kasperiode();
					
				$kas_masuk = $this->M_akuntansi_sa->total_kas_masuk($kasperiode[0]->kasperiode);
				$kas_keluar = $this->M_akuntansi_sa->total_kas_keluar($kasperiode[0]->kasperiode);

				$d['kas_masuk'] = $kas_masuk[0]->kas_bank_masuk;
				$d['kas_keluar'] = $kas_keluar[0]->kas_bank_keluar;
				$d['mutasi'] = $kas_masuk[0]->kas_bank_masuk - $kas_keluar[0]->kas_bank_keluar;

				$d['keu'] = array();
				$dk = $this->M_akuntansi_sa->getDataKasBank($kasperiode[0]->kasperiode, $tgl1, $tgl2);
				if($dk) $d['keu'] = $dk;

				$d['startdate'] = $_tgl1;
				$d['enddate'] 	= $_tgl2;
		  		$d['periode'] = date('d/m/Y',strtotime($d['startdate']))." - ".date('d/m/Y',strtotime($d['enddate']));
				$level=$this->session->userdata('level');		
				$akses= $this->M_global->cek_menu_akses($level, 5301);
				$d['akses']= $akses;		  
				$this->load->view('akuntansi/v_akuntansi_sa',$d);			   
			}
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detail(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$d['accountno'] 	= '';
			$d['startdate'] 	= '';
			$d['enddate'] 		= '';
			$d['akun_kas_bank'] = '';
			$d['keu'] = array();
			$d['accountno'] = $this->input->get('accountno');
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['akun_kas_bank'] = urldecode($this->input->get('akun_kas_bank'));
			$data_kas = $this->M_akuntansi_sa->getDataKasBank($this->M_akuntansi_sa->kasperiode()[0]->kasperiode, $d['startdate'], $d['enddate']);
			$filtered_data_kas = array_values(array_filter($data_kas, function ($item) use ($d) {
				return $item->accountno == $d['accountno'];
			}));
			$d['saldo_awal'] = $filtered_data_kas[0]->saldo_awal;
			$d['saldo_akhir'] = $filtered_data_kas[0]->saldo_akhir;
			
			$keu = $this->M_akuntansi_sa->getDetailDataKasBank($d['accountno'], $d['startdate'], $d['enddate']);
			if($keu != '' || $keu != null) $d['keu'] = $keu;
			
			// print_r($keu);
			$this->load->view('akuntansi/v_akuntansi_sa_detail',$d);			   
		} else
		{
			header('location:'.base_url());
		}
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		//$akses= $this->M_global->cek_menu_akses($level, 212);	
		$bulan = $this->M_global->_periodebulan();
	    $tahun = $this->M_global->_periodetahun();
		$list = $this->M_akuntansi_sa->get_datatables( $bulan, $tahun );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$row   = array();
			$row[] = $rd->accountno;
			$row[] = $rd->acname;
			$row[] = $rd->debet;
			$row[] = $rd->credit;
			
			//add html for action
			//if($akses->uedit==1 && $akses->udel==1)
			{
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		    } 
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_akuntansi_sa->count_all(),
						"recordsFiltered" => $this->M_akuntansi_sa->count_filtered( $bulan, $tahun ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_akuntansi_sa->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'accountno' => $this->input->post('kodeakun'),
				'debet' => $this->input->post('debet'),
				'credit' => $this->input->post('kredit'),
				'tahun' => $this->M_global->_periodetahun(),
				'bulan' => $this->M_global->_periodebulan(),
				'koders' =>  $this->session->userdata('unit'),
				);
		$insert = $this->M_akuntansi_sa->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'accountno' => $this->input->post('kodeakun'),
				'debet' => $this->input->post('debet'),
				'credit' => $this->input->post('kredit'),
			);
		$this->M_akuntansi_sa->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_akuntansi_sa->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kodeakun') == '')
		{
			$data['inputerror'][] = 'kodeakun';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('debet') == '')
		{
			$data['inputerror'][] = 'debet';
			$data['error_string'][] = 'Tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kredit') == '')
		{
			$data['inputerror'][] = 'kredit';
			$data['error_string'][] = 'Tidak boleh kosong';
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
		  
		    $nama_usaha =  $this->config->item('nama_perusahaan');
			$motto = $this->config->item('motto');
			$alamat =$this->config->item('alamat_perusahaan');
		    $bulan = $this->M_global->_periodebulan();
	        $tahun = $this->M_global->_periodetahun();
			$unit  = '';
		
		    $this->db->select('ms_akun.kodeakun, ms_akun.namaakun, ms_akunsaldo.debet, ms_akunsaldo.kredit, ms_akunsaldo.id')->from('ms_akun');
		    $this->db->join('ms_akunsaldo','ms_akunsaldo.kodeakun=ms_akun.kodeakun','left');
		    $this->db->where(array('ms_akunsaldo.tahun' => $tahun,'ms_akunsaldo.bulan' => $bulan));
			$saldoawal = $this->db->get()->result();
		
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($unit);
			$pdf->setsubjudul('');
			$pdf->setjudul('SALDO AWAL '.$this->M_global->_namabulan($bulan).'  '.$tahun);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('NO','KODE PERKIRAAN','NAMA','DEBET','KREDIT');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','L','L','R','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tdb = 0;
            $tkr = 0;			
			foreach($saldoawal as $db)
			{
			  $tdb += $db->debet;
			  $tkr += $db->kredit;
			  $pdf->row(array($nourut, $db->kodeakun, $db->namaakun, number_format($db->debet,'2',',','.'),  number_format($db->kredit,'2',',','.')));
			  $nourut++;
			}
			$pdf->setfont('Times','B',10);
			$pdf->SetWidths(array(125,30,30));
			$pdf->SetAligns(array('C','R','R'));
            $pdf->row(array('TOTAL', number_format($tdb,'2',',','.'),  number_format($tkr,'2',',','.')));

			$pdf->AliasNbPages();
			$pdf->output('saldoakun.PDF','I');


		  
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			// Akuntansi_sa/export?accountno='.($accountno).'&startdate='.$startdate.'&enddate='.$enddate.'&akun_kas_bank='.$akun_kas_bank
			
			$accountno = $this->input->get('accountno');
			$startdate = $this->input->get('startdate');
			$enddate   = $this->input->get('enddate');
			$akun_kas_bank = urldecode($this->input->get('akun_kas_bank'));
			
			$keu = $this->M_akuntansi_sa->getDetailDataKasBank($accountno, $startdate, $enddate);
			$d['keu'] = array();
			if($keu != '' || $keu != null) $d['keu'] = $keu;
			
			// $this->load->view('penjualan/v_detailPiutang_exp',$d);

			$periode = date('d-m-Y', strtotime($startdate))." s.d. ".date('d-m-Y', strtotime($enddate));

			$judul = "Kas & Bank";
			$chari = "";
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
					<thead class='breadcrumb'>
					<tr>
						<td colspan='8' style='text-align: center'>Mutasi Kas & Bank</td>
					</tr>
					<tr>
						<td colspan='8' style='text-align: center'>Akun $akun_kas_bank</td>
					</tr>
					<tr>
						<td colspan='8' style='text-align: center'>Periode $periode</td>
					</tr>
					</table>
					";

			$chari .= "<br/>";
			
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>";
			$chari .="<thead class='breadcrumb'>
                        <tr>
                            <th style='text-align: center'>Tanggal</th>
                            <th style='text-align: center'>No Bukti</th>
                            <th style='text-align: center'>Cek/Giro</th>
                            <th style='text-align: center'>Kelompok</th>
                            <th style='text-align: center'>Ketarangan</th>
                            <th style='text-align: center'>Kas Masuk</th>
                            <th style='text-align: center'>Kas Keluar</th>
                            <th style='text-align: center'>Saldo Kas</th>
                        </tr>
                    </thead>
                    <tbody>";
				
				$tanggal = '';
				$kasmasuk = 0;
				$kaskeluar = 0;
				$saldokas = 0;
				foreach($d['keu'] as $row)
				{                       
			
					$tanggal = date('d-m-Y',strtotime($row->tanggal)); 
					$kasmasuk = number_format($row->kasmasuk,0,'.',','); 
					$kaskeluar = number_format($row->kaskeluar,0,'.',','); 
					$saldokas = number_format($row->saldokas,0,'.',','); 
					
					$chari .= "<tr>
									<td align='center'>$tanggal</td>
									<td align='center'>$row->nobukti</td>
									<td align='center'>$row->cekgiro</td>
									<td align='center'>$row->kelompok</td>
									<td align='center'>$row->keterangan</td>
									<td align='right'>$kasmasuk</td>
									<td align='right'>$kaskeluar</td>
									<td align='right'>$saldokas</td>
								</tr>";

				} 
			$chari .= "</tbody>
					</table>";

			// echo $chari;

			// if($id == 1){
			// 	$this->M_cetak->mpdf('P','A4',$judul, $chari,'Piutang.PDF', 10, 10, 10, 1);
				
			// } else {
				header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=kas_dan_bank.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
			// }
		
		} else
		{
			header('location:'.base_url());
		}
	}


	public function export2()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		    $nama_usaha =  $this->config->item('nama_perusahaan');
			$motto = $this->config->item('motto');
			$alamat =$this->config->item('alamat_perusahaan');
		    $bulan = $this->M_global->_periodebulan();
	        $tahun = $this->M_global->_periodetahun();
			$unit  = '';
		    
		    $this->db->select('ms_akun.kodeakun, ms_akun.namaakun, ms_akunsaldo.debet, ms_akunsaldo.kredit, ms_akunsaldo.id')->from('ms_akun');
		    $this->db->join('ms_akunsaldo','ms_akunsaldo.kodeakun=ms_akun.kodeakun','left');
		    $this->db->where(array('ms_akunsaldo.tahun' => $tahun,'ms_akunsaldo.bulan' => $bulan));
			$saldoawal = $this->db->get()->result();
		  
		     header("Content-type: application/vnd-ms-excel");
			 header("Content-Disposition: attachment; filename=saldoawal.xls");
			 header("Pragma: no-cache");
			 header("Expires: 0");
			?>
			<h2><?php echo $nama_usaha;?></h2>
			<h4>SALDO AWAL  <?php echo $this->M_global->_namabulan($bulan).'  '.$tahun;?> </h4>
			<table border="1" >
				<thead>
					 <tr>
						 <th style="text-align: center">Kode Perkiraan</th>
						 <th style="text-align: center">Nama</th>
						 <th style="text-align: center">Debet</th>
						 <th style="text-align: center">Kredit</th>
					 </tr>
				 </thead>
				 <tbody>
				 <?php
				   foreach($saldoawal  as $db) { ?>
					 <tr>
						 <td><?php echo $db->kodeakun;?></td>
						 <td><?php echo $db->namaakun;?></td>
						 <td><?php echo $db->debet;?></td>
						 <td><?php echo $db->kredit;?></td>
					 </tr>
				 <?php } ?>
				 </tbody>
			</table>
           <?php
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */