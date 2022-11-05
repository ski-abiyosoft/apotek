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
                <a class="title-white" href="#">Mutasi Pendapatan Tunai</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet" style="padding:0 0 20px 0">
            <div class="portlet-title">
                <div class="caption">
                    Daftar Mutasi -
                    <span>
                        <b><?= $periode ?></b>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">
                    <?php 
                    $cek =  $this->session->userdata('user_level'); 
                    if($cek==0){?> 
                    <?php }else{ ?>

                        <a href="<?php echo base_url()?>mutasi_pendapatan_kasir/entri" class="btn btn-success">
                        <i class="fa fa-plus fa-fw"></i>&nbsp;
                        Mutasi Baru</a>

                    <?php } ?>
                    </div>
                    <div class="btn-group pull-right">
                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i
                                class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu pull-right">
                            <li><a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a></li>

                        </ul>
                    </div>

                </div>
                <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th style="text-align: center;width:15%" class="title-white">Cab.</th>
                            <th style="text-align: center;width:15%" class="title-white">User ID</th>
                            <th style="text-align: center;width:20%" class="title-white">No Mutasi</th>
                            <th style="text-align: center;width:15%" class="title-white">Tanggal Mutasi</th>
                            <th style="text-align: center;width:20%" class="title-white">Mutasi Rp</th>
                            <th style="text-align: center;width:10%" class="title-white">Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listv as $lvkey => $lval): ?>
                        <tr>
                            <td><?= $lval->koders ?></td>
                            <td><?= $lval->username ?></td>
                            <td><?= $lval->nomutasi ?></td>
                            <td><?= date("d/m/Y", strtotime($lval->tglmutasi)) ?></td>
                            <td><?= number_format($lval->mutasirp, 2, '.', ',') ?></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" type="button"
                                    onclick="window.open('/mutasi_pendapatan_kasir/detail/<?= $lval->nomutasi ?>', 'open')"><i
                                        class="glyphicon glyphicon-eye-open"></i></i></button>
                                <button class="btn btn-warning btn-sm" type="button"
                                    onclick="window.open('/mutasi_pendapatan_kasir/cetak/<?= $lval->nomutasi ?>', 'open')"><i
                                        class="fa fa-print"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
$(document).ready(function() {
    $("#table").DataTable({});

    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });
});

function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var str = tgl1 + '~' + tgl2;
    location.href = "<?php echo base_url();?>mutasi_pendapatan_kasir/filter/" + str;
}
</script>