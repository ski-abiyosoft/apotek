
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	<!-- <link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/> -->
	<!-- <link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css"/> -->
	
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Logistik <small>>BAPB</small>
					</h3>
                         <ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="">
                               Logistik
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="<?php echo base_url();?>Logistik_bapb">
                               Data BAPB
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Daftar BAPB -
								<span><b>
								<?php 
								   echo $periode;?> </b>
                                </span>
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								
								
								<div class="btn-group">
								   <?php if($akses->uadd){?>
								    <a href="<?php echo base_url()?>Logistik_bapb/entri" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Transaksi Baru
									</a>
								   <?php } ?>
								</div>	
								
								<div class="btn-group pull-right">
									<button class="btn dropdown-toggle"  data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>											
											<a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>										
										</li>	
										<li>										
                                            <a href="#" class="btnExport_1">Export ke Excel</a>	
										</li>									
									</ul>
								</div>

							</div>
							<table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                            <thead class="breadcrumb">
                                     <tr>
                                        <th class="title-white" style="text-align: center;">Cab</th>
                                         <th class="title-white" style="text-align: center;">User ID</th>
									     <!--th style="text-align: center">Register</th-->
                                         <th class="title-white" style="text-align: center">No. BAPB</th>
										 <th class="title-white" style="text-align: center">Tanggal</th>
                                         <th class="title-white" style="text-align: center">Vendor</th>
                                         <th class="title-white" style="text-align: center">No. SJ</th>
										 <th class="title-white" style="text-align: center">Gudang</th>
                                         <th class="title-white" style="text-align: center">Status</th>                                         
                                         <th class="title-white" style="text-align: center">Aksi</th>              
                                     </tr>
                                     </thead>
                                     <tbody>

                                     </tbody>
                                    

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_periode'); 
?>
<!-- 	

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
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>" type="text/javascript"></script> -->

<script type="text/javascript">
    
var keuangan_keluar_list;

// function currencyFormat (num) {
//     //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
//     return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
// }

$(document).ready(function() {
		
		//datatables
    // $('#loading').modal('show');

	var display = $.fn.dataTable.render.number( '.', ',', 2, ' ' ).display;
	keuangan_keluar_list = $('#keuangan-keluar-list').DataTable({ 
		"responsive": true,		
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.

		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('Logistik_bapb/ajax_list/1');?>",
			"type": "POST"
		},
		
        "scrollCollapse": false,
        "paging":         true,
		"oLanguage": {
					"sEmptyTable": "Tidak ada data",
					"sInfoEmpty": "",
					"sInfoFiltered": " - Dipilih dari _TOTAL_ data",
					"sSearch": "Pencarian Data : ",
					"sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
					"sLengthMenu": "_MENU_ Baris",
					"sZeroRecords": "Tidak ada data",
					"oPaginate": {
						"sPrevious": "Sebelumnya",
						"sNext": "Berikutnya"
					}
				},
				
		"aLengthMenu": [
					[5, 15, 20, -1],
					[5, 15, 20, "Semua"] // change per page values here
				],		

		//Set column definition initialisation properties.
		"columnDefs": [
		{ 
			"targets": [ -1 ], //last column
			"orderable": false, //set not orderable
		},
		{  
			"targets": [0,1,2,4,5,6], //last column
			"orderable": true, //set not orderable
			
			"className" : "text-center",				
			
			},
		
		
		],
			

	});
    
    // setTimeout(function() { $('#loading').modal('hide'); }, 2000);

    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});

function reload_table()
{
    keuangan_keluar_list.ajax.reload(null,false); //reload datatable ajax 
}
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = '2~'+tgl1+'~'+tgl2;     	
	keuangan_keluar_list.ajax.url("<?php echo base_url('Logistik_bapb/ajax_list/')?>"+str).load();	
}	

function delete_data(id) 
{
	swal({
		title: 'BAPB',
		html: "Data ini akan dihapus ? <strong><p>"+id,
		type: 'warning',
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger m-l-10',
		confirmButtonText: 'Ya, hapus',
		cancelButtonText: 'Batal'
	}).then(function () {
		$.ajax({
			type : 'POST',
			dataType : "json",
			url : '<?php echo base_url('logistik_bapb')?>/hapus/?terima_no='+id,
			data : {id : id},
			success:function(response){
				if(response.status == '1') {
					swal({
							title: "",
							html: "Data sudah dihapus...",
							type: "success",
							confirmButtonText: "OK" 
					});	
				} else {
					swal(
						'',
						'Data gagal dihapus.',
						''
					)
				}
				//dataTable.ajax.reload(null,false);
				reload_table();
			}
		});
	});
}
	

jQuery(document).ready(function() {       
//    TableEditable.init();
//    ComponentsPickers.init();
  
});
</script>
