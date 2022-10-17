    <?php
    $this->load->view('template/header');
    $this->load->view('template/body');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/barcodes/JsBarcode.code128.min.js"></script>

    <!-- svg-exportJS prerequisite: canvg -->
    <script src="https://unpkg.com/canvg@3.0.1/lib/umd.js"></script>
    <!-- svg-exportJS plugin -->
    <script src="https://sharonchoong.github.io/svg-exportJS/svg-export.min.js"></script>

    <style>
.select2-container {
  z-index: 99999;
}

/* Style milik Deri */
.flex {
  display: flex;
}

.table-auto {
  table-layout: auto;
}

.flex-row {
  flex-direction: row;
}

.flex-col {
  flex-direction: column;
}

.flew-wrap {
  flex-wrap: wrap;
}

.w-3\/4 {
  width: 75%;
}

.w-1\/3 {
  width: 33.33%;
}

.w-1\/2 {
  width: 50%;
}

.w-fit {
  width: fit-content;
}

.h-screen {
  height: 100vh;
}

.w-screen {
  width: 100vw;
}

.overflow-y-scroll {
  overflow-y: scroll;
}

#barcode{
  margin-top: 10px;
}

#download-barcode{
  display: block;
  padding: 10px;
}

#barcode-wrapper{
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 400px;
    height: auto;
    padding: 10px;
    border: 1px solid black;
}

.barcode-text{
    font-family: monospace;
    font-size: 16px;
    margin: 0;
}
    </style>

    <div class="row">
      <div class="col-md-12">
        <h3 class="page-title">
          <span class="title-unit">
            &nbsp;<?php echo $this->session->userdata('unit'); ?>
          </span>
          -
          <span class="title-web">Aktiva Tetap <small>Data Aktiva Tetap</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">

          <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo base_url(); ?>dashboard">
              Awal
            </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="#">
              Aktiva Tetap
            </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="#">
              Aktiva Tetap
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption">
              Daftar Aktiva Tetap
            </div>

          </div>
          <div class="portlet-body">
            <div class="table-toolbar">
              <div class="btn-group">

              </div>
              <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data
                Baru</button>
              <div class="btn-group pull-right">
                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                  <li>
                    <a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
                  </li>
                  <li>
                    <a href="<?php echo base_url() ?>at_jenis/export">
                      Export
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
              <thead class="breadcrumb">
                <tr>
                  <th style="text-align: center">Cabang</th>
                  <th style="text-align: center">Kode Aktiva </th>
                  <th style="text-align: center">Serial Number</th>
                  <th style="text-align: center">Nama Aktiva</th>
                  <th style="text-align: center">Lokasi</th>
                  <th style="text-align: center">Pemakai</th>
                  <th style="text-align: center">Tgl. Pengakuan</th>
                  <th style="text-align: center">Tgl. Pemakaian</th>
                  <th style="text-align: center">Tgl. Kalibrasi</th>
                  <th style="text-align: center;width:12%;">Aksi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>
    </div>

    <?php
    $this->load->view('template/footer_tb');
    $this->load->view('template/v_report');
    ?>


    <script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {
  //datatables
  table = $('#table').DataTable({

    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": "<?php echo site_url('at_at/ajax_list') ?>",
      "type": "POST"
    },

    "oLanguage": {
      "sEmptyTable": "Tidak ada data",
      "sInfoEmpty": "Tidak ada data",
      "sInfoFiltered": " - Dipilih dari _MAX_ data",
      "sSearch": "Pencarian Data : ",
      "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
      "sLengthMenu": "_MENU_ Baris",
      "sZeroRecords": "Tida ada data",
      "oPaginate": {
        "sPrevious": "Sebelumnya",
        "sNext": "Berikutnya"
      }
    },

    "aLengthMenu": [
      [5, 15, 20, -1],
      [5, 15, 20, "Semua"] // change per page values here
    ],

    //Set column definition initialisation properties.
    "columnDefs": [{
      "targets": [-1], //last column
      "orderable": false, //set not orderable
    }, ],

  });

  //datepicker
  $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    todayHighlight: true,
    orientation: "top auto",
    todayBtn: true,
    todayHighlight: true,
  });

  //set input/textarea/select event when change value, remove class error and remove text help block 
  $("input").change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $("textarea").change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $("select").change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
});

