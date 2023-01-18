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
                      <label class="col-md-3 control-label">KODE <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="text" name="kodokter" id="kodokter" class="form-control" placeholder="Otomatis" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NAMA <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="text" name="nadokter" id="nadokter" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NIK <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="nik" name="nik" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NO. SIP <font color="red">*</font></label>
                      <div id="nopo" class="col-md-9">
                        <input id="nosip" name="nosip" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NPWP <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="npwp" name="npwp" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NO. HP <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="hp" name="hp" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">STATUS <font color="red">*</font></label>
                      <div class="col-md-4">
                        <select name="status" id="status" class="form-control">
                          <option value="ON">Aktif</option>
                          <option value="OFF">Tidak Aktif</option>
                        </select>
                      </div>
                      <div class="col-md-5">
                        <input type="hidden" name="jenispegawai" id="jenispegawai" class="form-control" value="1" >
                        <input type="text" name="jenispegawaix" id="jenispegawaix" class="form-control" placeholder="Dokter" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">TGL MASUK <font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="date" name="tglmasuk" id="tglmasuk" class="form-control" value="<?= date('Y-m-d'); ?>">
                      </div>
                      <label class="col-md-3 control-label">TGL KELUAR</label>
                      <div class="col-md-3">
                        <input type="date" name="tglberhenti" id="tglberhenti" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">ALAMAT <font color="red">*</font></label>
                      <div class="col-md-9">
                        <textarea name="alamat" id="alamat" class="form-control"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">EMAIL <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="email" name="email" id="email" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <h3>UNIT BISNIS</h3>
                <div class="row">
                  <div class="col-md-6">
                    <table id="datatable_unit" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                      <thead class="page-breadcrumb breadcrumb">
                        <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                        <th class="title-white" width="95%" style="text-align: center">Unit Bisnis</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td width="5%">
                            <button type='button' onclick="hapusBaris_unit(1)" class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td width="95%">
                            <select name="unit[]" id="unit1" class="select2_el_poli form-control input-largex"></select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="tambah_unit()" class="btn green"><i class="fa fa-plus"></i> </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <table id="datatable_lokasi" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                      <thead class="page-breadcrumb breadcrumb">
                        <th class="title-white" width="5%" style="text-align: center">Hapus</th>
                        <th class="title-white" width="95%" style="text-align: center">Lokasi Praktek</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td width="5%">
                            <button type='button' onclick="hapusBaris_lokasi(1)" class='btn red'><i class='fa fa-trash-o'>
                          </td>
                          <td width="95%">
                            <select name="lokasi[]" id="lokasi1" class="select2_el_cabang_all form-control input-largex"></select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="tambah_lokasi()" class="btn green"><i class="fa fa-plus"></i> </button>
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
                  <a class="btn red" href="<?= site_url('Master_dokter2') ?>">
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

<!-- table -->
<script>
  // unit
  function hapusBaris_unit(param) {
    var x = document.getElementById('datatable_unit').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
  }

  var idrow = 2;
  var rowCount;
  var arr = [1];

  function tambah_unit() {
    var table = document.getElementById('datatable_unit');
    rowCount = table.rows.length;
    arr.push(idrow);
    var x = document.getElementById('datatable_unit').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var button = "<button type='button' onclick='hapusBaris_unit(" + idrow + ")' id='btnhapus" + idrow + "' class='btn red'><i class='fa fa-trash-o'></i></button>";
    var unit = "<select name='unit[]'  id='unit" + idrow + "' class='select2_el_poli form-control'></select>";
    td1.innerHTML = button;
    td2.innerHTML = unit;
    initailizeSelect2_poli();
    idrow++;
  }

  // lokasi
  function hapusBaris_lokasi(param) {
    var x = document.getElementById('datatable_lokasi').deleteRow(arr.indexOf(param) + 1);
    arr.splice(arr.indexOf(param), 1);
    rowCount--;
  }

  var idrowx = 2;
  var rowCount;
  var arr = [1];

  function tambah_lokasi() {
    var table = document.getElementById('datatable_lokasi');
    rowCount = table.rows.length;
    arr.push(idrowx);
    var x = document.getElementById('datatable_lokasi').insertRow(rowCount);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var button = "<button type='button' onclick='hapusBaris_lokasi(" + idrowx + ")' id='btnhapus" + idrowx + "' class='btn red'><i class='fa fa-trash-o'></i></button>";
    var lokasi = "<select name='lokasi[]'  id='lokasi" + idrowx + "' class='select2_el_cabang_all form-control'></select>";
    td1.innerHTML = button;
    td2.innerHTML = lokasi;
    initailizeSelect2_cabang_all();
    idrowx++;
  }
