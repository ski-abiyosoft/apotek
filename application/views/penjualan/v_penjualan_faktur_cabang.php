<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>


<link href="<?php echo base_url('css/font_css.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css') ?>" rel="stylesheet"
  type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css') ?>" rel="stylesheet"
  type="text/css" />

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
            <b><?php
                            echo $periode; ?></b>
          </span>
        </div>

      </div>
      <div class="portlet-body">
        <div class="table-toolbar">


          <div class="btn-group">
            <?php if ($akses->uadd) { ?>
              <?php 
              $cek =  $this->session->userdata('user_level'); 
              if($cek==0){?> 
              <?php }else{ ?>

                <a id="tambah" href="<?php echo base_url() ?>penjualan_cabang/entri" class="btn btn-success">
                  <i class="fa fa-plus"></i>
                  Data Baru
                </a>

              <?php } ?>
            
            <?php } ?>
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
        <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
          <thead>
            <tr class="page-breadcrumb breadcrumb">
              <th class="title-white" style="text-align: center">Cabang</th>
              <th class="title-white" style="text-align: center">No. Faktur</th>
              <th class="title-white" style="text-align: center">Cabang Tujuan</th>
              <th class="title-white" style="text-align: center">Tanggal</th>
              <th class="title-white" style="text-align: center">Jumlah Rp</th>
              <th class="title-white" style="text-align: center">No. Kwitansi</th>
              <th class="title-white" style="text-align: center">Status</th>
              <th class="title-white" style="text-align: center" colspan="4">Aksi</th>
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

              <td style="text-align: center"><?php
                                                                if ($row->keluar == '0') { ?>
                <span class="label label-sm label-warning">
                  Belum Lunas
                </span>
                <?php
                                                                } else
                                                 if ($row->keluar == '1') { ?>
                <span class="label label-sm label-success">
                  Lunas
                </span>

                <?php
                                                                } ?>

              </td>

              <td style="text-align: center">
                <?php
                                    if ($row->keluar == '0') { ?>


                <a href="<?php echo base_url() ?>penjualan_cabang/edit/<?php echo $row->resepno; ?>"><button
                    class="btn btn-primary" type="button"><i class="fa fa-edit"></i></button></a>
              </td>
              <?php } ?>
              </td>
              <td style="text-align: center">
                <?php if ($row->keluar == '0') : ?>
                <a target="_blank" type="button" class="btn btn-warning"
                  href="<?php echo base_url() ?>penjualan_cabang/cetakcab/?id=<?php echo $row->resepno; ?>"><i
                    title="FAKTUR" class="fa fa-print"></i></a>
                <?php endif; ?>
              </td>
              <td style="text-align: center">
                <?php if ($row->keluar == '0') : ?>
                <a target="_blank" type="button" class="btn btn-info"
                  href="<?php echo base_url() ?>penjualan_cabang/cetakjalan/?id=<?php echo $row->resepno; ?>"><i
                    title="SURAT JALAN" class="fa fa-print"></i></a>
                <?php endif; ?>
              </td>
              <td style="text-align: center">
                <?php
                                if ($row->keluar == '0') { ?>
                <a class="delete" onclick="Batalkan('<?= $row->resepno; ?>')"><button class="btn btn-danger"
                    type="button"><i title="HAPUS" class="fa fa-trash"></i></button></a>
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
</div>
</div>
</div>

<?php
$this->load->view('template/footero');
$this->load->view('template/v_report');
$this->load->view('template/v_periode');
?>


<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>"
  type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>"
  type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js') ?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>"
  type="text/javascript"></script>

<script>
  close_app();
  function close_app() 
  {
      $.ajax({
          url         : '<?php echo base_url(); ?>lock_so/close_app',
          type        : "POST",
          dataType    : "json",
          success:function(data){
                  if (data == 1) {
                      $('#tambah').attr('disabled',true);
                  }else{
                      $('#tambah').attr('disabled',false);
                  }
              }                                     
      });
          
  }  
function Batalkan(id) {
  // alert(id);
  // return;
  swal({
    //title: 'PENDAFTARAN',
    text: "Pembatalan ",
    type: 'info',
    //input: 'text',
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
    // dismiss can be 'cancel', 'overlay',
    // 'close', and 'timer'
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
          swal(
            '',
            'Data sudah dihapus...',
            ''
          )
        } else {
          swal(
            '',
            'Data gagal dihapus.',
            ''
          )
        }
        //dataTable.ajax.reload(null,false);
        reload_table();
      }
    });
  });
}


function currencyFormat(num) {
  //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
  return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

var TableEditable = function() {

  return {

    //main function to initiate the module
    init: function() {
      function restoreRow(oTable, nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);

        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
          oTable.fnUpdate(aData[i], nRow, i, false);
        }

        oTable.fnDraw();
      }


      var oTable = $('#keuangan-keluar-list').dataTable({
        "aLengthMenu": [
          [5, 15, 20, -1],
          [5, 15, 20, "Semua"] // change per page values here
        ],
        // set the initial value
        "iDisplayLength": 5,

        "sPaginationType": "bootstrap",
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
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
          },

        ]
      });

      jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass(
        "form-control input-medium input-inline"); // modify table search input
      jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass(
        "form-control input-small"); // modify table per page dropdown
      jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
        showSearchInput: false //hide search box with special css class
      }); // initialize select2 dropdown

      var nEditing = null;

      $('#keuangan-keluar-list_new').click(function(e) {
        e.preventDefault();
        var aiNew = oTable.fnAddData(['', '', '', '', '',
          '<a class="edit" href="">Edit</a>',
          '<a class="cancel" data-mode="new" href="">Batal</a>'
        ]);
        var nRow = oTable.fnGetNodes(aiNew[0]);
        editRow(oTable, nRow);
        nEditing = nRow;
      });

      function deleteRow(oTable, nRow) {
        if (confirm("Hapus Data Ini?")) {

          var row_id = nRow.id;
          var mydata = row_id.substring(4, 30);

          $.ajax({
            dataType: 'html',
            type: "POST",
            url: "<?php echo base_url(); ?>penjualan_cabang/hapus/" + mydata,
            cache: false,
            data: mydata,
            success: function() {
              oTable.fnDeleteRow(nRow);
              oTable.fnDraw();
            },
            error: function() {},
            complete: function() {}
          });

        }
      }

      $('#keuangan-keluar-list a.delete').live('click', function(e) {
        e.preventDefault();

        var nRow = $(this).parents('tr')[0];
        if (nRow) {
          deleteRow(oTable, nRow);
          nEditing = null;
        }

      });


      $('#keuangan-keluar-list a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
          var nRow = $(this).parents('tr')[0];
          oTable.fnDeleteRow(nRow);
        } else {
          restoreRow(oTable, nEditing);
          nEditing = null;
        }
      });

      $('#keuangan-keluar-list a.edit').live('click', function(e) {
        e.preventDefault();

        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];

        if (nEditing !== null && nEditing != nRow) {
          /* Currently editing - but not this row - restore the old before continuing to edit mode */
          restoreRow(oTable, nEditing);
          editRow(oTable, nRow);
          nEditing = nRow;
        } else if (nEditing == nRow && this.innerHTML == "Simpan") {
          /* Editing this row and want to save it */
          saveRow(oTable, nEditing);
          nEditing = null;
        } else {
          /* No edit in progress - let's start one */
          editRow(oTable, nRow);
          nEditing = nRow;
        }
      });
    }

  };

}();

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


jQuery(document).ready(function() {
  TableEditable.init();
  ComponentsPickers.init();

});
</script>