function add_data() {
  save_method = 'add';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $('[name="kode"]').val('Generated automatically');
  $('#modal_form').modal('show'); // show bootstrap modal
  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

/**
 * Fungsi untuk memformat tanggal
 */
function formatDate(date) {
  var unix = new Date(Date.parse(date));
  var options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  }

  return unix.toLocaleDateString('id-ID', options)
}

/**
 * Method untuk menampilkan detail aktiva tetap
 */
function view_data(id) {
  // Request ajax untuk mendapatkan data
  $.ajax({
    url: "<?= site_url('at_at/ajax_detail') ?>/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      $('#target-body').empty()

      // Deploy data rincian ke DOM
      $('#target-body').append(
        /*html*/
        `
                <tr>
                    <td class="w-fit">Unit</td>
                    <td>${ data.koders }</td>
                </tr>
                <tr>
                    <td class="w-fit">Kode Aktiva</td>
                    <td>${ data.kodefix }</td>
                </tr>
                <tr>
                    <td class="w-fit">Nomor Serial</td>
                    <td>${ data.serialno }</td>
                </tr>
                <tr>
                    <td class="w-fit">Tanggal Akuisisi</td>
                    <td>${ data.tglaku1 }</td>
                </tr>
                <tr>
                    <td class="w-fit">Tanggal Pakai</td>
                    <td>${ data.tglpakai1 }</td>
                </tr>
                <tr>
                    <td class="w-fit">Tanggal Kalibrasi</td>
                    <td>${ data.tglkalibrasi1 }</td>
                </tr>
                <tr>
                    <td class="w-fit">Nama Aktiva</td>
                    <td>${ data.namafix }</td>
                </tr>
                <tr>
                    <td class="w-fit">Lokasi</td>
                    <td>${ data.lokasi }</td>
                </tr>
                <tr>
                    <td class="w-fit">Pemakai</td>
                    <td>${ data.pemakai }</td>
                </tr>
                <tr>
                    <td class="w-fit">Golongan Aktiva</td>
                    <td>${ data.group_detail.fixid } | ${ data.group_detail.groupname }</td>
                </tr>
                <tr>
                    <td class="w-fit">Umur Aktiva</td>
                    <td>${ data.group_detail.tahunsusut } tahun (${ data.group_detail.tahunsusut * 12 } bulan)</td>
                </tr>
                <tr>
                    <td class="w-fit">Metode Penyusutan</td>
                    <td>${ data.group_detail.metode }</td>
                </tr>
                <tr>
                    <td class="w-fit">Penyusutan Per Tahun</td>
                    <td>${ data.group_detail.fixrate } %</td>
                </tr>
                <tr>
                    <td class="w-fit">Akun Aktiva</td>
                    <td>${ data.group_detail.fix_account.accountno } - ${ data.group_detail.fix_account.acname }</td>
                </tr>
                <tr>
                    <td class="w-fit">Akun Akumulasi Penyusutan</td>
                    <td>${ data.group_detail.depreciation_account.accountno } - ${ data.group_detail.depreciation_account.acname }</td>
                </tr>
                <tr>
                    <td class="w-fit">Akun Biaya Penyusutan</td>
                    <td>${ data.group_detail.cost_account.accountno } - ${ data.group_detail.cost_account.acname }</td>
                </tr>
                `
      )

      // Generate barcode
      JsBarcode("#barcode", data.serialno)
      $('#bc-nama').html(data.namafix)
      $('#bc-pakai').html(data.tglpakai1)
      $('#bc-kalibrasi').html(data.tglkalibrasi1)
      $('#download-barcode').attr('href', `<?= site_url() ?>/at_at/barcode?nama=${ data.namafix }&serial=${data.serialno}&tglpakai=${data.tglpakai1}&tglkalibrasi=${data.tglkalibrasi1}`)

      // Deploy data penyusutan ke DOM
      $('#tabel-penyusutan').empty()
      for (var i = 0; i < data.depreciation_list.length; i++) {
        $('#tabel-penyusutan').append(
          /* html */
          `
                    <tr>
                        <td>${ i + 1 }</td>
                        <td>${ data.depreciation_list[i].kodesusut }</td>
                        <td style="text-align: center">${ formatDate(data.depreciation_list[i].tglsusut) }</td>
                        <td style="text-align: center">${ data.depreciation_list[i].prosensusut } %</td>
                        <td>${ new Intl.NumberFormat('id-ID', { style: "currency", currency: "IDR" }).format(data.depreciation_list[i].susutrp) }</td>
                        <td>${ data.depreciation_list[i].susut == 1 ? 'Posted' : 'Unposted' }</td>
                    </tr>
                    `
        )
      }


      //Tampilkan data
      $('#detail-aktiva').modal('show')
    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Error get data from ajax');
    }
  })
}

function edit_data(id) {
  save_method = 'update';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string

  //Ajax Load data from ajax
  $.ajax({
    url: "<?php echo site_url('at_at/ajax_edit/') ?>/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      console.log(data.tglpakai)
      $('[name="cabang"]').val(data.koders);
      $('[name="id"]').val(data.id);
      $('[name="kode"]').val(data.kodefix);
      $('[name="serial"]').val(data.serialno);
      $('[name="nama"]').val(data.namafix);
      $('[name="jenis"]').val(data.fixid);
      $('[name="jumlah"]').val(data.jumlah);
      $('[name="hargaperolehan"]').val(data.nilaiaktiva);
      $('[name="tanggalbeli"]').val(moment(data.tglaku).format('YYYY-MM-DD'));
      $('[name="tanggalpakai"]').val(moment(data.tglpakai).format('YYYY-MM-DD'));
      $('[name="tanggalkalibrasi"]').val(moment(data.tglkalibrasi).format('YYYY-MM-DD'));
      $('[name="lokasi"]').val(data.lokasi);
      $('[name="pemakai"]').val(data.pemakai);

      //$('[name="dob"]').datepicker('update',data.dob);
      $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
      $('#modal_form .modal-title').text('Edit Data'); // Set title to Bootstrap modal title

    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Error get data from ajax');
    }
  });
}

function reload_table() {
  table.ajax.reload(null, false); //reload datatable ajax 
}

function save() {
  $('#btnSave').text('saving...'); //change button text
  $('#btnSave').attr('disabled', true); //set button disable 
  var url;

  if (save_method == 'add') {
    url = "<?php echo site_url('at_at/ajax_add') ?>";
  } else {
    url = "<?php echo site_url('at_at/ajax_update') ?>";
  }

  // ajax adding data to database
  $.ajax({
    url: url,
    type: "POST",
    data: $('#form').serialize(),
    dataType: "JSON",
    success: function(data) {

      if (data.status) //if success close modal and reload ajax table
      {
        $('#modal_form').modal('hide');
        console.log(data.status)
        // reload_table();
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass(
            'has-error'); //select parent twice to select div form-group class and add has-error class
          $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[
            i]); //select span help-block class set text error string
        }
      }
      $('#btnSave').text('Simpan'); //change button text
      $('#btnSave').attr('disabled', false); //set button enable 


    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Error adding / update data');
      $('#btnSave').text('save'); //change button text
      $('#btnSave').attr('disabled', false); //set button enable 

    }
  });
}

function delete_data(id) {
  if (confirm('Yakin data bank dengan kode ' + id + ' ini akan dihapus ?')) {
    // ajax delete data to database
    $.ajax({
      url: "<?php echo site_url('at_at/ajax_delete') ?>/" + id,
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        //if success reload ajax table
        $('#modal_form').modal('hide');
        reload_table();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error deleting data');
      }
    });

  }
}

function cetaklap(str) {
  var xhttp;
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
  //xhttp.open("GET", "getlaporan.php?q="+x, true);  
  xhttp.open("GET", "<?php echo base_url(); ?>at_cetak/" + str, true);
  xhttp.send();
}
    </script>

    <script>
$(document).ready(function() {

  $('.print_laporan').on("click", function() {
    $('.modal-title').text('MASTER');
    var no_daftar = this.id;
    $("#MyModalBody").html('<iframe src="<?php echo base_url(); ?>at_at/cetak/' + no_daftar +
      '" frameborder="no" width="100%" height="420"></iframe>');
  });
});

jQuery(document).ready(function() {
  ComponentsPickers.init();
});
    </script>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Data Aktiva Tetap</h3>
          </div>
          <div class="modal-body form">
            <form action="#" id="form" class="form-horizontal">
              <input type="hidden" value="" id="id" name="id" />
              <div class="form-body">
                <div class="form-group">
                  <label class="control-label col-md-3">Cabang</label>
                  <div class="col-md-9">
                    <input class="form-control" name="cabang" id="cabang" type="text" readonly value="<?= $cabang; ?>">
                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label col-md-3">Kode Aktiva</label>
                  <div class="col-md-9">
                    <input name="kode" placeholder="Kode" class="form-control input-medium" type="text" readonly>
                    <span class="help-block"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">No. Serial</label>
                  <div class="col-md-9">
                    <input name="serial" placeholder="" class="form-control" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Nama Aktiva</label>
                  <div class="col-md-9">
                    <input name="nama" placeholder="Nama Jenis AT" class="form-control" maxlength="30" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Jenis</label>
                  <div class="col-md-9">
                    <select name="jenis" id="jenis" class="form-control">
                      <option value="" selected disabled>--Pilih Kode--</option>
                      <?php foreach ($atjenis as $row) : ?>
                      <option value="<?php echo $row->fixid; ?>"><?php echo $row->fixid . '-' . $row->groupname; ?>
                      </option>
                      <?php endforeach; ?>
                    </select>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal Pengakuan</label>
                  <div class="col-md-4">
                    <div class="input-icon">
                      <i class="fa fa-calendar"></i>
                      <input id="tanggal" name="tanggalbeli" class="form-control" type="date" placeholder="" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal Pemakaian</label>
                  <div class="col-md-4">
                    <div class="input-icon">
                      <i class="fa fa-calendar"></i>
                      <input id="tanggalpakai" name="tanggalpakai" class="form-control" type="date" placeholder="" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3 control-label">Tanggal Kalibrasi</label>
                  <div class="col-md-4">
                    <div class="input-icon">
                      <i class="fa fa-calendar"></i>
                      <input id="tanggalkalibrasi" name="tanggalkalibrasi" class="form-control" type="date"
                        placeholder="" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Jumlah</label>
                  <div class="col-md-9">
                    <input name="jumlah" placeholder="" class="form-control" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Nilai Perolehan Awal</label>
                  <div class="col-md-9">
                    <input name="hargaperolehan" placeholder="" class="form-control" maxlength="30" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Lokasi</label>
                  <div class="col-md-9">
                    <input name="lokasi" placeholder="" class="form-control" maxlength="30" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Pemakai</label>
                  <div class="col-md-9">
                    <input name="pemakai" placeholder="" class="form-control" type="text">
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal untuk detail aktiva -->
    <div class="modal fade" id="detail-aktiva" role="dialog">
      <div class="modal-dialog w-3/4">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Rincian Aktiva Tetap</h3>
          </div>
          <div class="modal-body flex">
            <div class="w-1/2" id="left-well" style="padding: 1rem;">
              <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <tbody id="target-body"></tbody>
              </table>
              <span id="barcode-wrapper" style="width: 400px;">
                <span class="barcode-text">NAMA BARANG: <span id="bc-nama"></span></span>
                <svg id="barcode"></svg>
                <span class="barcode-text">TGL PAKAI <span id="bc-pakai"></span></span>
                <span class="barcode-text">TGL PAKAI <span id="bc-kalibrasi"></span></span>
              </span>
              <a href="" id="download-barcode" target="_blank">Download Barcode</a>
            </div>
            <div id="right-well" style="padding: 1rem;">
              <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead class="breadcrumb">
                  <tr>
                    <th style="text-align: center; vertical-align: middle;">No</th>
                    <th style="text-align: center; vertical-align: middle;">Kode Penyusutan</th>
                    <th style="text-align: center; vertical-align: middle;">Tanggal Penyusutan</th>
                    <th style="text-align: center; vertical-align: middle;">Persentase</th>
                    <th style="text-align: center; vertical-align: middle;">Nominal</th>
                    <th style="text-align: center; vertical-align: middle;">Status</th>
                  </tr>
                </thead>
                <tbody id="tabel-penyusutan"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
initailizeSelect2();
initailizeSelect2_cabang();
    </script>