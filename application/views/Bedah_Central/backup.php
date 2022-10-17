<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>


<link href="<?php echo base_url('assets/css/font_css.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet"
    type="text/css" />
<style>
.portlet-body a {
    color: black;
    text-decoration: none
}

.keterangan h5 {
    color: green;
}

.jadwal button {
    color: black;
    background-color: #00FF00;
}
</style>
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web"><?=$menu;?> <small> <?= $title;?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    <?=$menu;?> </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>poliklinik">
                    <?=$title;?>
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
                    INSTALASI BEDAH CENTRAL
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-barcodex"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>

                            <div class="desc">
                                PERMINTAAN OPERASI
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-printx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>
                            <div class="desc">
                                Dijadwalkan Operasi
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="fa fa-shopping-cartx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <!-- diisi data sesuai db -->
                            </div>
                            <div class="desc">
                                Selesai Operasi
                            </div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <br>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                            <div class="keterangan">
                                <h5> <b>DAFTAR PERMINTAAN OPERASI</b> </h5>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="btn-group pull-right">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i
                                        class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a data-toggle="modal" href="#hperiode">Filter Data</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <br>
            <table class="table table-striped table-hover table-bordered" id="table" name="table">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center">Buat Jadwal</th>
                        <th style="text-align: center">No Reg</th>
                        <th style="text-align: center">Rekmed</th>
                        <th style="text-align: center">Nama Pasien</th>
                        <th style="text-align: center">Tanggal Rencana</th>
                        <th style="text-align: center">Asal Pasien</th>
                        <th style="text-align: center">Tanggal Daftar</th>
                        <th style="text-align: center">Nama Pasien</th>
                        <th style="text-align: center">Dokter Pemohon</th>
                        <th style="text-align: center">Dokter Operator</th>
                        <th style="text-align: center">Jenis Pasien</th>
                        <th style="text-align: center">Tindakan</th>
                        <th style="text-align: center">Notes</th>
                    </tr>
                </thead>
                <tbody>


                </tbody>
                <tfoot>

                    <!-- <td colspan="7" style="text-align:right">Total:</td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td style="text-align:right"></td> -->
                    <!-- <td colspan="1"></td> -->


                </tfoot>

            </table>
            <div class="col-md-12">
                <div class="row">
                    <div class="pull-left">
                        <div class="keterangan">
                            <h5> <b>STATUS DAN JADWAL OPERASI</b> </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="jadwal">
                    <button type="button">
                        <b>Buat Jadwal</b>
                    </button>
                </div>
                <div class="btn-group pull-right">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a data-toggle="modal" href="#hperiode">Filter Data</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <table class="table table-striped table-hover table-bordered" id="tbl" name="table">
            <thead class="breadcrumb">
                <tr>
                    <th style="text-align: center">Task</th>
                    <th style="text-align: center">Billing</th>
                    <th style="text-align: center">No order</th>
                    <th style="text-align: center">Status Operasi</th>
                    <th style="text-align: center">No reg</th>
                    <th style="text-align: center">Rekmed</th>
                    <th style="text-align: center">Nama Pasien</th>
                    <th style="text-align: center">Tanggal Operasi</th>
                    <th style="text-align: center">Tindakan</th>
                    <th style="text-align: center">Asal Pasien</th>
                    <th style="text-align: center">Dokter Operator</th>
                    <th style="text-align: center">Jenis Pasien</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
            <tfoot>

                <!-- <td colspan="7" style="text-align:right">Total:</td> -->
                <!-- <td style="text-align:right"></td> -->
                <!-- <td style="text-align:right"></td> -->
                <!-- <td colspan="1"></td> -->


            </tfoot>

        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
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
                                <input id="tglmasuk" name="tglmasuk" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Sampai Tanggal:</label>
                            <div class="col-md-6">
                                <input id="tglakhir" name="tglakhir" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Kode Poli :</label>
                            <div class="col-md-6">
                                <select style='color:black;' id="kodepos" name="kodepos" class="selectpicker"
                                    data-live-search="true" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)">
                                    <option value=''>-- Pilih Data --</option>
                                    <?php
                                    foreach($listKodePoli as $key){
                                        echo "<option>$key->kodepos</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nama Dokter :</label>
                            <div class="col-md-6">

                                <select style='color:black;' id="nadokter" name="nadokter" class="selectpicker"
                                    data-live-search="true" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)">
                                    <option value=''>-- Pilih Data --</option>
                                    <?php
                                    foreach($naDokter as $key){
                                        echo "<option>$key->nadokter</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <p align="center">
                        <button type="button" id="btnfilter" class="btn green" onclick="filterdata()"
                            data-dismiss="modal">Buka Data</button>
                    </p>
                </div>
        </div>
    </div>
</div>

<?php
  $this->load->view('template/footero');
//   $this->load->view('template/footer');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>

<style>
.bootstrap-select .dropdown-toggle:focus {
    outline: none;
    outline: none;
    outline-offset: none;
}
</style>

<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>"
    type="text/javascript"></script>


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css"> -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-select2.min.css')?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<script>
function currencyFormat(num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$(document).ready(function() {

    //datatables
    table = $('#tbl').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_user/ajax_list')?>",
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


            var oTable = $('#table').dataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
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
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],

                "columnDefs": [{
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                    },


                    {
                        "targets": [8], //last column
                        "orderable": true, //set not orderable

                        "className": "text-right",
                        render: function(data, type, row) {
                            return '<b>' + display(row[8]) + '</b>';

                        }
                    },


                ],
                "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {

                    var iTotal = 0;
                    var iTotal1 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][7] * 1;
                        iTotal1 += aaData[i][8] * 1;
                    }


                    var iTot = 0;
                    var iTot1 = 0;
                    for (var i = iStart; i < iEnd; i++) {

                        var x = aaData[aiDisplay[i]][7];
                        var y = Number(x.replace(/[^0-9\.]+/g, ""));
                        iTot += y;

                        var x1 = aaData[aiDisplay[i]][8];
                        var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        iTot1 += y2;
                    }

                    // var nCells = nRow.getElementsByTagName('td');
                    // nCells[1].innerHTML = currencyFormat(iTot);
                    // nCells[2].innerHTML = currencyFormat(iTot1);
                }
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
        $('.modal-title').text('PEMBELIAN');

        var param = this.id;

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>hutang/cetak/' + param +
            '" frameborder="no" width="100%" height="420"></iframe>');
    });
});

function filterdata() {
    var tglmasuk = document.getElementById("tglmasuk").value;
    var kodepos = document.getElementById("kodepos").value;
    var nadokter = document.getElementById("nadokter").value;
    var tglakhir = document.getElementById("tglakhir").value;
    var str = '?tglmasuk=' + tglmasuk + '&tglakhir=' +
        tglakhir + '&kodepos=' + kodepos + '&nadokter=' + nadokter;
    location.href = "<?php echo base_url();?>Poliklinik/filter" + str;
}


jQuery(document).ready(function() {
    TableEditable.init();
    //    ComponentsPickers.init();

});
</script>