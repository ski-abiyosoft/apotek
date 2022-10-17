<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">
		<span class="title-unit">
				&nbsp;<?php echo $this->session->userdata('unit'); ?> 
			</span>
			- 
			<span class="title-web">Aktiva Tetap <small>Laporan</small>
		</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="<?php echo base_url()?>dashboard">
					Awal
				</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="">
					Aktiva Tetap
				</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="">
					Laporan
				</a>
			</li>
		</ul>
	</div>
		<div class="tab-pane" id="tab_1_3">
			<div class="row profile-account">
				<div class="col-md-3">
					<ul class="ver-inline-menu tabbable margin-bottom-10">
						<li class="active">
							<a data-toggle="tab" href="#tab_1-1" data-laporan="1">
								<i class="fa fa-angle-double-right"></i> Daftar Aktiva Tetap
							</a>
							<span class="after">
							</span>
						</li>
						<li>
							<a data-toggle="tab" href="#tab_2-2">
								<i class="fa fa-angle-double-right"></i> Histori Penyusutan
							</a>
						</li>
						<li>
							<a data-toggle="tab" href="#tab_3-3">
								<i class="fa fa-angle-double-right"></i> Daftar Aktiva yang Telah Habis
							</a>
						</li>
					</ul>
				</div>
				<div class="col-md-9">
					<div class="tab-content">
						<div id="tab_1-1" class="tab-pane active">
							<p><strong>Laporan Daftar Aktiva Tetap</strong></p>
							<div class="margiv-top-10">
								<a class="btn green print_laporan" href="#report" id="1" data-toggle="modal" data-laporan="laporan_daftar_aktiva_tetap">Cetak Laporan</a> 
								<a class="btn red export_laporan" id="1" data-toggle="modal">Export Ke Excel</a> 
							</div>
						</div>
						<div id="tab_2-2" class="tab-pane">
							<div class="margiv-top-10">
								<p><strong>Laporan Histori Aktiva Tetap</strong></p>
								<a class="btn green print_laporan" href="#report" id="2" data-toggle="modal" data-laporan="laporan_histori_penyusutan_aktiva_tetap">Cetak Laporan</a> 
								<a class="btn red export_laporan" id="2" data-toggle="modal">Export Ke Excel</a> 
							</div>
						</div>
						<div id="tab_3-3" class="tab-pane">
							<div class="margiv-top-10">
								<p><strong>Laporan Aktiva yang Telah Habis</strong></p>
								<a class="btn green print_laporan" href="#report" id="3" data-toggle="modal" data-laporan="laporan_daftar_aktiva_yang_telah_habis">Cetak Laporan</a> 
								<a class="btn red export_laporan" id="2" data-toggle="modal">Export Ke Excel</a> 
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php  
   $this->load->view('template/footer');
   $this->load->view('template/v_report');
  
?>

<script>
    
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
			$('.modal-title').text('LAPORAN AKTIVA TETAP');
			var param = $(this).data("laporan")
			$("#simkeureport").html('<iframe src="<?php echo base_url();?>at_lap/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
		
		$('.export_laporan').on("click", function(){		
		if(this.id==1)		
		{	
		var param = this.id;
		} else
		if(this.id==2)		
		{	
		var param = this.id+'~'+$('#pasar2').val()+'~'+$('#bulan').val()+'~'+$('#tahun').val();
		}
		
		location.href="<?php echo base_url()?>at_lap/export/"+param;
		});	
		
		
	});
	
	
	
	 jQuery(document).ready(function() {
        ComponentsPickers.init();
        });
	
	
	
</script>
