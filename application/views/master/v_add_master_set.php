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
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab1" data-toggle="tab">
                  Umum
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab2" data-toggle="tab">
                  Rekam Medis dan Pendaftaran Pasien
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab3" data-toggle="tab">
                  R Jalan
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab4" data-toggle="tab">
                  R Inap
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab5" data-toggle="tab">
                  Laborat
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab6" data-toggle="tab">
                  Radiologi
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab7" data-toggle="tab">
                  Bedah
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;" class="active">
                <a href="#tab8" data-toggle="tab">
                  Farmasi
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab9" data-toggle="tab">
                  Kasir
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab10" data-toggle="tab">
                  Accounting
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab11" data-toggle="tab">
                  Biaya Adm pasien
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab12" data-toggle="tab">
                  Periode
                </a>
              </li>
              <li style="border-right: 1px solid #ddd;">
                <a href="#tab13" data-toggle="tab">
                  Urut
                </a>
              </li>
            </ul>
            <br>
            <div class="tab-content">
              
              <div class="tab-pane active" id="tab1">
              </div>
              <div class="tab-pane active" id="tab2">
              </div>
              <div class="tab-pane active" id="tab3">
              </div>
              <div class="tab-pane active" id="tab4">
              </div>
              <div class="tab-pane active" id="tab5">
              </div>
              <div class="tab-pane active" id="tab6">
              </div>
              <div class="tab-pane active" id="tab7">
              </div>
              <div class="tab-pane active" id="tab8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Format Qty Obat<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Auto Update Price<font color="red">*</font></label>
                      <div class="col-md-5">
                        <table>
                          <tr>
                            <td>
                              <input type="radio" id="j_umum" name="j_jaminan"  onclick="cek_umum()">
                            </td>
                            <td>
                              <input type="radio" id="j_jaminan" name="j_jaminan"  onclick="cek_jaminan()">
                            </td>
                          </tr>
                        </table>  
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Format Harga<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Cek Stock Untuk Validasi<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Format Total<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Transaksi Penjualan<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Default Cetak Dokumen<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Blokir Obat Bila Tidak Sesuai Ketentuan<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Rubah Harga<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Peringatan Jika Stock 0<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Jenis Harga<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Penentuan Harga Jual<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>

                <hr>
                <!-- foot -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Harga Resep Umum/RJ<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Default Gudang Logistik<font color="red">*</font></label>
                      <div class="col-md-5">
                        <input type="text" name="tindakan" id="tindakan" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Harga Resep Jaminan <font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Jasa Resep Dr. Umum <font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Obat Bebas <font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Jasa Resep Dr. Spesialis <font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Harga Resep Inap Umum<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Harga Kertas/Bungkus<font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Harga Resep Inap Jaminan <font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Harga Kapsul/Kapsul<font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Margin Obat UGD<font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      <div class="col-md-2">
                        <span class="input-group-btn">
                            <label class="control-label"> &nbsp;&nbsp;&nbsp; <b> % </b></label>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Uang R<font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                     
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Uang Racik<font color="red">*</font></label>
                      <div id="nopo" class="col-md-5">
                        <input type="number" name="kodetarif" id="kodetarif" class="form-control" placeholder="">
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane active" id="tab9">
              </div>
              <div class="tab-pane active" id="tab10">
              </div>
              <div class="tab-pane active" id="tab11">
              </div>
              <div class="tab-pane active" id="tab12">
              </div>
              <div class="tab-pane active" id="tab13">
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
                  <a class="btn red" href="<?= site_url('Master_seting_r') ?>">
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
    
    var tindakan = $('[name="tindakan"]').val();
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
    
    if (tindakan != null || tindakan != '' && kodepos != null || kodepos != '' && accountno != null || accountno != '' && no != null || no != '' && nox != null || nox != '') {
      $.ajax({
        url: "<?php echo site_url('Master_seting_r/f_add') ?>,
        data: $('#frmdata').serialize(),
        type: 'POST',
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "TINDAKAN " + tindakan,
              html: "Berhasil ditambahkan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              $("#btn_save").attr("disabled", false);
              $("#btn_save").text("Simpan");
              location.href = "<?= site_url('Master_seting_r') ?>";
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