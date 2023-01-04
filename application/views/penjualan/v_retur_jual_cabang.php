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
      <span class="title-web">Logistik <small>Retur Penjualan Cabang</small>
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
        <a class="title-white" href="">
          Penjualan
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?php echo base_url(); ?>Retur_jual_cabang">
          Data Retur Cabang
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
          Daftar Retur Penjualan Cabang -
          <span>
            <b><?= $periode; ?></b>
          </span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="btn-group">
            <?php if ($akses->uadd) { ?>
              <a href="<?php echo base_url() ?>Retur_jual_cabang/entri" class="btn btn-success">
                <i class="fa fa-plus"></i>
                <b>Transaksi Baru</b>
              </a>
            <?php } ?>
          </div>
          <div class="btn-group pull-right">
            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
            <ul class="dropdown-menu pull-right">
              <li>
                <a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>
              </li>
            </ul>
          </div>
        </div>
        <table class="table table-striped table-hover table-bordered" id="retur_cabang_table">
          <thead>
            <tr class="page-breadcrumb breadcrumb">
              <th class="title-white" style="text-align: center">Cab.</th>
              <th class="title-white" style="text-align: center" width="150">User ID</th>
              <th class="title-white" style="text-align: center">No. Retur</th>
              <th class="title-white" style="text-align: center">No. Resep</th>
              <th class="title-white" style="text-align: center">Rekmed</th>
              <th class="title-white" style="text-align: center">Nama Pasien</th>
              <th class="title-white" style="text-align: center">Jumlah Rp</th>
              <th class="title-white" style="text-align: center">Tanggal</th>
              <th class="title-white" style="text-align: center">Gudang</th>
              <th class="title-white" style="text-align: center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $nomor = 1;
            foreach ($keu as $row) : ?>
              <tr class="show1" id="row_<?php echo $row->returno; ?>">
                <td align="center"><?php echo $row->koders; ?></td>
                <td align="center"><?php echo $row->username; ?></td>
                <td align="center"><?php echo $row->returno; ?></td>
                <td align="center"><?php echo $row->resepno; ?></td>
                <td align="center"><?php echo $row->rekmed; ?></td>
                <td align="center"><?php echo $row->namapas; ?></td>
                <td>Rp. <span align="right"><?php echo number_format($row->totalrp); ?></span></td>
                <td align="center"><?php echo date('d-m-Y', strtotime($row->tglretur)); ?></td>
                <td><?php echo $row->gudang; ?></td>
                <td style="text-align: center">
                  <?php if ($row->posting == '0') : ?>
                    <a type="button" class="btn btn-sm btn-primary" href="<?php echo base_url('Retur_jual_cabang/edit/') . $row->returno; ?>" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                    <a target="_blank" type="button" class="btn btn-sm btn-warning" href="<?php echo base_url() ?>Retur_jual_cabang/cetak/?id=<?php echo $row->returno; ?>"><i class="glyphicon glyphicon-print"></i></a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $row->returno; ?>', '<?= $row->resepno; ?>')"><i class="glyphicon glyphicon-trash"></i></button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
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
  function hapus(returno, resepno) {
    swal({
      title: 'HAPUS DATA',
      html: 'Yakin data dengan no retur : ' + returno,
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Tidak'
    }).then(function() {
      $.ajax({
        url: "<?php echo site_url('Retur_jual_cabang/hapus/') ?>" + returno + '/' + resepno,
        type: 'POST',
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "HAPUS DATA",
              html: "Berhasil dilakukan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('Retur_jual_cabang') ?>";
            });
          } else {
            swal({
              title: "HAPUS DATA",
              html: "Gagal dilakukan",
              type: "error",
              confirmButtonText: "OK"
            });
          }
        }
      });
    });
  }
  var table;
  var table = $('#retur_cabang_table').DataTable({
    "columnDefs": [{
      "targets": [-1],
      "orderable": false,
    }],
    "lengthMenu": [
      [5, 20, 50, -1],
      [5, 20, 50, 'semua']
    ],
    "oLanguage": {
      "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
      "sInfoEmpty": "",
      "sInfoFiltered": " - Dipilih dari _MAX_ data",
      "sSearch": "Pencarian Data : ",
      "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
      "sLengthMenu": "_MENU_ Baris",
      "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
      "oPaginate": {
        "sPrevious": "Sebelumnya",
        "sNext": "Berikutnya"
      }
    },
    "scrollCollapse": false,
    "paging": true,
    "responsive": true,
  });
</script>