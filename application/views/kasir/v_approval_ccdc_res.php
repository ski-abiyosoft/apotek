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
                <span class="title-web">Kasir <small>Approval Debit/Kredit Card</small>
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
                    <a class="title-white" href="#">Result</a>
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
        <div class="text-success">
            <b>Approval Debit/Kredit Card - <?= $this->input->get("cashier"); ?></b>
        </div>

    </div>

    <form id="acdform" class="form-horizontal" method="POST">
        <input type="hidden" name="hidenomutasi" id="hidenomutasi">
        <input type="hidden" name="username" value="<?= $username ?>">
        <input type="hidden" name="shift" value="<?= $shift ?>">
        <input type="hidden" name="kodebank" value="<?= $this->input->get("edc") ?>">
    <div class="portlet box blue" style="margin-bottom:30px !important;padding-bottom:0px !important">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>&nbsp;Data Baru</div>
        </div>
        
        <div class="portlet-body form" style="padding:20px !important">
            <div style="width:90%;margin:auto">
                <div class="row" style="margin:0 0 20px 0">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">No Transaksi</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="nomutasi" name="nomutasi" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">Debit</label>
                            </div>
                            <div class="col-md-9">
                                <select id="acdari" name="acdari" class="form-control select2_el_kasbank" onkeypress="return tabE(this,event)"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">Kredit</label>
                            </div>
                            <div class="col-md-9">
                                <select id="acke" name="acke" class="form-control select2_el_kasbank" onkeypress="return tabE(this,event)"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">Keterangan</label>
                            </div>
                            <div class="col-md-9">
                                <textarea type="test" class="form-control" id="keterangan" name="keterangan" rows="3" style="resize:none">Approval Debit/Kredit Cars <?= $username ?> Shift <?= $shift ?> <?= date("d/m/Y") ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Jumlah Mutasi Rp</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="jumlahmutasi" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <center>
                    <button type="button" class="btn btn-success" id="save_mutasi"><i class="fa fa-save"></i> Simpan & Mulai Mutasi</button>
                    <button type="button" class="btn btn-warning" id="cetak_mutasi"><i class="fa fa-print"></i> Cetak Mutasi</button>
                    <button type="button" class="btn btn-danger"  id="close_mutasi" onclick="location.href='/approval_ccdc'" style="margin-left:20px"><i class="fa fa-times"></i> Tutup</button>
                </center>
            </div>
        </div>
    </div>

    <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%" style="padding-bottom">							
        <thead>
            <tr class="page-breadcrumb breadcrumb">
                <th style="text-align: center" class="title-white">No Kwitansi</th>
                <th style="text-align: center" class="title-white">Tanggal Transaksi</th>
                <th style="text-align: center" class="title-white">Jenis Kartu</th>
                <th style="text-align: center" class="title-white">No Kartu</th>
                <th style="text-align: center" class="title-white">Bank Penerbit Kartu</th>
                <th style="text-align: center" class="title-white">Total + ADM Rp</th>
                <th style="text-align: center" class="title-white">Approve</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($listv as $lvkey => $lval): ?>
                <tr>
                    <td>
                        <input name="listpenerimaan[]" type="hidden" value="<?= $lval->nokwitansi ?>">
                        <?= $lval->nokwitansi ?>
                    </td>
                    <td><?= date("d/m/Y", strtotime($lval->tglbayar)) ?></td>
                    <td>
                        <?php
                            switch($lval->cardtype){
                                case 1 : echo "DEBIT"; break;
                                case 2 : echo "CREDIT"; break;
                                case 3 : echo "TRANSFER"; break;
                                default : echo "Unselected Card Type"; break;
                            }
                        ?>
                    </td>
                    <td><?= $lval->nocard ?></td>
                    <td><?= $lval->namabank ?></td>
                    <td id="count_total"><?= number_format($lval->totalcardrp, 2, '.', ',') ?></td>
                    <td><?= $lval->nootorisasi ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </form>

<?php
    $this->load->view('template/footer');  
?>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>

<script>
    $(window).on("load", function(){
        sequentNomutasi();
        $("#cetak_mutasi").hide();
        
        var totalrp     = 0;
        $("tr #count_total").each(function(index,value){
            currentRow  = parseInt(Number($(this).html().replace(/[^0-9\.]+/g,"")));
            totalrp += currentRow
        });
        $("input[name='jumlahmutasi']").val(formatCurrency1(totalrp));
    });

    $(document).ready(function() {
	    $("#table").DataTable({
            "order": [ 2, "desc" ],
        });
    });

    $("#cetak_mutasi").on("click", function(){
        var nomutasi = $("#nomutasi").val();
        location.href="/approval_ccdc";
        window.open("/approval_ccdc/cetak/"+nomutasi, "_blank");
    });

    $("#save_mutasi").on("click", function(){
        var post_form = $("#acdform").serialize();
        $.ajax({
            url: "/approval_ccdc/save",
            type: "POST",
            data: post_form,
            success: function(data){
                secondNomutasi();
                swal({
                    title: "Berhasil",
                    html: "Mutasi Pendapatan Berhasil Dibuat",
                    type: "success",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "green"
                });
                $("#save_mutasi").hide();
                $("#close_mutasi").show();
                $("#cetak_mutasi").show();

                $("#acdari").prop("disabled", true);
                $("#acke").prop("disabled", true);
                $("#keterangan").prop("disabled", true);
            },
            error: function (data,xhr, ajaxOptions, thrownError){
                swal({
                    title: "Kesalahan",
                    html: "Mutasi Gagal Ditambahkan",
                    type: "error",
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#aaa"
                });
            }
        });
    });

    function sequentNomutasi(){
        $.ajax({
            url: "/approval_ccdc/get_last_number/",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#hidenomutasi").val(data.lastno);
                $("#nomutasi").val(data.lastno);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("Error get nomutasi");
            }
        });        
    }

    function secondNomutasi(){
        $.ajax({
            url: "/approval_ccdc/get_recent_number/",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#hidenomutasi").val(data.notr);
                $("#nomutasi").val(data.notr);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("Error get nomutasi");
            }
        });        
    }
</script>