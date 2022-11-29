
<?php 
	$this->load->view('template/header');
	$this->load->view('template/body');    
	date_default_timezone_set("Asia/Jakarta");	
?>	

<div class="row" style="margin-bottom:20px;">
	<div class="col-md-12">
		<h3 class="page-title">
			<span class="title-unit">
				&nbsp;<?php echo $this->session->userdata('unit'); ?> 
			</span>
			- 
			<span class="title-web">RS <small>Pendaftaran Pasien RJ/IGD</small>
		</h3>
	</div>
</div>

<hr>

<div class="row" style="margin-bottom:20px;">
	<div class="col-md-12">
		<div class="h2">REGISTRASI</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form id="frmpasien" class="form-horizontal" method="post">	
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Cari <font color="red"></font></label>
						<div class="col-md-9">
							<div class="input-group">
								<select class="form-control select2_el_pasien input-medium" onChange="getinfopasien2()" id="pasien" name="pasien">
								</select>
								<input type="hidden" id="idpasien">
								<span class="input-group-btn">
									<a class="btn-sm btn green" id="plus" onclick="add_pasien()"><i class="fa fa-plus"></i></a>
								</span>
							</div>
						</div>
					</div>
				</div>			
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Poliklinik <font color="red">*</font></label>
						<div class="col-md-9">
							<select class="form-control select2_el_poli" id="poliklinik1" name="poliklinik1" onchange="update(); cekruang()">
								<?php if($data->kodepos){ 
									$vpoli = data_master('tbl_namapos', array('kodepos' => $data->kodepos));
								?>
								<option value="<?= $data->kodepos;?>"><?= $data->kodepos.' | '.$vpoli->namapost;?></option>
								<?php } ?>
							</select>	 
						</div>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Booking Id <font color="red"></font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="booking" value="<?= $data->mjkn_token; ?>" name="booking">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Nama Dokter <font color="red">*</font></label>
						<div class="col-md-9">
							<select class="form-control select2_dokterx" id="dokter" name="dokter">
								<!-- <?php if($data->kodokter){ 
									$vdokter = data_master('tbl_dokter', array('kodokter' => $data->kodokter));
								?>
								<option value="<?= $data->kodokter;?>"><?= $data->kodokter.' | '.$vdokter->nadokter;?></option>
								<?php } ?> -->

								
								<?php 
								

									$vdokter2 = $this->db->query("SELECT * FROM dokter WHERE kodokter = '$data->kodokter' and  koders='$data->koders' and kopoli='$data->kodepos'
									union ALL
									select*from((select*from dokter where koders='$data->koders' and kopoli='$data->kodepos' and nadokter='-' limit 1)
									union all
									select*from dokter where koders='$data->koders' and kopoli='$data->kodepos' and nadokter <> '-'
									)a where kodokter<>'$data->kodokter'")->result();

									foreach($vdokter2 as $row){  
										
									$selected = ($row->kodokter==$data->kodokter?'selected':'');?>

									<option <?= $selected; ?> value="<?= $row->kodokter;?>"><?= $row->kodokter.' | '.$row->nadokter;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">No. Registrasi <font color="red"></font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="noreg" name="noreg" value="<?= $data->noreg;?>" readonly placeholder="otomatis">							
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Lokasi Praktek <font color="red">*</font></label>
						<div class="col-md-9">
							<select class="form-control lok_prak" id="ruang" name="ruang">
								<!-- <option value="">--- Pilih ---</option> -->
								<?php 
									$poli = $this->db->get("tbl_ruangpoli")->result();
									foreach($poli as $row){ 
										$selected = ($row->koderuang==$data->koderuang?'selected':'');
								?>
								<option <?= $selected; ?> value="<?= $row->koderuang;?>"><?= $row->namaruang;?></option>
								<?php } ?>
							</select>
						</div>
						
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Tanggal / Jam <font color="red">*</font></label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="date" class="form-control input-medium" id="tanggal" name="tanggal" placeholder="Otomatis" value="<?= date('Y-m-d', strtotime($data->tglmasuk));?>">
								<input type="time" class="form-control input-small" id="jam" name="jam" placeholder="Otomatis" value="<?= date('H:i:s', strtotime($data->jam));?>" >
							</div>							
						</div>
					</div>
				</div>
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Pengirim <font color="red">*</font></label>
						<div class="col-md-9">
							<select class="form-control select2_el_dokter" id="pengirim" name="pengirim">
								<?php if($data->drpengirim){ 
									$vdokter = data_master('tbl_dokter', array('kodokter' => $data->drpengirim));
								?>
								<option value="<?= $data->drpengirim;?>"><?= $data->drpengirim.' | '.$vdokter->nadokter;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div> -->
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">No. Antri <font color="red"></font></label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="text" class="form-control input-small" id="antrino1" name="antrino1" value="<?= $data->antrino1;?>" placeholder="" readonly>
								<input type="text" class="form-control input-medium " name="antrino" id="antrino" value="<?= $data->antrino;?>">
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">No. RM <font color="red"></font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="norm" name="norm" value="<?= $data->rekmed; ?>" placeholder="otomatis" readonly>								
							<input type="hidden" name="pengirim" id="pengirim">
						</div>
					</div>
				</div>
				<div class="col-md-6" id="penjamin">
					<div class="form-group">
						<label class="col-md-3 control-label">Jenis Pasien <font color="red">*</font></label>
						<div class="col-md-9">
							<select class="form-control jen_pas" id="jenispasien" name="jenispasien" onchange="getRuang()" onclick="getRuang()">
								<?php 
									$jenis = $this->db->get_where("tbl_setinghms", array("lset" => 'JPAS'))->result();
									foreach($jenis as $row){ 
										$selected = ($row->kodeset==$data->jenispas?'selected':'');
								?>
								<option <?= $selected;?> value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">NIK <font color="red">*</font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="noidentitas" name="noidentitas" value="<?= $data->noidentitas; ?>" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-6" id="card">
					<div class="form-group">
						<label class="col-md-3 control-label">Penjamin <font color="red"></font></label>
						<div class="col-md-9">
							<select class="form-control select2_el_penjamin" id="vpenjamin" name="vpenjamin">
								<?php if($data->cust_id){ 
									$vpenjamin = data_master('tbl_penjamin', array('cust_id' => $data->cust_id));
								?>
								<option value="<?= $data->cust_id;?>"><?= $vpenjamin->cust_nama;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Nama Pasien <font color="red">*</font></label>
						<div class="col-md-9">	
							<input type="text" class="form-control" id="namapasien" name="namapasien" value="<?= $data->namapas; ?>" readonly>
						</div>

					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">No. Kartu <font color="red">*</font></label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="nocard" name="nocard" value="<?= $data->nobpjs; ?>" maxlength="13">
						</div>

					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Umur / Seks <font color="red">*</font></label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="text" class="form-control input-medium" id="umur123" name="umur" value="" readonly>
								<input type="text" class="form-control input-small" id="jeniskelamin" name="jeniskelamin" value="<?= $data->jkel; ?>" placeholder="Pria/Wanita" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6" id="rujukan1">
					<div class="form-group">
						<label class="col-md-3 control-label">No. Rujukan <font color="red">*</font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="norujukan" name="norujukan" value="<?= $data->norujukan; ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">No. Hp <font color="red">*</font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="hp" name="hp" value="<?= $data->handphone; ?>" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-6" id="sep1">
					<div class="form-group">
						<label class="control-label col-md-3">No. Sep<font color="red">*</font></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="nosep" name="nosep" value="<?= $data->nosep; ?>">
						</div>
					</div>						
				</div>
			</div>			
			<div class="row">
				<div class="col-md-12">
					<button class="btn green" style="float: right"><i class="fa fa-check-square"></i> Briging Vclaim</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-actions">
						<button id="btnsimpaneditpasien" type="button" onclick="register()" class="btn blue"><i class="fa fa-save"></i> <b>Simpan Data Pasien</b></button>
						<div class="btn-group">
							<button type="button" id="btncetak" class="btn btn-warning"> <i class="fa fa-print"></i> <b>Cetak</b></button>
							<button type="button" id="btncetak1" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="#" onclick="javascript:_urlcetak1();">
										Kartu Pasien
									</a>
								</li>
								<li>
									<a href="#" onclick="javascript:_urlcetak2();">
										Tracer Pasien
									</a>
								</li>
							</ul>
						</div>
						<a class="btn green" type="button" href="<?= site_url('PendaftaranVRS/entri_rj'); ?>"><i class="fa fa-refresh"></i> Data Baru</a>
						<a class="btn red" href="<?php echo base_url('pendaftaranVRS')?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
						<br><br><br><br>
					</div>
				</div>
			</div>
		</form>
		<br>
	</div>
</div>

<?php
	$this->load->view('template/footer_tb');  
?>

<div class="modal fade" id="lup_pasien" role="dialog">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header header-custom">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Data Pasien</h3>
			</div>
			<div class="modal-body form">
				<div id="_datapasien"></div>
			</div>
		</div>
	</div>
</div>	

<div class="modal fade" id="modal_form" role="dialog">
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header header-custom">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Data Pasien</h3>
				</div>
			<form id="frmpasien2" class="form-horizontal" style="padding:20px;" method="post">
				<div class="modal-body form" style="margin-top:20px;">	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Cabang  <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control input-small" id="lupcabang" name="lupcabang" readonly value="<?= $this->session->userdata('unit'); ?>">	
								</div>
							</div>
						</div>				
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">No. RM <font color="red">*</font></label>
								<div class="col-md-9">
									<div class="input-group">
										<input type="text" class="form-control" id="lupnorm" name="lupnorm" placeholder="Otomatis" value="<?= $data->rekmed; ?>" readonly>
									</div>	 
								</div>
							</div>
						</div>
					</div>	
					<div class="row">		
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Pasien <font color="red">*</font></label>
								<div class="col-md-9">
									<div class="input-group">
										<select class="form-control input-small" name="preposition" id="luppreposition">
											<option value="<?= $data->preposisi; ?>">
												<?php $sql = $this->db->get_where('tbl_setinghms', ['kodeset' => $data->preposisi])->row_array(); ?>
												<?= $sql['keterangan']; ?>
											</option>
											<?php
												foreach(setinghms('PREP') as $row){ 
											?>
											<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
											<?php } ?>
										</select>
										<input type="text" class="form-control input-medium" name="lupnamapasien"  id="lupnamapasien" value="<?= $data->namapas; ?>" >
									</div>	
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Identitas <font color="red">*</font></label>
								<div class="col-md-9">
									<div class="input-group">
										<select class="form-control input-small" id="lupidentitas"
													name="lupidentitas">
											<option <?= ($data->idpas=='-'?'selected':'')?> value="-">-</option>
											<option <?= ($data->idpas=='KTP'?'selected':'')?> value="KTP">KTP</option>
											<option <?= ($data->idpas=='SIM'?'selected':'')?> value="SIM">SIM</option>
											<option <?= ($data->idpas=='PASPORT'?'selected':'')?> value="PASPORT">PASPORT</option>
											<option <?= ($data->idpas=='K_PELAJAR'?'selected':'')?>value="K_PELAJAR">K_PELAJAR</option>
											<option <?= ($data->idpas=='KMAHASISWA'?'selected':'')?> value="KMAHASISWA">KMAHASISWA</option>
													</select>
										<input type="text" class="form-control input-medium" name="lupnoidentitas" id="lupnoidentitas" value="<?= $data->noidentitas; ?>">
									</div>	
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Panggilan <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupnamapanggilan" name="lupnamapanggilan" value="<?= $data->namapanggilan; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Keluarga <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupnamakeluarga" name="lupnamakeluarga" value="<?= $data->namakeluarga; ?>" >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Tempat Lahir <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="luptempatlahir" name="luptempatlahir" value="<?= $data->tempatlahir; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Tanggal Lahir <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="date" class="form-control input-medium" onChange="tgllahir()" id="luptgllahir" name="luptgllahir" value="<?= date('Y-m-d', strtotime($data->tgllahir)); ?>" >
								</div>
							</div>
						</div>
					</div>			
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Kelamin <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control input-small" id="lupjeniskelamin" name="lupjeniskelamin">
										<option value="<?= $data->jkel; ?>">
											<?php if($data->jkel == 'P'){ echo 'Pria'; } else { echo 'Wanita'; } ?>
										</option>
										<?php if($data->jkel == 'P') { ?>
											<option value="W">Wanita</option> 
										<?php } else { ?>
										<option value="P">Pria</option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Umur <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control input-medium" id="lupumur" name="lupumur" value="" readonly>	
								</div>
							</div>
						</div>
					</div>			
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Status <font color="red"></font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_statuspasien" name="lupstatus" id="lupstatus">
										<option value="<?= $data->status; ?>">
											<?php $status = $this->db->get_where('tbl_setinghms', ['kodeset' => $data->status])->row_array(); ?>
											<?= $status['keterangan']; ?>
										</option>
										<?php
										foreach(setinghms('STAT') as $row){ ?>
										<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Warga Negara <font color="red"></font></label>
								<div class="col-md-9">
									<select class="form-control input-small" id="lupwarganegara" name="lupwarganegara">
										<option value="<?= $data->wn; ?>"><?= $data->wn; ?></option>
										<?php if($data->wn == 'WNI') : ?>
											<option value="WNA">WNA</option> 
										<?php else : ?>
											<option value="WNI">WNI</option> 
										<?php endif; ?>
									</select>
								</div>
							</div>
						</div>
					</div>			
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Agama <font color="red"></font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_agama" id="lupagama" name="lupagama">
										<option value="<?= $data->agama; ?>">
											<?php $agama = $this->db->get_where('tbl_setinghms', ['kodeset' => $data->agama])->row_array(); ?>
											<?= $agama['keterangan']; ?>
										</option>
										<?php
										foreach(setinghms('AGAM') as $row){ ?>
										<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Pendidikan <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_pendidikan" id="luppendidikan" name="luppendidikan">
										<option value="<?= $data->pendidikan; ?>">
											<?php $pendidikan = $this->db->get_where('tbl_setinghms', ['kodeset' => $data->pendidikan])->row_array(); ?>
											<?= $pendidikan['keterangan']; ?>
										</option>
										<?php
										foreach(setinghms('PEND') as $row){ ?>
										<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Gol. Darah <font color="red"></font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_goldarah" id="lupgoldarah" name="lupgoldarah">
										<option value="<?= $data->goldarah; ?>"><?= $data->goldarah; ?></option>
										<?php
										foreach(setinghms('GOLD') as $row){ ?>
										<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Hobby <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="luphobby" name="luphobby" value="<?= $data->hoby; ?>" >	
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Pekerjaan <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_pekerjaan" id="luppekerjaan" name="luppekerjaan">
										<option value="<?= $data->pekerjaan; ?>">
											<?php $pekerjaan = $this->db->get_where('tbl_setinghms', ['kodeset' => $data->pekerjaan])->row_array(); ?>
											<?= $pekerjaan['keterangan']; ?>
										</option>
										<?php
										foreach(setinghms('PEKE') as $row){ ?>
										<option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option> 
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat Sesuai KTP <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupalamat1" name="lupalamat1" value="<?= $data->alamat; ?>" >
								</div>
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">RT/RW <font color="red">*</font></label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="luprt" name="luprt" value="<?= $data->rt; ?>" >
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control" id="luprw" name="luprw" value="<?= $data->rw; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Kec / KabKota /Prov<font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupalamat2" name="lupalamat2" value="<?= $data->alamat2; ?>" >
								</div>
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Handphone <font color="red">*</font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="luphp" name="luphp" value="<?= $data->handphone;?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Provinsi <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control select2_el_provinsi2" id="lupprovinsi" name="lupprovinsi" onChange="getKota()" onclick="getKota()">
										<option value="<?= $data->propinsi; ?>">
											<?php $propinsi1 = $this->db->get_where('tbl_propinsi', ['kodeprop' => $data->propinsi])->row_array(); ?>
											<?= $propinsi1['namaprop']; ?>
										</option>
										<?php foreach($propinsi as $p) : ?>
											<option value="<?= $p->kodeprop; ?>"><?= $p->namaprop; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>			
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Phone <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupphone" name="lupphone" value="<?= $data->phone; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Kab/Kota <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control" name="kabkota" id="kabkota" style="width:100%;" onChange="getKecamatan()" onclick="getKecamatan()">
										<option value="<?= $data->kabupaten; ?>">
										<?php $kabupaten = $this->db->get_where('tbl_kabupaten', ['kodekab' => $data->kabupaten])->row_array(); ?>
										<?= $kabupaten['namakab']; ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Email <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupemail" name="lupemail" value="<?= $data->email; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Kecamatan <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control" name="lupkecamatan" id="lupkecamatan" onChange="getDesa()" onclick="getDesa()">
										<option value="<?= $data->kecamatan; ?>">
										<?php $kecamatan = $this->db->get_where('tbl_kecamatan', ['kodekec' => $data->kecamatan])->row_array(); ?>
										<?= $kecamatan['namakec']; ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">FB <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupfb" name="lupfb" value="<?= $data->fb; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Kelurahan <font color="red">*</font></label>
								<div class="col-md-9">
									<select class="form-control" name="lupkelurahan" id="lupkelurahan" onClick="getKP()">
										<option value="<?= $data->kelurahan; ?>">
										<?php $kelurahan = $this->db->get_where('tbl_desa', ['kodedesa' => $data->kelurahan])->row_array(); ?>
										<?= $kelurahan['namadesa']; ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Twitter <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="luptwitter" name="luptwitter" value="<?= $data->twit; ?>" >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Kode Pos <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupkodepos1" name="lupkodepos1" value="<?= $data->kdpos; ?>" placeholder="otomatis" readonly>
								</div>
							</div>
						</div>
					</div>			
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Instagram <font color="red"></font></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="lupig" name="lupig" value="<?= $data->ig; ?>" >
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnSave" onclick="save_pasien()" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="history_form" role="dialog">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header header-custom">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Data History Pasien</h3>
			</div>
			<div class="modal-body form">
				<form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="form-body">
					<div id="history_panel"></div>  
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(".select2_dokterx").select2();
	$(".lok_prak").select2("");
	$(".jen_pas").select2("");

function update() {
	var select = document.getElementById('poliklinik1').value;

	if(select=='PUMUM'){
	$('#antrino1').val('A');
		// GIGI
	}else if(select=='PGIGI'){
		$('#antrino1').val('B');
		// KIA
	}else if(select=='BIDAN'){
		$('#antrino1').val('C');
	}else{
		$('#antrino1').val('F');
	}

	$.ajax({
		url: "<?= site_url('PendaftaranVRS/get_dokter_rj');?>",
		type: "POST",
		data: ($('#frmpasien').serialize()),
		dataType: "JSON",
		success: function(data) {
		var opt = data;
		var nadokter = $("#dokter");
		nadokter.empty();
		$(opt).each(function() {
			var option = $("<option/>");
			option.html(this.nadokter);
			option.val(this.kodokter);
			nadokter.append(option);
		});
		}
	});
}

function cekruang(){

var poliklinik = $("#poliklinik1").val();

	$.ajax({
	url: "/PendaftaranVRS/cekruang/" + poliklinik,
	type: "GET",
	dataType: "JSON",
	success: function(data) {
		if (data.status == 0) {
		
		swal({
			title: "Kesalahan",
			html: "Cek Lagi",
			type: "error",
			confirmButtonText: "OK"
		}).then((value) => {
			return;
		});

		} else {
		$("#ruang").empty();
		$.each(data, function(key, value) {
			$("#ruang").append("<option value='" + value.koderuang + "'>" + value.namaruang +
			"</option>");
		});
		}
	}
	});

}

	// husain add
	$("#rujukan1").hide();
	$("#sep1").hide();
	// husain end

var jenispasien = document.getElementById('jenispasien').value;
var penjamin = document.getElementById('vpenjamin').value;
if(jenispasien != 'PAS1'){
	$('#penjamin').show();
	$('#card').show();
	$('#sep').show();
	$('#rujukan').show();
	$('#vpenjamin').val(penjamin).change();
} else {
	$('#penjamin').hide();
	$('#card').hide();
	$('#sep').hide();
	$('#rujukan').hide();
}

$('#penjamin').hide();
$('#card').hide();
$('#sep').hide();
$('#rujukan').hide();

if(jenispasien != 'PAS1'){
	$('#penjamin').show();
	$('#card').show();
	$('#sep').show();
	$('#rujukan').show();
	// $('#vpenjamin').val("BPJS").change();
} else {
	$('#penjamin').hide();
	$('#card').hide();
	$('#rujukan').hide();
	$('#sep').hide();
}

var jk = document.getElementById('jeniskelamin').value;
if(jk == 'P'){
	$('#jeniskelamin').val('Pria');
} else {
	$('#jeniskelamin').val('Wanita');
}

var birthDate = new Date($('#luptgllahir').val());
var usia = hitung_usia(birthDate);
$('#lupumur').val(usia);
$('#umur123').val(usia);

var segment = window.location.href;
if(segment == '<?= site_url("PendaftaranVRS/entri_igd"); ?>'){
	var selectElement = document.getElementById('poliklinik');
	var opt = document.createElement('option');
	opt.value = "PUGD";
	opt.innerHTML = "IGD";
	selectElement.appendChild(opt);			
	$('#poliklinik').val("PUGD");
	$('#poliklinik').attr("disabled", true);
}

function getKota(){
	var select = document.getElementById('lupprovinsi').value;
	$.ajax({
		url: "<?= site_url('PendaftaranVRS/get_kota');?>",
		type: "POST",
		data: ($('#frmpasien2').serialize()),
		dataType: "JSON",
		success:function(data){
			// console.log(data);
			var opt=data;
			var namakab = $("#kabkota");
			namakab.empty();
			$(opt).each(function () {
			var option = $("<option/>");
			option.html(this.namakab);
			option.val(this.kodekab);
			namakab.append(option);
			});
		}
	});
}

function getKecamatan(){
	var select = document.getElementById('kabkota').value;
	// console.log(select);
	$.ajax({
		url: "<?= site_url('PendaftaranVRS/get_kecamatan');?>",
		type: "POST",
		data: ($('#frmpasien2').serialize()),
		dataType: "JSON",
		success:function(data){
			// console.log(data);
			var opt=data;
			var namakec = $("#lupkecamatan");
			namakec.empty();
			$(opt).each(function () {
			var option = $("<option/>");
			option.html(this.namakec);
			option.val(this.kodekec);
			namakec.append(option);
			});
		}
	});
}

function getDesa(){
	var select = document.getElementById('lupkecamatan').value;
	$.ajax({
		url: "<?= site_url('PendaftaranVRS/get_desa');?>",
		type: "POST",
		data: ($('#frmpasien2').serialize()),
		dataType: "JSON",
		success:function(data){
			// console.log(data);
			var opt=data;
			var namadesa = $("#lupkelurahan");
			// namadesa.empty();
			$(opt).each(function () {
			var option = $("<option/>");
			option.html(this.namadesa);
			option.val(this.kodedesa);
			namadesa.append(option);
			});
		}
	});
}

function getKP(){
	var select = document.getElementById('lupkecamatan').value;
	// console.log(select);
	$.ajax({
		url: "<?= site_url('PendaftaranVRS/getKP');?>",
		type: "POST",
		data: ($('#frmpasien2').serialize()),
		dataType: "JSON",
		success:function(data){
			// console.log(data);
			var opt=data;
			var kp = $("#lupkodepos1");
			kp.val(opt.kodepos);
		}
	});
}

$(".select2_dokter_igd").select2({
	allowClear: true,
	multiple: false,  
	placeholder: '--- Pilih Dokter ---',  
	//minimumInputLength: 2,
	dropdownAutoWidth : true,
	language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Akun Biaya minimal 2 huruf';
		}
	},  
	ajax: {
		url: "<?php echo base_url();?>PendaftaranVRS/dokter_igd",
		type: "post",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
			searchTerm: params.term // search term
			};
		},
		processResults: function (response) {
			return {
			results: response
			};
		},
		cache: true
	}
});

function getRuang(){
	var jenispasien = document.getElementById('jenispasien').value;
	if(jenispasien != 'PAS1'){
		$('#penjamin').show(200);
		$('#card').show(200);
		$('#sep').show(200);
		$('#rujukan').show(200);
		$('#vpenjamin').val("BPJS").change();
	} else {
		$('#penjamin').hide(200);
		$('#card').hide(200);
		$('#sep').hide(200);
		$('#rujukan').hide(200);
	}
}

function _urlcetak1()
{
	var baseurl    = "<?php echo base_url()?>";
	var noreg      = $('[name="noreg"]').val();
if(noreg==''){
	swal({
	title   : "Data Pasien ",
	html    : "Belum Ada ...",
	type    : "error",
	confirmButtonText: "OK"
	});
	return;
}else{
	url = baseurl+'PendaftaranVRS/cetak_rj2/?noreg='+noreg
	window.open(url, '');
				
}
}

function _urlcetak2()
{
	var baseurl    = "<?php echo base_url()?>";
	var noreg      = $('[name="noreg"]').val();
	var umur      = $('#umur123').val();
if(noreg==''){
	swal({
	title   : "Data Pasien ",
	html    : "Belum Ada ...",
	type    : "error",
	confirmButtonText: "OK"
	});
	return;
}else{
	url = baseurl+'PendaftaranVRS/cetak_rj3/?noreg='+noreg+"&umur="+umur
	window.open(url, '');
}
}

function getinfopasien2(){
	var xhttp; 
	var vid = $('#pasien').val();
	$('#bpjs').show(200);
	$('#penjamin').show(200);
	$.ajax({
	url : "<?php echo base_url();?>PendaftaranVRS/getinfopasien/?id="+vid,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			console.log(data.kodokter);
			$('#idpasien').val(data.idtr);
			$('#idpasien2').val(data.idtr);
			$('#booking').val(data.mjkn_token);
			$('#namapasien').val(data.namapas);
			$('#dokter').val(data.kodokter).change();
			$('#ruang').val(data.koderuang).change();
			$('#vpengirim').val(data.drpengirim).change();
			$('#noreg').val(data.noreg);
			$('#norm').val(data.rekmed);
			$('#lupnorm').val(data.rekmed);
			$('#lupnamapasien').val(data.namapas);
			$('#nomember').val(data.rekmed);
			$('#namapanggilan').val(data.namapanggilan);
			$('#lupnamapanggilan').val(data.namapanggilan);
			$('#lupnamakeluarga').val(data.namakeluarga);
			$('#luptgllahir').val(data.tanggallahir);
			$('#luptempatlahir').val(data.tempatlahir);
			$('#lupalamat1').val(data.alamat);
			$('#lupprovinsi').val(data.namaprop);
			$('#kabkota').val(data.kabupaten).change();
			$('#lupkecamatan').val(data.kodekec).change();
			$('#lupkelurahan').val(data.kelurahan).change();
			$('#lupkodepos1').val(data.kodepos1).change();
			if(data.jenispas =='' || data.jenispas == null){
				$('#jenispasien').val('PAS1').change();
			} else if (data.jenispas != 'PAS2') {
				$('#jenispasien').val('PAS1').change();
			} else {
				$('#jenispasien').val(data.jenispas).change();
			}
			$('#lupalamat2').val(data.alamat2);
			$('#goldarah').val(data.goldarah);
			$('#hp').val(data.handphone);
			$('#luphp').val(data.handphone);
			$('#lupphone').val(data.phone);
			$('#identitas').val(data.idpas);
			$('#lupidentitas').val(data.idpas);
			$('#noidentitas').val(data.noidentitas);
			$('#lupnoidentitas').val(data.noidentitas);
			$('#warganegara').val(data.wn);
			$('#jeniskelamin').val(data.jkel);
			$('#lupjeniskelamin').val(data.jkel).change();
			$('#luptwitter').val(data.twit);
			$('#lupfb').val(data.fb);
			$('#lupig').val(data.ig);
			$('#luphobby').val(data.hoby);
			$('#lupnamakel').val(data.orhub);
			$('#hubungan').val(data.hubungan);
			$('#alamatkel').val(data.alamathub);
			$('#emailkeluarga').val(data.emailhub);
			$('#phonekeluarga').val(data.phonehub);
			$('#hpkeluarga').val(data.hphub);
			$('#luprt').val(data.rt);
			$('#luprw').val(data.rw);
			// $('#lupkodepos').val(data.kodepos);
			$('#lupinfoklinik').val(data.iklinik);
			$('#lupinfopas').val(data.cekiklinik);
			$('#umur123').val(hitung_usia(data.tanggallahir));
			var formtglalhir = $('#luptgllahir').val();
			if(formtglalhir == '' || formtglalhir == null){
				$('#lupumur').val(hitung_usia(data.tanggallahir));
			} else {
				$('#lupumur').val(hitung_usia(formtglalhir));
			}
			$('#nocard').val(data.nobpjs);
			$('#nosep').val(data.nosep);
			$('#norujukan').val(data.norujukan);
			$('#jenispas').val(data.jenispas);
			$('#antrino').val(data.antrino);

			var selectElement = document.getElementById('luppreposition');
			var opt = document.createElement('option');
			opt.value = data.preposisi;
			opt.innerHTML = data.namapreposisi;
			selectElement.appendChild(opt);
			$('#luppreposition').val(data.preposisi);
			
			var selectElement = document.getElementById('lupstatus');
			var opt = document.createElement('option');
			opt.value = data.status;
			opt.innerHTML = data.namastatus;
			selectElement.appendChild(opt);			
			$('#lupstatus').val(data.status);
			
			var selectElement = document.getElementById('lupgoldarah');
			var opt = document.createElement('option');
			opt.value = data.goldarah;
			opt.innerHTML = data.goldarah;
			selectElement.appendChild(opt);
			$('#lupgoldarah').val(data.goldarah);

			var selectElement = document.getElementById('lupwarganegara');
			var opt = document.createElement('option');
			opt.value = data.suku;
			opt.innerHTML = data.suku;
			selectElement.appendChild(opt);
			$('#lupwarganegara').val(data.suku);
						
			var selectElement = document.getElementById('lupcabang');
			var opt = document.createElement('option');
			opt.value = data.koders;
			opt.innerHTML = data.namars;
			selectElement.appendChild(opt);
			$('#lupcabang').val(data.koders);
		
				if(data.propinsi !=''){		
				var selectElement = document.getElementById('lupprovinsi');
				var opt = document.createElement('option');
				opt.value = data.propinsi;
				opt.innerHTML = data.namaprop;
				selectElement.appendChild(opt);
				$('#lupprovinsi').val(data.propinsi);
			}
			
		
			if(data.kabupaten !=''){
				var selectElement = document.getElementById('kabkota');
				var opt = document.createElement('option');
				opt.value = data.kabupaten;
				opt.innerHTML = data.namakab;
				opt.selected;
				selectElement.appendChild(opt);
				$('#kabkota').val(data.kabupaten);
			}
			
			if(data.kodekec !=''){
				var selectElement = document.getElementById('lupkecamatan');
				var opt = document.createElement('option');
				opt.value = data.kodekec;
				opt.innerHTML = data.namakec;
				selectElement.appendChild(opt);
				$('#lupkecamatan').val(data.kodekec);
			}
			
			if(data.kelurahan !=''){
				var selectElement = document.getElementById('lupkelurahan');
				var opt = document.createElement('option');
				opt.value = data.kelurahan;
				opt.innerHTML = data.namadesa;
				selectElement.appendChild(opt);
				$('#lupkelurahan').val(data.kelurahan);
			}
			
			var selectElement = document.getElementById('lupagama');
			var opt = document.createElement('option');
			opt.value = data.agama;
			opt.innerHTML = data.namaagama;
			selectElement.appendChild(opt);			
			$('#lupagama').val(data.agama);

			
			var selectPendidikan = document.getElementById('luppendidikan');
			var opt = document.createElement('option');
			opt.value = data.pendidikan;
			opt.innerHTML = data.namapendidikan;
			selectPendidikan.appendChild(opt);
			$('#luppendidikan').val(data.pendidikan);
										
			var selectPekerjaan = document.getElementById('luppekerjaan');
			var opt = document.createElement('option');
			opt.value = data.pekerjaan;
			opt.innerHTML = data.namapekerjaan;
			selectPekerjaan.appendChild(opt);
			$('#luppekerjaan').val(data.pekerjaan);
			
			$('#lupemail').val(data.email);
			$('#lupgoldarah').val(data.goldarah);
			$('#luptgllahir').trigger('change'); 

			// getHistori();
		}
	});
}

function tgllahir(){
	// $('#luptgllahir').on('change', function() {		
		var birthDate = new Date($('#luptgllahir').val());
		var usia = hitung_usia(birthDate);
		$('#lupumur').val(usia);
	// });
}

function add_pasien(){
	save_method = 'add';
	$('#form')[0].reset(); // reset form on modals
	$('.form-group').removeClass('has-error'); // clear error class
	$('.help-block').empty(); // clear error string
	$('#modal_form').modal('show'); // show bootstrap modal
	$('.modal-title').text('Pasien Baru'); // Set Title to Bootstrap modal title
}

function save_pasien() {
		var clupcabang = document.getElementById('lupcabang').value;
		var clupnorm = document.getElementById('lupnorm').value;
		var cluppreposition = document.getElementById('luppreposition').value;
		var clupnamapasien = document.getElementById('lupnamapasien').value;
		var clupidentitas = document.getElementById('lupidentitas').value;
		var clupnoidentitas = document.getElementById('lupnoidentitas').value;
		var clupnamapanggilan = document.getElementById('lupnamapanggilan').value;
		var clupnamakeluarga = document.getElementById('lupnamakeluarga').value;
		var cluptempatlahir = document.getElementById('luptempatlahir').value;
		var cluptgllahir = document.getElementById('luptgllahir').value;
		var birthDate = new Date($('#luptgllahir').val());
		var usia = hitung_usia(birthDate);
		$('#umur123').val(usia);
		var clupjeniskelamin = document.getElementById('lupjeniskelamin').value;
		var clupstatus = document.getElementById('lupstatus').value;
		var clupwarganegara = document.getElementById('lupwarganegara').value;
		var clupagama = document.getElementById('lupagama').value;
		var cluppendidikan = document.getElementById('luppendidikan').value;
		var clupgoldarah = document.getElementById('lupgoldarah').value;
		var cluphobby = document.getElementById('luphobby').value;
		var cluppekerjaan = document.getElementById('luppekerjaan').value;
		var clupalamat1 = document.getElementById('lupalamat1').value;
		var cluprt = document.getElementById('luprt').value;
		var cluprw = document.getElementById('luprw').value;
		var clupalamat2 = document.getElementById('lupalamat2').value;
		var cluphp = document.getElementById('luphp').value;
		var cekhp = cluphp.substring(0, 3);
		var clupprovinsi = document.getElementById('lupprovinsi').value;
		var cluptelp = document.getElementById('lupphone').value;
		var ckabkota = document.getElementById('kabkota').value;
		var clupemail = document.getElementById('lupemail').value;
		var clupkecamatan = document.getElementById('lupkecamatan').value;
		var clupfb = document.getElementById('lupfb').value;
		var clupkelurahan = document.getElementById('lupkelurahan').value;
		var cluptwitter = document.getElementById('luptwitter').value;
		var clupkodepos = document.getElementById('lupkodepos1').value;
		var clupig = document.getElementById('lupig').value;
		const tgl = new Date().toISOString().split('T')[0];

		// kondisi
		if ($('#jeniskelamin').val() == '' || $('#jeniskelamin').val() == null) {
			if (clupjeniskelamin == 'P') {
					clupjkel = 'Pria';
			} else {
					clupjkel = 'Wanita';
			}
			$('#jeniskelamin').val(clupjkel);
		}

		if ($('#luppreposition').val() == '' || $('#luppreposition').val() == null) {
			$('#luppreposition').val(cluppreposition);
		} else {
			$('#luppreposition').val();
		}

		if ($('#lupnamapasien').val() == '' || $('#lupnamapasien').val() == null) {
			$('#lupnamapasien').val(clupnamapasien);
		} else {
			$('#lupnamapasien').val();
		}

		if ($('#lupidentitas').val() == '' || $('#lupidentitas').val() == null) {
			$('#lupidentitas').val(clupidentitas);
		} else {
			$('#lupidentitas').val();
		}

		if ($('#lupnoidentitas').val() == '' || $('#lupnoidentitas').val() == null) {
			$('#lupnoidentitas').val(clupnoidentitas);
		} else {
			$('#lupnoidentitas').val();
		}

		if ($('#luptgllahir').val() == '' || $('#luptgllahir').val() == null) {
			$('#luptgllahir').val(cluptgllahir);
		} else {
			$('#luptgllahir').val();
		}

		if ($('#luptgllahir').val() == '' || $('#luptgllahir').val() == null) {
			$('#umur123').val(hitung_usia(cluptgllahir));
		} else {
			$('#umur123').val(hitung_usia(cluptgllahir));
		}

		if ($('#luppendidikan').val() == '' || $('#luppendidikan').val() == null) {
			$('#luppendidikan').val(cluppendidikan);
		} else {
			$('#luppendidikan').val();
		}

		if ($('#lupnamapasien').val() != '' || $('#lupnamapasien').val() != null) {
			$('#namapasien').val($('#lupnamapasien').val());
		}

		if ($('#lupnoidentitas').val() != '' || $('#lupnoidentitas').val() != null) {
			$('#noidentitas').val(clupnoidentitas);
		}

		if ($('#lupnoidentitas').val() != '' || $('#lupnoidentitas').val() != null) {
			$('#noidentitas').val(clupnoidentitas);
		}

		if ($('#luphp').val() != '' || $('#luphp').val() != null) {
			$('#hp').val(cluphp);
		}

		// alert
		if (cluppreposition == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "PREPOSISI",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupstatus == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "STATUS",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluptempatlahir == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "TEMPAT LAHIR",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupcabang == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "CABANG",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupnamapasien == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "NAMA PASIEN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluptgllahir == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "TANGGAL LAHIR",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluphp == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "NOMOR HP",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cekhp != '+62') {
			$('#modal_form').modal('hide');
			swal({
					title: "NOMOR HP",
					html: " Harus Di Awali +62 ",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupidentitas == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "PILIHAN IDENTITAS",
					html: " Pilih Dahulu .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupnoidentitas == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "NOMOR IDENTITAS",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupjeniskelamin == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "JENIS KELAMIN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupcabang == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "CABANG",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluppendidikan == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "PENDIDIKAN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupalamat2 == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "KEC / KAB KOTA / PROV",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluppekerjaan == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "PEKERJAAN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupalamat1 == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "ALAMAT SESUAI KTP",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluprt == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "RT",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (cluprw == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "RW",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupprovinsi == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "PROVINSI",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (ckabkota == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "KAB/KOTA",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupkecamatan == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "KECAMATAN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		if (clupkelurahan == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "KELURAHAN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}

		$('#modal_form').modal('hide');
		swal({
			title: "DATA PASIEN",
			html: "Akan dikonfirmasi",
			type: "success",
			confirmButtonText: "OK"
		});

		url = "<?php echo site_url('PendaftaranVRS/tambah_pasien_rawat_jalan')?>";
		$.ajax({
			url : url,
			type: "POST",
			data: ($('#frmpasien2').serialize()),
			dataType: "JSON",
			success: function(data){
					if(data.status == 0){
						swal({
							title: "DATA PASIEN",
							html: "Data berhasil tersimpan",
							type: "success",
							confirmButtonText: "OK" 
						}).then((value) => {
							$('#modal_form').modal('hide');
						});
					}
					if(data.status == 1){
						swal({
							title: "DATA PASIEN",
							text: "Ingin mengubah data ini?",
							icon: "warning",
							buttons: true,
							buttons: false,
							dangerMode: true,
						}).then((value) => {
							swal({
								title: "DATA PASIEN",
								html: "Data berhasil diubah",
								type: "success",
								confirmButtonText: "OK" 
							}).then((value) => {
								$('#modal_form').modal('hide');
							});
						}); 
					} else {
						swal({
							title: "DATA PASIEN",
							html: "Data gagal tersimpan",
							type: "error",
							confirmButtonText: "OK" 
						}).then((value) => {
							$('#modal_form').modal('hide');
						});
					}
			}
		});
	}



$('#lupnoidentitas').on('change', function() {
	var noktp = this.value;
	var prov = noktp.substring(0, 2);
	var kotakab = noktp.substring(0, 4);
	var kec = noktp.substring(0, 6);
	getprov(prov);
	getkot(kotakab);
	getkec(kec);
	$('#lupkecamatan').click();	
});

function getprov(kode) {
		$.ajax({
		url: "<?php echo base_url();?>PendaftaranVRS/namaprovinsi/?kode=" + kode,
		type: "POST",
		dataType: "JSON",
		success: function(data) {
			// console.log(data.kodeprop);
			// $('#lupprovinsi').val(data.kodeprop).change();
			// $('#kabkota').val(data.kodekab).change();	
			var opt = data;
			var lupprovinsi = $("#lupprovinsi");
			lupprovinsi.empty();
			$(opt).each(function() {
				console.log(this.namaprop);
				var option = $("<option/>");
				option.html(this.namaprop);
				option.val(this.kodeprop);
				lupprovinsi.append(option);
			});
			}
		});
}

function getkot(kode) {
	$.ajax({
		url: "<?php echo base_url();?>PendaftaranVRS/namakota/?kode=" + kode,
		type: "POST",
		dataType: "JSON",
		success: function(data) {
			// $('#kabkota').val(data.kodekab).change();	
			var opt = data;
			var kabkota = $("#kabkota");
			kabkota.empty();
			$(opt).each(function() {
				console.log(this.namakab);
				var option = $("<option/>");
				option.html(this.namakab);
				option.val(this.kodekab);
				kabkota.append(option);
			});
		}
	});
}


function getkec(kode) {
	$.ajax({
		url: "<?php echo base_url();?>PendaftaranVRS/namakecamatan/?kode=" + kode,
		type: "POST",
		dataType: "JSON",
		success: function(data) {
			// console.log(data.namakec);
			// $('#lupkecamatan').val(data.namakec).change();		
			var opt = data;
			var lupkecamatan = $("#lupkecamatan");
			lupkecamatan.empty();
			$(opt).each(function() {
				console.log(this.namakec);
				var option = $("<option/>");
				option.html(this.namakec);
				option.val(this.kodekec);
				lupkecamatan.append(option);
			});
			getdes(data.kodekec);
		}
	});
}

function getdes(kode) {
	var kec = document.getElementById('lupkecamatan').value;
	$.ajax({
		url: '<?php echo base_url() ?>PendaftaranVRS/getDesa/?kode=' + kec,
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			// console.log(data);
			var opt = data;
			var lupkelurahan = $("#lupkelurahan");
			lupkelurahan.empty();
			$(opt).each(function() {
				console.log(this.namadesa);
				var option = $("<option/>");
				option.html(this.namadesa);
				option.val(this.kodedesa);
				lupkelurahan.append(option);
			});
		}
	});
}

function register(){
		var norm          = document.getElementById('norm').value;
		var tanggal       = document.getElementById('tanggal').value;
		var jam           = document.getElementById('jam').value;
		var jenispasien   = document.getElementById('jenispasien').value;
		var poliklinik    = document.getElementById('poliklinik1').value;
		var penjamin      = document.getElementById('vpenjamin').value;
		var dokter        = document.getElementById('dokter').value;
		var antrino       = document.getElementById('antrino').value;
		var antrino1      = document.getElementById('antrino1').value;
		var pengirim      = document.getElementById('pengirim').value;
		var ruang         = document.getElementById('ruang').value;
		var booking       = document.getElementById('booking').value;
		var nocard        = document.getElementById('nocard').value;
		var norujukan     = document.getElementById('norujukan').value;
		var nosep         = document.getElementById('nosep').value;

		// alert
		if (poliklinik == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "POLIKLINIK",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
		if (dokter == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "NAMA DOKTER",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
		if (ruang == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "RUAMG",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
		if (tanggal == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "TANGGAL",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
		if (jam == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "JAM",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
		//   if (pengirim == '') {
		//        $('#modal_form').modal('hide');
		//        swal({
		//             title: "PENGIRIM",
		//             html: " Tidak Boleh Kosong .!!!",
		//             type: "error",
		//             confirmButtonText: "OK"
		//        }).then((value) => {
		//             $('#modal_form').modal('show');
		//        });
		//        $('#btnSave').text('save');
		//        $('#btnSave').attr('disabled', false);
		//        return;
		//   }
		if (jenispasien == '') {
			$('#modal_form').modal('hide');
			swal({
					title: "JENIS PASIEN",
					html: " Tidak Boleh Kosong .!!!",
					type: "error",
					confirmButtonText: "OK"
			}).then((value) => {
					$('#modal_form').modal('show');
			});
			$('#btnSave').text('save');
			$('#btnSave').attr('disabled', false);
			return;
		}
					var noregz = $("#noreg").val();
					// console.log(noregz)
					if(poliklinik != '' && dokter != '' && ruang != '' && tanggal != '' && jam != '' && jenispasien != ''){
						swal({
							title: 'UBAH DATA',
							text: "Yakin ingin mengubah data pasien ini ?",
							type: 'info',
							showCancelButton: true,
							confirmButtonClass: 'btn btn-success',
							cancelButtonClass: 'btn btn-success',
							confirmButtonColor: '#227dff',
							cancelButtonColor: '#d33',
							confirmButtonText: 'UBAH',
							cancelButtonText: 'TIDAK',
						}).then(function() {
							url = "<?php echo site_url('PendaftaranVRS/edit_rawatjalan?noreg=')?>"+noregz;
							$.ajax({
									url : url,
									type: "POST",
									data: ($('#frmpasien').serialize()),
									dataType: "JSON",
									success: function(data){
										if(data.status == 0){
											swal({
												title: "DATA PASIEN",
												html: "Data berhasil diupdate",
												type: "success",
												confirmButtonText: "OK" 
											}).then((value) => {
												$('#modal_form').modal('hide');
												$("#btnsimpaneditpasien").attr('disabled', true);
											});
										} else {
											swal({
												title: "DATA PASIEN",
												html: "Data gagal diupdate",
												type: "error",
												confirmButtonText: "OK" 
											}).then((value) => {
												$('#modal_form').modal('hide');
											});
										}
									}
							});
						});
					}
	}
</script>
