<?php
    $this->load->view("template/header");
    $this->load->view("template/body");

    $error  = $this->session->flashdata("error");

    if(isset($error)){
        echo "<script>alert('". $error ."')</script>";
    }

?>

<!-- <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet"> -->

<!-- <link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/> -->

<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css"/>

<style>
    .toolbar {float:left}
    .modal {text-align:center;padding:0!important}
    .modal:before {content:'';display:inline-block;height:100%;vertical-align:middle;margin-right: -4px;}
    .modal-dialog {display:inline-block;text-align:left;vertical-align:middle}
    @media screen and (max-width:720px){.modal-content {width:100%;margin:auto}}
</style>

<!-- Modal Filter Date -->
<?php
    // Filter Periode Default Date Value

    if(isset($_GET["filterdate"])){
        $extract_date   = explode("~", $_GET["filterdate"]);

        if($extract_date[0] == "1"){
            $filterdate1_start  = $extract_date[1];
            $filterdate1_end    = $extract_date[2];
            $filterdate2_start  = date("Y-m-d");
            $filterdate2_end    = date("Y-m-d");
        } else 
        if($extract_date[0] == "2"){
            $filterdate1_start  = date("Y-m-d");
            $filterdate1_end    = date("Y-m-d");
            $filterdate2_start  = $extract_date[1];
            $filterdate2_end    = $extract_date[2];
        } else {
            header("locatio:/ro2/");
        }
    } else {
        $filterdate1_start  = date("Y-m-d");
        $filterdate1_end    = date("Y-m-d");
        $filterdate2_start  = date("Y-m-d");
        $filterdate2_end    = date("Y-m-d");
    }
?>

