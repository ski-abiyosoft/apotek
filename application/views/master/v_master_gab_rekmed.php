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
			<span class="title-web">ABSEN <small>Gabung Rekmed</small>
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
					Gabung Rekmed
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
					<i class="fa fa-reorder"></i><b>Gabung RekmedGabung Rekmed</b>
				</div>

			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
						<table width="100%" border="0">
							<tr>
								<td colspan="3">
									&nbsp;
								</td>
							</tr>
							<tr>
								
							</tr>
							<tr>
								<td colspan="3">
									&nbsp;
								</td>
							</tr>
							<tr>
								<td width="20%" align="center" >
									<label class="control-label"><b>CETAK PASIEN DATA DOBEL </b></label> </td>
								<td width="10%"><b>: &nbsp;&nbsp;</b> 
									<a class="btn btn-sm blue print_laporan" onclick="urlcetak(0);">
									<i title="CETAK PDF" class="fa fa-print"></i>
									<b> LAYAR </b>
									</a>	
								</td>
								<td width="70%">
									<a class="btn btn-sm green print_laporan" onclick="urlcetak(2);">
									<i title="CETAK PDF" class="fa fa-file"></i>
									<b> EXCEL </b>
									</a>	
								</td>
							</tr>
							<tr>
								<td colspan="3" >&nbsp;<br><br><br></td>
							</tr>
							<tr>
								<td  colspan="3" align="center" >
									<label class="control-label"><U><b>PENGGABUNGAN REKMED DENGAN PASIEN YG SAMA</b></U></label>
									<br><br></td>
							</tr>
							<tr>
								<td colspan="3" >
									<label class="col-md-3 control-label">REKMED 1</label>
									<div class="col-md-2">

										<input id="rm1" name="rm1" class="form-control input-medium" type="text" value="" placeholder="" />

									</div></td>
							</tr>
							<tr>
								<td colspan="3" >
									<label class="col-md-5 control-label"><b>DI GABUNG KE </b></label><br><br></td>
							</tr>
							<tr>
								<td colspan="3" >
									<label class="col-md-3 control-label">REKMED 2</label>
									<div class="col-md-2">

										<input id="rm2" name="rm2" class="form-control input-medium" type="text" value="" placeholder="" />

									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" >&nbsp;</td>
							</tr>
							<tr>
								<td  colspan="3" align="center">
									<a class="btn btn-sm red " onclick="gabb();" ><i title="CETAK PDF" class="fa fa-check"></i><b> GABUNG </b></a>

								</td>
							</tr>
							
							<tr>
								<td colspan="3" >&nbsp;<br><br></td>
							</tr>
						</table>
						<!-- LAP HARIAN -->
						

						

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
	function urlcetak(cekpdf) {

		var baseurl = "<?= base_url() ?>";
		var var1    = baseurl+'gabung_rekmed/cetak_data/'+cekpdf;

		window.open(var1,'_blank');

	}

	function alltrim(kata){
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	}

	
	function gabb() {
		
		var rm1      = alltrim($('[name="rm1"]').val());
		var rm2      = alltrim($('[name="rm2"]').val());

		swal({
        title: 'GABUNG DATA',
        html: 'Yakin Ingin Gabung Data Ini ??' ,
        type: 'question',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-success',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, update',
        cancelButtonText: 'Batal'
		}).then(function() {

			$.ajax({
				type        : 'POST',
				dataType    : 'json',
				data        : {rm1:rm1, rm2:rm2},
				url         : '<?= base_url('gabung_rekmed')?>/gabdat',
				success: function(data) {
					
					if(data.status==1){
						swal({
							title: "GABUNG DATA " ,
							html: "Berhasil diupdate",
							type: "success",
							confirmButtonText: "OK"
						}).then((value) => {
							location.href = "<?= site_url('Gabung_rekmed') ?>";
						});
					}else{
						swal({
                            title: "GABUNG DATA",
                            html: "Gagal dilakukan !<br>Cek Lagi",
                            type: "error",
                            confirmButtonText: "Ok"
                        });
					}
				}
			});
				
		});

		// var baseurl   = "<?php echo base_url() ?>";
		// var tgl1      = $('[name="tanggal1"]').val();
		// var tgl2      = $('[name="tanggal2"]').val();
		// var param     = '?tgl1=' + tgl1 + '&tgl2=' + tgl2+ '&cek=' + cek;

		// return baseurl + 'Absen_laporan/cetak_absen/' + param;
	}
</script>