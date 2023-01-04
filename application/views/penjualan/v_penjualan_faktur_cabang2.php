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
      <span class="title-web">Farmasi <small>Penjualan Ke Cabang</small>
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
          Farmasi
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?php echo base_url(); ?>penjualan_cabang">
          Data Penjualan Ke Cabang
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
          Daftar Faktur Penjualan -
          <span>
            <b><?php echo $periode; ?></b>
          </span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="btn-group">
            <?php if ($akses->uadd) { ?>
              <a href="<?php echo base_url() ?>penjualan_cabang/entri" class="btn btn-success">
                <i class="fa fa-plus"></i>
                Data Baru
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
        <table id="table_cabang" class="table table-striped table-hover table-bordered">
          <thead>
            <tr class="page-breadcrumb breadcrumb">
              <th class="title-white" style="text-align: center">Cabang</th>
              <th class="title-white" style="text-align: center">No. Faktur</th>
              <th class="title-white" style="text-align: center">Cabang Tujuan</th>
              <th class="title-white" style="text-align: center">Tanggal</th>
              <th class="title-white" style="text-align: center">Jumlah Rp</th>
              <th class="title-white" style="text-align: center">No. Kwitansi</th>
              <th class="title-white" style="text-align: center">Status</th>
              <th class="title-white" style="text-align: center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $nomor = 1;
            foreach ($keu as $row) {
            ?>
              <tr class="show1" id="row_<?php echo $row->resepno; ?>">
                <td align="center"><?php echo $row->koders; ?></td>
                <td align="center"><?php echo $row->resepno; ?></td>
                <td align="center"><?php echo $row->rekmed; ?></td>
                <td align="center"><?php echo date('d-m-Y', strtotime($row->tglresep)); ?></td>
                <td align="right"><?php echo number_format($row->poscredit, 0, ',', '.'); ?></td>
                <td><?php echo $row->nokwitansi; ?></td>
                <td style="text-align: center">
                  <?php if ($row->keluar == '0') { ?>
                    <span class="label label-sm label-warning">
                      Belum Lunas
                    </span>
                  <?php } else if ($row->keluar == '1') { ?>
                    <span class="label label-sm label-success">
                      Lunas
                    </span>
                  <?php } ?>
                </td>
                <td style="text-align: center" width="20%">
                  <?php if ($row->keluar == '0') { ?>
                    <?php
                    $resep = $this->db->get_where("tbl_apohresep", ["resepno" => $row->resepno])->row();
                    $pono = $resep->pono;
                    $cek = $this->db->get_where("tbl_apodterimalog", ["po_no" => $pono]);
                    if ($cek->num_rows() > 0) {
                      $ceknya = $cek;
                    } else {
                      $ceknya = $this->db->get_where("tbl_barangdterima", ["po_no" => $pono]);
                    }
                    if ($ceknya->num_rows() < 1) :
                    ?>
                      <a href="<?php echo base_url() ?>penjualan_cabang/edit/<?php echo $row->resepno; ?>"><button class="btn btn-primary" type="button"><i class="fa fa-edit"></i></button></a>
                      <a class="delete" onclick="Batalkan('<?= $row->resepno; ?>')"><button class="btn btn-danger" type="button"><i title="HAPUS" class="fa fa-trash"></i></button></a>
                    <?php endif; ?>
                      <a target="_blank" type="button" class="btn btn-warning" href="<?php echo base_url() ?>penjualan_cabang/cetakcab/?id=<?php echo $row->resepno; ?>"><i title="FAKTUR" class="fa fa-print"></i></a>
                      <input type="hidden" id="resepnop" value="<?= $row->resepno; ?>">
                      <button class="btn btn-info" type="button" onclick="cekctk('<?= $row->resepno; ?>');" title="SURAT JALAN">
                        <i class="glyphicon glyphicon-print"></i>
                      </button>
                  <?php } ?>
                </td>
              </tr>
            <?php
              $nomor++;
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title"><b>TAMPILKAN DI CETAKAN ?</b></h5>
      </div>
      <div class="modal-body form">
        <div class="container-fluid">
          <form action="#" id="form" class="form-horizontal">
            <input type="hidden" name="resepnox" id="resepnox" />
            <div class="form-body">
              <div class="form-group">
                <label class="control-label col-md-4">STOK : </label>
                <div class="col-md-8">
                  <select class="form-control" id="cekstok" name="cekstok">
                    <option value='1'>Ya</option>
                    <option value='2'>Tidak</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-4">HARGA : </label>
                <div class="col-md-8">
                  <select class="form-control" id="cekharga" name="cekharga">
                    <option value='1'>Ya</option>
                    <option value='2'>Tidak</option>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSave" onclick="_urlcetak()" class="btn btn-primary">CETAK</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
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
  var table = $('#table_cabang').DataTable({
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
  function cekctk(param) {
    var resepno = $("#resepnop").val();
    $('#resepnox').val(param);
    $('#modal_form').modal('show');
  }

  function _urlcetak() {
    var cekstokk = document.getElementById('cekstok').value;
    var cekhargaa = document.getElementById('cekharga').value;
    var baseurl = "<?php echo base_url() ?>";
    var resepno = $('[name=resepnox]').val();
    url = baseurl + 'penjualan_cabang/cetakjalan/?id=' + resepno + '&stock=' + cekstokk + '&harga=' + cekhargaa
    window.open(url, '');
    $('#modal_form').modal('hide');
  }

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
        url: '<?php echo base_url() ?>Penjualan_cabang/hapus/' + id,
        success: function(response) {
          if (response.status == 1) {
            swal({
              title: "Penghapusan",
              html: "<p> Nomor Retur   : <b>" + response.nomor + "</b> </p>" +
                "BERHASIL",
              type: "success",
              confirmButtonText: "OK"
            }).then((value) => {
              location.href = "<?php echo base_url() ?>Penjualan_cabang";
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


  function delete_data(id, nomor) {
    swal({
      title: 'BAPB',
      html: "Data ini akan dihapus ? <strong><p>" + nomor,
      type: 'warning',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger m-l-10',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(function() {
      $.ajax({
        type: 'POST',
        dataType: "json",
        url: '<?php echo base_url('farmasi_bapb') ?>/ajax_delete?terima_no=' + nomor,
        data: {
          id: id
        },
        success: function(response) {

          if (response.status == '1') {
            swal('', 'Data sudah dihapus...', '')
          } else {
            swal('', 'Data gagal dihapus.', '')
          }
          reload_table();
        }
      });
    });
  }

  $(document).ready(function() {
    $('.print_laporan').on("click", function() {
      $('.modal-title').text('penjualan');
      var param = this.id;
      $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>penjualan_faktur/cetak/' + param +
        '" frameborder="no" width="100%" height="420"></iframe>');
    });
  });

  function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var str = '2~' + tgl1 + '~' + tgl2;
    location.href = "<?php echo base_url(); ?>penjualan_cabang/filter/" + str;
  }
</script>