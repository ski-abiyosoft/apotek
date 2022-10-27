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
			<span class="title-web">Master <small>Tarif</small>
		</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="<?php echo base_url(); ?>dashboard">
					Awal
				</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="<?php echo base_url(); ?>master_tarif">
					Daftar Tarif
				</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="">
					Master BHP
				</a>
			</li>
		</ul>
	</div>
</div>
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-reorder"></i>*Edit Data
		</div>


	</div>

	<div class="portlet-body form">
		<form id="frmtarif" class="form-horizontal" method="post">
			<div class="form-body">
				<div class="tabbable tabbable-custom tabbable-full-width">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#detailtarif" data-toggle="tab">
								Detail Tarif
							</a>
						</li>
						<li class="">
							<a href="#costbhp" data-toggle="tab">
								Cost dan BHP
							</a>
						</li>
					</ul>
					<div class="tab-content">

						<!--Tab Detail Tarif-->
						<div class="tab-pane active" id="detailtarif">
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-3 control-label">Kode <font color="red">*</font></label>
											<div class="col-md-6">
												<input name="kodetarif" class="form-control" type="text" value="<?= $kode; ?>" readonly>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">

											<table id="datatableTarif" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
												<thead>
													<th width="15%" style="text-align: center">Cabang</th>
													<th width="15%" style="text-align: center">Kelompok Tarif</th>
													<th width="10%" style="text-align: center">Jasa RS/Klinik</th>
													<th width="10%" style="text-align: center">Jasa DR</th>
													<th width="10%" style="text-align: center">Jasa Perawat</th>
													<th width="10%" style="text-align: center">BHP</th>
													<th width="10%" style="text-align: center">Total</th>
												</thead>

												<tbody>
													<?php
													$no = 1;
													foreach ($cab as $rowcab) {
													?>
														<tr>
															<td width="15%">
																<select name="cabang[]" id="cabang<?= $no; ?>" class="select2_el_cabangg form-control input-largex">
																	<option value="<?= $rowcab->koders; ?>"><?= $rowcab->koders; ?></option>
																</select>
															</td>
															<td width="15%">
																<select name="keltarif[]" id="keltarif<?= $no; ?>" class="select2_el_tarif form-control input-largex">
																	<option value="<?= $rowcab->cust_id; ?>"><?= $rowcab->cust_id; ?></option>
																</select>
															</td>
															<td width="10%"><input name="jasars[]" onchange="totallineTarif(<?= $no; ?>)" value="<?= angka_rp($rowcab->tarifrspoli, 2); ?>" id="jasars<?= $no; ?>" type="text" class="form-control rightJustified"></td>
															<td width="10%"><input name="jasadr[]" onchange="totallineTarif(<?= $no; ?>)" value="<?= angka_rp($rowcab->tarifdrpoli, 2); ?>" id="jasadr<?= $no; ?>" type="text" class="form-control rightJustified"></td>
															<td width="10%"><input name="jasaperawat[]" onchange="totallineTarif(<?= $no; ?>)" value="<?= angka_rp($rowcab->feemedispoli, 2); ?>" id="jasaperawat<?= $no; ?>" type="text" class="form-control rightJustified"></td>
															<td width="10%"><input name="bhp[]" onchange="totallineTarif(<?= $no; ?>)" value="<?= angka_rp($rowcab->bhppoli, 2); ?>" id="bhp<?= $no; ?>" type="text" class="form-control rightJustified"></td>
															<td width="10%"><input name="total[]" value="<?= angka_rp($rowcab->cost, 2); ?>" id="total<?= $no; ?>" type="text" class="form-control rightJustified" readonly></td>
														</tr>
													<?php $no++;
													} ?>
												</tbody>
											</table>

											<div class="row">
												<div class="col-xs-9">
													<div class="wells">
														<button type="button" onclick="tambahTarif()" class="btn green"><i class="fa fa-plus"></i> </button>
														<button type="button" onclick="hapusTarif()" class="btn red"><i class="fa fa-trash-o"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Tab Cost dan BHP -->
						<div class="tab-pane" id="costbhp">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-3 control-label">Kode <font color="red">*</font></label>
										<div class="col-md-6">
											<input name="kodetarif" class="form-control" type="text" value="<?= $kode; ?>" readonly>
										</div>
									</div>
								</div>


								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-3 control-label">Tindakan <font color="red">*</font></label>
										<div class="col-md-9">
											<input name="namatindakan" class="form-control" type="text" value="<?= $nama; ?>" readonly>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">

									<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
										<thead>
											<th width="20%" style="text-align: center">Kode Barang</th>
											<th width="10%" style="text-align: center">Kuantitas</th>
											<th width="10%" style="text-align: center">Satuan</th>
											<th width="10%" style="text-align: center">Harga</th>
											<th width="15%" style="text-align: center">Total Harga</th>
										</thead>

										<tbody>
											<?php
											$no = 1;
											foreach ($bhp as $row) {
											?>
												<tr>
													<td width="30%">
														<select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_barang form-control input-largex" onchange="showbarangname(this.value, <?= $no; ?>);cekstok(this.value)">
															<option value="<?= $row->kodeobat; ?>"><?= $row->kodeobat . ' | ' . $row->namabarang; ?></option>
														</select>
													</td>
													<td width="10%"><input name="qty[]" onchange="totalline(<?= $no; ?>);total()" value="<?= $row->qty; ?>" id="qty<?= $no; ?>" type="text" class="form-control rightJustified"></td>
													<td width="10%"><input name="sat[]" id="sat<?= $no; ?>" type="text" class="form-control " value="<?= $row->satuan; ?>" onkeypress="return tabE(this,event)"></td>
													<td width="10%"><input name="harga[]" onchange="totalline(<?= $no; ?>)" value="<?= angka_rp($row->harga, 2); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified" readonly></td>
													<td width="20%"><input name="jumlah[]" value="<?= angka_rp($row->totalharga, 2); ?>" id="jumlah<?= $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
												</tr>
											<?php $no++;
											} ?>
										</tbody>
									</table>

									<div class="row">
										<div class="col-xs-8">
											<div class="wells">
												<button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
												<button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
											</div>
										</div>
										<div class="col-xs-4 invoice-block">
											<div class="well">
												<table>
													<tr>
														<td width="40%"><strong>TOTAL</strong></td>
														<td width="1%"><strong>:</strong></td>
														<td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
													</tr>
													<input type="hidden" id="tersimpan">
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-8">
							<div class="wells">
								<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
								<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
