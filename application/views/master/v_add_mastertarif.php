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
      <span class="title-web"><?= $modul; ?> <small><small><?= $submodul; ?></small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url(); ?>dashboard">
          Awal
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?php echo base_url(); ?><?= $url; ?>">
          Daftar <?= $submodul; ?>
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Entri Data
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="portlet-body form">
      <form id="frmdata" class="form-horizontal" method="post">
        <div class="form-body">
          <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab1" data-toggle="tab">
                  <?= $submodul; ?>
                </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab1">
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">KODE TARIF <font color="red">*</font></label>
                      <div class="col-md-4">
                        <input type="text" name="kodetarif" id="kodetarif" class="form-control" placeholder="Otomatis" readonly>
                      </div>
                      <label class="col-md-3 control-label">TIDAK AKTIF</label>
                      <div class="col-md-2">
                        <input type="checkbox" style="margin-top: -0.5px;" name="tidakaktif" id="tidakaktif" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NAMA TINDAKAN<font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">UNIT <font color="red">*</font></label>
                      <div class="col-md-9">
                        <select name="kodepos" id="kodepos" class="select2_el_poli form-control input-largex"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">AKUN PENDAPATAN <font color="red">*</font></label>
                      <div id="nopo" class="col-md-9">
                        <select name="accountno" id="accountno" class="form-control select2_el_pendapatan"></select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                      <ul class="nav nav-tabs">
                        <li class="active" id="luptab_detail">
                          <a href="#luptab1" data-toggle="tab">Detail Tarif <font color="red">*</font></a>
                        </li>
                        <li class="" id="luptab_costbhp">
                          <a href="#luptab2" data-toggle="tab">Cost & BHP <font color="red">*</font></a>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="luptab1">
                          <input type="hidden" name="jum_detail" id="jum_detail">
                          <table id="detail_tarif" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead class="breadcrumb header-custom">
                              <tr>
                                <th style="text-align: center; color: white;" width="5%">Hapus</th>
                                <th style="text-align: center; color: white;" width="20%">Cabang</th>
                                <th style="text-align: center; color: white;" width="20%">Kelompok Tarif</th>
                                <th style="text-align: center; color: white;" width="10%">Jasa RS/Klinik</th>
                                <th style="text-align: center; color: white;" width="10%">Jasa Dokter</th>
                                <th style="text-align: center; color: white;" width="10%">Jasa Perawat</th>
                                <th style="text-align: center; color: white;" width="10%">BHP</th>
                                <th style="text-align: center; color: white;" width="15%">Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr id="rowtarif_1">
                                <td>
                                  <button type='button' onclick=hapusBaris_tarif(1) class='btn red'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name='cabang[]' id='cabang1' class='select2_el_cabang_all form-control input-largex'></select>
                                </td>
                                <td>
                                  <select name='keltarif[]' id='keltarif1' class='select2_el_penjamin form-control input-largex'></select>
                                </td>
                                <td>
                                  <input name='jasars[]' id='jasars1' onchange='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='jasadr[]' id='jasadr1' onchange='totallineTarif(1)' value="0" min="0" type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='jasaperawat[]' id='jasaperawat1' onchange='totallineTarif(1)' value='0' min='0' type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='bhp[]' id='bhp1' onchange='totallineTarif(1)' value='0' min='0' type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='total[]' id='total1' readonly value='0' min='0' type='text' class='form-control rightJustified'>
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
                        <div class="tab-pane" id="luptab2">
                          <table id="cost_bhp" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead class="breadcrumb header-custom">
                              <tr>
                                <th style="text-align: center; color: white;" width="5%">Hapus</th>
                                <th style="text-align: center; color: white;" width="35%">Kode Barang</th>
                                <th style="text-align: center; color: white;" width="10%">Qty</th>
                                <th style="text-align: center; color: white;" width="10%">Satuan</th>
                                <th style="text-align: center; color: white;" width="20%">Harga</th>
                                <th style="text-align: center; color: white;" width="20%">Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr id="rowbhp_1">
                                <td>
                                  <button type='button' onclick=hapusBaris_barang(1) class='btn red'><i class='fa fa-trash-o'></i></button>
                                </td>
                                <td>
                                  <select name='kodebarang[]' id='kodebarang1' class='select2_el_farmasi_barangdata form-control input-large' onchange='showbarangname(this.value, 1)'></select>
                                </td>
                                <td>
                                  <input name='qty[]' id='qty1' onchange='totalline_barang(1);' value='1' type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='satuan[]' id='satuan1' readonly type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='harga[]' id='harga1' onchange='totalline_barang(1);' value="0" type='text' class='form-control rightJustified'>
                                </td>
                                <td>
                                  <input name='jumlah[]' id='jumlah1' readonly value='0' type='text' class='form-control rightJustified'>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="row">
                            <div class="col-xs-8">
                              <div class="wells">
                                <button type="button" onclick="tambah_barang()" class="btn green"><i class="fa fa-plus"></i> </button>
                              </div>
                            </div>
                            <div class="col-xs-4">
                              <div class="well">
                                <table>
                                  <tr>
                                    <td width="40%"><strong>Total</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59%" align="right"><strong><span id="_total"></span></strong></td>
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
            <hr>
            <div class="row">
              <div class="wells">
                <button type="button" onclick="save()" style="margin-left:15px;" class="btn blue" id="btn_save">
                  <i class="fa fa-save"></i>
                  <b>Simpan </b>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn green" onclick="this.form.reset();location.reload();">
                    <i class="fa fa-pencil-square-o"></i> <b>Data Baru </b>
                  </button>
                </div>
                <div class="btn-group">
                  <a class="btn red" href="<?= site_url('Master_tarif2') ?>">
                    <i class="fa fa-undo"></i><b> KEMBALI </b>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$this->load->view('template/footer');
