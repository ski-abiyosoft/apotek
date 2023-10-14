<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
  $datpas   = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$header->rekmed'")->row();
  if ($datpas) {
    $age_date       = new DateTime($datpas->tgllahir);
    $age_now        = new DateTime();
    $age_interval   = $age_now->diff($age_date);
  } else {
    $age_interval = (object) [
      "y"  => 0,
      "m"  => 0,
      "d"  => 0,
    ];
  }
  $umur = $age_interval->y . ' Tahun ' . $age_interval->m . ' Bulan ' . $age_interval->d . ' Hari';
  if($noedit == "") {
    $edited   = "";
    $edited2  = "";
  } else {
    $edited   = "disabled";
    $edited2  = "readonly";
  }
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
        <a class="title-white" href="<?= site_url('dashboard'); ?>">Awal</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= site_url('penjualan_faktur'); ?>">Daftar Faktur Penjualan</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">Update Faktur</a>
      </li>
    </ul>
  </div>
</div>

<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i><b>*Data Update</b>
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
                    <select id="pembeli" name="pembeli" class="form-control select2_pembeli select2_all" onchange="getdataklinik()" <?= $edited; ?>>
                      <option value="adr" <?php if($header->kodepel == "adr") { echo "selected"; } else { echo ""; } ?>>Apotik Dengan Resep</option>
                      <option value="atr" <?php if($header->kodepel == "atr") { echo "selected"; } else { echo ""; } ?>>Apotik Tanpa Resep</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Resep Dari <font color="red">*</font></label>
                  <div class="col-md-9">
                    <input type="text" id="dokter" name="dokter" class="form-control" placeholder="dr .." value="<?= $header->kodokter; ?>" <?= $edited2; ?>>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                  <div class="col-md-9">
                    <select id="gudang" name="gudang" class="form-control select2_all" onchange="getkodebaru(this.value)" style="width: 100%;" data-placeholder="APOTEK" <?= $edited; ?>>
                      <option value="">APOTEK</option>
                      <?php
                      $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE (konekpos = 'FARMASI' OR konekpos = 'APOTEK')")->result(); 
                      foreach($gudang as $g) :
                        if($g->depocode == $header->gudang) { $sel = "selected"; } else { $sel = ""; }
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
                    <input type="text" id="noresep" name="noresep" class="form-control" readonly value="<?= $header->resepno; ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                  <div class="col-md-6">
                    <input id="tanggal" name="tanggal" class="form-control" type="date" value="<?= date('Y-m-d', strtotime($header->tglresep)); ?>"  readonly/>
                  </div>
                  <div class="col-md-3">
                    <input type="time" class="form-control" name="jam" id="jam" value="<?= date('H:i:s', strtotime($header->jam)); ?>" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Alamat Kirim</label>
                  <div class="col-md-9">
                    <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $header->alamat; ?>" <?= $edited2; ?>>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Member</label>
                  <div class="col-md-6">
                    <select id="pasien" name="pasien" class="form-control select2_el_pasien" onchange="getinfopasien(this.value)" data-placeholder="Pilih..." style="width: 100%;" <?= $edited; ?>>
                      <?php if($datpas->rekmed == "Non Member") : ?>
                      <option value="<?= $datpas->rekmed; ?>"><?= $datpas->rekmed." | ".$datpas->namapas; ?></option>
                      <input type="hidden" name="namapasien" id="namapasien" class="form-control" value="<?= $datpas->namapas; ?>">
                      <?php endif; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <span class="input-group-btn">
                      <a class="btn-sm btn green" style="width: 100%; border-radius: 5px;" onclick="add_pasien()" <?= $edited; ?>><i class="fa fa-plus"></i> Pasien Baru</a>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Jenis Kelamin</label>
                  <div class="col-md-9">
                    <select name="jkel" id="jkel" class="form-control select2_all" <?= $edited; ?>>
                      <?php if($datpas) : ?>
                        <option value="P" <?php if($datpas->jkel == "P") { echo "selected"; } else { echo ""; } ?>>Pria</option>
                        <option value="W" <?php if($datpas->jkel == "W") { echo "selected"; } else { echo ""; } ?>>Wanita</option>
                      <?php else : ?>
                        <option value="P">Pria</option>
                        <option value="W">Wanita</option>
                      <?php endif; ?>
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
                    <?php if($header) : ?>
                      <input id="nama_pas" name="nama_pas" type="text" class="form-control" style="text-transform: uppercase !important" value="<?= $header->pro; ?>" <?= $edited2; ?>>
                    <?php else : ?>
                      <input id="nama_pas" name="nama_pas" type="text" class="form-control" style="text-transform: uppercase !important">
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Tgl Lahir</label>
                  <div class="col-md-4">
                    <?php if($datpas) : ?>
                      <input id="tgllahir" name="tgllahir" type="date" onchange="tgllahirr()" class="form-control" value="<?= date('Y-m-d', strtotime($datpas->tgllahir)); ?>" <?= $edited2; ?>>
                    <?php else : ?>
                      <input id="tgllahir" name="tgllahir" type="date" onchange="tgllahirr()" class="form-control" value="<?= date('Y-m-d'); ?>">
                    <?php endif; ?>
                  </div>
                  <div class="col-md-5">
                    <input id="lumur" name="lumur" type="text" class="form-control" readonly value="<?= $umur; ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Berat Badan</label>
                  <div class="col-md-6">
                    <input type="number" name="bb" id="bb" class="form-control" value="<?= $header->bb; ?>" <?= $edited2; ?>>
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
                    <input type="text" name="phone" id="phone" class="form-control" value="<?= $header->nohp; ?>" <?= $edited2; ?>>
                  </div>
                  <div class="col-md-2"> 
                    <input type="checkbox" checked id="reg_cekhp" name="reg_cekhp" value="1" class="form-control" <?= $edited; ?>>
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
              <?php 
                if($racikansem > 0) {
                  $jumr = "<span style='height: 20px; width: 20px; text-align: center; color: white; background-color: #9b1405; border-radius: 50%; display: inline-block;'>$racikansem</span>";
                }
              ?>
              <?php if($racikansem > 0) : ?>
                <a href="#tab2" data-toggle="tab">Racikan <?= $jumr; ?></a>
              <?php else : ?>
                <a href="#tab2" data-toggle="tab">Racikan</a>
              <?php endif; ?>
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
                      <?php 
                        if($jumdata > 0) : 
                          $no = 1;
                          foreach ($detil as $key => $value) :
                      ?>
                      <tr id="resep_tr<?= $no; ?>">
                        <td>
                          <?php if($noedit == "") : ?>
                            <button type='button' id='btnhbi<?= $no; ?>' onclick='hapusBarisIni(<?= $no; ?>)' class='btn red' <?php if($no == 1) { echo "disabled"; } else { echo ""; }?>><i class='fa fa-trash-o'></i></button>
                          <?php else : ?>
                            <button type='button' id='btnhbi<?= $no; ?>' onclick='hapusBarisIni(<?= $no; ?>)' class='btn red' disabled><i class='fa fa-trash-o'></i></button>
                          <?php endif; ?>
                        </td>
                        <td>
                          <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek(this.value, <?= $no; ?>)" style="width: 100%;" <?= $edited; ?>>
                            <option value="<?= $value->kodebarang; ?>"><?= $value->kodebarang." | ".$value->namabarang; ?></option>
                          </select>
                          <input name="nama[]" id="nama<?= $no; ?>" type="hidden" class="form-control" value="<?= $value->namabarang; ?>">
                        </td>
                        <td>
                          <input name="qty[]" onchange="totalline(<?= $no; ?>);total(); ceksaldoakhir(<?= $no; ?>)" value="<?= number_format($value->qty); ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                        </td>
                        <td>
                          <input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control" value="<?= $value->satuan; ?>" <?= $edited2; ?>>
                        </td>
                        <td>
                          <input name="harga[]" onchange="totalline(<?= $no; ?>);" value="<?= number_format($value->price); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                        </td>
                        <td>
                          <input type="checkbox" name="ppn[]" id="ppn<?= $no; ?>" class="form-control" onchange="totalline(<?= $no; ?>); total()" <?= $edited; ?> disabled>
                        </td>
                        <td>
                          <input name="disc[]" onchange="cekdisc(<?= $no; ?>); totalline(<?= $no; ?>)" value="<?= number_format($value->discount); ?>" id="disc<?= $no; ?>" type="text" class="form-control rightJustified " <?= $edited2; ?>>
                        </td>
                        <td>
                          <input name="disc2[]" value="<?= number_format($value->discrp); ?>" id="disc2<?= $no; ?>" type="text" onchange="total(); myFunction(<?= $no; ?>); totalline(<?= $no; ?>)" class="form-control rightJustified" <?= $edited2; ?>>
                        </td>
                        <td>
                          <input name="jumlah[]" id="jumlah<?= $no; ?>" type="text" value="<?= number_format($value->totalrp); ?>" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                        </td>
                        <td>
                          <textarea name="keterangan[]" id="keterangan<?= $no; ?>" type="text" class="form-control" style="resize:none" rows="2" <?= $edited2; ?>><?= $value->ket; ?></textarea>
                        </td>
                        <td>
                          <select name="aturan_pakai[]" id="aturan_pakai<?= $no; ?>" class="form-control select2_all" data-placeholder="Pilih..." style="width: 100%;" <?= $edited; ?>>
                            <option value="">Pilih...</option>
                            <?php foreach ($atpakaix as $atpx) : ?>
                              <?php if($atpx->apocode == $value->atpakai) { $atp = "selected"; } else { $atp = ""; } ?>
                              <option value="<?= $atpx->apocode; ?>" <?= $atp; ?>><?= $atpx->aponame; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <input name="expire[]" id="expire<?= $no; ?>" type="date" style="width:90%;" class="form-control" min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($value->exp_date)); ?>" <?= $edited2; ?>>
                        </td>
                      </tr>
                      <?php 
                            $no++;
                          endforeach; 
                        else :
                      ?>
                      <tr id="resep_tr1">
                        <td><button type='button' id='btnhbi1' onclick="hapusBarisIni(1)" disabled class='btn red' <?= $edited; ?>><i class='fa fa-trash-o'></i></button></td>
                        <td>
                          <select name="kode[]" id="kode1" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname(this.value, 1)" style="width: 100%;"></select>
                          <input name="nama[]" id="nama1" type="hidden" class="form-control">
                        </td>
                        <td><input name="qty[]" onchange="totalline(1);total(); ceksaldoakhir(1)" value="1" id="qty1" type="text" class="form-control rightJustified" <?= $edited2; ?>></td>
                        <td><input name="sat[]" id="sat1" type="text" class="form-control" <?= $edited2; ?>></td>
                        <td><input name="harga[]" onchange="totalline(1);" value="0" id="harga1" type="text" class="form-control rightJustified" readonly></td>
                        <td><input type="checkbox" name="ppn[]" id="ppn1" class="form-control" onchange="totalline(1);total()" <?= $edited; ?>></td>
                        <td><input name="disc[]" onchange="cekdisc(1);totalline(1)" value="0" id="disc1" type="text" class="form-control rightJustified" <?= $edited2; ?>></td>
                        <td><input name="disc2[]" value="0" id="disc21" type="text" onchange="total();myFunction(1);totalline(1)" class="form-control rightJustified" <?= $edited2; ?>></td>
                        <td><input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
                        <td><textarea name="keterangan[]" id="keterangan1" type="text" class="form-control" style="resize:none" rows="2" <?= $edited2; ?>></textarea></td>
                        <td>
                          <select name="aturan_pakai[]" id="aturan_pakai1" class="form-control select2_all" data-placeholder="Pilih..." style="width: 100%;" <?= $edited; ?>>
                            <option value="">Pilih...</option>
                            <?php foreach ($atpakaix as $atpx) : ?>
                              <option value="<?= $atpx->apocode; ?>"><?= $atpx->aponame; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <input name="expire[]" id="expire1" type="date" style="width:90%;" class="form-control" min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited2; ?>>
                        </td>
                      </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-xs-9">
                      <div class="wells">
                        <button type="button" onclick="tambah()" class="btn green" <?= $edited; ?>><i class="fa fa-plus"></i> </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-xs-7">
                  <div class="wells">
                    <?php if($noedit == "") : ?>
                      <button id="btnsimpan" type="button" onclick="ceksave()" class="btn blue" style="width: 20%;">
                        <i class="fa fa-save"></i> <b>Posting Resep</b>
                      </button>
                    <?php endif; ?>
                    <a href="<?= site_url('Penjualan_faktur/entri'); ?>" type="button" class="btn green" style="width: 20%;"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></a>
                    <a href="<?= site_url('penjualan_faktur') ?>" type="button" class="btn btn red" style="width: 20%;"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                    <h4>
                      <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span>
                      <span id="success" style="display:none; color:#0C0"><b>Data sudah disimpan...</b></span>
                    </h4>
                  </div>
                </div>
                <div class="col-xs-7 invoice-block">
                  <div class="wells">
                    <button id="btnsimpan" type="button" onclick="etiket()" class="btn yellow" style="width: 20%;">
                      <i class="fa fa-print"></i><b> ETiket</b>
                    </button>
                    <button type="button" onclick="telaah();" class="btn yellow" style="width: 20%;">
                      <i class="fa fa-print"></i><b> Telaah</b>
                    </button>
                    <button type="button" onclick="urlcetak_cr();" class="btn yellow" style="width: 20%;">
                      <i class="fa fa-print"></i><b> Copy Resep</b>
                    </button>
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
                        <input type="hidden" id="ppn2_" name="ppn2_" value="<?= $ppn->prosentase; ?>">
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
                          <option value="5">5</option>
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
                              <select id="jenis_1" name="jenis_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                    if($racik1) {
                                      if($row->apocode == $racik1->jenisracik) {
                                        $jenisr1 = "selected";
                                      } else {
                                        $jenisr1 = "";
                                      }
                                    } else {
                                      $jenisr1 = "";
                                    }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $jenisr1; ?>><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <?php 
                                if($racik1) {
                                  $namar1 = $racik1->namaracikan;
                                } else {
                                  $namar1 = "";
                                }
                              ?>
                              <input type="text" class="form-control" name="namaracik_1" id="namaracik_1" value="<?= $namar1; ?>" Placeholder="Nama" <?= $edited2; ?>>
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_1" id="carapakai_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <option value="DIMINUM" <?php if($racik1) { if($racik1->carapakai == "DIMINUM") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIMINUM </option>
                                <option value="DIOLES" <?php if($racik1) { if($racik1->carapakai == "DIOLES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIOLES </option>
                                <option value="DITETES" <?php if($racik1) { if($racik1->carapakai == "DITETES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <?php 
                                if($racik1) {
                                  if($racik1->jumlahracik) {
                                    $jumr1 = $racik1->jumlahracik;
                                  } else {
                                    $jumr1 = "";
                                  }
                                } else {
                                  $jumr1 = "";
                                }
                              ?>
                              <input type="number" class="form-control " name="jumracik_1" id="jumracik_1" value="<?= $jumr1; ?>" <?= $edited2; ?>>
                            </td>
                            <td width="12%">
                              <select name="stajum_1" id="stajum_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                  if($racik1) {
                                    if($row->apocode == $racik1->kemasanracik) {
                                      $satr1 = "selected";
                                    } else {
                                      $satr1 = "";
                                    }
                                  } else {
                                    $satr1 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $satr1; ?>>
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_1" id="atpakai_1" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                  if($racik1) {
                                    if($row->apocode == $racik1->aturanpakai) {
                                      $atpr1 = "selected";
                                    } else {
                                      $atpr1 = "";
                                    }
                                  } else {
                                    $atpr1 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $atpr1; ?>>
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
                            <?php 
                              if($racik1) : 
                                $nor1 = 1;
                                foreach($detil_r1x as $r1) :
                            ?>
                              <tr id="racik_no<?= $nor1; ?>">
                                <td>
                                  <?php if($noedit == "") : ?>
                                    <button type='button' id='btnhbi1<?= $nor1; ?>' onclick='hapusBarisIni_racik1(<?= $nor1; ?>)' class='btn purple' <?php if($detil_r1 < 2) { echo "disabled"; } else { echo ""; } ?>><i class='fa fa-trash-o'></i></button>
                                  <?php else : ?>
                                    <button type='button' id='btnhbi1<?= $nor1; ?>' onclick='hapusBarisIni_racik1(<?= $nor1; ?>)' class='btn purple' disabled><i class='fa fa-trash-o'></i></button>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <select name="koderacik_1[]" id="koderacik_1<?= $nor1; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_1(this.value, <?= $nor1; ?>)" style="width: 100%;" <?= $edited; ?>>
                                    <?php $barangr1 = $this->db->get_where("tbl_barang", ["kodebarang" => $r1->kodebarang])->row(); ?>
                                    <option value="<?= $barangr1->kodebarang; ?>"><?= $barangr1->kodebarang." | ".$barangr1->namabarang; ?></option>
                                  </select>
                                  <input name="nama_racik_1[]" id="nama_racik_1<?= $nor1; ?>" type="hidden" class="form-control" value="<?= $barangr1->namabarang; ?>">
                                </td>
                                <td>
                                  <input name="satracik_1[]" id="satracik_1<?= $nor1; ?>" type="text" class="form-control" readonly value="<?= $r1->satuan; ?>" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_jualracik_1[]" id="qty_jualracik_1<?= $nor1; ?>" onchange="totalline_racik_1(<?= $nor1; ?>); total_racik_1(); cekqty_racik_1(<?= $nor1; ?>); cekstok_racik_1(<?= $nor1; ?>)" value="<?= number_format($r1->qty); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_1[]" id="qty_racik_racik_1<?= $nor1; ?>" onchange="totalline_racik_1(<?= $nor1; ?>); total_racik_1(); cekqty_racik_1(<?= $nor1; ?>)" value="<?= number_format($r1->qtyr); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_1[]" onchange="totalline_racik_1(<?= $nor1; ?>);" value="<?= number_format($r1->price); ?>" id="hargajualracik_1<?= $nor1; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_1[]" onchange="totalline_racik_1(<?= $nor1; ?>);" value="<?= number_format($r1->totalrp); ?>" id="total_hrg_racik_1<?= $nor1; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_1[]' id='exp_racik_1<?= $nor1; ?>' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($r1->exp_date)); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php
                                  $nor1++;
                                endforeach;
                              else : 
                            ?>
                              <tr id="racik_no1">
                                <td>
                                  <button type='button' id='btnhbi11' onclick='hapusBarisIni_racik1(1)' disabled class='btn purple'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name="koderacik_1[]" id="koderacik_11" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_1(this.value, 1)" style="width: 100%;" <?= $edited; ?>></select>
                                  <input name="nama_racik_1[]" id="nama_racik_11" type="hidden" class="form-control">
                                </td>
                                <td>
                                  <input name="satracik_1[]" id="satracik_11" type="text" class="form-control" readonly>
                                </td>
                                <td>
                                  <input name="qty_jualracik_1[]" id="qty_jualracik_11" onchange="totalline_racik_1(1); total_racik_1(); cekqty_racik_1(1); cekstok_racik_1(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_1[]" id="qty_racik_racik_11" onchange="totalline_racik_1(1); total_racik_1(); cekqty_racik_1(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_1[]" onchange="totalline_racik_1(1);" value="0" id="hargajualracik_11" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_1[]" onchange="totalline_racik_1(1);" value="0" id="total_hrg_racik_11" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_1[]' id='exp_racik_11' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_1' id='jml_racikan_1'>
                              <button type="button" onclick="tambah_racikan_1()" class="btn green" <?= $edited; ?>><i class="fa fa-plus"></i></button>
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
                              <?php 
                                if($racik1) {
                                  $rdr1 = $racik1->resep_manual;
                                } else {
                                  $rdr1 = "";
                                }
                              ?>
                              <textarea type="text" class="form-control " name="resman_racik_1" id="resman_racik_1" value="<?= $rdr1; ?>" <?= $edited; ?>><?= $rdr1; ?></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <?php
                                if($racik1) { $diskonr1 = $racik1->diskon; } else { $diskonr1 = 0; }
                              ?>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_1" id="disknom_racik_1" value="<?= number_format($diskonr1); ?>" onchange="total_racik_1()" <?= $edited2; ?>>
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
                            <?php
                              if($racik1) { $ppnrpr1 = $racik1->ppnrp; } else { $ppnrpr1 = 0; }
                            ?>
                            <td>
                              <?php
                                if($ppnrpr1 > 0) { $cekppnr1 = "checked"; } else { $cekppnr1 = ""; }
                              ?>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_1" id="cek_ppn_racik_1" onchange="cek_ppn2()" disabled <?= $cekppnr1; ?>>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_1" id="ppn_racik_1" value="<?= number_format($ppnrpr1); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <?php 
                                if($racik1) {
                                  $ongkirr1 = $racik1->ongkosracik;
                                } else {
                                  $ongkirr1 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_1" id="ongra_racik_1" value="<?= number_format($ongkirr1); ?>" <?= $edited2; ?> onchange="total_racik_1()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_1" id="totp_racik_1" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_1" name="ppn_pajak_racik_1" value="<?= $ppn->prosentase; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <?php 
                                if($racik1) {
                                  $hargamr1 = $racik1->harga_manual;
                                } else {
                                  $hargamr1 = 0;
                                }
                                if($hargamr1 > 0) {
                                  $cekhmr1 = "checked";
                                } else {
                                  $cekhmr1 = "";
                                }
                              ?>
                              <input type="checkbox" class="form-control" name="t_manual_racik_1" id="t_manual_racik_1" onclick="cekmanual_racik_1()" <?= $cekhmr1; ?> <?= $edited; ?>>
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_1" id="toto_racik_1" value="<?= number_format($hargamr1); ?>" readonly onchange="t_jual_manual_racik_1()">
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
                              <select id="jenis_2" name="jenis_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                    if($racik2) {
                                      if($row->apocode == $racik2->jenisracik) {
                                        $jenisr2 = "selected";
                                      } else {
                                        $jenisr2 = "";
                                      }
                                    } else {
                                      $jenisr2 = "";
                                    }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $jenisr2; ?>><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <?php 
                                if($racik2) {
                                  $namar2 = $racik2->namaracikan;
                                } else {
                                  $namar2 = "";
                                }
                              ?>
                              <input type="text" class="form-control " name="namaracik_2" id="namaracik_2" value="<?= $namar2; ?>" Placeholder="Nama" <?= $edited2; ?>>
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_2" id="carapakai_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <option value="DIMINUM" <?php if($racik2) { if($racik2->carapakai == "DIMINUM") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIMINUM </option>
                                <option value="DIOLES" <?php if($racik2) { if($racik2->carapakai == "DIOLES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIOLES </option>
                                <option value="DITETES" <?php if($racik2) { if($racik2->carapakai == "DITETES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <?php 
                                if($racik2) {
                                  if($racik2->jumlahracik) {
                                    $jumr2 = $racik2->jumlahracik;
                                  } else {
                                    $jumr2 = "";
                                  }
                                } else {
                                  $jumr2 = "";
                                }
                              ?>
                              <input type="number" class="form-control " name="jumracik_2" id="jumracik_2" value="<?= $jumr2; ?>" <?= $edited2; ?>>
                            </td>
                            <td width="12%">
                              <select name="stajum_2" id="stajum_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                  if($racik2) {
                                    if($row->apocode == $racik2->kemasanracik) {
                                      $satr2 = "selected";
                                    } else {
                                      $satr2 = "";
                                    }
                                  } else {
                                    $satr1 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $satr2; ?>>
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_2" id="atpakai_2" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                  if($racik2) {
                                    if($row->apocode == $racik2->aturanpakai) {
                                      $atpr2 = "selected";
                                    } else {
                                      $atpr2 = "";
                                    }
                                  } else {
                                    $atpr2 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $atpr2; ?>>
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
                            <?php 
                              if($racik2) : 
                                $nor2 = 1; 
                                foreach ($detil_r2x as $r2) :
                            ?>
                                  <tr id="racik2_no<?= $nor2; ?>">
                                    <td>
                                      <?php if($noedit == "") : ?>
                                        <button type='button' id='btnhbi2<?= $nor2; ?>' onclick='hapusBarisIni_racik2(<?= $nor2; ?>)' class='btn red' <?php if($detil_r2 < 2) { echo "disabled"; } else { echo ""; } ?>><i class='fa fa-trash-o'></i></button>
                                      <?php else : ?>
                                        <button type='button' id='btnhbi2<?= $nor2; ?>' onclick='hapusBarisIni_racik2(<?= $nor2; ?>)' class='btn red' disabled><i class='fa fa-trash-o'></i></button>
                                      <?php endif; ?>
                                    </td>
                                    <td>
                                      <select name="koderacik_2[]" id="koderacik_2<?= $nor2; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_2(this.value, <?= $nor2; ?>)" style="width: 100%;" <?= $edited; ?>>
                                        <?php $barangr2 = $this->db->get_where("tbl_barang", ["kodebarang" => $r2->kodebarang])->row(); ?>
                                        <option value="<?= $barangr2->kodebarang; ?>"><?= $barangr2->kodebarang." | ".$barangr2->namabarang; ?></option>
                                      </select>
                                      <input name="nama_racik_2[]" id="nama_racik_2<?= $nor2; ?>" type="hidden" class="form-control" value="<?= $barangr2->namabarang; ?>">
                                    </td>
                                    <td>
                                      <input name="satracik_2[]" id="satracik_2<?= $nor2; ?>" type="text" class="form-control" readonly value="<?= $r2->satuan; ?>">
                                    </td>
                                    <td>
                                      <input name="qty_jualracik_2[]" id="qty_jualracik_2<?= $nor2; ?>" onchange="totalline_racik_2(<?= $nor2; ?>); total_racik_2(); cekqty_racik_2(<?= $nor2; ?>); cekstok_racik_2(<?= $nor2; ?>)" value="<?= number_format($r2->qty); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                    </td>
                                    <td>
                                      <input name="qty_racik_racik_2[]" id="qty_racik_racik_2<?= $nor2; ?>" onchange="totalline_racik_2(<?= $nor2; ?>); total_racik_2(); cekqty_racik_2(<?= $nor2; ?>)" value="<?= number_format($r2->qtyr); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                    </td>
                                    <td>
                                      <input name="hargajualracik_2[]" onchange="totalline_racik_2(<?= $nor2; ?>);" value="<?= number_format($r2->price); ?>" id="hargajualracik_2<?= $nor2; ?>" type="text" class="form-control rightJustified" readonly>
                                    </td>
                                    <td>
                                      <input name="total_hrg_racik_2[]" onchange="totalline_racik_2(<?= $nor2; ?>);" value="<?= number_format($r2->totalrp); ?>" id="total_hrg_racik_2<?= $nor2; ?>" type="text" class="form-control rightJustified" readonly>
                                    </td>
                                    <td>
                                      <input name='exp_racik_2[]' id='exp_racik_2<?= $nor2; ?>' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($r2->exp_date)); ?>" <?= $edited2; ?>>
                                    </td>
                                  </tr>
                            <?php 
                                  $nor2++;
                                endforeach;
                            ?>
                            <?php else : ?>
                              <tr id="racik2_no1">
                                <td>
                                  <button type='button' id='btnhbi51' onclick='hapusBarisIni_racik2(1)' disabled class='btn red'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name="koderacik_2[]" id="koderacik_21" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_2(this.value, 1)" style="width: 100%;" <?= $edited; ?>></select>
                                  <input name="nama_racik_2[]" id="nama_racik_21" type="hidden" class="form-control">
                                </td>
                                <td>
                                  <input name="satracik_2[]" id="satracik_21" type="text" class="form-control" readonly>
                                </td>
                                <td>
                                  <input name="qty_jualracik_2[]" id="qty_jualracik_21" onchange="totalline_racik_2(1); total_racik_2(); cekqty_racik_2(1); cekstok_racik_2(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_2[]" id="qty_racik_racik_21" onchange="totalline_racik_2(1); total_racik_2(); cekqty_racik_2(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_2[]" onchange="totalline_racik_2(1);" value="0" id="hargajualracik_21" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_2[]" onchange="totalline_racik_2(1);" value="0" id="total_hrg_racik_21" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_2[]' id='exp_racik_21' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_2' id='jml_racikan_2'>
                              <button type="button" onclick="tambah_racikan_2()" class="btn green" <?= $edited; ?>><i class="fa fa-plus"></i></button>
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
                              <?php 
                                if($racik2) {
                                  $rdr2 = $racik2->resep_manual;
                                } else {
                                  $rdr2 = "";
                                }
                              ?>
                              <textarea type="text" class="form-control " name="resman_racik_2" id="resman_racik_2" value="<?= $rdr2; ?>" <?= $edited; ?>><?= $rdr2; ?></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <?php 
                                if($racik2) {
                                  $diskonr2 = $racik2->diskon;
                                  $diskonrpr2 = $racik2->diskonrp;
                                } else {
                                  $diskonr2 = 0;
                                  $diskonrpr2 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_2" id="disknom_racik_2" value="<?= number_format($diskonr2); ?>" onchange="total_racik_2()" <?= $edited; ?>>
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_2" id="disk_racik_2" value="<?= number_format($diskonrpr2); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <?php 
                              if($racik2) {
                                $ppnrpr2 = $racik2->ppnrp;
                              } else {
                                $ppnrpr2 = 0;
                              }
                              if($ppnrpr2 > 0) {
                                $cekppnr2 = "checked";
                              } else {
                                $cekppnr2 = "";
                              }
                            ?>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_2" id="cek_ppn_racik_2" onchange="cek_ppn2()" <?= $cekppnr2; ?> disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_2" id="ppn_racik_2" value="<?= number_format($ppnrpr2); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <?php 
                                if($racik2) {
                                  $ongkirr2 = $racik2->ongkosracik;
                                } else {
                                  $ongkirr2 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_2" id="ongra_racik_2" value="<?= number_format($ongkirr2); ?>" onchange="total_racik_2()" <?= $edited2; ?>>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_2" id="totp_racik_2" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_2" name="ppn_pajak_racik_2" value="<?= $ppn->prosentase; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <td width="6%">
                              <?php 
                                if($racik2) {
                                  $hargamr2 = $racik2->harga_manual;
                                } else {
                                  $hargamr2 = 0;
                                }
                                if($hargamr2 > 0) {
                                  $cekhmr2 = "checked";
                                } else {
                                  $cekhmr2 = "";
                                }
                              ?>
                              <input type="checkbox" class="form-control" name="t_manual_racik_2" id="t_manual_racik_2" onclick="cekmanual_racik_2()" <?= $cekhmr2; ?> <?= $edited; ?>>
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_2" id="toto_racik_2" value="<?= number_format($hargamr2); ?>" readonly onchange="t_jual_manual_racik_2()">
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
                              <select id="jenis_3" name="jenis_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                    if($racik3) {
                                      if($row->apocode == $racik3->jenisracik) {
                                        $jenisr3 = "selected";
                                      } else {
                                        $jenisr3 = "";
                                      }
                                    } else {
                                      $jenisr3 = "";
                                    }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $jenisr3; ?>><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <?php 
                                if($racik3) {
                                  $namar3 = $racik3->namaracikan;
                                } else {
                                  $namar3 = "";
                                }
                              ?>
                              <input type="text" class="form-control " name="namaracik_3" id="namaracik_3" value="<?= $namar3; ?>" Placeholder="Nama" <?= $edited; ?>>
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_3" id="carapakai_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <option value="DIMINUM" <?php if($racik3) { if($racik3->carapakai == "DIMINUM") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIMINUM </option>
                                <option value="DIOLES" <?php if($racik3) { if($racik3->carapakai == "DIOLES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIOLES </option>
                                <option value="DITETES" <?php if($racik3) { if($racik3->carapakai == "DITETES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <?php 
                                if($racik3) {
                                  if($racik3->jumlahracik) {
                                    $jumr3 = $racik3->jumlahracik;
                                  } else {
                                    $jumr3 = "";
                                  }
                                } else {
                                  $jumr3 = "";
                                }
                              ?>
                              <input type="number" class="form-control " name="jumracik_3" id="jumracik_3" value="<?= $jumr3; ?>" <?= $edited2; ?>>
                            </td>
                            <td width="12%">
                              <select name="stajum_3" id="stajum_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                  if($racik3) {
                                    if($row->apocode == $racik3->kemasanracik) {
                                      $satr3 = "selected";
                                    } else {
                                      $satr3 = "";
                                    }
                                  } else {
                                    $satr3 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $satr3; ?>>
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_3" id="atpakai_3" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                  if($racik3) {
                                    if($row->apocode == $racik3->aturanpakai) {
                                      $atpr3 = "selected";
                                    } else {
                                      $atpr3 = "";
                                    }
                                  } else {
                                    $atpr3 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $atpr3; ?>>
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
                            <?php 
                              if($racik3) : 
                                $nor3 = 1;
                                foreach($detil_r3x as $r3) :
                            ?>
                              <tr id="racik3_no<?= $nor3; ?>">
                                <td>
                                  <?php if($noedit == "") : ?>
                                    <button type='button' id='btnhbi3<?= $nor3; ?>' onclick='hapusBarisIni_racik3(<?= $nor3; ?>)' class='btn yellow' <?php if($detil_r3 < 3) { echo "disabled"; } else { echo ""; } ?>><i class='fa fa-trash-o'></i></button>
                                  <?php else : ?>
                                    <button type='button' id='btnhbi3<?= $nor3; ?>' onclick='hapusBarisIni_racik3(<?= $nor3; ?>)' class='btn yellow' disabled><i class='fa fa-trash-o'></i></button>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <select name="koderacik_3[]" id="koderacik_3<?= $nor3; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_3(this.value, <?= $nor3; ?>)" style="width: 100%;" <?= $edited; ?>>
                                    <?php $barangr3 = $this->db->get_where("tbl_barang", ["kodebarang" => $r3->kodebarang])->row(); ?>
                                    <option value="<?= $barangr3->kodebarang; ?>"><?= $barangr3->kodebarang." | ".$barangr3->namabarang; ?></option>
                                  </select>
                                  <input name="nama_racik_3[]" id="nama_racik_3<?= $nor3; ?>" type="hidden" class="form-control" value="<?= $barangr3->namabarang; ?>">
                                </td>
                                <td>
                                  <input name="satracik_3[]" id="satracik_3<?= $nor3; ?>" type="text" class="form-control" readonly value="<?= $r3->satuan; ?>">
                                </td>
                                <td>
                                  <input name="qty_jualracik_3[]" id="qty_jualracik_3<?= $nor3; ?>" onchange="totalline_racik_3(<?= $nor3; ?>); total_racik_3(); cekqty_racik_3(<?= $nor3; ?>); cekstok_racik_3(<?= $nor3; ?>)" value="<?= number_format($r3->qty); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_3[]" id="qty_racik_racik_3<?= $nor3; ?>" onchange="totalline_racik_3(<?= $nor3; ?>); total_racik_3(); cekqty_racik_3(<?= $nor3; ?>)" value="<?= number_format($r3->qtyr); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_3[]" onchange="totalline_racik_3(<?= $nor3; ?>);" value="<?= number_format($r3->price); ?>" id="hargajualracik_3<?= $nor3; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_3[]" onchange="totalline_racik_3(<?= $nor3; ?>);" value="<?= number_format($r3->totalrp); ?>" id="total_hrg_racik_3<?= $nor3; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_3[]' id='exp_racik_3<?= $nor3; ?>' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($r3->exp_date)); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php 
                                  $nor3++;
                                endforeach;
                              else : 
                            ?>
                              <tr id="racik3_no1">
                                <td>
                                  <button type='button' id='btnhbi31' onclick="hapusBarisIni_racik3(1)" disabled class='btn yellow'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name="koderacik_3[]" id="koderacik_31" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_3(this.value, 1)" style="width: 100%;" <?= $edited; ?>></select>
                                  <input name="nama_racik_3[]" id="nama_racik_31" type="hidden" class="form-control">
                                </td>
                                <td>
                                  <input name="satracik_3[]" id="satracik_31" type="text" class="form-control" readonly>
                                </td>
                                <td>
                                  <input name="qty_jualracik_3[]" id="qty_jualracik_31" onchange="totalline_racik_3(1); total_racik_3(); cekqty_racik_3(1); cekstok_racik_3(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_3[]" id="qty_racik_racik_31" onchange="totalline_racik_3(1); total_racik_3(); cekqty_racik_3(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_3[]" onchange="totalline_racik_3(1);" value="0" id="hargajualracik_31" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_3[]" onchange="totalline_racik_3(1);" value="0" id="total_hrg_racik_31" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_3[]' id='exp_racik_31' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_3' id='jml_racikan_3'>
                              <button type="button" onclick="tambah_racikan_3()" class="btn green" <?= $edited; ?>><i class="fa fa-plus"></i></button>
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
                              <?php 
                                if($racik3) {
                                  $rdr3 = $racik3->resep_manual;
                                } else {
                                  $rdr3 = "";
                                }
                              ?>
                              <textarea type="text" class="form-control " name="resman_racik_3" id="resman_racik_3" value="<?= $rdr3; ?>" <?= $edited; ?>><?= $rdr3; ?></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <td>
                              <?php 
                                if($racik3) {
                                  $diskonr3 = $racik3->diskon;
                                  $diskonrpr3 = $racik3->diskonrp;
                                } else {
                                  $diskonr3 = 0;
                                  $diskonrpr3 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_3" id="disknom_racik_3" value="<?= number_format($diskonr3); ?>" onchange="total_racik_3()" <?= $edited2; ?>>
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_3" id="disk_racik_3" value="<?= number_format($diskonrpr3); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <td>
                              <?php 
                              if($racik3) {
                                $ppnrpr3 = $racik3->ppnrp;
                              } else {
                                $ppnrpr3 = 0;
                              }
                              if($ppnrpr3 > 0) {
                                $cekppnr3 = "checked";
                              } else {
                                $cekppnr3 = "";
                              }
                              ?>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_3" id="cek_ppn_racik_3" onchange="cek_ppn3()" disabled <?= $cekppnr3; ?>>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_3" id="ppn_racik_3" value="<?= number_format($ppnrpr3); ?>" readonly >
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <?php 
                                if($racik3) {
                                  $ongkirr3 = $racik3->ongkosracik;
                                } else {
                                  $ongkirr3 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_3" id="ongra_racik_3" value="<?= number_format($ongkirr3); ?>" <?= $edited2; ?> onchange="total_racik_3()">
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_3" id="totp_racik_3" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_3" name="ppn_pajak_racik_3" value="<?= $ppn->prosentase; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <?php 
                              if($racik3) {
                                $hargamr3 = $racik3->harga_manual;
                              } else {
                                $hargamr3 = 0;
                              }
                              if($hargamr3 > 0) {
                                $cekhmr3 = "checked";
                              } else {
                                $cekhmr3 = "";
                              }
                            ?>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_3" id="t_manual_racik_3" onclick="cekmanual_racik_3()" <?= $cekhmr3; ?> <?= $edited; ?>>
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_3" id="toto_racik_3" value="<?= number_format($hargamr3); ?>" readonly onchange="t_jual_manual_racik_3()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="portlet box blue" id="racik4">
                    <div class="portlet-title">
                      <div class="caption">
                        <span class="title-white"><b>RACIKAN KE - 4</b></span>
                      </div>
                    </div>
                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" border="0" width="100%">
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_4" name="jenis_4" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                    if($racik4) {
                                      if($row->apocode == $racik4->jenisracik) {
                                        $jenisr4 = "selected";
                                      } else {
                                        $jenisr4 = "";
                                      }
                                    } else {
                                      $jenisr4 = "";
                                    }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $jenisr4; ?>><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <?php 
                                if($racik4) {
                                  $namar4 = $racik4->namaracikan;
                                } else {
                                  $namar4 = "";
                                }
                              ?>
                              <input type="text" class="form-control " name="namaracik_4" id="namaracik_4" value="<?= $namar4; ?>" Placeholder="Nama" <?= $edited2; ?>>
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_4" id="carapakai_4" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <option value="DIMINUM" <?php if($racik4) { if($racik4->carapakai == "DIMINUM") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIMINUM </option>
                                <option value="DIOLES" <?php if($racik4) { if($racik4->carapakai == "DIOLES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIOLES </option>
                                <option value="DITETES" <?php if($racik4) { if($racik4->carapakai == "DITETES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <?php 
                                if($racik4) {
                                  if($racik4->jumlahracik) {
                                    $jumr4 = $racik4->jumlahracik;
                                  } else {
                                    $jumr4 = "";
                                  }
                                } else {
                                  $jumr4 = "";
                                }
                              ?>
                              <input type="number" class="form-control " name="jumracik_4" id="jumracik_4" value="<?= $jumr4; ?>" <?= $edited2; ?>>
                            </td>
                            <td width="12%">
                              <select name="stajum_4" id="stajum_4" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                  if($racik4) {
                                    if($row->apocode == $racik4->kemasanracik) {
                                      $satr4 = "selected";
                                    } else {
                                      $satr4 = "";
                                    }
                                  } else {
                                    $satr4 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $satr4; ?>>
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_4" id="atpakai_4" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                  if($racik4) {
                                    if($row->apocode == $racik4->aturanpakai) {
                                      $atpr4 = "selected";
                                    } else {
                                      $atpr4 = "";
                                    }
                                  } else {
                                    $atpr4 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $atpr4; ?>>
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
                        <table id="datatble_racikan4" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
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
                            <?php
                              if($racik4) :
                                $nor4 = 1;
                                foreach($detil_r4x as $r4) :
                            ?>
                              <tr id="racik4_no<?= $nor4; ?>">
                                <td>
                                  <?php if($noedit == "") : ?>
                                    <button type='button' id='btnhbi4<?= $nor4; ?>' onclick='hapusBarisIni_racik4(<?= $nor4; ?>)' class='btn btn-primary' <?php if($detil_r4 < 4) { echo "disabled"; } else { echo ""; } ?>><i class='fa fa-trash-o'></i></button>
                                  <?php else : ?>
                                    <button type='button' id='btnhbi4<?= $nor4; ?>' onclick='hapusBarisIni_racik4(<?= $nor4; ?>)' class='btn btn-primary' disabled><i class='fa fa-trash-o'></i></button>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <select name="koderacik_4[]" id="koderacik_4<?= $nor4; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_4(this.value, <?= $nor4; ?>)" style="width: 100%;" <?= $edited; ?>>
                                    <?php $barangr4 = $this->db->get_where("tbl_barang", ["kodebarang" => $r4->kodebarang])->row(); ?>
                                    <option value="<?= $barangr4->kodebarang; ?>"><?= $barangr4->kodebarang." | ".$barangr4->namabarang; ?></option>
                                  </select>
                                  <input name="nama_racik_4[]" id="nama_racik_4<?= $nor4; ?>" type="hidden" class="form-control" value="<?= $barangr4->namabarang; ?>">
                                </td>
                                <td>
                                  <input name="satracik_4[]" id="satracik_4<?= $nor4; ?>" type="text" class="form-control" readonly value="<?= $r4->satuan; ?>">
                                </td>
                                <td>
                                  <input name="qty_jualracik_4[]" id="qty_jualracik_4<?= $nor4; ?>" onchange="totalline_racik_4(<?= $nor4; ?>); total_racik_4(); cekqty_racik_4(<?= $nor4; ?>); cekstok_racik_4(<?= $nor4; ?>)" value="<?= number_format($r4->qty); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_4[]" id="qty_racik_racik_4<?= $nor4; ?>" onchange="totalline_racik_4(<?= $nor4; ?>); total_racik_4(); cekqty_racik_4(<?= $nor4; ?>)" value="<?= number_format($r4->qtyr); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_4[]" onchange="totalline_racik_4(<?= $nor4; ?>);" value="<?= number_format($r4->price); ?>" id="hargajualracik_4<?= $nor4; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_4[]" onchange="totalline_racik_4(<?= $nor4; ?>);" value="<?= number_format($r4->totalrp); ?>" id="total_hrg_racik_4<?= $nor4; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_4[]' id='exp_racik_4<?= $nor4; ?>' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($r4->exp_date)); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php
                                  $nor4++;
                                endforeach;
                              else :
                            ?>
                              <tr id="racik4_no1">
                                <td>
                                  <button type='button' id='btnhbi41' onclick="hapusBarisIni_racik4(1)" disabled class='btn btn-primary'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name="koderacik_4[]" id="koderacik_41" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_4(this.value, 1)" style="width: 100%;" <?= $edited; ?>></select>
                                  <input name="nama_racik_4[]" id="nama_racik_41" type="hidden" class="form-control">
                                </td>
                                <td>
                                  <input name="satracik_4[]" id="satracik_41" type="text" class="form-control" readonly>
                                </td>
                                <td>
                                  <input name="qty_jualracik_4[]" id="qty_jualracik_41" onchange="totalline_racik_4(1); total_racik_4(); cekqty_racik_4(1); cekstok_racik_4(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_4[]" id="qty_racik_racik_41" onchange="totalline_racik_4(1); total_racik_4(); cekqty_racik_4(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_4[]" onchange="totalline_racik_4(1);" value="0" id="hargajualracik_41" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_4[]" onchange="totalline_racik_4(1);" value="0" id="total_hrg_racik_41" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_4[]' id='exp_racik_41' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited; ?>>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_4' id='jml_racikan_4'>
                              <button type="button" onclick="tambah_racikan_4()" <?= $edited; ?> class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racikan_4" id="toto_racikan_4" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">Resep Manual Dari Dokter
                              <?php 
                                if($racik4) {
                                  $rdr4 = $racik4->resep_manual;
                                } else {
                                  $rdr4 = "";
                                }
                              ?>
                              <textarea type="text" class="form-control " name="resman_racik_4" id="resman_racik_4" <?= $edited2; ?> value="<?= $rdr4; ?>"><?= $rdr4; ?></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <?php 
                              if($racik4) {
                                $diskonr4 = $racik4->diskon;
                                $diskonrpr4 = $racik4->diskonrp;
                              } else {
                                $diskonr4 = 0;
                                $diskonrpr4 = 0;
                              }
                            ?>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_4" id="disknom_racik_4" value="<?= number_format($diskonr4); ?>" <?= $edited2; ?> onchange="total_racik_4()">
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_4" id="disk_racik_4" value="<?= number_format($diskonrpr4); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <?php 
                              if($racik4) {
                                $ppnrpr4 = $racik4->ppnrp;
                              } else {
                                $ppnrpr4 = 0;
                              }
                              if($ppnrpr4 > 0) {
                                $cekppnr4 = "checked";
                              } else {
                                $cekppnr4 = "";
                              }
                            ?>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_4" id="cek_ppn_racik_4" onchange="cek_ppn4()" <?= $cekppnr4; ?> disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_4" id="ppn_racik_4" value="<?= number_format($ppnrpr4); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <?php 
                                if($racik4) {
                                  $ongkirr4 = $racik4->ongkosracik;
                                } else {
                                  $ongkirr4 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_4" id="ongra_racik_4" value="<?= number_format($ongkirr4); ?>" onchange="total_racik_4()" <?= $edited2; ?>>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_4" id="totp_racik_4" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_4" name="ppn_pajak_racik_4" value="<?= $ppn->prosentase; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <?php 
                              if($racik4) {
                                $hargamr4 = $racik4->harga_manual;
                              } else {
                                $hargamr4 = 0;
                              }
                              if($hargamr4 > 0) {
                                $cekhmr4 = "checked";
                              } else {
                                $cekhmr4 = "";
                              }
                            ?>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_4" id="t_manual_racik_4" onclick="cekmanual_racik_4()" <?= $cekhmr4; ?> <?= $edited; ?>>
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_4" id="toto_racik_4" value="<?= number_format($hargamr4); ?>" readonly onchange="t_jual_manual_racik_4()">
                            </td>
                          </tr>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="portlet box green" id="racik5">
                    <div class="portlet-title">
                      <div class="caption">
                        <span class="title-white"><b>RACIKAN KE - 5</b></span>
                      </div>
                    </div>
                    <div class="portlet-body form">
                      <div class="form-body">
                        <table class="table" border="0" width="100%">
                          <tr bgcolor="#c7f2ff">
                            <td width="10%" class="control-labelh rightJustified">JENIS</td>
                            <td width="20%" colspan="2">
                              <select id="jenis_5" name="jenis_5" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                  $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup ='JENISRACIK'")->result();
                                  foreach ($data as $row) {
                                    if($racik5) {
                                      if($row->apocode == $racik5->jenisracik) {
                                        $jenisr5 = "selected";
                                      } else {
                                        $jenisr5 = "";
                                      }
                                    } else {
                                      $jenisr5 = "";
                                    }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $jenisr5; ?>><?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td width="15%" class="control-labelh rightJustified">NAMA RACIKAN</td>
                            <td width="20%">
                              <?php 
                                if($racik5) {
                                  $namar5 = $racik5->namaracikan;
                                } else {
                                  $namar5 = "";
                                }
                              ?>
                              <input type="text" class="form-control " name="namaracik_5" id="namaracik_5" value="<?= $namar5; ?>" Placeholder="Nama" <?= $edited2; ?>>
                            </td>
                            <td> &nbsp; </td>
                            <td width="15%" class="control-labelh rightJustified">CARA PAKAI</td>
                            <td>
                              <select name="carapakai_5" id="carapakai_5" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <option value="DIMINUM" <?php if($racik5) { if($racik5->carapakai == "DIMINUM") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIMINUM </option>
                                <option value="DIOLES" <?php if($racik5) { if($racik5->carapakai == "DIOLES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DIOLES </option>
                                <option value="DITETES" <?php if($racik5) { if($racik5->carapakai == "DITETES") { echo "selected"; } else { echo ""; } } else { echo ""; } ?>> DITETES </option>
                              </select>
                            </td>
                          </tr>
                          <tr bgcolor="#c7f2ff">
                            <td class="control-labelh rightJustified">JUMLAH</td>
                            <td width="8%">
                              <?php 
                                if($racik5) {
                                  if($racik5->jumlahracik) {
                                    $jumr5 = $racik5->jumlahracik;
                                  } else {
                                    $jumr5 = "";
                                  }
                                } else {
                                  $jumr5 = "";
                                }
                              ?>
                              <input type="number" class="form-control " name="jumracik_5" id="jumracik_5" value="<?= $jumr5; ?>" <?= $edited2; ?>>
                            </td>
                            <td width="12%">
                              <select name="stajum_5" id="stajum_5" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
                                foreach ($data as $row) {
                                  if($racik5) {
                                    if($row->apocode == $racik5->kemasanracik) {
                                      $satr5 = "selected";
                                    } else {
                                      $satr5 = "";
                                    }
                                  } else {
                                    $satr5 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $satr5; ?>>
                                    <?= $row->aponame; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                            <td>
                              <select name="atpakai_5" id="atpakai_5" class="form-control select2_all" style="width: 100%;" data-placeholder="Pilih..." <?= $edited; ?>>
                                <option value="">Pilih...</option>
                                <?php
                                $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                foreach ($data as $row) {
                                  if($racik5) {
                                    if($row->apocode == $racik5->aturanpakai) {
                                      $atpr5 = "selected";
                                    } else {
                                      $atpr5 = "";
                                    }
                                  } else {
                                    $atpr5 = "";
                                  }
                                ?>
                                  <option value="<?= $row->apocode; ?>" <?= $atpr5; ?>>
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
                        <table id="datatble_racikan5" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
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
                            <?php
                              if($racik5) :
                                $nor5 = 1;
                                foreach($detil_r5x as $r5) :
                            ?>
                              <tr id="racik5_no<?= $nor5; ?>">
                                <td>
                                  <?php if($noedit == "") : ?>
                                    <button type='button' id='btnhbi5<?= $nor5; ?>' onclick='hapusBarisIni_racik5(<?= $nor5; ?>)' class='btn green' <?php if($detil_r5 < 5) { echo "disabled"; } else { echo ""; } ?>><i class='fa fa-trash-o'></i></button>
                                  <?php else : ?>
                                    <button type='button' id='btnhbi5<?= $nor5; ?>' onclick='hapusBarisIni_racik5(<?= $nor5; ?>)' class='btn green' disabled><i class='fa fa-trash-o'></i></button>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <select name="koderacik_5[]" id="koderacik_5<?= $nor5; ?>" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_5(this.value, <?= $nor5; ?>)" style="width: 100%;" <?= $edited; ?>>
                                    <?php $barangr5 = $this->db->get_where("tbl_barang", ["kodebarang" => $r5->kodebarang])->row(); ?>
                                    <option value="<?= $barangr5->kodebarang; ?>"><?= $barangr5->kodebarang; ?></option>
                                  </select>
                                  <input name="nama_racik_5[]" id="nama_racik_5<?= $nor5; ?>" type="hidden" class="form-control" value="<?= $barangr5->namabarang; ?>">
                                </td>
                                <td>
                                  <input name="satracik_5[]" id="satracik_5<?= $nor5; ?>" type="text" class="form-control" readonly value="<?= $r5->satuan; ?>">
                                </td>
                                <td>
                                  <input name="qty_jualracik_5[]" id="qty_jualracik_5<?= $nor5; ?>" onchange="totalline_racik_5(<?= $nor5; ?>); total_racik_5(); cekqty_racik_5(<?= $nor5; ?>); cekstok_racik_5(<?= $nor5; ?>)" value="<?= number_format($r5->qty); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_5[]" id="qty_racik_racik_5<?= $nor5; ?>" onchange="totalline_racik_5(<?= $nor5; ?>); total_racik_5(); cekqty_racik_5(<?= $nor5; ?>)" value="<?= number_format($r5->qtyr); ?>" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_5[]" onchange="totalline_racik_5(<?= $nor5; ?>);" value="<?= number_format($r5->price); ?>" id="hargajualracik_5<?= $nor5; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_5[]" onchange="totalline_racik_5(<?= $nor5; ?>);" value="<?= number_format($r5->totalrp); ?>" id="total_hrg_racik_5<?= $nor5; ?>" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_5[]' id='exp_racik_5<?= $nor5; ?>' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($r5->exp_date)); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php
                                  $nor5++;
                                endforeach;
                              else :
                            ?>
                              <tr id="racik5_no1">
                                <td>
                                  <button type='button' id='btnhbi51' onclick="hapusBarisIni_racik5(1)" disabled class='btn green'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name="koderacik_5[]" id="koderacik_51" class="select2_el_farmasi_baranggud form-control" onchange="showbarangname_racik_5(this.value, 1)" style="width: 100%;" <?= $edited; ?>></select>
                                  <input name="nama_racik_5[]" id="nama_racik_51" type="hidden" class="form-control">
                                </td>
                                <td>
                                  <input name="satracik_5[]" id="satracik_51" type="text" class="form-control" readonly>
                                </td>
                                <td>
                                  <input name="qty_jualracik_5[]" id="qty_jualracik_51" onchange="totalline_racik_5(1); total_racik_5(); cekqty_racik_5(1); cekstok_racik_5(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="qty_racik_racik_5[]" id="qty_racik_racik_51" onchange="totalline_racik_5(1); total_racik_5(); cekqty_racik_5(1)" value="1" type="text" class="form-control rightJustified" <?= $edited2; ?>>
                                </td>
                                <td>
                                  <input name="hargajualracik_5[]" onchange="totalline_racik_5(1);" value="0" id="hargajualracik_51" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name="total_hrg_racik_5[]" onchange="totalline_racik_5(1);" value="0" id="total_hrg_racik_51" type="text" class="form-control rightJustified" readonly>
                                </td>
                                <td>
                                  <input name='exp_racik_5[]' id='exp_racik_51' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" <?= $edited2; ?>>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                        <table class="table" border="0" width="100%">
                          <tr class="wells">
                            <td colspan="2">
                              <input type="hidden" name='jml_racikan_5' id='jml_racikan_5'>
                              <button type="button" onclick="tambah_racikan_5()" <?= $edited; ?> class="btn green"><i class="fa fa-plus"></i></button>
                            </td>
                            <td class="control-labelh leftJustified">TOTAL</td>
                            <td width="6%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racikan_5" id="toto_racikan_5" value=0 readonly>
                            </td>
                          </tr>
                          <tr>
                            <td width="30%" rowspan="6" class="control-labelh leftJustified">Resep Manual Dari Dokter
                              <?php 
                                if($racik5) {
                                  $rdr5 = $racik5->resep_manual;
                                } else {
                                  $rdr5 = "";
                                }
                              ?>
                              <textarea type="text" class="form-control " name="resman_racik_5" id="resman_racik_5" value="<?= $rdr5; ?>" <?= $edited2; ?>><?= $rdr5; ?></textarea>
                            </td>
                            <td rowspan="6" width="30%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">DISKON</td>
                            <?php 
                              if($racik5) {
                                $diskonr5 = $racik5->diskon;
                                $diskonrpr5 = $racik5->diskonrp;
                              } else {
                                $diskonr5 = 0;
                                $diskonrpr5 = 0;
                              }
                            ?>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disknom_racik_5" id="disknom_racik_5" value="<?= number_format($diskonr5); ?>" onchange="total_racik_5()" <?= $edited2; ?>>
                            </td>
                            <td class="control-labelh leftJustified"><b>%</b></td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="disk_racik_5" id="disk_racik_5" value="<?= number_format($diskonrpr5); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">
                              <label for="ppn">PPN</label>
                            </td>
                            <?php 
                              if($racik5) {
                                $ppnrpr5 = $racik5->ppnrp;
                              } else {
                                $ppnrpr5 = 0;
                              }
                              if($ppnrpr5 > 0) {
                                $cekppnr5 = "checked";
                              } else {
                                $cekppnr5 = "";
                              }
                            ?>
                            <td>
                              <input class='form-control' type="checkbox" name="cek_ppn_racik_5" id="cek_ppn_racik_5" onchange="cek_ppn5()" <?= $cekppnr5; ?> disabled>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="ppn_racik_5" id="ppn_racik_5" value="<?= number_format($ppnrpr5); ?>" readonly>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">ONGKOS RACIK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <?php 
                                if($racik5) {
                                  $ongkirr5 = $racik5->ongkosracik;
                                } else {
                                  $ongkirr5 = 0;
                                }
                              ?>
                              <input type="text" class="form-control rightJustified" name="ongra_racik_5" id="ongra_racik_5" value="<?= number_format($ongkirr5); ?>" onchange="total_racik_5()" <?= $edited2; ?>>
                            </td>
                          </tr>
                          <tr>
                            <td class="control-labelh leftJustified">TOTAL+PPN</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                              <input type="text" class="form-control rightJustified" name="totp_racik_5" id="totp_racik_5" value=0 readonly>
                              <input type="hidden" id="ppn_pajak_racik_5" name="ppn_pajak_racik_5" value="<?= $ppn->prosentase; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td width="10%" class="control-labelh leftJustified">TOTAL JUAL PASIEN</td>
                            <?php 
                              if($racik5) {
                                $hargamr5 = $racik5->harga_manual;
                              } else {
                                $hargamr5 = 0;
                              }
                              if($hargamr5 > 0) {
                                $cekhmr5 = "checked";
                              } else {
                                $cekhmr5 = "";
                              }
                            ?>
                            <td width="6%">
                              <input type="checkbox" class="form-control" name="t_manual_racik_5" id="t_manual_racik_5" onclick="cekmanual_racik_5()" <?= $cekhmr5; ?> <?= $edited; ?>>
                            </td>
                            <td width="2%">
                              &nbsp;
                            </td>
                            <td width="15%">
                              <input type="text" class="form-control rightJustified" name="toto_racik_5" id="toto_racik_5" value="<?= number_format($hargamr5); ?>" readonly onchange="t_jual_manual_racik_5()">
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
                      <?php if($noedit == "") : ?>
                        <button id="btnsimpan_racik1" type="button" onclick="save_racik_1()" class="btn blue"><i class="fa fa-save"></i> <b>Posting Racik</b></button>
                      <?php endif; ?>
                      <a href="<?= site_url('penjualan_faktur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
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
  $this->load->view('template/footer_tb');
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
    $("#racik4").hide();
    $("#racik5").hide();
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
    var baseurl = "<?= site_url() ?>";
    var nobukti = $('#noresep').val();
    return baseurl + 'penjualan_faktur/cetak/?nobukti=' + nobukti;
  }

  function ubah_tab(isi) {
    if(isi == 1) {
      $("#racik1").show();
      $("#racik2").hide();
      $("#racik3").hide();
      $("#racik4").hide();
      $("#racik5").hide();
    } else if(isi == 2) {
      $("#racik1").hide();
      $("#racik2").show();
      $("#racik3").hide();
      $("#racik4").hide();
      $("#racik5").hide();
    } else if(isi == 3) {
      $("#racik1").hide();
      $("#racik2").hide();
      $("#racik3").show();
      $("#racik4").hide();
      $("#racik5").hide();
    } else if(isi == 4) {
      $("#racik1").hide();
      $("#racik2").hide();
      $("#racik3").hide();
      $("#racik4").show();
      $("#racik5").hide();
    } else {
      $("#racik1").hide();
      $("#racik2").hide();
      $("#racik3").hide();
      $("#racik4").hide();
      $("#racik5").show();
    }
  }
</script>

<!-- PASIEN BARU -->
<script>
  $('#luppreposition').on('change', function() {
    var prep = this.value;
    $.ajax({
      url: "<?= site_url();?>app/getvaluesetinghms/?kode=" + prep,
      type: "POST",
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
    total();
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

<script>
  $( document ).ready(function() {
    var cekppn2     = $('#ppn2_').val();
    total();
    if("<?= $detil_r5; ?>" > 0) {
      total_racik_5();
    } else if("<?= $detil_r4; ?>" > 0) {
      total_racik_4();
    } else if("<?= $detil_r3; ?>" > 0) {
      total_racik_3();
    } else if("<?= $detil_r2; ?>" > 0) {
      total_racik_2();
    } else if("<?= $detil_r1; ?>" > 0) {
      total_racik_1();
    } else {}
    var totalsemuax = $("#_vtotal").text();
    var totalsemua = Number(parseInt(totalsemuax.replaceAll(",","")));
    if(totalsemua > 0) {
      $("#btnsimpan").attr("disabled", false);
    } else {
      $("#btnsimpan").attr("disabled", true);
    }
  });

  var rowCount;
  var rowCount2;
  var rowCount3;
  var rowCount4;
  var rowCount5;
  var arr = [1];
  if("<?= $jumdata; ?>" > 0){
    var idrow = "<?= $jumdata; ?>" + 1;
  } else {
    var idrow = 2;
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
      <td><button id='btnhbi` + idrow + `' type='button' onclick='hapusBarisIni(` + idrow + `)' class='btn red'><i class='fa fa-trash-o'></i> </button></td>
      <td>
        <select name="kode[]" id="kode`+idrow+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek(this.value, `+idrow+`)" style="width: 100%;"></select>
        <input name="nama[]" id="nama`+idrow+`" type="hidden" class="form-control">
      </td>
      <td><input name="qty[]" onchange="totalline(`+idrow+`); total(); ceksaldoakhir(`+idrow+`)" value="1" id="qty`+idrow+`" type="text" class="form-control rightJustified"></td>
      <td><input name="sat[]" id="sat`+idrow+`" type="text" class="form-control"></td>
      <td><input name="harga[]" onchange="totalline(`+idrow+`);" value="0" id="harga`+idrow+`" type="text" class="form-control rightJustified" readonly></td>
      <td><input type="checkbox" name="ppn[]" id="ppn`+idrow+`" class="form-control" onchange="totalline(`+idrow+`);total()" disabled></td>
      <td><input name="disc[]" onchange="cekdisc(`+idrow+`);totalline(`+idrow+`)" value="0" id="disc`+idrow+`" type="text" class="form-control rightJustified "></td>
      <td><input name="disc2[]" value="0" id="disc2`+idrow+`" type="text" onchange="total();myFunction(`+idrow+`);totalline(`+idrow+`)" class="form-control rightJustified "></td>
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
        <input name="expire[]" id="expire`+idrow+`" type="date" style="width:90%;" class="form-control" min="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrow++;
    $('.select2_all').select2();
  }

  function hapusBarisIni(param) {
    // var table = document.getElementById('datatable');
    // var rowCount = table.rows.length;
    // jumr = 0;
    // for (var i = 1; i < (rowCount - 1); i++) {
    //   jumr += i;
    // }
    // if(jumr < 2){
    //   swal({
    //     title: "BARANG",
    //     html: "Harus Tersisa min 1",
    //     type: "info",
    //     confirmButtonText: "OK"
    //   });
    //   $("#btnhbi"+param).attr("disabled", true);
    // } else {
      $("#resep_tr" + param).remove();
      total();
    // }
  }

  function showbarangcek(str, id) {
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (str == kode) {
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
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
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
      url: "<?= site_url(); ?>penjualan_faktur/cekharga/?kode=" + str,
      type: "POST",
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

  function cekdisc(id) {
      var qtyx    = $('#qty' + id).val();
      var qty     = Number(parseInt(qtyx.replaceAll(',', '')));
      var hargax  = $('#harga' + id).val();
      var harga   = Number(parseInt(hargax.replaceAll(',', '')));
      var discx   = $('#disc' + id).val();
      if (discx >= 100) {
            swal({
                title: "DISKON %",
                html: "<p>MAKSIMAL 100%</p>",
                type: "error",
                confirmButtonText: "OK"
            }).then((value) => {
                $('#disc' + id).val(0);
                var discrp = 0;
                $('#disc2' + id).val(discrp);
                totalx = (qty * harga) - discrp;
                $('#jumlah' + id).val(separateComma(totalx));
                total();
            });
      } else {
            var disc       = discx;
            discrp         = qty * harga * disc / 100;
            $('#disc2' + id).val(separateComma(discrp));
            var discrpx    = $('#disc2' + id).val();
            var discrpxx   = Number(parseInt(discrpx.replaceAll(',', '')));
            totalx         = (qty * harga) - discrpxx;
            $('#jumlah' + id).val(separateComma(totalx));
            total();
      }
  }

  function cekdiscrp(id) {
      $('#disc' + id).val('0');
      var qtyx        = $('#qty' + id).val();
      var qty         = Number(parseInt(qtyx.replaceAll(',', '')));
      var hargax      = $('#harga' + id).val();
      var harga       = Number(parseInt(hargax.replaceAll(',', '')));
      var discrpx     = $('#disc2' + id).val();
      var discrpxx    = Number(parseInt(discrpx.replaceAll(',', '')));
      $('#disc2' + id).val(separateComma(discrpxx));
      totalx = (qty * harga) - discrpxx;
      $('#jumlah' + id).val(separateComma(totalx));
      total();
  }

  function totalline(id) {
    var hargax    = $("#harga"+id).val();
    var harga     = Number(parseInt(hargax.replaceAll(',','')));
    var qtyx      = $("#qty"+id).val();
    var qty       = Number(parseInt(qtyx.replaceAll(',','')));
    var discx     = $("#disc"+id).val();
    var disc      = Number(parseInt(discx.replaceAll(',','')));
    var jumlah    = harga * qty;
    var diskon    = (disc / 100) * jumlah;
    if(diskon > 0) {
      $("#disc2"+id).val(separateComma(diskon));
      var tot   = jumlah - diskon;
    } else {
      var disc2x    = $("#disc2"+id).val();
      var disc2     = Number(parseInt(disc2x.replaceAll(',','')));
      var tot       = jumlah - disc2;
    }
    var kode    = $("#kode"+id).val();
    cekhargajual(kode, harga, id);
    if($("ppn"+id).checked == true) {
      var tot = tot * "<?= $ppn->prosentase / 100; ?>";
    }
    $("#jumlah"+id).val(separateComma(tot));
    total();
  }

  function total() {
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
    var ppn           = dpp * cekppn2;
    if(document.getElementById("t_manual_racik_1").checked == true) {
      var toto1x = $("#toto_racik_1").val();
      var toto1 = Number(toto1x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_1").checked == false) {
      $("#toto_racik_1").val(0);
      var toto1x = $("#totp_racik_1").val();
      var toto1 = Number(toto1x.replaceAll(',',''));
    }
    if(document.getElementById("t_manual_racik_2").checked == true) {
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_2").checked == false) {
      $("#toto_racik_2").val(0);
      var toto2x = $("#totp_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
    }
    if(document.getElementById("t_manual_racik_3").checked == true) {
      var toto3x = $("#toto_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_3").checked == false) {
      $("#toto_racik_3").val(0);
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
    }
    if(document.getElementById("t_manual_racik_4").checked == true) {
      var toto4x = $("#toto_racik_4").val();
      var toto4 = Number(toto4x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_4").checked == false) {
      $("#toto_racik_4").val(0);
      var toto4x = $("#totp_racik_4").val();
      var toto4 = Number(toto4x.replaceAll(',',''));
    }
    if(document.getElementById("t_manual_racik_5").checked == true) {
      var toto5x = $("#toto_racik_5").val();
      var toto5 = Number(toto5x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_5").checked == false) {
      $("#toto_racik_5").val(0);
      var toto5x = $("#totp_racik_5").val();
      var toto5 = Number(toto5x.replaceAll(',',''));
    }
    
    document.getElementById("_vsubtotal").innerHTML = separateComma(subtotal.toFixed(0));
    document.getElementById("_vdiskon").innerHTML = separateComma(diskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(ppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma((total+toto1+toto2+toto3+toto4+toto5).toFixed(0));
    document.getElementById("_vdpp").innerHTML = separateComma(dpp.toFixed(0));
    document.getElementById("_vracik").innerHTML = separateComma((toto1+toto2+toto3+toto4+toto5).toFixed(0));
    if((total+toto1+toto2+toto3+toto4+toto5) > 0) {
      $("#btnsimpan").attr("disabled", false);
    } else {
      $("#btnsimpan").attr("disabled", true);
    }
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
    if("<?= $racikansem; ?>" > 0) {
      bayar();
    } else {
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

  function bayar() {
    var table       = document.getElementById('datatable');
    var rowCount    = table.rows.length;
    var tanggal     = $('[name="tanggal"]').val();
    var gudang      = $('[name="gudang"]').val();
    var pembeli     = $('[name="pembeli"]').val();
    
    // cek jenis pembeli
    if(pembeli == 'atr') {
      $("#pembeli").val('adr').change();
      getdataklinik();
    }
    var namapasien  = $('[name="namapasien"]').val();
    var nama_pas    = $('[name="nama_pas"]').val();
    var dokter      = $('[name="dokter"]').val();
    var nohp        = $('#phone').val();

    var totalxx = Number(parseInt(($('#_vtotal').text()).replaceAll(',','')));
    var total = totalxx;
    

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
        title: "RESEP & RACIKAN TERBENTUK ",
        html: "DENGAN NOMINAL <b>" + separateComma(total) + "</b> <br><br><p> Lanjut Ke Proses Racik...</p>",
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
    var dokter      = $("#dokter").val();
    if(pembeli == 'adr') {
      if(dokter == '' || dokter == null) {
        swal({
          title: "Resep Dari",
          html: "<p>HARUS DI ISI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }
    if (document.getElementById('t_manual_racik_1').checked == true) {
      var h_manual = 1;
      var totalx = $('#toto_racikan_1').val();
    } else {
      var totalx = $('#totp_racik_1').val();
      var h_manual = 0;
    }

    
    for (var i = 1; i < rowCount; i++) {
      var row       = table.rows[i];
      var kode      = row.cells[1].children[0].value;
      var expire    = row.cells[11].children[0].value;
      var atpakai   = row.cells[10].children[0].value;
      // var expire    = $("#expire" + i).val(); 
      if (kode != null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date",
          html: "<p>HARUS DI ISI</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }

      // var aturan_pakai    = $("#aturan_pakai" + i).val(); 
      if (kode == null || kode == "") {} else {
        if((atpakai == '' || atpakai == null)) {
          swal({
            title               : "Aturan Pakai",
            html                : "<p>HARUS DI ISI</p>",
            type                : "error",
            confirmButtonText   : "OK"
          });
          return;
        }
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
          html: "<p>HARUS DI ISI</p>",
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
        url: '<?= site_url('penjualan_faktur/save/2/') ?>' + params,
        data: $('#frmpenjualan').serialize(),
        dataType: "JSON",
        type: 'POST',
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "PENJUALAN RESEP",
              html: "<p> No. Bukti   : <b>" + data.nobukti + "</b> </p>" + "Tanggal :  " + tanggal + "<br><br>Biaya Terbentuk <b>" + separateComma(jumlahtot) + "</b>",
              type: "info",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('penjualan_faktur/edit2/') ?>"+data.nobukti;
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
              location.href = "<?= site_url('penjualan_faktur/edit2/') ?>"+data.nobukti;
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
  if(<?= $detil_r1; ?> > 0) {
    var idrowobat_1 = <?= $detil_r1; ?> + 1;
  } else {
    var idrowobat_1 = 2;
  }
  if(<?= $detil_r2; ?> > 0) {
    var idrowobat_2 = <?= $detil_r2; ?> + 1;
  } else {
    var idrowobat_2 = 2;
  }
  if(<?= $detil_r3; ?> > 0) {
    var idrowobat_3 = <?= $detil_r3; ?> + 1;
  } else {
    var idrowobat_3 = 2;
  }
  if(<?= $detil_r4; ?> > 0) {
    var idrowobat_4 = <?= $detil_r4; ?> + 1;
  } else {
    var idrowobat_4 = 2;
  }
  if(<?= $detil_r5; ?> > 0) {
    var idrowobat_5 = <?= $detil_r5; ?> + 1;
  } else {
    var idrowobat_5 = 2;
  }

  var cekppn  = '<?= $ppn->prosentase / 100; ?>';
  var cekppn2 = '<?= $ppn->prosentase / 100; ?>';
  var cekppn3 = '<?= $ppn->prosentase / 100; ?>';
  var cekppn4 = '<?= $ppn->prosentase / 100; ?>';
  var cekppn5 = '<?= $ppn->prosentase / 100; ?>';

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
        <button type='button' id='btnhbi1`+idrowobat_1+`' onclick="hapusBarisIni_racik1(`+idrowobat_1+`)" class='btn purple'><i class='fa fa-trash-o'></i></button>
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
        <input name='exp_racik_1[]' id='exp_racik_1`+idrowobat_1+`' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_1++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik1(param) {
    var table = document.getElementById('datatble_racikan1');
    var rowCount = table.rows.length;
    jumr1 = 0;
    for (var i = 1; i < (rowCount - 1); i++) {
      jumr1 += i;
    }
    if(jumr1 > 0){
      $("#racik_no" + param).remove();
      total_racik_1();
    } else {
      swal({
        title: "BARANG",
        html: "Harus Tersisa min 1",
        type: "info",
        confirmButtonText: "OK"
      });
      $("#btnhbi1"+param).attr("disabled", true);
    }
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
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
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
    var harga       = $("#hargajualracik_1"+id).val();
    var harga1      = Number(parseInt(harga.replaceAll(",","")));
    var qtyjual     = $("#qty_jualracik_1"+id).val();
    var qtyjual1    = Number(parseInt(qtyjual.replaceAll(",","")));
    jumlah1         = qtyjual1 * harga1;
    $("#total_hrg_racik_1"+id).val(separateComma(jumlah1));
    total_racik_3();
    t_jual_manual_racik_1();
  }

  function t_jual_manual_racik_1() {
    var y = $("#totp_racik_1").val();
    var yy = Number(parseInt(y.replaceAll(',', '')));
    var x = $("#toto_racik_1").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_1').checked == true) {
      if(xx < yy) {
        $("#toto_racik_1").val(separateComma(yy));
        swal({
          title: "TOTAL PENJUALAN PASIEN",
          html: "Tidak bisa lebih kecil dari TOTAL + PPN!",
          type: "error",
          confirmButtonText: "OK"
        });
      } else {
        $("#toto_racik_1").val(separateComma(xx));
      }
    } else {
      $("#toto_racik_1").val(separateComma(xx));
    }
    total_racik_2();
  }

  function cekmanual_racik_1() {
    if (document.getElementById('t_manual_racik_1').checked == true) {
      var hargax = $("#totp_racik_1").val();
      var harga = Number(parseInt(hargax.replaceAll(',','')));
      $("#toto_racik_1").val(separateComma(harga));
      $('#toto_racik_1').attr('readonly', false);
    } else {
      $('#toto_racik_1').attr('readonly', true);
    }
    total_racik_2();
  }

  function total_racik_1() {
    total_racik_2();
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
        <button type='button' id='btnhbi2`+idrowobat_2+`' onclick="hapusBarisIni_racik2(`+idrowobat_2+`)" class='btn red'><i class='fa fa-trash-o'></i></button>
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
        <input name='exp_racik_2[]' id='exp_racik_2`+idrowobat_2+`' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_2++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik2(param) {
    var table = document.getElementById('datatble_racikan2');
    var rowCount = table.rows.length;
    jumr2 = 0;
    for (var i = 1; i < (rowCount - 1); i++) {
      jumr2 += i;
    }
    if(jumr2 > 0){
      $("#racik2_no" + param).remove();
      total_racik_2();
    } else {
      swal({
        title: "BARANG",
        html: "Harus Tersisa min 1",
        type: "info",
        confirmButtonText: "OK"
      });
      $("#btnhbi2"+param).attr("disabled", true);
    }
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
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
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
    var harga       = $("#hargajualracik_2"+id).val();
    var harga2      = Number(parseInt(harga.replaceAll(",","")));
    var qtyjual     = $("#qty_jualracik_2"+id).val();
    var qtyjual2    = Number(parseInt(qtyjual.replaceAll(",","")));
    jumlah2         = qtyjual2 * harga2;
    $("#total_hrg_racik_2"+id).val(separateComma(jumlah2));
    total_racik_3();
    t_jual_manual_racik_2();
  }

  function t_jual_manual_racik_2() {
    var y = $("#totp_racik_2").val();
    var yy = Number(parseInt(y.replaceAll(',', '')));
    var x = $("#toto_racik_2").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_2').checked == true) {
      if(xx < yy) {
        $("#toto_racik_2").val(separateComma(yy));
        swal({
          title: "TOTAL PENJUALAN PASIEN",
          html: "Tidak bisa lebih kecil dari TOTAL + PPN!",
          type: "error",
          confirmButtonText: "OK"
        });
      } else {
        $("#toto_racik_2").val(separateComma(xx));
      }
    } else {
      $("#toto_racik_2").val(separateComma(xx));
    }
    total_racik_2();
  }

  function cekmanual_racik_2() {
    if (document.getElementById('t_manual_racik_2').checked == true) {
      var hargax = $("#totp_racik_2").val();
      var harga = Number(parseInt(hargax.replaceAll(',','')));
      $("#toto_racik_2").val(separateComma(harga));
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
        <button type='button' id='btnhbi3`+idrowobat_3+`' onclick="hapusBarisIni_racik3(`+idrowobat_3+`)" class='btn yellow'><i class='fa fa-trash-o'></i></button>
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
        <input name='exp_racik_3[]' id='exp_racik_3`+idrowobat_3+`' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_3++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik3(param) {
    var table = document.getElementById('datatble_racikan3');
    var rowCount = table.rows.length;
    jumr3 = 0;
    for (var i = 1; i < (rowCount - 1); i++) {
      jumr3 += i;
    }
    if(jumr3 > 0){
      $("#racik3_no" + param).remove();
      total_racik_3();
    } else {
      swal({
        title: "BARANG",
        html: "Harus Tersisa min 1",
        type: "info",
        confirmButtonText: "OK"
      });
      $("#btnhbi3"+param).attr("disabled", true);
    }
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
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
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
    var harga       = $("#hargajualracik_3"+id).val();
    var harga3      = Number(parseInt(harga.replaceAll(",","")));
    var qtyjual     = $("#qty_jualracik_3"+id).val();
    var qtyjual3    = Number(parseInt(qtyjual.replaceAll(",","")));
    jumlah3         = qtyjual3 * harga3;
    $("#total_hrg_racik_3"+id).val(separateComma(jumlah3));
    total_racik_3();
    t_jual_manual_racik_3();
  }

  function t_jual_manual_racik_3() {
    var y = $("#totp_racik_3").val();
    var yy = Number(parseInt(y.replaceAll(',', '')));
    var x = $("#toto_racik_3").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_3').checked == true) {
      if(xx < yy) {
        $("#toto_racik_3").val(separateComma(yy));
        swal({
          title: "TOTAL PENJUALAN PASIEN",
          html: "Tidak bisa lebih kecil dari TOTAL + PPN!",
          type: "error",
          confirmButtonText: "OK"
        });
      } else {
        $("#toto_racik_3").val(separateComma(xx));
      }
    } else {
      $("#toto_racik_3").val(separateComma(xx));
    }
    total_racik_3();
  }

  function cekmanual_racik_3() {
    if (document.getElementById('t_manual_racik_3').checked == true) {
      var hargax = $("#totp_racik_3").val();
      var harga = Number(parseInt(hargax.replaceAll(',','')));
      $("#toto_racik_3").val(separateComma(harga));
      $('#toto_racik_3').attr('readonly', false);
    } else {
      $('#toto_racik_3').attr('readonly', true);
    }
    total_racik_3();
  }

  function total_racik_3() {
    total_racik_4();
  }
  
  // RACIKAN 4

  function tambah_racikan_4() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatble_racikan4");

    table.append(`<tr id="racik4_no`+idrowobat_4+`">
      <td>
        <button type='button' id='btnhbi4`+idrowobat_4+`' onclick="hapusBarisIni_racik4(`+idrowobat_4+`)" class='btn blue'><i class='fa fa-trash-o'></i></button>
      </td>
      <td>
        <select name="koderacik_4[]" id="koderacik_4`+idrowobat_4+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek_racik4(this.value, `+idrowobat_4+`)" style="width: 100%;"></select>
        <input name="nama_racik_4[]" id="nama_racik_4`+idrowobat_4+`" type="hidden" class="form-control">
      </td>
      <td>
        <input name="satracik_4[]" id="satracik_4`+idrowobat_4+`" type="text" class="form-control" readonly>
      </td>
      <td>
        <input name="qty_jualracik_4[]" id="qty_jualracik_4`+idrowobat_4+`" onchange="totalline_racik_4(`+idrowobat_4+`); total_racik_4(); cekqty_racik_4(`+idrowobat_4+`); cekstok_racik_4(`+idrowobat_4+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="qty_racik_racik_4[]" id="qty_racik_racik_4`+idrowobat_4+`" onchange="totalline_racik_4(`+idrowobat_4+`); total_racik_4(); cekqty_racik_4(`+idrowobat_4+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="hargajualracik_4[]" onchange="totalline_racik_4(`+idrowobat_4+`);" value="0" id="hargajualracik_4`+idrowobat_4+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name="total_hrg_racik_4[]" onchange="totalline_racik_4(`+idrowobat_4+`);" value="0" id="total_hrg_racik_4`+idrowobat_4+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name='exp_racik_4[]' id='exp_racik_4`+idrowobat_4+`' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_4++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik4(param) {
    var table = document.getElementById('datatble_racikan4');
    var rowCount = table.rows.length;
    jumr4 = 0;
    for (var i = 1; i < (rowCount - 1); i++) {
      jumr4 += i;
    }
    if(jumr4 > 0){
      $("#racik4_no" + param).remove();
      total_racik_4();
    } else {
      swal({
        title: "BARANG",
        html: "Harus Tersisa min 1",
        type: "info",
        confirmButtonText: "OK"
      });
      $("#btnhbi4"+param).attr("disabled", true);
    }
  }

  function showbarangcek_racik4(str, id) {
    var table = document.getElementById('datatble_racikan4');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#koderacik_4" + id).empty();
        $("#satracik_4" + id).val("");
        $("#qty_jualracik_4" + id).val(1);
        $("#qty_racik_racik_4" + id).val(1);
        $("#hargajualracik_4" + id).val(0);
        $("#_racik_4" + id).val(0);
        $("#total_hrg_racik_4" + id).val(0);
        $("#exp_racik_4" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname_racik_4(str, id);
      }
    }
  }

  function showbarangname_racik_4(str, id) {
    var xhttp;
    var vid = id;
    $('#satracik_4' + vid).val('');
    var qty = $('#qty_jualracik_4' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#hargajualracik_4' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          $("#koderacik_4" + vid).empty();
          $("#nama_racik_4" + vid).val("");
          $("#qty_jualracik_4" + vid).val(1);
          $("#qty_racik_racik_4" + vid).val(1);
          $("#hargajualracik_4" + vid).val(0);
          $("#btnsimpan").attr("disabled", true);
          totalline_racik_4(vid);
          swal('SALDO BARANG', 'Minus ...', '');
        } else {
          $("#nama_racik_4" + vid).val(data.namabarang);
          $('#satracik_4' + vid).val(data.satuan1);
          $('#hargajualracik_4' + vid).val(separateComma(data.hargajual));
          totalline_racik_4(vid);
          if ($("#nama_racik_4" + vid).val() != null || $( "#nama_racik_4" + vid).val() != '' ||$("#satracik_4" + vid).val() != null || $( "#satracik_4" + vid).val() != '') {
            $("#btnsimpan").attr("disabled", false);
          }
        }
      }
    });
  }

  function cekqty_racik_4(id) {
    var qtyrx = $("#qty_racik_racik_4"+id).val();
    var qtyjx = $("#qty_jualracik_4"+id).val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik_racik_4"+id).val(qtyj);
      totalline_racik_4(id);
    }
  }

  function cekstok_racik_4(id) {
    var qtyjx = $("#qty_jualracik_4"+id).val();
    var qty = Number(parseInt(qtyjx.replaceAll(',','')));
    var kode = $("#koderacik_4"+id).val();
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
          $("#qty_jualracik_4"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline_racik_4(id);
      }
    });
  }

  function totalline_racik_4(id) {
    var harga       = $("#hargajualracik_4"+id).val();
    var harga4      = Number(parseInt(harga.replaceAll(",","")));
    var qtyjual     = $("#qty_jualracik_4"+id).val();
    var qtyjual4    = Number(parseInt(qtyjual.replaceAll(",","")));
    jumlah4         = qtyjual4 * harga4;
    $("#total_hrg_racik_4"+id).val(separateComma(jumlah4));
    total_racik_4();
    t_jual_manual_racik_4();
  }

  function t_jual_manual_racik_4() {
    var y = $("#totp_racik_4").val();
    var yy = Number(parseInt(y.replaceAll(',', '')));
    var x = $("#toto_racik_4").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_4').checked == true) {
      if(xx < yy) {
        $("#toto_racik_4").val(separateComma(yy));
        swal({
          title: "TOTAL PENJUALAN PASIEN",
          html: "Tidak bisa lebih kecil dari TOTAL + PPN!",
          type: "error",
          confirmButtonText: "OK"
        });
      } else {
        $("#toto_racik_4").val(separateComma(xx));
      }
    } else {
      $("#toto_racik_4").val(separateComma(xx));
    }
    total_racik_4();
  }

  function cekmanual_racik_4() {
    if (document.getElementById('t_manual_racik_4').checked == true) {
      var hargax = $("#totp_racik_4").val();
      var harga = Number(parseInt(hargax.replaceAll(',','')));
      $("#toto_racik_4").val(separateComma(harga));
      $('#toto_racik_4').attr('readonly', false);
    } else {
      $('#toto_racik_4').attr('readonly', true);
    }
    total_racik_4();
  }

  function total_racik_4() {
    total_racik_5();
  }

  // RACIKAN 5

  function tambah_racikan_5() {
    var gud = $('[name="gudang"]').val();
    if(gud == '' || gud == null) {
      gud = 'APTK';
    } else {
      gud = gud;
    }
    var table = $("#datatble_racikan5");

    table.append(`<tr id="racik5_no`+idrowobat_5+`">
      <td>
        <button type='button' id='btnhbi5`+idrowobat_5+`' onclick="hapusBarisIni_racik5(`+idrowobat_5+`)" class='btn green'><i class='fa fa-trash-o'></i></button>
      </td>
      <td>
        <select name="koderacik_5[]" id="koderacik_5`+idrowobat_5+`" class="select2_el_farmasi_baranggud form-control" onchange="showbarangcek_racik5(this.value, `+idrowobat_5+`)" style="width: 100%;"></select>
        <input name="nama_racik_5[]" id="nama_racik_5`+idrowobat_5+`" type="hidden" class="form-control">
      </td>
      <td>
        <input name="satracik_5[]" id="satracik_5`+idrowobat_5+`" type="text" class="form-control" readonly>
      </td>
      <td>
        <input name="qty_jualracik_5[]" id="qty_jualracik_5`+idrowobat_5+`" onchange="totalline_racik_5(`+idrowobat_5+`); total_racik_5(); cekqty_racik_5(`+idrowobat_5+`); cekstok_racik_5(`+idrowobat_5+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="qty_racik_racik_5[]" id="qty_racik_racik_5`+idrowobat_5+`" onchange="totalline_racik_5(`+idrowobat_5+`); total_racik_5(); cekqty_racik_5(`+idrowobat_5+`)" value="1" type="text" class="form-control rightJustified">
      </td>
      <td>
        <input name="hargajualracik_5[]" onchange="totalline_racik_5(`+idrowobat_5+`);" value="0" id="hargajualracik_5`+idrowobat_5+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name="total_hrg_racik_5[]" onchange="totalline_racik_5(`+idrowobat_5+`);" value="0" id="total_hrg_racik_5`+idrowobat_5+`" type="text" class="form-control rightJustified" readonly>
      </td>
      <td>
        <input name='exp_racik_5[]' id='exp_racik_5`+idrowobat_5+`' type='date' class='form-control rightJustified' min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
      </td>
    </tr>`);
    initailizeSelect2_farmasi_baranggud(gud);
    idrowobat_5++;
    $('.select2_all').select2();
  }

  function hapusBarisIni_racik5(param) {
    var table = document.getElementById('datatble_racikan5');
    var rowCount = table.rows.length;
    jumr5 = 0;
    for (var i = 1; i < (rowCount - 1); i++) {
      jumr5 += i;
    }
    if(jumr5 > 0){
      $("#racik5_no" + param).remove();
      total_racik_5();
    } else {
      swal({
        title: "BARANG",
        html: "Harus Tersisa min 1",
        type: "info",
        confirmButtonText: "OK"
      });
      $("#btnhbi5"+param).attr("disabled", true);
    }
  }

  function showbarangcek_racik5(str, id) {
    var table = document.getElementById('datatble_racikan5');
    var rowCount = table.rows.length;
    for (var i = 1; i < (rowCount - 1); i++) {
      var row = table.rows[i];
      kode = row.cells[1].children[0].value;
      if (kode == str) {
        $("#koderacik_5" + id).empty();
        $("#satracik_5" + id).val("");
        $("#qty_jualracik_5" + id).val(1);
        $("#qty_racik_racik_5" + id).val(1);
        $("#hargajualracik_5" + id).val(0);
        $("#_racik_5" + id).val(0);
        $("#total_hrg_racik_5" + id).val(0);
        $("#exp_racik_5" + id).val("");
        swal({
          title: "BARANG",
          html: "Sudah ada, silahkan pilih barang lain",
          type: "warning",
          confirmButtonText: "OK"
        });
      } else {
        showbarangname_racik_5(str, id);
      }
    }
  }

  function showbarangname_racik_5(str, id) {
    var xhttp;
    var vid = id;
    $('#satracik_5' + vid).val('');
    var qty = $('#qty_jualracik_5' + vid).val();
    var gudang = $('#gudang').val();
    if(gudang == null || gudang == '') {
      gudang = 'APTK';
    } else {
      gudang = gudang;
    }
    $('#hargajualracik_5' + vid).val(0);
    var customer  = $('#cust').val();
    $.ajax({
      url: "<?= site_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str + "&gudang=" + gudang,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        var saldo = Number(data.saldoakhir);
        if (saldo < qty) {
          $("#koderacik_5" + vid).empty();
          $("#nama_racik_5" + vid).val("");
          $("#qty_jualracik_5" + vid).val(1);
          $("#qty_racik_racik_5" + vid).val(1);
          $("#hargajualracik_5" + vid).val(0);
          $("#btnsimpan").attr("disabled", true);
          totalline_racik_5(vid);
          swal('SALDO BARANG', 'Minus ...', '');
        } else {
          $("#nama_racik_5" + vid).val(data.namabarang);
          $('#satracik_5' + vid).val(data.satuan1);
          $('#hargajualracik_5' + vid).val(separateComma(data.hargajual));
          totalline_racik_5(vid);
          if ($("#nama_racik_5" + vid).val() != null || $( "#nama_racik_5" + vid).val() != '' ||$("#satracik_5" + vid).val() != null || $( "#satracik_5" + vid).val() != '') {
            $("#btnsimpan").attr("disabled", false);
          }
        }
      }
    });
  }

  function cekqty_racik_5(id) {
    var qtyrx = $("#qty_racik_racik_5"+id).val();
    var qtyjx = $("#qty_jualracik_5"+id).val();
    var qtyj = Number(parseInt(qtyjx.replaceAll(',','')));
    if(qtyrx > qtyj) {
      swal({
        title: "QTY RACIK",
        html: "Tidak boleh lebih besar dari qty jual",
        type: "error",
        confirmButtonText: "OK"
      }); 
      $("#qty_racik_racik_5"+id).val(qtyj);
      totalline_racik_5(id);
    }
  }

  function cekstok_racik_5(id) {
    var qtyjx = $("#qty_jualracik_5"+id).val();
    var qty = Number(parseInt(qtyjx.replaceAll(',','')));
    var kode = $("#koderacik_5"+id).val();
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
          $("#qty_jualracik_5"+id).val(Number(data.saldoakhir));
          swal({
            title : "QTY JUAL",
            html : "Melebihi saldo akhir",
            type : "error",
            confirmButtonText : "OK"
          })
        }
        totalline_racik_5(id);
      }
    });
  }

  function totalline_racik_5(id) {
    var harga       = $("#hargajualracik_5"+id).val();
    var harga5      = Number(parseInt(harga.replaceAll(",","")));
    var qtyjual     = $("#qty_jualracik_5"+id).val();
    var qtyjual5    = Number(parseInt(qtyjual.replaceAll(",","")));
    jumlah5         = qtyjual5 * harga5;
    $("#total_hrg_racik_5"+id).val(separateComma(jumlah5));
    total_racik_5();
    t_jual_manual_racik_5();
  }

  function t_jual_manual_racik_5() {
    var y = $("#totp_racik_5").val();
    var yy = Number(parseInt(y.replaceAll(',', '')));
    var x = $("#toto_racik_5").val();
    var xx = Number(parseInt(x.replaceAll(',', '')));
    if (document.getElementById('t_manual_racik_5').checked == true) {
      if(xx < yy) {
        $("#toto_racik_5").val(separateComma(yy));
        swal({
          title: "TOTAL PENJUALAN PASIEN",
          html: "Tidak bisa lebih kecil dari TOTAL + PPN!",
          type: "error",
          confirmButtonText: "OK"
        });
      } else {
        $("#toto_racik_5").val(separateComma(xx));
      }
    } else {
      $("#toto_racik_5").val(separateComma(xx));
    }
    total_racik_5();
  }

  function cekmanual_racik_5() {
    if (document.getElementById('t_manual_racik_5').checked == true) {
      var hargax = $("#totp_racik_5").val();
      var harga = Number(parseInt(hargax.replaceAll(',','')));
      $("#toto_racik_5").val(separateComma(harga));
      $('#toto_racik_5').attr('readonly', false);
    } else {
      $('#toto_racik_5').attr('readonly', true);
    }
    total_racik_5();
  }

  function total_racik_5() {
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

    // RACIKAN 4

    var table4 = document.getElementById('datatble_racikan4');
    var rowCount4 = table4.rows.length;
    var diskonper4 = $('#disknom_racik_4').val();
    var ongkosracik4x = $('#ongra_racik_4').val();
    var ongkosracik4 = Number(parseInt(ongkosracik4x.replaceAll(',', '')));
    $('#ongra_racik_4').val(separateComma(ongkosracik4));
    var cek_ppnn4 = $('#cek_ppn_racik_4').is(':checked');
    var ppn_pajak4 = Number($("#ppn_pajak_racik_4").val()) / 100;
    tjumlah4 = 0;
    tdiskon4 = 0;
    for (var i = 1; i < rowCount4; i++) {
      var row4 = table4.rows[i];
      var qtyjual4 = Number(row4.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga4 = Number(row4.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah4 = tjumlah4 + eval(qtyjual4 * harga4);
    }
    total_done4 = tjumlah4;
    if (diskonper4 == 0) {
      tdiskon4 = 0;
    } else {
      tdiskon4 = total_done4 * (diskonper4 / 100);
    }
    if (cek_ppnn4 == false) {
      tppno4 = 0;
    } else {
      tppno4 = (tjumlah4 - tdiskon4) * cekppn4;
    }
    diskon_done4 = tdiskon4;
    total_sppn4 = tjumlah4 - diskon_done4;
    dpp4x = total_sppn4 / (111 / 100);
    ppn_done4 = dpp4x * ppn_pajak4;
    total_ppn4 = total_sppn4;
    ppnc4 = ((tjumlah4 - tdiskon4) / 111 / 100) * ppn_pajak4;
    totpracik4 = total_ppn4 + ongkosracik4;
    
    document.getElementById("toto_racikan_4").value = separateComma(total_done4);
    document.getElementById("disk_racik_4").value = separateComma(diskon_done4.toFixed(0));
    document.getElementById("ppn_racik_4").value = separateComma(ppn_done4.toFixed(0));
    document.getElementById("totp_racik_4").value = separateComma((totpracik4).toFixed(0));

    // RACIKAN 5

    var table5 = document.getElementById('datatble_racikan5');
    var rowCount5 = table5.rows.length;
    var diskonper5 = $('#disknom_racik_5').val();
    var ongkosracik5x = $('#ongra_racik_5').val();
    var ongkosracik5 = Number(parseInt(ongkosracik5x.replaceAll(',', '')));
    $('#ongra_racik_5').val(separateComma(ongkosracik5));
    var cek_ppnn5 = $('#cek_ppn_racik_5').is(':checked');
    var ppn_pajak5 = Number($("#ppn_pajak_racik_5").val()) / 100;
    tjumlah5 = 0;
    tdiskon5 = 0;
    for (var i = 1; i < rowCount5; i++) {
      var row5 = table5.rows[i];
      var qtyjual5 = Number(row5.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
      var harga5 = Number(row5.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
      tjumlah5 = tjumlah5 + eval(qtyjual5 * harga5);
    }
    total_done5 = tjumlah5;
    if (diskonper5 == 0) {
      tdiskon5 = 0;
    } else {
      tdiskon5 = total_done5 * (diskonper5 / 100);
    }
    if (cek_ppnn5 == false) {
      tppno5 = 0;
    } else {
      tppno5 = (tjumlah5 - tdiskon5) * cekppn5;
    }
    diskon_done5 = tdiskon5;
    total_sppn5 = tjumlah5 - diskon_done5;
    dpp5x = total_sppn5 / (111 / 100);
    ppn_done5 = dpp5x * ppn_pajak5;
    total_ppn5 = total_sppn5;
    ppnc5 = ((tjumlah5 - tdiskon5) / 111 / 100) * ppn_pajak5;
    totpracik5 = total_ppn5 + ongkosracik5;
    
    document.getElementById("toto_racikan_5").value = separateComma(total_done5);
    document.getElementById("disk_racik_5").value = separateComma(diskon_done5.toFixed(0));
    document.getElementById("ppn_racik_5").value = separateComma(ppn_done5.toFixed(0));
    document.getElementById("totp_racik_5").value = separateComma((totpracik5).toFixed(0));
    
    // GABUNGAN RACIK 1, 2, 3, 4, 5
    var stx = $("#_vsubtotal").text();
    var stz = Number(parseInt(stx.replaceAll(',','')));
    if(stx == null || stx == '' || stx == 'null') {
      var st = 0;
    } else {
      var st = stz;
    }

    // RACIKAN 1
    if(document.getElementById("t_manual_racik_1").checked == true) {
      var totox = $("#toto_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
    } else if(document.getElementById("t_manual_racik_1").checked == false) {
      $("#toto_racik_1").val(0);
      var totox = $("#totp_racik_1").val();
      var toto = Number(totox.replaceAll(',',''));
    }

    // RACIKAN 2
    if(document.getElementById("t_manual_racik_2").checked == true) {
      var toto2x = $("#toto_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_2").checked == false) {
      $("#toto_racik_2").val(0);
      var toto2x = $("#totp_racik_2").val();
      var toto2 = Number(toto2x.replaceAll(',',''));
    }
    
    // RACIKAN 3
    if(document.getElementById("t_manual_racik_3").checked == true) {
      var toto3x = $("#toto_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_3").checked == false) {
      $("#toto_racik_3").val(0);
      var toto3x = $("#totp_racik_3").val();
      var toto3 = Number(toto3x.replaceAll(',',''));
    }
    
    // RACIKAN 4
    if(document.getElementById("t_manual_racik_4").checked == true) {
      var toto4x = $("#toto_racik_4").val();
      var toto4 = Number(toto4x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_4").checked == false) {
      $("#toto_racik_4").val(0);
      var toto4x = $("#totp_racik_4").val();
      var toto4 = Number(toto4x.replaceAll(',',''));
    }

    // RACIKAN 5
    if(document.getElementById("t_manual_racik_5").checked == true) {
      var toto5x = $("#toto_racik_5").val();
      var toto5 = Number(toto5x.replaceAll(',',''));     
    } else if(document.getElementById("t_manual_racik_5").checked == false) {
      $("#toto_racik_5").val(0);
      var toto5x = $("#totp_racik_5").val();
      var toto5 = Number(toto5x.replaceAll(',',''));
    }

    var vracik = toto + toto2 + toto3 + toto4 + toto5;
    $("#_vracik").text(separateComma((vracik).toFixed(0)));
    $("#_vtotal").text(separateComma((vracik + st).toFixed(0)));

    if (tjumlah > 0) {
      $("#btnsimpan_racik1").attr("disabled", false);
    } else {
      $("#btnsimpan_racik1").attr("disabled", true);
    }
  }

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

    // RACIKAN 4

    var jenis_4       = $('[name="jenis_4"]').val();
    var resman_4      = $('[name="resman_racik_4"]').val();
    var namaracik_4   = $('[name="namaracik_4"]').val();
    var jumracik_4    = $('[name="jumracik_4"]').val();
    var stajum_4      = $('[name="stajum_4"]').val();
    var atpakai_4     = $('[name="atpakai_4"]').val();
    var carapakai     = $('[name="carapakai_4"]').val();
    var nobukti       = $('#noresep').val();

    var table4        = document.getElementById('datatble_racikan4');
    var rowCount4     = table4.rows.length;
    for (i = 1; i < rowCount4; i++) {
      var expire      = $("#exp_racik_4" + i).val(); 
      if ($("#koderacik_4"+i).val() !=  null && (expire == '' || expire == null)) {
        swal({
          title: "Expired Date Racik",
          html: "<p>HARUS DI isi</p>",
          type: "error",
          confirmButtonText: "OK"
        });
        return;
      }
    }

    // RACIKAN 5

    var jenis_5       = $('[name="jenis_5"]').val();
    var resman_5      = $('[name="resman_racik_5"]').val();
    var namaracik_5   = $('[name="namaracik_5"]').val();
    var jumracik_5    = $('[name="jumracik_5"]').val();
    var stajum_5      = $('[name="stajum_5"]').val();
    var atpakai_5     = $('[name="atpakai_5"]').val();
    var carapakai     = $('[name="carapakai_5"]').val();
    var nobukti       = $('#noresep').val();

    var table5        = document.getElementById('datatble_racikan5');
    var rowCount5     = table5.rows.length;
    for (i = 1; i < rowCount5; i++) {
      var expire      = $("#exp_racik_5" + i).val(); 
      if ($("#koderacik_5"+i).val() !=  null && (expire == '' || expire == null)) {
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

      if($("#koderacik_31").val() != null) {
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

        if($("#koderacik_41").val() != null) {
          if (document.getElementById('t_manual_racik_4').checked == true) {
            var h_manual4    = 1;
            var totalx4      = $('#toto_racikan_4').val();
          } else {
            var totalx4      = $('#_vtotal').val();
            var h_manual4    = 0;
          }
      
          var totalxx4       = Number(parseInt(totalx.replaceAll(',', '')));
          if (document.getElementById('t_manual_racik_4').checked == true) {
            var h_manual4    = 1;
          } else {
            var h_manual4    = 0;
          }
      
          if (jenis_4 == '') {
            swal({
              title: "Jenis Masih Kosong",
              html: "<p>CEK LAGI</p>",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          }
      
          if (namaracik_4 == '') {
            swal({
              title: "Nama Racik Masih Kosong",
              html: "<p>CEK LAGI</p>",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          }
      
          if (jumracik_4 == '') {
            swal({
              title: "Jumlah Masih Kosong",
              html: "<p>CEK LAGI</p>",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          }
      
          if (stajum_4 == '') {
            swal({
              title: "Pilihan Jumlah Masih Kosong",
              html: "<p>CEK LAGI</p>",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          }
      
          if (atpakai_4 == '') {
            swal({
              title: "Aturan Pakai Masih Kosong",
              html: "<p>CEK LAGI</p>",
              type: "error",
              confirmButtonText: "OK"
            });
            return;
          }
      
          var table4 = document.getElementById('datatble_racikan4');
          var rowCount4 = table4.rows.length;
          for (i = 1; i < rowCount4; i++) {
            var kode4       = $('#koderacik_4' + i).val();
            var nama4       = $('#nama_racik_4' + i).val();
            var satuan4     = $('#satracik_4' + i).val();
            var qty_jual4   = $('#qty_jualracik_4' + i).val();
            var qty_racik4  = $('#qty_racik_racik_4' + i).val();
            var harga_jual4 = $('#hargajualracik_4' + i).val();
            var total4      = $('#total_hrg_racik_4' + i).val();
            var jml4        = i;
            $("#jml_racikan_4").val(jml4);
          }
      
          var jmlx4            = $("#jml_racikan_4").val();
          var toto_racikan_4  = Number(parseInt($("#toto_racikan_4").val().replaceAll(',', '')));
          var disk_racik_4    = Number(parseInt($("#disk_racik_4").val().replaceAll(',', '')));
          var ppn_4           = Number(parseInt($("#ppn_racik_4").val().replaceAll(',', '')));
          var totp_racik_4    = Number(parseInt($("#totp_racik_4").val().replaceAll(',', '')));

          if($("#koderacik_51").val() != null) {
            if (document.getElementById('t_manual_racik_5').checked == true) {
              var h_manual5    = 1;
              var totalx5      = $('#toto_racikan_5').val();
            } else {
              var totalx5      = $('#_vtotal').val();
              var h_manual5    = 0;
            }
        
            var totalxx5       = Number(parseInt(totalx.replaceAll(',', '')));
            if (document.getElementById('t_manual_racik_5').checked == true) {
              var h_manual5    = 1;
            } else {
              var h_manual5    = 0;
            }
        
            if (jenis_5 == '') {
              swal({
                title: "Jenis Masih Kosong",
                html: "<p>CEK LAGI</p>",
                type: "error",
                confirmButtonText: "OK"
              });
              return;
            }
        
            if (namaracik_5 == '') {
              swal({
                title: "Nama Racik Masih Kosong",
                html: "<p>CEK LAGI</p>",
                type: "error",
                confirmButtonText: "OK"
              });
              return;
            }
        
            if (jumracik_5 == '') {
              swal({
                title: "Jumlah Masih Kosong",
                html: "<p>CEK LAGI</p>",
                type: "error",
                confirmButtonText: "OK"
              });
              return;
            }
        
            if (stajum_5 == '') {
              swal({
                title: "Pilihan Jumlah Masih Kosong",
                html: "<p>CEK LAGI</p>",
                type: "error",
                confirmButtonText: "OK"
              });
              return;
            }
        
            if (atpakai_5 == '') {
              swal({
                title: "Aturan Pakai Masih Kosong",
                html: "<p>CEK LAGI</p>",
                type: "error",
                confirmButtonText: "OK"
              });
              return;
            }
        
            var table5 = document.getElementById('datatble_racikan5');
            var rowCount5 = table5.rows.length;
            for (i = 1; i < rowCount5; i++) {
              var kode5       = $('#koderacik_5' + i).val();
              var nama5       = $('#nama_racik_5' + i).val();
              var satuan5     = $('#satracik_5' + i).val();
              var qty_jual5   = $('#qty_jualracik_5' + i).val();
              var qty_racik5  = $('#qty_racik_racik_5' + i).val();
              var harga_jual5 = $('#hargajualracik_5' + i).val();
              var total5      = $('#total_hrg_racik_5' + i).val();
              var jml5        = i;
              $("#jml_racikan_5").val(jml5);
            }
        
            var jmlx5            = $("#jml_racikan_5").val();
            var toto_racikan_5  = Number(parseInt($("#toto_racikan_5").val().replaceAll(',', '')));
            var disk_racik_5    = Number(parseInt($("#disk_racik_5").val().replaceAll(',', '')));
            var ppn_5           = Number(parseInt($("#ppn_racik_5").val().replaceAll(',', '')));
            var totp_racik_5    = Number(parseInt($("#totp_racik_5").val().replaceAll(',', '')));
            var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
            "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2 +
            "&toto_racikan_3=" + toto_racikan_3 + "&disk_racik_3=" + disk_racik_3 + "&totp_racik_3=" + totp_racik_3 + "&resman_racik_3=" + resman_3 + "&cek_rm3=" + h_manual3 + "&jml3=" + jmlx3 + "&harga_manual3=" + totalxx3 +
            "&toto_racikan_4=" + toto_racikan_4 + "&disk_racik_4=" + disk_racik_4 + "&totp_racik_4=" + totp_racik_4 + "&resman_racik_4=" + resman_4 + "&cek_rm4=" + h_manual4 + "&jml4=" + jmlx4 + "&harga_manual4=" + totalxx4 +
            "&toto_racikan_5=" + toto_racikan_5 + "&disk_racik_5=" + disk_racik_5 + "&totp_racik_5=" + totp_racik_5 + "&resman_racik_5=" + resman_5 + "&cek_rm5=" + h_manual5 + "&jml5=" + jmlx5 + "&harga_manual5=" + totalxx5;
          } else {
            var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
            "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2 +
            "&toto_racikan_3=" + toto_racikan_3 + "&disk_racik_3=" + disk_racik_3 + "&totp_racik_3=" + totp_racik_3 + "&resman_racik_3=" + resman_3 + "&cek_rm3=" + h_manual3 + "&jml3=" + jmlx3 + "&harga_manual3=" + totalxx3 +
            "&toto_racikan_4=" + toto_racikan_4 + "&disk_racik_4=" + disk_racik_4 + "&totp_racik_4=" + totp_racik_4 + "&resman_racik_4=" + resman_4 + "&cek_rm4=" + h_manual4 + "&jml4=" + jmlx4 + "&harga_manual4=" + totalxx4;
          }
        } else {
          var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
          "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2 +
          "&toto_racikan_3=" + toto_racikan_3 + "&disk_racik_3=" + disk_racik_3 + "&totp_racik_3=" + totp_racik_3 + "&resman_racik_3=" + resman_3 + "&cek_rm3=" + h_manual3 + "&jml3=" + jmlx3 + "&harga_manual3=" + totalxx3;
        }
      } else {
        var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx + 
        "&toto_racikan_2=" + toto_racikan_2 + "&disk_racik_2=" + disk_racik_2 + "&totp_racik_2=" + totp_racik_2 + "&resman_racik_2=" + resman_2 + "&cek_rm2=" + h_manual2 + "&jml2=" + jmlx2 + "&harga_manual2=" + totalxx2;
      }
    } else {
      var param           = "?toto_racikan_1=" + toto_racikan_1 + "&disk_racik_1=" + disk_racik_1 + "&totp_racik_1=" + totp_racik_1 + "&resman_racik_1=" + resman_1 + "&cek_rm1=" + h_manual + "&jml1=" + jmlx1 + "&harga_manual1=" + totalxx;
    }
    $.ajax({
      url: '<?= site_url() ?>penjualan_faktur/saveracik/2/' + param,
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

<div class="modal fade" id="modal-detail"  tabindex="-1"role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <h5><b>Daftar Resep</b></h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
          <thead>
            <tr class="page-breadcrumb breadcrumb">
              <th class="text-center title-white">Nama</th>
              <th class="text-center title-white">Aturan Pakai</th>
              <th class="text-center title-white">Check</th>
            </tr>
          </thead>
          <tbody id="daftar_resep"> </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" id="cetak_etiket" onclick="urlcetak_etiket()" class="btn btn-success"><b>
          <i class="fa fa-print"></i> CETAK</b>
        </button>
        <button type="button" class="btn red" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</b></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-telaah"  tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <h5><b>Daftar Telaah</b></h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped" id="tbl2" style="margin:auto !important">
          <thead>
            <tr class="page-breadcrumb breadcrumb">
              <th class="text-center title-white">No</th>
              <th class="text-center title-white">Aspek Telaah</th>
              <th class="text-center title-white">Check</th>
            </tr>
          </thead>
          <tbody id="daftar_telaah"> </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" id="cetak_etiket" onclick="urlcetak_telaah()" class="btn btn-success"><b>
          <i class="fa fa-print"></i> CETAK</b>
        </button>
        <button type="button" class="btn red" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</b></button>
      </div>
    </div>
  </div>
</div>

<!-- CETAK -->
<script>
  function etiket() {
    var noresep = $('[name="noresep"]').val();
    $.ajax({
      url        : "<?= site_url('Penjualan_faktur/getctk/?noresep='); ?>" + noresep,
      type       : "POST",
      dataType   : "JSON",
      success: function(data) {
        $('#daftar_resep').empty();
        $.each(data, function(key, value) {    
          if(value.cetak==1){ 
            checked = 'checked';   
          }else{
            checked = ''; 
          }                
          $('#daftar_resep').append("<tr>\
            <td>"+value.namabarang1+"</td>\
            <td class='text-center'>"+value.nm_atpakai+"</td>\
            <td style='text-align: center'><input class='form-control' type='checkbox' id='kd_barang["+value.kodebarang+"]' name='kd_barang["+value.kodebarang+"]' onclick=updt_ctk('"+value.kodebarang+"'); "+checked+"></td>\
          </tr>");
        });
      }
    });
    $('#modal-detail').modal('show');
  }
     
  function updt_ctk(kd) {
    var baseurl   = "<?= site_url(); ?>";
    var noresep   = $('[name="noresep"]').val();
    if (document.getElementById('kd_barang['+kd+']').checked == true) {
      stat = 1;
    }else{
      stat = 0;
    }
    $.ajax({
      url        : "<?= site_url('Penjualan_faktur/updt_ctk/?kd='); ?>" + kd+ "&resep=" + noresep+ "&stat=" + stat,
      type       : "POST",
      dataType   : "JSON",
    });
  }

  function urlcetak_etiket() {
    var baseurl       = "<?= site_url() ?>";
    var noresep       = $('[name="noresep"]').val();
    $('#modal-detail').modal('hide');
    var ctk           = baseurl + 'penjualan_faktur/ctk_etiket/?resep=' + noresep;
    window.open(ctk,'_blank');
  }

  function telaah() {
    var noresep = $('[name="noresep"]').val();
    $.ajax({
      url        : "<?= site_url(); ?>Penjualan_faktur/get_telaah/?noresep=" + noresep,
      type       : "POST",
      dataType   : "JSON",
      success: function(data) {
        $('#daftar_telaah').empty();
        $no = 1;
        $.each(data, function(key, value) {    
          if(value.cek==1){ 
            checked = 'checked';   
          }else{
            checked = ''; 
          }                
          $('#daftar_telaah').append("<tr>\
            <td class='text-center'>"+$no+"</td>\
            <td>"+value.aspek+"</td>\
            <td style='text-align: center'><input class='form-control' type='checkbox' id='kd_barang["+value.kode+"]' name='kd_barang["+value.kode+"]' onclick=updt_telaah('"+value.kode+"'); "+checked+"></td>\
          </tr>");
          $no++;
        });
      }
    });
    $('#modal-telaah').modal('show');
  }

  function updt_telaah(kd) {
    var baseurl   = "<?= site_url() ?>";
    var noresep   = $('[name="noresep"]').val();
    if (document.getElementById('kd_barang['+kd+']').checked == true) {
      stat = 1;
    }else{
      stat = 0;
    }
    $.ajax({
      url        : "<?= site_url(); ?>Penjualan_faktur/updt_telaah/?kd=" + kd+ "&resep=" + noresep+ "&stat=" + stat,
      type       : "POST",
      dataType   : "JSON",
    });
  }

  function urlcetak_telaah() {
    var baseurl       = "<?= site_url() ?>";
    var noresep       = $('[name="noresep"]').val();
    $('#modal-detail').modal('hide');
    var ctk           = baseurl + 'penjualan_faktur/ctk_telaah/?resep=' + noresep;
    window.open(ctk,'_blank');
  }

  function urlcetak_cr() {
    var baseurl       = "<?= site_url() ?>";
    var noresep       = $('[name="noresep"]').val();
    $('#modal-detail').modal('hide');
    var ctk           = baseurl + 'penjualan_faktur/ctk_cr/?resep=' + noresep;
    window.open(ctk,'_blank');
  }
</script>