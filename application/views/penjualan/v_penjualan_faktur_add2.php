<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
  $pasrsp       = $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '-'")->row();
  if ($pasrsp) {
    $age_date     = new DateTime($pasrsp->tgllahir);
    $age_now      = new DateTime();
    $age_interval = $age_now->diff($age_date);
  } else {
    $age_interval = (object) array(
      "y"  => 0,
      "m"  => 0,
      "d"  => 0,
    );
  }
  $umur = $age_interval->y . ' Tahun ' . $age_interval->m . ' Bulan ' . $age_interval->d . ' Hari';
?>

<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?= $this->session->userdata('unit'); ?>
      </span>
      &nbsp;-
      <span class="title-web">APOTEK <small>Penjualan Resep</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?= base_url(); ?>dashboard">Awal</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= base_url(); ?>penjualan_faktur">Daftar Faktur Penjualan</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">Entri Faktur</a>
      </li>
    </ul>
  </div>
</div>

<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i><b>*Data Baru</b>
    </div>
  </div>
  <div class="portlet-body form">
    <form id="frmpenjualan" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Pembeli <font color="red">*</font></label>
                  <div class="col-md-9">
                    <select id="pembeli" name="pembeli" class="form-control select2_pembeli select2_all" onchange="getdataklinik()">
                      <option value="adr">Apotik Dengan Resep</option>
                      <option value="atr">Apotik Tanpa Resep</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Resep Dari <font color="red">*</font></label>
                  <div class="col-md-9">
                    <input type="text" id="dokter" name="dokter" class="form-control" placeholder="dr ..">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                  <div class="col-md-9">
                    <select id="gudang" name="gudang" class="form-control select2_all" onchange="getkodebaru(this.value)" style="width: 100%;" data-placeholder="APOTEK">
                      <option value="">APOTEK</option>
                      <?php
                      $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE (konekpos = 'FARMASI' OR konekpos = 'APOTEK')")->result(); 
                      foreach($gudang as $g) :
                        if($g->depocode == 'APTK') { $sel = "selected"; } else { $sel = ""; }
                      ?>
                      <option value="<?= $g->depocode; ?>" <?= $sel; ?>><?= $g->keterangan; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">No. Pembelian <font color="red">*</font></label>
                  <div class="col-md-9">
                    <input type="hidden" name="eresepstatus" value="1">
                    <input type="text" id="noresep" name="noresep" class="form-control" readonly placeholder="AUTO">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                  <div class="col-md-6">
                    <input id="tanggal" name="tanggal" class="form-control" type="date" value="<?= date('Y-m-d'); ?>"  readonly/>
                  </div>
                  <div class="col-md-3">
                    <input type="time" class="form-control" name="jam" id="jam" value="<?= date('H:i:s'); ?>" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Alamat Kirim</label>
                  <div class="col-md-9">
                    <input type="text" name="alamat" id="alamat" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Member</label>
                  <div class="col-md-6">
                    <select id="pasien" name="pasien" class="form-control select2_el_pasien" onchange="getinfopasien(this.value)" data-placeholder="Pilih..." style="width: 100%;">
                      <input type="hidden" name="namapasien" id="namapasien" class="form-control">
                    </select>
                  </div>
                  <div class="col-md-3">
                    <span class="input-group-btn">
                      <a class="btn-sm btn green" style="width: 100%; border-radius: 5px;" onclick="add_pasien()"><i class="fa fa-plus"></i> Pasien Baru</a>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Jenis Kelamin</label>
                  <div class="col-md-9">
                    <select name="jkel" id="jkel" class="form-control select2_all">
                      <option value="P">Pria</option>
                      <option value="W">Wanita</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Nama Pembeli</label>
                  <div class="col-md-9">
                    <input id="nama_pas" name="nama_pas" type="text" class="form-control" style="text-transform: uppercase !important">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Tgl Lahir</label>
                  <div class="col-md-4">
                    <input id="tgllahir" name="tgllahir" type="date" onchange="tgllahirr()" class="form-control" >
                  </div>
                  <div class="col-md-5">
                    <input id="lumur" name="lumur" type="text" class="form-control" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Berat Badan</label>
                  <div class="col-md-6">
                    <input type="number" name="bb" id="bb" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <span class="input-group-btn">
                      <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> Kg </b></label>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">No Handphone</label>
                  <div class="col-md-7">
                    <input type="text" name="phone" id="phone" class="form-control" value="+62">
                  </div>
                  <div class="col-md-2"> 
                    <input type="checkbox" id="reg_cekhp" name="reg_cekhp" value="1" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <ul class="nav nav-pills">
            <li class="active">
              <a href="#tab1" data-toggle="tab">Resep</a>
            </li>
            <li class="">
              <a href="#tab2" data-toggle="tab">Racikan</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <div class="row">
                <div class="col-md-12">
                  <table id="datatable" class="table table-hover table-striped table-bordered table-condensed table-scrollable">
                    <thead class="page-breadcrumb breadcrumb">
                      <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                      <th class="title-white" width="15%" style="text-align: center">Kode Barang</th>
                      <th class="title-white" width="8%" style="text-align: center">Qty Jual</th>
                      <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                      <th class="title-white" width="10%" style="text-align: center">Harga</th>
                      <th class="title-white" width="2%" style="text-align: center">PPN</th>
                      <th class="title-white" width="5%" style="text-align: center">Disc. %</th>
                      <th class="title-white" width="10%" style="text-align: center">Disc. Rp</th>
                      <th class="title-white" width="10%" style="text-align: center">Total Harga</th>
                      <th class="title-white" width="5%" style="text-align: center">Keterangan</th>
                      <th class="title-white" width="10%" style="text-align: center">Aturan Pakai</th>
                      <th class="title-white" width="5%" style="text-align: center">Expired Date</th>
                    </thead>
                    <tbody>
                      <tr id="resep_tr1">
                        <td><button type='button' onclick="hapusBarisIni(1)" disabled class='btn red'><i class='fa fa-trash-o'></td>
                        <td>
                          <select name="kode[]" id="kode1" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname(this.value, 1)" style="width: 100%;"></select>
                          <input name="nama[]" id="nama1" type="hidden" class="form-control " onkeypress="return tabE(this,event)">
                        </td>
                        <td><input name="qty[]" onchange="totalline(1);total(); ceksaldoakhir(1)" value="1" id="qty1" type="text" class="form-control rightJustified"></td>
                        <td><input name="sat[]" id="sat1" type="text" class="form-control " onkeypress="return tabE(this,event)"></td>
                        <td><input name="harga[]" onchange="totalline(1);" value="0" id="harga1" type="text" class="form-control rightJustified" readonly></td>
                        <td><input type="checkbox" name="ppn[]" id="ppn1" class="form-control" onchange="totalline(1);total()" disabled></td>
                        <td><input name="disc[]" onchange="totalline(1)" value="0" id="disc1" type="text" class="form-control rightJustified "></td>
                        <td><input name="disc2[]" value="0" id="disc21" type="text" onchange="total();myFunction(1)" class="form-control rightJustified "></td>
                        <td><input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
                        <td><textarea name="keterangan[]" id="keterangan1" type="text" class="form-control" style="resize:none" rows="2"></textarea></td>
                        <td>
                          <select name="aturan_pakai[]" id="aturan_pakai1" class="form-control select2_all" data-placeholder="Pilih..." style="width: 100%;">
                            <option value="">Pilih...</option>
                            <?php foreach ($atpakaix as $atpx) : ?>
                              <option value="<?= $atpx->apocode; ?>"><?= $atpx->aponame; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <input name="expire[]" value="" id="expire1" type="date" style="width:90%;" class="form-control">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-xs-9">
                      <div class="wells">
                        <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-xs-7">
                  <div class="wells">
                    <button id="btnsimpan" type="button" onclick="ceksave()" class="btn blue"><i class="fa fa-save"></i>
                      <b>Posting Resep</b>
                    </button>
                    <div class="btn-group">
                      <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></button>
                    </div>
                    <button id="btncetak" type="button" onclick="javascript:window.open(_urlcetak(),'_blank');" class="btn yellow"><i class="fa fa-print"></i> <b>Cetak</b></button>
                    <a href="<?= base_url('penjualan_faktur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                    <h4>
                      <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span>
                      <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                    </h4>
                  </div>
                </div>
                <div class="col-xs-5 invoice-block">
                  <div class="well">
                    <table id="tabeltotal">
                      <tr>
                        <td width="40%"><strong>SUB TOTAL</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>DISKON</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vdiskon" data-type="currency"></span></strong>
                        </td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>DPP</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vdpp" data-type="currency"></span></strong></td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>PPN</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vppn" name="_vppn"></span></strong>
                        </td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL RACIK</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vracik" name="_vracik"></span></strong>
                        </td>
                      </tr>
                      <tr>
                        <td width="40%"><strong>TOTAL</strong></td>
                        <td width="1%"><strong>:</strong></td>
                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong>
                        </td>
                        <input type="hidden" id="ppn2_" name="ppn2_" value="<?= $ppn['prosentase']; ?>">
                      </tr>
                      <input type="hidden" id="tersimpan">
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab2">
              <div class="row">
                <div class="col-md-12 form-body">
                  <table class="table" border="0" width="100%">
                    <tr bgcolor="#c7f2ff">
                      <td class="control-labelh">
                        &nbsp; <b>RACIKAN KE</b> &nbsp;
                        <select id="cekracik" name="cekracik" class="form-control select2_all" style="width: 20%" onchange="ubah_tab(this.value)">
                          <option value="1" selected>1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-12">
                  <div class="portlet box purple" id="racik1">
                    <div class="portlet-title">
                      <div class="caption">
                        <span class="title-white"><b>RACIKAN KE - 1</b></span>
                      </div>
                    </div>
                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" border="0" width="100%">
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_1" name="jenis_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <input type="text" class="form-control " name="namaracik_1" id="namaracik_1" value="" Placeholder="Nama">
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_1" id="carapakai_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <option value="DIMINUM"> DIMINUM </option>
                                <option value="DIOLES"> DIOLES </option>
                                <option value="DITETES"> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <input type="number" class="form-control " name="jumracik_1" id="jumracik_1">
                            </td>
                            <td width="12%">
                              <select name="stajum_1" id="stajum_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_1" id="atpakai_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td>&nbsp;</td>
                            <td class="control-labelh rightJustified" type="hidden" width="15%"></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="7">&nbsp;</td>
                          </tr>
                        </table>
                        <table id="datatble_racikan1" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                          <thead class="page-breadcrumb breadcrumb">
                            <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                            <th class="title-white" width="20%" style="text-align: center">Kode Obat</th>
                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Racik</th>
                            <th class="title-white" width="10%" style="text-align: center">Harga Jual</th>
                            <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                            <th class="title-white" width="15%" style="text-align: center">Expired</th>
                          </thead>
                          <tbody>
                            <tr id="racik_no1">
                              <td>
                                <button type='button' onclick=hapusBarisIni_racik1(1) disabled class='btn purple'><i class='fa fa-trash-o'>
                              </td>
                              <td>
                                <select name="koderacik_1[]" id="koderacik_11" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_1(this.value, 1)" style="width: 100%;"></select>
                                <input name="nama_racik_1[]" id="nama_racik_11" type="hidden" class="form-control">
                              </td>
                              <td>
                                <input name="satracik_1[]" id="satracik_11" type="text" class="form-control" readonly>
                              </td>
                              <td>
                                <input name="qty_jualracik_1[]" id="qty_jualracik_11" onchange="totalline_racik_1(1); total_racik_1(); cekqty_racik_1(1); cekstok_racik_1(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="qty_racik_racik_1[]" id="qty_racik_racik_11" onchange="totalline_racik_1(1); total_racik_1(); cekqty_racik_1(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="hargajualracik_1[]" onchange="totalline_racik_1(1);" value="0" id="hargajualracik_11" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name="total_hrg_racik_1[]" onchange="totalline_racik_1(1);" value="0" id="total_hrg_racik_11" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name='exp_racik_1[]' id='exp_racik_11' type='date' class='form-control rightJustified'>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_1' id='jml_racikan_1'>
                              <button type="button" onclick="tambah_racikan_1()" class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racikan_1" id="toto_racikan_1" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">Resep Manual Dari Dokter
                              <textarea type="text" class="form-control " name="resman_racik_1" id="resman_racik_1" value=""></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_1" id="disknom_racik_1" value="0" onchange="total_racik_1()">
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_1" id="disk_racik_1" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_1" id="cek_ppn_racik_1" onchange="cek_ppn2()" disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_1" id="ppn_racik_1" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_1" id="ongra_racik_1" value="0" onchange="total_racik_1()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_1" id="totp_racik_1" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_1" name="ppn_pajak_racik_1" value="<?= $ppn['prosentase']; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_1" id="t_manual_racik_1" onclick="cekmanual_racik_1()">
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_1" id="toto_racik_1" value="0" readonly onchange="t_jual_manual_racik_1()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="portlet box red" id="racik2">
                    <div class="portlet-title">
                      <div class="caption">
                        <span class="title-white"><b>RACIKAN KE - 2</b></span>
                      </div>
                    </div>
                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" border="0" width="100%">
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_2" name="jenis_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <input type="text" class="form-control " name="namaracik_2" id="namaracik_2" value="" Placeholder="Nama">
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_2" id="carapakai_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <option value="DIMINUM"> DIMINUM </option>
                                <option value="DIOLES"> DIOLES </option>
                                <option value="DITETES"> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <input type="number" class="form-control " name="jumracik_2" id="jumracik_2">
                            </td>
                            <td width="12%">
                              <select name="stajum_2" id="stajum_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_2" id="atpakai_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td>&nbsp;</td>
                            <td class="control-labelh rightJustified" type="hidden" width="15%"></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="7">&nbsp;</td>
                          </tr>
                        </table>
                        <table id="datatble_racikan2" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                          <thead class="page-breadcrumb breadcrumb">
                            <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                            <th class="title-white" width="20%" style="text-align: center">Kode Obat</th>
                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Racik</th>
                            <th class="title-white" width="10%" style="text-align: center">Harga Jual</th>
                            <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                            <th class="title-white" width="15%" style="text-align: center">Expired</th>
                          </thead>
                          <tbody>
                            <tr id="racik2_no1">
                              <td>
                                <button type='button' onclick=hapusBarisIni_racik2(1) disabled class='btn red'><i class='fa fa-trash-o'>
                              </td>
                              <td>
                                <select name="koderacik_2[]" id="koderacik_21" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_2(this.value, 1)" style="width: 100%;"></select>
                                <input name="nama_racik_2[]" id="nama_racik_21" type="hidden" class="form-control">
                              </td>
                              <td>
                                <input name="satracik_2[]" id="satracik_21" type="text" class="form-control" readonly>
                              </td>
                              <td>
                                <input name="qty_jualracik_2[]" id="qty_jualracik_21" onchange="totalline_racik_2(1); total_racik_2(); cekqty_racik_2(1); cekstok_racik_2(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="qty_racik_racik_2[]" id="qty_racik_racik_21" onchange="totalline_racik_2(1); total_racik_2(); cekqty_racik_2(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="hargajualracik_2[]" onchange="totalline_racik_2(1);" value="0" id="hargajualracik_21" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name="total_hrg_racik_2[]" onchange="totalline_racik_2(1);" value="0" id="total_hrg_racik_21" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name='exp_racik_2[]' id='exp_racik_21' type='date' class='form-control rightJustified'>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_2' id='jml_racikan_2'>
                              <button type="button" onclick="tambah_racikan_2()" class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racikan_2" id="toto_racikan_2" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">Resep Manual Dari Dokter
                              <textarea type="text" class="form-control " name="resman_racik_2" id="resman_racik_2" value=""></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_2" id="disknom_racik_2" value="0" onchange="total_racik_2()">
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_2" id="disk_racik_2" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_2" id="cek_ppn_racik_2" onchange="cek_ppn2()" disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_2" id="ppn_racik_2" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_2" id="ongra_racik_2" value="0" onchange="total_racik_2()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_2" id="totp_racik_2" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_2" name="ppn_pajak_racik_2" value="<?= $ppn['prosentase']; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_2" id="t_manual_racik_2" onclick="cekmanual_racik_2()">
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_2" id="toto_racik_2" value="0" readonly onchange="t_jual_manual_racik_2()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="portlet box yellow" id="racik3">
                    <div class="portlet-title">
                      <div class="caption">
                        <span class="title-white"><b>RACIKAN KE - 3</b></span>
                      </div>
                    </div>
                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" border="0" width="100%">
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_3" name="jenis_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <input type="text" class="form-control " name="namaracik_3" id="namaracik_3" value="" Placeholder="Nama">
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_3" id="carapakai_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <option value="DIMINUM"> DIMINUM </option>
                                <option value="DIOLES"> DIOLES </option>
                                <option value="DITETES"> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <input type="number" class="form-control " name="jumracik_3" id="jumracik_3">
                            </td>
                            <td width="12%">
                              <select name="stajum_3" id="stajum_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_3" id="atpakai_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih...">
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                ?>
                                  <option value="<?= $row->apocode; ?>">
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td>&nbsp;</td>
                            <td class="control-labelh rightJustified" type="hidden" width="15%"></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="7">&nbsp;</td>
                          </tr>
                        </table>
                        <table id="datatble_racikan3" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                          <thead class="page-breadcrumb breadcrumb">
                            <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                            <th class="title-white" width="20%" style="text-align: center">Kode Obat</th>
                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Racik</th>
                            <th class="title-white" width="10%" style="text-align: center">Harga Jual</th>
                            <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                            <th class="title-white" width="15%" style="text-align: center">Expired</th>
                          </thead>
                          <tbody>
                            <tr id="racik3_no1">
                              <td>
                                <button type='button' onclick=hapusBarisIni_racik3(1) disabled class='btn yellow'><i class='fa fa-trash-o'>
                              </td>
                              <td>
                                <select name="koderacik_3[]" id="koderacik_31" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_3(this.value, 1)" style="width: 100%;"></select>
                                <input name="nama_racik_3[]" id="nama_racik_31" type="hidden" class="form-control">
                              </td>
                              <td>
                                <input name="satracik_3[]" id="satracik_31" type="text" class="form-control" readonly>
                              </td>
                              <td>
                                <input name="qty_jualracik_3[]" id="qty_jualracik_31" onchange="totalline_racik_3(1); total_racik_3(); cekqty_racik_3(1); cekstok_racik_3(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="qty_racik_racik_3[]" id="qty_racik_racik_31" onchange="totalline_racik_3(1); total_racik_3(); cekqty_racik_3(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="hargajualracik_3[]" onchange="totalline_racik_3(1);" value="0" id="hargajualracik_31" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name="total_hrg_racik_3[]" onchange="totalline_racik_3(1);" value="0" id="total_hrg_racik_31" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name='exp_racik_3[]' id='exp_racik_31' type='date' class='form-control rightJustified'>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_3' id='jml_racikan_3'>
                              <button type="button" onclick="tambah_racikan_3()" class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racikan_3" id="toto_racikan_3" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">Resep Manual Dari Dokter
                              <textarea type="text" class="form-control " name="resman_racik_3" id="resman_racik_3" value=""></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_3" id="disknom_racik_3" value="0" onchange="total_racik_3()">
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_3" id="disk_racik_3" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_3" id="cek_ppn_racik_3" onchange="cek_ppn3()" disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_3" id="ppn_racik_3" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_3" id="ongra_racik_3" value="0" onchange="total_racik_3()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_3" id="totp_racik_3" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_3" name="ppn_pajak_racik_3" value="<?= $ppn['prosentase']; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_3" id="t_manual_racik_3" onclick="cekmanual_racik_3()">
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_3" id="toto_racik_3" value="0" readonly onchange="t_jual_manual_racik_3()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <button id="btnsimpan_racik1" type="button" onclick="save_racik_1()" class="btn blue"><i class="fa fa-save"></i> <b>Posting Racik</b></button>
                      <a href="<?= base_url('penjualan_faktur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                      <h4>
                        <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span>
                        <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Pasien</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Penjamin <font color="red">*</font></label>
                                <div class="col-md-9">
                                  <select class="form-control" style="width:100%;" id="vpenjamin" name="vpenjamin" >
                                    <option value="">Pilih...</option>
                                    <?php $penjamin = $this->db->get("tbl_penjamin")->result();
                                    foreach($penjamin as $row){ 
                                    ?>
                                    <option value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Identitas <font color="red">*</font></label>
                                <div class="col-md-2">
                                  <select name="lupidentitas" id="lupidentitas" class="form-control" onchange="s_identitas()">
                                      <option value="-">Pilih...</option>
                                      <option value="KTP">KTP</option>
                                      <option value="SIM">SIM</option>
                                      <option value="PASPORT">PASPORT</option>
                                      <option value="K_PELAJAR">K_PELAJAR</option>
                                      <option value="KMAHASISWA">KMAHASISWA</option>
                                  </select>
                                </div>
                                <div class="col-md-7" id="k_identitas">
                                  <input type="text" placeholder="No Identitas" name="lupnoidentitas" id="lupnoidentitas" class="form-control" onchange="cek_noidentitas(this.value)">
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">No Member
                                    <font color="red">*</font>
                                  </label>
                                  <div class="col-md-9">
                                      <input name="rekmed" id="rekmed" placeholder="Auto" class="form-control" type="text" readonly>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">No Penjamin <font color="red">*</font></label>
                                  <div class="col-md-6">
                                      <input name="no_bpjs" id="no_bpjs" placeholder="No Kartu" class="form-control" type="number" >
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">Nama Pasien <font color="red">*</font></label>
                                  <div class="col-md-2">
                                    <select class="form-control" name="luppreposition" id="luppreposition">
                                        <option value="">Pilih...</option>
                                        <?php
                                        foreach (setinghms('PREP') as $row) { ?>
                                            <option value="<?= $row->kodeset; ?>"><?= $row->keterangan; ?></option>
                                        <?php } ?>
                                    </select>
                                  </div>
                                  <div class="col-md-7">
                                        <input name="lupnamapasien" required="required" id="lupnamapasien" placeholder="Nama Pasien"  class="form-control" type="text">
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Handphone <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <input name="luphp" id="luphp" placeholder="Dimulai Tanpa 0" class="form-control" maxlength="" type="text" value="+62">
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label col-md-3">Alamat <font color="red">*</font></label>
                                  <div class="col-md-9">
                                      <input name="lupalamat" id="lupalamat" placeholder="Alamat Pasien" class="form-control" type="text" >
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Jenis Kelamin</label>
                                <div class="col-md-6">
                                    <select name="jkelp" id="jkelp" class="form-control">
                                      <option value="P">Pria</option>
                                      <option value="W">Wanita</option>
                                    </select>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label col-md-3">Tgl Lahir</label>
                                <div class="col-md-5 input-medium">
                                  <input id="tgllahirp" name="tgllahirp" type="date" onchange="tgllahirpp()" class="form-control input-medium" >
                                </div>
                                <div class="col-md-4">
                                    <input id="lumurp" name="lumurp" type="text" class="form-control" readonly>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_pass" onclick="save_pasien()" class="btn btn-primary"><b>Simpan</b></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Batal</b></button>
            </div>
        </div>
    </div>
</div>

<?php
  $this->load->view('template/footer');
?>

<!-- MASTER -->
<script>
  $(document).ready(function() {
    // RESEP
    $("#k_identitas").hide();
    $(".select2_all").select2();
    $("#btnsimpan").attr("disabled", true);
    $("#btncetak").attr("disabled", true);
    var gudang = 'APTK';
    initailizeSelect2_farmasi_baranggud(gudang);

    // RACIK
    var ppn_pajak = Number($("#ppn_pajak_racik_1").val()) / 100;
    $("#racik2").hide();
    $("#racik3").hide();
  });

  function separateComma(val) {
    var sign = 1;
    if (val < 0) {
      sign = -1;
      val = -val;
    }
    let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
    let len = num.toString().length;
    let result = '';
    let count = 1;
    for (let i = len - 1; i >= 0; i--) {
      result = num.toString()[i] + result;
      if (count % 3 === 0 && count !== 0 && i !== 0) {
        result = ',' + result;
      }
      count++;
    }
    if (val.toString().includes('.')) {
      result = result + '.' + val.toString().split('.')[1];
    }
    return sign < 0 ? '-' + result : result;
  }

  function _urlcetak() {
    var baseurl = "<?= base_url() ?>";
    var nobukti = $('#noresep').val();
    return baseurl + 'penjualan_faktur/cetak/?nobukti=' + nobukti;
  }

  function ubah_tab(isi) {
    if(isi == 1) {
      $("#racik1").show();
      $("#racik2").hide();
      $("#racik3").hide();
    } else if(isi == 2) {
      $("#racik1").hide();
      $("#racik2").show();
      $("#racik3").hide();
    } else if(isi == 3) {
      $("#racik1").hide();
      $("#racik2").hide();
      $("#racik3").show();
    }
  }
</script>

<!-- PASIEN BARU -->
<script>
  $('#luppreposition').on('change', function() {
    var prep = this.value;
    $.ajax({
      url: "<?php echo base_url();?>app/getvaluesetinghms/?kode=" + prep,
      type: "GET",
      dataType: 'json',
      success: function(data) {
        var hasil = eval(data.data.valuerp);
        if (hasil == 2) {
          $('#jkelp').val('W');
        } else {
          $('#jkelp').val('P');
        }
      }
    });
  });

  function tgllahirr() {
    var birthDate = new Date($('#tgllahir').val());
    var usia = hitung_usia(birthDate);
    $('#lumur').val(usia);
  }

  function tgllahirpp() {
    var birthDate = new Date($('#tgllahirp').val());
    var usia = hitung_usia(birthDate);
    $('#lumurp').val(usia);
  }

  function s_identitas() {
    $("#k_identitas").show();
    var cek = $("#lupnoidentitas").val();
    if(cek == '' || cek == null) {} else {
      cek_noidentitas(cek);
    }
  }

  function cek_noidentitas(identitas) {
    var jenis = $("#lupidentitas").val();
    if(jenis == "KTP") {
      $.ajax({
        url: "<?= site_url('Pendaftaran/cek_noidentitas/'); ?>"+identitas,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if(data.status == 1) {
            $("#lupnoidentitas").val("");
            swal({
              title : "NOMOR IDENTITAS",
              html : "Sudah digunakan, silahkan masukan yang lain",
              type : "error",
              confirmButtonText : "OK"
            });
          }
        }
      });
    }
  }

  function add_pasien() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Pasien Baru');
  }

  function save_pasien() {
    $('#btnSave_pass').text('saving...');
    $('#btnSave_pass').attr('disabled', true);
    var url;
    var v_vpenjamin       = $('[name="vpenjamin"]').val();
    var v_lupidentitas    = $('[name="lupidentitas"]').val();
    var v_lupnoidentitas  = $('[name="lupnoidentitas"]').val();
    var v_no_bpjs         = $('[name="no_bpjs"]').val();
    var v_luppreposition  = $('[name="luppreposition"]').val();
    var v_lupnamapasien   = $('[name="lupnamapasien"]').val();
    var v_luphp           = $('[name="luphp"]').val();
    var v_lupalamat       = $('[name="lupalamat"]').val();
    var v_tgllahir        = $('[name="tgllahirp"]').val();
    if ( v_vpenjamin == '' || v_vpenjamin == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "PENJAMIN",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_luppreposition == '' || v_luppreposition == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "PREPOSISI",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_lupnamapasien == '' || v_lupnamapasien == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "NAMA PASIEN",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }        
    if ( v_lupalamat == '' || v_lupalamat == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "ALAMAT",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_tgllahir == '' || v_tgllahir == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "TANGGAL LAHIR",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_lupidentitas == '' || v_lupidentitas == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "IDENTITAS",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_lupnoidentitas == '' || v_lupnoidentitas == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "NO IDENTITAS",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_no_bpjs == '' || v_no_bpjs == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "NO PENJAMIN",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    if ( v_luphp == '' || v_luphp == null ) {
      $('#modal_form').modal('hide');
      swal({
        title               : "NO HP",
        html                : " Tidak Boleh Kosong .!!!",
        type                : "error",
        confirmButtonText   : "OK"
      }).then((value) => {
        $('#modal_form').modal('show');
      });
      $('#btnSave_pass').text('SIMPAN');
      $('#btnSave_pass').attr('disabled', false);
      return;
    }
    url = "<?= site_url('penjualan_faktur/save_pasien') ?>";
    $.ajax({
      url: url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      success: function(data) {
        if (data.status) {
          if (data.value == 1) {
            $('#btnSave_pass').text('SIMPAN');
            $('#btnSave_pass').attr('disabled', false);
            $('#modal_form').modal('hide');
            swal({
              title: "DATA PASIEN",
              html: "Berhasil dibuat <br/> dengan Nomor Member <br/><b><span style='color:green;font-size:30px;font-weight:bold;'>" + data.rekmed + "</span></b>",
              type: "success",
              confirmButtonText: "OK"
            });
            document.getElementById("btncetak").disabled = false;
            document.getElementById("btncetak1").disabled = false;
          } else {
            $('#modal_form').modal('hide');
            swal({
              title   : "PASIEN",
              html    : " Data Pasien sudah terdaftar .!! <br> CEK LAGI..",
              type    : "error",
              confirmButtonText   : "OK"
            }).then((value) => {
              $('#modal_form').modal('show');
            });
            $('#btnSave_pass').text('SIMPAN');
            $('#btnSave_pass').attr('disabled', false);
          }
        } else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
          }
        }
        $('#btnSave_pass').text('SIMPAN');
        $('#btnSave_pass').attr('disabled', false);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#btnSave_pass').text('SIMPAN');
        $('#btnSave_pass').attr('disabled', false);
      }
    });
  }
</script>

<!-- RESEP -->
<script>
  var rowCount;
  var rowCount2;
  var rowCount3;
  var arr = [1];
  var idrow = 2;
  
  function getdataklinik() {
    var xhttp;
    var str = $('[name=pembeli]').val();
    if (str == "") {} else {
      initailizeSelect2_register(str);
    }
    if (str == 'atr') {
      $('#dokter').prop('disabled', true);
    } else {
      $('#dokter').prop('disabled', false);
    }
  }

  function getkodebaru(gudang) {
    initailizeSelect2_farmasi_baranggud(gudang);
  }

  function getinfopasien(rekmed) {
    if(rekmed != '' || rekmed != null) {
      $.ajax({
        url: "<?= base_url(); ?>pasien/getinfopasien/?id=" + rekmed,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $('#namapasien').val(data.namapas);
          $('#nama_pas').val(data.namapas);
          $('#alamat').val(data.alamat);
          $('#phone').val(data.handphone);
          $('#alamat').val(data.alamat);
          $('#phone').val(data.handphone);
          $('#jkel').val(data.jkel).change();
          $('#tgllahir').val(data.tanggallahir);
          $("#tgllahir").attr("disabled", true);
          $('#lumur').val(hitung_usia(data.tanggallahir));
        }
      });
    } else {
      swal({
        title : "NOMOR REKMED",
        html : "Tidak ditemukan",
        type : "error",
        confirmButtonText : "OK"
      });
    }
  }

  function tambah() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatable");

    table.append(`<tr id="resep_tr`+idrow+`">
      <td><button id='btnhapus` + idrow + `' type='button' onclick='hapusBarisIni(` + idrow + `)' class='btn red'><i class='fa fa-trash-o'></i> </button></td>
      <td>
        <select name="kode[]" id="kode`+idrow+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek(this.value, `+idrow+`)" style="width: 100%;"></select>
        <input name="nama[]" id="nama`+idrow+`" type="hidden" class="form-control">
      </td>
      <td><input name="qty[]" onchange="totalline(`+idrow+`); total(); ceksaldoakhir(`+idrow+`)" value="1" id="qty`+idrow+`" type="text" class="form-control rightJustified"></td>
      <td><input name="sat[]" id="sat`+idrow+`" type="text" class="form-control"></td>
      <td><input name="harga[]" onchange="totalline(`+idrow+`);" value="0" id="harga`+idrow+`" type="text" class="form-control rightJustified" readonly></td>
      <td><input type="checkbox" name="ppn[]" id="ppn`+idrow+`" class="form-control" onchange="totalline(`+idrow+`);total()" disabled></td>
      <td><input name="disc[]" onchange="totalline(`+idrow+`)" value="0" id="disc`+idrow+`" type="text" class="form-control rightJustified "></td>
      <td><input name="disc2[]" value="0" id="disc2`+idrow+`" type="text" onchange="total();myFunction(`+idrow+`)" class="form-control rightJustified "></td>
      <td><input name="jumlah[]" id="jumlah`+idrow+`" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
      <td><textarea name="keterangan[]" id="keterangan`+idrow+`" type="text" class="form-control" style="resize:none" rows="2"></textarea></td>
      <td>
        <select name="aturan_pakai[]" id="aturan_pakai`+idrow+`" class="form-control select2_all" data-placeholder="Pilih..." style="width: 100%;">
          <option value="">Pilih...</option>
          <?php foreach ($atpakaix as $atpx) : ?>
            <option value="<?= $atpx->apocode; ?>"><?= $atpx->aponame; ?></option>
          <?php endforeach; ?>
        </select>
      </td>
      <td>
        <input name="expire[]" value="" id="expire`+idrow+`" type="date" style="width:90%;" class="form-control">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrow++;
    $('.select2_all').select2();
  }

  function hapusBarisIni(param) {
    $("#resep_tr" + param).remove();
    total();
  }

  function showbarangcek(str, id) {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#kode" + id).empty();
        $("#qty" + id).val(1);
        $("#sat" + id).val("");
        $("#harga" + id).val(0);
        $("#disc" + id).val(0);
        $("#disc2" + id).val(0);
        $("#jumlah" + id).val(0);
        $("#keterangan" + id).val("");
        $("#aturan_pakai" + id).val("").change();
        $("#expire" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname(str, id);
      }
    }
  }

  function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $('#sat' + vid).val('');
    var qty = $('#qty' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#harga' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          swal('SALDO BARANG', 'Minus ...', '');
          $("#kode" + vid).empty();
          $("#btnsimpan").attr("disabled", true);
          return;
        }
        if (data == null || data == '') {
          swal('DATA BARANG', 'Kosong ...', '');
          $("#kode" + vid).empty();
          $("#btnsimpan").attr("disabled", true);
          return;
        }
        $('#sat' + vid).val(data.satuan1);
        $('#nama' + vid).val(data.namabarang);
        $('#harga' + vid).val(separateComma(data.hargajual));
        totalline(vid);
        if ($("#nama" + vid).val() != null || $("#nama" + vid).val() != '' || $("#sat" + vid).val() != null || $( "#sat" + vid).val() != '') {
          $("#btnsimpan").attr("disabled", false);
        }
      }
    });
  }

  function cekhargajual(str, harga, id) {
    var gudang = $('#gudang').val();
    var xhttp;
    var vid = id;
    var customer = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/cekharga/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
      }
    });
  }

  function myFunction(id) {
    var discrp = $("#disc2" + id).val();
    var jumlahx = $("#jumlah" + id).val();
    var jumlah = Number(parseInt(jumlahx.replaceAll(',', '')));
    var total = jumlah - discrp;
    if (discrp == "") {
      $("#disc2" + id).val("0");
      $("#jumlah" + id).val(jumlahx);
    } else {
      $("#jumlah" + id).val(separateComma(total));
      $("#disc2" + id).val(separateComma(discrp));
    }
  }

  function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[2].children[0].value * harga;
    vdiskon = (row.cells[6].children[0].value / 100) * jumlah;
    if (eval(vdiskon) > 0) {
      diskon = (row.cells[6].children[0].value / 100) * harga * row.cells[2].children[0].value;
      row.cells[7].children[0].value = separateComma(diskon);
      tot = harga - diskon;
    } else {
      var diskon = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
      row.cells[7].children[0].value = separateComma(diskon);
      tot = harga - diskon;
    }
    tot = jumlah - diskon;
    kode = row.cells[1].children[0].value;
    cekhargajual(kode, harga, id);
    if (document.getElementById('ppn' + id).checked == true) {
      tot = tot * 1.1;
    }
    row.cells[8].children[0].value = separateComma(tot);
    total();
  }

  function total() {
    var cekppn2 = $('#ppn2_').val();
    var table       = document.getElementById('datatable');
    var rowCount    = table.rows.length;
    var subtotal    = 0;
    var diskon      = 0;
    for (i = 1; i < rowCount; i++) {
      var row       = table.rows[i];
      var qtyx      = row.cells[2].children[0].value;
      var qty       = Number(parseInt(qtyx.replaceAll(',', '')));
      var hargax    = row.cells[4].children[0].value;
      var harga     = Number(parseInt(hargax.replaceAll(',', '')));
      var diskon2x  = row.cells[7].children[0].value;
      var diskon2   = Number(parseInt(diskon2x.replaceAll(',', '')));
      var subtot    = qty * harga;
      subtotal      += subtot;
      diskon        += diskon2;
    }
    var total         = subtotal - diskon;
    var dpp           = total / (111 / 100);
    var ppn           = dpp * cekppn2 / 100;
    var ongkosracikx   =  Number(parseInt(($('#totp_racik_1').val()).replaceAll(',','')))
    if(ongkosracikx < 1) {
      ongkosracik = 0;
    } else {
      ongkosracik = ongkosracikx;
    }
    
    document.getElementById("_vsubtotal").innerHTML = separateComma(subtotal.toFixed(0));
    document.getElementById("_vdiskon").innerHTML = separateComma(diskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(ppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma((total+ongkosracik).toFixed(0));
    document.getElementById("_vdpp").innerHTML = separateComma(dpp.toFixed(0));
    document.getElementById("_vracik").innerHTML = separateComma(ongkosracik.toFixed(0));
  }

  function ceksaldoakhir(id) {
    var gudang = $("#gudang").val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    var kode = $("#kode"+id).val();
    var qty = Number($("#qty"+id).val());
    $.ajax({
      url: "<?= site_url('Penjualan_faktur/ceksaldoakhir/'); ?>"+kode+"?gudang="+gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if(qty > data.saldoakhir) {
          $("#qty"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline(id);
      }
    });
  }

  function ceksave() {
    swal({
      title: 'Apakah Menggunakan Racikan ?',
      text: "",
      type: 'info',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      confirmButtonColor: '#227dff',
      cancelButtonColor: '#d33',
      confirmButtonText: 'PAKAI RACIKAN',
      cancelButtonText: 'TIDAK',
    }).then(function() {
      bayar();
    }, function(dismiss) {
      if (dismiss === 'cancel') {
        save();
      }
    })
  }

  function bayar() {
    var table       = document.getElementById('datatable');
    var rowCount    = table.rows.length;
    var tanggal     = $('[name="tanggal"]').val();
    var gudang      = $('[name="gudang"]').val();
    var pembeli     = $('[name="pembeli"]').val();
    var namapasien  = $('[name="namapasien"]').val();
    var nama_pas    = $('[name="nama_pas"]').val();
    var dokter      = $('[name="dokter"]').val();
    var nohp        = $('#phone').val();

    var totalxx = Number(parseInt(($('#_vtotal').text()).replaceAll(',','')));
    var total = totalxx;
    console.log(total)

    var cekhp = $('#reg_cekhp').is(':checked');
    if (pembeli == 'atr' && dokter == '') {
      dokter = '-';
    }
    if (nama_pas == '') {
      swal({
        title: "Nama Pasien",
        html: "<p> Masih Kosong</b> </p>" + "CEK LAGI",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }
    if (pembeli == null || gudang == null || gudang == "" || pembeli == "" || total == "0.00" || total == "") {
      if (gudang == "" || gudang == null) {
        swal('PENJUALAN', 'Depo belum diisi ...', '');
      }
      if (pembeli == "" || pembeli == null) {
        swal('PENJUALAN', 'Pembeli belum diisi ...', '');
      }
      if (total == "" || total == "0.00") {
        swal({
          title: "PENJUALAN",
          html: "Belum ada item barang yang dipilih ...",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    } else {
      swal({
        title: "RESEP TERBENTUK ",
        html: "DENGAN NOMINAL <b>" + total + "</b> <br><br><p> Lanjut Ke Proses Racik...</p>",
        type: "info",
        confirmButtonText: "OK"
      }).then((value) => {
        $('.nav-pills a[href="#tab2"]').tab('show');
      });
    }
  }

  function save() {
    var table       = document.getElementById('datatable');
    var rowCount    = table.rows.length;
    var tgllahir    = $('[name="tgllahir"]').val();
    var bb          = $('[name="bb"]').val();
    var tanggal     = $('[name="tanggal"]').val();
    var pasien      = $('[name="pasien"]').val();
    var gudang      = $('[name="gudang"]').val();
    var pembeli     = $('[name="pembeli"]').val();
    var nohp        = $('#phone').val();
    var cekhp       = $('#reg_cekhp').is(':checked');
    var jumlahtotv  = $('#_vtotal').text();
    var jumlahtot   = Number(parseInt(jumlahtotv.replaceAll(',', '')));
    var racikanxx   = $('#totp_racik_1').val();
    var racikan     = Number(parseInt(racikanxx.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_1').checked == true) {
      var h_manual = 1;
      var totalx = $('#toto_racikan_1').val();
    } else {
      var totalx = $('#totp_racik_1').val();
      var h_manual = 0;
    }
    for (var i = 1; i < rowCount; i++) {
      var expire    = $("#expire" + i).val(); 
      if ($("#kode"+i).val() != null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
      var aturan_pakai    = $("#aturan_pakai" + i).val(); 
      if ($("#kode"+i).val() != null && (aturan_pakai == '' || aturan_pakai == null)) {
        swal({
          title               : "Aturan Pakai",
          html                : "<p>HARUS DI isi</p>",
          type                : "error",
          confirmButtonText   : "OK"
        });
        return;
      }
    }
    var total = Number(parseInt(totalx.replaceAll(',', '')));
    var tabler = document.getElementById('datatble_racikan1');
    var rowCountr = tabler.rows.length;
    for (var i = 1; i < rowCountr; i++) {
      var expire    = $("#exp_racik_1" + i).val(); 
      if ($("#koderacik_1"+i).val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }
    if (gudang == null || gudang == "" || jumlahtot == "0.00" || jumlahtot == "") {
      if (gudang == "" || gudang == null) {
        swal('PENJUALAN', 'Depo belum diisi ...', '');
      }
      if (total == "" || total == "0.00") {
        swal({
          title: "PENJUALAN",
          html: "Belum ada item barang yang dipilih ...",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    } else {
      var params = '?vtotal=' + jumlahtot + "&racikan=" + total;
      $.ajax({
        url: '<?php echo site_url('penjualan_faktur/save/1') ?>' + params,
        data: $('#frmpenjualan').serialize(),
        dataType: "JSON",
        type: 'POST',
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "PENJUALAN RESEP",
              html: "<p> No. Bukti   : <b>" + data.nobukti + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <br><b>" + separateComma(jumlahtot) + "<br> </b>" + "Biaya Racikan<br><b>" + totalx + '</b>',
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('penjualan_faktur/edit/') ?>"+data.nobukti+"/"+2;
              return;
            });
            document.getElementById("tersimpan").value = "OK";
            document.getElementById("noresep").value = data;
            document.getElementById("btnsimpan").disabled = true;
            document.getElementById("btncetak").disabled = false;
            var table = document.getElementById('datatable');
            var rowCount = table.rows.length;
            for (let index = 1; index < rowCount; index++) {
              document.getElementById("kode" + index).disabled = true;
              document.getElementById("nama" + index).disabled = true;
              document.getElementById("qty" + index).disabled = true;
              document.getElementById("sat" + index).disabled = true;
              document.getElementById("harga" + index).disabled = true;
              document.getElementById("disc" + index).disabled = true;
              document.getElementById("disc2" + index).disabled = true;
            }
            document.getElementById("pembeli").disabled = true;
            document.getElementById("noreg").disabled = true;
            document.getElementById("pasien").disabled = true;
            document.getElementById("namapasien").disabled = true;
            document.getElementById("dokter").disabled = true;
            document.getElementById("tanggal").disabled = true;
            document.getElementById("alamat").disabled = true;
            document.getElementById("phone").disabled = true;
          } else if (data.status == 2) {
            swal({
              title: "PENJUALAN RESEP",
              html: "<p> No. Bukti   : <b>" + data.nobukti + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <br><b>" + separateComma(jumlahtot) + "</b>",
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('penjualan_faktur/edit/') ?>"+data.nobukti+"/"+2;
              return;
            });
          }
        },
        error: function(data) {
          swal('PENJUALAN', 'Data gagal disimpan ...', '');
        }
      });
    }
  }
</script>

<!-- RACIKAN -->
<script>
  var idrow2 = 2;
  var idrowobat_1 = 2;
  var idrowobat_2 = 2;
  var idrowobat_3 = 2;
  var idrowobat_4 = 2;
  var cekppn2 = '<?= $ppn["prosentase"] / 100; ?>';

  // RACIKAN 1

  function tambah_racikan_1() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatble_racikan1");

    table.append(`<tr id="racik_no`+idrowobat_1+`">
      <td>
        <button type='button' onclick="hapusBarisIni_racik1(`+idrowobat_1+`)" class='btn purple'><i class='fa fa-trash-o'></i></button>
      </td>
      <td>
        <select name="koderacik_1[]" id="koderacik_1`+idrowobat_1+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek_racik1(this.value, `+idrowobat_1+`)" style="width: 100%;"></select>
        <input name="nama_racik_1[]" id="nama_racik_1`+idrowobat_1+`" type="hidden" class="form-control">
      </td>
      <td>
        <input name="satracik_1[]" id="satracik_1`+idrowobat_1+`" type="text" class="form-control" readonly>
      </td>
      <td>
        <input name="qty_jualracik_1[]" id="qty_jualracik_1`+idrowobat_1+`" onchange="totalline_racik_1(`+idrowobat_1+`); total_racik_1(); cekqty_racik_1(`+idrowobat_1+`); cekstok_racik_1(`+idrowobat_1+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="qty_racik_racik_1[]" id="qty_racik_racik_1`+idrowobat_1+`" onchange="totalline_racik_1(`+idrowobat_1+`); total_racik_1(); cekqty_racik_1(`+idrowobat_1+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="hargajualracik_1[]" onchange="totalline_racik_1(`+idrowobat_1+`);" value="0" id="hargajualracik_1`+idrowobat_1+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name="total_hrg_racik_1[]" onchange="totalline_racik_1(`+idrowobat_1+`);" value="0" id="total_hrg_racik_1`+idrowobat_1+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name='exp_racik_1[]' id='exp_racik_1`+idrowobat_1+`' type='date' class='form-control rightJustified'>
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_1++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik1(param) {
    $("#racik_no" + param).remove();
    total_racik_1();
  }

  function showbarangcek_racik1(str, id) {
    var table = document.getElementById('datatble_racikan1');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#koderacik_1" + id).empty();
        $("#satracik_1" + id).val("");
        $("#qty_jualracik_1" + id).val(1);
        $("#qty_racik_racik_1" + id).val(1);
        $("#hargajualracik_1" + id).val(0);
        $("#_racik_1" + id).val(0);
        $("#total_hrg_racik_1" + id).val(0);
        $("#exp_racik_1" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname_racik_1(str, id);
      }
    }
  }

  function showbarangname_racik_1(str, id) {
    var xhttp;
    var vid = id;
    $('#satracik_1' + vid).val('');
    var qty = $('#qty_jualracik_1' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#hargajualracik_1' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          $("#koderacik_1" + vid).empty();
          $("#nama_racik_1" + vid).val("");
          $("#qty_jualracik_1" + vid).val(1);
          $("#qty_racik_racik_1" + vid).val(1);
          $("#hargajualracik_1" + vid).val(0);
          $("#btnsimpan").attr("disabled", true);
          totalline_racik_1(vid);
          swal('SALDO BARANG', 'Minus ...', '');
          return;
        } else {
          $("#nama_racik_1" + vid).val(data.namabarang);
          $('#satracik_1' + vid).val(data.satuan1);
          $('#hargajualracik_1' + vid).val(separateComma(data.hargajual));
          totalline_racik_1(vid);
          if ($("#nama_racik_1" + vid).val() != null || $( "#nama_racik_1" + vid).val() != '' ||$("#satracik_1" + vid).val() != null || $( "#satracik_1" + vid).val() != '') {
            $("#btnsimpan").attr("disabled", false);
          }
          return;
        }
      }
    });
  }

  function cekqty_racik_1(id) {
    var qtyrx = $("#qty_racik_racik_1"+id).val();
    var qtyjx = $("#qty_jualracik_1"+id).val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik_racik_1"+id).val(qtyj);
      totalline_racik_1(id);
    }
  }

  function cekstok_racik_1(id) {
    var qtyjx = $("#qty_jualracik_1"+id).val();
    var qty = Number(parseInt(qtyjx.replaceAll(',','')));
    var kode = $("#koderacik_1"+id).val();
    var gudang = $("#gudang").val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $.ajax({
      url: "<?= site_url('Penjualan_faktur/ceksaldoakhir/'); ?>"+kode+"?gudang="+gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if(qty > data.saldoakhir) {
          $("#qty_jualracik_1"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline_racik_1(id);
      }
    });
  }

  function totalline_racik_1(id) {
    var table = document.getElementById('datatble_racikan1');
    var row = table.rows[id];
    var kode = row.cells[1].children[0].value;
    var harga = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var qtyjual = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = qtyjual * harga;
    row.cells[6].children[0].value = separateComma(jumlah);
    total_racik_3();
    t_jual_manual_racik_1();
  }

  function t_jual_manual_racik_1() {
    var x = $("#toto_racik_1").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    $("#toto_racik_1").val(separateComma(xx));
    total_racik_2();
  }

  function cekmanual_racik_1() {
    if (document.getElementById('t_manual_racik_1').checked == true) {
      $('#toto_racik_1').attr('readonly', false);
    } else {
      $('#toto_racik_1').attr('readonly', true);
    }
    total_racik_2();
  }

  function total_racik_1() {
    total_racik_3();
  }

  // RACIKAN 2

  function tambah_racikan_2() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatble_racikan2");

    table.append(`<tr id="racik2_no`+idrowobat_2+`">
      <td>
        <button type='button' onclick="hapusBarisIni_racik2(`+idrowobat_2+`)" class='btn red'><i class='fa fa-trash-o'></i></button>
      </td>
      <td>
        <select name="koderacik_2[]" id="koderacik_2`+idrowobat_2+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek_racik2(this.value, `+idrowobat_2+`)" style="width: 100%;"></select>
        <input name="nama_racik_2[]" id="nama_racik_2`+idrowobat_2+`" type="hidden" class="form-control">
      </td>
      <td>
        <input name="satracik_2[]" id="satracik_2`+idrowobat_2+`" type="text" class="form-control" readonly>
      </td>
      <td>
        <input name="qty_jualracik_2[]" id="qty_jualracik_2`+idrowobat_2+`" onchange="totalline_racik_2(`+idrowobat_2+`); total_racik_2(); cekqty_racik_2(`+idrowobat_2+`); cekstok_racik_2(`+idrowobat_2+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="qty_racik_racik_2[]" id="qty_racik_racik_2`+idrowobat_2+`" onchange="totalline_racik_2(`+idrowobat_2+`); total_racik_2(); cekqty_racik_2(`+idrowobat_2+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="hargajualracik_2[]" onchange="totalline_racik_2(`+idrowobat_2+`);" value="0" id="hargajualracik_2`+idrowobat_2+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name="total_hrg_racik_2[]" onchange="totalline_racik_2(`+idrowobat_2+`);" value="0" id="total_hrg_racik_2`+idrowobat_2+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name='exp_racik_2[]' id='exp_racik_2`+idrowobat_2+`' type='date' class='form-control rightJustified'>
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_2++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik2(param) {
    $("#racik2_no" + param).remove();
    total_racik_2();
  }

  function showbarangcek_racik2(str, id) {
    var table = document.getElementById('datatble_racikan2');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#koderacik_2" + id).empty();
        $("#satracik_2" + id).val("");
        $("#qty_jualracik_2" + id).val(1);
        $("#qty_racik_racik_2" + id).val(1);
        $("#hargajualracik_2" + id).val(0);
        $("#_racik_2" + id).val(0);
        $("#total_hrg_racik_2" + id).val(0);
        $("#exp_racik_2" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname_racik_2(str, id);
      }
    }
  }

  function showbarangname_racik_2(str, id) {
    var xhttp;
    var vid = id;
    $('#satracik_2' + vid).val('');
    var qty = $('#qty_jualracik_2' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#hargajualracik_2' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          $("#koderacik_2" + vid).empty();
          $("#nama_racik_2" + vid).val("");
          $("#qty_jualracik_2" + vid).val(1);
          $("#qty_racik_racik_2" + vid).val(1);
          $("#hargajualracik_2" + vid).val(0);
          $("#btnsimpan").attr("disabled", true);
          totalline_racik_2(vid);
          swal('SALDO BARANG', 'Minus ...', '');
        } else {
          $("#nama_racik_2" + vid).val(data.namabarang);
          $('#satracik_2' + vid).val(data.satuan1);
          $('#hargajualracik_2' + vid).val(separateComma(data.hargajual));
          totalline_racik_2(vid);
          if ($("#nama_racik_2" + vid).val() != null || $( "#nama_racik_2" + vid).val() != '' ||$("#satracik_2" + vid).val() != null || $( "#satracik_2" + vid).val() != '') {
            $("#btnsimpan").attr("disabled", false);
          }
        }
      }
    });
  }

  function cekqty_racik_2(id) {
    var qtyrx = $("#qty_racik_racik_2"+id).val();
    var qtyjx = $("#qty_jualracik_2"+id).val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik_racik_2"+id).val(qtyj);
      totalline_racik_2(id);
    }
  }

  function cekstok_racik_2(id) {
    var qtyjx = $("#qty_jualracik_2"+id).val();
    var qty = Number(parseInt(qtyjx.replaceAll(',','')));
    var kode = $("#koderacik_2"+id).val();
    var gudang = $("#gudang").val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $.ajax({
      url: "<?= site_url('Penjualan_faktur/ceksaldoakhir/'); ?>"+kode+"?gudang="+gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if(qty > data.saldoakhir) {
          $("#qty_jualracik_2"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline_racik_2(id);
      }
    });
  }

  function totalline_racik_2(id) {
    var table2 = document.getElementById('datatble_racikan2');
    var row2 = table2.rows[id];
    var kode2 = row2.cells[1].children[0].value;
    var harga2 = Number(row2.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var qtyjual2 = Number(row2.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah2 = qtyjual2 * harga2;
    row2.cells[6].children[0].value = separateComma(jumlah2);
    total_racik_3();
    t_jual_manual_racik_2();
  }

  function t_jual_manual_racik_2() {
    var x = $("#toto_racik_2").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    $("#toto_racik_2").val(separateComma(xx));
    total_racik_2();
  }

  function cekmanual_racik_2() {
    if (document.getElementById('t_manual_racik_2').checked == true) {
      $('#toto_racik_2').attr('readonly', false);
    } else {
      $('#toto_racik_2').attr('readonly', true);
    }
    total_racik_2();
  }

  function total_racik_2() {
    total_racik_3();
  }

  // RACIKAN 3

  function tambah_racikan_3() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatble_racikan3");

    table.append(`<tr id="racik3_no`+idrowobat_3+`">
      <td>
        <button type='button' onclick="hapusBarisIni_racik3(`+idrowobat_3+`)" class='btn yellow'><i class='fa fa-trash-o'></i></button>
      </td>
      <td>
        <select name="koderacik_3[]" id="koderacik_3`+idrowobat_3+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek_racik3(this.value, `+idrowobat_3+`)" style="width: 100%;"></select>
        <input name="nama_racik_3[]" id="nama_racik_3`+idrowobat_3+`" type="hidden" class="form-control">
      </td>
      <td>
        <input name="satracik_3[]" id="satracik_3`+idrowobat_3+`" type="text" class="form-control" readonly>
      </td>
      <td>
        <input name="qty_jualracik_3[]" id="qty_jualracik_3`+idrowobat_3+`" onchange="totalline_racik_3(`+idrowobat_3+`); total_racik_3(); cekqty_racik_3(`+idrowobat_3+`); cekstok_racik_3(`+idrowobat_3+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="qty_racik_racik_3[]" id="qty_racik_racik_3`+idrowobat_3+`" onchange="totalline_racik_3(`+idrowobat_3+`); total_racik_3(); cekqty_racik_3(`+idrowobat_3+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="hargajualracik_3[]" onchange="totalline_racik_3(`+idrowobat_3+`);" value="0" id="hargajualracik_3`+idrowobat_3+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name="total_hrg_racik_3[]" onchange="totalline_racik_3(`+idrowobat_3+`);" value="0" id="total_hrg_racik_3`+idrowobat_3+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name='exp_racik_3[]' id='exp_racik_3`+idrowobat_3+`' type='date' class='form-control rightJustified'>
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_3++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik3(param) {
    $("#racik3_no" + param).remove();
    total_racik_3();
  }

  function showbarangcek_racik3(str, id) {
    var table = document.getElementById('datatble_racikan3');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#koderacik_3" + id).empty();
        $("#satracik_3" + id).val("");
        $("#qty_jualracik_3" + id).val(1);
        $("#qty_racik_racik_3" + id).val(1);
        $("#hargajualracik_3" + id).val(0);
        $("#_racik_3" + id).val(0);
        $("#total_hrg_racik_3" + id).val(0);
        $("#exp_racik_3" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname_racik_3(str, id);
      }
    }
  }

  function showbarangname_racik_3(str, id) {
    var xhttp;
    var vid = id;
    $('#satracik_3' + vid).val('');
    var qty = $('#qty_jualracik_3' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#hargajualracik_3' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          $("#koderacik_3" + vid).empty();
          $("#nama_racik_3" + vid).val("");
          $("#qty_jualracik_3" + vid).val(1);
          $("#qty_racik_racik_3" + vid).val(1);
          $("#hargajualracik_3" + vid).val(0);
          $("#btnsimpan").attr("disabled", true);
          totalline_racik_3(vid);
          swal('SALDO BARANG', 'Minus ...', '');
        } else {
          $("#nama_racik_3" + vid).val(data.namabarang);
          $('#satracik_3' + vid).val(data.satuan1);
          $('#hargajualracik_3' + vid).val(separateComma(data.hargajual));
          totalline_racik_3(vid);
          if ($("#nama_racik_3" + vid).val() != null || $( "#nama_racik_3" + vid).val() != '' ||$("#satracik_3" + vid).val() != null || $( "#satracik_3" + vid).val() != '') {
            $("#btnsimpan").attr("disabled", false);
          }
        }
      }
    });
  }

  function cekqty_racik_3(id) {
    var qtyrx = $("#qty_racik_racik_3"+id).val();
    var qtyjx = $("#qty_jualracik_3"+id).val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik_racik_3"+id).val(qtyj);
      totalline_racik_3(id);
    }
  }

  function cekstok_racik_3(id) {
    var qtyjx = $("#qty_jualracik_3"+id).val();
    var qty = Number(parseInt(qtyjx.replaceAll(',','')));
    var kode = $("#koderacik_3"+id).val();
    var gudang = $("#gudang").val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $.ajax({
      url: "<?= site_url('Penjualan_faktur/ceksaldoakhir/'); ?>"+kode+"?gudang="+gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if(qty > data.saldoakhir) {
          $("#qty_jualracik_3"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline_racik_3(id);
      }
    });
  }

  function totalline_racik_3(id) {
    var table3 = document.getElementById('datatble_racikan3');
    var row3 = table3.rows[id];
    var kode3 = row3.cells[1].children[0].value;
    var harga3 = Number(row3.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var qtyjual3 = Number(row3.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah3 = qtyjual3 * harga3;
    row3.cells[6].children[0].value = separateComma(jumlah3);
    total_racik_3();
    t_jual_manual_racik_3();
  }

  function t_jual_manual_racik_3() {
    var x = $("#toto_racik_3").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    $("#toto_racik_3").val(separateComma(xx));
    total_racik_3();
  }

  function cekmanual_racik_3() {
    if (document.getElementById('t_manual_racik_3').checked == true) {
      $('#toto_racik_3').attr('readonly', false);
    } else {
      $('#toto_racik_3').attr('readonly', true);
    }
    total_racik_3();
  }

  function total_racik_3() {

    // RACIKAN 

    var table = document.getElementById('datatble_racikan1');
    var rowCount = table.rows.length;
    var diskonper = $('#disknom_racik_1').val();
    var ongkosracikx = $('#ongra_racik_1').val();
    var ongkosracik = Number(parseInt(ongkosracikx.replaceAll(',', '')));
    $('#ongra_racik_1').val(separateComma(ongkosracik));
    var cek_ppnn = $('#cek_ppn_racik_1').is(':checked');
    var ppn_pajak = Number($("#ppn_pajak_racik_1").val()) / 100;
    tjumlah = 0;
    tdiskon = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var qtyjual = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah = tjumlah + eval(qtyjual * harga);
    }
    total_done = tjumlah;
    if (diskonper == 0) {
      tdiskon = 0;
    } else {
      tdiskon = total_done * (diskonper / 100);
    }
    if (cek_ppnn == false) {
      tppno = 0;
    } else {
      tppno = (tjumlah - tdiskon) * cekppn;
    }
    diskon_done = tdiskon;
    total_sppn = tjumlah - diskon_done;
    dppx = total_sppn / (111 / 100);
    ppn_done = dppx * ppn_pajak;
    total_ppn = total_sppn;
    ppnc = ((tjumlah - tdiskon) / 111 / 100) * ppn_pajak;
    totpracik1 = total_ppn + ongkosracik;
    
    document.getElementById("toto_racikan_1").value = separateComma(total_done);
    document.getElementById("disk_racik_1").value = separateComma(diskon_done.toFixed(0));
    document.getElementById("ppn_racik_1").value = separateComma(ppn_done.toFixed(0));
    document.getElementById("totp_racik_1").value = separateComma((totpracik1).toFixed(0));

    // RACIKAN 2

    var table2 = document.getElementById('datatble_racikan2');
    var rowCount2 = table2.rows.length;
    var diskonper2 = $('#disknom_racik_2').val();
    var ongkosracik2x = $('#ongra_racik_2').val();
    var ongkosracik2 = Number(parseInt(ongkosracik2x.replaceAll(',', '')));
    $('#ongra_racik_2').val(separateComma(ongkosracik2));
    var cek_ppnn2 = $('#cek_ppn_racik_2').is(':checked');
    var ppn_pajak2 = Number($("#ppn_pajak_racik_2").val()) / 100;
    tjumlah2 = 0;
    tdiskon2 = 0;
    for (var i = 1; i < rowCount2; i++) {
      var row2 = table2.rows[i];
      var qtyjual2 = Number(row2.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga2 = Number(row2.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah2 = tjumlah2 + eval(qtyjual2 * harga2);
    }
    total_done2 = tjumlah2;
    if (diskonper2 == 0) {
      tdiskon2 = 0;
    } else {
      tdiskon2 = total_done2 * (diskonper2 / 100);
    }
    if (cek_ppnn2 == false) {
      tppno2 = 0;
    } else {
      tppno2 = (tjumlah2 - tdiskon2) * cekppn2;
    }
    diskon_done2 = tdiskon2;
    total_sppn2 = tjumlah2 - diskon_done2;
    dpp2x = total_sppn2 / (111 / 100);
    ppn_done2 = dpp2x * ppn_pajak2;
    total_ppn2 = total_sppn2;
    ppnc2 = ((tjumlah2 - tdiskon2) / 111 / 100) * ppn_pajak2;
    totpracik2 = total_ppn2 + ongkosracik2;
    
    document.getElementById("toto_racikan_2").value = separateComma(total_done2);
    document.getElementById("disk_racik_2").value = separateComma(diskon_done2.toFixed(0));
    document.getElementById("ppn_racik_2").value = separateComma(ppn_done2.toFixed(0));
    document.getElementById("totp_racik_2").value = separateComma((totpracik2).toFixed(0));

    // RACIKAN 3

    var table3 = document.getElementById('datatble_racikan3');
    var rowCount3 = table3.rows.length;
    var diskonper3 = $('#disknom_racik_3').val();
    var ongkosracik3x = $('#ongra_racik_3').val();
    var ongkosracik3 = Number(parseInt(ongkosracik3x.replaceAll(',', '')));
    $('#ongra_racik_3').val(separateComma(ongkosracik3));
    var cek_ppnn3 = $('#cek_ppn_racik_3').is(':checked');
    var ppn_pajak3 = Number($("#ppn_pajak_racik_3").val()) / 100;
    tjumlah3 = 0;
    tdiskon3 = 0;
    for (var i = 1; i < rowCount3; i++) {
      var row3 = table3.rows[i];
      var qtyjual3 = Number(row3.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga3 = Number(row3.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah3 = tjumlah3 + eval(qtyjual3 * harga3);
    }
    total_done3 = tjumlah3;
    if (diskonper3 == 0) {
      tdiskon3 = 0;
    } else {
      tdiskon3 = total_done3 * (diskonper3 / 100);
    }
    if (cek_ppnn3 == false) {
      tppno3 = 0;
    } else {
      tppno3 = (tjumlah3 - tdiskon3) * cekppn3;
    }
    diskon_done3 = tdiskon3;
    total_sppn3 = tjumlah3 - diskon_done3;
    dpp3x = total_sppn3 / (111 / 100);
    ppn_done3 = dpp3x * ppn_pajak3;
    total_ppn3 = total_sppn3;
    ppnc3 = ((tjumlah3 - tdiskon3) / 111 / 100) * ppn_pajak3;
    totpracik3 = total_ppn3 + ongkosracik3;
    
    document.getElementById("toto_racikan_3").value = separateComma(total_done3);
    document.getElementById("disk_racik_3").value = separateComma(diskon_done3.toFixed(0));
    document.getElementById("ppn_racik_3").value = separateComma(ppn_done3.toFixed(0));
    document.getElementById("totp_racik_3").value = separateComma((totpracik3).toFixed(0));
    
    // GABUNGAN RACIK 1, 2, 3
    var stx = $("#_vsubtotal").text();
    var stz = Number(parseInt(stx.replaceAll(',','')));
    if(stx == null || stx == '' || stx == 'null') {
      var st = 0;
    } else {
      var st = stz;
    }
    if(document.getElementById("t_manual_racik_1").checked == true && document.getElementById("t_manual_racik_2").checked == false && document.getElementById("t_manual_racik_3").checked == false) {
      var totox = $("#toto_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#totp_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == false && document.getElementById("t_manual_racik_2").checked == true && document.getElementById("t_manual_racik_3").checked == false) {
      var totox = $("#totp_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == true && document.getElementById("t_manual_racik_2").checked == true && document.getElementById("t_manual_racik_3").checked == false) {
      var totox = $("#toto_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == false && document.getElementById("t_manual_racik_2").checked == true && document.getElementById("t_manual_racik_3").checked == true) {
      var totox = $("#totp_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#toto_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == false && document.getElementById("t_manual_racik_2").checked == false && document.getElementById("t_manual_racik_3").checked == true) {
      var totox = $("#totp_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#totp_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#toto_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == true && document.getElementById("t_manual_racik_2").checked == true && document.getElementById("t_manual_racik_3").checked == true) {
      var totox = $("#toto_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#toto_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));
    } else if(document.getElementById("t_manual_racik_1").checked == false && document.getElementById("t_manual_racik_2").checked == false && document.getElementById("t_manual_racik_3").checked == false) {
      var totox = $("#totp_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
      var toto2x = $("#totp_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
      var vracik13 = toto + toto2 + toto3;
      $("#_vracik").text(separateComma((vracik13).toFixed(0)));
      $("#_vtotal").text(separateComma((vracik13 + st).toFixed(0)));
    }
    if (tjumlah > 0) {
      document.getElementById("btnsimpan_racik1").disabled = false;
    } else {
      document.getElementById("btnsimpan_racik1").disabled = true;
    }
  }
</script>

<!-- SIMPAN RESEP -->
<script>
  function save_racik_1() {
    var bb            = $('[name="bb"]').val();
    var tgllahir      = $('[name="tgllahir"]').val();

    // RACIKAN 1

    var jenis_1       = $('[name="jenis_1"]').val();
    var resman_1      = $('[name="resman_racik_1"]').val();
    var namaracik_1   = $('[name="namaracik_1"]').val();
    var jumracik_1    = $('[name="jumracik_1"]').val();
    var stajum_1      = $('[name="stajum_1"]').val();
    var atpakai_1     = $('[name="atpakai_1"]').val();
    var carapakai     = $('[name="carapakai_1"]').val();
    var nobukti       = $('#noresep').val();

    var tabler        = document.getElementById('datatble_racikan1');
    var rowCountr     = tabler.rows.length;
    for (i = 1; i < rowCountr; i++) {
      var expire      = $("#exp_racik_1" + i).val(); 
      if ($("#koderacik_1"+i).val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }
    if (document.getElementById('t_manual_racik_1').checked == true) {
      var h_manual    = 1;
      var totalx      = $('#toto_racikan_1').val();
    } else {
      var totalx      = $('#_vtotal').val();
      var h_manual    = 0;
    }

    var totalxx       = Number(parseInt(totalx.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_1').checked == true) {
      var h_manual    = 1;
    } else {
      var h_manual    = 0;
    }

    if (jenis_1 == '') {
      swal({
        title: "Jenis Masih Kosong",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }

    if (namaracik_1 == '') {
      swal({
        title: "Nama Racik Masih Kosong",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }

    if (jumracik_1 == '') {
      swal({
        title: "Jumlah Masih Kosong",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }

    if (stajum_1 == '') {
      swal({
        title: "Pilihan Jumlah Masih Kosong",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }

    if (atpakai_1 == '') {
      swal({
        title: "Aturan Pakai Masih Kosong",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }

    var table = document.getElementById('datatble_racikan1');
    var rowCount = table.rows.length;
    for (i = 1; i < rowCount; i++) {
      var kode1       = $('#koderacik_1' + i).val();
      var nama1       = $('#nama_racik_1' + i).val();
      var satuan1     = $('#satracik_1' + i).val();
      var qty_jual    = $('#qty_jualracik_1' + i).val();
      var qty_racik   = $('#qty_racik_racik_1' + i).val();
      var harga_jual  = $('#hargajualracik_1' + i).val();
      var total       = $('#total_hrg_racik_1' + i).val();
      var jml         = i;
      $("#jml_racikan_1").val(jml);
    }

    var jmlx1            = $("#jml_racikan_1").val();
    var toto_racikan_1  = Number(parseInt($("#toto_racikan_1").val().replaceAll(',', '')));
    var disk_racik_1    = Number(parseInt($("#disk_racik_1").val().replaceAll(',', '')));
    var ppn_1           = Number(parseInt($("#ppn_racik_1").val().replaceAll(',', '')));
    var totp_racik_1    = Number(parseInt($("#totp_racik_1").val().replaceAll(',', '')));

    // RACIKAN 2

    var jenis_2       = $('[name="jenis_2"]').val();
    var resman_2      = $('[name="resman_racik_2"]').val();
    var namaracik_2   = $('[name="namaracik_2"]').val();
    var jumracik_2    = $('[name="jumracik_2"]').val();
    var stajum_2      = $('[name="stajum_2"]').val();
    var atpakai_2     = $('[name="atpakai_2"]').val();
    var carapakai     = $('[name="carapakai_2"]').val();
    var nobukti       = $('#noresep').val();

    var tabler        = document.getElementById('datatble_racikan2');
    var rowCountr     = tabler.rows.length;
    for (i = 1; i < rowCountr; i++) {
      var expire      = $("#exp_racik_2" + i).val(); 
      if ($("#koderacik_2"+i).val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }

    // RACIKAN 3

    var jenis_3       = $('[name="jenis_3"]').val();
    var resman_3      = $('[name="resman_racik_3"]').val();
    var namaracik_3   = $('[name="namaracik_3"]').val();
    var jumracik_3    = $('[name="jumracik_3"]').val();
    var stajum_3      = $('[name="stajum_3"]').val();
    var atpakai_3     = $('[name="atpakai_3"]').val();
    var carapakai     = $('[name="carapakai_3"]').val();
    var nobukti       = $('#noresep').val();

    var table3        = document.getElementById('datatble_racikan3');
    var rowCount3     = table3.rows.length;
    for (i = 1; i < rowCount3; i++) {
      var expire      = $("#exp_racik_3" + i).val(); 
      if ($("#koderacik_3"+i).val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }

    if($("#koderacik_21").val() != null) {
      if (document.getElementById('t_manual_racik_2').checked == true) {
        var h_manual2    = 1;
        var totalx2      = $('#toto_racikan_2').val();
      } else {
        var totalx2      = $('#_vtotal').val();
        var h_manual2    = 0;
      }
  
      var totalxx2       = Number(parseInt(totalx.replaceAll(',', '')));
      if (document.getElementById('t_manual_racik_2').checked == true) {
        var h_manual2    = 1;
      } else {
        var h_manual2    = 0;
      }
  
      if (jenis_2 == '') {
        swal({
          title: "Jenis Masih Kosong",
          html: "<p>CEK LAGI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
  
      if (namaracik_2 == '') {
        swal({
          title: "Nama Racik Masih Kosong",
          html: "<p>CEK LAGI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
  
      if (jumracik_2 == '') {
        swal({
          title: "Jumlah Masih Kosong",
          html: "<p>CEK LAGI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
  
      if (stajum_2 == '') {
        swal({
          title: "Pilihan Jumlah Masih Kosong",
          html: "<p>CEK LAGI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
  
      if (atpakai_2 == '') {
        swal({
          title: "Aturan Pakai Masih Kosong",
          html: "<p>CEK LAGI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
  
      var table = document.getElementById('datatble_racikan2');
      var rowCount = table.rows.length;
      for (i = 1; i < rowCount; i++) {
        var kode2       = $('#koderacik_2' + i).val();
        var nama2       = $('#nama_racik_2' + i).val();
        var satuan2     = $('#satracik_2' + i).val();
        var qty_jual    = $('#qty_jualracik_2' + i).val();
        var qty_racik   = $('#qty_racik_racik_2' + i).val();
        var harga_jual  = $('#hargajualracik_2' + i).val();
        var total       = $('#total_hrg_racik_2' + i).val();
        var jml         = i;
        $("#jml_racikan_2").val(jml);
      }
  
      var jmlx2           = $("#jml_racikan_2").val();
      var toto_racikan_2  = Number(parseInt($("#toto_racikan_2").val().replaceAll(',', '')));
      var disk_racik_2    = Number(parseInt($("#disk_racik_2").val().replaceAll(',', '')));
      var ppn_2           = Number(parseInt($("#ppn_racik_2").val().replaceAll(',', '')));
      var totp_racik_2    = Number(parseInt($("#totp_racik_2").val().replaceAll(',', '')));

      if($("#koderacik3_1").val() != null) {
        if (document.getElementById('t_manual_racik_3').checked == true) {
          var h_manual3    = 1;
          var totalx3      = $('#toto_racikan_3').val();
        } else {
          var totalx3      = $('#_vtotal').val();
          var h_manual3    = 0;
        }
    
        var totalxx3       = Number(parseInt(totalx.replaceAll(',', '')));
        if (document.getElementById('t_manual_racik_3').checked == true) {
          var h_manual3    = 1;
        } else {
          var h_manual3    = 0;
        }
    
        if (jenis_3 == '') {
          swal({
            title: "Jenis Masih Kosong",
            html: "<p>CEK LAGI</p>",
            type: "error",
            confirmButtonText: "OK"
          });
          return;
        }
    
        if (namaracik_3 == '') {
          swal({
            title: "Nama Racik Masih Kosong",
            html: "<p>CEK LAGI</p>",
            type: "error",
            confirmButtonText: "OK"
          });
          return;
        }
    
        if (jumracik_3 == '') {
          swal({
            title: "Jumlah Masih Kosong",
            html: "<p>CEK LAGI</p>",
            type: "error",
            confirmButtonText: "OK"
          });
          return;
        }
    
        if (stajum_3 == '') {
          swal({
            title: "Pilihan Jumlah Masih Kosong",
            html: "<p>CEK LAGI</p>",
            type: "error",
            confirmButtonText: "OK"
          });
          return;
        }
    
        if (atpakai_3 == '') {
          swal({
            title: "Aturan Pakai Masih Kosong",
            html: "<p>CEK LAGI</p>",
            type: "error",
            confirmButtonText: "OK"
          });
          return;
        }
    
        var table3 = document.getElementById('datatble_racikan3');
        var rowCount3 = table3.rows.length;
        for (i = 1; i < rowCount3; i++) {
          var kode3       = $('#koderacik_3' + i).val();
          var nama3       = $('#nama_racik_3' + i).val();
          var satuan3     = $('#satracik_3' + i).val();
          var qty_jual3   = $('#qty_jualracik_3' + i).val();
          var qty_racik3  = $('#qty_racik_racik_3' + i).val();
          var harga_jual3 = $('#hargajualracik_3' + i).val();
          var total3      = $('#total_hrg_racik_3' + i).val();
          var jml3        = i;
          $("#jml_racikan_3").val(jml3);
        }
    
        var jmlx3            = $("#jml_racikan_3").val();
        var toto_racikan_3  = Number(parseInt($("#toto_racikan_3").val().replaceAll(',', '')));
        var disk_racik_3    = Number(parseInt($("#disk_racik_3").val().replaceAll(',', '')));
        var ppn_3           = Number(parseInt($("#ppn_racik_3").val().replaceAll(',', '')));
        var totp_racik_3    = Number(parseInt($("#totp_racik_3").val().replaceAll(',', '')));

        var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
        "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2 +
        "&toto_racikan_3=" + toto_racikan_3 + "&disk_racik_3=" + disk_racik_3 + "&totp_racik_3=" + totp_racik_3 + "&resman_racik_3=" + resman_3 + "&cek_rm3=" + h_manual3 + "&jml3=" + jmlx3 + "&harga_manual3=" + totalxx3;
      } else {
        var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
        "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2;
      }
    } else {
      var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx;
    }
    $.ajax({
      url: '<?php echo site_url() ?>penjualan_faktur/saveracik/' + param,
      data: $('#frmpenjualan').serialize(),
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if(data.status == 1) {
          save();
        }
      },
      error: function(data) {
        swal({
          title: "PENJUALAN",
          html: "Data gagal disimpan",
          type: "error",
          confirmButtonText: "OK"
        });
      }
    });
  }
</script>

</body>

</html>