?>

<script>
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
  $(".select2_akun").select2({
    allowClear: true,
    multiple: false,
    placeholder: '--- Pilih Pendapatan ---',
    //minimumInputLength: 2,
    dropdownAutoWidth: true,
    language: {
      inputTooShort: function() {
        return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
      }
    },
  });
</script>

<script>
  // tarif
  var idrow = 2;
  var rowCount;
  var arr = [1];

  function hapusBaris_tarif(param) {
    var x = document.getElementById('detail_tarif').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
  }

  function tambah() {
    var table = document.getElementById('detail_tarif');
    rowCount = table.rows.length;
    arr.push(idrow);
    var x = document.getElementById('detail_tarif').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    td1.innerHTML = "<button type='button' onclick=hapusBaris_tarif(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i></button>";
    td2.innerHTML = "<select name='cabang[]' id='cabang" + idrow + "' class='select2_el_cabang_all form-control input-largex'></select>";
    td3.innerHTML = "<select name='keltarif[]' id='keltarif" + idrow + "' class='select2_el_penjamin form-control input-largex'></select>";
    td4.innerHTML = "<input name='jasars[]' id='jasars" + idrow + "' onchange='totallineTarif(" + idrow + ")' value='0' min='0' type='text' class='form-control rightJustified'>";
    td5.innerHTML = "<input name='jasadr[]' id='jasadr" + idrow + "' onchange='totallineTarif(" + idrow + ")' value='0' min='0' type='text' class='form-control rightJustified'>";
    td6.innerHTML = "<input name='jasaperawat[]' id='jasaperawat" + idrow + "' onchange='totallineTarif(" + idrow + ")' value='0' min='0' type='text' class='form-control rightJustified'>";
    td7.innerHTML = "<input name='bhp[]' id='bhp" + idrow + "' onchange='totallineTarif(" + idrow + ")' value='0' min='0' type='text' class='form-control rightJustified'>";
    td8.innerHTML = "<input name='total[]' id='total" + idrow + "' readonly value='0' min='0' type='text' class='form-control rightJustified'>";
    initailizeSelect2_cabang_all();
    initailizeSelect2_penjamin();
    idrow++;
  }

  // bhp
  var idrowx = 2;

  function hapusBaris_barang(param) {
    var x = document.getElementById('cost_bhp').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
  }

  function tambah_barang() {
    var table = document.getElementById('cost_bhp');
    rowCount = table.rows.length;
    arr.push(idrowx);
    var x = document.getElementById('cost_bhp').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    td1.innerHTML = "<button type='button' onclick=hapusBaris_barang(" + idrowx + ") class='btn red'><i class='fa fa-trash-o'></i></button>";
    td2.innerHTML = "<select name='kodebarang[]' id='kodebarang" + idrowx + "' class='select2_el_farmasi_barangdata form-control input-large' onchange='showbarangname(this.value, " + idrowx + ")'></select>";
    td3.innerHTML = "<input name='qty[]' id='qty" + idrowx + "' onchange='totalline_barang(" + idrowx + ")' value='1' type='text' class='form-control rightJustified'>";
    td4.innerHTML = "<input name='satuan[]' id='satuan" + idrowx + "' readonly type='text' class='form-control rightJustified'>";
    td5.innerHTML = "<input name='harga[]' id='harga" + idrowx + "' onchange='totalline_barang(" + idrowx + ")' value='0' type='text' class='form-control rightJustified'>";
    td6.innerHTML = "<input name='jumlah[]' id='jumlah" + idrowx + "' readonly value='0' type='text' class='form-control rightJustified'>";
    initailizeSelect2_farmasi_barangdata();
    idrowx++;
  }
