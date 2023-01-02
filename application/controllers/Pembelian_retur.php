  <?php if (!defined('BASEPATH')) exit('No direct script access allowed');
  class Pembelian_retur extends CI_Controller
  {
  public function __construct()
  {
    parent::__construct();
    $this->session->set_userdata('menuapp', '3000');
    $this->session->set_userdata('submenuapp', '3103');
    $this->load->helper('simkeu_nota');
    $this->load->model('M_cetak');
    $this->load->model('M_retur_bapb_farmasi');
  }

  public function index()
  {
    $cek = $this->session->userdata('level');
    $unit = $this->session->userdata('unit');
    if (!empty($cek)) {
      $bulan           = $this->M_global->_periodebulan();
      $nbulan          = $this->M_global->_namabulan($bulan);
      $periode         = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
      $d['keu']        = $this->db->query("SELECT a.*, b.vendor_name from tbl_baranghreturbeli a, tbl_vendor b where a.vendor_id=b.vendor_id and a.koders = '$unit' order by a.retur_date, a.retur_no desc")->result();
      $d['periode']    = $periode;
      $level           = $this->session->userdata('level');
      $akses           = $this->M_global->cek_menu_akses($level, 3102);
      $d['akses']      = $akses;
      $this->load->view('pembelian/v_pembelian_retur_1', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function data_list($param)
  {
    $user_level   = $this->session->userdata('user_level');
		$lock         = $this->M_global->close_app();
    $dat = explode("~", $param);
    if ($dat[0] == 1) {
      $bulan  = $this->M_global->_periodebulan();
      $tahun  = $this->M_global->_periodetahun();
      $list   = $this->M_retur_bapb_farmasi->get_datatables(1, $bulan, $tahun);
    } else {
      $bulan  = date('Y-m-d', strtotime($dat[1]));
      $tahun  = date('Y-m-d', strtotime($dat[2]));
      $list   = $this->M_retur_bapb_farmasi->get_datatables(2, $bulan, $tahun);
    }
    $data = array();
    $no   = $_POST['start'];
    foreach ($list as $unit) {
      $vendor = $this->db->get_where("tbl_vendor", ['vendor_id' => $unit->vendor_id])->row();
      $no++;
      $row    = array();
      $row[]  = '<div style="text-align: right;">'.$no.'</div>';
      $row[]  = $unit->retur_no;
      $row[]  = $unit->terima_no;
      $row[]  = '<div style="text-align: center;">' . date("d-m-Y", strtotime($unit->retur_date)) . '</div>';
      $row[]  = $vendor->vendor_name;

      if($user_level==0 && $lock == 1){
        
          $row[]  = 
          '<div style="text-align: center;">
          <a class="btn btn-sm btn-warning" href="' . base_url("Pembelian_retur/cetak/?id=" . $unit->retur_no . "") . '" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a></div>';
          
      }else{
          if($lock == 0){
            $row[]  = 
            '<div style="text-align: center;">
            <a class="btn btn-sm btn-primary" href="' . base_url("Pembelian_retur/edit/" . $unit->retur_no . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i></a>
            <a class="btn btn-sm btn-warning" href="' . base_url("Pembelian_retur/cetak/?id=" . $unit->retur_no . "") . '" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="Batalkan(' . "'" . $unit->retur_no . "'" . ')"><i class="glyphicon glyphicon-remove"></i></a></div>';
          }else{
            $row[]  = 
            '<div style="text-align: center;">
            <a class="btn btn-sm btn-warning" href="' . base_url("Pembelian_retur/cetak/?id=" . $unit->retur_no . "") . '" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a></div>';
          }
        
          
      }
      
      $data[] = $row;
    }
    $output = array(
      "draw"            => $_POST['draw'],
      "recordsTotal"    => $this->M_retur_bapb_farmasi->count_all($dat[0], $bulan, $tahun),
      "recordsFiltered" => $this->M_retur_bapb_farmasi->count_filtered($dat[0], $bulan, $tahun),
      "data"            => $data,
    );
    echo json_encode($output);
  }

  public function entri() {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $d['nomor']   = 'AUTO';
      $query_ppn    = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->result();
      $d['cekppn2'] = $query_ppn[0]->prosentase / 100;
      $this->load->view('pembelian/v_pembelian_retur_add', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function getlistpo($supp) {
    $tgl = date('Y-m-d');
    $uid = $this->session->userdata('unit');
    if (!empty($supp)) {
      $po  = $this->db->query("SELECT * from tbl_baranghterima where vendor_id = '$supp' and koders='$uid' and terima_date like '%$tgl%' and terima_no in (select terima_no from tbl_apoap where tukarfaktur=0) and terima_no not in (select terima_no from tbl_baranghreturbeli)")->result();
      ?>
      <select name="kodepu" id="kodepu" class="form-control  input-medium select2me">
        <option value="">-- Tanpa BAPB ---</option>
        <?php foreach ($po  as $row) { ?>
          <option value="<?php echo $row->terima_no; ?>"><?php echo $row->terima_no . ' | ' . date('Y-m-d', strtotime($row->terima_date)); ?></option>
        <?php } ?>
      </select>
    <?php
    } else {
      echo "";
    }
  }

  public function getpoheader($kodepo) {
    $data = $this->db->query("SELECT left(terima_date,10)terima_date1,(select keterangan from tbl_depo b where a.gudang=b.depocode)nm_gud,a.* from tbl_baranghterima a where terima_no='$kodepo'")->row();
    echo json_encode($data);
  }

  public function getpo($po) {
    $data = $this->db->query("SELECT tbl_barangdterima.*, tbl_barang.namabarang FROM tbl_barangdterima LEFT JOIN tbl_barang ON tbl_barang.kodebarang=tbl_barangdterima.kodebarang WHERE terima_no = '$po'")->result();
    echo json_encode($data);
  }

  public function getbarangname($kode) {
    if (!empty($kode)) {
      $query    = "select namabarang from inv_barang where kodeitem = '$kode'";
      $data     = $this->db->query($query);
      $jumdata  = $this->db->query($query)->num_rows();
      foreach ($data->result_array() as $row) {
        echo [$row['namabarang'], 'jumdata' => $jumdata];
      }
    } else {
      echo "";
    }
  }

  public function save_one()
  {
    $userid   = $this->session->userdata('username');
    $_vtotalx   = $this->input->post('_vtotalx');
    $gudang   = $this->input->post('gudang1');
    $bapb_no  = $this->input->post('kodepu');
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $nobukti  = urut_transaksi('URUT_BHP', 19);
    $data = array(
      'koders'     => $cabang,
      'retur_no'   => $nobukti,
      'vendor_id'  => $this->input->post('supp'),
      'terima_no'  => $this->input->post('kodepu'),
      'retur_date' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
      'invoice_no' => '',
      'gudang'     => $gudang,
      'jamretur'   => date('H:i:s'),
    );
    $this->db->insert('tbl_baranghreturbeli', $data);
    $hbapb  = $this->db->query("SELECT*FROM tbl_apoap where terima_no='$bapb_no'")->row();
    $data_ap = array(
      'koders'          => $cabang,
      'terima_no'       => $nobukti,
      'invoice_no'        => $invoice_no         = $hbapb->invoice_no,
      'vendor_id'         => $vendor_id          = $hbapb->vendor_id,
      'notukar'           => $notukar            = $hbapb->notukar,
      'tglinvoice'        => $tglinvoice         = $hbapb->tglinvoice,
      'duedate'           => $duedate            = $hbapb->duedate,
      'tglrencanabayar'   => $tglrencanabayar    = $hbapb->tglrencanabayar,
      'diambil'           => $diambil            = $hbapb->diambil,
      'totaltagihan'      => $totaltagihan       = - ($_vtotalx),
      'dpp'               => $dpp                = $hbapb->dpp,
      'ppn'               => $ppn                = $hbapb->ppn,
      'ppnrp'             => $ppnrp              = $hbapb->ppnrp,
      'pph'               => $pph                = $hbapb->pph,
      'biayalain'         => $biayalain          = $hbapb->biayalain,
      'materai'           => $materai            = $hbapb->materai,
      'totalbayar'        => $totalbayar         = $hbapb->totalbayar,
      'lunas'             => $lunas              = $hbapb->lunas,
      'keterangan'        => $keterangan         = $hbapb->keterangan,
      'otomatis'          => $otomatis           = $hbapb->otomatis,
      'term'              => $term               = $hbapb->term,
      'tukarfaktur'       => $tukarfaktur        = $hbapb->tukarfaktur,
      'otax'              => $otax               = $hbapb->otax,
      'jenis'             => $jenis              = $hbapb->jenis,
      'accountno'         => $accountno          = $hbapb->accountno,
      'acbiaya'           => $acbiaya            = $hbapb->acbiaya,
      'username'          => $userid,
    );
    $this->db->insert('tbl_apoap', $data_ap);
    echo json_encode(['nomor' => $nobukti]);
  }

  public function save_multi()
  {
    $cabang = $this->session->userdata('unit');
    $retur_no = $this->input->get('retur_no');
    $kode = $this->input->get('kode');
    $qty = $this->input->get('qty');
    $sat = $this->input->get('sat');
    $harga = $this->input->get('harga');
    $disc = $this->input->get('disc');
    $discrp = $this->input->get('discrp');
    $tax = $this->input->get('tax');
    $jumlah = $this->input->get('jumlah');
    $pajak = $this->input->get('pajak');
    $taxrp = $this->input->get('taxrp');
    $gudang   = $this->input->post('gudang1');
    $data = [
      'koders' => $cabang,
      'retur_no' => $retur_no,
      'kodebarang' => $kode,
      'qty_retur'  => $qty,
      'satuan'     => $sat,
      'price'      => $harga,
      'discount'   => $disc,
      'discountrp'   => $discrp,
      'totalrp'    => $jumlah,
      'tax'    => $tax,
      'taxrp'    => $taxrp,
    ];
    $this->db->insert('tbl_barangdreturbeli', $data);
    $stok = $this->db->query("select * from tbl_barangstock where kodebarang = '$kode' and gudang = '$gudang' and koders = '$cabang'")->num_rows();
    $date_now = date('Y-m-d H:i:s');
    if($stok > 0){
      $this->db->query("UPDATE tbl_barangstock set keluar = keluar+ $qty, saldoakhir = saldoakhir - $qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang'");
    } else {
      $datastock = array(
        'koders'       => $cabang,
        'kodebarang'   => $kode,
        'gudang'       => $gudang,
        'saldoawal'    => 0,
        'terima'       => 0,
        'keluar'       => $qty,
        'saldoakhir'   => 0-$qty,
        'lasttr'       => $date_now,
      );
      $this->db->insert('tbl_barangstock', $datastock);
    }
  }

  public function update_one()
  {
    $userid   = $this->session->userdata('username');
    $_vtotalx   = $this->input->post('_vtotalx');
    $gudang   = $this->input->post('gudang');
    $bapb_no  = $this->input->post('kodepu');
    $retur_no  = $this->input->get('retur_no');
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $datagudang = $this->db->get_where('tbl_baranghreturbeli', array('retur_no' => $retur_no))->row_array();
    $gudangx = $datagudang['gudang'];
    $this->db->set('koders', $cabang);
    $this->db->set('vendor_id', $this->input->post('supp'));
    $this->db->set('terima_no', $this->input->post('kodepu'));
    $this->db->set('retur_date', date('Y-m-d', strtotime($this->input->post('tanggal'))));
    $this->db->set('gudang', $gudangx);
    $this->db->where('retur_no', $retur_no);
    $this->db->update('tbl_baranghreturbeli');

    $dataretur  = $this->db->get_where('tbl_barangdreturbeli', ['retur_no' => $retur_no])->result();
    foreach ($dataretur as $row) {
      $_qty     = $row->qty_retur;
      $_kode    = $row->kodebarang;
      $this->db->query("UPDATE tbl_barangstock set keluar=keluar-$_qty, saldoakhir=saldoakhir+$_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
    }
    $this->db->delete('tbl_barangdreturbeli', ['retur_no' => $retur_no]);
    $this->db->delete('tbl_apoap', ['terima_no' => $retur_no]);
    echo json_encode(['nomor' => $retur_no]);
  }
  public function update_multi()
  {
    $cabang = $this->session->userdata('unit');
    $retur_no = $this->input->get('retur_no');
    $kode = $this->input->get('kode');
    $qty = $this->input->get('qty');
    $sat = $this->input->get('sat');
    $harga = $this->input->get('harga');
    $disc = $this->input->get('disc');
    $discrp = $this->input->get('discrp');
    $tax = $this->input->get('tax');
    $jumlah = $this->input->get('jumlah');
    $pajak = $this->input->get('pajak');
    $taxrp = $this->input->get('taxrp');
    $gudang   = $this->input->post('gudang');

    $datagudang = $this->db->get_where('tbl_baranghreturbeli', array('retur_no' => $retur_no))->row_array();
    $gudangx = $datagudang['gudang'];
    $data = [
      'koders' => $cabang,
      'retur_no' => $retur_no,
      'kodebarang' => $kode,
      'qty_retur'  => $qty,
      'satuan'     => $sat,
      'price'      => $harga,
      'discount'   => $disc,
      'discountrp'   => $discrp,
      'totalrp'    => $jumlah,
      'tax'    => $tax,
      'taxrp'    => $taxrp,
    ];
    $this->db->insert('tbl_barangdreturbeli', $data);
    $stok = $this->db->query("select * from tbl_barangstock where kodebarang = '$kode' and gudang = '$gudang' and koders = '$cabang'")->num_rows();
    $date_now = date('Y-m-d H:i:s');
    if($stok > 0){
      $this->db->query("UPDATE tbl_barangstock set keluar = keluar+ $qty, saldoakhir = saldoakhir - $qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang'");
    } else {
      $datastock = array(
        'koders'       => $cabang,
        'kodebarang'   => $kode,
        'gudang'       => $gudang,
        'saldoawal'    => 0,
        'terima'       => 0,
        'keluar'       => $qty,
        'saldoakhir'   => 0-$qty,
        'lasttr'       => $date_now,
      );
      $this->db->insert('tbl_barangstock', $datastock);
    }
    echo json_encode($data);
  }

  public function update_one_u()
  {
    $userid   = $this->session->userdata('username');
    $_vtotalx   = $this->input->post('_vtotalx');
    $gudang   = $this->input->post('gudang');
    $bapb_no  = $this->input->post('kodepu');
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $retur_no = $this->input->get('retur_no');
    $totaltagihan = $this->input->get('totaltagihan');
    $ppnrp = $this->input->get('ppnrp');
    $hbapb  = $this->db->query("SELECT*FROM tbl_apoap where terima_no='$bapb_no'")->row();
    $data_ap = array(
      'koders'          => $cabang,
      'terima_no'       => $retur_no,
      'invoice_no'        => $invoice_no         = $hbapb->invoice_no,
      'vendor_id'         => $vendor_id          = $hbapb->vendor_id,
      'notukar'           => $notukar            = $hbapb->notukar,
      'tglinvoice'        => $tglinvoice         = $hbapb->tglinvoice,
      'duedate'           => $duedate            = $hbapb->duedate,
      'tglrencanabayar'   => $tglrencanabayar    = $hbapb->tglrencanabayar,
      'diambil'           => $diambil            = $hbapb->diambil,
      'totaltagihan'      => $totaltagihan       = - ($totaltagihan),
      'dpp'               => $dpp                = $hbapb->dpp,
      'ppn'               => $ppn                = $hbapb->ppn,
      'ppnrp'             => $ppnrp              = $ppnrp,
      'pph'               => $pph                = $hbapb->pph,
      'biayalain'         => $biayalain          = $hbapb->biayalain,
      'materai'           => $materai            = $hbapb->materai,
      'totalbayar'        => $totalbayar         = $hbapb->totalbayar,
      'lunas'             => $lunas              = $hbapb->lunas,
      'keterangan'        => $keterangan         = $hbapb->keterangan,
      'otomatis'          => $otomatis           = $hbapb->otomatis,
      'term'              => $term               = $hbapb->term,
      'tukarfaktur'       => $tukarfaktur        = $hbapb->tukarfaktur,
      'otax'              => $otax               = $hbapb->otax,
      'jenis'             => $jenis              = $hbapb->jenis,
      'accountno'         => $accountno          = $hbapb->accountno,
      'acbiaya'           => $acbiaya            = $hbapb->acbiaya,
      'username'          => $userid,
    );
    $this->db->insert('tbl_apoap', $data_ap);
    echo json_encode(['nomor' => $retur_no]);
  }

  public function getharga($kode) {
    if (!empty($kode)) {
      $data  = explode("~", $kode);
      $supp  = $data[0];
      $item  = $data[1];
      $data  = $this->db->query("select * from ap_pudetail inner join ap_pufile on ap_pufile.kodepu=ap_pudetail.kodepu where ap_pufile.kodesup = '$supp' and ap_pudetail.kodeitem = '$item' order by ap_pufile.tglpu desc")->result();
      ?>
      <table id="myTable">
        <tr class="header">
          <th style="width:20%;">No. Faktur</th>
          <th style="width:20%;">Tanggal</th>
          <th style="width:20%;">Harga</th>
          <th style="width:10%;">Disc</th>
          <th style="width:10%;">Satuan</th>
        </tr>
        <?php foreach ($data  as $row) { ?>
          <tr>
            <td width="50" align="center">
              <a href="#" onclick="post_harga('<?php echo $row->hargabeli; ?>','<?php echo $row->satuan; ?>')"><?php echo $row->kodepu; ?></a>
            </td>
            <td><?php echo date('d-m-Y', strtotime($row->tglpu)); ?></td>
            <td><?php echo $row->hargabeli; ?></td>
            <td><?php echo $row->disc; ?></td>
            <td><?php echo $row->satuan; ?></td>
          </tr>
          <?php
        }
      echo "</table>";
    } else {
      echo "";
    }
  }

  public function getinfobarang($kode) {
    $data = $this->M_global->_data_barang($kode);
    echo json_encode($data);
  }

  public function edit($nomor)
  {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $header         = $this->db->get_where('tbl_baranghreturbeli', ['retur_no' => $nomor]);
      $detil          = $this->db->query('select a.*, (select namabarang from tbl_barang where kodebarang = a.kodebarang) as namabarang from tbl_barangdreturbeli a where retur_no = "' . $nomor . '"');
      $d['header']    = $header->row();
      $d['detil']     = $detil->result();
      $d['jumdata1']  = $detil->num_rows();
      $query_ppn      = $this->db->get_where("tbl_pajak", ['kodetax' => 'PPN'])->row();
      $d['cekppn2']   = $query_ppn->prosentase / 100;
      $this->load->view('pembelian/v_pembelian_retur_edit', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function cetak()
  {
    $cek = $this->session->userdata('level');
    $unit = $this->session->userdata('unit');
    if (!empty($cek)) {
      $unit       = $this->session->userdata('unit');
      $avatar     = $this->session->userdata('avatar_cabang');
      $kop        = $this->M_cetak->kop($unit);
      $profile    = data_master('tbl_namers', array('koders' => $unit));
      $namars     = $kop['namars'];
      $alamat     = $kop['alamat'];
      $alamat2    = $kop['alamat2'];
      $alamat3    = $profile->kota;
      $kota       = $kop['kota'];
      $phone      = $kop['phone'];
      $whatsapp   = $kop['whatsapp'];
      $npwp       = $kop['npwp'];
      $chari      = '';
      $param      = $this->input->get('id');
      $queryh     = "SELECT * from tbl_baranghreturbeli inner join
      tbl_vendor on tbl_baranghreturbeli.vendor_id=tbl_vendor.vendor_id 
      where tbl_baranghreturbeli.retur_no = '$param'";
      $queryd = "SELECT tbl_barangdreturbeli.*, tbl_barang.namabarang from tbl_barangdreturbeli inner join 
      tbl_barang on tbl_barangdreturbeli.kodebarang=tbl_barang.kodebarang
      where retur_no = '$param'";
      $detil  = $this->db->query($queryd)->result();
      $header = $this->db->query($queryh)->row();
      $gudangz = $this->db->get_where('tbl_depo', ['depocode' => $header->gudang])->row_array();
      $chari .= "
                    <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                          <thead>
                              <tr>
                                    <td rowspan=\"6\" align=\"center\">
                                        <img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
                                    </td>
                                    <td colspan=\"20\">
                                        <b>
                                              <tr>
                                                  <td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
                                              </tr>
                                              <tr>
                                                  <td style=\"font-size:9px;\">$alamat</td>
                                              </tr>
                                              <tr>
                                                  <td style=\"font-size:9px;\">$alamat2</td>
                                              </tr>
                                              <tr>
                                                  <td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
                                              </tr>
                                              <tr>
                                                  <td style=\"font-size:9px;\">No. NPWP : $npwp</td>
                                              </tr>
                                        </b>
                                    </td>
                              </tr>
                          </table>";
      $chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                    <tr>
                                        <td> &nbsp; </td>
                                    </tr> 
                              </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                    <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                    <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td> &nbsp; </td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                    <td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>RETUR PEMBELIAN</b></td>
                              </tr>
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td> &nbsp; </td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                    <td width=\"15%\" style=\"text-align:left;\"><b>Kepada Yth:</b></td>
                              </tr>
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none;\">Di return ke</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none;\">$header->vendor_name</td>
                                    <td width=\"4%\" style=\"border-left:none; border-right:none; border-bottom:none;\">&nbsp;</td>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none;\">Retur No.</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none;\">$header->retur_no</td>
                              </tr> 
                              <tr>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">$header->alamat</td>
                                    <td width=\"4%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">No. BAPB</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">$header->terima_no</td>
                              </tr> 
                              <tr>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"4%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">Tanggal</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">" . date('d-m-Y', strtotime($header->retur_date)) . "</td>
                              </tr> 
                              <tr>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">$header->phone</td>
                                    <td width=\"4%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">Invoice</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">$header->invoice_no</td>
                              </tr> 
                              <tr>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"4%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">&nbsp;</td>
                                    <td width=\"10%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">Gudang</td>
                                    <td width=\"3%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">:</td>
                                    <td width=\"35%\" style=\"border-left:none; border-right:none; border-bottom:none; border-top:none;\">" . $gudangz['keterangan'] . "</td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                    <tr>
                                        <td width=\"5%\" align=\"center\" style=\"text-align:center; border-right: none;\"><b>No</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Kode Barang</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Nama Barang</b></td>
                                        <td width=\"10%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Qty</b></td>
                                        <td width=\"10%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Satuan</b></td>
                                        <td width=\"10%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Harga</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Diskon</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-left: none;\"><b>Total</b></td>
                                    </tr>
                              </thead>";
      $no = 1;
      $totitem = 0;
      $subtotal = 0;
      $diskon = 0;
      $ppn = 0;
      $tot = 0;
      $ppna = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
      foreach ($detil as $db) {
        $chari .= "<tbody><tr>
                                <td style=\"text-align:center; border-right: none; border-left: none;\">" . $no++ . "</td>
                                <td style=\"text-align:left; border-right: none; border-left: none;\">$db->kodebarang</td>
                                <td style=\"text-align:left; border-right: none; border-left: none;\">$db->namabarang</td>
                                <td style=\"text-align:right; border-right: none; border-left: none;\">" . number_format($db->qty_retur) . "</td>
                                <td style=\"text-align:right; border-right: none; border-left: none;\">" . $db->satuan . "</td>
                                <td style=\"text-align:right; border-right: none; border-left: none;\">" . number_format($db->price) . "</td>
                                <td style=\"text-align:right; border-right: none; border-left: none;\">" . number_format($db->discountrp) . "</td>
                                <td style=\"text-align:right; border-left: none; border-right: none;\">" . number_format($db->totalrp) . "</td>
                            </tr></tbody>";
        $subtotal += ($db->qty_retur * $db->price);
        $diskon += ($db->discountrp);

        if ($db->tax == 1) {
          $ppnx = $db->totalrp * $ppna['prosentase'] / 100;
        } else {
          $ppnx = 0;
        }

        $ppn += $ppnx;
        $no++;
      }
      $tot = $subtotal - $diskon + $ppn;
      $dpp = ($tot) / (111 / 100);
      $chari .= "</table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td colspan=\"7\" style=\"text-align:right; border-right: none; border-top: none;\"><b>Subtotal</b></td>
                                    <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">" . number_format($subtotal) . "</td>
                              </tr> 
                              <tr>
                                    <td colspan=\"7\" style=\"text-align:right; border-right: none; border-top: none;\"><b>Diskon</b></td>
                                    <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">" . number_format($diskon) . "</td>
                              </tr> 
                              <tr>
                                    <td colspan=\"7\" style=\"text-align:right; border-right: none; border-top: none;\"><b>DPP</b></td>
                                    <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">" . number_format($dpp) . "</td>
                              </tr> 
                              <tr>
                                    <td colspan=\"7\" style=\"text-align:right; border-right: none; border-top: none;\"><b>PPN</b></td>
                                    <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">" . number_format($ppn) . "</td>
                              </tr> 
                              <tr>
                                    <td colspan=\"7\" style=\"text-align:right; border-right: none; border-top: none;\"><b>Total</b></td>
                                    <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">" . number_format($tot) . "</td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td> &nbsp; </td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td> &nbsp; </td>
                              </tr> 
                          </table>";
      $chari .= "
                          <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">Mengetahui :</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">Disetujui Oleh :</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">Yang Menerima :</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">$alamat3, " . date('d-m-Y') . "</td>
                              </tr> 
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">Keuangan,</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">Apoteker Penanggung Jawab,</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">$header->vendor_name</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">Yang Meretur,</td>
                              </tr> 
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                              </tr> 
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                              </tr> 
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                              </tr> 
                              <tr>
                                    <td width=\"25%\"  style=\"text-align:center;\">$profile->pejabat1</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">$profile->apoteker</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">....................................</td>
                                    <td width=\"25%\"  style=\"text-align:center;\">&nbsp;</td>
                              </tr> 
                          </table>";
      $data['prev'] = $chari;
      $judul = $param;
      echo ("<title>$judul</title>");
      $this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
    } else {
      header('location:' . base_url());
    }
  }

  public function hapus($nomor)
  {
    $cek          = $this->session->userdata('level');
    if (!empty($cek)) {
      $hretur     = $this->db->query("SELECT * from tbl_baranghreturbeli where retur_no = '$nomor'")->row();
      $cabang     = $hretur->koders;
      $gudang     = $hretur->gudang;
      $dataretur  = $this->db->get_where('tbl_barangdreturbeli', ['retur_no' => $nomor])->result();
      foreach ($dataretur as $row) {
        $_qty     = $row->qty_retur;
        $_kode    = $row->kodebarang;
        $this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
      }
      $this->db->delete('tbl_barangdreturbeli', ['retur_no' => $nomor]);
      $this->db->delete('tbl_baranghreturbeli', ['retur_no' => $nomor]);
      echo json_encode(["status" => 1, "nomor" => $nomor]);
    } else {
      header('location:' . base_url());
    }
  }
  }
