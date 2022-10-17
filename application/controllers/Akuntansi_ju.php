<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_ju extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akuntansi_ju','M_akuntansi_ju');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5502');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		
		
				
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5502);		
		  $this->load->helper('url');		  
		  $data['tanggal'] = date('d-m-Y');
		  $data['akses']= $akses;	
		  $this->load->view('akuntansi/v_akuntansi_ju',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	public function ajax_list( $param )
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 1202);			
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$list = $this->M_akuntansi_ju->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_akuntansi_ju->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$nobukti = $rd->nobukti;
			$koders  = $rd->koders;
			
			$total   = $this->db->query("select sum(debet) as debet,  sum(credit) as kredit from tbl_jurnald 
			where koders = '$koders' and nobukti = '$nobukti'")->row();
			
			 if ($rd->tutup=='0') {
                $status = '<span class="label label-sm label-warning">Open</span>';
			 } else {
				$status = '<span class="label label-sm label-danger">Closed</span> '; 
			 }	
	         									   
			$row   = array();
			$row[] = $rd->koders;
			$row[] = $rd->nobukti;
			$row[] = date('d-m-Y',strtotime($rd->tgljurnal));
			$row[] = $rd->keterangan;
			$row[] = $total->debet;
			$row[] = $total->kredit;
			$row[] = $status;
			$row[] = $rd->user_name;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("akuntansi_jurnal/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".",'".$rd->nobukti."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("akuntansi_jurnal/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';				  
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
						"recordsTotal" => $this->M_akuntansi_ju->count_all( $dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_akuntansi_ju->count_filtered( $dat[0],  $bulan, $tahun ),
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

	
	public function ajax_add_header()
	{
		$data = array(
				'novoucher' => $this->M_global->_Autonomor('JU'), 
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))), 
				'jenis' => $this->input->post('jenis'), 
				'statusid' => 1,
			);
		$this->db->update('tr_jurnal', $data, array('novoucher' => $this->M_global->_Autonomor('JU')));
		$this->M_global->_updatecounter1('JU');
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeakun' => $this->input->post('kodeakun'),
				'debet' => $this->input->post('debet'),
				'kredit' => $this->input->post('kredit'),
			);
		$this->M_akuntansi_sa->update(array('kodeakun' => $this->input->post('kodeakun')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('tbl_jurnalh',array('id' => $id))->row();
		$nobukti = $data->nobukti;
		$cabang  = $data->koders;
		_hapusjurnal( $nobukti, $cabang);
		echo json_encode(array("status" => 1));
	}
	
	
	public function getakun($id)	
	{
		$data = $this->db->get_where('ms_akun',array('kodeakun' => $id))->row();
		echo json_encode($data);
	}
	
	public function getnomor( $kode )	
	{
		//$data = $this->M_global->_Autonomor( $kode );
		$data = $this->db->select('')->get_where('ms_counter1',array('kdtr' => $kode))->row();
		echo json_encode($data);
	}
	
	public function gettotal($id)	
	{
		$data = $this->db->select('ifnull(sum(debet),0) as debet, ifnull(sum(kredit),0) as kredit')->get_where('tr_jurnal',array('novoucher' => $id))->row();
		echo json_encode($data);
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
	
	public function ajax_delete_all($id)
	{
		$this->db->delete('tr_jurnal', array('novoucher' => $id, 'statusid' => 0));		
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

