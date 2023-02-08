<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
?>

<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?= $this->session->userdata('unit'); ?>
      </span>
      -
      <span class="title-web">APOTEK <small>Retur Pembelian</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?= base_url(); ?>dashboard">
          Awal
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="">
          Pembelian
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= base_url(); ?>Pembelian_retur">
          Data Retur
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
          Daftar Retur Pembelian -
          <span>
            <b><?= $periode; ?> </b>
          </span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="btn-group">
            <?php if ($akses->uadd) : ?>
              <a href="<?= base_url() ?>pembelian_retur/entri" class="btn btn-success">
                <i class="fa fa-plus"></i>
                Transaksi Baru
              </a>
            <?php endif ?>
          </div>

          <div class="btn-group pull-right">
            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right">
              <li>
                <a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>
              </li>
            </ul>
          </div>
        </div>
        <table class="table table-striped table-hover table-bordered" id="datatable">
          <thead>
            <tr class="breadcrumb header-custom">
              <th class="title-white" style="text-align: center">No</th>
              <th class="title-white" style="text-align: center">No. Retur</th>
              <th class="title-white" style="text-align: center">No. BAPB</th>
              <th class="title-white" style="text-align: center">Tanggal</th>
              <th class="title-white" style="text-align: center">Vendor</th>
              <th class="title-white" style="text-align: center">Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode');
?>

<script>
  var table;
  table = $('#datatable').DataTable({
    destroy: true,
    "processing": true,
    "responsive": true,
    "serverSide": true,
    "order": [],
    "ajax": {
      "url": "<?php echo site_url('Pembelian_retur/data_list/1') ?>",
      "type": "POST"
    },
    "scrollCollapse": false,
    "paging": true,
    "oLanguage": {
      "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
      "sInfoEmpty": "",
      "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
      "sSearch": "Pencarian Data:",
      "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
      "sLengthMenu": "_MENU_ Baris",
      "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
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
      "targets": [-1],
      "orderable": false,
    }, ],
  });

  function Batalkan(id) {
    swal({
      text: "Pembatalan ",
      type: 'info',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger m-l-10',
      confirmButtonText: 'Ya, Batalkan',
      cancelButtonText: 'Tidak',
    }).then(function(alasan) {
      $.ajax({
        type: 'POST',
        dataType: "json",
        data: {
          alasan: alasan
        },
        url: '<?php echo base_url() ?>Pembelian_retur/hapus/' + id,
        success: function(response) {
          if (response.status == 1) {
            swal(
              'Updated!',
              'Pembatalan berhasil.',
              'success'
            )
            swal({
              title: "Penghapusan",
              html: "<p> Nomor Retur   : <b>" + response.nomor + "</b> </p>" +
                "BERHASIL",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>pembelian_retur";
            });
          } else {
            swal(
              'Failed!',
              'Pembatalan gagal',
              'failed'
            )
          }
          reload_table();
        }
      });
    }, function(dismiss) {
      if (dismiss === 'cancel') {
        return;
      }
    });
  }

  function reload_table() {
    table.ajax.reload(null, false);
  }

  function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var id = 2;
    var str = id + '~' + tgl1 + '~' + tgl2;
    table.ajax.url("<?php echo base_url('Pembelian_retur/data_list/') ?>" + str).load();
  }
</script>