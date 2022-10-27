<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>

<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<style>
.dropbtn {
    border: none;
    cursor: pointer;
}

.dropbtn:hover,
.dropbtn:focus {
    background-color: #2980B9;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    overflow: auto;
    background-color: #3498DB;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown a:hover {
    background-color: #ddd;
}

.show {
    display: block;
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
                    Daftar Pasien Poli -
                    <span>
                        <b>
                            <?= $periode;
                             ?>

                        </b>
                    </span>
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
                                <?php echo number_format($total_pasien,0, ',','.');?>
                            </div>

                            <div class="desc">
                                TOTAL PASIEN POLI HARI INI
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-printx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <?php echo number_format($diperiksa_perawat,0, ',','.');?>
                            </div>
                            <div class="desc">
                                BELUM DIVERIFIKASI DAN DIPERIKSA OLEH TTV PERAWAT
                            </div>
                        </div>

                        <a data-toggle="modal" class="more" href="">
                            Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="fa fa-shopping-cartx"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <?php echo number_format($diperiksa_dokter,0, ',','.');?>
                            </div>
                            <div class="desc">
                                PASIEN SELESAI DIPERIKSA
                            </div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat yellow">
                        <div class="visual">
                            <i class="fa fa-shopping-cartx"></i>
                        </div>
                        <div class="details">
                            <div class="number">

                            </div>
                            <div class="desc">
                                RATA RATA WAKTU TUNGGU
                            </div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            <br>
            <div class="portlet-body">
                <div class="table-toolbar">


                    <div class="btn-group">

                        <h5 style="color: blue"> <b>Poliklinik: All Poli &nbsp; Dokter: All Dokter </b> &nbsp; </h5>

                    </div>

                    <div class="btn-group pull-right">
                        <!-- <a href="<?php echo base_url()?>hutang/export" class="btn btn-success">  -->
                        <button class="btn btn-default" onclick="reload_table()"><i name="refresh" id="refresh"
                                class="glyphicon glyphicon-refresh"></i> Refresh</button>

                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a data-toggle="modal" href="#hperiode">Filter Data</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <br>
                <table class="table table-striped table-hover table-bordered" id="table" name="table">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center">E-MR</th>
                            <th style="text-align: center">Diperiksa Perawat</th>
                            <th style="text-align: center">Diperiksa Dokter</th>
                            <th style="text-align: center">No antri</th>
                            <th style="text-align: center">No REG</th>
                            <th style="text-align: center">No RM</th>
                            <th style="text-align: center">Tanggal Daftar</th>
                            <th style="text-align: center">Nama Pasien</th>
                            <th style="text-align: center">Poliklinik</th>
                            <th style="text-align: center">Dokter</th>
                            <th style="text-align: center">Pembayaran</th>
                            <!-- <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                     if($data != ''){
                     $no = 1;
                     foreach ($data as $dt){ ?>

                        <tr>
                            <td>
                                <i class="fa fa-solid fa-address-card" data-toggle="modal" href="#e-mr"></i>
                            </td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                            <td><?= $dt->antrino; ?> <button type="submit">call</button></td>
                            <td><?= $dt->noreg;?></td>
                            <td><?= $dt->rekmed;?></td>
                            <td><?= $dt->tglmasuk; ?></td>
                            <td><?= $dt->namapas; ?></td>
                            <td><?= $dt->namapost; ?></td>
                            <td><?= $dt->nadokter; ?></td>
                            <td><?= $dt->ketjenis; ?></td>
                            <!-- <td> <a href="">Edit</a> </td> -->
                            <?php  } } ?>

                    </tbody>
                    <tfoot>

                        <td colspan="7" style="text-align:right">Total:</td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td colspan="1"></td>


                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
</script>

<div class="modal fade" id="e-mr" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <label class="col-md-2 control-label">No REG:</label>
                            <div class="col-md-6">
                                <input id="noreg" name="noreg" class="form-control input-medium" type="text"
                                    value="<?= $dt->noreg; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">NO RM:</label>
                            <div class="col-md-6">
                                <input id="noreg" name="noreg" class="form-control input-medium" type="text"
                                    value="<?= $dt->rekmed; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="col-md-6">
                                <button type="button" class="btn green"><i class=" fa fa-solid fa-check"></i>Pemeriksaan
                                    Dokter</button>
                            </p>
                            <p class="col-md-6">
                                <button type="button" class="btn green"><i class=" fa fa-solid fa-check"></i>Pemeriksaan
                                    Dokter</button>
                            </p>
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <p class="col-md-6">
                                <button type="button" class="btn green">Surat Perintah Rawat Inap</button>
                            </p>
                        </div>
                        <div class="form-group">
                            <p class="col-md-6">
                                <button type="button" class="btn green">Surat Persetujuan Tindakan</button>
                            </p>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">

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
                            <label class="col-md-4 control-label">Kode Dokter :</label>
                            <div class="col-md-6">

                                <select style='color:black;' id="kodokter" name="kodokter" class="selectpicker"
                                    data-live-search="true" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)">
                                    <option value=''>-- Pilih Data --</option>
                                    <?php
                                    foreach($listDokter as $key){
                                        echo "<option>$key->kodokter</option>";
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
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<!-- <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script> -->
<!-- <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script> -->

<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript">
< /scrip> <
script src = "<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>"
type = "text/javascript" >
</script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>"
    type="text/javascript"></script>
<script>
< link rel = "stylesheet"
href = "<?php echo base_url('assets/css/bootstrap-select2.min.css')?>" >
    <
    script src = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js" >
</script>

</script>

</script>
<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Poliklinik/ajax_list')?>",
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
    // $("select").change(function() {
    //     $(this).parent().parent().removeClass('has-error');
    //     $(this).next().empty();
    // });

});




function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}


function delete_data(id) {
    if (confirm('Yakin data barang dengan kode ' + id + ' ini akan dihapus ?')) {
        // ajax delete data to database
        $.ajax({
            url: "<?php echo site_url('logistik_keluar/ajax_delete')?>/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

    }
}

function filterdata() {
    var tglmasuk = document.getElementById("tglmasuk").value;
    var kodepos = document.getElementById("kodepos").value;
    var kodokter = document.getElementById("kodokter").value;
    var tglakhir = document.getElementById("tglakhir").value;
    // var koders = document.getElementById("koders").value;
    var str = '?tglmasuk=' + tglmasuk +
        tglakhir + '&kodepos=' + kodepos + '&kodokter=' + kodokter;
    location.href = "<?php echo base_url();?>Poliklinik/filter" + str;
}
</script>

<script>
jQuery(document).ready(function() {
    ComponentsPickers.init();
});
</script>