<div id="filterdate1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">
            <form id="frmdate1">
                <div class="modal-header">
                    <h4 class="modal-title">Periode Data</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filterdate1-start" class="form-label"><b>Mulai Tanggal</b></label>
                        <input type="date" class="form-control" name="filterdate1-start" id="filterdate1-start" value="<?= $filterdate1_start ?>">
                    </div>
                    <div style="margin-botttom:0px;padding-bottom:0px">
                        <label for="filterdate1-end" class="form-label"><b>s/d Tanggal</b></label>
                        <input type="date" class="form-control" name="filterdate1-end" id="filterdate1-end" value="<?= $filterdate1_end ?>">
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <center>
                        <button type="button" class="btn btn-success" onclick="filterDate(1)">Buka Data</button>
                    </center>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="filterdate2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">
            <form id="frmdate2">
                <div class="modal-header">
                    <h4 class="modal-title">Periode Data</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filterdate2-start" class="form-label"><b>Mulai Tanggal</b></label>
                        <input type="date" class="form-control" name="filterdate2-start" id="filterdate2-start" value="<?= $filterdate2_start ?>">
                    </div>
                    <div style="margin-botttom:0px;padding-bottom:0px">
                        <label for="filterdate2-end" class="form-label"><b>s/d Tanggal</b></label>
                        <input type="date" class="form-control" name="filterdate2-end" id="filterdate2-end" value="<?= $filterdate2_end ?>">
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <center>
                        <button type="button" class="btn btn-success" onclick="filterDate(2)">Buka Data</button>
                    </center>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- Modal Filter Date -->

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?= $this->session->userdata("unit"); ?>
            </span>&nbsp;
            -
            &nbsp;<span class="title-web"><?= $modul; ?> <small> <?= $title; ?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url(); ?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i>&nbsp;</li>
            <li><a href="#" class="title-white"><?= $modul; ?>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></a></li>
            <li><a href="#" class="title-white"><?= $submodul; ?></a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">

            <!-- TITLE -->
            <div class="portlet-title">
                <div class="caption"><?= strtoupper($title) ?></div>
            </div>

            <!-- DASHBOARD -->
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                <?= number_format($total_eorder, 0, ',', '.') ?> <small style="font-size:12px !important">Order</small></div>
                            <div class="desc" style="font-weight:bold;font-size:14px !important">BELUM DIPROSES</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                <?= number_format($total_order, 0, ',', '.') ?> <small style="font-size:12px !important">Order</small></div>
                            <div class="desc" style="font-weight:bold;font-size:14px !important">SUDAH DIPROSES</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                0 <small style="font-size:12px !important">Photo</small></div>
                            <div class="desc" style="font-weight:bold;font-size:14px !important">SELESAI ISI HASIL</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                0 <small style="font-size:12px !important">Photo</small></div>
                            <div class="desc" style="font-weight:bold;font-size:14px !important">BELUM ISI HASIL</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- DASHBOARD -->

            <br /><br />

            <!-- ========================================================================================= TABLE ========================================================================================= -->
            
            <!-- E-Order Radiologi List -->
            <div class="portlet-body">
                <div class="table-toolbar">
                    <!-- <h5 style="display:inline-block;color:green"><b>DAFTAR ORDER DARI UNIT LAIN</b> </h5> -->
                    <h4 style="display:inline-block;color:green"><b>DAFTAR E-ORDER
                    <?php
                        if(isset($_GET["filterdate"])){
                            $extract_fd = explode("~", $_GET["filterdate"]);

                            if($extract_fd[0] == "1"){
                                function convertmonth($month){
                                    switch($month){
                                        case "01"   : $result   = "Januari"; break;
                                        case "02"   : $result   = "Februari"; break;
                                        case "03"   : $result   = "Maret"; break;
                                        case "04"   : $result   = "April"; break;
                                        case "05"   : $result   = "Mei"; break;
                                        case "06"   : $result   = "Juni"; break;
                                        case "07"   : $result   = "Juli"; break;
                                        case "08"   : $result   = "Agustus"; break;
                                        case "09"   : $result   = "September"; break;
                                        case "10"   : $result   = "Oktober"; break;
                                        case "11"   : $result   = "November"; break;
                                        case "12"   : $result   = "Desember"; break;
                                    }

                                    return $result;
                                }

                                $convert_start  = date("m-Y", strtotime($extract_fd[1]));
                                $convert_end    = date("m-Y", strtotime($extract_fd[2]));

                                $extract_convert_start  = explode("-", $convert_start);
                                $extract_convert_end    = explode("-", $convert_end);

                                $month_start    = convertmonth($extract_convert_start[0]);
                                $month_end      = convertmonth($extract_convert_end[0]);

                                if($extract_convert_start[1] == $extract_convert_end[1]){
                                    if($extract_convert_start[0] == $extract_convert_end[0]){
                                        $periode    = $month_start ." ". $extract_convert_start[1];
                                    } else {
                                        $periode    = $month_start ."  <font style='font-size:12px'>s/d</font>   ". $month_end ." ". $extract_convert_start[1] ."&emsp;<a href='/ro2/'>Lihat Semua</a>";
                                    }
                                } else {
                                    $periode    = $month_start ." ". $extract_convert_start[1] ."  <font style='font-size:12px'>s/d</font>   ". $month_end ." ". $extract_convert_end[1] ."&emsp;<a href='/ro2/'>Lihat Semua</a>";
                                }
                                
                                echo "&nbsp;&nbsp;-&nbsp;&nbsp;Periode ". $periode;
                            }
                        } else {
                            //
                        }
                    ?>
                    </b></h4>
                </div>
                
                <!-- FILTER DATE -->
                <div style="display:block;position:relative;margin-bottom:30px">
                    &nbsp;
                    <button class="btn btn-secondary pull-right" type="button" style="float:right" data-toggle="modal" data-target="#filterdate1">Filter Periode</button>
                </div>
                <!-- FILTER DATE -->

                <table class="table table-striped table-hover table-bordered" id="dataTableEx" name="table" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center" class="title-white">Proses</th>
                            <th style="text-align: center" class="title-white">No Order</th>
                            <th style="text-align: center" class="title-white">Tanggal dan Jam Order</th>
                            <th style="text-align: center" class="title-white">No Mr</th>
                            <th style="text-align: center" class="title-white">Nama Pasien</th>
                            <th style="text-align: center" class="title-white">Pemeriksaan</th>
                            <th style="text-align: center" class="title-white">Asal</th>
                            <th style="text-align: center" class="title-white">Permintaan Dari Dr</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($eorder as $eo){ ?>
                            <div id="<?= $eo->orderno ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Detail Pemeriksaan No Order : <b><?= $eo->orderno ?></b></h4>
                                        </div>
                                        <div class="modal-body">
                                            <ul>
                                            <?php
                                                $modal_erad_list    = $this->db->query("SELECT * FROM tbl_eradio WHERE notr = '$eo->orderno' AND noreg = '$eo->noreg'")->result();
                                                foreach($modal_erad_list as $melval){
                                                    echo "<li>". $melval->kodetarif ." - ". $melval->tindakan ." - Rp ". number_format($melval->tarifrs + $melval->tarifdr, 0, ',', '.') ." - Ket : ". $melval->keterangan ."</li>";
                                                }
                                            ?>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <tr>
                                <td>
                                    <button type="button" class="btn blue btn-xs" onclick="location.href='/ro2/eorder/<?= $eo->orderno ?>'">Proses</button>
                                </td>
                                <td><?= $eo->orderno ?></td>
                                <td><?= date("d/m/Y", strtotime($eo->tglorder)) ." ". $eo->jamorder ?></td>
                                <td><?= $eo->rekmed ?></td>
                                <td><?= data_master("tbl_pasien", array("rekmed" => $eo->rekmed))->namapas ?></td>
                                <td><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#<?= $eo->orderno ?>">Detail Pemeriksaan</button></td>
                                <td><?= data_master("tbl_namapos", array("kodepos" => $eo->asal))->namapost ?></td>
                                <td><?= ($eo->asal == "")? "" : data_master("dokter", array("koders" => $eo->koders, "kodokter" => $eo->kodokter, "kopoli" => $eo->asal))->nadokter ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <hr />

            <!-- Order List -->
            <div class="portlet-body">
                <div class="table-toolbar">
                    <h4 style="display:inline-block;color:green"><b>DAFTAR PEMERIKSAAN
                    <?php
                        if(isset($_GET["filterdate"])){
                            $extract_fd = explode("~", $_GET["filterdate"]);

                            if($extract_fd[0] == "2"){
                                function convertmonth($month){
                                    switch($month){
                                        case "01"   : $result   = "Januari"; break;
                                        case "02"   : $result   = "Februari"; break;
                                        case "03"   : $result   = "Maret"; break;
                                        case "04"   : $result   = "April"; break;
                                        case "05"   : $result   = "Mei"; break;
                                        case "06"   : $result   = "Juni"; break;
                                        case "07"   : $result   = "Juli"; break;
                                        case "08"   : $result   = "Agustus"; break;
                                        case "09"   : $result   = "September"; break;
                                        case "10"   : $result   = "Oktober"; break;
                                        case "11"   : $result   = "November"; break;
                                        case "12"   : $result   = "Desember"; break;
                                    }

                                    return $result;
                                }

                                $convert_start  = date("m-Y", strtotime($extract_fd[1]));
                                $convert_end    = date("m-Y", strtotime($extract_fd[2]));

                                $extract_convert_start  = explode("-", $convert_start);
                                $extract_convert_end    = explode("-", $convert_end);

                                $month_start    = convertmonth($extract_convert_start[0]);
                                $month_end      = convertmonth($extract_convert_end[0]);

                                if($extract_convert_start[1] == $extract_convert_end[1]){
                                    if($extract_convert_start[0] == $extract_convert_end[0]){
                                        $periode    = $month_start ." ". $extract_convert_start[1];
                                    } else {
                                        $periode    = $month_start ."  <font style='font-size:12px'>s/d</font>   ". $month_end ." ". $extract_convert_start[1] ."&emsp;<a href='/ro2/'>Lihat Semua</a>";
                                    }
                                } else {
                                    $periode    = $month_start ." ". $extract_convert_start[1] ."  <font style='font-size:12px'>s/d</font>   ". $month_end ." ". $extract_convert_end[1] ."&emsp;<a href='/ro2/'>Lihat Semua</a>";
                                }
                                
                                echo "&nbsp;&nbsp;-&nbsp;&nbsp;Periode ". $periode;
                            }
                        } else {
                            //
                        }
                    ?>
                    </b></h4>
                </div>
                
                <!-- FILTER DATE -->
                <div style="display:block;position:relative;margin-bottom:10px">
                    <button class="btn green" type="button" onclick="location.href='/ro2/create'"><i class="fa fa-plus"></i>&nbsp; Tambah Transaksi</button>
                    <button class="btn btn-secondary pull-right" type="button" style="float:right" data-toggle="modal" data-target="#filterdate2">Filter Periode</button>
                </div>
                <!-- FILTER DATE -->

                <table id="orderData" class="table table-striped table-hover table-bordered" name="table" style="overflow: auto; white-space: nowrap;" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center" class="title-white">Aksi</th>
                            <th style="text-align: center" class="title-white">Hasil</th>
                            <th style="text-align: center" class="title-white">No Radio</th>
                            <th style="text-align: center" class="title-white">No Order</th>
                            <th style="text-align: center" class="title-white">Tanggal dan Jam</th>
                            <th style="text-align: center" class="title-white">No Rm</th>
                            <th style="text-align: center" class="title-white">Nama Pasien</th>
                            <th style="text-align: center" class="title-white">Pemeriksaan</th>
                            <th style="text-align: center" class="title-white">Dr Pengirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($order as $o){ ?>
                            <tr>
                                <td><button type="button" class="btn blue btn-xs" onclick="location.href='/ro2/update/<?= $o->noradio ?>'">Edit</button></td>
                                <td>
                                    <button type="button" class="btn btn-success btn-xs">Isi</button>
                                    <button type="button" class="btn btn-success btn-xs">Serahkan</button>
                                </td>
                                <td><?= $o->noradio ?></td>
                                <td><?= $o->orderno ?></td>
                                <td><?= date("d/m/Y", strtotime($o->tglradio)) ." ". $o->jam ?></td>
                                <td><?= $o->rekmed ?></td>
                                <td><?= $o->namapas ?></td>
                                <td></td>
                                <td><?= ($o->asal == "")? "-" : data_master("dokter", array("koders" => $o->koders, "kodokter" => $o->drpengirim, "kopoli" => $o->asal))->nadokter ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <!-- TABLE -->

        </div>
    </div>
</div>

<?php
    $this->load->view('template/footero');
    $this->load->view('template/v_report');
    $this->load->view('template/v_periode'); 
?>

<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>" type="text/javascript"></script>

<script>
    $(window).on("load", function(){
        filterdateScrolled();
    });

    $(document).ready(function() {
        $('#dataTableEx').DataTable({
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
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
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ]
            },
        });

        $('#orderData').DataTable({
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
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
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ]
            },
        });

        $('#dataTableEx_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#dataTableEx_wrapper .dataTables_length select').addClass("form-control input-small  input-inline"); // modify table per page dropdown
        $('#dataTableEx_wrapper .dataTables_length').attr("style", "float:left");

        $('#orderData_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#orderData_wrapper .dataTables_length select').addClass("form-control input-small  input-inline"); // modify table per page dropdown
        $('#orderData_wrapper .dataTables_length').attr("style", "float:left");
    });

    function filterDate(param){
        var dateStart   = $("#filterdate"+ param +"-start").val();
        var dateEnd     = $("#filterdate"+ param +"-end").val();

        location.href='/ro2/?filterdate='+ param +'~'+ dateStart +'~'+ dateEnd;
    }

    function filterdateScrolled(){
        var filterdate_query    = '<?= $this->input->get("filterdate") ?>';
        if(filterdate_query != ""){
            var splitfilter = filterdate_query.split("~");

            if(splitfilter[0] == "2"){
                var $target = $('html,body'); 
                 return $target.animate({scrollTop: $target.height()}, 1000);
            }
        }
    }
</script>