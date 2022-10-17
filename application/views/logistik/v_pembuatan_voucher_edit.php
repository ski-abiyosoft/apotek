<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">Voucher <small>Edit Voucher</small></h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo base_url();?>dashboard">Awal</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo base_url();?>pembuatan_voucher">Voucher</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>							
                    <a href="">Edit Voucher</a>
                </li>
            </ul>
        </div>
    </div>

    <button class="btn btn-primary btn-lg" style="margin:0 0 20px 0" onclick="location.href='/pembayaran_voucher'"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Kembali</button>

    <div class="portlet box blue" style="margin-bottom:30px !important;padding-bottom:0px !important">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>*Edit Data</div>
        </div>
        
        <div class="portlet-body form">									
            <form id="formvoucher" class="form-horizontal" method="post">
                <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Untuk Cabang <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <select type="text" name="cabang" class="form-control" id="cabang">
                                                <option value="<?= $unit ?>"><?= str_replace("KLINIK ESTETIKA dr. Affandi ", "", $cabang->namars) ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Jenis Voucher <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="hidden" name="hidejenisvouc" value="<?= $get_voucher->cust_id ?>">
                                            <select type="text" class="form-control" name="jenisvouc" id="jenisvouc">
                                                <option>- Pilih Jenis Voucher -</option>
                                                <?php
                                                    foreach($penjamin as $pjkey => $pjval){
                                                        if($pjval->cust_id == $get_voucher->cust_id){
                                                            echo '<option value="'. $pjval->cust_id .'" selected>'. $pjval->cust_nama .'</option>';
                                                        } else {
                                                            echo '<option value="'. $pjval->cust_id .'">'. $pjval->cust_nama .'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Tanggal Kirim <font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date("Y-m-d", strtotime($get_voucher->tglkirim)) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">No Kirim <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="nokirim" id="nokirim" value="<?= $get_voucher->nokir ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label">Catatan <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <textarea rows="3" style="resize:none" type="text" class="form-control" name="keterangan" id="keterangan"><?= $get_voucher->ket ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>	
                </div>

                <div class="row" style="padding:0 25px 0 25px">
                    <div class="col-md-12">                           	
                        <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="text-align:center;width:50%">Nomor Voucher</th>
                                    <th style="text-align:center;width:45%">Nominal Voucher RP</th>       
                                </tr>  									
                            </thead>						
                            <tbody>
                                <?php foreach($listv as $lvkey => $lvval): ?>
                                <tr class="listexist">	
                                    <td style="width:5%">
                                        <button class="btn btn-danger" type="button" onclick="deletevoucher('<?= $lvval->novoucher ?>')"><i class="fa fa-trash"></i></button>
                                    </td>												   
                                    <td style="width:50%">
                                        <input type="text" class="form-control" value="<?= $lvval->novoucher ?>" id="novoucher" readonly>
                                    </td>
                                    <td style="width:45%">
                                        <input type="number" class="form-control" value="<?= $lvval->nominal ?>" id="nominal" readonly>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="row" style="padding:5px 0 0 0">
                            <div class="col-xs-9">
                                <div class="wells">
                                    <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                    <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
                                </div>															
                            </div>
                        </div>
                    </div>
                </div>

                <div style="padding:10px 25px 25px 25px;width:auto">
                    <button class="btn btn-primary" type="button" id="newvoucher"><i class="fa fa-edit"></i> Teruskan Edit</button>
                    <button class="btn btn-primary" type="button" id="savevoucher"><i class="fa fa-save"></i> Simpan</button>
                    <a class="btn red" href="<?php echo base_url('pembuatan_voucher/')?>">
                        <i class="fa fa-undo"></i><b> KEMBALI </b>
                    </a>
                    <button class="btn btn-warning" type="button" id="cetak"><i class="fa fa-print"></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>

<?php
    $this->load->view('template/footer');
?>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

<script>
    $(window).on("load", function(){
        // sequentNokirim();
        $('#formvoucher').trigger("reset");
    });

    $("#newvoucher").hide();
    $("#cetak").hide();

    var jenisvouc = $("#jenisvouc").val();
    var keterangan = $("#keterangan").val();

    var table = document.getElementById("datatable");
    var tbodyRowCount = table.tBodies[0].rows.length;
    var idrow  = tbodyRowCount+1;
    var idrow2 = tbodyRowCount+1;

    var rowexist = $(".listexist").length + 1;

    function tambah(){
        var x=document.getElementById('datatable').insertRow(idrow);
        var td1=x.insertCell(0);
        var td2=x.insertCell(1);

        td1.colSpan  ="2";
        td1.innerHTML="<input name='novoucher[]' id='novoucher' type='text' class='form-control'>";
        td2.innerHTML="<input name='nominal[]' id='nominal' type='number' class='form-control'>";
        idrow++;
    }

    function hapus(){
		if(idrow>rowexist){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
		}
	}

    function deletevoucher(id){
        $.ajax({
            url : "/pembuatan_voucher/hapus_voucher/" + id,
            type : "POST",
            dataType : "JSON",
            success: function(data){
                if(data.status == 0){
                    swal({
                        title: "Kesalahan",
                        html: "Voucher Sudah Terjual atau Terpakai",
                        type: "warning",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#aaa"
                    });
                } else {
                    location.reload();
                }
            },
            error: function (data, xhr, ajaxOptions, thrownError){
                if(data.status == 0){
                    swal({
                        html: "Voucher Gagal Di Hapus",
                        type: "Error",
                        confirmButtonText: "Coba Lagi" 
                    });
                }
            }
        });
    }

    /*function sequentNokirim(){
        var cabval = $("#cabang").val();
        $.ajax({
            url: "/pembuatan_voucher/get_last_number/",
            type: "GET",
            dataType: "JSON",
            data: {cabang: cabval},
            success: function(data){
                $("#nokirim").val(data.nokir);
                $("#hidenokirim").val(data.nokir);
            },
            error: function (data,xhr, ajaxOptions, thrownError) {
                console.error(data.nokir);
            }
        });
    }*/

    $("#cetak").on("click", function(e){
        e.preventDefault();
        var recent_voucher = $("#formvoucher #nokirim").val();
        window.open("<?= base_url() ?>pembuatan_voucher/cetak/?no_kirim=" + recent_voucher, "_blank");
    });

    $("#savevoucher").on("click", function(e){
        e.preventDefault();
        var nokir = $("#formvoucher #nokirim").val();
        var jenisvouc = $("#jenisvouc").val();
        var keterangan = $("#keterangan").val();
        var novoucher = $("#novoucher").val();
        var nominal = $("#nominal").val();
        console.log($("#formvoucher").serialize());
        
        /*if(jenisvouc == "" || keterangan == "" || novoucher == "" || nominal == ""){
            swal({
                html: "Data masih kosong",
                type: "error",
                confirmButtonText: "Ok" 
            });
        } else { */
            $.ajax({
                url: "/pembuatan_voucher/update",
                type: "POST",
                data: $("#formvoucher").serialize(),
                success: function(data){
                    //sequentNokirim();
                    swal({
                        html: "Voucher Berhasil Di Simpan",
                        type: "success",
                        confirmButtonText: "Ok" 
                    });
                    $("#savevoucher").hide();
                    $("#newvoucher").show();
                    $("#cetak").show();
                    // $("#formvoucher input").prop("disabled", true);
                    // $("#formvoucher select").prop("disabled", true);
                    // $("#formvoucher textarea").prop("disabled", true);
                    // $(".wells").hide();
                },
                error: function (data, xhr, ajaxOptions, thrownError){
                    if(data.status == 0){
                        swal({
                            html: "Voucher Gagal Di Simpan",
                            type: "Error",
                            confirmButtonText: "Coba Lagi" 
                        });
                    }
                }
            });
        
    });

    $("#newvoucher").on("click", function(){
        // sequentNokirim();
        // hapus();
        // $('#formvoucher').trigger("reset");
        $("#savevoucher").show();
        $("#cetak").hide();
        $("#newvoucher").hide();
        $(".wells").show();
        location.reload();
        // $("#formvoucher input").prop("disabled", false);
        // $("#formvoucher select").prop("disabled", false);
        // $("#formvoucher textarea").prop("disabled", false);
    });
</script>
</body>
</html>