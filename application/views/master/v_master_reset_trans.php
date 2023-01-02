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
			<span class="title-web">RESET <small>RESET NO TRANSAKSI</small>
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
					RESET
				</a>
				<i style="color:white;" class="fa fa-angle-right"></i>
			</li>
			<li>
				<a class="title-white" href="#">
					Reset No transaksi
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
					<i class="fa fa-reorder"></i><b>RESET NO TRANSAKSI</b>
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
								<td  colspan="3" align="center" >
									<h3><b>RESET NO TRAKSAKSI -> ALL RSBR</b></h3>
									<br><br></td>
							</tr>
							<tr>
								<td colspan="3" >&nbsp;</td>
							</tr>
							<tr>
								<td  colspan="3" align="center">
									<button type="button" id="ress" onclick="resett();" class="btn btn-danger" readonly> <i title="RESET" class="fa fa-check"></i>
									<b>RESET NO TRANSAKSI</b></button>

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
	cek_button();

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
	
	function cek_button() {
		$.ajax({
			url: "<?php echo base_url(); ?>reset_transaksi/cek_button",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				// if(data.tgl=='12-31'){
					
				// }else{
				// 	document.getElementById("ress").disabled = true;

				// }
				if(data.jam > '23:01:00'){
					document.getElementById("ress").disabled = false;
				}else{
					document.getElementById("ress").disabled = true;
				}
	// 			var waktuMulai = $('#waktuMulai').val(),
    //       waktuSelesai = $('#waktuSelesai').val(),
    //    hours = waktuSelesai.split(':')[0] - waktuMulai.split(':')[0],
    //       minutes = waktuSelesai.split(':')[1] - waktuMulai.split(':')[1];
 
    //   if (waktuMulai <= "12:00:00" && waktuSelesai >= "13:00:00"){
    //     a = 1;
    //   }else {
    //     a = 0;
    //   }
				// alert(data.jam);
				// alert(data.tgl);

			}
		});
	}

	function urlcetak(cekpdf) {

		var baseurl = "<?= base_url() ?>";
		var var1    = baseurl+'reset_transaksi/cetak_data/'+cekpdf;

		window.open(var1,'_blank');

	}

	function alltrim(kata){
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	}

	
	function resett() {
		

		swal({
        title               : 'RESET DATA',
        html                : 'Yakin Ingin RESET Data Ini ??' ,
        type                : 'question',
        showCancelButton    : true,
        confirmButtonClass  : 'btn btn-success',
        cancelButtonClass   : 'btn btn-success',
        cancelButtonColor   : '#d33',
        confirmButtonText   : 'Ya, update',
        cancelButtonText    : 'Batal'
		}).then(function() {

			$.ajax({
				type        : 'POST',
				dataType    : 'json',
				url         : '<?= base_url('reset_transaksi')?>/res_dat',
				success: function(data) {
					
					if(data.status==1){
						swal({
							title: "RESET DATA " ,
							html: "Berhasil ",
							type: "success",
							confirmButtonText: "OK"
						}).then((value) => {
							location.href = "<?= site_url('reset_transaksi') ?>";
						});
					}else{
						swal({
                            title: "RESET DATA",
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