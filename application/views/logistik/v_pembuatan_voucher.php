<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>
    
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <?php if($this->session->flashdata("not_found")): ?>
    <script>alert("Voucher Yang Ingin Dicetak Tidak Ditemukan")</script>
    <?php endif; ?>
    
    <div class="row">
		<div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?= $this->session->userdata("unit") ?> 
                </span>
                - 
                <span class="title-web">Voucher <small>Pembuatan Voucher</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:white;"></i>&nbsp;<a href="<?php echo base_url();?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:white;"></i></li>
                <li><a href="#" class="title-white">Logistik</a>&nbsp;<i class="fa fa-angle-right" style="color:white;"></i></li>
                <li><a href="#" class="title-white">Pembuatan Voucher</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
		<div class="col-md-12">
		    <div class="portlet" style="padding:0 0 20px 0">
				<div class="portlet-title">
					<div class="caption">
                        Daftar Pembuatan Voucher -
                        <span>
                            <b><?= $periode ?></b>
                        </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">
                            <a href="<?php echo base_url()?>pembuatan_voucher/entri" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>&nbsp;Voucher Baru</a>
						</div>
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
                                         <th style="text-align: center;width:15%" class="title-white">User ID</th>
									     <th style="text-align: center;width:20%" class="title-white">No Kirim</th>
									     <th style="text-align: center;width:15%" class="title-white">Tanggal Kirim</th>
										 <th style="text-align: center;width:25%" class="title-white">Jenis Voucher</th>
										 <th style="text-align: center;width:20%" class="title-white">Aksi</th>

                                     </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listv as $lvkey => $lvval): ?>
                                        <tr >
                                            <td><?= $lvval->koders ?></td>
                                            <td><?= $lvval->user_id ?></td>
                                            <td><?= $lvval->nokir ?></td>
                                            <td><?= date("d/m/Y", strtotime(str_replace(" 00:00:00","", $lvval->tglkirim))) ?></td>
                                            <td><?= $lvval->cust_nama ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm" type="button" onclick="location.href='/pembuatan_voucher/edit/<?= $lvval->nokir ?>'"><i class="fa fa-edit"></i></button>&nbsp;
                                                <button class="btn btn-danger btn-sm" type="button" onclick="deletevoucher('<?= $lvval->nokir ?>')"><i class="fa fa-trash"></i></button>&nbsp;
                                                <button class="btn btn-warning btn-sm" type="button" onclick="window.open('<?= base_url() ?>/pembuatan_voucher/cetak/?no_kirim=<?= $lvval->nokir ?>', '_blank')"><i class="fa fa-print"></i></button>
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
        "order": [[ 0, "desc" ]],
        
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
function deletevoucher(id){
    swal({
        title: "Apakah Anda Yakin?",
        text: "Voucher Tidak Dapat Dikembalikan Setelah Dihapus",
        type: "question",
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Hapus",
        cancelButtonColor: "#aaa",
        cancelButtonText: "Batal",
        showCancelButton: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url : "/pembuatan_voucher/check_hapus/" + id,
                type : "POST",
                dataType : "JSON",
                success: function(check){
                    if(check.status == 1){
                        $.ajax({
                            url : "/pembuatan_voucher/hapus/" + id,
                            type : "POST",
                            dataType : "JSON",
                            success : function(data){
                                location.reload();
                            },
                            error : function(jqXHR, textStatus, errorThrown){
                                alert('Gagal menghapus data');
                            }
                        });
                    } else {
                        swal({
                            title: "Gagal Menghapus",
                            text: "Terdapat Voucher Yang Terjual atau Tepakai",
                            type: "warning",
                            confirmButtonColor: "#aaa",
                            confirmButtonText: "Kembali",
                            showCancelButton: false,
                        })
                    }
                },
                error : function(jqXHR, textStatus, errorThrown){
                    alert('Gagal menghapus data');
                }
            });
        }
    });
}
    /*
$(document).ready(function() {
	$("#table").DataTable({
		"ajax": {
			"url" : "/pembuatan_voucher/ajax_list/",
			"dataSrc" : ""
		},
        "columns" : [
            {"data" : "nokir"},
			{"data": "tglkirim"},
			{"data" : "namars"},
			{"data" : "cust_nama"},
            {"data" : "action",
                render : function(data, type, row) {
                    return "<a href='/pembuatan_voucher/edit/"+ data +"' class='btn btn-primary btn-sm'><i class='fa fa-edit fa-fw'></i>&nbsp; edit</a>&emsp;<form action='' method='POST' style='display:inline-block' ='deldata()'><input type='hidden' name='nokir' value='"+ data +"'><button class='btn btn-danger btn-sm' type='button'><i class='fa fa-trash'></i>&nbsp; Hapus</button></form>";
                }    
            }
        ]
	});

    //datepicker
});

function deldata(){
        swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
  if (willDelete) {
            $.ajax({
                        url : "<?php echo base_url('C_unit/delete')?>/" + id,
                        type : "POST",
                        dataType : "JSON",
                        success : function(data)
                        {
                            location.reload();
                        },
                        error : function(jqXHR, textStatus, errorThrown)
                        {
                            alert('Gagal menghapus data');
                        }
                 });
                }
        });
}
*/

function filterdata(){
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = tgl1+'~'+tgl2; 
	location.href = "<?php echo base_url();?>pembuatan_voucher/filter/"+str;
}
</script>
</body>
</html>