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
			&nbsp;-
			<span class="title-web">ABSEN <small>Laporan Absensi</small>
		</h3>
		<ul class="page-breadcrumb breadcrumb">

			<li>
				<i style="color:white;" class="fa fa-home"></i>
				<a class="title-white" href="../home">
					Home
				</a>
				<i style="color:white;" class="fa fa-angle-right"></i>
			</li>
			<li>
				<a class="title-white" href="#">
					Absen
				</a>
				<i style="color:white;" class="fa fa-angle-right"></i>
			</li>
			<li>
				<a class="title-white" href="#">
					Laporan
				</a>
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-12">

		<br>

		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i><b>Laporan</b>
				</div>

			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
					<div class="form-body">

						<!-- LAP HARIAN -->
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-5 control-label"><b>LAPORAN HARIAN</b></label>
									

								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">TANGGAL LAPORAN</label>
									<div class="col-md-2">

										<input id="tanggalh" name="tanggalh" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="" />

									</div>

								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<a class="btn btn-sm blue print_laporan" onclick="javascript:window.open(cetak_harian(0),'_blank');" ><i title="CETAK PDF" class="glyphicon glyphicon-file"></i><b> LAYAR </b></a>
 
								<br>
								<br>
								<br>
							</div>
						</div>

						<!-- LAP BULANAN -->

						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-5 control-label"><b>LAPORAN BULANAN</b></label>
									

								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">Mulai</label>
									<div class="col-md-2">

										<input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="" />

									</div>

								</div>
							</div>

						</div>
						<!-- <div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">Bulan</label>
									<div class="col-md-3">
									<select type="text" class="form-control" id="bln" name="bln" >
									<?php
										$bln        = date("M");
                                        foreach($bulan as $bulann){
										echo '<option value='.$bulann->id.' >'. $bulann->bulan .'</option>';
									} ?>
									
									</select>
									</div>

									<label class="col-md-2 control-label">Tahun</label>
									<div class="col-md-2">

									<select type="text" class="form-control" id="thn" name="thn" >
									<?php 
										$thang        = date("Y");
										$thang_maks   = $thang + 2 ;
										$thang_min    = $thang - 2 ;

										for ($th=$thang_min ; $th<=$thang_maks ; $th++)
										{
											if ($th==$thang) {
												echo "<option selected value=$th>$thang</option>";
												}
											else {	
												echo "<option value=$th>$th</option>";
											}
										}
									?>   
									
									</select>

									</div>

								</div>
							</div>

						</div> -->
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">

									<label class="col-md-3 control-label">s/d</label>
									<div class="col-md-2">
										<input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

									</div>

								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<a class="btn btn-sm blue print_laporan" onclick="javascript:window.open(_urlcetak(0),'_blank');" ><i title="CETAK PDF" class="glyphicon glyphicon-file"></i><b> LAYAR </b></a>

								<a class="btn btn-sm red print_laporan" onclick="javascript:window.open(_urlcetak(1),'_blank');"><i title="CETAK PDF" class="glyphicon glyphicon-print"></i><b> PDF </b></a>

								<a class="btn btn-sm green print_laporan" onclick="javascript:window.open(_urlcetak(2),'_blank');"><i title="CETAK PDF" class="fa fa-download"></i><b> EXCEL </b></a>
								<br>
								<br>
								<br>
								<br>
							</div>
						</div>
					</div>
						

				</form>
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
	cabb();

	function cabb() {
		$.ajax({
			url: "<?php echo base_url(); ?>app/search_cabang2",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				console.log(data.text);		
				$('#cabang').val(data.id);

			}
		});


	}

	function cetak_harian(cek) {
		var baseurl   = "<?php echo base_url() ?>";
		var tglh      = $('[name="tanggalh"]').val();
		var param     = '?tglh=' + tglh + '&cek=' + cek;

		return baseurl + 'Absen_laporan/cetak_absen_h/' + param;
	}
	
	function _urlcetak(cek) {
		var baseurl = "<?php echo base_url() ?>";
		var tgl1 = $('[name="tanggal1"]').val();
		var tgl2 = $('[name="tanggal2"]').val();
		var param = '?tgl1=' + tgl1 + '&tgl2=' + tgl2+ '&cek=' + cek;

		return baseurl + 'Absen_laporan/cetak_absen/' + param;
	}
</script>