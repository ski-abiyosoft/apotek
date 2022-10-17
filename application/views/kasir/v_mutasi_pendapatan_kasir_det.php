<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<style>
    .additional-toolbar {position:relative;margin:0 0 15px 0}
    .additional-toolbar .right {position:absolute;right:0;bottom:0}
    .additional-toolbar .right input, .additional-toolbar .right input[type='submit'] {border:1px solid #ddd}
    .additional-toolbar .right input[type='date'] {}
    #myBtnContainer {margin: 0 0 20px 0}
    .filterDiv {display:none;margin-bottom:30px}
    .filter-title {margin:0 0 20px 0;font-weight:bold;font-size:18px}
</style>

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?>&nbsp;</span>
                - 
                <span class="title-web">Kasir <small>Mutasi Pendapatan Tunai</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i style="color:white;" class="fa fa-home"></i>
                    <a class="title-white" href="../home.php">Awal</a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="#">Kasir</a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="#">Result Mutasi</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="additional-toolbar">
        <!--<div class="right" id="filterdate">
            <form action="/mutasi_pendapatan_kasir/query" method="POST" class="form-inline">
                <input type="hidden" name="user" value="<?= $this->input->get("cashier") ?>">
                <input type="hidden" name="shift" value="<?= $this->input->get("shift") ?>">
                <b>Ubah Periode</b>&emsp;: Dari Tanggal &nbsp;<input type="date" name="fromdate" value="<?= $this->input->get("from") ?>">&nbsp;
                s/d &nbsp;<input type="date" name="todate" value="<?= $this->input->get("to") ?>">&nbsp;
                <input type="submit"/>
            </form>
        </div>-->
        <h4 class="caption text-success">
            <b>Mutasi Pendapatan Tunai : <?= $head->nomutasi ?></b>
        </h4>

    </div>

    <form id="mkform" class="form-horizontal" method="POST">
        <input type="hidden" name="saldoawal" id="saldoawal" value="0">
        <input type="hidden" name="selisih" id="selisih" value="0">
        <input type="hidden" name="pengeluaran" id="pengeluaran" value="0">
        <div class="portlet box blue" style="margin-bottom:30px !important;padding-bottom:0px !important">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-reorder"></i>&nbsp;Data Baru</div>
            </div>
            
            <div class="portlet-body form" style="padding:20px !important">
                <div style="width:90%;margin:auto">
                    <div class="row" style="margin:0 0 20px 0">
                        <div class="col-sm-6">
                            <!-- TEMP -->
                            <!-- <div class="form-group">
                                <div class="col-md-4">
                                    <label class="control-label">Saldo Awal Kas</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="saldoawal" name="saldoawal" value="0" readonly>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Pendapatan&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="penerimaan" name="penerimaan" value="<?= $penerimaan ?>" readonly>
                                </div>
                            </div>
                            <!-- TEMP -->
                            <!-- <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Pengeluaran&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="pengeluaran" name="pengeluaran" value="<?= $pengeluaran ?>" readonly>
                                </div>
                            </div> -->
                            <!-- TEMP -->
                            <!-- <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Selisih</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="selisih" name="selisih" value="0" readonly>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Saldo Akhir&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="saldoakhir" name="saldoakhir" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Debit&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="<?= $acdari ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Kredit&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="<?= $acke ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Keterangan&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" id="keterangan" name="keterangan" rows="3" style="resize:none" readonly>Mutasi Pendapatan Kasir Tunai <?= $username ?> Shift <?= $shift ?> <?= date("d/m/Y") ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label class="control-label">Jumlah Mutasi Rp&nbsp;<font color="red">*</font>   </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="jumlahmutasi" name="jumlahmutasi" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-warning" id="cetak_mutasi" onclick="window.open('/mutasi_pendapatan_kasir/cetak/<?= $head->nomutasi ?>','open')"><i class="fa fa-print"></i> Cetak Mutasi</button>
                        <button type="button" class="btn btn-danger"  id="close_mutasi" onclick="location.href='/mutasi_pendapatan_kasir'" style="margin-left:20px"><i class="fa fa-times"></i> Tutup</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myBtnContainer">
            <button class="btn btn-primary" onclick="filterSelection('penerimaan')" type="button"> Daftar Pendapatan</button>
            <!-- TEMP -->
            <!-- <button class="btn btn-secondary" onclick="filterSelection('pengeluaran')" type="button"> Daftar Pengeluaran</button> -->
        </div>

        <div class="filterDiv penerimaan">
            <div class="filter-title">Daftar Pendapatan</div>
            <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%" style="padding-bottom">							
                <thead>
                    <tr class="page-breadcrumb breadcrumb">
                        <th style="text-align: center;width:25%" class="title-white">No Kwitansi</th>
                        <th style="text-align: center;width:25%" class="title-white">Tanggal Bayar</th>
                        <th style="text-align: center;width:25%" class="title-white">Keterangan</th>
                        <th style="text-align: center;width:25%" class="title-white">Saldo</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listv as $lvkey => $lval): ?>
                        
                        <tr>
                            <td><input name="listpenerimaan[]" type="hidden" value="<?= $lval->nokwitansi ?>"><?= $lval->nokwitansi ?></td>
                            <td><?= str_replace(" 00:00:00", "", date("d/m/Y", strtotime($lval->tglbayar))) ?></td>
                            <td><?= $lval->keterangan ?></td>
                            <td id="count_total"><?= number_format($lval->total, 2, '.', ',') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="filterDiv pengeluaran">
            <div class="filter-title">Daftar Pengeluaran</div>
            <table id="table2" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%" style="padding-bottom">							
                <thead>
                    <tr class="page-breadcrumb breadcrumb">
                        <th style="text-align: center;width:25%" class="title-white">No Bukti</th>
                        <th style="text-align: center;width:25%" class="title-white">Tanggal Bukti</th>
                        <th style="text-align: center;width:25%" class="title-white">Keterangan</th>
                        <th style="text-align: center;width:25%" class="title-white">Saldo</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listx as $lvkey => $lval): ?>
                        <input name="listpengeluaran[]" type="hidden" value="<?= $lval->bayarno ?>">
                        <tr>
                            <td>
                                <?= $lval->bayarno ?>
                            </td>
                            <td><?= str_replace(" 00:00:00", "", date("d/m/Y", strtotime($lval->tglbayar))) ?></td>
                            <td><?= $lval->keterangan ?></td>
                            <td><?= number_format($lval->jmbayar, 2, '.', ',') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>

<?php
    $this->load->view('template/footer_tb');  
?>

<!-- <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script> -->
<!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
<!-- <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script> -->

<script>
    var penerimaan  = $("#penerimaan").val().split(",").join("");
    var pengeluaran = $("#pengeluaran").val().split(",").join("");
    var saldoawal   = ($("#saldoawal").val() == 0)? 0 : $(this).val();
    var saldoakhir  = eval(penerimaan.split(".00").join("")) - eval(pengeluaran.split(".00").join(""));
    var finalsaldo  = (saldoawal == 0)? saldoakhir : eval(saldoakhir) + eval(saldoawal);

    $(document).ready(function() {
	    $("#table").DataTable({
            "order": [[ 0, "desc" ]],
        });

        $("#table2").DataTable({
            "order": [[ 0, "desc" ]],
        });

        filterSelection("penerimaan");
    });
    
    $("#jumlahmutasi").val(formatCurrency1(finalsaldo));
    $("#saldoakhir").val(formatCurrency1(finalsaldo));

    $("#selisih").on("keyup", function(){
        var selisih         = $(this).val();
        var saldoawal       = ($("#saldoawal").val() == 0)? 0 : $("#saldoawal").val();
        var saldoakhir      = eval(penerimaan.split(".00").join("")) - eval(pengeluaran.split(".00").join(""));
        var zeroselisih     = eval(saldoawal) + eval(penerimaan.split(".00").join("")) - eval(pengeluaran.split(".00").join(""));
        var saldoakhirbs    = (eval(saldoawal) + eval(saldoakhir)) - eval(selisih);

        if(selisih == 0){
            $("#saldoakhir").val(formatCurrency1(zeroselisih));
            $("#jumlahmutasi").val(formatCurrency1(zeroselisih));
        } else {
            $("#saldoakhir").val(formatCurrency1(saldoakhirbs));
            $("#jumlahmutasi").val(formatCurrency1(saldoakhirbs));
        }
    });

    $("#saldoawal").on("keyup", function(){
        var saldoawal       = ($(this).val() == "")? 0 : $(this).val();
        var selisih         = ($("#selisih").val() == 0 || $("#selisih").val() == "")? 0 : $("#selisih").val();
        var finalsaldobs    = eval(saldoakhir) + eval(saldoawal) - eval(selisih);

        if(saldoawal == 0){
            $("#saldoakhir").val(formatCurrency1(finalsaldobs));
            $("#jumlahmutasi").val(formatCurrency1(finalsaldobs));
        } else {
            $("#saldoakhir").val(formatCurrency1(finalsaldobs));
            $("#jumlahmutasi").val(formatCurrency1(finalsaldobs));
        }
    });
