<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<style>
    div.tableContainer {
        clear: both;
        border: 1px solid #963;
        height: 285px;
        overflow: auto;
        width: 100%
    }

    html>body div.tableContainer {
        overflow: hidden;
        width: 100%
    }

    div.tableContainer table {
        float: left;
    }

    html>body div.tableContainer table {}

    thead.fixedHeader tr {
        position: relative;
    }

    thead.fixedHeader th {
        background: #C96;
        border-left: 1px solid #EB8;
        border-right: 1px solid #B74;
        border-top: 1px solid #EB8;
        padding: 4px 3px;
        text-align: left
    }

    html>body tbody.scrollContent {
        display: block;
        height: 262px;
        overflow: auto;
        width: 100%
    }

    html>body thead.fixedHeader {
        display: table;
        overflow: auto;
        width: 100%
    }

    tbody.scrollContent td,
    tbody.scrollContent tr.normalRow td {
        background: #FFF;
        border-bottom: none;
        border-left: none;
        border-right: 1px solid #CCC;
        border-top: 1px solid #DDD;
        padding: 2px 3px 3px 4px
    }

    tbody.scrollContent tr.alternateRow td {
        background: #EEE;
        border-bottom: none;
        border-left: none;
        border-right: 1px solid #CCC;
        border-top: 1px solid #DDD;
        padding: 2px 3px 3px 4px
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?= $unit ?> </span>&nbsp;
            -
            <span class="title-web">Logistik <small>Pemakaian Barang</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:#fff" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i style="color:#fff" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Logistik</a>
                <i style="color:#fff" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Pemakaian Barang </a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">Daftar Pemakaian Barang - <?= $periode ?></div>
            </div>

            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">
                        <a href="<?php echo base_url() ?>logistik_keluar/entri" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Data Baru</a>
                    </div>
                    <!--button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button-->
                    <!-- <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button> -->
                    <div class="btn-group pull-right">
                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th style="text-align: center;width:10%;color:#fff">Cabang</th>
                            <th style="text-align: center;width:10%;color:#fff">User ID</th>
                            <th style="text-align: center;width:20%;color:#fff">No. Pemakaian</th>
                            <th style="text-align: center;width:10%;color:#fff">Tanggal</th>
                            <th style="text-align: center;width:15%;color:#fff">Gudang</th>
                            <th style="text-align: center;width:20%;color:#fff">Keterangan</th>
                            <th style="text-align: center;width:15%;color:#fff">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listv as $lkey => $lval) {
                            echo "
                                    <tr>
                                        <td>" . $lval->koders . "</td>
                                        <td>" . $lval->username . "</td>
                                        <td>" . $lval->pakaino . "</td>
                                        <td>" . date("d/m/Y", strtotime($lval->pakaidate)) . "</td>
                                        <td>" . $lval->gudang . "</td>
                                        <td>" . $lval->keterangan . "</td>
                                        <td class='text-center'>
                                            <a href='/logistik_keluar/detail/" . $lval->pakaino . "'><button class='btn blue btn-sm' type='button'><i class='fa fa-edit'></i></button></a>
                                            <a href='/logistik_keluar/cetak/" . $lval->pakaino . "' target='_blank'><button class='btn yellow btn-sm' type='button'><i class='fa fa-print'></i></button></a>
                                            <button class='btn red btn-sm' type='button' onclick='delete_data($lval->id)'><i class='fa fa-trash'></i></button>
                                        </td>
                                    </tr>
                                ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('template/footero');
$this->load->view('template/v_report');
$this->load->view('template/v_periode');
?>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js') ?>"></script>


<script type="text/javascript">
    // var save_method; //for save method string
    // var table;

    $(document).ready(function() {

        $('#table').DataTable({});

        //datatables
        // table = $('#table').DataTable({ 

        //     "processing": true, //Feature control the processing indicator.
        //     "serverSide": true, //Feature control DataTables' server-side processing mode.
        //     "order": [], //Initial no order.

        //     // Load data for the table's content from an Ajax source
        //     "ajax": {
        //         "url": "<?php echo site_url('logistik_keluar/ajax_list/1') ?>",
        //         "type": "POST"
        //     },

        // 	"oLanguage": {
        //                 "sEmptyTable": "Tidak ada data",
        //                 "sInfoEmpty": "Tidak ada data",
        //                 "sInfoFiltered": " - Dipilih dari _MAX_ data",
        //                 "sSearch": "Pencarian Data : ",
        //                 "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
        //                 "sLengthMenu": "_MENU_ Baris",
        //                 "sZeroRecords": "Tida ada data",
        //                 "oPaginate": {
        //                     "sPrevious": "Sebelumnya",
        //                     "sNext": "Berikutnya"
        //                 }
        //             },

        // 	"aLengthMenu": [
        //                 [5, 15, 20, -1],
        //                 [5, 15, 20, "Semua"] // change per page values here
        //             ],		

        //     //Set column definition initialisation properties.
        //     "columnDefs": [
        //     { 
        //         "targets": [ -1 ], //last column
        //         "orderable": false, //set not orderable
        //     },
        //     ],

        // });

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
        // $("input").change(function(){
        //     $(this).parent().parent().removeClass('has-error');
        //     $(this).next().empty();
        // });
        // $("textarea").change(function(){
        //     $(this).parent().parent().removeClass('has-error');
        //     $(this).next().empty();
        // });
        // $("select").change(function(){
        //     $(this).parent().parent().removeClass('has-error');
        //     $(this).next().empty();
        // });

    });

    // function reload_table(){
    //     table.ajax.reload(null,false); //reload datatable ajax 
    // }

    function delete_data(id) {
        if (confirm('Yakin data barang dengan kode ' + id + ' ini akan dihapus ?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('logistik_keluar/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    //if success reload ajax table
                    // $('#modal_form').modal('hide');
                    location.href = '/logistik_keluar';
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    // function filterdata(){
    // 	var tgl1 = document.getElementById("tanggal1").value;
    // 	var tgl2 = document.getElementById("tanggal2").value;
    // 	var id   = 2; 
    // 	var str  = id+'~'+tgl1+'~'+tgl2; 
    // 	table.ajax.url("<?php echo base_url('logistik_keluar/ajax_list/') ?>"+str).load();		
    // }

    function filterdata() {
        var tgl1 = document.getElementById("tanggal1").value;
        var tgl2 = document.getElementById("tanggal2").value;
        var str = tgl1 + '~' + tgl2;
        location.href = "<?php echo base_url(); ?>logistik_keluar/filter/" + str;
    }
</script>

<script>
    jQuery(document).ready(function() {
        ComponentsPickers.init();
    });
</script>