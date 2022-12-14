<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_keluar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_keluar','M_keuangan_keluar');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5106');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5106);		
		  $this->load->helper('url');		  
		  $data['tanggal'] = date('d-m-Y');
		  $data['akses']= $akses;	
		  $this->load->view('keuangan/v_keuangan_keluar',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri()
	{
	    $cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}			
			$d['unit']  = $this->db->query($qp);
			
			$d['jenis_pembayaran'] = get_jenis_bayar();
			
			$this->load->view('keuangan/v_keuangan_keluar_add',$d);
			} else
		{
			header('location:'.base_url());
		}		
	}
	
	public function edit( $id )
	{
	    $cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}
						
			$d['register']= $id;			
			$d['unit']    = $this->db->query($qp);		
			
			// $header = $this->db->query("select tbl_hbayar.*,tbl_accounting.acname, tbl_namers.namars 
			// from tbl_hbayar inner join tbl_accounting on tbl_hbayar.accountno=tbl_accounting.accountno
			// left outer join tbl_namers on tbl_hbayar.koders=tbl_namers.koders
			// where tbl_hbayar.id = '$id'")->row();		
			
			$header = $this->db->query("SELECT h.*, a.`acname`, ac.`namadep`
				FROM tbl_hbayar h 
					LEFT JOIN tbl_accounting a ON h.accountno = a.`accountno`
					LEFT JOIN tbl_accostcentre ac ON h.`depid` = ac.`depid`
				WHERE h.id = '$id'")->row();	
			

			$d['header']  =  $header;
			$nomorbukti   =  $header->bayarno;
			
			
			$d['detil']   = $this->db->query("select tbl_dbayar.*, tbl_accounting.acname from tbl_dbayar 
			inner join tbl_accounting on tbl_dbayar.accountno=tbl_accounting.accountno
			where tbl_dbayar.bayarno = '$nomorbukti' ")->result();		
			
			$d['jumdata']   = $this->db->query("select tbl_dbayar.*, tbl_accounting.acname from tbl_dbayar 
			inner join tbl_accounting on tbl_dbayar.accountno=tbl_accounting.accountno
			where tbl_dbayar.bayarno = '$nomorbukti' ")->num_rows();	
			
			$d['jenis_pembayaran'] = $this->M_keuangan_keluar->getJenisPembayaran();

			$this->load->view('keuangan/v_keuangan_keluar_edit',$d);
			} else
		{
			header('location:'.base_url());
		}		
	}
	
	public function ajax_list( $param )
	{
		$level=$this->session->userdata('level');		
		//$akses= $this->M_global->cek_menu_akses($level, 906);			
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = date('n');
			$tahun = date('Y');
			$list = $this->M_keuangan_keluar->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_keuangan_keluar->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
					   
			$row   = array();
			$row[] = $rd->koders;
			$row[] = $rd->username;
			$row[] = $rd->bayarno;
			$row[] = date('d-m-Y',strtotime($rd->tglbayar));
			$row[] = $rd->accountno;
			$row[] = $rd->keterangan;
			$row[] = $rd->jmbayar;
			
			// <a class="btn btn-sm btn-warning" target="_blank" href="'.base_url("keuangan_keluar/cetak/".$rd->id."").'" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
				  
			//if($akses->uedit==1 && $akses->udel==1)
			{
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("keuangan_keluar/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".",'".$rd->bayarno."'".')"><i class="glyphicon glyphicon-trash"></i> </a>
				  <a class="btn btn-sm btn-warning" id="'.$rd->id.'" href="#report" onclick="id_cetak('."'".$rd->bayarno."'".')" data-toggle="modal"><i class="glyphicon glyphicon-print"></i></a>';
			} 
			
			/*else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("keuangan_keluar/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';				  
			} else 	
			if($akses->uedit==0 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 	{
			$row[] = '';	
			}
			*/
			
				
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_keuangan_keluar->count_all( $dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_keuangan_keluar->count_filtered( $dat[0],  $bulan, $tahun ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$nomor = $this->db->get_where('tbl_hbayar',array('id' => $id))->row()->bayarno;
		$this->db->delete('tbl_hbayar', array('id' => $id));
		$this->db->delete('tbl_dbayar', array('bayarno' => $nomor));
		
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
		
		
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak($id)
	{
		// $cek    	  = '1';
		//-----
        $chari   	  = '';
		$username     = $this->session->userdata('username');
		$cekk         = $this->session->userdata('level');
        $unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		
		if(!empty($cekk))
		{
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];

			$kota      = str_replace('Kota ', '', $kop['kota']);

		$sql = "SELECT *
			FROM tbl_hbayar
			WHERE bayarno = '$id';
		";
		 

		$query1    = $this->db->query($sql);
    		foreach ($query1->result() as $r);

		$jmbayar = angka_rp($r->jmbayar,0);
		$terbilang = ucwords($this->M_global->terbilang($r->jmbayar))." Rupiah";

		$date = date_create($r->tglbayar);
		$tglbayar = date_format($date,"d-m-Y");


		$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:11px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr >
				<td colspan=\"2\" rowspan=\"6\" align=\"center\" style='border-top:1px solid black;border-left:1px solid black;border-bottom:1px solid black;'>
					<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"75\" height=\"75\"  
					style='padding:-1px;' />
				</td>

				<td colspan=\"18\" >
					<b>
						<tr>
							<td colspan=7 style=\"font-size:20px;border-bottom: none;\"
							style='border-top: 1px solid black;'><b>$namars</b></td>
							<td colspan=11 style=\"font-size:13px;border-bottom: none;\" align=\"center\" style='border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;'><b>BUKTI PENGELUARAN KAS</b></td>
						</tr>
						<tr>
							<td colspan=7 style=\"font-size:13px;\" style='border-right: 1px solid black;'>$alamat</td>
							<td colspan=11 style=\"font-size:13px;\" style='border-bottom: 1px solid black;border-right: 1px solid black;' align=\"center\"><b>UNIT KASIR SENTRAL</b></td>
						</tr>
						<tr>
							<td colspan=7 style=\"font-size:13px;\" style='border-right: 1px solid black;'>$alamat2</td>
							<td colspan=3 style=\"font-size:13px;\" style='border-left: 1px solid black;'>&nbsp;No. Pembayaran</td>
							<td colspan=1 style=\"font-size:13px;\" align=\"center\">:</td>
							<td colspan=7 style=\"font-size:13px;\" style='border-right: 1px solid black;'>$r->bayarno &nbsp;</td>
						</tr>
						<tr>
							<td colspan=7 style=\"font-size:13px;\" style='border-right: 1px solid black;'>Wa :$whatsapp    Telp :$phone </td>
							<td colspan=3 style=\"font-size:13px;\" style='border-left: 1px solid black;'>&nbsp;Tanggal</td>
							<td colspan=1 style=\"font-size:13px;\" align=\"center\">:</td>
							<td colspan=7 style=\"font-size:13px;\" style='border-right: 1px solid black;'>$tglbayar</td>
						</tr>
						<tr>
							<td colspan=7 style=\"font-size:13px;\" style='border-bottom: 1px solid black;'>No. NPWP : $npwp</td>
							<td colspan=3 style=\"font-size:13px;\" style='border-bottom: 1px solid black;border-left: 1px solid black;'>&nbsp;Kas/Bank</td>
							<td colspan=1 style=\"font-size:13px;\" style='border-bottom: 1px solid black;' align=\"center\">:</td>
							<td colspan=7 style=\"font-size:13px;\" style='border-bottom: 1px solid black;border-right: 1px solid black;'>$r->accountno</td>
						</tr>
					</b>

				</td>
			</tr> 
			 
			
			</table>";
		
		// $chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
        //         <thead>
		// 		<tr>
        //             <td colspan=\"14\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
        //         </tr> 
        //         <tr>
        //             <td colspan=\"14\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>BUKTI PENGELUARAN KAS UNIT KASIR SENTRAL</b></td>
        //         </tr> 
                 
        //         <tr>
        //             <td colspan=\"14\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
        //         </tr>
                
        //         </table>";

		

		$chari .= "<br>";
		$chari .= "<table style=\"border-collapse:collapse;font-size:11px;\">";
		$chari .= "<tr>
			<td align=\"left\" style='border:none;'>Untuk Pembayaran</td>
			<td align=\"center\" style='border:none;'> : </td>
			<td align=\"left\" style='border:none;'><b>$r->keterangan</b></td>
		</tr>";
		$chari .= "<tr>
			<td align=\"left\" style='border:none;'>Jumlah Bayar</td>
			<td align=\"center\" style='border:none;'> : </td>
			<td align=\"left\" style='border:none;'><b>$jmbayar</b></td>
		</tr>";
		$chari .= "<tr>
			<td align=\"left\" style='border:none;'>Terbilang</td>
			<td align=\"center\" style='border:none;'> : </td>
			<td align=\"left\" style='border:none;'><b>$terbilang</b></td>
		</tr>";
		$chari .= "</table>";
		

        $chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No.</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No. Perkiraan</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Keterangan</b> </td>
				<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Jumlah</b> </td>
            </tr>
            
		</thead>";
		
		$sql = "SELECT d.*, a.`acname`
			FROM tbl_dbayar d LEFT JOIN tbl_accounting a ON d.`accountno` = a.`accountno`
			WHERE bayarno = (
				SELECT bayarno
				FROM tbl_hbayar
				WHERE bayarno = '$id'
			);
		";
		

		$query1    = $this->db->query($sql);

		// 	$lcno        = 0;$terima_no    = 0; $terima_date  = 0; $invoice_no   = 0; $sj_no        = 0; $kodebarang   = 0; $namabarang   = 0; $qty_terima   = 0; $satuan       = 0; $price        = 0; $totalrp      = 0; $discount     = 0; $vat          = 0; $vatrp1        = 0; $po_no        = 0;

			

			$no = 0;
			$ttl = 0;
			foreach ($query1->result() as $row) {
			
				$no = ($no+1);
				$jml = angka_rp($row->jumlah,0);
				$chari .= "<tr>
					<td align=\"center\">$no</td>
					<td align=\"left\">$row->acname / $row->accountno</td>
					<td align=\"left\">$row->keterangan</td>
					<td align=\"right\">$jml</td>
				</tr>";
				
				$ttl = $ttl + $row->jumlah;
				
			}
			
			$ttl = angka_rp($ttl,0);
			$chari .= "<tr>
					<td align=\"right\" colspan=3>Total</td>
					<td align=\"right\">$ttl</td>
				</tr>";
			
		for($i=0;$i<5;$i++){
			$chari .= "<tr>
				<td align=\"center\" colspan=2 style='border:none;'></td>
				<td align=\"center\" colspan=2 style='border:none;'></td>
			</tr>";
		}


			$chari .= "<tr>
				<td align=\"center\" colspan=2 style='border:none;'></td>
				<td align=\"center\" colspan=2 style='border:none;'>$kota, $tglbayar</td>
			</tr>";
		$chari .= "</table>";
		
		
		$chari .= "<br><br>";


		$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">";
		$chari .= "<tr>
			<td align=\"center\" style='border:none;'>Mengetahui:</td>
			<td align=\"center\" style='border:none;'>Diterima oleh,</td>
			<td align=\"center\" style='border:none;'>Kasir,</td>
		</tr>";
		
		for($i=0;$i<10;$i++){
			$chari .= "<tr>
				<td align=\"center\" style='border:none;'></td>
				<td align=\"center\" style='border:none;'></td>
				<td align=\"center\" style='border:none;'></td>
			</tr>";
		}

		$chari .= "<tr>
			<td align=\"center\" style='border:none;'>Hospital Management System(HMS)/<br>Kasir Sentral</td>
			<td align=\"center\" style='border:none;'>___________________________</td>
			<td align=\"center\" style='border:none;'>( $username )</td>
		</tr>";

		$chari .= "</table>";

        $data['prev'] = $chari;
		$judul        = 'LAPORAN FARMASI PEMBELIAN DETAIL';
		
		// -----
			$cek = '1';
		// -----
        switch ($cek) {
            case 0;
                echo ("<title>LAPORAN FARMASI PEMBELIAN DETAIL</title>");
                echo ($chari);
			break;

            case 1;
                $this->M_cetak->mpdf('P','A4',$judul, $chari,'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 0);
			break;

			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('app/master_cetak', $data);
			break;
        }

		}else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function cetak2()
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
<h4>SALDO AWAL <?php echo $this->M_global->_namabulan($bulan).'  '.$tahun;?> </h4>
<table border="1">
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
	
	public function pengeluaran_save($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');
			
			if($kode==2){
			  $register = $this->input->post('register');			  	
			  $nobukti  = $this->input->post('nomorbukti');
			  $this->db->query("delete from tbl_dbayar where bayarno = '$nobukti'");			  	
            } else {
			//   $nobukti  = urut_transaksi('URUT_KASKELUAR',16);
			  $nobukti  = urut_transaksi('SETUP_LANGSUNG',19);
			}
			
			$tanggal  = $this->input->post('tanggal');
			$tahun    = date('Y',strtotime($tanggal));
			$bulan    = date('m',strtotime($tanggal));

			$ket      = $this->input->post('ket');
		    $jumlah   = $this->input->post('jumlah');
			
			$kodeakun = $this->input->post('akun');
		    $jumdata  = count($kodeakun);
			
			$total = 0;
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_akun   = $kodeakun[$i];
				$total  += str_replace(',','',$jumlah[$i]);
				
				$data = array(
					'koders' => $cabang,
					'bayarno'   => $nobukti,
					'accountno'  => $_akun,
					'keterangan' => $ket[$i],
					'jumlah' => str_replace(',','',$jumlah[$i]),
			    );

			   
				// print_r($data);
			
				if ($_akun!="")
				{
					$this->db->insert('tbl_dbayar',$data);
				}
			}
			
			$data_header = array(
			    'koders' => $cabang,
				'bayarno'  => $nobukti,
				'tglbayar' => $tanggal,
				'accountno'  => $this->input->post('kasbank'),
				'jmbayar' => $total,
				'keterangan' => $this->input->post('keterangan'),
				'jenisbayar' => $this->input->post('jenis'),
				'username' => $userid,
				'depid' => $this->input->post('cost_centre'),
				'nokasbon' => $this->input->post('no_mohon'),
				'nocek' =>  $this->input->post('cekgiro'),
			);

			// print_r($data_header);
			
			if($kode==1){
			  $this->db->insert('tbl_hbayar',$data_header);
			} else {
			  $this->db->update('tbl_hbayar',$data_header, array('id' => $register));
			//   echo $this->db->last_query();	
			}
			
			echo $nobukti;
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */