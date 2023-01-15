<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
  $session   = $this->session->flashdata("session");
  if (isset($session)) {
    echo "<script>if(confirm('$session')){ window.open('','_self').close(); } else { window.open('','_self').close(); }</script>";
  }

?>

<!-- header -->
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span> 
      &nbsp;-
      <span class="title-web">e-HMS <small>IGD</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?= site_url('home'); ?>">
          Awal
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= site_url('Igd'); ?>">
          Instalasi Gawat Darurat
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- body -->
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">
          Daftar Pasien IGD - <span><b><?= $periode; ?></b></span>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat green">
            <div class="visual">
              <i class="fa fa-barcodex"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php foreach ($total_pasien as $total_pasienn) : ?>
                  <?= $total_pasienn->jum; ?>
                <?php endforeach; ?>
              </div>
              <div class="desc">
                TOTAL PASIEN IGD HARI INI
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat red">
            <div class="visual">
              <i class="fa fa-printx"></i>
            </div>
            <div class="details">
              <div class="number">
                <?= $diperiksa_perawat == 0 ? "0" : $diperiksa_perawat ?>
              </div>
              <div class="desc">
                PEMERIKSAAN TRIASE
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat blue">
            <div class="visual">
              <i class="fa fa-shopping-cartx"></i>
            </div>
            <div class="details">
              <div class="number">
                <?= $diperiksa_dokter == 0 ? "0" : $diperiksa_dokter ?>
              </div>
              <div class="desc">
                PERENCANAAN RAWAT
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat yellow">
            <div class="visual">
              <i class="fa fa-shopping-cartx"></i>
            </div>
            <div class="details">
              <div class="number">
                0
              </div>
              <div class="desc">
                RATA RATA WAKTU TUNGGU
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <table border="0" class="table" width="100%">
        <div class="portlet-body">
          <div class="table-toolbar">
            <tr>
              <td>
                <a href="<?= site_url('Igd/buat_triase'); ?>" type="button" class="btn green" style="margin-top: 23.5px; margin-left: -8px;"><i class="fa fa-plus"></i> Buat Triage</a>
              </td>
              <td class="">
                <div class="btn-group pull-right">
                  <button style="margin-top: 23.5px;" class="btn btn-default" onclick="reload_table()">
                    <i name="refresh" id="refresh" class="glyphicon glyphicon-refresh"></i> Refresh
                  </button>
                  <button style="margin-top: 23.5px;" class="btn dropdown-toggle" data-toggle="dropdown">
                    Data <i class="fa fa-angle-down"></i>
                  </button>
                  <ul style="margin-top: 23.5px;" class="dropdown-menu">
                    <li>
                      <a data-toggle="modal" href="#hperiode">Filter Data</a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </div>
        </div>
      </table>
      <div class="portlet-body">
        <div class="table-responsive">
          <table id="table_igd" class="table table-striped table-bordered table-hover" width="100%">
            <thead class="page-breadcrumb breadcrumb">
              <tr>
                <th class="title-white" style="text-align: center" width="7%">E-MR</th>
                <th class="title-white" style="text-align: center">No REG</th>
                <th class="title-white" style="text-align: center">No RM</th>
                <th class="title-white" style="text-align: center">Tanggal Daftar</th>
                <th class="title-white" style="text-align: center">Nama Pasien</th>
                <th class="title-white" style="text-align: center">Dokter</th>
                <th class="title-white" style="text-align: center">Pembayaran</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal filter data -->
<div class="modal fade" id="hperiode" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-small">
    <div class="modal-content">
      <span id="nopilih">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
          <h4 class="modal-title">Data</h4>
        </div>
        <div class="modal-body">
          <form action="#" class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 control-label">Tanggal Masuk:</label>
              <div class="col-md-6">
                <input id="tglmasuk" name="tglmasuk" class="form-control input-medium" type="date" value="<?= date('Y-m-d'); ?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-4 control-label">Sampai Tanggal:</label>
              <div class="col-md-6">
                <input id="tglakhir" name="tglakhir" class="form-control input-medium" type="date" value="<?= date('Y-m-d'); ?>" />
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <p align="center">
            <button type="button" id="btnfilter" class="btn green" onclick="filterdata()" data-dismiss="modal">Buka Data</button>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal Emr -->
<div class="modal fade " id="modal-detail" role="dialog">
  <div class="container">
    <div class="modal-dialog modal-sm modal-center">
      <div class="modal-content">
        <div class="modal-header">
          <div class="h3" style="text-align: center;">DATA PASIEN</div>
        </div>
        <div class="modal-body" class="btn-group">
          <table width="100%">
            <tr align="center">
              <td width="100%">
                <input readonly type="text" id="nampasdet" name="nampasdet" class="form-control input-medium" style="width: 100%;">
              </td>
            </tr>
            <tr align="center">
              <td colspan="3" width="100%">&nbsp;</td>
            </tr>
            <tr align="center">
              <td width="100%">
                <input readonly type="text" id="noregdet" name="noregdet" class="form-control input-medium" style="width: 100%;">
              </td>
            </tr>
            <tr align="center">
              <td colspan="3" width="100%">&nbsp;</td>
            </tr>
            <tr align="center">
              <td width="100%">
                <input readonly type="text" id="rekmeddet" name="rekmeddet" class="form-control input-medium" style="width: 100%;">
              </td>
            </tr>
            <tr align="center">
              <td colspan="3" width="100%"><hr></td>
            </tr>
            <tr align="center">
              <td width="100%" style="padding: 10px;">
                <button type="button" class="btn green" onclick="cek_per();" style="width: 100%;">
                  <i class="fa fa-solid fa-check"></i> <b>Pemeriksaan Perawat</b>
                </button>
                <br>
                <br>
                <button type="button" class="btn green" onclick="cek_dok();" style="width: 100%;">
                  <i class="fa fa-solid fa-check"></i> <b>Pemeriksaan Dokter</b>
                </button>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal loading -->
<div class="modal fade" id="loading" data-toggle="modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog vertical-align-center2" style="margin-top: 350px;">
  <table align="center">
    <tr>
      <td>Loading...</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr align="center">
      <td>
        <img id="search1" height="50px" width="50px" src="<?php echo base_url(); ?>assets/img/loadinghar2.gif" />
      </td>
    </tr>
  </table>
</div>

</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_periode');
?>

<!-- master js -->
<script>
  var save_method;
  var table_igd;

  // reload datatable
  function reload_table() {
    table_igd.ajax.reload(null,false);
  }
</script>

<!-- body datatable -->
<script>
  $(document).ready(function() {
    var display = $.fn.dataTable.render.number('.', ',', 2, ' ').display;
    table_igd = $('#table_igd').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= site_url('Igd/ajax_list/1') ?>",
        "type": "POST"
      },
      "scrollCollapse": false,
      "paging": true,
      "oLanguage": {
        "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
        "sInfoEmpty": "",
        "sInfoFiltered": " - Dipilih dari _END_ data",
        "sSearch": "Pencarian Data : ",
        "sInfo": "Data (_START_ - _END_)",
        "sLengthMenu": "_MENU_ Baris",
        "sLoadingRecords": "Loading...",
        "sProcessing": "Tunggu Sebentar... Loading...",
        "sZeroRecords": "Tida ada data",
        "oPaginate": {
          "sPrevious": "Sebelumnya",
          "sNext": "Berikutnya"
        }
      },
      "aLengthMenu": [
        [5, 15, 20, -1],
        [5, 15, 20, "Semua"]
      ],
      "columnDefs": [{
        "orderable": true,
        "className": "text-right",
        render: function(data, type, row) {
        }
      }],
    });

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

  // filter
  function filterdata(){
    var tgl1        = document.getElementById("tglmasuk").value;
    var tgl2        = document.getElementById("tglakhir").value;
    var id          = 2;
    var str         = id+'~'+tgl1+'~'+tgl2;
    table_igd.ajax.url("<?= base_url('Igd/ajax_list/')?>"+str).load();
  }
</script>

<!-- emr js -->
<script>
  // klik emr card
  function add_list(id, rekmed) {
    var select = id;
    $('#loading').modal('show');
    $.ajax({
      url: "<?= site_url('Igd/get_detail');?>",
      type: "POST",
      dataType: "JSON",
      data: {ceknoreg:select, cekrekmed:rekmed},
      success: function(data) {
        var namapase = data.namapas;   
        var norege = data.noreg;   
        var rekmede = data.rekmed;   
        $('#nampasdet').val(namapase);
        $('#noregdet').val(norege);
        $('#rekmeddet').val(rekmede);
        $('#loading').modal('hide');
      }
    });
    $('#modal-detail').modal('show');
  }

  // klik pemeriksaan perawat
  function cek_per() {
    var nampasdet = document.getElementById("nampasdet").value;
    var noregdet  = document.getElementById("noregdet").value;
    var rekmeddet = document.getElementById("rekmeddet").value;
    url = "<?= site_url('Igd/pemeriksaan_perawat/?noreg='); ?>" + noregdet + "&rekmed=" + rekmeddet;
    window.open(url, '_blank');
    window.focus();
  }

  // klik pemeriksaan dokter
  function cek_dok() {
    var nampasdet = document.getElementById("nampasdet").value;
    var noregdet = document.getElementById("noregdet").value;
    var rekmeddet = document.getElementById("rekmeddet").value;
    url = "<?= site_url('Igd/pemeriksaan_dokter/?noreg='); ?>" + noregdet + "&rekmed=" + rekmeddet;
    window.open(url, '_blank');
    window.focus();
  }
</script>