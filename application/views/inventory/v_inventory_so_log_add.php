<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">
			<span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
			-
			<span class="title-web">Logistik <small>Stok Opname</small>
		</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home title-white"></i>
				<a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
				<i class="fa fa-angle-right title-white"></i>
			</li>
			<li>
				<a class="title-white" href="<?php echo base_url(); ?>inventory_tso_log">Daftar Stok Opname</a>
				<i class="fa fa-angle-right title-white"></i>
			</li>
			<li>
				<a class="title-white" href="">Entri Stok Opname</a>
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-reorder"></i>&nbsp;Data Baru
		</div>
	</div>

	<div class="portlet-body form">
		<form id="frmpenjualan" class="form-horizontal" method="post">
			<div class="form-body">

				<br />

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal <small style="color:red">*</small></label>
							<div class="col-md-4">
								<input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Yang Membuat <small style="color:red">*</small></label>
							<div class="col-md-6">
								<input type="text" name="pic" class="form-control" value="<?= $this->session->userdata("username"); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Gudang <small style="color:red">*</small></label>
							<div class="col-md-6">
								<select id="gudang" name="gudang" class="form-control select2_el_logistik_depo" onchange="getgudang()" data-placeholder="Pilih..." onkeypress="return tabE(this,event)"></select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Jenis <small style="color:red">*</small></label>
							<div class="col-md-6">
								<select type="text" name="typestock" class="form-control">
									<option value="so" selected>Stock Opname</option>
									<option value="adjustment">Adjusment (Penyesuaian)</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Yang Menyetujui <small style="color:red">*</small></label>
							<div class="col-md-6">
								<select name="yangsetuju" id="yangsetuju" class="form-control select2_el_farmasi_user_2"></select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>

				<br />

				<div class="row">
					<div class="col-md-12">
						<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
							<thead>
								<tr>
									<th width="20%" style="text-align: center">Kode/Nama Barang</th>
									<th width="20%" style="text-align: center">Saldo Akhir</th>
									<th width="20%" style="text-align: center">Hasil Hitung Fisik</th>
									<th width="20%" style="text-align: center">+/-</th>
									<th width="10%" style="text-align: center">Satuan</th>
									<th width="10%" style="text-align: center">Yang Ubah</th>
								</tr>
								<thead>
								<tbody>
									<tr>
										<td width="20%">
											<select name="kode[]" id="kode1" class="select2_el_log_barangdata form-control" onchange="showbarangname(this.value, 1)"></select>
										</td>
										<td width="20%"><input name="saldoakhir[]" value="0" id="saldoakhir1" type="text" class="form-control rightJustified"></td>
										<td width="20%"><input name="qty[]" value="1" id="qty1" type="text" class="form-control rightJustified" onchange="totalline(1)"></td>
										<td width="20%"><input name="plusminus[]" id="plusminus1" type="text" class="form-control rightJustified" readonly>
										<td width="10%"><input name="sat[]" id="sat1" type="text" class="form-control "></td>
										<td width="10%">
											<select name="yangubah[]" id="yangubah1" class="form-control select2_el_farmasi_user"></select>
										</td>
									</tr>
								</tbody>
						</table>

						<div class="row">
							<div class="col-xs-9">
								<div class="wells" style="margin-bottom:20px">
									<button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
									<button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="well">
							<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							<div class="btn-group">
								<button type="button" class="btn red" onclick="location.href='/inventory_tso_log'"><i class="fa fa-times"></i> Tutup</button>
							</div>
							<!-- <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								 -->
						</div>
					</div>
				</div>
			</div>

	</div>
	</form>
</div>
</div>

<?php
$this->load->view('template/footer');
?>

<script>
	$(window).on("load", function() {
		initailizeSelect2_log_baranggud(null);
	});

	var idrow = 2;

	function tambah() {
		var x = document.getElementById('datatable').insertRow(idrow);
		var td1 = x.insertCell(0);
		var td2 = x.insertCell(1);
		var td3 = x.insertCell(2);
		var td4 = x.insertCell(3);
		var td5 = x.insertCell(4);
		var td6 = x.insertCell(5);

		var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_log_barangdata form-control' ><option value=''>--- Pilih Barang ---</option></select>";
		td1.innerHTML = akun;
		td2.innerHTML = "<input name='saldoakhir[]'    id='saldoakhir" + idrow + "' onchange='totalline(" + idrow +
			")' value='0'  type='text' class='form-control rightJustified' >";
		td3.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow +
			")' value='1'  type='text' class='form-control rightJustified'  >";
		td4.innerHTML = "<input name='plusminus[]'    id=plusminus" + idrow + " onchange='totalline(" + idrow +
			")' type='text' class='form-control rightJustified'  readonly>";
		td5.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
		td6.innerHTML = "<select name='yangubah[]' id='yangubah" + idrow + "' class='form-control select2_el_farmasi_user'></select>";
		// initailizeSelect2_log_baranggud($("#gudang").val());
		initailizeSelect2_log_barangdata();
		idrow++;
	}

	function totalline(id) {
		var qty = $('#qty' + id).val();
		var saldoakhir = $('#saldoakhir' + id).val();
		var art = qty - saldoakhir;
		$('#plusminus' + id).val(art);
	}

	function showbarangname(str, id) {
		var xhttp;
		var gudang = $("#gudang").val();
		var vid = id;
		$.ajax({
			url: "<?= site_url('Inventory_tso_log/validkan/'); ?>"+str+"/"+gudang,
			type: "POST",
			dataType: "JSON",
			success: function(data){
				if(data.status == 1){
					$.ajax({
						url: "<?php echo base_url(); ?>inventory_tso_log/getinfobarang/" + str + "/?gudang=" + gudang,
						type: "GET",
						dataType: "JSON",
						success: function(data) {
							console.log(data)
							$('#sat' + vid).val(data.satuan1);
							var qty = $('#qty' + vid).val();
							$('#saldoakhir' + id).val(Math.round(data.salakhir));
							var salakhirx = Number(parseInt(data.salakhir));
							if (data.salakhir != null) {
								var salakhir = salakhirx;
							} else {
								var salakhir = 0;
							}
							$('#sat' + id).val(data.satuan1);
							var art = qty - salakhir;
							$('#plusminus' + id).val(art);
						}
					});
				}
			}
		});
	}

	function getgudang() {
		console.log($("#gudang").val());
		initailizeSelect2_log_baranggud($("#gudang").val());
	}

	function save() {
		var noform = $('[name="pic"]').val();
		var tanggal = $('[name="tanggal"]').val();
		var setuju = $('#yangsetuju').val();
		var ubah = $('#yangubah').val();
		if (noform == "") {
			swal('STOK OPNAME', 'Petugas belum diisi ...', '');
		} else {
			$.ajax({
				url: '<?php echo site_url('inventory_tso_log/save/1?setuju=') ?>' + setuju + "&ubah=" + ubah,
				data: $('#frmpenjualan').serialize(),
				type: 'POST',
				success: function(data) {
					swal({
						title: "STOK OPNAME",
						html: "<p> Petugas   : <b>" + noform + "</b> </p>" +
							"Tanggal :  " + tanggal,
						type: "info",
						confirmButtonText: "OK"
					}).then((value) => {
						location.href = "<?php echo base_url() ?>inventory_tso_log";
					});

				},
				error: function(data) {
					swal('STOK OPNAME', 'Data gagal disimpan ...', '');
				}
			});
		}
	}

	function hapus() {
		if (idrow > 2) {
			var x = document.getElementById('datatable').deleteRow(idrow - 1);
			idrow--;
		}
	}
</script>



</body>

</html>