</script>

<!-- function -->
<script>
  function save() {
    $("#btn_save").attr("disabled", true);
    $("#btn_save").text("proses");
    // header
    var nadokter        = $('[name="nadokter"]').val();
    var nik             = $('[name="nik"]').val();
    var nosip           = $('[name="nosip"]').val();
    var npwp            = $('[name="npwp"]').val();
    var hp              = $('[name="hp"]').val();
    var status          = $('[name="status"]').val();
    var tglmasuk        = $('[name="tglmasuk"]').val();
    var alamat          = $('[name="alamat"]').val();
    var jenispegawai    = $('[name="jenispegawai"]').val();
    var email           = $('[name="email"]').val();
    var unit            = $('#unit1').val();
    var lokasi          = $('#lokasi1').val();
    if (nadokter == '' || nadokter == null) {
      swal({
        title: "NAMA",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (nik == '' || nik == null) {
      swal({
        title: "NIK",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (nosip == '' || nosip == null) {
      swal({
        title: "NO. SIP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (npwp == '' || npwp == null) {
      swal({
        title: "NPWP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (hp == '' || hp == null) {
      swal({
        title: "NO. HP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (status == '' || status == null) {
      swal({
        title: "STATUS",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (tglmasuk == '' || tglmasuk == null) {
      swal({
        title: "TGL MASUK",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (alamat == '' || alamat == null) {
      swal({
        title: "ALAMAT",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (email == '' || email == null) {
      swal({
        title: "EMAIL",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (unit == '' || unit == null) {
      swal({
        title: "UNIT PRAKTEK",
        html: "Minimal 1 data",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
    if (lokasi == '' || lokasi == null) {
      swal({
        title: "LOKASI PRAKTEK",
        html: "Minimal 1 data",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }

    if (nadokter != '' || nadokter != null && nik != '' || nik != null && nosip != '' || nosip != null && npwp != '' || npwp != null && hp != '' || hp != null && status != '' || status != null && tglmasuk != '' || tglmasuk != null && alamat != '' || alamat != null && email != '' || email != null && unit != '' || unit != null && lokasi != '' || lokasi != null) {
      $.ajax({
        url: "<?php echo site_url('Master_dokter2/f_add') ?>",
        data: $('#frmdata').serialize(),
        type: 'POST',
        dataType: "json",
        success: function(data) {
          if (data.status == 1) {
            var kodokter = data.kodokter;
            var table = document.getElementById('datatable_unit');
            rowCount = table.rows.length;
            for (i = 1; i < rowCount; i++) {
              var unit = $("#unit" + i).val();
              $.ajax({
                url: "<?= site_url('Master_dokter2/fd_add?unit=') ?>" + unit + "&kodokter=" + kodokter,
                type: "POST",
                dataType: "JSON",
              });
            }
            var table = document.getElementById('datatable_lokasi');
            rowCount = table.rows.length;
            for (i = 1; i < rowCount; i++) {
              var lokasi = $("#lokasi" + i).val();
              $.ajax({
                url: "<?= site_url('Master_dokter2/fdc_add?lokasi=') ?>" + lokasi + "&kodokter=" + kodokter,
                type: "POST",
                dataType: "JSON",
              });
            }
            swal({
              title: "DOKTER " + nadokter,
              html: "Berhasil ditambahkan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('Master_dokter2') ?>";
            });
          } else {
            swal({
              title: "DATA",
              html: "Gagal Simpan",
              type: "error",
              confirmButtonText: "OK"
            });
            $("#btn_save").attr("disabled", false);
            $("#btn_save").text("Simpan");
          }
        }
      });
    } else {
      swal({
        title: "DATA",
        html: "Harus Lengkap",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_save").attr("disabled", false);
      $("#btn_save").text("Simpan");
      return;
    }
  }
</script>