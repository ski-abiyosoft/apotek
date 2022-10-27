<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
?>

<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span>
      -
      <span class="title-web">MASTER <small><small>Tarif</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?php echo base_url(); ?><?= site_url('Master_tarif'); ?>">Master Tarif</a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Update Data
        </a>
      </li>
    </ul>
  </div>
</div>
<div class="portlet box yellow">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-reorder"></i>*Data Update
    </div>
  </div>
  <div class="portlet-body form">
    <form id="frmtambah" class="form-horizontal" method="post">
      <div class="form-body">
        <div class="tabbable tabbable-custom tabbable-full-width">
          <div class="tab-pane active" style="margin-top: 30px;">
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label class="col-md-6 control-label">Kode Tarif
                    <font color="red">*</font>
                  </label>
                  <div class="col-md-6">
                    <input type="text" readonly id="kode" name="kode" class="form-control" value="<?= $header->kodetarif; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="col-md-6 control-label">Unit
                    <font color="red">*</font>
                  </label>
                  <div class="col-md-6">
                    <select class="form-control select2_el_poli" id="poli" name="poli">
                      <?php if ($header->kodepos) {
                        $namapos = data_master('tbl_namapos', array('kodepos' => $header->kodepos));
                      ?>
                        <option value="<?= $header->kodepos; ?>"><?= $namapos->namapost; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label class="col-md-6 control-label">Tindakan & Layanan
                    <font color="red">*</font>
                  </label>
                  <div class="col-md-6">
                    <input type="text" name="tindakan" id="tindakan" class="form-control" placeholder="Nama Tindakan..." value="<?= $header->tindakan; ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="col-md-6 control-label">Akun Pendapatan
                    <font color="red">*</font>
                  </label>
                  <div class="col-md-6">
                    <select class="form-control select2_el_pos" id="akunpendapatan" name="akunpendapatan">
                      <?php if ($header->kodepos) {
                        $namapos = data_master('tbl_accounting', array('accountno' => $header->accountno));
                      ?>
                        <option value="<?= $header->accountno; ?>"><?= $namapos->acname; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label class="col-md-6 control-label">Tidak dipakai</label>
                  <div class="col-md-6" style="display: flex; gap: 5px; margin-top: 7px;">
                    <?php if($header->tidakaktif == 1) { $cek = 'checked'; } else { $cek = ''; } ?>
                    <input name="tidakaktif" id="tidakaktif" <?= $cek; ?> type="checkbox">
                  </div>
                </div>
              </div>
              <div class="col-md-5"></div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="portlet-body">
                  <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                      <ul class="nav nav-tabs">
                        <li class="active" id="tab_detail_tarif">
                          <a href="#tab1" data-toggle="tab" onclick="tab1_()">
                            Detail Tarif
                          </a>
                        </li>
                        <li class="" id="tab_bhp">
                          <a href="#tab2" data-toggle="tab" onclick="tab2_()">
                            Cost & BHP
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                          <div class="portlet-body">
                            <div class="table-responsive">
                              <table id="detail_tarif" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <!-- <th style="text-align: center;" width="5%">Hapus</th> -->
                                    <th style="text-align: center;" width="25%">Cabang</th>
                                    <th style="text-align: center;" width="20%">Kelompok Tarif</th>
                                    <th style="text-align: center;" width="10%">Jasa RS/Klinik</th>
                                    <th style="text-align: center;" width="10%">Jasa Dokter</th>
                                    <th style="text-align: center;" width="10%">Jasa Perawat</th>
                                    <th style="text-align: center;" width="10%">BHP</th>
                                    <th style="text-align: center;" width="10%">Total</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php $no = 1; foreach($detail as $d) : ?>
                                  <tr id="row_detail_tarif<?= $no; ?>">
                                    <!-- <td> -->
                                        <!-- <button type='button' onclick=hapusBarisIni(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button> -->
                                        <!-- <button type='button' disabled class='btn red'><i class='fa fa-trash-o'></i></button>
                                    </td> -->
                                    <td>
                                      <select class="form-control select2_el_cabang_all" id="cabang<?= $no; ?>" name="cabang[]">
                                        <?php if ($header->kodepos) {
                                          $rs = data_master('tbl_namers', array('koders' => $d->koders));
                                        ?>
                                          <option value="<?= $d->koders; ?>"><?= $rs->namars; ?></option>
                                        <?php } ?>
                                      </select>
                                    </td>
                                    <td>
                                      <select class="form-control select2_el_penjamin" id="keltarif<?= $no; ?>" name="keltarif[]">
                                        <?php if ($header->kodepos) {
                                          $penjamin = data_master('tbl_penjamin', array('cust_id' => $d->cust_id));
                                        ?>
                                          <option value="<?= $d->cust_id; ?>"><?= $penjamin->cust_nama; ?></option>
                                        <?php } ?>
                                      </select>
                                    </td>
                                    <td>
                                      <input name='jasars[]' id='jasars<?= $no; ?>' onchange='totallineTarif(<?= $no; ?>); total_tarif()' value="<?= number_format($d->tarifrspoli) ?>" min="0" type='text' class='form-control rightJustified'>
                                    </td>
                                    <td>
                                      <input name='jasadr[]' id='jasadr<?= $no; ?>' onchange='totallineTarif(<?= $no; ?>); total_tarif()' value="<?= number_format($d->tarifdrpoli) ?>" min="0" type='text' class='form-control rightJustified'>
                                    </td>
                                    <td>
                                      <input name='jasaperawat[]' id='jasaperawat<?= $no; ?>' onchange='totallineTarif(<?= $no; ?>); total_tarif()' value="<?= number_format($d->feemedispoli) ?>" min="0" type='text' class='form-control rightJustified'>
                                    </td>
                                    <td>
                                      <input name='bhp[]' id='bhp<?= $no; ?>' onchange='totallineTarif(<?= $no; ?>); total_tarif()' value="<?= number_format($d->obatpoli) ?>" min="0" type='text' class='form-control rightJustified'>
                                    </td>
                                    <td>
                                      <input name='total[]' id='total<?= $no; ?>' readonly value="<?php $total = $d->tarifrspoli + $d->tarifdrpoli + $d->feemedispoli + $d->obatpoli; echo number_format($total) ?>" min="0" type='text' class='form-control rightJustified'>
                                    </td>
                                  </tr>
                                  <?php $no++; endforeach; ?>
                                </tbody>
                              </table>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="row">
                                  <div class="col-xs-9">
                                    <div class="wells">
                                      <button type="button" onclick="tambah()" class="btn green" style="margin-left:8px"><i class="fa fa-plus"></i></button>
                                      <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                          <div class="table-responsive">
                            <table id="cost_bhp" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <!-- <th style="text-align: center;" width="5%">Hapus</th> -->
                                  <th style="text-align: center;" width="40%">Kode Barang</th>
                                  <th style="text-align: center;" width="10%">Qty</th>
                                  <th style="text-align: center;" width="10%">Satuan</th>
                                  <th style="text-align: center;" width="20%">Harga</th>
                                  <th style="text-align: center;" width="20%">Total</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $nox = 1; foreach($bhp as $b) : ?>
                                <tr id="row_cost_bhp<?= $nox; ?>">
                                  <!-- <td> -->
                                      <!-- <button type='button' onclick=hapusBarisBarang(<?= $nox; ?>) class='btn red'><i class='fa fa-trash-o'></i></button> -->
                                      <!-- <button type='button' disabled class='btn red'><i class='fa fa-trash-o'></i></button>
                                  </td> -->
                                  <td>
                                    <select name="kodebarang[]" id="kodebarang<?php echo $nox; ?>" class="select2_el_log_barangdata form-control input-large" onchange="showbarangname(this.value, <?= $nox; ?>)">
                                      <option value="<?= $b->kodeobat; ?>">[ <?= $b->kodeobat; ?> ] - [ <?= $b->namabarang; ?> ] - [ <?= $b->satuan1; ?> ]</option>
                                    </select>
                                  </td>
                                  <td>
                                    <input name='qty[]' id='qty<?= $nox; ?>' onchange='totalline_barang(<?= $nox; ?>); total_barang()' value="<?= number_format($b->qty); ?>" type='text' class='form-control rightJustified'>
                                  </td>
                                  <td>
                                    <input name='satuan[]' id='satuan<?= $nox; ?>' readonly type='text' class='form-control rightJustified' value="<?= $b->satuan; ?>">
                                  </td>
                                  <td>
                                    <input name='harga[]' id='harga<?= $nox; ?>' onchange='totalline_barang(<?= $nox; ?>); total_barang()' value="<?= number_format($b->harga) ?>" type='text' class='form-control rightJustified'>
                                  </td>
                                  <td>
                                    <input name='jumlah[]' id='jumlah<?= $nox; ?>' readonly value="<?= number_format($b->totalharga) ?>" type='text' class='form-control rightJustified'>
                                  </td>
                                </tr>
                                <?php $nox++; endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="wells">
                                <button type="button" onclick="tambah_barang()" class="btn green" style="margin-left:7px;"><i class="fa fa-plus"></i> </button>
                                <button type="button" onclick="hapusx()" class="btn red"><i class="fa fa-trash-o"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                              <div class="well">
                                <table>
                                  <tr>
                                    <td width="40%"><strong>Total</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59%" align="right"><strong><span style="font-size: 14px;" id="_vtotal"></span></strong></td>
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
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <div class="wells">
              <button type="button" onclick="update()" id="btnupdate" class="btn yellow"><i class="fa fa-refresh"></i>
                <b>Ubah</b>
              </button>
              <div class="btn-group">
                <a class="btn red" href="<?php echo base_url('Master_tarif') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
?>

<script>
  $( document ).ready(function() {
    total_barang();
  });

  function tab1_(){
    $('#tab1').addClass('active');
    $('#tab2').removeClass('active');
  }
  function tab2_(){
    $('#tab1').removeClass('active');
    $('#tab2').addClass('active');
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
</script>

<script>
  var idrow = <?php echo $jumdetail + 1; ?>;
  var idrow2 = <?php echo $jumbhp + 1; ?>;
  var rowCount;
  var arr = [1];

  function tambah() {
    var table = $("#detail_tarif");
    // <td id='kolom" + idrow + "'><button type='button' onclick='hapusBarisIni(" + idrow + ");' id=btnhapus" + idrow +" class='btn red'><i class='fa fa-trash-o'></i></button></td>
    table.append("<tr id='row_detail_tarif"+idrow+"'>" +
    "<td><select id='cabang"+idrow+"' name='cabang[]' class='form-control select2_el_cabang_all' data-placeholder='Pilih...' onkeypress='return tabE(this,event)'></select></td>" +
    "<td><select name='keltarif[]' id='keltarif"+idrow+"' class='select2_el_penjamin form-control input-largex'><option value=''></option></select></td>" +
    "<td><input name='jasars[]' id='jasars"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>" +
    "<td><input name='jasadr[]' id='jasadr"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>" +
    "<td><input name='jasaperawat[]' id='jasaperawat"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>" +
    "<td><input name='bhp[]' id='bhp"+idrow+"' onkeyup='totallineTarif("+idrow+")' value='0' min='0' type='text' class='form-control rightJustified'></td>"+
    "<td><input name='total[]' id='total"+idrow+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>");
    initailizeSelect2_cabang_all();
    initailizeSelect2_penjamin();
    idrow++;
  }

  function hapus() {
    if (idrow > 2) {
      var x = document.getElementById('detail_tarif').deleteRow(idrow - 1);
      idrow--;
      total_tarif();
    }
  }

  function hapusBarisIni(param) {
    $("#row_detail_tarif" + param).remove();
    total_tarif();
  }

  function tambah_barang() {
    var table = $("#cost_bhp");
    // <td id='kolom" + idrow2 + "'><button type='button' onclick='hapusBarisBarang(" + idrow2 + ");' id=btnhapus" + idrow2 +" class='btn red'><i class='fa fa-trash-o'></i></button></td>
    table.append("<tr id='row_cost_bhp"+idrow2+"'>" +
    "<td><select name='kodebarang[]' id='kodebarang"+idrow2+"' class='select2_el_log_barangdata form-control input-largex' onchange='showbarangname(this.value, "+idrow2+")'><option value=''></option></select></td>" +
    "<td><input name='qty[]' id='qty"+idrow2+"' onkeyup='totalline_barang("+idrow2+")' value='1' type='text' class='form-control rightJustified'></td>" +
    "<td><input name='satuan[]' id='satuan"+idrow2+"' readonly type='text' class='form-control rightJustified'></td>" +
    "<td><input name='harga[]' id='harga"+idrow2+"' onkeyup='totalline_barang("+idrow2+")' value='1' type='text' class='form-control rightJustified'></td>" +
    "<td><input name='jumlah[]' id='jumlah"+idrow2+"' value='0' min='0' type='text' class='form-control rightJustified' readonly></td></tr>");
    initailizeSelect2_log_barangdata();
    idrow2++;
  }

  function hapusx() {
    if (idrow2 > 2) {
      var x = document.getElementById('cost_bhp').deleteRow(idrow2 - 1);
      idrow2--;
      total_barang();
    }
  }

  function hapusBarisBarang(param) {
    $("#row_cost_bhp" + param).remove();
    total_barang();
  }

  function showbarangname(str, id) {
    var xhttp;
    $('#satuan' + id).val('');
    $('#harga' + id).val(0);
    $.ajax({
      url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#kode' + id).val(data.kodebarang);
        $('#satuan' + id).val(data.satuan1);
        $('#harga' + id).val(separateComma(data.hargabeli));
        totalline_barang(id);
      }
    });
  }

  function totallineTarif(param){
    var jasarsx = $("#jasars"+param).val();
    jasars = Number(parseInt(jasarsx.replaceAll(',','')));
    var jasadrx = $("#jasadr"+param).val();
    jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
    var jasaperawatx = $("#jasaperawat"+param).val();
    jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
    var bhpx = $("#bhp"+param).val();
    bhp = Number(parseInt(bhpx.replaceAll(',','')));
    var total = jasars + jasadr + jasaperawat + bhp;
    $("#jasars"+param).val(separateComma(jasars));
    $("#jasadr"+param).val(separateComma(jasadr));
    $("#jasaperawat"+param).val(separateComma(jasaperawat));
    $("#bhp"+param).val(separateComma(bhp));
    $("#total"+param).val(separateComma(total));
  }

  function total_tarif(){
    var table = document.getElementById('detail_tarif');
    var rowCount = table.rows.length;
    tjumlah = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      jumlah3 = row.cells[3].children[0].value;
      jumlah4 = row.cells[4].children[0].value;
      jumlah5 = row.cells[5].children[0].value;
      jumlah6 = row.cells[6].children[0].value;
      var jumlah31 = Number(jumlah3.replace(/[^0-9\.]+/g, ""));
      var jumlah41 = Number(jumlah4.replace(/[^0-9\.]+/g, ""));
      var jumlah51 = Number(jumlah5.replace(/[^0-9\.]+/g, ""));
      var jumlah61 = Number(jumlah6.replace(/[^0-9\.]+/g, ""));
      tjumlah = eval(jumlah31 + jumlah41 + jumlah51 + jumlah61);
      row.cells[7].children[0].value = separateComma(tjumlah);
    }
  }

  function totalline_barang(param){
    var qtyx = $("#qty"+param).val();
    var hargax = $("#harga"+param).val();
    var qty = Number(parseInt(qtyx.replaceAll(',','')));
    var harga = Number(parseInt(hargax.replaceAll(',','')));
    var total = qty * harga;
    $("#jumlah"+param).val(separateComma(total));
    total_barang();
  }

  function total_barang(){
    var table = document.getElementById('cost_bhp');
    var rowCount = table.rows.length;
    tjumlah = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      jumlah = row.cells[1].children[0].value;
      harga = row.cells[3].children[0].value;
      var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
      var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
      tjumlah = tjumlah + eval(jumlah1 * harga1);
    }
    document.getElementById("_vtotal").innerHTML = separateComma(tjumlah);
  }
</script>

<script>
  function validation(poli, tindakan, akunpendapatan){
    if(poli == ''){
      swal({
        title: "UNIT",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(tindakan == ''){
      swal({
        title: "TINDAKAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(akunpendapatan == ''){
      swal({
        title: "AKUN PENDAPATAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
  }

  function validation_detail(cabang, keltarif, jasars, jasadr, jasaperawat, bhp, total){
    if(cabang == ''){
      swal({
        title: "CABANG",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(keltarif == ''){
      swal({
        title: "KELOMPOK TARIF",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(jasars == ''){
      swal({
        title: "JASA RS",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(jasadr == ''){
      swal({
        title: "JASA DOKTER",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(jasaperawat == ''){
      swal({
        title: "JASA PERAWAT",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(bhp == ''){
      swal({
        title: "BHP",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(total == ''){
      swal({
        title: "TOTAL",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
  }

  function validation_barang(kodebarang, qty, satuan, harga, jumlah){
    if(kodebarang == ''){
      swal({
        title: "KODE BARANG",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(qty == ''){
      swal({
        title: "JUMLAH",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(satuan == ''){
      swal({
        title: "SATUAN",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(harga == ''){
      swal({
        title: "HARGA",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
    if(jumlah == ''){
      swal({
        title: "TOTAL",
        html: "Tidak boleh kosong !",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#btnupdate").attr("disabled", false);
        $("#btnupdate").text("Ubah");
      });
      return;
    }
  }
</script>

<script>
  function update(){
    $("#btnupdate").attr("disabled", true);
    $("#btnupdate").text("Proses");
    var kodetarif = $("#kode").val();
    var poli = $("#poli").val();
    var tindakan = $("#tindakan").val();
    var akunpendapatan = $("#akunpendapatan").val();
    if(document.getElementById("tidakaktif").checked == true){
      var tidakaktif = 1;
    } else {
      var tidakaktif = 0;
    }
    swal({
      title: 'UBAH DATA',
      html: "Yakin ingin mengubah data ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ubah',
      cancelButtonText: 'Belum'
    }).then(function() {
      if(poli != '' && tindakan != '' && akunpendapatan != ''){
        $.ajax({
          url: "<?= site_url('Master_tarif/ubah_tarif?tidakaktif='); ?>"+tidakaktif,
          data: $('#frmtambah').serialize(),
          type: "POST",
          dataType: "JSON",
          success: function(data){
            if(data.status == 1){
              // data detail
              var cabang = $('[name="cabang"]').val();
              var keltarif = data.kodetarif;
              var jasars = $('[name="jasars"]').val();
              var jasadr = $('[name="jasadr"]').val();
              var jasaperawat = $('[name="jasaperawat"]').val();
              var bhp = $('[name="bhp"]').val();
              var total = $('[name="total"]').val();
              var kodetarif = data.kodetarif;
              if(cabang != '' && keltarif != '' && jasars != '' && jasadr != '' && jasaperawat != '' && bhp != '' && total != ''){
                var table = document.getElementById('detail_tarif');
                var rowCount = table.rows.length;
                for (i = 1; i < rowCount; i++) {
                  var cabang = $("#cabang"+i).val();
                  var keltarif = $("#keltarif"+i).val();
                  var jasarsx = $("#jasars"+i).val();
                  var jasars = Number(parseInt(jasarsx.replaceAll(',','')));
                  var jasadrx = $("#jasadr"+i).val();
                  var jasadr = Number(parseInt(jasadrx.replaceAll(',','')));
                  var jasaperawatx = $("#jasaperawat"+i).val();
                  var jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',','')));
                  var bhpx = $("#bhp"+i).val();
                  var bhp = Number(parseInt(bhpx.replaceAll(',','')));
                  var totalx = $("#total"+i).val();
                  var total = Number(parseInt(totalx.replaceAll(',','')));
                  var param = "?cabang="+cabang+"&keltarif="+keltarif+"&jasars="+jasars+"&jasadr="+jasadr+"&jasaperawat="+jasaperawat+"&bhp="+bhp+"&total="+total+"&kodetarif="+kodetarif;
                  $.ajax({
                    url: "<?= site_url('Master_tarif/simpan_tarif_detail') ?>"+param,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                      // console.log(data)
                    }
                  });
                }
              } else {
                validation_detail(cabang, keltarif, jasars, jasadr, jasaperawat, bhp, total);
              }
              // data barang
              var kodebarang = $('[name="kodebarang"]').val();
              var qty = $('[name="qty"]').val();
              var satuan = $('[name="satuan"]').val();
              var harga = $('[name="harga"]').val();
              var jumlah = $('[name="jumlah"]').val();
              var kodetarif = data.kodetarif;
              if(kodebarang != '' && qty != '' && satuan != '' && harga != '' && jumlah != ''){
                var table = document.getElementById('cost_bhp');
                var rowCount = table.rows.length;
                for (i = 1; i < rowCount; i++) {
                  var kodebarang = $("#kodebarang"+i).val();
                  var qtyx = $("#qty"+i).val();
                  var qty = Number(parseInt(qtyx.replaceAll(',','')));
                  var satuan = $("#satuan"+i).val();
                  var hargax = $("#harga"+i).val();
                  var harga = Number(parseInt(hargax.replaceAll(',','')));
                  var jumlahx = $("#jumlah"+i).val();
                  var jumlah = Number(parseInt(jumlahx.replaceAll(',','')));
                  var param_barang = "?kodebarang="+kodebarang+"&qty="+qty+"&satuan="+satuan+"&harga="+harga+"&jumlah="+jumlah+"&kodetarif="+kodetarif;
                  $.ajax({
                    url: "<?= site_url('Master_tarif/simpan_tarif_barang') ?>"+param_barang,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                      // console.log(data)
                    }
                  });
                }
              } else {
                validation_barang(kodebarang, qty, satuan, harga, jumlah);
              }
              swal({
                title: "UPDATE TARIF",
                html: "Berhasil dilakukan !",
                type: "success",
                confirmButtonText: "OK"
              }).then((value) => {
                $("#btnupdate").attr("disabled", false);
                $("#btnupdate").text("Ubah");
                location.href = "<?php echo base_url() ?>Master_tarif";
              });
            } else {
              swal({
                title: "UPDATE TARIF",
                html: "Gagal dilakukan !",
                type: "error",
                confirmButtonText: "OK"
              }).then((value) => {
                $("#btnupdate").attr("disabled", false);
                $("#btnupdate").text("Ubah");
              });
            }
          }
        })
      } else {
        validation(poli, tindakan, akunpendapatan);
      }
    });
  }
</script>