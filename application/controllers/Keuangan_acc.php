<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_acc extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_acc','M_keuangan_acc');
		$this->session->set_userdata('menuapp', '100');
		$this->session->set_userdata('submenuapp', '103');		
	}

	public function index()
	{
	   $cek = $this->session->userdata('level');		
	   if(!empty($cek))
	   {	
		$unit = $this->session->userdata('unit');	
		
		//if(!empty($unit))
		{
		   $query =
			  "select a.terima_register, a.terima_tanggal,  a.terima_uraian, sum(b.terimad_jumlah) as jumlah,
			  a.terima_status, a.terima_acc1, terima_acc1_user, a.terima_acc2, terima_acc2_user, terima_userentry
				from
				   tr_penerimaan a,
				   tr_penerimaand b
				where
				   a.terima_register=b.terimad_register and
				   a.terima_acc2 is null and
				   a.terima_kodepasar = '$unit'
				group by
				   a.terima_tanggal, a.terima_nomor, a.terima_uraian, a.terima_status
				order by a.terima_nomor desc";  
		} 
				
		$d['keu'] = $this->db->query($query)->result();		
		$this->load->view('keuangan/v_keuangan_acc',$d);
	   } else
	   {
		   header('location:'.base_url());
	   }		   
	}
		
		
	
	
	
	public function approv($kode)
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{						
	              $userid  = $this->session->userdata('username');
                  $tanggal = date('Y-m-d');	
				  
				  $query = "select terima_acc1_user, terima_nomor from tr_penerimaan where terima_register = '$kode'";
				  $data  = $this->db->query($query)->result();
				  foreach($data as $row){
					$nomor_bukti = $row->terima_nomor;
                    $acc_user    = $row->terima_acc1_user;					
				  }
				  
				  if (empty($acc_user)||$acc_user=="")
				  {
					$this->db->query("update tr_penerimaan set terima_status=2, terima_acc1 = '$tanggal', terima_acc1_user='$userid' where terima_register= '$kode'");					
				  } else
				  {
					$query = "select terima_uraian, terima_kasbank, terima_tanggal, terima_kodepasar, terima_nomor from tr_penerimaan where terima_register='$kode'";
					$data  = $this->db->query($query)->result();
					foreach($data as $row){
						$keterangan = $row->terima_uraian;
						$kasbank    = $row->terima_kasbank;
						$tanggal    = $row->terima_tanggal;
						$unit       = $row->terima_kodepasar;
						$nobukti    = $row->terima_nomor;
					}
					$tanggal = date('Y-m-d',strtotime($tanggal));
					$thn = date('y',strtotime($tanggal));
					$bln = date('m',strtotime($tanggal));

					$this->db->query("update tr_penerimaan set terima_status=3, terima_acc2 = '$tanggal', terima_acc2_user='$userid' where terima_register= '$kode'");
					
					
					$nojurnal     = $this->M_akuntansi_jurnal->nomor_jurnal($unit,'JK',$thn,$bln);  
					$akunkasbank  = $this->M_global->_akunbank($kasbank);
										
					
					$data = array(
						'nojurnal' => $nojurnal,
						'novoucher' => $nobukti,
						'tanggal' => $tanggal,
						'noref' => $kode,
						'keterangan' => $keterangan,
						'jenis' => 'JK',
						'kodepasar' => $unit,				
					);
					$this->M_global->input_data($data,"tr_jurnalh");
					

					//debet
					$query = "select sum(terimad_jumlah) as jumlah, count(*) as baris from tr_penerimaand where terimad_register='$kode'";
					$data  = $this->db->query($query)->result();
					foreach($data as $row){
					  $jumlah = $row->jumlah;
					  $baris  = $row->baris;
					}
					$baris++;
					
					
					$query = "insert into tr_jurnald(nojurnal, novoucher, nourut, kodeakun, debet, kredit, tanggal, keterangan)
							 values('$nojurnal','$nomor_bukti',1, '$akunkasbank', '$jumlah', 0, '$tanggal','$keterangan')";
					$this->db->query($query);
					
					
					
					$query = "insert into tr_jurnald(nojurnal, novoucher, nourut, kodeakun, debet, kredit, tanggal, keterangan)
							  select '$nojurnal','$nomor_bukti',terimad_nourut, terimad_akun, 0, terimad_jumlah,  '$tanggal',terimad_uraian from tr_penerimaand where terimad_register = '$kode'";
					$this->db->query($query);
					
					$this->M_global->update();	
                  }					
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
}

/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */