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
          Edit Data
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
                        <input type="text" name="kodokter" id="kodokter" class="form-control" value="<?= $header->kodokter; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NAMA <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="text" name="nadokter" id="nadokter" class="form-control" value="<?= $header->nadokter; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NIK <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="nik" name="nik" class="form-control" type="text" value="<?= $header->nik; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NO. SIP <font color="red">*</font></label>
                      <div id="nopo" class="col-md-9">
                        <input id="nosip" name="nosip" class="form-control" type="text" value="<?= $header->nosip; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NPWP <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="npwp" name="npwp" class="form-control" type="text" value="<?= $header->npwp; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">NO. HP <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input id="hp" name="hp" class="form-control" type="text" value="<?= $header->hp; ?>">
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
                          <option <?= ($header->status == 'ON' ? 'selected' : '') ?> value="ON">Aktif</option>
                          <option <?= ($header->status == 'OFF' ? 'selected' : '') ?> value="OFF">Tidak Aktif</option>
                        </select>
                      </div>
                      <div class="col-md-5">
                        <input type="hidden" name="jenispegawai" id="jenispegawai" class="form-control" value="1">
                        <input type="text" name="jenispegawaix" id="jenispegawaix" class="form-control" placeholder="Dokter" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">TGL MASUK <font color="red">*</font></label>
                      <div class="col-md-3">
                        <input type="date" name="tglmasuk" id="tglmasuk" class="form-control" value="<?= date('Y-m-d', strtotime($header->tglmasuk)); ?>">
                      </div>
                      <label class="col-md-3 control-label">TGL KELUAR</label>
                      <div class="col-md-3">
                        <input type="date" name="tglberhenti" id="tglberhenti" class="form-control" value="<?= date('Y-m-d', strtotime($header->tglberhenti)); ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-md-3 control-label">ALAMAT <font color="red">*</font></label>
                      <div class="col-md-9">
                        <textarea name="alamat" id="alamat" class="form-control"><?= $header->alamat; ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="col-md-3 control-label">EMAIL <font color="red">*</font></label>
                      <div class="col-md-9">
                        <input type="email" name="email" id="email" class="form-control" value="<?= $header->email; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <h3>PRAKTEK</h3>
                <div class="row">
                  <div class="col-md-6">
                    <table id="datatable_unit" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                      <thead class="page-breadcrumb breadcrumb">
                        <th class="title-white" style="text-align: center; width: 5%;">Hapus</th>
                        <th class="title-white" style="text-align: center">Unit Praktek</th>
                      </thead>
                      <tbody>
                        <?php $no = 1;
                        foreach ($kopoli as $k) : ?>
                          <tr id="unitrow_<?= $no; ?>">
                            <td>
                              <button type='button' onclick='hapusBarisIni(<?= $no; ?>);' class='btn red'><i class='fa fa-trash-o'></i></button>
                            </td>
                            <td>
                              <input type="hidden" id="unit<?= $no; ?>" name="unit[]" value="<?= $k->kopoli; ?>">
                              <select name="unit1[]" id="unit1<?= $no; ?>" class="select2_el_poli form-control input-largex" disabled>
                                <option value="<?= $k->kopoli; ?>"><?= $k->namapost; ?></option>
                              </select>
                            </td>
                          </tr>
                        <?php $no++;
                        endforeach; ?>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="tambah_unit()" class="btn green"><i class="fa fa-plus"></i> </button>
                          <!-- <button type="button" onclick="hapusBaris_unit()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <table id="datatable_lokasi" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                      <thead class="page-breadcrumb breadcrumb">
                        <th class="title-white" style="text-align: center; width: 5%;">Hapus</th>
                        <th class="title-white" style="text-align: center">Lokasi Praktek</th>
                      </thead>
                      <tbody>
                        <?php $no = 1;
                        foreach ($drcabang as $dc) : ?>
                          <tr id="lokasirow_<?= $no; ?>">
                            <td>
                              <button type='button' onclick='hapusBarisIni_l(<?= $no; ?>);' class='btn red'><i class='fa fa-trash-o'></i></button>
                            </td>
                            <td>
                              <input type="hidden" id="lokasi<?= $no; ?>" name="lokasi[]" value="<?= $dc->koders; ?>">
                              <select name="lokasi1[]" id="lokasi1<?= $no; ?>" class="select2_el_cabang_all form-control input-largex" disabled>
                                <option value="<?= $dc->koders; ?>"><?= $dc->namars; ?></option>
                              </select>
                            </td>
                          </tr>
                        <?php $no++;
                        endforeach; ?>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-9">
                        <div class="wells">
                          <button type="button" onclick="tambah_lokasi()" class="btn green"><i class="fa fa-plus"></i> </button>
                          <!-- <button type="button" onclick="hapusBaris_lokasi()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
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
                <button type="button" onclick="update()" style="margin-left:15px;" class="btn yellow" id="btn_update">
                  <i class="fa fa-refresh"></i>
                  <b>Update </b>
                </button>
                <div class="btn-group">
                  <a href="<?= site_url('Master_dokter2/v_add') ?>" type="button" class="btn green">
                    <i class="fa fa-pencil-square-o"></i> <b>Data Baru </b>
                  </a>
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