</div>
</div>
</div>

<?php
$this->load->view('template/footer');
?>

<script>
	var idrow = "<?= $jumdata + 1; ?>";
	var idroww = "<?= $jumdatatarif + 1; ?>";

	function tambahTarif() {
		var x = document.getElementById('datatableTarif').insertRow(idroww);
		var td1 = x.insertCell(0);
		var td2 = x.insertCell(1);
		var td3 = x.insertCell(2);
		var td4 = x.insertCell(3);
		var td5 = x.insertCell(4);
		var td6 = x.insertCell(5);
		var td7 = x.insertCell(6);

		td1.innerHTML = "<select name='cabang[]' id=cabang" + idroww + "' class='select2_el_cabangg form-control' ></select>";
		td2.innerHTML = "<select name='keltarif[]' id=keltarif" + idroww + "' class='select2_el_tarif form-control' ></select>";
		td3.innerHTML = "<input name='jasars[]' id=jasars" + idroww + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
		td4.innerHTML = "<input name='jasadr[]' id=jasadr" + idroww + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
		td5.innerHTML = "<input name='jasaperawat[]' id=jasaperawat" + idrow + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
		td6.innerHTML = "<input name='bhp[]' id=bhp" + idroww + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
		td7.innerHTML = "<input name='total[]' id=total" + idroww + " type='text' class='form-control rightJustified' size='40%' readonly>";

		initailizeSelect2_cabangg();
		initailizeSelect2_tarif();
		idroww++;
	}


	function tambah() {
		var x = document.getElementById('datatable').insertRow(idrow);
		var td1 = x.insertCell(0);
		var td2 = x.insertCell(1);
		var td3 = x.insertCell(2);
		var td4 = x.insertCell(3);
		var td5 = x.insertCell(4);

		var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ");cekstok(this.value)' class='select2_el_farmasi_barang form-control' ></select>";
		td1.innerHTML = akun;
		td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
		td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
		td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified'>";
		td5.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";

		initailizeSelect2_farmasi_barang();
		idrow++;
	}


	function showbarangname(str, id) {
		var xhttp;
		var vid = id;
		$('#sat' + vid).val('');
		$('#harga' + vid).val(0);
		var customer = $('#cust').val();
		$.ajax({
			url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#nama' + vid).val(data.namabarang);
				$('#sat' + vid).val(data.satuan1);
				$('#harga' + vid).val(formatCurrency1(data.hargajual));
				totalline(vid);
			}
		});
	}

	function save() {
		var kode = $('[name="kodetarif"]').val();
		var nama = $('[name="namatindakan"]').val();
		var total = $('#_vtotal').text();

		if (kode == "") {
			swal('MASTER BHP', 'Data Belum Lengkap/Belum ada transaksi ...', '');
		} else {
			$.ajax({
				url: '<?php echo site_url('master_tarif/save_bhp/2') ?>',
				data: $('#frmtarif').serialize(),
				type: 'POST',

				success: function(data) {
					//document.getElementById("btnsimpan").disabled=true;
					document.getElementById("tersimpan").value = "OK";

					swal({
						title: "MASTER BHP",
						html: "Tindakan   : <b>" + nama + "</b>",
						type: "info",
						confirmButtonText: "OK"
					}).then((value) => {
						location.href = "<?php echo base_url() ?>master_tarif";
					});

				},
				error: function(data) {
					swal('MASTER BHP', 'Data gagal disimpan ...', '');

				}
			});
		}
	}

	function hapusTarif() {
		if (idroww > 1) {
			var x = document.getElementById('datatableTarif').deleteRow(idroww - 1);
			idroww--;
			total();
		}
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow - 1);
			idrow--;
			total();
		}
	}

	function total() {

		var table = document.getElementById('datatable');
		var rowCount = table.rows.length;

		tjumlah = 0;
		for (var i = 1; i < rowCount; i++) {
			var row = table.rows[i];

			jumlah = row.cells[1].children[0].value;
			harga = row.cells[3].children[0].value;
			var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
			var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));

			tjumlah = tjumlah + eval(jumlah1 * harga1);

			document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah);

			if (tjumlah > 0) {
				document.getElementById("btnsimpan").disabled = false;
			} else {
				document.getElementById("btnsimpan").disabled = true;
			}
		}
	}

	function totalline(id) {

		var table = document.getElementById('datatable');
		var row = table.rows[id];
		var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
		jumlah = row.cells[1].children[0].value * harga;

		tot = jumlah;

		row.cells[4].children[0].value = formatCurrency1(tot);
		total();
	}

	function totallineTarif(id) {
		var table = document.getElementById('datatableTarif');
		var row = table.rows[id];
		var jasars = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
		var jasadr = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
		var jasaperawat = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
		var bhp = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
		totalTarif = jasars + jasadr + jasaperawat + bhp;

		tot = totalTarif;

		row.cells[6].children[0].value = formatCurrency1(tot);
	}

	total();
</script>
</body>

</html>