</script>

<script>

    // var penerimaan  = numeric_restruct($("#penerimaan").val());
    // var pengeluaran = numeric_restruct($("#pengeluaran").val());
    // var saldoawal   = ($("#saldoawal").val() == 0)? 0 : $(this).val();
    // var saldoakhir  = eval(penerimaan) - eval(pengeluaran);

    // $(window).on("load", function(){
    //     sequentNomutasi();
    //     $("#cetak_mutasi").hide();

    //     $("#jumlahmutasi").val(formatCurrency1(finalsaldo));
    //     $("#saldoakhir").val(formatCurrency1(finalsaldo));
    // });

    // $(document).ready(function() {
	//     $("#table").DataTable({
    //         "order": [[ 0, "desc" ]],
    //     });

    //     $("#table2").DataTable({
    //         "order": [[ 0, "desc" ]],
    //     });

    //     filterSelection("penerimaan");
    // });

    // $("#saldoawal").on("keyup", function(){
    //     var finalsaldo1;

    //     finalsaldo1 = eval(penerimaan) + eval($(this).val()) - eval(pengeluaran);

    //     if($(this).val() == ""){
    //         $("#jumlahmutasi").val(formatCurrency1(penerimaan));
    //         $("#saldoakhir").val(formatCurrency1(penerimaan));
    //     } else {
    //         $("#jumlahmutasi").val(formatCurrency1(finalsaldo1));
    //         $("#saldoakhir").val(formatCurrency1(finalsaldo1));
    //     }
    // });

    function numeric_restruct(param){
        var resone, restwo;

        resone = param.split(",").join("");
        restwo = resone.split(".00").join("");

        return restwo;
    }

    function filterSelection(c) {
        var x, i;
        x = document.getElementsByClassName("filterDiv");
        if (c == "all") c = "";
        for (i = 0; i < x.length; i++) {
            Sectionremoveclass(x[i], "show");
            if (x[i].className.indexOf(c) > -1) Sectionaddclass(x[i], "show");
        }
    }

    function Sectionaddclass(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++) {
            if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
        }
    }

    function Sectionremoveclass(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++) {
            while (arr1.indexOf(arr2[i]) > -1) {
                arr1.splice(arr1.indexOf(arr2[i]), 1);     
            }
        }
        element.className = arr1.join(" ");
    }

    var btnContainer = document.getElementById("myBtnContainer");
    var btns = btnContainer.getElementsByClassName("btn");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(){
            var current = document.getElementsByClassName("btn-primary");
            current[0].className = current[0].className.replace(" btn-primary", "");
            this.className += " btn-primary";
        });
    }
</script>