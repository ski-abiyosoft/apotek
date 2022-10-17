<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrd_gaji extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen coa (CRUD master coa)
	 **/
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_gaji','M_gaji');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '709');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		
		
				
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 222);		
		  $this->load->helper('url');		  
		  $data['nojurnal'] = $this->M_global->_Autonomor('JU');
		  $data['tanggal'] = date('d-m-Y');
		  $data['jurnal'] = $this->db->get_where('tr_jurnal',array('novoucher' => $this->M_global->_Autonomor('JU')))->result();
		  $data['jenis'] = $this->db->get_where('ms_jurnal',array('jurnal_kode' => 'JU'));
		  $data['tipeakun']= $this->db->order_by('nomor')->get('ms_akun_kelompok')->result_array();	
		  $data['kodeakun']= $this->db->order_by('kodeakun')->get_where('ms_akun', array('akuninduk != ' => ''))->result();	
		  $data['akses']= $akses;	
		  $this->load->view('hrd/v_hrd_gaji',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function add()
	{
		$cek = $this->session->userdata('level');		
						
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $data['tanggal'] = date('Y-m-d');
		  $data['bulan'] = date('n');
		  $data['tahun'] = date('Y');
		  $this->load->view('hrd/v_hrd_gaji_add',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function pph()
	{
		$cek = $this->session->userdata('level');						
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $data['tanggal'] = date('Y-m-d');
		  $data['bulan'] = date('n');
		  $data['tahun'] = date('Y');
		  $this->load->view('hrd/v_hrd_gaji_pph',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
					
			$qheader ="select hrd_transpayroll.*, hrd_karyawan.nip, hrd_karyawan.nama as nama_karyawan
			from hrd_transpayroll inner join hrd_karyawan on hrd_transpayroll.id_karyawan=hrd_karyawan.id
			where hrd_transpayroll.id = '$nomor'"; 		
			$d['header'] = $this->db->query($qheader)->row();
			$d['nomor'] = $nomor;
            
			$this->load->view('hrd/v_hrd_gaji_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function ajax_list( $param )
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 222);			
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$list = $this->M_gaji->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = $dat[1];
		    $tahun  = $dat[2];
		    $list = $this->M_gaji->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$idk = $rd->id_karyawan;
			$karyawan = $this->db->query("select * from hrd_karyawan where id = '$idk'")->row();
			if($karyawan){
			  $nip = $karyawan->nip;
              $nama = $karyawan->nama;			  
			} else {
			  $nip = $nama = '';	
			}
			$no++;
			$row   = array();
			$row[] = $rd->bulan;
			$row[] = $rd->tahun;
			$row[] = date('d-m-Y',strtotime($rd->tglpay));
			$row[] = $nip;
			$row[] = $nama;
			$row[] = $rd->thp;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("hrd_gaji/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("hrd_gaji/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';				  
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
						"recordsTotal" => $this->M_gaji->count_all( $dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_gaji->count_filtered( $dat[0],  $bulan, $tahun ),
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
		        //'novoucher' => $this->input->post('nomorbukti'), 
				'novoucher' => $this->M_global->_Autonomor('JU'), 
				'jenis' => $this->input->post('jenis'), 
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))), 
				'keterangan' => $this->input->post('keterangan'), 
				'kodeakun' => $this->input->post('kodeakun'),
				'debet' => $this->input->post('debet'),
				'kredit' => $this->input->post('kredit'),
				'userid' => $this->session->userdata('username'),				
				);
		$insert = $this->db->insert('tr_jurnal', $data);
		echo json_encode(array("status" => TRUE));
	}
	
	
	public function ajax_update()
	{
		$this->_validate();
		$gapok = $this->input->post('gapok');
		$tunjanganpph = $this->input->post('tunjanganpph');
		$uanglembur = $this->input->post('lembur');
		$uangtransport = $this->input->post('transport');
		$uangpulsa = $this->input->post('pulsa');
		$uangmakan = $this->input->post('makan');
		$jkm = $this->input->post('jkm');
		$jkk = $this->input->post('jkk');
		$pph = $this->input->post('pph');
		$askes = $this->input->post('askes');
		$potongan = $this->input->post('potongan');
		$bonus = $this->input->post('bonus');
		
		$gapok  =  str_replace(',','',$gapok);
		$tunjanganpph  =  str_replace(',','',$tunjanganpph);
		$uanglembur  =  str_replace(',','',$uanglembur);
		$uangtransport  =  str_replace(',','',$uangtransport);
		$uangpulsa  =  str_replace(',','',$uangpulsa);
		$uangmakan  =  str_replace(',','',$uangmakan);
		$jkm  =  str_replace(',','',$jkm);
		$jkk  =  str_replace(',','',$jkk);
		$askes  =  str_replace(',','',$askes);
		$potongan  =  str_replace(',','',$potongan);
		$pph  =  str_replace(',','',$pph);
		$bonus  =  str_replace(',','',$bonus);
		$thp    = $gapok+$tunjanganpph+$uanglembur+$uangtransport+$uangpulsa+$uangmakan-$jkm-$jkk-$askes-$potongan-$pph+$bonus;
		
		$data = array(
				'gapok' => $gapok,
				'tunjanganpph' => $tunjanganpph,
				'uanglembur' => $uanglembur,
				'uangtransport' => $uangtransport,
				'uangpulsa' => $uangpulsa,
				'uangmakan' => $uangmakan,
				'jkm' => $jkm,
				'jkk' => $jkk,
				'askes' => $askes,
				'potongan' => $potongan,
				'thp'   => $thp,
				'pph'   => $pph,
				'bonus' => $bonus,
				
			);
		$this->M_gaji->update(array('id' => $this->input->post('nomor')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->db->delete('hrd_transpayroll', array('id' => $id));
		echo json_encode(array("status" => TRUE));
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

		if($this->input->post('gapok') == '')
		{
			$data['inputerror'][] = 'gapok';
			$data['error_string'][] = 'gapok  harus diisi';
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
	
	public function hitunggaji()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
             $bulan       = trim($this->input->post('bulan'));
			 $tahun       = trim($this->input->post('tahun'));
			 $tanggal     = trim($this->input->post('tanggal'));
			   
			 $userid         = $this->session->userdata('userid');		
			 $tgl_proses     = date("Y-m-d");

           
			 $query = "select count(*) as jumdata from hrd_transpayroll where tahun = '$tahun' and bulan= '$bulan'";			 
			 $data  = $this->db->query($query)->row();

			 if ($data->jumdata>0)
			 {
				echo "not ok";
			 } else
			 {
			 
			   
				$query = "select * from hrd_karyawan order by id";											
				$hasil = $this->db->query($query)->result();
				foreach ($hasil as $row)
				{
				   $gapok = $row->gapok;
				   $uangpulsa = $row->uangpulsa;
				   $uangmakan = $row->uangmakan;
				   $jkm = $row->jkm;
				   $jkk = $row->jkk;
				   $askes = $row->askes;
				   
				   $thp = $gapok+$uangpulsa+$uangmakan-$jkm-$jkk-$akses;
				   $id_karyawan = $row->id;
				   
				   if ($thp!=0)
				   {
					$qgaji = "insert into hrd_transpayroll(tahun, bulan, tglpay, id_karyawan, 
					gapok, uangmakan, uangpulsa, jkm, jkk, askes, thp,
					userid, tglentry)
					values('$tahun','$bulan','$tanggal','$id_karyawan', '$gapok', '$uangmakan', '$uangpulsa',
					'$jkm','$jkk','$askes','$thp','$userid','$tgl_proses')";

					$this->db->query($qgaji);
				   }
				 }
				  
				 echo "ok";

					
				  
		   }		  
		  
		}
		else
		{			
			header('location:'.base_url());		
		}
	}	
	
	public function hitungpph()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
             $bulan       = trim($this->input->post('bulan'));
			 $tahun       = trim($this->input->post('tahun'));
			 $tanggal     = trim($this->input->post('tanggal'));
			   
			 $userid         = $this->session->userdata('userid');		
			 $tgl_proses     = date("Y-m-d");

           
			 $query = "select count(*) as jumdata from hrd_transpayroll where tahun = '$tahun' and bulan= '$bulan'";			 
			 $data  = $this->db->query($query)->row();

			 if ($data->jumdata<1)
			 {
				echo "not ok";
			 } else
			 {
			 
			   
				$query = "select * from hrd_transpayroll where tahun='$tahun' and bulan='$bulan'	order by id";											
				$hasil = $this->db->query($query)->result();
				foreach ($hasil as $row)
				{
				    $id_karyawan = $row->id_karyawan;
					$id = $row->id;
				    //
					$karyawan = $this->db->get_where('hrd_karyawan', array('id' => $id_karyawan))->row();
					if($karyawan){
					  $id_ptkp = $karyawan->ptkp_id;
					  $grossup = $karyawan->grossup;
					  
                   	  $ptkp = $this->db->get_where('ms_ptkp', array('id_ptkp' => $id_ptkp, 'tahun' => $tahun))->row();				  
					  if($ptkp){
						 $nptkp = $ptkp->ptkp; 
					  } else {
						 $nptkp = 0; 
					  }
					}
					
				    $gapok  =  $row->gapok;
					
					$uanglembur  =  $row->uanglembur;
					$uangtransport  =  $row->uangtransport;
					$uangpulsa  =  $row->uangpulsa;
					$uangmakan  =  $row->uangmakan;
					$jkm  =  $row->jkm;
					$jkk  =  $row->jkk;
					$askes  =  $row->askes;
					$potongan  =  $row->potongan;
					$bonus  =  $row->bonus;
					
					
					//hitung pph
					
					$_gaji = $gapok * 12;
					$_tunjangan_lain = $uanglembur+$uangtransport+$uangpulsa+$uangmakan;
					$_bonus = $bonus;
					
					$_bruto = $_gaji+$_tunjangan_lain+$_bonus;
					$_iuran_pensiun = $jkm+$jkk+$askes;
					
					//biaya jabatan
					
					if($_gaji*0.05<6000000){
					  $_biaya_jabatan = $_gaji*0.05;
					} else {
					  $_biaya_jabatan = 6000000;	
					}  
					
					$_netto = $_bruto - $_iuran_pensiun - $_biaya_jabatan;
					
					$_pkp = $_netto - $nptkp;
					
					$_pkp = floor($_pkp);
					
					if ($_pkp<=50000000){
					   $_pph21tahun = $_pkp*0.05;
					}elseif (($_pkp>50000000)and($_pkp<=250000000)){
					   $_pph21tahun=(50000000*0.05)+(($_pkp-50000000)*0.15);
					}elseif (($_pkp>250000000)and($_pkp<=500000000)){ 
					   $_pph21tahun=(50000000*0.05)+(200000000*0.15)+(($_pkp-250000000)*0.25);
  					}else {
					   $_pph21tahun=(50000000*0.05)+(200000000*0.15)+(250000000*0.25)+(($_pkp-500000000)*0.3);
					}   
				   
				    $pph = $_pph21tahun/12;
					
					if($grossup=='Y'){
					  $tunjanganpph  =  $pph;   	
					} else {
					  $tunjanganpph  = 0;	
					}
        
					
					$thp    = $gapok+$tunjanganpph+$uanglembur+$uangtransport+$uangpulsa+$uangmakan-$jkm-$jkk-$askes-$potongan-$pph+$bonus;
					
					$data = array(
							'gapok' => $gapok,
							'tunjanganpph' => $tunjanganpph,
							'uanglembur' => $uanglembur,
							'uangtransport' => $uangtransport,
							'uangpulsa' => $uangpulsa,
							'uangmakan' => $uangmakan,
							'jkm' => $jkm,
							'jkk' => $jkk,
							'askes' => $askes,
							'potongan' => $potongan,
							'thp'   => $thp,
							'pph'   => $pph,
							'bonus' => $bonus,
							
						);
					$this->M_gaji->update(array('id' => $id), $data);
		
				   
				   }
				 }
				  
				 echo "ok";

			  
		  
		}
		else
		{			
			header('location:'.base_url());		
		}
	}	
	
	
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */