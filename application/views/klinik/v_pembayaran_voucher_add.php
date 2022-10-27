<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

    <div class="row">
		<div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?= $this->session->userdata("unit"); ?> 
                </span>
                - 
                <span class="title-web">Voucher <small>Penjualan Baru</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="/pembayaran_voucher" class="title-white">Voucher</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white">Penjualan Baru</a></li>
            </ul>
        </div>
    </div>

    <button class="btn btn-primary btn-lg" style="margin:0 0 20px 0" onclick="location.href='/pembayaran_voucher'"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Kembali</button>

    <div class="portlet box blue" style="margin-bottom:30px !important;padding-bottom:0px !important">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>*Data Baru</div>
        </div>
        
        <div class="portlet-body form">							
            <form id="formvoucher" class="form-horizontal" method="post">
                <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                        <div style="padding:10px 0 20px 0;font-weight:bold">
                            <i class="fa fa-bars fa-fw"></i>&nbsp; <span class="label label-info">DATA PASIEN</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Nomor Tr <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="hidden" name="hidenotr" id="hidenotr">
                                            <input type="text" class="form-control" name="notr" id="notr" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Bill No <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="hidden" name="hidebillno" id="hidebillno">
                                            <input type="text" class="form-control" name="billno" id="billno" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Tanggal Jual <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date("Y-m-d") ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">No MR <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <select id="nomr" name="nomr" class="form-control select2_el_pasien" onchange="getrekmed()"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Nama Pasien <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="namapas" id="namapas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>	
                </div>

                <hr />

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="padding:10px 0 20px 0;font-weight:bold">
                                <i class="fa fa-bars fa-fw"></i>&nbsp; <span class="label label-success">ITEM</span>
                            </div>
                            <table id="datatable_voucher" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                <thead  class="breadcrumb">
                                    <tr>
                                        <th class="title-white" width="25%" style="text-align: center">No Voucher</th>
                                        <th class="title-white" width="25%" style="text-align: center">Nominal Rp</th>
                                        <th class="title-white" width="25%" style="text-align: center">Diskon Rp</th>
                                        <th class="title-white" width="25%" style="text-align: center">Net Rp</th>                 									
                                    </tr>                    									
                                </thead>
                                <tbody id="tablelist">
                                    <tr>
                                        <td><select name="novoucher[]" id="novoucher1" class="select2_el_voucher_penjualan form-control input-largex" onchange="getVoucher(1)"></select></td>
                                        <td><input type="text" class="form-control" name="nominal[]" id="nominal1" readonly></td>
                                        <td><input type="text" class="disnet form-control" name="diskon[]" id="diskon1" onchange="totalitem(1)"></td>
                                        <td><input type="text" class="sumnet form-control" name="netrp[]" id="netrp1" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i></button>
                            <button type="button" onclick="hapus()"  class="btn red"><i class="fa fa-trash-o"></i></button>													
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label class="col-md-3 control-label">TOTAL RP</label>
                                <div class="col-md-9">    
                                    <input type="hidden" name="diskonrp" id="diskonrp">
                                    <input type="text" class="form-control" name="totalrp" id="totalrp" readonly style="color:blue;font-weight:bold">
                                </div>
                            </div>
                        </div>
                    </div> 	   
                </div>

                <hr />

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="padding:10px 0 20px 0;font-weight:bold">
                                <i class="fa fa-bars fa-fw"></i>&nbsp; <span class="label label-primary">PEMBAYARAN</span>
                            </div>
                            <table id="datatable_pembayaran" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                <thead  class="breadcrumb">
                                    <tr>
                                        <th class="title-white" width="20%" style="text-align: center">Bank/Provier</th>
                                        <th class="title-white" width="10%" style="text-align: center">Pay Type</th>
                                        <th class="title-white" width="15%" style="text-align: center">No. Kartu/Member</th>
                                        <th class="title-white" width="10%" style="text-align: center">Approval Code</th>
                                        <th class="title-white" width="15%" style="text-align: center">Nilai Transaksi</th>
                                        <th class="title-white" width="10%" style="text-align: center">Adm %</th>
                                        <th class="title-white" width="20%" style="text-align: center">Grand Total</th>                    									
                                    </tr>                    									
                                </thead>
                                <tbody>
                                    <tr>													   
                                        <td width="20%"><select name="bayar_bank[]" id="bayar_bank1" class="select2_el_kasbankedc form-control input-largex"></select></td>
                                        <td width="10%">
                                            <select name="bayar_tipe[]" id="bayar_tipe1" class="form-control">
                                                <option>--- PILIH ---</option>
                                                <option value="1">DEBIT</option>	
                                                <option value="2">CREDIT CARD</option>
                                                <option value="3">TRANFER</option>
                                                <option value="4">ONLINE</option>
                                            </select>												
                                        </td>
                                        <td width="15%"><input name="bayar_nokartu[]" class="form-control" type="text" maxlength="20"></td>								
                                        <td width="10%"><input name="bayar_trvalid[]" value="0"  type="text" class="form-control rightJustified" maxlength="6"></td>
                                        <td width="15%"><input name="bayar_nilai[]" id="nilai1" onchange="totalbayar(1)" type="text" class="form-control rightJustified" ></td>
                                        <td width="10%"><input name="bayar_adm[]" id="adm1" onchange="totalbayar(1)" type="text" class="form-control rightJustified" ></td>
                                        <td width="20%"><input name="bayar_total[]" id="total1" type="text" class="sumtot form-control rightJustified" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" onclick="tambah_bayar()" class="btn green"><i class="fa fa-plus"></i></button>
                            <button type="button" onclick="hapus_bayar()"  class="btn red"><i class="fa fa-trash-o"></i></button>
                        </div>
                    </div> 	   
                </div>

                <div style="padding:10px 25px 0 25px;width:auto">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 control-label">TOTAL ELECTRONIC RP</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="totalelec" id="totalelec" style="color:blue;font-weight:bold" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 control-label">TOTAL TUNAI</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="totaltunai" id="totaltunai" onchange="totalbayar(1)">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 control-label">UANG PASIEN RP</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="uangpasien" id="uangpasien" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 control-label">KEMBALI RP</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="kembalian" id="kembalian" style="color:blue;font-weight:bold" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:20px;padding:30px;width:auto;background:#f5f5f5">
                    <button type="button" class="btn blue" style="font-weight:bold" id="new_pembayaran"><i class="fa fa-plus fa-fw"></i>&nbsp; Penjualan Baru</button>
                    <button type="button" class="btn blue" style="font-weight:bold" id="save_pembayaran"><i class="fa fa-save fa-fw"></i>&nbsp; Simpan</button>
                    <button type="button" class="btn yellow" style="font-weight:bold" id="cetak_pembayaran"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak Kwitansi</button>
                    <button type="button" class="btn red" style="font-weight:bold" id="tutup_pembayaran" onclick="location.href='/pembayaran_voucher'"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                </div>
            </form>
        </div>
    </div>

