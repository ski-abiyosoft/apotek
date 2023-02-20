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
      <span class="title-web">Master <small>Aturan Pakai</small>
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
        <a href="<?php echo base_url(); ?>Master_ap">
          Daftar Aturan Pakai
        </a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <a href="">
          Master Aturan Pakai
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
          Daftar Aturan Pakai
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <button type="button" class="btn btn-success" onclick="tambah()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
        </div>
        <table id="table_ap" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          <thead class="breadcrumb header-custom">
            <tr>
              <th class="title-white" style="text-align: center" width="5%">No</th>
              <th class="title-white" style="text-align: center">Kode Aturan Pakai</th>
              <th class="title-white" style="text-align: center">Aturan Pakai</th>
              <th class="title-white" style="text-align: center" width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($atp as $key => $value) : ?>
              <tr>
                <td class="text-right"><?= $no++; ?></td>
                <td><?= $value->apocode; ?></td>
                <td><?= $value->aponame; ?></td>
                <td class="text-center">
                  <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i></button>
                  <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Aturan Pakai</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="apocode" placeholder="apocode" class="form-control" type="text" readonly value="<?= $kode; ?>">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Aturan Pakai</label>
                            <div class="col-md-9">
                                <input name="aponame" id="aponame" placeholder="Aturan Pakai" class="form-control" maxlength="100" type="text">
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
        </div>
    </div>
</div>

<?php
$this->load->view('template/footer_tb');
?>

<script>
  var table = $('#table_ap').DataTable({
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

<script>
  function tambah() {
    $("#modal_form").modal("show");
  }

  function save() {
    $("#modal_form").modal("hide");
    var aponame = $("#aponame").val().replaceAll('%', ' ');
    if(aponame == '') {
      swal({
        title: "ATURAN PAKAI",
        html: "Tidak boleh kosong",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $("#modal_form").modal("show");
      });
    } else {
      $.ajax({
        url: "<?= site_url('Master_ap/cek_nama/'); ?>"+aponame,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          console.log(data)
        }
      });
    }
  }
</script>