</script>

<script>
  function showbarangname(str, id) {
    $.ajax({
      url: "<?php echo base_url(); ?>farmasi_bapb/getinfobarang/?kode=" + str,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('#satuan' + id).val(data.satuan1);
        $('#harga' + id).val(separateComma(data.hargabeli));
        totalline_barang(id);
      }
    });
  }

  function totalline_barang(id) {
    var table = document.getElementById('cost_bhp');
    var row = table.rows[arr.indexOf(id) + 1];
    var qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    var total = qty * harga;
    row.cells[5].children[0].value = separateComma(total);
    var table = document.getElementById('cost_bhp');
    var rowCount = table.rows.length;
    var totalx = 0;
    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var total1 = $("#jumlah" + i).val();
      var total = Number(parseInt(total1.replaceAll(',', '')));
      totalx += total;
    }
    $("#_total").text(separateComma(totalx));
  }

  function totallineTarif(id) {
    var table = document.getElementById('detail_tarif');
    var row = table.rows[arr.indexOf(id) + 1];
    var rs = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    var dr = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    var perawat = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
    var bhp = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
    var total = rs + dr + perawat + bhp;
    row.cells[3].children[0].value = separateComma(rs);
    row.cells[4].children[0].value = separateComma(dr);
    row.cells[5].children[0].value = separateComma(perawat);
    row.cells[6].children[0].value = separateComma(bhp);
    row.cells[7].children[0].value = separateComma(total);
  }
</script>

