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
      <span class="title-web">Master <small>Tarif</small>
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
        <a href="<?php echo base_url(); ?>Master_tarif2">
          Daftar Tarif
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="">
          Master BHP
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
          Daftar Kode Tarif
        </div>
        <div class="btn-group pull-right" style="margin-bottom: 20px;">
          <label>Cabang : </label>
          <select class="form-control input-large select2_el_cabang_all" id="cabang" name="cabang" onchange="getcabang()">
            <?php if (isset($_GET["cabang"])) :
              $rs = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$_GET[cabang]'")->row();
            ?>
              <option value="<?= $rs->koders ?>" selected><?= $rs->namars ?></option>
            <?php endif; ?>
          </select>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <a href="<?= site_url('Master_tarif2/v_add'); ?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Data Baru</a>
        </div>
        <table id="table_tarif" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          <thead class="breadcrumb header-custom">
            <tr>
              <th class="title-white" style="text-align: center">Cabang</th>
              <th class="title-white" style="text-align: center">Kode Tarif</th>
              <th class="title-white" style="text-align: center">Tindakan</th>
              <th class="title-white" style="text-align: center">Poli</th>
              <th class="title-white" style="text-align: center" width="10%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tarif as $t) : ?>
              <tr>
                <td><?= $t->koders; ?></td>
                <td><?= $t->kodetarif; ?></td>
                <td><?= $t->tindakan; ?></td>
                <td><?= $t->kodepos; ?></td>
                <td width="10%" style="text-align: center">
                  <a href="<?= site_url('Master_tarif2/v_edit/') . $t->id; ?>" type="button" class="btn btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                  <?php $cek = $this->db->query("SELECT * FROM tbl_dpoli WHERE kodetarif = '$t->kodetarif'")->num_rows(); ?>
                  <?php if ($cek > 0) : ?>
                    <button type="button" class="btn btn-danger" disabled><i class="fa fa-trash"></i></button>
                  <?php else : ?>
                    <button type="button" class="btn btn-danger" onclick="hapus('<?= $t->kodetarif; ?>', '<?= $t->tindakan; ?>')" title="Hapus"><i class="fa fa-trash"></i></button>
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
?>

<script>
  var table;
  var table = $('#table_tarif').DataTable({
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

  function getcabang() {
    var cabang = $("#cabang").val();
    location.href = '/Master_tarif2/?cabang=' + cabang;
  }

  function hapus(kodetarif, tindakan) {
    swal({
      title: 'HAPUS DATA',
      html: "Tindakan <b>" + tindakan + "</b> ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Tidak'
    }).then(function() {
      $.ajax({
        url: "<?= site_url('Master_tarif2/delete/'); ?>" + kodetarif,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "HAPUS DATA TARIF",
              html: "Berhasil dilakukan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('Master_tarif2') ?>";
            });
          } else {
            swal({
              title: "HAPUS DATA TARIF",
              html: "Gagal dilakukan",
              type: "error",
              confirmButtonText: "OK"
            });
          }
        }
      });
    })
  }
</script>