<?php
    $this->load->view('template/footer_tb');  
?>

<script>

    $(window).on("load", function(){
        sequentPenjualan();
        $("form").trigger("reset");
        $("#save_pembayaran").show();
        $("#new_pembayaran").hide();
        $("#cetak_pembayaran").hide();
    });

    $("#new_pembayaran").on("click", function(){
        sequentPenjualan();
        $("form").trigger("reset");
        $("#tanggal").prop("readonly", false);
        $("#nomr").prop("disabled", false);
        $("#namapas").prop("readonly", false);
        $("[name='novoucher[]']").prop("disabled", false);
        $("[name='nominal[]']").prop("disabled", false);
        $("[name='diskon[]']").prop("readonly", false);
        $("[name='netrp[]']").prop("readonly", false);
        $("[name='bayar_bank[]']").prop("disabled", false);
        $("[name='bayar_nokartu[]']").prop("readonly", false);
        $("[name='bayar_trvalid[]']").prop("readonly", false);
        $("[name='bayar_nilai[]']").prop("readonly", false);
        $("[name='bayar_adm[]']").prop("readonly", false);
        $("[name='bayar_total[]']").prop("readonly", false);
        $("[name='bayar_tipe[]']").prop("disabled", false);
        $("#totaltunai").prop("readonly", false);
        $("#kembalian").prop("readonly", false);
        $("#save_pembayaran").show();
        $("#new_pembayaran").hide();
        $("#cetak_pembayaran").hide();
    });

    $("#save_pembayaran").on("click", function(e){
        e.preventDefault();
        var post_form = $("#formvoucher").serialize();
        var nomr = $("#nomr").val();
        var namapas = $("#namapas").val();
        var voucher = $("[name='novoucher']").val();

        if(nomr == "" || voucher == "" || namapas == ""){
            swal({
                html: "Data Masih Kosong",
                type: "error",
                confirmButtonText: "Ok" 
            });
        } else {
            console.log(post_form);
            $.ajax({
                url: "/pembayaran_voucher/save/",
                type: "POST",
                data: post_form,
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Voucher Hanya Boleh 1x Penjualan",
                            type: "error",
                            confirmButtonText: "Ok" 
                        });
                    } else {
                        secondPenjualan();
                        $("#tanggal").prop("readonly", true);
                        $("#nomr").prop("disabled", true);
                        $("#namapas").prop("readonly", true);
                        $("[name='novoucher[]']").prop("disabled", true);
                        $("[name='nominal[]']").prop("disabled", true);
                        $("[name='diskon[]']").prop("readonly", true);
                        $("[name='netrp[]']").prop("readonly", true);
                        $("[name='bayar_bank[]']").prop("disabled", true);
                        $("[name='bayar_nokartu[]']").prop("readonly", true);
                        $("[name='bayar_trvalid[]']").prop("readonly", true);
                        $("[name='bayar_nilai[]']").prop("readonly", true);
                        $("[name='bayar_adm[]']").prop("readonly", true);
                        $("[name='bayar_total[]']").prop("readonly", true);
                        $("[name='bayar_tipe[]']").prop("disabled", true);
                        $("#totaltunai").prop("readonly", true);
                        $("#kembalian").prop("readonly", true);
                        $("#save_pembayaran").hide();
                        $("#new_pembayaran").show();
                        $("#cetak_pembayaran").show();
                        console.log(post_form);
                        swal({
                            html: "Voucher Berhasil Terjual",
                            type: "success",
                            confirmButtonText: "Ok" 
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    // alert(xhr.status);
                    // alert(thrownError);
                    swal({
                        html: "Voucher Hanya Boleh 1x Penjualan",
                        type: "error",
                        confirmButtonText: "Ok" 
                    });
                }
            });
            
        }
    });

    function sequentPenjualan(){
        $.ajax({
            url: "/pembayaran_voucher/get_last_number/JUAL_VOC",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#notr").val("Auto");
                $("#hidenotr").val(data.lastno);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("error get no tr");
            }
        });

        $.ajax({
            url: "/pembayaran_voucher/get_last_number/URUTKWT",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#billno").val("Auto");
                $("#hidebillno").val(data.lastno);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("error get bill no");
            }
        });
    }

    function secondPenjualan(){
        $.ajax({
            url: "/pembayaran_voucher/get_recent_number/JUAL_VOC",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#notr").val("Auto");
                $("#hidenotr").val(data.notr);
                $("#cetak_pembayaran").attr("onclick", "window.open('<?= base_url() ?>pembayaran_voucher/cetak/?notransaksi="+ data.notr +"', '_blank')");
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("error get no tr");
            }
        });

        $.ajax({
            url: "/pembayaran_voucher/get_recent_number/URUTKWT",
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#billno").val("Auto");
                $("#hidebillno").val(data.notr);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                alert("error get bill no");
            }
        });
    }

    var idrow  = 2;
    var idrow2 = 2;

    function tambah(){
        var v=document.getElementById('datatable_voucher').insertRow(idrow);
        var tdv1=v.insertCell(0);
        var tdv2=v.insertCell(1);
        var tdv3=v.insertCell(2);
        var tdv4=v.insertCell(3);

        var novoucher = '<select name="novoucher[]" id="novoucher'+ idrow +'" class="select2_el_voucher_penjualan form-control input-largex" onchange="getVoucher('+ idrow +')">';
        tdv1.innerHTML=novoucher;
        tdv2.innerHTML='<input type="text" class="form-control" name="nominal[]" id="nominal'+ idrow +'" readonly>';
        tdv3.innerHTML='<input type="text" class="disnet form-control" name="diskon[]" id="diskon'+ idrow +'" onchange="totalitem('+ idrow +')">';
        tdv4.innerHTML='<input type="text" class="sumnet form-control" name="netrp[]" id="netrp'+ idrow +'" readonly>';
        initailizeSelect2_voucher_penjualan();
        idrow++;
    }
    
    function tambah_bayar(){
        var x=document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1=x.insertCell(0);
        var td2=x.insertCell(1);
        var td3=x.insertCell(2);
        var td4=x.insertCell(3);
        var td5=x.insertCell(4);
        var td6=x.insertCell(5);
        var td7=x.insertCell(6);
        
        var akun='<select name="bayar_bank[]" id="bayar_bank'+ idrow2 +'" class="select2_el_kasbankedc form-control input-largex"></select>';
        td1.innerHTML=akun;
        td2.innerHTML=
            '<select name="bayar_tipe[]" id="bayar_tipe'+ idrow2 +'" class="form-control">'+
            '<option>--- PILIH ---</option>'+
            '<option value="1">DEBIT</option>	'+
            '<option value="2">CREDIT CARD</option>'+
            '<option value="3">TRANFER</option>'+
            '<option value="4">ONLINE</option>'+
            '</select>';
        td3.innerHTML='<input name="bayar_nokartu[]" class="form-control" type="text" maxlength="20">';
        td4.innerHTML='<input name="bayar_trvalid[]" value="0"  type="text" class="form-control rightJustified" maxlength="6">';
        td5.innerHTML='<input name="bayar_nilai[]" id="nilai'+ idrow2 +'" onchange="totalbayar('+ idrow2 +')" type="text" class="form-control rightJustified" >';
        td6.innerHTML='<input name="bayar_adm[]" id="adm'+ idrow2 +'" onchange="totalbayar('+ idrow2 +')" type="text" class="form-control rightJustified" >';
        td7.innerHTML='<input name="bayar_total[]" id="total'+ idrow2 +'" type="text" class="sumtot form-control rightJustified" readonly>';
        initailizeSelect2_kasbankedc();
        idrow2++;
    }

    function hapus_bayar(){
		if(idrow2>2){
			var x=document.getElementById('datatable_pembayaran').deleteRow(idrow2-1);
			idrow2--;
		}
        var totalelec   = Number($("#totalelec").val().replace(/[^0-9\.]+/g,""));
        var totaltunai  = Number($("#totaltunai").val().replace(/[^0-9\.]+/g,""));
        var uangpasien  = Number($("#uangpasien").val().replace(/[^0-9\.]+/g,""));
        var totalrp     = Number($("#totalrp").val().replace(/[^0-9\.]+/g,""));

        var totalelec   = 0;
        $("tr .sumtot").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalelec += currentRow
        });
        $("#totalelec").val(formatCurrency1(totalelec));

        if(totaltunai == ""){
            var uangpasien = totalelec;
        } else {
            var uangpasien = eval(totalelec)+eval(totaltunai);
        }

        var totaluangpasein = $("#uangpasien").val(uangpasien);

        $("#uangpasien").val(formatCurrency1(uangpasien));

        $("#kembalian").val(formatCurrency1(eval(uangpasien) - eval(totalrp)));
	}

    function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable_voucher').deleteRow(idrow-1);
			idrow--;
		}
        var totalrp     = Number($("#totalrp").val().replace(/[^0-9\.]+/g,""));

        var uangpasien     = Number($("#uangpasien").val().replace(/[^0-9\.]+/g,""));

        var totalrp     = 0;
        $("tr .sumnet").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalrp += currentRow
        });

        var tempkembalian = uangpasien - totalrp;

        $("#totalrp").val(formatCurrency1(totalrp));

        $("#kembalian").val(formatCurrency1(tempkembalian));
	}

    function getrekmed(){
        var nopas = $("#nomr").val();
        $.ajax({
            url: "/pembayaran_voucher/getpasien/"+ nopas,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#namapas").val(data.nama);
            },
            error: function(data, xhr, ajaxOptions, thrownError){
                alert("error get rekmed");
            }
        });
    }

    function getVoucher(id){
        var novoucher = $("#novoucher"+ id).val();
        $.ajax({
            url: "/pembayaran_voucher/getvoucher/"+novoucher,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                if(data.status == 0){
                    $("#nominal"+id).val("Tidak Ditemukan");
                } else {
                    $("#nominal"+id).val(formatCurrency1(data.nominal));
                }
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                // alert("error get bill no");
            }
        })
    }

    function totalitem(id){
        /* Number(.replace(/[^0-9\.]+/g,"")) */
        /* Formating */

        /* Initial */
        var nominal     = Number($("#nominal" + id).val().replace(/[^0-9\.]+/g,""));
        var diskon      = Number($("#diskon" + id).val().replace(/[^0-9\.]+/g,""));
        var netrp       = Number($("#netrp" + id).val().replace(/[^0-9\.]+/g,""));
        var totalrp     = Number($("#totalrp").val().replace(/[^0-9\.]+/g,""));

        var uangpasien     = Number($("#uangpasien").val().replace(/[^0-9\.]+/g,""));

        /* Operator */
        var totalnet    = nominal-diskon;

        /* Result */
        $("#netrp" + id).val(formatCurrency1(totalnet));

        var totalrp     = 0;
        $("tr .sumnet").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalrp += currentRow
        });

        var diskonrp   = 0;
        $("tr .disnet").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            diskonrp += currentRow
        });

        var tempkembalian = uangpasien - totalrp;

        $("#totalrp").val(formatCurrency1(totalrp));
        $("#diskonrp").val(formatCurrency1(diskonrp));
        $("#kembalian").val(formatCurrency1(tempkembalian));
    }

    function totalbayar(id){
        // Number(.replace(/[^0-9\.]+/g,""))
        //

        // INITIAL
        var nilai       = Number($("#nilai" + id).val().replace(/[^0-9\.]+/g,""));
        var adm         = Number($("#adm" + id).val().replace(/[^0-9\.]+/g,""));
        var totalelec   = Number($("#totalelec").val().replace(/[^0-9\.]+/g,""));
        var totaltunai  = Number($("#totaltunai").val().replace(/[^0-9\.]+/g,""));
        var uangpasien  = Number($("#uangpasien").val().replace(/[^0-9\.]+/g,""));
        var totalrp     = Number($("#totalrp").val().replace(/[^0-9\.]+/g,""));

        // operator
        var rpadm       = (adm/100)* nilai;
        var totalgrand  = eval(nilai) + eval(rpadm);

        // Result
        $("#total" + id).val(formatCurrency1(totalgrand));

        var totalelec   = 0;
        $("tr .sumtot").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalelec += currentRow
        });
        $("#totalelec").val(formatCurrency1(totalelec));

        if(totaltunai == ""){
            var uangpasien = totalelec;
        } else {
            var uangpasien = eval(totalelec)+eval(totaltunai);
        }

        var totaluangpasein = $("#uangpasien").val(uangpasien);

        $("#uangpasien").val(formatCurrency1(uangpasien));

        $("#kembalian").val(formatCurrency1(eval(uangpasien) - eval(totalrp)));

    }

    /*function totalline(id){
        // Number(.replace(/[^0-9\.]+/g,""))
        //

        // INITIAL
        var nominal     = Number($("#nominal" + id).val().replace(/[^0-9\.]+/g,""));
        var diskon      = Number($("#diskon" + id).val().replace(/[^0-9\.]+/g,""));
        var netrp       = Number($("#netrp" + id).val().replace(/[^0-9\.]+/g,""));
        var nilai       = Number($("#nilai" + id).val().replace(/[^0-9\.]+/g,""));
        var adm         = Number($("#adm" + id).val().replace(/[^0-9\.]+/g,""));
        var totalelec   = Number($("#totalelec").val().replace(/[^0-9\.]+/g,""));
        var totaltunai  = Number($("#totaltunai").val().replace(/[^0-9\.]+/g,""));
        var uangpasien  = Number($("#uangpasien").val().replace(/[^0-9\.]+/g,""));
        var totalrp     = Number($("#totalrp").val().replace(/[^0-9\.]+/g,""));

        // operator
        var totalnet    = nominal-diskon;

        var rpadm       = (adm/100)* nilai;
        var totalgrand  = eval(nilai) + eval(rpadm);

        // Result
        $("#netrp" + id).val(formatCurrency1(totalnet));

        $("#total" + id).val(totalgrand);

        var totalrp     = 0;
        $("tr .sumnet").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalrp += currentRow
        });

        $("#totalrp").val(formatCurrency1(totalrp));

        var totalelec   = 0;
        $("tr .sumtot").each(function(index,value){
            currentRow  = parseInt(Number($(this).val().replace(/[^0-9\.]+/g,"")));
            totalelec += currentRow
        });
        $("#totalelec").val(formatCurrency1(totalelec));

        if(totaltunai == ""){
            var uangpasien = totalelec;
        } else {
            var uangpasien = eval(totalelec)+eval(totaltunai);
        }

        var totaluangpasein = $("#uangpasien").val(uangpasien);

        $("#uangpasien").val(formatCurrency1(uangpasien));

        $("#kembalian").val(formatCurrency1(eval(uangpasien) - eval(totalrp)));

    }*/

</script>