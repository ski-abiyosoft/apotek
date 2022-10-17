<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<style>
    .form-label {margin-top:5px;font-weight:bold}
    .form-label2 {font-weight:bold}
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?= $unit ?> </span>&nbsp;-&nbsp;
            <span class="title-web">Finance <small>Laporan Hutang</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home title-white"></i>
                <a href="../home" class="title-white">Awal</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a href="#" class="title-white">Finance</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a href="#" class="title-white">Laporan Hutang</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="note note-success">
            <p>Laporan - laporan untuk transaksi Finance</p>
        </div>

        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i>Parameter Laporan
                </div>
            </div>
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <form id="frm_hutanglap" class="col-sm-8 form-horizontal" style="padding:20px 0px 10px 0px">
                        <div class="form-group row">
                            <label class="col-sm-3 form-label2">Semua Cabang</label>
                            <div class="col-sm-9">
                                <input type="checkbox" name="allcabang" id="allcabang" value="0" onclick="cb()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Cabang</label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control" name="cabang" id="cabang">
                                    <option value="<?= $unit ?>"><?= data_master("tbl_namers", array("koders" => $unit))->namars ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label2">Semua Vendor</label>
                            <div class="col-sm-9">
                                <input type="checkbox" name="allvendor" id="allvendor" value="0" onclick="vn()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Vendor</label>
                            <div class="col-sm-9">
                                <select type="text" id="vendor" name="vendor" class="form-control selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style='color:#222 !important'>
                                    <?php
                                        foreach($vendor as $vkey => $vval):
                                    ?>
                                        <option value="<?= $vval->vendor_id ?>"><?= $vval->vendor_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Dari Tanggal</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="fromdate" id="fromdate" value="<?= date("Y-m-d") ?>">
                            </div>
                            <label class="col-sm-3 form-label text-center">s/d</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="todate" id="todate" value="<?= date("Y-m-d") ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Laporan</label>
                            <div class="col-sm-9">
                                <select type="text" id="laporan" name="laporan" class="form-control selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style='color:#222 !important'>
                                    <?php
                                        $laporan    = array(
                                            1 => "1. Laporan Detail Hutang",
                                            2 => "2. Laporan Umur Hutang",
                                            5 => "5. Kartu Piutang",
                                            6 => "6. Laporan Rencana Pembayaran",
                                            7 => "7. Laporan Pembayaran Hutang",
                                        );

                                        foreach($laporan as $lkey => $lval):
                                    ?>
                                        <option value="<?= $lkey ?>"><?= $lval ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <br />
                                <button class="btn red" type="button" onclick="cetak(1)"><i class="fa fa-print fa-fw"></i>&nbsp; PDF</button>&nbsp;
                                <button class="btn green" type="button" onclick="cetak(2)"><i class="fa fa-download fa-fw"></i>&nbsp; Excel</button>
                            </div>
                        </div>
                    </form>
                    <div class="col-sm-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<br />

<?php  
    $this->load->view('template/footer');
    // $this->load->view('template/v_report');
?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

<script>
    function cb(){
        if($("#allcabang").is(":checked")){
            $("#allcabang").val(1);
            $("#cabang").prop("disabled", true);
        } else {
            $("#allcabang").val(0);
            $("#cabang").prop("disabled", false);
        }
    }

    function vn(){
        if($("#allvendor").is(":checked")){
            $("#allvendor").val(1);
            $("#vendor").attr('disabled',true);
            $('#vendor').selectpicker('refresh');
        } else {
            $("#allvendor").val(0);
            $("#vendor").attr('disabled',false);
            $('#vendor').selectpicker('refresh');
        }
    }

    function cetak(param){
        var post_form   = $("#frm_hutanglap").serialize();
        var query_string;

        console.log(post_form);

        $.ajax({
            url: "/hutang_lap/cetak/"+ param,
            data: post_form,
            dataType: "JSON",
            type: "POST",
            success: function(data){
                query_string    = "?allcabang="+ data.res.allcabang +
                "&cabang="+ data.res.cabang +
                "&allvendor="+ data.res.allvendor +
                "&vendor="+ data.res.vendor +
                "&fromdate="+ data.res.fromdate +
                "&todate="+ data.res.todate +
                "&laporan="+ data.res.laporan;

                window.open(data.url+query_string, '_blank');
            },
            error: function(data, jqXHR, textStatus, errorThrown) {
                swal({
                    html: textStatus,
                    type: "error",
                    confirmButtonText: "Close",
                    confirmButtonColor: "red"
                });
            }
        });
    }
</script>