<script>
  function save() {
    $('#btn_save').attr('disabled', true);
    $('#btn_save').text('Proses');
    if (document.getElementById('tidakaktif').checked == true) {
      // non-aktif
      var tidakaktif = 1;
    } else {
      // aktif
      var tidakaktif = 0;
    }
    var tindakan = $('[name="tindakan"]').val();
    var kodepos = $('[name="kodepos"]').val();
    var accountno = $('[name="accountno"]').val();
    if (tindakan == '' || tindakan == null) {
      swal({
        title: "TINDAKAN",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (kodepos == '' || kodepos == null) {
      swal({
        title: "UNIT",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (accountno == '' || accountno == null) {
      swal({
        title: "AKUN PENDAPATAN",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    var cabang = $("#cabang1").val();
    if (cabang == null || cabang == '') {
      swal({
        title: "DETAIL TARIF",
        html: "Minimal 1 data",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    var kodebarang = $("#kodebarang1").val();
    if (kodebarang == null || kodebarang == '') {
      swal({
        title: "COST & BHP",
        html: "Minimal 1 data",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (tindakan != null || tindakan != '' && kodepos != null || kodepos != '' && accountno != null || accountno != '' && no != null || no != '' && nox != null || nox != '') {
      $.ajax({
        url: "<?php echo site_url('Master_tarif2/f_add?tidakaktif=') ?>" + tidakaktif,
        data: $('#frmdata').serialize(),
        type: 'POST',
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
            var kodetarif = data.kodetarif;
            var table = document.getElementById('detail_tarif');
            rowCount = table.rows.length;
            for (i = 1; i < rowCount; i++) {
              var cabang = $("#cabang" + i).val();
              var keltarif = $("#keltarif" + i).val();
              var jasarsx = $("#jasars" + i).val();
              var jasars = Number(parseInt(jasarsx.replaceAll(',', '')));
              var jasadrx = $("#jasadr" + i).val();
              var jasadr = Number(parseInt(jasadrx.replaceAll(',', '')));
              var jasaperawatx = $("#jasaperawat" + i).val();
              var jasaperawat = Number(parseInt(jasaperawatx.replaceAll(',', '')));
              var bhpx = $("#bhp" + i).val();
              var bhp = Number(parseInt(bhpx.replaceAll(',', '')));
              var totalx = $("#total" + i).val();
              var total = Number(parseInt(totalx.replaceAll(',', '')));
              var param = '?kodetarif=' + kodetarif + '&cabang=' + cabang + '&keltarif=' + keltarif + '&jasars=' + jasars + '&jasadr=' + jasadr + '&jasaperawat=' + jasaperawat + '&bhp=' + bhp + '&total=' + total;
              $.ajax({
                url: "<?= site_url('Master_tarif2/fd_add') ?>" + param,
                type: "POST",
                dataType: "JSON",
                // success: function(data) {
                //   console.log(data);
                // }
              });
            }
            var table = document.getElementById('cost_bhp');
            rowCount = table.rows.length;
            for (i = 1; i < rowCount; i++) {
              var kodebarang = $("#kodebarang" + i).val();
              var qtyx = $("#qty" + i).val();
              var qty = Number(parseInt(qtyx.replaceAll(',', '')));
              var satuan = $("#satuan" + i).val();
              var hargax = $("#harga" + i).val();
              var harga = Number(parseInt(hargax.replaceAll(',', '')));
              var jumlahx = $("#jumlah" + i).val();
              var jumlah = Number(parseInt(jumlahx.replaceAll(',', '')));
              var param = '?kodetarif=' + kodetarif + '&kodebarang=' + kodebarang + '&qty=' + qty + '&satuan=' + satuan + '&harga=' + harga + '&jumlah=' + jumlah;
              $.ajax({
                url: "<?= site_url('Master_tarif2/fdc_add') ?>" + param,
                type: "POST",
                dataType: "JSON",
                // success: function(data){
                //   console.log(data);
                // }
              });
            }
            swal({
              title: "TINDAKAN " + tindakan,
              html: "Berhasil ditambahkan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              $("#btn_save").attr("disabled", false);
              $("#btn_save").text("Simpan");
              location.href = "<?= site_url('Master_tarif2') ?>";
            });
          } else {
            swal({
              title: "DATA",
              html: "Gagal Simpan",
              type: "error",
              confirmButtonText: "OK"
            });
          }
          $("#btn_save").attr("disabled", false);
          $("#btn_save").text("Simpan");
        }
      });
      $('#btn_save').attr('disabled', false);
    }
  }
</script>