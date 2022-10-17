<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <?php if($this->session->flashdata("not_found")): ?>
    <script>alert("Pembelian Yang Ingin Dicetak Tidak Ditemukan")</script>
    <?php endif; ?>
    <div class="row">
		<div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?= $this->session->userdata("unit"); ?> 
                </span>
                - 
                <span class="title-web">Voucher <small>Penjualan Voucher</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white">Voucher</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="#" class="title-white">Penjualan Voucher</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
		<div class="col-md-12">
		    <div class="portlet" style="padding:0 0 50px 0">
				<div class="portlet-title">
					<div class="caption">
                        Daftar Penjualan Voucher -
                        <span><b>
                        <?= $periode ?></b>
                        </span>
                    </div>
                </div>
                <div class="portlet-body">  
                    <div class="table-toolbar">
                            <button class="btn btn-success" type="button" onclick="location.href='/pembayaran_voucher/entri'"><i class="fa fa-plus fa-fw"></i>&nbsp;Data Baru</button>
                            <div class="btn-group pull-right">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a></li>	
                                        
                                    </ul>
                                </div>
                            </div>
                            <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">							
                               <thead>
                                     <tr class="page-breadcrumb breadcrumb">
                                        <th style="text-align: center;width:5%" class="title-white">Cab.</th>
                                        <th style="text-align: center;width:15%" class="title-white">User</th>
                                        <th style="text-align: center;width:25%" class="title-white">No Transaksi</th>
                                        <th style="text-align: center;width:25%" class="title-white">Member</th>
                                        <th style="text-align: center;width:20%" class="title-white">Nominal Rp</th>
                                        <th style="text-align: center;width:15%" class="title-white">Aksi</th>
                                     </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listv as $lkey => $lval): ?>
                                        <tr>
                                            <td><?= $lval->koders ?></td>
                                            <td><?= $lval->namauser ?></td>
                                            <td><?= $lval->nojual ?></td>
                                            <td><?= $lval->namapas ?></td>
                                            <td><?= number_format($lval->total, 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm" type="button" id="" onclick="location.href='/pembayaran_voucher/edit/<?= $lval->nojual ?>'"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" type="button" id="" onclick="hapus('<?= $lval->nojual ?>')"><i class="fa fa-trash"></i></button>
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
        $("#table").DataTable({
            order:[[2,"DESC"]],
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",
            todayBtn: true,
            todayHighlight: true,  
        });
    });

    function hapus(id){
        swal({
            html: "Apakah Yakin Kamu Ingin Menghapus Pembayaran ini ?",
            type: "question",
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Hapus",
            cancelButtonColor: "#aaa",
            cancelButtonText: "Batal",
            showCancelButton: true
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "/pembayaran_voucher/hapus/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                        if(data.status == 0){
                            swal({
                                html: "Pembayaran Gagal Dihapus 1",
                                type: "error",
                                confirmButtonText: "Ok" 
                            });
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        // alert(xhr.status);
                        // alert(thrownError);
                        swal({
                            html: "Pembayaran Gagal Dihapus 0",
                            type: "error",
                            confirmButtonText: "Ok" 
                        });
                    }
                });
            }
        });
    }

    function filterdata(){
        var tgl1 = document.getElementById("tanggal1").value;
        var tgl2 = document.getElementById("tanggal2").value;
        var str  = tgl1+'~'+tgl2; 
        location.href = "<?php echo base_url();?>pembayaran_voucher/filter/"+str;
    }
</script>