<script>
  // unit
  var idrow = <?php echo $jumunit + 1; ?>;
  var rowCount;
  var arr = [1];

  // function hapusBaris_unit() {
  //   if (idrow > 2) {
  //     var x = document.getElementById('datatable_unit').deleteRow(idrow - 1);
  //     idrow--;
  //   }
  // }

  function tambah_unit() {
    var table = document.getElementById('datatable_unit');
    rowCount = table.rows.length;
    arr.push(idrow);

    var x = document.getElementById('datatable_unit').insertRow(rowCount);

    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);

    td1.innerHTML = "<button type='button' onclick='hapusBarisIni("+idrow+");' class='btn red'><i class='fa fa-trash-o'></i></button>";
    td2.innerHTML = "<select name='unit[]'  id='unit" + idrow + "' class='select2_el_poli form-control'></select>";

    initailizeSelect2_poli();

    idrow++;
  }

  function hapusBarisIni(param) {
    $("#unitrow_" + param).remove();
  }

  // lokasi
  var idrowx = <?php echo $jumcabang + 1; ?>;
  var rowCountx;
  var arrx = [1];

  function hapusBaris_lokasi() {
    if (idrow > 2) {
      var x = document.getElementById('datatable_lokasi').deleteRow(idrowx - 1);
      idrowx--;
    }
  }

  function tambah_lokasi() {
    var table = document.getElementById('datatable_lokasi');
    rowCountx = table.rows.length;
    arrx.push(idrowx);

    var x = document.getElementById('datatable_lokasi').insertRow(rowCountx);

    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);

    td1.innerHTML = "<button type='button' onclick='hapusBarisIni_l("+idrow+");' class='btn red'><i class='fa fa-trash-o'></i></button>";
    td2.innerHTML = "<select name='lokasi[]'  id='lokasi" + idrowx + "' class='select2_el_cabang_all form-control'></select>";

    initailizeSelect2_cabang_all();

    idrowx++;
  }

  function hapusBarisIni_l(param) {
    $("#lokasirow_" + param).remove();
  }
</script>

<script>
  function update() {
    $("#btn_update").attr("disabled", true);
    $("#btn_update").text("proses");
    // header
    var nadokter = $('[name="nadokter"]').val();
    var nik = $('[name="nik"]').val();
    var nosip = $('[name="nosip"]').val();
    var npwp = $('[name="npwp"]').val();
    var hp = $('[name="hp"]').val();
    var status = $('[name="status"]').val();
    var tglmasuk = $('[name="tglmasuk"]').val();
    var alamat = $('[name="alamat"]').val();
    var jenispegawai = $('[name="jenispegawai"]').val();
    var email = $('[name="email"]').val();
    var kodokter = $('#kodokter').val();
    if (nadokter == '' || nadokter == null) {
      swal({
        title: "NAMA",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (nik == '' || nik == null) {
      swal({
        title: "NIK",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (nosip == '' || nosip == null) {
      swal({
        title: "NO. SIP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (npwp == '' || npwp == null) {
      swal({
        title: "NPWP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (hp == '' || hp == null) {
      swal({
        title: "NO. HP",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (status == '' || status == null) {
      swal({
        title: "STATUS",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (tglmasuk == '' || tglmasuk == null) {
      swal({
        title: "TGL MASUK",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (alamat == '' || alamat == null) {
      swal({
        title: "ALAMAT",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (email == '' || email == null) {
      swal({
        title: "EMAIL",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
    if (nadokter != '' || nadokter != null && nik != '' || nik != null && nosip != '' || nosip != null && npwp != '' || npwp != null && hp != '' || hp != null && status != '' || status != null && tglmasuk != '' || tglmasuk != null && alamat != '' || alamat != null && email != '' || email != null) {
      swal({
        title: 'UPDATE DATA',
        html: 'Yakin Ingin Update Data Dokter ' + nadokter,
        type: 'question',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-success',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, update',
        cancelButtonText: 'Tidak'
      }).then(function() {
        $.ajax({
          url: "<?php echo site_url('Master_dokter2/f_edit/') ?>" + kodokter,
          data: $('#frmdata').serialize(),
          type: 'POST',
          dataType: "json",
          success: function(data) {
            if (data.status == 1) {
              var table = document.getElementById('datatable_unit');
              rowCount = table.rows.length;
              for (i = 1; i < rowCount; i++) {
                var row = table.rows[i];
                var unit = row.cells[1].children[0].value;
                $.ajax({
                  url: "<?= site_url('Master_dokter2/fd_edit?unit=') ?>" + unit + "&kodokter=" + kodokter,
                  type: "POST",
                  dataType: "JSON",
                });
              }
              var table = document.getElementById('datatable_lokasi');
              rowCount = table.rows.length;
              for (i = 1; i < rowCount; i++) {
                var row = table.rows[i];
                var lokasi = row.cells[1].children[0].value;
                $.ajax({
                  url: "<?= site_url('Master_dokter2/fdc_edit?lokasi=') ?>" + lokasi + "&kodokter=" + kodokter,
                  type: "POST",
                  dataType: "JSON",
                  // success: function(data) {
                  //   console.log(data)
                  // }
                });
              }
              swal({
                title: "DOKTER " + nadokter,
                html: "Berhasil diupdate",
                type: "success",
                confirmButtonText: "OK"
              }).then((value) => {
                location.href = "<?= site_url('Master_dokter2') ?>";
              });
            } else {
              swal({
                title: "DATA",
                html: "Gagal diupdate",
                type: "error",
                confirmButtonText: "OK"
              });
              $("#btn_save").attr("disabled", false);
              $("#btn_save").text("Simpan");
            }
          }
        });
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
    } else {
      swal({
        title: "DATA",
        html: "Harus Lengkap",
        type: "error",
        confirmButtonText: "OK"
      });
      $("#btn_update").attr("disabled", false);
      $("#btn_update").text("Update");
      return;
    }
  }
</script>