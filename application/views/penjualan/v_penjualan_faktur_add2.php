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
                    <select id="pembeli" name="pembeli" class="form-control select2_pembeli" onchange="getdataklinik()">
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
                      ?>
                      <option value="<?= $g->depocode; ?>"><?= $g->keterangan; ?></option>
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
                      <th class="title-white" width="20%" style="text-align: center">Kode Barang</th>
                      <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                      <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                      <th class="title-white" width="10%" style="text-align: center">Harga</th>
                      <th class="title-white" width="2%" style="text-align: center">PPN</th>
                      <th class="title-white" width="3%" style="text-align: center">Disc. %</th>
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
                          <select name="aturan_pakai[]" id="aturan_pakai1" class="form-control select2_atp" data-placeholder="Pilih...">
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
                        <td width="40%"><strong>TOTAL ONGKOS RACIK</strong></td>
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
            <div class="tab-pane" id="tab2" onclick="cek_tab()">
              <div class="row">
                <div class="col-md-12 form-body">
                  <table class="table" border="0" width="100%">
                    <tr bgcolor="#c7f2ff">
                      <td width="10%" class="control-labelh rightJustified">RACIKAN KE</td>
                      <td width="20%">
                        <select id="cekracik" name="cekracik" class="form-control">
                          <option value="1" selected>1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                        </select>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
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
                          <tr>
                            <td colspan="7">&nbsp;</td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_1" name="jenis_1" class="form-control">
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
                              <select name="carapakai" id="carapakai" class="form-control">
                                <option value=""> --- PILIH ----</option>
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
                              <select name="stajum_1" id="stajum_1" class="form-control">
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
                              <select name="atpakai_1" id="atpakai_1" class="form-control">
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
                        <table id="datatableobat_1" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                          <thead class="page-breadcrumb breadcrumb">
                            <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                            <th class="title-white" width="20%" style="text-align: center">Kode Obat</th>
                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Jual</th>
                            <th class="title-white" width="10%" style="text-align: center">Qty Racik</th>
                            <th class="title-white" width="10%" style="text-align: center">Harga Jual</th>
                            <th class="title-white" width="10%" style="text-align: center">Uang R</th>
                            <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                            <th class="title-white" width="15%" style="text-align: center">Expired</th>
                          </thead>
                          <tbody>
                            <tr id="racik_no1">
                              <td>
                                <button type='button' onclick=hapusBarisIni_1(1) disabled class='btn red'><i class='fa fa-trash-o'>
                              </td>
                              <td>
                                <select name="kodeo_1[]" id="kodeo1_1" class="select2_el_farmasi_baranggud form-control input-large" onchange="showbarangnameo_1(this.value, 1);cekstok_1(this.value, 1)"></select>
                              </td>
                              <td>
                                <input name="sato_1[]" id="sato1_1" type="text" class="form-control " onkeypress="return tabE(this,event)">
                              </td>
                              <td>
                                <input name="qty_jual_1[]" id="qty_jual1_1" onchange="totallineo_1(1);totalo_1(); cekqty(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="qty_racik_1[]" id="qty_racik1_1" onchange="totallineo_1(1);totalo_1(); cekqty(1)" value="1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="hargaoju1[]" onchange="totallineo_1(1);" value="0" id="hargaoju1_1" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name="uangr1[]" onchange="totallineo_1(1);" value="0" id="uangr1_1" type="text" class="form-control rightJustified">
                              </td>
                              <td>
                                <input name="total_hrg1[]" onchange="totallineo_1(1);" value="0" id="total_hrg1_1" type="text" class="form-control rightJustified" readonly>
                              </td>
                              <td>
                                <input name='exp1[]' value='' id='exp1_1' type='date' class='form-control rightJustified'>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_1' id='jml_racikan_1'>
                              <button type="button" onclick="tambaho_1()" class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_1" id="toto_1" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              Resep Manual Dari Dokter
                              <textarea type="text" class="form-control " name="resman_1" id="resman_1" value=""></textarea>
                              <br>
                              <div class="wells">
                                <button id="btnsimpan" type="button" onclick="saveracik_1()" class="btn blue"><i class="fa fa-save"></i> <b>Posting Racik</b></button>
                                <a href="<?= base_url('penjualan_faktur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                <h4>
                                  <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span>
                                  <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                                </h4>
                              </div>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_1" id="disknom_1" value="0" onchange="totalo_1()">
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_1" id="disk_1" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn" id="cek_ppn" onchange="cek_ppn2()" disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_1" id="ppn_1" value="0" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="number" class="form-control rightJustified" name="ongra_1" id="ongra_1" value="0" onchange="totalo_1()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_1" id="totp_1" value=0 readonly>
                              <input type="hidden" id="ppn_pajak" name="ppn_pajak" value="<?= $ppn['prosentase']; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual" id="t_manual" onclick="cekmanual()">
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_11" id="toto_11" value="0" readonly onchange="t_jual_manual()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
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
                                    <option value="">--- Pilih ---</option>
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
                                  <select name="lupidentitas" id="lupidentitas" class="form-control input-small">
                                      <option value="-">-- Pilih --</option>
                                      <option value="KTP">KTP</option>
                                      <option value="SIM">SIM</option>
                                      <option value="PASPORT">PASPORT</option>
                                      <option value="K_PELAJAR">K_PELAJAR</option>
                                      <option value="KMAHASISWA">KMAHASISWA</option>
                                  </select>
                                </div>
                                <div class="col-md-7">
                                  <input type="text" placeholder="No Identitas" name="lupnoidentitas" id="lupnoidentitas" class="form-control">
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
                                    <select class="form-control input-small" name="luppreposition" id="luppreposition">
                                        <option value="">-- Pilih --</option>
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

