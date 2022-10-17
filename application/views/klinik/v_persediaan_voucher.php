<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <div class="row">
		<div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?= $this->session->userdata("unit"); ?> 
                </span>
                - 
                <span class="title-web">Voucher <small>Persediaan Voucher</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white">Klinik</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white">Persediaan Voucher</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
		<div class="col-md-12">
		    <div class="portlet" style="padding:0 0 50px 0">
				<div class="portlet-title">
					<div class="caption">
                        Daftar  Persediaan Voucher -
                        <span><b>
                        <?= $periode ?></b>
                        </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <!--
                        <form method="POST" style="display:inline-block">
                            <select class="form-control" type="text" id="filtercabang" onchange="filterCabang()">
                                <option value="all">Semua Cabang</option>
                                <?php
                                    foreach($cabang as $cbkey => $cbval){
                                        echo "<option value='$cbval->id'>". str_replace("KLINIK ESTETIKA dr. Affandi ", "", $cbval->text) ."</option>";
                                    }
                                ?>
                            </select>
                        </form>
                        -->
                        <div style="display:block;position:relative">
                            <div class="btn-group" style="margin:0 0 20px 0;position:absolute;right:0">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a></li>	
                                </ul>
                            </div>
                            <div class="btn-group"></div>
                        </div>
                        <br />
                        <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">							
                            <thead>
                                    <tr class="page-breadcrumb breadcrumb">
                                    <th style="text-align: center;width:5%" class="title-white">No Voucher</th>
                                    <th style="text-align: center;width:5%" class="title-white">Nominal</th>
                                    <th style="text-align: center;width:5%" class="title-white">Tanggal Kirim</th>
                                    <th style="text-align: center;width:5%" class="title-white">Voucher Owner</th>
                                    <th style="text-align: center;width:5%" class="title-white">Tanggal Jual</th>
                                    <th style="text-align: center;width:5%" class="title-white">Tanggal Pakai</th>
                                    <th style="text-align: center;width:5%" class="title-white">Cabang Pakai</th>
                                    <th style="text-align: center;width:5%" class="title-white">Status</th>
                                    </tr>
                            </thead>
                            <tbody id="tablelist">
                                <?php 
                                    foreach($listv as $lvkey => $lvval){
                                        if($lvval->terjual > 0){
                                ?>
                                <!-- Terjual -->
                                <tr>
                                    <td><?= $lvval->novoucher ?></td>
                                    <td><?= number_format($lvval->nominal, 0, ',', '.') ?></td>
                                    <td><?= date("d/m/Y", strtotime($lvval->tglkirim)) ?></td>
                                    <td><?= $lvval->cabangvoc ?></td>
                                    <td><?= ($lvval->tgjual == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($lvval->tgjual)) ?></td>
                                    <td><?= ($lvval->tglpakai == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($lvval->tglpakai)) ?></td>
                                    <td><?= $lvval->cabangpakai ?></td>
                                    <td class="text-primary text-center" style="font-weight:bold">Terjual</td>
                                </tr>
                                <?php 
                                    } else
                                        if($lvval->terpakai > 0){
                                ?>
                                <!-- Terpakai -->
                                <tr>
                                    <td><?= $lvval->novoucher ?></td>
                                    <td><?= number_format($lvval->nominal, 0, ',', '.') ?></td>
                                    <td><?= date("d/m/Y", strtotime($lvval->tglkirim)) ?></td>
                                    <td><?= $lvval->cabangvoc ?></td>
                                    <td><?= ($lvval->tgjual == "0000-00-00 00:00:00")? "" : date("d-m-Y", strtotime($lvval->tgjual)) ?></td>
                                    <td><?= ($lvval->tglpakai == "0000-00-00 00:00:00")? "" : date("d-m-Y", strtotime($lvval->tglpakai)) ?></td>
                                    <td><?= $lvval->cabangpakai ?></td>
                                    <td class="text-warning text-center" style="font-weight:bold">Terpakai</td>
                                </tr>
                                <?php } else { ?>
                                <!-- Tersedia -->
                                <tr>
                                    <td><?= $lvval->novoucher ?></td>
                                    <td><?= number_format($lvval->nominal, 0, ',', '.') ?></td>
                                    <td><?= date("d/m/Y", strtotime($lvval->tglkirim)) ?></td>
                                    <td><?= $lvval->cabangvoc ?></td>
                                    <td><?= ($lvval->tgjual == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($lvval->tgjual)) ?></td>
                                    <td><?= ($lvval->tglpakai == "0000-00-00 00:00:00")? "" : date("d/m/Y", strtotime($lvval->tglpakai)) ?></td>
                                    <td><?= $lvval->cabangpakai ?></td>
                                    <td class="text-success text-center" style="font-weight:bold">Tersedia</td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
					</div>
				</div>
			</div>
        </div>
    </div>
<?php
    $this->load->view('template/footer');
    $this->load->view('template/v_report');
    $this->load->view('template/v_periode');
?>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

<script>
    $("#table").DataTable({});
    $(document).ready(function() {
        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",
            todayBtn: true,
            todayHighlight: true,  
        });
    });

    function filterCabang(){
        var result, stats;
        var cabang = $("#filtercabang").val();
        $.ajax({
            url: "/persediaan_voucher/cabang/"+ cabang,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                if(data.status == 0){
                    $("#tablelist").html("<tr><td colspan='8' class='text-center text-danger'><b>Tidak Ada Data Untuk Cabang : "+ cabang +"</b></td></tr>");
                } else {
                    $.each(data,function(key,val){
                        result += "<tr><td>"+ val.novoucher +"</td><td>"+ val.nominal +"</td><td>"+ val.tglkirim +"</td><td>"+ val.cabangvoc +"</td><td>"+ val.tgjual +"</td><td>"+ val.tglpakai +"</td><td>"+ val.cabangpakai +"</td><td>"+ val.status +"</td></tr>";
                        $('#tablelist').html(result);
                    });
                }
            }
        });
    }

    function filterdata(){
        var tgl1 = document.getElementById("tanggal1").value;
        var tgl2 = document.getElementById("tanggal2").value;
        var str  = tgl1+'~'+tgl2; 
        location.href = "<?php echo base_url();?>persediaan_voucher/filter/"+str;
    }
</script>