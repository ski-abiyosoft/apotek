<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/exporting.js"></script>

<?php

foreach ($report as $result) {
	$bulan[] = $result->bulan;
	$value[] = (float) $result->jumlah;
}

?>

<style type="text/css" />


#sales-chart
{
min-height: 300px;
}

</style>

<div class="row">
	<a style="text-decoration: none;" type="button" href="#" onclick="hide(1)">
		<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat blue" id="border1">
				<div class="visual">
					<i class="fa fa-barcodex"></i>
				</div>
				<div class="details">
					<div class="number">
						KUNJUNGAN
					</div>
					<div class="desc">
						Laporan Kunjungan
					</div>
				</div>
			</div>
		</div>
	</a>
	<a style="text-decoration: none;" type="button" href="#" onclick="hide(2)">
		<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat green" id="border2">
				<div class="visual">
					<i class="fa fa-printx"></i>
				</div>
				<div class="details">
					<div class="number">
						PENYAKIT
					</div>
					<div class="desc">
						Laporan Penyakit ICD 10
					</div>
				</div>
			</div>
		</div>
	</a>
	<a style="text-decoration: none;" type="button" href="#" onclick="hide(3)">
		<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat yellow" id="border3">
				<div class="visual">
					<i class="fa fa-shopping-cartx"></i>
				</div>
				<div class="details">
					<div class="number">
						DASHBOARD
					</div>
					<div class="desc">
						Management
					</div>
				</div>
			</div>
		</div>
	</a>
</div>