<script type="text/javascript">
  $(document).ready(function() {
    $(".select2_all").select2();
    $("#btnsimpan").attr("disabled", true);
    $("#btncetak").attr("disabled", true);
    var gudang = 'APTK';
    initailizeSelect2_farmasi_baranggud(gudang);

    // $('#racik2').hide();
    // $('#racik3').hide();
    // $('#racik4').hide();

    // var selectElement = document.getElementById('gudang');
    // var opt = document.createElement('option');
    // var str = $('[name=pembeli]').val();
    // var gud = 'APTK';
    // var cekppn2 = 0;
    // opt.value = 'APTK';
    // opt.innerHTML = 'APOTEK';
    // selectElement.appendChild(opt);

    // var ppn_pajakx = $("#ppn_pajak").val();
    // var ppn_pajak = Number(parseInt(ppn_pajakx.replaceAll(',', ''))) / 100;
    // $('.select2_pembeli').select2();

    // $('.select2_atp').select2();
  });

  var rowCount;
  var arr = [1];
  var idrow = 2;
  var idrow2 = 2;
  var idrowobat_1 = 2;
  var idrowobat_2 = 2;
  var idrowobat_3 = 2;
  var idrowobat_4 = 2;

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
          $('#jkel').val(data.jkel);
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

  function tambah() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatable");

    table.append("<tr id='resep_tr" + idrow + "'>" +
      "<td><button id='btnhapus" + idrow + "' type='button' onclick='hapusBarisIni(" + idrow + ")' class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
      "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_baranggud form-control' style='width: 100%;'></select> <input name='nama[]' id='nama" + idrow + "' type='hidden' class='form-control' value='' onchange='totalline(" + idrow + ")'></td>" +
      "<td><input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  ></td>" +
      "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' ></td>" +
      "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified' readonly></td>" +
      "<td><input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totalline(" + idrow + ")' class='form-control' disabled ></td>" +
      "<td><input name='disc[]' id=disc" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
      "<td><input name='disc2[]' id=disc2" + idrow + " onchange='total();myFunction(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
      "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly></td>" +
      "<td><textarea name='keterangan[]' id='keterangan" + idrow + "' type='text' class='form-control' style='resize:none' rows='2'></textarea></td>" +
      "<td><select name='aturan_pakai[]' id='aturan_pakai" + idrow + "' class='form-control select2_all' style='width: 100%;' data-placeholder='Pilih...'><option value=''>Pilih...</option><?php foreach ($atpakaix as $row) : ?> <option value='<?= $row->apocode; ?>'><?= $row->aponame; ?></option><?php endforeach; ?></select></td>" +
      "<td><input name='expire[]' onchange='totalline(1);total()' value='' id='expire" + idrow + "' type='date' style='width:90%;' class='form-control'> </td>"+
      "</tr>");
    initailizeSelect2_farmasi_baranggud(gud);
    idrow++;
    $('.select2_all').select2();
  }

  function hapusBarisIni(param) {
    $("#resep_tr" + param).remove();
    total();
  }

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

  function t_jual_manual() {
    var x = $("#toto_11").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    $("#toto_11").val(separateComma(xx));
    totalo_1();
  }

  function cekmanual() {
    if (document.getElementById('t_manual').checked == true) {
      $('#toto_11').attr('readonly', false);
    } else {
      $('#toto_11').attr('readonly', true);
    }
    totalo_1();
  }

  $('#cekracik').change(function() {
    var cekracik = $(this).val();
    if (cekracik == 2) {
      $('#racik1').hide();
      $('#racik2').show();
      $('#racik3').hide();
      $('#racik4').hide();
    } else if (cekracik == 3) {
      $('#racik1').hide();
      $('#racik2').hide();
      $('#racik3').show();
      $('#racik4').hide();
    } else if (cekracik == 4) {
      $('#racik1').hide();
      $('#racik2').hide();
      $('#racik3').hide();
      $('#racik4').show();
    } else {
      $('#racik1').show();
      $('#racik2').hide();
      $('#racik3').hide();
      $('#racik4').hide();
    }
  });

  function hapusBarisIni_1(param) {
    $("#racik_tr" + param).remove();
    totalo_1();
  }

  function showbarang(str) {
    var xhttp;
    var cust = $('[name="cust"]').val();
    var str = str + '~' + cust;
    if (str == "") {
      document.getElementById("txtHint").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "<?= base_url(); ?>penjualan_faktur/getbarang/" + str, true);
    xhttp.send();
  }

  function showakun(str) {
    var xhttp;
    if (str == "") {
      document.getElementById("daftarakun").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("daftarakun").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "<?= base_url(); ?>penjualan_faktur/getakun/" + str, true);
    xhttp.send();
  }

  function showharga(str) {
    var xhttp;
    if (str == "") {
      document.getElementById("dafhargabeli").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("dafhargabeli").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "<?= base_url(); ?>penjualan_faktur/getharga/" + str, true);
    xhttp.send();
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
    var ongkosracik   = $('#ongra_1').val();
    document.getElementById("_vsubtotal").innerHTML = separateComma(subtotal.toFixed(0));
    document.getElementById("_vdiskon").innerHTML = separateComma(diskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(ppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma(total.toFixed(0));
    document.getElementById("_vdpp").innerHTML = separateComma(dpp.toFixed(0));
    document.getElementById("_vracik").innerHTML = ongkosracik;
  }

  function cekstok(str, id) {
    var gudang = $('#gudang').val();
    var xhttp;
    var vid = id;
    var kode = $("#kode" + id).val();
    var customer = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>Penjualan_faktur/cekstok?kode=" + kode + '&gudang=' + gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if (data.status == 2) {
          swal('PENJUALAN', 'Barang tidak tersedia ...', '');
        } else {
          if (data <= '0') {
            swal('PENJUALAN', 'Stok barang kurang ...', '');
            $('#kode' + vid).val('');
            $('#qty' + vid).val('1');
            $('#sat' + vid).val('');
            $('#nama' + vid).val('');
            $('#harga' + vid).val('');
            $('#jumlah' + vid).val('');
            totalline(1);
          }
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

  function tambaho_1() {
    var gud = $('[name="gudang"]').val();
    var table = $("#datatableobat_1");
    table.append("<tr id='racik_tr" + idrowobat_1 + "'>" +
      "<td><button id='btnhapus" + idrowobat_1 + "' type='button' onclick='hapusBarisIni_1(" + idrowobat_1 +
      ")' class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
      "<td><select name='kodeo_1[]' id='kodeo" + idrowobat_1 +
      "_1' class='select2_el_farmasi_baranggud form-control input-largex' onchange='showbarangnameo_1(this.value," +
      idrowobat_1 + ");cekstok_1(this.value," + idrowobat_1 + ")'></select></td>" +
      "<td><input name='sato_1[]'    id='sato" + idrowobat_1 +
      "_1' type='text' class='form-control '  onkeypress='return tabE(this,event)'></td>" +
      "<td><input name='qty_jual_1[]' id='qty_jual" + idrowobat_1 + "_1' onchange='totallineo_1(" + idrowobat_1 +
      ");totalo_1(); cekqty("+idrowobat_1+")' value='1' type='text' class='form-control rightJustified'  ></td>" +
      "<td><input name='qty_racik_1[]' id='qty_racik" + idrowobat_1 + "_1' onchange='totallineo_1(" + idrowobat_1 +
      ");totalo_1(); cekqty("+idrowobat_1+")' value='1' type='text' class='form-control rightJustified' ></td>" +
      "<td><input name='hargaoju1[]' id='hargaoju" + idrowobat_1 + "_1' onchange='totallineo_1(" + idrowobat_1 +
      ");' value='0'  type='text' class='form-control rightJustified'  readonly></td>" +
      "<td><input name='uangr1[]' id='uangr" + idrowobat_1 + "_1' onchange='totallineo_1(" + idrowobat_1 +
      ");' value='0'  type='text' class='form-control rightJustified'></td>" +
      "<td><input name='total_hrg1[]' id='total_hrg" + idrowobat_1 +
      "_1' value='0' type='text' class='form-control rightJustified'  readonly></td>" +
      "<td><input name='exp1[]' value='' id='exp"+idrowobat_1+"_1' type='date' class='form-control rightJustified'></td>" +
      "</tr>");
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_1++;
  }

  function cekqty(id) {
    var qtyrx = $("#qty_racik"+id+"_1").val();
    var qtyjx = $("#qty_jual"+id+"_1").val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik"+id+"_1").val(qtyj);
      totallineo_1(id);
    }
  }

  function hapuso_1() {
    if (idrowobat_1 > 2) {
      var x = document.getElementById('datatableobat_1').deleteRow(idrowobat_1 - 1);
      idrowobat_1--;
    }
  }

  function showbarangnameo_1(str, id) {
    var xhttp;
    var vid = id;
    var customer = $('#cust').val();
    $('#sato' + vid + '_1').val('');
    $('#namao' + vid + '_1').val('');
    $('#hargaoju' + vid + '_1').val(0);
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#sato' + vid + '_1').val(data.satuan1);
        $('#namao' + vid + '_1').val(data.namabarang.trim());
        $('#hargaoju' + vid + '_1').val(separateComma(data.hargajual));
        $('#qty_racik' + vid + '_1').val(1);
        $('#qty_jual' + vid + '_1').val(1);
        $('#uangr' + vid + '_1').val(0);
        totallineo_1(vid);
      }
    });
  }

  function cekstok_1(str, id) {
    var xhttp;
    var gudang = $('#gudang').val();
    var customer = $('#cust').val();
    var vid = id;
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/cekstok/?kode=" + str + '&gudang=' + gudang,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data <= '0') {
          swal({
            title: "PENJUALAN",
            html: " Stok barang kurang",
            type: "error",
            confirmButtonText: "OK"
          });
          $('#kodeo' + vid + '_1').val('');
          $('#namao' + vid + '_1').val('');
          $('#sato' + vid + '_1').val('');
          $('#qty_racik' + vid + '_1').val('');
          $('#qty_jual' + vid + '_1').val('');
          $('#hargaoju' + vid + '_1').val('');
          $('#uangr' + vid + '_1').val('');
          $('#total_hrg' + vid + '_1').val();
          totallineo_1(1);
        }
      }
    });
  }

  function totallineo_1(id) {
    var table = document.getElementById('datatableobat_1');
    var row = table.rows[id];
    var kode = row.cells[1].children[0].value;
    var harga = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var qtyjual = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    var uangr = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = qtyjual * harga;
    tot = jumlah + uangr;
    cekhargajualo_1(kode, harga, id);
    row.cells[7].children[0].value = separateComma(tot);
    totalo_1();
    t_jual_manual();
  }

  function cekhargajualo_1(str, harga, id) {
    var xhttp;
    var gudang = $('#gudang').val();
    var vid = id;
    var customer = $('#cust').val();
    $.ajax({
      url: "<?= base_url(); ?>penjualan_faktur/cekharga/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {}
    });
  }

  function cek_ppn2() {
    $.ajax({
      url: '<?= base_url(); ?>farmasi_bapb/cekppn',
      type: "GET",
      dataType: "json",
      success: function(data) {
        cekppn = data.prosentase;
        cekppn2 = cekppn / 100;
        totalo_1();
      }
    });
  }

  $('#disknom_1').keyup(function() {
    totalo_1();
  });

  function totalo_1() {
    var table = document.getElementById('datatableobat_1');
    var rowCount = table.rows.length;
    var diskonper = $('#disknom_1').val();
    var ongkosracikx = $('#ongra_1').val();
    var ongkosracik = Number(parseInt(ongkosracikx.replaceAll(',', '')));
    var cek_ppnn = $('#cek_ppn').is(':checked');
    tjumlah = 0;
    tdiskon = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var qtyjual = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      var uangr = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah = tjumlah + eval(qtyjual * harga) + uangr;
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
      tppno = (tjumlah - tdiskon) * cekppn2;
    }
    diskon_done = tdiskon;
    total_sppn = tjumlah - diskon_done;
    dppx = total_sppn / (111 / 100);
    ppn_done = dppx * ppn_pajak;
    total_ppn = total_sppn;
    ppnc = ((tjumlah - tdiskon) / 111 / 100) * ppn_pajak;
    document.getElementById("toto_1").value = separateComma(total_done);
    document.getElementById("disk_1").value = separateComma(diskon_done);
    document.getElementById("ppn_1").value = separateComma(ppn_done.toFixed(0));
    document.getElementById("totp_1").value = separateComma((total_ppn + ongkosracik).toFixed(0));
    var stx = $("#_vsubtotal").text();
    var stz = Number(parseInt(stx.replaceAll(',','')));
    if(stx == null || stx == '' || stx == 'null') {
      var st = 0;
    } else {
      var st = stz;
    }
    if(document.getElementById("t_manual").checked == true) {
      var totox = $("#toto_11").val();
      var toto = Number(totox.replaceAll(',',''));
      $("#_vracik").text(separateComma((toto).toFixed(0)));
      $("#_vtotal").text(separateComma((toto + st).toFixed(0)));
    } else {
      $("#_vracik").text(separateComma((total_ppn + ongkosracik).toFixed(0)));
      $("#_vtotal").text(separateComma((total_ppn + ongkosracik + st).toFixed(0)));
    }
    if (tjumlah > 0) {
      document.getElementById("btnsimpan").disabled = false;
    } else {
      document.getElementById("btnsimpan").disabled = true;
    }
  }

  function post_harga(v1, v2) {
    id = document.getElementById("nopilihharga").value;
    document.getElementById("sat" + id).value = v2;
    document.getElementById("harga" + id).value = v1;
    totalline(id);
  }


  function getidharga(id) {
    var vid = id.substring(8);
    document.getElementById("nopilihharga").value = vid;
    var supp = document.getElementById("cust").value;
    var item = document.getElementById("kode" + vid).value;
    var param = supp + '~' + item;
    showharga(param);
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
            $('#idtr').val(data.idtr);
            var selectElement   = null;
            selectElement       = document.getElementById('pasien');
            var opt             = document.createElement('option');
            opt.value           = data.rekmed;
            opt.innerHTML       = data.rekmed + ' | ' + data.nama + ' | ' + data.alamat;
            selectElement.removeChild(selectElement.lastChild);
            selectElement.appendChild(opt);
            $('#nomember').val(data.rekmed);
            getinfopasien();
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
          }
        } else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
          }
        }
        $('#btnSave_pass').text('Simpan');
        $('#btnSave_pass').attr('disabled', false);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#btnSave_pass').text('save');
        $('#btnSave_pass').attr('disabled', false);
      }
    });
  }

  function ceksave() {
    swal({
      title: 'Apakah Menggunakan Racikan ???',
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

  function saveracik_1() {
    var bb            = $('[name="bb"]').val();
    var tgllahir      = $('[name="tgllahir"]').val();
    var jenis_1       = $('[name="jenis_1"]').val();
    var resman_1      = $('[name="resman_1"]').val();
    var namaracik_1   = $('[name="namaracik_1"]').val();
    var jumracik_1    = $('[name="jumracik_1"]').val();
    var stajum_1      = $('[name="stajum_1"]').val();
    var atpakai_1     = $('[name="atpakai_1"]').val();
    var kodebarang    = $('[name="atpakai_1"]').val();
    var carapakai     = $('[name="carapakai"]').val();
    var nobukti       = $('#noresep').val();
    if (document.getElementById('t_manual').checked == true) {
      var h_manual = 1;
      var totalx = $('#toto_11').val();
    } else {
      var totalx = $('#_vtotal').val();
      var h_manual = 0;
    }
    var totalxx = Number(parseInt(totalx.replaceAll(',', '')));
    if (document.getElementById('t_manual').checked == true) {
      var h_manual = 1;
    } else {
      var h_manual = 0;
    }
    var tabler = document.getElementById('datatableobat_1');
    var rowCountr = tabler.rows.length;
    for (var i = 1; i < rowCountr; i++) {
      var expire    = $("#exp" + i+"_1").val(); 
      if ($("#kodeo"+i+"_1").val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
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
    if (bb == '') {
      swal({
        title: "Berat Badan",
        html: "<p>CEK LAGI</p>",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }
    if (tgllahir == '') {
      swal({
        title: "Tanggal Lahir",
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
    var table = document.getElementById('datatableobat_1');
    var rowCount = table.rows.length;
    for (i = 1; i < rowCount; i++) {
      var kode1       = $('#kodeo' + i + '_1').val();
      var nama1       = $('#namao' + i + '_1').val();
      var satuan1     = $('#sato' + i + '_1').val();
      var qty_racik   = $('#qty_racik' + i + '_1').val();
      var qty_jual    = $('#qty_jual' + i + '_1').val();
      var harga_jual  = $('#hargaoju' + i + '_1').val();
      var uang        = $('#uangr' + i + '_1').val();
      var total       = $('#total_hrg' + i + '_1').val();
      var jml         = i;
      $("#jml_racikan_1").val(jml);
    }
    var jmlx    = $("#jml_racikan_1").val();
    var toto_1  = Number(parseInt($("#toto_1").val().replaceAll(',', '')));
    var disk_1  = Number(parseInt($("#disk_1").val().replaceAll(',', '')));
    var ppn_1   = Number(parseInt($("#ppn_1").val().replaceAll(',', '')));
    var totp_1  = Number(parseInt($("#totp_1").val().replaceAll(',', '')));
    var param   = '?kodeo=' + kode1 + '&namao=' + nama1 + '&satuan=' + satuan1 + '&qty_racik=' + qty_racik + '&qty_jual=' + qty_jual + '&harga=' + harga_jual + '&uang=' + uang + '&total=' + total + "&jml=" + jmlx + "&toto_1=" + toto_1 + "&disk_1=" + disk_1 + "&ppn_1=" + ppn_1 + "&totp_1=" + totp_1 + "&resman_1=" + resman_1 + "&cek_rm=" + h_manual + "&harga_manual=" + totalxx;
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
    var racikanxx   = $('#totp_1').val();
    var racikan     = Number(parseInt(racikanxx.replaceAll(',', '')));
    if (document.getElementById('t_manual').checked == true) {
      var h_manual = 1;
      var totalx = $('#toto_11').val();
    } else {
      var totalx = $('#totp_1').val();
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
    var tabler = document.getElementById('datatableobat_1');
    var rowCountr = tabler.rows.length;
    for (var i = 1; i < rowCountr; i++) {
      var expire    = $("#exp" + i+"_1").val(); 
      if ($("#kodeo"+i+"_1").val() !=  null && (expire == '' || expire == null)) {
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
              html: "<p> No. Bukti   : <b>" + data.nobukti + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <br><b>" + jumlahtotv + "<br> </b>" + "Biaya Racikan<br><b>" + totalx + '</b>',
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>penjualan_faktur";
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
              html: "<p> No. Bukti   : <b>" + data.nobukti + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <br><b>" + jumlahtot + "</b>",
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>penjualan_faktur";
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
    if (document.getElementById('t_manual').checked == true) {
      var totalxx = $('#toto_11').val();
    } else {
      var totalxx = $('#_vtotal').text();
    }
    var total = Number(parseInt(totalxx.replaceAll(',', '')));
    var cekhp = $('#reg_cekhp').is(':checked');
    if (pembeli == 'atr' && dokter == '') {
      dokter = '-';
    }
    if (nama_pas == '') {
      swal({
        title: "Nama Pasien",
        html: "<p> Masih Kosong</b> </p>" +
          "CEK LAGI",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }
    if (nohp == '') {
      swal({
        title: "No Hp Masih Kosong",
        html: "<p> No.Hp : <b>" + nohp + "</b> </p>" +
          "CEK LAGI",
        type: "error",
        confirmButtonText: "OK"
      });
      return;
    }
    if (!cekhp) {
      swal({
        title: "Cek Kembali Nomor Hp Pasien",
        html: "<p> No.Hp : <b>" + nohp + "</b> </p>" +
          "Jika Sudah Benar Lakukan <br>CHECKLIST <br>di Samping Kolom No Hp",
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

  function getbar() {
    var xhttp;
    var gud = $("#gudang").val();
    if (gud == "") {} else {
      initailizeSelect2_farmasi_baranggud(gud);
    }
  }

  function tambah_racikan() {
    $('.nav-pills a[href="#tab2"]').tab('show');
  }

  $("#pembeli").on("change", function() {
    var pembeli = $("#pembeli").val();
    switch (pembeli) {
      case "KULIT":
        $("#gudang").html("<option value='FARMASI' selected>FARMASI TUNAI</option>");
        break;
      case "LOKAL":
        $("#gudang").html("<option value='FARMASI' selected>FARMASI TUNAI</option>");
        break;
      case "KIRIM":
        $("#gudang").html("<option value='FARMASI' selected>FARMASI TUNAI</option>");
        break;
      case "SPA":
        $("#gudang").html("<option value='FARMASISPA' selected>FARMASI SPA</option>");
        break;
      case "GIGI":
        $("#gudang").html("<option value='FARMASIGIG' selected>FARMASI GIGI</option>");
        break;
      case "ONLINE":
        $("#gudang").html("<option value='ONLINE' selected>ONLINE</option>");
        break;
      case "APOTIK":
        $("#gudang").html("<option value='FARMASIAPT' selected>FARMASI APOTIK</option>");
        break;
    }
    if (pembeli == 'atr') {
      $('#dokter').prop('disabled', true);
    } else {
      $('#dokter').prop('disabled', false);
    }
    getbar();
  });

  function getdataregistrasi() {
    var xhttp;
    var str = $('[name=noreg]').val();
    if (str == "") {} else {
      $.ajax({
        url: "<?= base_url(); ?>kasir_konsul/getdataregistrasi/?noreg=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $('#pasien').html(data.rekmed);
          $('#namapasien').val(data.namapas);
          $('#alamat').val(data.alamat);
          $('#phone').val(data.handphone);
          $('#reg_poli').val(data.kodepos);
          var selectElement = document.getElementById('pasien');
          var opt = document.createElement('option');
          opt.value = data.rekmed;
          opt.innerHTML = data.rekmed;
          selectElement.appendChild(opt);
          var selectElement = document.getElementById('dokter');
          var opt = document.createElement('option');
          opt.value = data.kodokter;
          opt.innerHTML = data.kodokter + ' | ' + data.nadokter;
          selectElement.appendChild(opt);
        }
      });
    }
  }

  function _urlcetak() {
    var baseurl = "<?= base_url() ?>";
    var nobukti = $('#noresep').val();
    return baseurl + 'penjualan_faktur/cetak/?nobukti=' + nobukti;
  }
</script>

</body>

</html>