<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_retur_log extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->session->set_userdata('menuapp', '4000');
    $this->session->set_userdata('submenuapp', '4103');
    $this->load->helper('simkeu_nota');
    $this->load->model('M_cetak');
    $this->load->model('M_retur_bapb_logistik');
  }

  public function index()
  {
    $cek = $this->session->userdata('level');
    $unit = $this->session->userdata('unit');

    if (!empty($cek)) {
      $bulan           = $this->M_global->_periodebulan();
      $nbulan          = $this->M_global->_namabulan($bulan);
      $periode         = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
      $d['keu']        = $this->db->query("SELECT * from tbl_apohreturbelilog a, tbl_vendor b where a.vendor_id=b.vendor_id and a.koders = '$unit' order by a.retur_date, a.retur_no desc")->result();
      $d['periode']    = $periode;
      $level           = $this->session->userdata('level');
      $akses           = $this->M_global->cek_menu_akses($level, 4103);
      $d['akses']      = $akses;
      $this->load->view('pembelian/v_pembelian_retur_log_1', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function data_list($param)
  {
    $dat = explode("~", $param);
    if ($dat[0] == 1) {
      $bulan  = $this->M_global->_periodebulan();
      $tahun  = $this->M_global->_periodetahun();
      $list   = $this->M_retur_bapb_logistik->get_datatables(1, $bulan, $tahun);
    } else {
      $bulan  = date('Y-m-d', strtotime($dat[1]));
      $tahun  = date('Y-m-d', strtotime($dat[2]));
      $list   = $this->M_retur_bapb_logistik->get_datatables(2, $bulan, $tahun);
    }
    $data = array();
    $no   = $_POST['start'];
    foreach ($list as $unit) {
      $vendor = $this->db->get_where("tbl_vendor", ['vendor_id' => $unit->vendor_id])->row();
      $no++;
      $row    = array();
      $row[]  = '<div style="text-align: right;">' . $no . '</div>';
      $row[]  = $unit->retur_no;
      $row[]  = $unit->terima_no;
      $row[]  = '<div style="text-align: center;">' . date("d-m-Y", strtotime($unit->retur_date)) . '</div>';
      $row[]  = $vendor->vendor_name;
      $row[]  = '<div style="text-align: center;"><a class="btn btn-sm btn-primary" href="' . base_url("Pembelian_retur_log/edit/" . $unit->retur_no . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i></a>
      <a class="btn btn-sm btn-warning" href="' . base_url("Pembelian_retur_log/cetak/?id=" . $unit->retur_no . "") . '" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>
      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="Batalkan(' . "'" . $unit->retur_no . "'" . ')"><i class="glyphicon glyphicon-remove"></i></a></div>';
      $data[] = $row;
    }
    $output = array(
      "draw"            => $_POST['draw'],
      "recordsTotal"    => $this->M_retur_bapb_logistik->count_all($dat[0], $bulan, $tahun),
      "recordsFiltered" => $this->M_retur_bapb_logistik->count_filtered($dat[0], $bulan, $tahun),
      "data"            => $data,
    );
    echo json_encode($output);
  }

  public function entri()
  {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
      $d['cekppn2'] = $ppn['prosentase'] / 100;
      $d['nomor'] = urut_transaksi('URUT_BHP', 19);
      $this->load->view('pembelian/v_pembelian_retur_log_add', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function getlistpo($supp)
  {
    $tgl = date('Y-m-d');
    $uid = $this->session->userdata('unit');
    if (!empty($supp)) {
      $po  = $this->db->query("SELECT * from tbl_apohterimalog where vendor_id = '$supp' and terima_date like '%$tgl%' and koders='$uid' and terima_no not in (select terima_no from tbl_apohreturbelilog)")->result();
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

  public function getpoheader($kodepo)
  {
    $data = $this->db->query("SELECT left(terima_date,10)terima_date1,(select keterangan from tbl_depo b where a.gudang=b.depocode)nm_gud,a.* from tbl_apohterimalog a where terima_no='$kodepo'")->row();
    echo json_encode($data);
  }

  public function getpo($po)
  {
    $data = $this->db->query("SELECT tbl_apodterimalog.*, tbl_logbarang.namabarang FROM tbl_apodterimalog LEFT JOIN tbl_logbarang ON tbl_logbarang.kodebarang=tbl_apodterimalog.kodebarang WHERE terima_no = '$po'")->result();
    echo json_encode($data);
  }

  public function getbarangname($kode)
  {
    if (!empty($kode)) {
      $data  = $this->db->query("select namabarang from inv_barang where kodeitem = '$kode'");
      foreach ($data->result_array() as $row) {
        echo $row['namabarang'];
      }
    } else {
      echo "";
    }
  }

  public function getharga($kode)
  {
    if (!empty($kode)) {
      $data  = explode("~", $kode);
      $supp  = $data[0];
      $item  = $data[1];

      $query = "select * from ap_pudetail inner join ap_pufile on ap_pufile.kodepu=ap_pudetail.kodepu where ap_pufile.kodesup = '$supp' and ap_pudetail.kodeitem = '$item' order by ap_pufile.tglpu desc";
      $data  = $this->db->query($query)->result();
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
        <?php }
      echo "</table>";
    } else {
      echo "";
    }
  }

  public function getinfobarang($kode)
  {
    $data = $this->db->get_where('tbl_logbarang', ['kodebarang' => $kode])->row_array();
    echo json_encode($data);
  }

  public function data_awal_terima()
  {
    $kode = $this->input->get('kode');
    $terima_no = $this->input->get('terima_no');
    $cabang = $this->session->userdata('unit');
    $data_barang = $this->db->query("SELECT * FROM tbl_apodterimalog WHERE kodebarang = '$kode' AND koders = '$cabang' AND terima_no = '$terima_no'")->row_array();
    echo json_encode($data_barang);
  }

  public function save_one()
  {
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $nobukti  = $this->input->post('nomorbukti');
    $userid   = $this->session->userdata('username');
    $gudang   = $this->input->post('gudang1');
    $bapb_no  = $this->input->post('kodepu');
    $_vtotalx   = $this->input->post('_vtotalx');
    $ppnrp   = $this->input->post('_vppn');
    $qcek = $this->db->query("SELECT * FROM tbl_apoap WHERE terima_no = '$nobukti' and koders='$cabang'")->result_array();
    $cek = count($qcek);
    if ($cek > 0) {
      echo json_encode(array("status" => "1", "nomor" => $nobukti));
    } else {
      $data = [
        'koders'    => $this->session->userdata('unit'),
        'vendor_id' => $this->input->post('supp'),
        'retur_no'  => $this->input->post('nomorbukti'),
        'terima_no' => $this->input->post('kodepu'),
        'retur_date'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
        'invoice_no' => '',
        'gudang' => $gudang,
        'jamretur'   => date('h:i:s'),
      ];
      $this->db->insert('tbl_apohreturbelilog', $data);

      $hbapb  = $this->db->query("SELECT*FROM tbl_apoap where terima_no='$bapb_no'")->row();
      $data_ap = [
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
      ];
      $this->db->insert('tbl_apoap', $data_ap);
      echo json_encode(['status' => 2, 'nomor' => $nobukti]);
    }
  }

  public function save_multi()
  {
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $nobukti  = $this->input->post('nomorbukti');
    $userid   = $this->session->userdata('username');
    $gudang   = $this->input->post('gudang1');
    $bapb_no  = $this->input->post('kodepu');
    $_vtotalx   = $this->input->post('_vtotalx');
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
    $hpp = $this->db->get_where('tbl_logbarang', array('kodebarang' => $kode))->row()->hpp;
    $data = [
      'koders' => $cabang,
      'retur_no' => $nobukti,
      'kodebarang' => $kode,
      'qty_retur'  => $qty,
      'satuan'     => $sat,
      'price'      => $harga,
      'discount'   => $disc,
      'discountrp'   => $discrp,
      'totalrp'    => $jumlah,
      'tax'    => $tax,
      'taxrp'    => $taxrp,
      'hpp' => $hpp,
    ];
    $this->db->insert('tbl_apodreturbelilog', $data);
    $stok = $this->db->query("select * from tbl_apostocklog where kodebarang = '$kode' and gudang = '$gudang' and koders = '$cabang'")->row_array();
    $keluar = (int)$stok['keluar'] + (int)$qty;
    $saldoakhir = (int)$stok['saldoakhir'] - (int)$qty;
    $datax = [
      'keluar' => $keluar,
      'saldoakhir' => $saldoakhir,
    ];
    $wherex = [
      'koders' => $cabang,
      'gudang' => $gudang,
      'kodebarang' => $kode
    ];
    $xxx = $this->db->update('tbl_apostocklog', $datax, $wherex);
    echo json_encode($xxx);
  }

  public function hapus($nomor)
  {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $hretur  = $this->db->query("SELECT * from tbl_apohreturbelilog where retur_no = '$nomor'")->row();
      $cabang  = $hretur->koders;
      $gudang  = $hretur->gudang;
      $dataretur = $this->db->get_where('tbl_apodreturbelilog', ['retur_no' => $nomor])->result();
      foreach ($dataretur as $row) {
        $_qty  = $row->qty_retur;
        $_kode = $row->kodebarang;
        $this->db->query("UPDATE tbl_apostocklog set keluar=keluar-$_qty, saldoakhir= saldoakhir+$_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
      }
      $delete = [
        $this->db->delete('tbl_apodreturbelilog', ['retur_no' => $nomor]),
        $this->db->delete('tbl_apohreturbelilog', ['retur_no' => $nomor]),
      ];
      if ($delete) {
        echo json_encode(["status" => 1]);
      } else {
        echo json_encode(["status" => 0]);
      }
    } else {
      header('location:' . base_url());
    }
  }

  public function edit($nomor)
  {
    $cek = $this->session->userdata('level');
    if (!empty($cek)) {
      $unit = $this->session->userdata('unit');
      $header = $this->db->get_where('tbl_apohreturbelilog', array('retur_no' => $nomor));
      $detil  = $this->db->join('tbl_logbarang', 'tbl_logbarang.kodebarang=tbl_apodreturbelilog.kodebarang')->get_where('tbl_apodreturbelilog', array('retur_no' => $nomor));
      $d['header']  = $header->result();
      $d['detil']   = $detil->result();
      $d['jumdata1'] = $detil->num_rows();
      $ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
      $d['cekppn2'] = $ppn['prosentase'] / 100;
      $this->load->view('pembelian/v_pembelian_retur_log_edit', $d);
    } else {
      header('location:' . base_url());
    }
  }

  public function update_one()
  {
    $cabang   = $this->session->userdata('unit');
    $cek      = $this->session->userdata('level');
    $nobukti  = $this->input->post('nomorbukti');
    $userid   = $this->session->userdata('username');
    $gudang   = $this->input->post('gudang');
    $bapb_no  = $this->input->post('kodepu');
    $_vtotalx   = $this->input->post('_vtotalx');
    $ppnrp   = $this->input->post('_vppn');
    $data = [
      'koders'    => $this->session->userdata('unit'),
      'vendor_id' => $this->input->post('supp'),
      'retur_no'  => $this->input->post('nomorbukti'),
      'terima_no' => $this->input->post('kodepu'),
      'retur_date'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
      'invoice_no' => '',
      'gudang' => $gudang,
    ];
    $this->db->set('koders', $cabang);
    $this->db->set('vendor_id', $this->input->post('supp'));
    $this->db->set('terima_no', $this->input->post('kodepu'));
    $this->db->set('retur_date', date('Y-m-d', strtotime($this->input->post('tanggal'))));
    $this->db->set('gudang', $gudang);
    $this->db->where('retur_no', $nobukti);
    $this->db->update('tbl_apohreturbelilog');
    $dataretur = $this->db->get_where('tbl_apodreturbelilog', ['retur_no' => $nobukti])->result();
    foreach ($dataretur as $row) {
      $_qty  = $row->qty_retur;
      $_kode = $row->kodebarang;
      $this->db->query("UPDATE tbl_apostocklog set keluar=keluar-$_qty, saldoakhir=saldoakhir+$_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
    }
    $this->db->delete('tbl_apodreturbelilog', ['retur_no' => $nobukti]);
    $this->db->delete('tbl_apoap', array('terima_no' => $nobukti));
    echo json_encode(['nomor' => $nobukti]);
  }

  public function update_multi()
  {
    $cabang     = $this->session->userdata('unit');
    $retur_no   = $this->input->post('nomorbukti');
    $kode       = $this->input->get('kode');
    $qty        = $this->input->get('qty');
    $sat        = $this->input->get('sat');
    $harga      = $this->input->get('harga');
    $disc       = $this->input->get('disc');
    $discrp     = $this->input->get('discrp');
    $tax        = $this->input->get('tax');
    $jumlah     = $this->input->get('jumlah');
    $pajak      = $this->input->get('pajak');
    $taxrp      = $this->input->get('taxrp');
    $gudang     = $this->input->post('gudang');

    $datagudang = $this->db->get_where('tbl_apohreturbelilog', array('retur_no' => $retur_no))->row_array();
    $gudangx    = $datagudang['gudang'];

    $data = [
      'koders'      => $cabang,
      'retur_no'    => $retur_no,
      'kodebarang'  => $kode,
      'qty_retur'   => $qty,
      'satuan'      => $sat,
      'price'       => $harga,
      'discount'    => $disc,
      'discountrp'  => $discrp,
      'totalrp'     => $jumlah,
      'tax'         => $tax,
      'taxrp'       => $taxrp,
    ];
    $this->db->insert('tbl_apodreturbelilog', $data);
    $stok = $this->db->query("select * from tbl_apostocklog where kodebarang = '$kode' and gudang = '$gudang' and koders = '$cabang'")->row_array();
    $keluar = (int)$stok['keluar'] + (int)$qty;
    $saldoakhir = (int)$stok['saldoakhir'] - (int)$qty;
    $datax = [
      'keluar'     => $keluar,
      'saldoakhir' => $saldoakhir,
    ];
    $wherex = [
      'koders'     => $cabang,
      'gudang'     => $gudang,
      'kodebarang' => $kode
    ];
    $xxx = $this->db->update('tbl_apostocklog', $datax, $wherex);
    echo json_encode($stok);
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
      'koders'            => $cabang,
      'terima_no'         => $retur_no,
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

  public function cetak()
  {
    $cek        = $this->session->userdata('level');
    $unit       = $this->session->userdata('unit');
    $param      = $this->input->get('id');
    $profile    = $this->M_global->_LoadProfileLap();
    if (!empty($cek)) {
      $nama_usaha = $profile->nama_usaha;
      $alamat1  = $profile->alamat1;
      $alamat2  = $profile->alamat2;

      $queryh = "SELECT * from tbl_apohreturbelilog 
			inner join tbl_vendor on tbl_apohreturbelilog.vendor_id=tbl_vendor.vendor_id where tbl_apohreturbelilog.retur_no = '$param'";

      $queryd = "SELECT * from tbl_apodreturbelilog 
			inner join tbl_logbarang on tbl_apodreturbelilog.kodebarang=tbl_logbarang.kodebarang where tbl_apodreturbelilog.retur_no = '$param'";

      $header   = $this->db->query($queryh)->row();
      $detil    = $this->db->query($queryd)->result();
      $pdf       = new simkeu_nota();
      $pdf->setID($nama_usaha, $alamat1, $alamat2);
      $pdf->setjudul('');
      $pdf->setsubjudul('');
      $pdf->addpage("P", "A4");
      $pdf->setsize("P", "A4");
      $pdf->SetWidths(array(70, 30, 90));
      $border   = array('T', '', 'BT');
      $size     = array('', '', '');
      $pdf->setfont('Arial', 'B', 18);
      $pdf->SetAligns(array('C', 'C', 'C'));
      $align    = array('L', 'C', 'L');
      $style    = array('', '', 'B');
      $size     = array('12', '', '18');
      $max      = array(5, 5, 20);
      $judul    = array('Kepada :', '', 'Retur Pembelian');
      $fc       = array('0', '0', '0');
      $hc       = array('20', '20', '20');
      $pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
      $pdf->ln(1);
      $pdf->setfont('Arial', 'B', 10);
      $pdf->SetWidths(array(70, 30, 30, 5, 55));
      $border   = array('', '', '', '', '');
      $fc       = array('0', '0', '0', '0', '0');
      $pdf->SetFillColor(230, 230, 230);
      $pdf->setfont('Arial', '', 9);
      $pdf->FancyRow(array($header->vendor_name, '', 'No. Retur', ':', $header->retur_no), $fc, $border);
      $pdf->FancyRow(array($header->alamat, '', 'Tanggal', ':', date('d M Y', strtotime($header->retur_date))), $fc, $border);
      $pdf->FancyRow(array($header->alamat2, '', 'No. Ref', ':', $header->terima_no), $fc, $border);
      $pdf->FancyRow(array($header->contact, '', '', '', ''), $fc, $border);

      $pdf->ln(2);

      $pdf->SetWidths(array(30, 50, 20, 25, 15, 20, 30));
      $border   = array('TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB');
      $align    = array('L', 'L', 'R', 'R', 'R', 'R', 'R');
    //   $style      = array('TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB');
      $pdf->setfont('Arial', 'B', 10);
      $pdf->SetAligns(array('L', 'C', 'R'));
      //$pdf->SetFillColor(0,0,139);
      //$pdf->settextcolor(255,255,255);
      $fc       = array('0', '0', '0', '0', '0', '0', '0');
      $judul    = array('Kode Barang', 'Nama Barang', 'Qty', 'Harga', 'Diskon', 'Diskon Rp', 'Total Harga');
      $pdf->FancyRow2(8, $judul, $fc, $border, $align );
      $border   = array('', '', '');
      $pdf->setfont('Arial', '', 10);
      $tot      = 0;
      $subtot   = 0;
      $tdisc    = 0;
      $tdiscrp  = 0;
      $tot_1    = 0;
      $ppn      = 0;
      $border   = array('', '', '', '', '', '', '');
      $align    = array('L', 'L', 'R', 'R', 'R', 'R', 'R');
      $fc       = array('0', '0', '0', '0', '0', '0', '0');
      $pdf->SetFillColor(0, 0, 139);
      $pdf->settextcolor(0);
      $ppna     = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
      foreach ($detil as $db) {
        $dpp        = $db->qty_retur * $db->hargabeli;
        $dis        = ($db->discount / 100) * $dpp;
        $jum        = $dpp - $db->discountrp;
        $tot        = $tot + $jum;
        $tot_1      += $db->qty_retur * $db->hargabeli;

        $dppz       = ($tot - $dis) / 1.11;

        $subtot     = $subtot + $dpp;
        $tdisc      = $tdisc + $dis;
        $tdiscrp    += $db->discountrp;

        $pdf->FancyRow(array(
          $db->kodebarang,
          $db->namabarang,
          $db->qty_retur,
          number_format($db->hargabeli, 0, '.', ','),
          $db->discount,
          number_format($db->discountrp, 2),
          number_format($jum, 0, '.', ',')
        ), $fc, $border, $align);
        $ppn += $db->taxrp;
      }
      // if($header->sppn=="Y"){
      // if($header->fax=="Y"){
      // 	$ppn = ($subtot-$tdisc) * 0.11;
      // } else {
      // 	$ppn = 0;
      // }
      $tot        = $tot + $ppn;
      $dpp_new    = ($tot - $tdisc) * 111 / 100;
      $pdf->SetFillColor(230);
      $border     = array('B', 'B', 'B', 'B', 'B', 'B');
      $fc         = array('0', '0', '0', '0', '0', '0');
      $pdf->FancyRow(array('', '', '', '', '', ''), $fc, $border);
      $pdf->ln(2);
      $pdf->SetWidths(array(100, 20, 30, 40));
      $border     = array('TB', '', 'T', 'T');
      $align      = array('L', '', 'L', 'R');
      //$pdf->SetFillColor(230,230,230);
      //$pdf->settextcolor(0);
      $fc         = array('0', '0', '0', '0');
      $pdf->FancyRow(array('Keterangan', '', 'Sub Total', number_format($subtot, 0, '.', ',')), $fc, $border, $align, 0);
      $border     = array('', '', '', '');
      // $pdf->FancyRow(array('','', 'DPP', number_format($dppz,0,'.',',')),$fc, $border, $align);
      if($header->ket1 == null || $header->ket1 == "NULL") { $ket1 = ""; } else { $ket1 = $header->ket1; }
      $pdf->FancyRow(array($ket1, '', 'Diskon', number_format($tdiscrp, 0, '.', ',')), $fc, $border, $align);
      $pdf->FancyRow(array('', '', 'PPN (11%)', number_format($ppn, 0, '.', ',')), $fc, $border, $align);
      $style      = array('', '', 'B', 'B');
      $size       = array('', '', '', '');
      $border     = array('T', '', 'BT', 'BT');
      ///$pdf->SetFillColor(0,0,139);
      //$pdf->settextcolor(255,255,255);
      $fc         = array('0', '0', '0', '0');
      $pdf->FancyRow(array('', '', 'Total', number_format($tot, 0, '.', ',')), $fc, $border, $align, $style, $size);
      $pdf->settextcolor(0);
      $pdf->SetWidths(array(50, 50));
      $pdf->SetFont('Arial', '', 9);
      $pdf->SetAligns(array('C', 'C'));
      $pdf->ln(5);
      $pdf->AliasNbPages();
      $pdf->output($param . '.PDF', 'I');
    } else {
      header('location:' . base_url());
    }
  }
}
