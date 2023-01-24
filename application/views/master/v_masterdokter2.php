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
      <span class="title-web">Master <small>Dokter</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">

      <li>
        <i class="fa fa-home"></i>
        <a class="title-white" href="<?php echo base_url(); ?>dashboard">
          Awal
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">
          Master
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="#">
          Dokter
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
          Daftar Dokter
        </div>
        <div class="btn-group pull-right" style="margin-bottom:20px;">
          <label>Cabang : </label>
          <select class="form-control input-large select2_el_cabang_all_sess" id="cabang" name="cabang" onchange="getcabang()">
            <!-- <?php if (isset($cabang)) :
              $rs = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
            ?>
              <option value="<?= $cabang ?>" selected><?= $rs->namars ?></option>
            <?php endif; ?> -->
          </select>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="btn-group">
          </div>
          <a href="<?= site_url('Master_dokter2/v_add'); ?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Data Baru</a>
        </div>
        <table id="table_dokter" class="table table-striped table-bordered" width="100%">
          <thead class="breadcrumb">
            <tr>
              <th class="title-white" style="text-align: center">Cabang</th>
              <th class="title-white" style="text-align: center">Kode</th>
              <th class="title-white" style="text-align: center">Nama</th>
              <th class="title-white" style="text-align: center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($list as $l) : ?>
              <tr>
                <?php $cbg = $this->db->get_where("tbl_namers", ['koders' => $l->koders])->row(); ?>
                <td><?= $cbg->namars; ?></td>
                <td><?= $l->kodokter; ?></td>
                <td><?= $l->nadokter; ?></td>
                <td style="text-align: center">
                  <a type="button" title="Ubah" href="<?= site_url('Master_dokter2/v_edit/') . $l->id; ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                  <?php $cek = $this->db->query("SELECT * FROM tbl_regist WHERE kodokter = '$l->kodokter'")->num_rows();
                  if ($cek == 0) :
                  ?>
                    <button type="button" title="Hapus" class="btn btn-danger" onclick="hapus('<?= $l->kodokter; ?>', '<?= $l->nadokter; ?>')"><i class="fa fa-trash"></i></button>
                  <?php else : ?>
                    <button type="button" title="Hapus" class="btn btn-danger" disabled><i class="fa fa-trash"></i></button>
                  <?php endif ?>
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
?>

<script>
  var table;
  var table = $('#table_dokter').DataTable({
    "columnDefs": [{
      "targets": [-1],
      "orderable": false,
    }],
    "lengthMenu": [
      [5, 20, 50, -1],
      [5, 20, 50, 'semua']
    ],
    "oLanguage": {
      "sEmptyTable"   : "<div class='text-center'>Data Kosong</div>",
      "sInfoEmpty"    : "",
      "sInfoFiltered" : " - Dipilih dari _MAX_ data",
      "sSearch"       : "Pencarian Data : ",
      "sInfo"         : " Jumlah _TOTAL_ Data (_START_ - _END_)",
      "sLengthMenu"   : "_MENU_ Baris",
      "sZeroRecords"  : "<div class='text-center'>Tida ada data</div>",
      "oPaginate": {
        "sPrevious"   : "Sebelumnya",
        "sNext"       : "Berikutnya"
      }
    },
    "scrollCollapse": false,
    "paging": true,
    "responsive": true,
  });

  function getcabang() {
    var cabang = $("#cabang").val();
    location.href = '/Master_dokter2/?cabang=' + cabang;
  }

  function hapus(kodokter, nadokter) {
    swal({
      title: 'HAPUS DATA',
      html: "DOKTER <b>" + nadokter + "</b> ?",
      type: 'question',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-success',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Tidak'
    }).then(function() {
      $.ajax({
        url: "<?= site_url('Master_dokter2/delete/'); ?>" + kodokter,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if (data.status == 1) {
            swal({
              title: "HAPUS DATA DOKTER",
              html: "Berhasil dilakukan",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?= site_url('Master_dokter2') ?>";
            });
          } else {
            swal({
              title: "HAPUS DATA DOKTER",
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