<!-- kun_pas -->
<div class="row" id="kun_pas">
	<div class="col-md-12">
		<div class="row" style="margin-bottom: 10px;">
			<div class="col-md-12">
				<div style="font-weight: bold;">
					<h3 class="page-title">
						<span style="padding: 10px; background-color: #27a9e3; color: white;">
							KUNJUNGAN PASIEN
						</span>
					</h3>
				</div>
			</div>
		</div>
		<hr>
		<form method="POST" id="form_diag">
			<div class="row">
				<div class="col-md-3">
					<div class="row">
						<label for="dari" class="col-md-3">Dari</label>
						<div class="col-md-9">
							<?php if ($dari != '' || $dari != null) {
								$darix = $dari;
							} else {
								$darix = date("Y-m-d");
							}
							?>
							<input type="date" name="dari" id="dari" value="<?= $darix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="row">
						<label for="sampai" class="col-md-3">Sampai</label>
						<div class="col-md-9">
							<?php if ($sampai != '' || $sampai != null) {
								$sampaix = $sampai;
							} else {
								$sampaix = date("Y-m-d");
							}
							?>
							<input type="date" name="sampai" id="sampai" value="<?= $sampaix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-primary" onclick="getdata(1);">Proses</button>
				</div>
			</div>
			<hr>
			<!-- agama -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Agama</div>
										<table id="datatable_agama" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Agama</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($agama as $a) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $a->agama; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($a->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_agama" onclick="unduh(1)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="agama-chart" style="height: 300px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- jenis kelamin -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Jenis Kelamin</div>
										<table id="datatable_jeniskelamin" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Jenis Kelamin</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($jeniskelamin as $jkel) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $jkel->jk; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($jkel->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_jeniskelamin" onclick="unduh(2)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="jeniskelamin-chart" style="height: 300px;"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- pendidikan -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Pendidikan</div>
										<table id="datatable_pendidikan" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Pendidikan</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($pendidikan as $p) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $p->pen; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($p->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_pendidikan" onclick="unduh(3)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="pendidikan-chart" style="height: 300px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- pekerjaan -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Pekerjaan</div>
										<table id="datatable_pekerjaan" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Pekerjaan</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($pekerjaan as $pk) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $pk->pek; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($pk->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_pekerjaan" onclick="unduh(4)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="pekerjaan-chart" style="height: 500px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- status -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Status</div>
										<table id="datatable_status" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Status</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($status as $s) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $s->stat; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($s->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_status" onclick="unduh(5)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="status-chart" style="height: 500px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- cara bayar -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Cara Bayar</div>
										<table id="datatable_carabayar" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Cara Bayar</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($carabayar as $cb) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $cb->cara; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($cb->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_carabayar" onclick="unduh(6)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="carabayar-chart" style="height: 500px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- poli -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Poli</div>
										<table id="datatable_poli" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Poli/Unit</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($poli as $p) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $p->pol; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($p->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_poli" onclick="unduh(7)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="poli-chart" style="height: 500px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- dokter -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Dokter</div>
										<table id="datatable_dokter" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Dokter</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; foreach ($dokter as $d) : ?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= $d->dok; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color; ?>;"><?= number_format($d->jumlah); ?></td>
													</tr>
												<?php $no++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_dokter" onclick="unduh(8)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="dokter-chart" style="height: 500px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- kecamatan -->
			<div class="row" style="margin-bottom: 50px;">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">Berdasarkan Wilayah Kecamatan</div>
										<table id="datatable_wilayahkecamatan" class="table table-striped table-hover table-bordered" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="40%">Kecamatan</th>
													<th style="text-align: center;" width="60%">Jumlah Kunjungan</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$no = 1; 
													foreach ($kecamatan as $k) : 
												?>
													<?php if($no == 1) {
														$bgcolor = "#95ceff";
														$color = "color: black";
													} else if($no == 2) {
														$bgcolor = "#5c5c61";
														$color = "color: white";
													} else if($no == 3) {
														$bgcolor = "#a9ff96";
														$color = "color: black";
													} else if($no == 4) {
														$bgcolor = "#ffbc75";
														$color = "color: black";
													} else if($no == 5) {
														$bgcolor = "#999eff";
														$color = "color: black";
													} else if($no == 6) {
														$bgcolor = "#ff7599";
														$color = "color: black";
													} else if($no == 7) {
														$bgcolor = "#fdec6d";
														$color = "color: black";
													} else if($no == 8) {
														$bgcolor = "#44a9a8";
														$color = "color: black";
													} else if($no == 9) {
														$bgcolor = "#ff7474";
														$color = "color: black";
													} else if($no == 10) {
														$bgcolor = "#91e8e1";
														$color = "color: black";
													} ?>
													<tr>
														<td style="background-color: <?= $bgcolor; ?>; <?= $color?>;"><?= $k->kec; ?></td>
														<td style="text-align: right; background-color: <?= $bgcolor; ?>; <?= $color?>;"><?= number_format($k->jumlah); ?></td>
													</tr>
												<?php 
														$no++; 
													endforeach; 
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_wilayahkecamatan" onclick="unduh(9)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="wilayahkecamatan-chart" style="height: 500px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- lap_pen -->
<div class="row" id="lap_pen">
	<div class="col-md-12">
		<div class="row" style="margin-bottom: 10px;">
			<div class="col-md-12">
				<div style="font-weight: bold;">
					<h3 class="page-title">
						<span style="padding: 10px; background-color: #28b779; color: white">
							LAPORAN PENYAKIT / ICD 10 DAN PROSEDUR
						</span>
					</h3>
				</div>
			</div>
		</div>
		<hr>
		<form method="POST" id="form_diag2">
			<div class="row">
				<div class="col-md-3">
					<div class="row">
						<label for="dari2" class="col-md-3">Dari</label>
						<div class="col-md-9">
							<?php if ($dari != '' || $dari != null) {
								$darix = $dari;
							} else {
								$darix = date("Y-m-d");
							}
							?>
							<input type="date" name="dari2" id="dari2" value="<?= $darix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="row">
						<label for="sampai2" class="col-md-3">Sampai</label>
						<div class="col-md-9">
							<?php if ($sampai != '' || $sampai != null) {
								$sampaix = $sampai;
							} else {
								$sampaix = date("Y-m-d");
							}
							?>
							<input type="date" name="sampai2" id="sampai2" value="<?= $sampaix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-primary" onclick="getdata(2);">Proses</button>
				</div>
			</div>
			<hr>
			<!-- 10 besar penyakit -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">
											<div class="row">
												<div class="col-md-10">
													<span id="total_isi"><?= $isi; ?></span> Besar Penyakit Code ICD
												</div>
												<div class="col-md-2" style="text-align: right;">
													<select name="isi" id="isi" class="form-control" onchange="ubah_isi(this.value, 1)">
														<option <?= ($isi == 10 ? 'selected' : '') ?> value="10">10</option>
														<option <?= ($isi == 20 ? 'selected' : '') ?> value="20">20</option>
														<option <?= ($isi == 30 ? 'selected' : '') ?> value="30">30</option>
														<option <?= ($isi == 40 ? 'selected' : '') ?> value="40">40</option>
														<option <?= ($isi == 50 ? 'selected' : '') ?> value="50">50</option>
													</select>
												</div>
											</div>
										</div>
										<table id="datatable_penyakit10" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="5%">No</th>
													<th style="text-align: center;" width="15%">Kode</th>
													<th style="text-align: center;" width="70%">Nama Penyakit</th>
													<th style="text-align: center;" width="10%">Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($penyakit10 as $pk) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $pk->kode; ?></td>
														<td><?= $pk->ket; ?></td>
														<td style="text-align: right;"><?= number_format($pk->jumlah); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_penyakit10" onclick="unduh_p(1)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="penyakit10-chart" style="height: 500px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- 10 tindakan prosedur -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">
											<div class="row">
												<div class="col-md-10">
													<span id="total_isi"><?= $isi2; ?></span> Besar Tindakan Prosedur Prosedur ICD
												</div>
												<div class="col-md-2" style="text-align: right;">
													<select name="isi2" id="isi2" class="form-control" onchange="ubah_isi(this.value, 2)">
														<option <?= ($isi2 == 10 ? 'selected' : '') ?> value="10">10</option>
														<option <?= ($isi2 == 20 ? 'selected' : '') ?> value="20">20</option>
														<option <?= ($isi2 == 30 ? 'selected' : '') ?> value="30">30</option>
														<option <?= ($isi2 == 40 ? 'selected' : '') ?> value="40">40</option>
														<option <?= ($isi2 == 50 ? 'selected' : '') ?> value="50">50</option>
													</select>
												</div>
											</div>
										</div>
										<table id="datatable_tindakan" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="5%">No</th>
													<th style="text-align: center;" width="15%">Kode</th>
													<th style="text-align: center;" width="70%">Nama Penyakit</th>
													<th style="text-align: center;" width="10%">Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($tindakan as $tk) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $tk->kode; ?></td>
														<td><?= $tk->ket; ?></td>
														<td style="text-align: right;"><?= number_format($tk->jumlah); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_tindakan" onclick="unduh_p(2)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="tindakan-chart" style="height: 500px; width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<!-- statistik -->
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<div class="h4">
											<div class="row">
												<div class="col-md-10">
													<span id="total_isi"><?= $isi3; ?></span> Statistik ICD
												</div>
												<div class="col-md-2" style="text-align: right;">
													<select name="isi3" id="isi3" class="form-control" onchange="ubah_isi(this.value, 3)">
														<option <?= ($isi3 == 10 ? 'selected' : '') ?> value="10">10</option>
														<option <?= ($isi3 == 20 ? 'selected' : '') ?> value="20">20</option>
														<option <?= ($isi3 == 30 ? 'selected' : '') ?> value="30">30</option>
														<option <?= ($isi3 == 40 ? 'selected' : '') ?> value="40">40</option>
														<option <?= ($isi3 == 50 ? 'selected' : '') ?> value="50">50</option>
													</select>
												</div>
											</div>
										</div>
										<table id="datatable_statistik" class="table table-striped table-hover table-bordered master-table" width="100%">
											<thead>
												<tr>
													<th style="text-align: center;" width="5%">No</th>
													<th style="text-align: center;" width="15%">Kode</th>
													<th style="text-align: center;" width="30%">Nama Penyakit</th>
													<th style="text-align: center;" width="10%">Jumlah</th>
													<th style="text-align: center;" width="10%">Ulang</th>
													<th style="text-align: center;" width="10%">Baru</th>
													<th style="text-align: center;" width="10%">Pria</th>
													<th style="text-align: center;" width="10%">Wanita</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($statistik as $st) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $st->kode; ?></td>
														<td><?= $st->ket; ?></td>
														<td style="text-align: right;"><?= number_format($st->jumlah); ?></td>
														<td style="text-align: right;"><?= number_format($st->ulang); ?></td>
														<td style="text-align: right;"><?= number_format($st->baru); ?></td>
														<td style="text-align: right;"><?= number_format($st->pria); ?></td>
														<td style="text-align: right;"><?= number_format($st->wanita); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn green" type="button" id="download_statistik" onclick="unduh_p(3)"><i class="fa fa-download"></i> Unduh Excel</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
		</form>
	</div>
</div>

<!-- das_man -->
<div class="row" id="das_man">
	<div class="col-md-12">
		<div class="row" style="margin-bottom: 10px;">
			<div class="col-md-12">
				<div style="font-weight: bold;">
					<h3 class="page-title">
						<span style="padding: 10px; background-color: #ffb848; color: white">
							DASHBOARD MANAGEMENT
						</span>
					</h3>
				</div>
			</div>
		</div>
		<hr>
		<form method="POST" id="form_diag">
			<div class="row">
				<div class="col-md-3">
					<div class="row">
						<label for="dari" class="col-md-3">Dari</label>
						<div class="col-md-9">
							<?php if ($dari != '' || $dari != null) {
								$darix = $dari;
							} else {
								$darix = date("Y-m-d");
							}
							?>
							<input type="date" name="dari3" id="dari3" value="<?= $darix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="row">
						<label for="sampai" class="col-md-3">Sampai</label>
						<div class="col-md-9">
							<?php if ($sampai != '' || $sampai != null) {
								$sampaix = $sampai;
							} else {
								$sampaix = date("Y-m-d");
							}
							?>
							<input type="date" name="sampai3" id="sampai3" value="<?= $sampaix; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-primary" onclick="getdata(3);">Proses</button>
				</div>
			</div>
			<hr>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- master -->
<script>
	function getdata(id) {
		if (id == 1) {
			fokus = 'kun_pas';
			var dari = $("#dari").val();
			var sampai = $("#sampai").val();
		} else if (id == 2) {
			fokus = 'lap_pen';
			var dari = $("#dari2").val();
			var sampai = $("#sampai2").val();
		} else if (id == 3) {
			fokus = 'das_man';
			var dari = $("#dari3").val();
			var sampai = $("#sampai3").val();
		}
		if (dari != '' && sampai != '') {
			param = "?dari=" + dari + "&sampai=" + sampai + "&fokus=" + fokus;
			location.href = '/Dashboard/' + param;
		}
	}

	$(document).ready(function() {
		$("#kun_pas").show();
		$("#lap_pen").hide();
		$("#das_man").hide();
		if ('<?= $fokus; ?>' == '' || '<?= $fokus; ?>' == null || '<?= $fokus; ?>' == 'kun_pas') {
			id = 1;
		} else if ('<?= $fokus; ?>' == 'lap_pen') {
			id = 2;
		} else if ('<?= $fokus; ?>' == 'das_man') {
			id = 3;
		}
		hide(id);
		document.getElementById('total_isi').innerHTML = '<?= $isi; ?>';
	});

	function hide(id) {
		if (id == 1) {
			$("#kun_pas").show();
			$("#border1").css("border-style", "double");
			$("#border1").css("border-color", "red");
			$("#border2").css("border-style", "none");
			$("#border2").css("border-color", "none");
			$("#border3").css("border-style", "none");
			$("#border3").css("border-color", "none");
			$("#lap_pen").hide();
			$("#das_man").hide();
		} else if (id == 2) {
			$("#kun_pas").hide();
			$("#lap_pen").show();
			$("#border1").css("border-style", "none");
			$("#border1").css("border-color", "none");
			$("#border2").css("border-style", "double");
			$("#border2").css("border-color", "red");
			$("#border3").css("border-style", "none");
			$("#border3").css("border-color", "none");
			$("#das_man").hide();
		} else if (id == 3) {
			$("#kun_pas").hide();
			$("#lap_pen").hide();
			$("#das_man").show();
			$("#border1").css("border-style", "none");
			$("#border1").css("border-color", "none");
			$("#border2").css("border-style", "none");
			$("#border2").css("border-color", "none");
			$("#border3").css("border-style", "double");
			$("#border3").css("border-color", "red");
		}
	}
</script>

<!-- kun pas -->
<script type="text/javascript">
	function unduh(no) {
		var baseurl = "<?php echo base_url() ?>";
		var dari = document.getElementById('dari').value;
		var sampai = document.getElementById('sampai').value;
		var param = '?dari=' + dari + '&sampai=' + sampai + '&cek=' + no;
		url = baseurl + 'Dashboard/unduh/' + param;
		window.open(url, '');
	}

	$('#agama-chart').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: '',
			align: 'left'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			name: 'Brands',
			colorByPoint: true,
			data: [
				<?php foreach ($agama as $a) { ?> {
						name: '<?= $a->agama; ?>',
						y: <?= $a->jumlah; ?>
					},
				<?php } ?>
			]
		}]
	});

	$('#jeniskelamin-chart').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: '',
			align: 'left'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			name: 'Brands',
			colorByPoint: true,
			data: [
				<?php foreach ($jeniskelamin as $jkel) { ?> {
						name: '<?= $jkel->jk; ?>',
						y: <?= $jkel->jumlah; ?>
					},
				<?php } ?>
			]
		}]
	});

	$('#pendidikan-chart').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: '',
			align: 'left'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			name: 'Brands',
			colorByPoint: true,
			data: [
				<?php foreach ($pendidikan as $p) { ?> {
						name: '<?= $p->pen; ?>',
						y: <?= $p->jumlah; ?>
					},
				<?php } ?>
			]
		}]
	});

	$('#pekerjaan-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (Orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($pekerjaan as $p) : ?> {
					name: '<?= $p->pek; ?>',
					data: [
						<?= '' . $p->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#status-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($status as $cb) : ?> {
					name: '<?= $cb->stat; ?>',
					data: [
						<?= '' . $cb->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#carabayar-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($carabayar as $cb) : ?> {
					name: '<?= $cb->cara; ?>',
					data: [
						<?= '' . $cb->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#poli-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($poli as $p) : ?> {
					name: '<?= $p->pol; ?>',
					data: [
						<?= '' . $p->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#dokter-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($dokter as $d) : ?> {
					name: '<?= $d->dok; ?>',
					data: [
						<?= '' . $d->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#wilayahkecamatan-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($kecamatan as $k) : ?> {
					name: '<?= $k->kec; ?>',
					data: [
						<?= '' . $k->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});
</script>

<!-- lap_pen -->
<script>
	function ubah_isi(isi, par) {
		var dari = $("#dari2").val();
		var sampai = $("#sampai2").val();
		if (dari != '' && sampai != '') {
			fokus = 'lap_pen';
			param = "?dari=" + dari + "&sampai=" + sampai + "&isi=" + isi + "&fokus=" + fokus + "&par=" + par;
			location.href = '/Dashboard/' + param;
		}
	}

	function unduh_p(id) {
		if (id == 1) {
			var isi = $("#isi").val();
		} else if (id == 2) {
			var isi = $("#isi2").val();
		} else if (id == 3) {
			var isi = $("#isi3").val();
		}
		var baseurl = "<?php echo base_url() ?>";
		var dari = document.getElementById('dari2').value;
		var sampai = document.getElementById('sampai2').value;
		var param = '?dari=' + dari + '&sampai=' + sampai + '&cek=' + id + '&isi=' + isi;
		url = baseurl + 'Dashboard/unduh_p/' + param;
		window.open(url, '');
	}

	$('#penyakit10-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($penyakit10 as $pk) : ?> {
					name: "<?= $pk->ket; ?>",
					data: [
						<?= '' . $pk->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});

	$('#tindakan-chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [''],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya (orang)'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.0f} orang</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php foreach ($tindakan as $tk) : ?> {
					name: "<?= $tk->ket; ?>",
					data: [
						<?= '' . $tk->jumlah . ','; ?>
					]

				},
			<?php endforeach; ?>
		]
	});
</script>

<?php
$this->load->view('template/footer');
?>