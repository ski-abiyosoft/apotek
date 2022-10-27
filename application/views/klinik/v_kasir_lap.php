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
			<span class="title-web">KLINIK <small>Laporan Kasir</small>
		</h3>
		<ul class="page-breadcrumb breadcrumb">

			<li>
				<i style="color:white;" class="fa fa-home"></i>
				<a class="title-white" href="../home.php">
					Awal
				</a>
				<i style="color:white;" class="fa fa-angle-right"></i>
			</li>
			<li>
				<a class="title-white" href="#">
					Kasir
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

		<div class="note note-success">
			<p>
				Laporan - laporan untuk Kasir
				<br>
			</p>
		</div>

		<br>

		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i><b>Parameter Laporan</b>
				</div>

			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
					<div class="form-body">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">KLINIK ESTETIKA</label>
									<div class="col-md-7">
										<B><input style="background-color:#99ff33; color:black" type="text" name="cabang" id="cabang" class="form-control" disabled> </B>

									</div>
								</div>
							</div>


						</div>

						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">Nama Laporan</label>
									<div class="col-md-7">
										<select id="idlap" name="idlap" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih...">
											<optgroup label="Keuangan">
												<option data-subtext="101" value="101">01. LAPORAN PENDAPATAN KASIR PER SHIFT</option>
												<option data-subtext="102" value="102">02. REKAP PENJUALAN HARIAN </option>
												<option data-subtext="103" value="103">03. LAPORAN RINCIAN TRANSAKSI</option>
												<option data-subtext="104" value="104">04. LAPORAN KASIR PER JENIS</option>
												<option data-subtext="105" value="105">05. REKAP PENJUALAN HARIAN LENGKAP</option>
												<option data-subtext="106" value="106">06. LAPORAN OMSET PER KELOMPOK UMUR</option>
												<option data-subtext="107" value="107">07. LAPORAN PENDAPATAN REKAP UANG MUKA</option>
												<option data-subtext="108" value="108">08. LAPORAN PENDAPATAN DETAIL UANG MUKA</option>

											</optgroup>
										</select>
									</div>

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
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">Dokter</label>
									<div class="col-md-7">
										<select name="dokter" id="dokter" class="select2_el_dokter form-control">
										</select>
									</div>
								</div>
							</div>


						</div>
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-md-3 control-label">Unit</label>
									<div class="col-md-7">
										<select name="unit" id="unit" class="select2_el_poli form-control">
										</select>
									</div>
								</div>
							</div>


						</div>
						<!-- <div class="row">
										<div class="col-md-12">
											<div class="form-group">
											<label class="col-md-3 control-label">PEMBAYARAN</label>
											<div class="col-md-9">
											<select id="pembayaran" name="pembayaran" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih...">
											<optgroup label="pembayaran">	
											<option value="1">Gabungan</option>
											<option value="2">Cash</option>
											<option value="3">Credit Card</option>
											<option value="4">Debet Card</option>
											<option value="5">Transfer</option>
											<option value="6">Online</option>
											</optgroup>	
											</select>
											</div>

										</div>
										</div>	
									</div>	 -->




						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<!-- <a class="btn green print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" >Tampilkan</a> -->
								<br>
								<!-- <a class="btn btn-sm blue print_laporan" onclick="javascript:window.open(_urlcetak(0),'_blank');" ><i title="CETAK PDF" class="glyphicon glyphicon-file"></i><b> LAYAR </b></a> -->

								<a class="btn btn-sm red print_laporan" onclick="javascript:window.open(_urlcetak(1),'_blank');"><i title="CETAK PDF" class="glyphicon glyphicon-print"></i><b> PDF </b></a>

								<a class="btn btn-sm green print_laporan" onclick="javascript:window.open(_urlcetak(2),'_blank');"><i title="CETAK PDF" class="fa fa-download"></i><b> EXCEL </b></a>

								<br>
								<br>
								<br>
								<!-- <div >
											<img id="proses" src="<?php echo base_url(); ?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden"/>
										</div> -->
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
				// $('#idpasien').val(data.text);
				// var selectElement = document.getElementById('text');
				// var opt = document.createElement('option');
				// opt.value = data.text;
				// opt.innerHTML = data.namatext;
				// selectElement.appendChild(opt);			
				$('#cabang').val(data.id);

			}
		});


	}

	function _urlcetak(cek) {
		var baseurl = "<?php echo base_url() ?>";
		var idlap = $('[name="idlap"]').val();
		var tgl1 = $('[name="tanggal1"]').val();
		var tgl2 = $('[name="tanggal2"]').val();
		var dokter = $('[name="dokter"]').val();
		var unit = $('[name="unit"]').val();
		var cbg = $('[name="cabang"]').val();
		var pemb = $('[name="pembayaran"]').val();
		var param = '?idlap=' + idlap + '&tgl1=' + tgl1 + '&tgl2=' + tgl2 + '&dokter=' + dokter + '&cabang=' + cbg + '&unit=' + unit + '&cekk=' + cek;

		// return baseurl+'kasir_laporan/cetak/'+param;
		if (idlap == '101') {
			return baseurl + 'kasir_laporan/ctk_101/' + param;
		}
		if (idlap == '102') {
			return baseurl + 'kasir_laporan/cetak/' + param;
		}
		if (idlap == '103') {
			return baseurl + 'kasir_laporan/cetak/' + param;
		}
		if (idlap == '104') {
			return baseurl + 'kasir_laporan/cetak/' + param;
		}
		if (idlap == '105') {
			return baseurl + 'kasir_laporan/ctk_105/' + param;
		}
		if (idlap == '106') {
			return baseurl + 'kasir_laporan/ctk_106/' + param;
		}
		if (idlap == '107') {
			return baseurl + 'kasir_laporan/ctk_107/' + param;
		}
		if (idlap == '108') {
			return baseurl + 'kasir_laporan/ctk_108/' + param;
		}
	}
</script>