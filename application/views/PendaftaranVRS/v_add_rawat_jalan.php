<?php 
	$this->load->view('template/header');
	$this->load->view('template/body');    
	date_default_timezone_set("Asia/Jakarta");	
?>

<style>
  .hidden {
    display: none;
  }

  .flex {
    display: flex;
  }

  .gap {
    gap: 10px
  }

  .flex-col {
    flex-direction: column;
  }

  .pointer{
    cursor: pointer;
  }
</style>

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
              <div class="input-group flex gap">
                <span class="btn-sm btn blue pointer" onclick="pasienLama(this)"><i class="fa fa-id-card"></i> Pasien Lama</span>
                <div class="hidden" id="pasien_lama">
                  <select class="form-control select2_el_pasien input-medium" onChange="getinfopasien()" id="pasien"
                    name="pasien">
                  </select>
                </div>
                <input type="hidden" id="idpasien">
                <div class="input-group-btn" id="pasien_baru">
                  <a class="btn-sm btn green" id="plus" onclick="add_pasien()"><i class="fa fa-plus"></i> Pasien Baru</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">Poliklinik <font color="red">*</font></label>
            <div class="col-md-9">
              <select name="poliklinik1" id="poliklinik1" class="form-control select2_el_poli" onchange="update(); cekruang()"
                style="width:100%;">
                <option value="">-- Pilih --</option>
                <?php foreach($namapos as $pos): ?>
                <option value="<?= $pos->kodepos;?>"><?= $pos->namapost;?></option>
                <?php endforeach; ?>
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
              <input type="text" class="form-control" id="booking" name="booking">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">Nama Dokter <font color="red">*</font></label>
            <div class="col-md-9">
              <select name="dokter" class="form-control select2_dokter" id="dokter"></select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">No. Registrasi <font color="red"></font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="noreg" name="noreg" value="" readonly placeholder="otomatis">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">Lokasi Praktek <font color="red">*</font></label>
            <div class="col-md-9">
              <select class="form-control select2_lokasix" id="ruang" name="ruang">
                
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
                <input type="date" class="form-control input-medium" id="tanggal" name="tanggal" placeholder="Otomatis"
                  value="<?= date('Y-m-d');?>">
                <input type="time" class="form-control input-small" id="jam" name="jam" placeholder="Otomatis"
                  value="<?= date('H:i:s');?>">
              </div>
            </div>
          </div>
        </div>
          <!-- <div class="col-md-6">
            <div class="form-group">
              <label class="col-md-3 control-label">Pengirim </label>
              <div class="col-md-9"> 
                <select class="form-control select2_el_dokter" style="width:100%;" id="pengirim" name="pengirim"></select>
              </div>
            </div>
          </div> -->
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">No. Antri <font color="red"></font></label>
            <div class="col-md-9">
              <div class="input-group">
                <input type="text" class="form-control input-small" id="antrino1" name="antrino1" value="" placeholder="" readonly>
                <input type="text" class="form-control input-medium " name="antrino" id="antrino" value="1">
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
                <input type="hidden" name="pengirim" id="pengirim">
                <input type="text" class="form-control" id="norm" name="norm" value="" placeholder="otomatis" readonly>
              </div>
            </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">Jenis Pasien <font color="red">*</font></label>
            <div class="col-md-9">
              <select class="form-control select2_el_jenispasien" style="width:100%;" id="jenispasien" name="jenispasien" onchange="getRuang()">
                <option value="">--- Pilih ---</option>
                <?php $jenis = $this->db->get_where("tbl_setinghms", array("lset" => 'JPAS'))->result();
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
              <input type="text" class="form-control" id="noidentitas" name="noidentitas" value="" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6" id="penjamin">
          <div class="form-group">
            <label class="col-md-3 control-label">Penjamin <font color="red"></font></label>
            <div class="col-md-9">
              <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin" name="vpenjamin" onchange="get_pcare(this.value)">
                <option value="">--- Pilih ---</option>
                <?php $penjamin = $this->db->get_where("tbl_penjamin", array("cust_id" => 'BPJS'))->result();
								foreach($penjamin as $row){ 
								$selected = ($row->cust_id==$data->cust_nama?'selected':'');
								?>
                <option <?= $selected;?> value="<?= $row->cust_id;?>"><?= $row->cust_nama;?></option>
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
              <input type="text" class="form-control" id="namapasien" name="namapasien" value="" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6" id="card">
          <div class="form-group">
            <label class="col-md-3 control-label">No. Kartu <font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="nocard" name="nocard" value="" maxlength="13">
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
                <input type="text" class="form-control input-small" id="jeniskelamin" name="jeniskelamin" value=""
                  placeholder="Pria/Wanita" readonly>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6" id="rujukan1">
          <div class="form-group">
            <label class="col-md-3 control-label">No. Rujukan <font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="norujukan" name="norujukan" value="" maxlength="17">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="col-md-3 control-label">No. Hp <font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="hp" name="hp" value="" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6" id="sep1">
          <div class="form-group">
            <label class="control-label col-md-3">No. Sep<font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="nosep" name="nosep" value="" maxlength="19">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="button" class="btn blue" style="float: right" onclick="vpcare();"  id="pcare" name="pcare"> 
            <i class="fa fa-check-square"></i> Bridging PCare
          </button><br><br>
        </div>
        <div class="col-md-12">
          <button type="button" class="btn green" style="float: right"><i class="fa fa-check-square"></i> Bridging
            Vclaim</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-actions">
            <button id="btnsimpaneditpasien" type="button" onclick="register()" class="btn blue"><i
                class="fa fa-save"></i> <b>Simpan Data Pasien</b></button>
            <div class="btn-group">
              <button type="button" id="btncetak" class="btn btn-warning"> 
                <i class="fa fa-print"></i>
                <b>Cetak</b></button>
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
            <button class="btn green" type="button" onClick="window.location.reload();"><i class="fa fa-refresh"></i>
              Data Baru</button>
            <a class="btn red" href="<?php echo base_url('pendaftaranVRS')?>"><i class="fa fa-undo"></i><b> KEMBALI
              </b></a>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Data Pasien</h3>
      </div>
      <div class="modal-body form">
        <div id="_datapasien">
        </div>
      </div>
    </div>
  </div>

</div>

<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog modal-full">
    <div class="modal-content">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Data Pasien</h3>
      </div>
      <form id="frmpasien2" class="form-horizontal" style="padding:20px;" method="post">
        <div class="modal-body form" style="margin-top:20px;">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Cabang <font color="red">*</font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control input-small" id="lupcabang" name="lupcabang" readonly
                    value="<?= $this->session->userdata('unit'); ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">No. RM <font color="red">*</font></label>
                <div class="col-md-9">
                  <div class="input-group">
                    <input type="text" class="form-control" id="lupnorm" name="lupnorm" placeholder="Otomatis" value=""
                      readonly>
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
                    <select class="form-control input-small" name="luppreposition" id="luppreposition">
                      <option value="">-- Pilih --</option>
                      <?php foreach(setinghms('PREP') as $row){ ?>
                      <option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
                      <?php } ?>
                    </select>
                    <input type="text" class="form-control input-medium" name="lupnamapasien" id="lupnamapasien"
                      value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Identitas <font color="red">*</font></label>
                <div class="col-md-9">
                  <div class="input-group">
                    <select class="form-control input-small" name="lupidentitas" id="lupidentitas" onchange="ktp()"
                      onclick="ktp()">
                      <option value="">-- Pilih --</option>
                      <option value="KTP">KTP</option>
                      <option value="SIM">SIM</option>
                      <option value="PASPORT">PASPORT</option>
                      <option value="K_PELAJAR">K_PELAJAR</option>
                      <option value="KMAHASISWA">KMAHASISWA</option>
                    </select>
                    <input type="text" class="form-control input-medium" name="lupnoidentitas" id="lupnoidentitas"
                      value="">
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
                  <input type="text" class="form-control" id="lupnamapanggilan" name="lupnamapanggilan" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Nama Keluarga <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupnamakeluarga" name="lupnamakeluarga" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Tempat Lahir <font color="red">*</font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="luptempatlahir" name="luptempatlahir" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Tanggal Lahir <font color="red">*</font></label>
                <div class="col-md-9">
                  <input type="date" class="form-control input-medium" onchange="tgllahir()" id="luptgllahir"
                    name="luptgllahir" value="">
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
                    <option value="">-- Pilih --</option>
                    <option value="P">Pria</option>
                    <option value="W">Wanita</option>
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
                <label class="col-md-3 control-label">Status <font color="red">*</font></label>
                <div class="col-md-9">
                  <select class="form-control select2_el_statuspasien" name="lupstatus" id="lupstatus">
                    <option value="">-- Pilih --</option>
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
                    <option value="">-- Pilih --</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
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
                    <option value="">-- Pilih --</option>
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
                    <option value="">-- Pilih --</option>
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
                    <option value="">-- Pilih --</option>
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
                  <input type="text" class="form-control" id="luphobby" name="luphobby" value="">
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
                    <option value="">-- Pilih --</option>
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
                <label class="col-md-3 control-label">Alamat Sesuai KTP <font color="red">*</font>
                </label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupalamat1" name="lupalamat1" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">RT/RW <font color="red">*</font></label>
                <div class="col-md-2">
                  <input type="text" class="form-control" id="luprt" name="luprt" value="">
                </div>
                <div class="col-md-2">
                  <input type="text" class="form-control" id="luprw" name="luprw" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Kec / KabKota /Prov<font color="red">*</font>
                </label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupalamat2" name="lupalamat2" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Handphone <font color="red">*</font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="luphp" name="luphp" placeholder="Diawali dengan +62" value="+62">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Provinsi <font color="red">*</font></label>
                <div class="col-md-9">
                  <select class="form-control select2_el_provinsi" id="lupprovinsi" name="lupprovinsi"
                    onChange="getKota()" onclick="getKota()">
                    <option value="">-- Pilih --</option>
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
                  <input type="text" class="form-control" id="lupphone" name="lupphone" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Kab/Kota <font color="red">*</font></label>
                <div class="col-md-9">
                  <select class="form-control" name="kabkota" id="kabkota" style="width:100%;" onChange="getKecamatan()"
                    onclick="getKecamatan()"></select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Email <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupemail" name="lupemail" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Kecamatan <font color="red">*</font></label>
                <div class="col-md-9">
                  <select class="form-control" name="lupkecamatan" id="lupkecamatan" onChange="getDesa()"
                    onclick="getDesa()"></select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">FB <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupfb" name="lupfb" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Kelurahan <font color="red">*</font></label>
                <div class="col-md-9">
                  <select class="form-control" name="lupkelurahan" id="lupkelurahan" onClick="getKP()"></select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Twitter <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="luptwitter" name="luptwitter" value="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Kode Pos <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupkodepos1" name="lupkodepos1" value=""
                    placeholder="otomatis" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Instagram <font color="red"></font></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="lupig" name="lupig" value="">
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Data History Pasien</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div id="history_panel"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" name="now" id="now" value="<?= date('Y-m-d'); ?>">
<script>
// husaina add
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
// end husain

// husain add
$("#rujukan1").hide();
$("#sep1").hide();
// end husain
$('#luppreposition').on('change', function() {
  var prep = this.value;
  $.ajax({
    url: "<?php echo base_url();?>app/getvaluesetinghms/?kode=" + prep,
    type: "GET",
    dataType: 'json',
    success: function(data) {
      var hasil = eval(data.data.valuerp);
      if (hasil == 2) {
        $('#lupjeniskelamin').val('W');
      } else {
        $('#lupjeniskelamin').val('P');
      }

    }
  });

});

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

function getinfopasien() {
  var xhttp;
  var vid = $('#pasien').val();
  $('#bpjs').show(200);
  $('#penjamin').show(200);
  $.ajax({
    url: "<?php echo base_url();?>PendaftaranVRS/getinfopasien/?id=" + vid,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      console.log(data);
      if(data.ada == 1){
        const tanggalx = new Date(data.tglmasuk);
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        swal({
            title: "REGISTRASI PASIEN TIDAK BISA DILAKUKAN",
            // html: "Pasien atas nama : <b>"+data.namapas+"</b><br>Sudah registrasi di cabang : <b>"+data.koders_regist+"</b><br>Silahkan hubungi admin cabang : <b>"+data.koders+"</b>",
            html: "<b>Close Bill</b> atau <b>Batalkan</b> pasien terlebih dahulu<br>Dengan nomor bill : "+data.noreg+"<br>Poliklinik : "+data.kodepos+"<br>Tanggal : "+tanggalx.getDate()+" "+months[tanggalx.getMonth()]+" "+tanggalx.getFullYear(),
            type: "error",
            confirmButtonText: "OK"
          }).then((value) => {
            window.location.reload();
          });
      } else {
        if (data.keluar == 0) {
          $('#poliklinik1').val(data.kodepos).change();
          $('#ruang').val(data.koderuang).change();
          if (data.drpengirim != '') {
            var selectElement = document.getElementById('pengirim');
            var opt = document.createElement('option');
            opt.value = data.drpengirim;
            opt.innerHTML = data.drpengirim;
            selectElement.appendChild(opt);
            $('#pengirim').val(data.drpengirim);
          }
          if (data.kodokter != '') {
            var selectElement = document.getElementById('dokter');
            var opt = document.createElement('option');
            opt.value = data.kodokter;
            opt.innerHTML = data.nadokter;
            selectElement.appendChild(opt);
            $('#dokter').val(data.kodokter);
          }
          $('#antrino').val(data.antrino);
          if (data.jenispas == '' || data.jenispas == null) {
            $('#penjamin').hide();
            $('#card').hide();
            $('#sep').hide();
            $('#rujukan').hide();
          } else {
            $('#jenispasien').val(data.jenispas).change();
          }
          swal({
            title: "PASIEN",
            html: "Sudah keluar",
            type: "info",
            confirmButtonText: "OK"
          }).then((value) => {
            $('#modalruangkamar').modal('show');
          });
        } else if (data.keluar == 1) {
          $('#poliklinik1').empty();
          $('#dokter').empty();
          // $('#ruang').empty();
          $('#vpenjamin').empty();
          $('#antrino').val('1');
          $('#jenispasien').empty();
          $('#pengirim').empty();
          $('#penjamin').hide(200);
          $('#card').hide(200);
          $('#sep').hide(200);
          $('#rujukan').hide(200);
        }
        $('#idpasien').val(data.idtr);
        $('#idpasien2').val(data.idtr);
        $('#booking').val(data.mjkn_token);
        $('#namapasien').val(data.namapas);
        // $('#ruang').val(data.koderuang).change();
        // $('#noreg').val(data.noreg);
        $('#norm').val(data.rekmed);
        // $('#poliklinik1').val(data.kodepos).change();
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
        $('#lupkodepos1').val(data.kodepos1).change();
        // if(data.jenispas == '' || data.jenispas == null){
        //      $('#penjamin').hide();
        //      $('#card').hide();
        //      $('#sep').hide();
        //      $('#rujukan').hide();
        // } else {
        //      $('#jenispasien').val(data.jenispas).change();
        // }
        $('#lupalamat2').val(data.alamat2);
        $('#goldarah').val(data.goldarah);
        $('#hp').val(data.handphone);
        $('#luphp').val(data.handphone);
        $('#lupphone').val(data.phone);
        $('#identitas').val(data.idpas);
        $('#lupidentitas').val(data.idpas);
        $('#noidentitas').val(data.noidentitas);
        $('#lupnoidentitas').val(data.noidentitas);
        if (data.jkel == 'P') {
          $('#jeniskelamin').val('Pria');
        } else {
          $('#jeniskelamin').val('Wanita');
        }
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
        if (formtglalhir == '' || formtglalhir == null) {
          $('#lupumur').val(hitung_usia(data.tanggallahir));
        } else {
          $('#lupumur').val(hitung_usia(formtglalhir));
        }
        $('#nocard').val(data.nobpjs);
        $('#nosep').val(data.nosep);
        $('#norujukan').val(data.norujukan);
        $('#jenispasien').val(data.jenispas);
        // $('#antrino').val(data.antrino);
        // $('#vpenjamin').val(data.cust_id).change();
  
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
        opt.value = data.wn;
        opt.innerHTML = data.wn;
        selectElement.appendChild(opt);
        $('#lupwarganegara').val(data.wn);
  
        var selectElement = document.getElementById('lupcabang');
        var opt = document.createElement('option');
        opt.value = data.koders;
        opt.innerHTML = data.namars;
        selectElement.appendChild(opt);
        $('#lupcabang').val(data.koders);
  
        if (data.cust_nama != '') {
          var selectElement = document.getElementById('vpenjamin');
          var opt = document.createElement('option');
          opt.value = data.cust_id;
          opt.innerHTML = data.cust_nama;
          selectElement.appendChild(opt);
          $('#vpenjamin').val(data.cust_id);
        }
  
        // if(data.drpengirim !=''){		
        //           var selectElement = document.getElementById('pengirim');
        //           var opt = document.createElement('option');
        //           opt.value = data.drpengirim;
        //           opt.innerHTML = data.drpengirim;
        //           selectElement.appendChild(opt);
        //           $('#pengirim').val(data.drpengirim);
        //      }
  
        // if(data.kodokter !=''){		
        //           var selectElement = document.getElementById('dokter');
        //           var opt = document.createElement('option');
        //           opt.value = data.kodokter;
        //           opt.innerHTML = data.nadokter;
        //           selectElement.appendChild(opt);
        //           $('#dokter').val(data.kodokter);
        //      }
  
        if (data.propinsi != '') {
          var selectElement = document.getElementById('lupprovinsi');
          var opt = document.createElement('option');
          opt.value = data.propinsi;
          opt.innerHTML = data.namaprop;
          selectElement.appendChild(opt);
          $('#lupprovinsi').val(data.propinsi);
        }
  
  
        if (data.kabupaten != '') {
          var selectElement = document.getElementById('kabkota');
          var opt = document.createElement('option');
          opt.value = data.kabupaten;
          opt.innerHTML = data.namakab;
          opt.selected;
          selectElement.appendChild(opt);
          $('#kabkota').val(data.kabupaten);
        }
  
        if (data.kecamatan != '') {
          var selectElement = document.getElementById('lupkecamatan');
          var opt = document.createElement('option');
          opt.value = data.kecamatan;
          opt.innerHTML = data.namakec;
          selectElement.appendChild(opt);
          $('#lupkecamatan').val(data.kecamatan);
        }
  
        if (data.kelurahan != '') {
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
      }
    }
  });
}
</script>

<!-- modal -->
<script>
function add_pasien() {
  save_method = 'add';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $('#modal_form').modal('show'); // show bootstrap modal
  $('.modal-title').text('Pasien Baru'); // Set Title to Bootstrap modal title
}


cabb();

function cabb() {
  var vid = 'aaa';
  $.ajax({
    url: "<?php echo base_url();?>app/search_cabang2/?id=" + vid,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      $('#cabang').val(data.id);

    }
  });
}

function ktp() {
  var ktp = document.getElementById('lupidentitas').value;
  if (ktp == "KTP") {
    $('#lupnoidentitas').on('change', function() {
      if($("#lupnoidentitas").val() != "") {
        if($("#lupnoidentitas").val() == "-"){
          $.ajax({
            url: "<?php echo base_url();?>PendaftaranVRS/namaprovinsi_all/",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
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
              $("#kabkota").val("").change();
              $("#lupkecamatan").val("").change();
              $("#lupkelurahan").val("").change();
            }
          });
        } else {
          var noktp = this.value;
          var prov = noktp.substring(0, 2);save
          var kotakab = noktp.substring(0, 4);
          var kec = noktp.substring(0, 6);
          getprov(prov);
          getkot(kotakab);
          getkec(kec);
          $('#lupkecamatan').click();
        }
      } else {
        $.ajax({
          url: "<?php echo base_url();?>PendaftaranVRS/namaprovinsi_all/",
          type: "POST",
          dataType: "JSON",
          success: function(data) {
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
            $("#kabkota").val("").change();
            $("#lupkecamatan").val("").change();
            $("#lupkelurahan").val("").change();
          }
        });
      }
    });
  }
}

function getprov(kode) {
  $.ajax({
    url: "<?php echo base_url();?>PendaftaranVRS/namaprovinsi/?kode=" + kode,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
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

$(".select2_dokter").select2("");
$(".select2_lokasix").select2();

function update() {
  var select = document.getElementById('poliklinik1').value;
  initailizeSelect2_dokter(select);
    // UMUM
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

$('#penjamin').hide();
$('#card').hide();
$('#sep').hide();
$('#rujukan').hide();

function getKota() {
  var select = document.getElementById('lupprovinsi').value;
  $.ajax({
    url: "<?= site_url('PendaftaranVRS/get_kota');?>",
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
      // console.log(data);
      var opt = data;
      var namakab = $("#kabkota");
      namakab.empty();
      $(opt).each(function() {
        var option = $("<option/>");
        option.html(this.namakab);
        option.val(this.kodekab);
        namakab.append(option);
      });
    }
  });
}

function getKecamatan() {
  var select = document.getElementById('kabkota').value;
  $.ajax({
    url: "<?= site_url('PendaftaranVRS/get_kecamatan');?>",
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
      var opt = data;
      var namakec = $("#lupkecamatan");
      namakec.empty();
      $(opt).each(function() {
        var option = $("<option/>");
        option.html(this.namakec);
        option.val(this.kodekec);
        namakec.append(option);
      });
    }
  });
}

function getDesa() {
  var kec = document.getElementById('lupkecamatan').value;
  $.ajax({
    url: "<?= site_url()?>PendaftaranVRS/get_desa",
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
      var opt = data;
      var namadesa = $("#lupkelurahan");
      namadesa.empty();
      $(opt).each(function() {
        var option = $("<option/>");
        option.html(this.namadesa);
        option.val(this.kodedesa);
        namadesa.append(option);
      });
    }
  });
}

function getKP() {
  var select = document.getElementById('lupkecamatan').value;
  $.ajax({
    url: "<?= site_url('PendaftaranVRS/getKP');?>",
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
      var opt = data;
      var kp = $("#lupkodepos1");
      kp.val(opt.kodepos);
    }
  });
}

function tgllahir() {
  var birthDate = new Date($('#luptgllahir').val());
  var usia = hitung_usia(birthDate);
  $('#lupumur').val(usia);
}

function save_pasien() {
  var clupcabang        = document.getElementById('lupcabang').value;
  var clupnorm          = document.getElementById('lupnorm').value;
  var cluppreposition   = document.getElementById('luppreposition').value;
  var clupnamapasien    = document.getElementById('lupnamapasien').value;
  var clupidentitas     = document.getElementById('lupidentitas').value;
  var clupnoidentitas   = document.getElementById('lupnoidentitas').value;
  var clupnamapanggilan = document.getElementById('lupnamapanggilan').value;
  var clupnamakeluarga  = document.getElementById('lupnamakeluarga').value;
  var cluptempatlahir   = document.getElementById('luptempatlahir').value;
  var cluptgllahir      = document.getElementById('luptgllahir').value;
  var birthDate         = new Date($('#luptgllahir').val());
  var usia              = hitung_usia(birthDate);
  $('#umur123').val(usia);
  var clupjeniskelamin  = document.getElementById('lupjeniskelamin').value;
  var clupstatus        = document.getElementById('lupstatus').value;
  var clupwarganegara   = document.getElementById('lupwarganegara').value;
  var clupagama         = document.getElementById('lupagama').value;
  var cluppendidikan    = document.getElementById('luppendidikan').value;
  var clupgoldarah      = document.getElementById('lupgoldarah').value;
  var cluphobby         = document.getElementById('luphobby').value;
  var cluppekerjaan     = document.getElementById('luppekerjaan').value;
  var clupalamat1       = document.getElementById('lupalamat1').value;
  var cluprt            = document.getElementById('luprt').value;
  var cluprw            = document.getElementById('luprw').value;
  var clupalamat2       = document.getElementById('lupalamat2').value;
  var cluphp            = document.getElementById('luphp').value;
  var cekhp             = cluphp.substring(0, 3);
  var clupprovinsi      = document.getElementById('lupprovinsi').value;
  var cluptelp          = document.getElementById('lupphone').value;
  var ckabkota          = document.getElementById('kabkota').value;
  var clupemail         = document.getElementById('lupemail').value;
  var clupkecamatan     = document.getElementById('lupkecamatan').value;
  var clupfb            = document.getElementById('lupfb').value;
  var clupkelurahan     = document.getElementById('lupkelurahan').value;
  var cluptwitter       = document.getElementById('luptwitter').value;
  var clupkodepos       = document.getElementById('lupkodepos1').value;
  var clupig            = document.getElementById('lupig').value;
  const tgl             = new Date().toISOString().split('T')[0];

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

  url = "<?php echo site_url('PendaftaranVRS/tambah_pasien_rawat_jalan')?>";
  $.ajax({
    url: url,
      type: "POST",
      data: ($('#frmpasien2').serialize()),
      dataType: "JSON",
      success: function(data) {
      // console.log(data);
      if (data.status == 0) {
        $('#modal_form').modal('hide');
        swal({
          title: "DATA PASIEN",
          html: "Data berhasil tersimpan",
          type: "success",
          confirmButtonText: "OK"
        }).then((value) => {
          $('#modal_form').modal('hide');
        });
        $('#norm').val(data.norm);
      }  else if (data.status == 1) {
        $('#modal_form').modal('hide');
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
        $('#modal_form').modal('hide');
        swal({
          title: "DATA PASIEN",
          text: "Data ini sudah terdaftar !!",
          icon: "info",
          buttons: true,
          buttons: false,
          dangerMode: true,
          confirmButtonText: "OK"
        }).then((value) => {
          $('#modal_form').modal('show');
        });
      }
    }
  });
}
</script>

<!-- form -->
<script>
$(".select2_dokter_igd").select2({
  allowClear: true,
  multiple: false,
  placeholder: '--- Pilih Dokter ---',
  //minimumInputLength: 2,
  dropdownAutoWidth: true,
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
    data: function(params) {
      return {
        searchTerm: params.term // search term
      };
    },
    processResults: function(response) {
      return {
        results: response
      };
    },
    cache: true
  }
});

document.getElementById('pcare').style.visibility="hidden";

function get_pcare(vpenjamin) {
  
  if (vpenjamin == "BPJS") {
    document.getElementById('pcare').style.visibility="visible";
  } else {
    document.getElementById('pcare').style.visibility="hidden";
  }
}

function vpcare()
{
    // var nampasdet = document.getElementById("nampasdet").value;
    var noregdet  = $('#noreg').val();
    var rekmeddet = '000459';
    url="<?php echo base_url()?>PendaftaranVRS/pcare_rj/?noreg="+noregdet+"&rekmed="+rekmeddet
    
    window.open(url,'_blank');
    window.focus();
}

function getRuang() {
  var jenispasien = document.getElementById('jenispasien').value;
  if (jenispasien != 'PAS1') {
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

function register() {
  var norm          = document.getElementById('norm').value;
  var tanggal       = document.getElementById('tanggal').value;
  var jam           = document.getElementById('jam').value;
  // var jenispasien   = document.getElementById('jenispasien').value;
  var jenispasien   = $('#jenispasien').val();
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
      // $('#modal_form').modal('show');
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
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
      // $('#modal_form').modal('show');
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
    return;
  }
  if (ruang == '') {
    $('#modal_form').modal('hide');
    swal({
      title: "Lokasi Praktek ",
      html: " Tidak Boleh Kosong .!!!",
      type: "error",
      confirmButtonText: "OK"
    }).then((value) => {
      // $('#modal_form').modal('show');
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
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
      // $('#modal_form').modal('show');
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
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
      // $('#modal_form').modal('show');
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
    return;
  }
  // if (pengirim == '') {
  //   $('#modal_form').modal('hide');
  //   swal({
  //     title: "PENGIRIM",
  //     html: " Tidak Boleh Kosong .!!!",
  //     type: "error",
  //     confirmButtonText: "OK"
  //   }).then((value) => {
  //     // $('#modal_form').modal('show');
  //     $('#btnSave').text('save');
  //     $('#btnSave').attr('disabled', false);
  //   });
  //   return;
  // }
  if (jenispasien == '' || jenispasien== null) {
    $('#modal_form').modal('hide');
    swal({
      title: "JENIS PASIEN",
      html: " Tidak Boleh Kosong .!!!",
      type: "error",
      confirmButtonText: "OK"
    }).then((value) => {
      // $('#modal_form').modal('show');      
      $('#btnSave').text('save');
      $('#btnSave').attr('disabled', false);
    });
    return;
  }

  // swal({
  //   title: "DATA PASIEN",
  //   html: "Akan berhasil teregistrasi",
  //   type: "success",
  //   confirmButtonText: "OK"
  // });

  var noregz = $("#noreg").val();
  url = "<?php echo site_url('PendaftaranVRS/tambah_pasien_register_rawat_jalan?noreg=')?>"+noregz;
  $.ajax({
    url: url,
    type: "POST",
    data: ($('#frmpasien').serialize()),
    dataType: "JSON",
    success: function(data) {
      // script originial
      if(data.status == 0){
        if(vpenjamin == "BPJS"){
          //
        } else {
          // swal({
          //   title: "DATA PASIEN",
          //   html: "Data berhasil teregistrasi",
          //   type: "success",
          //   confirmButtonText: "OK" 
          // }).then((value) => {
          //   $('#modal_form').modal('hide');
          //   $("#btnsimpaneditpasien").attr('disabled', true);
          //   $("#noreg").val(data.noreg);
          // });

          swal({
            title: "DATA PASIEN",
            html: "Data berhasil teregistrasi",
            type: "success",
            confirmButtonText: "Bridging PCare",
            cancelButtonText: "Ok",
            confirmButtonColor: "blue",
            cancelButtonColor: "green",
            showCancelButton: true,
            allowOutsideClick: false
          }).then(function() {
              url="<?php echo base_url()?>PendaftaranVRS/pcare_rj/?noreg="+data.noreg+"&rekmed="+norm
              
              window.open(url,'_blank');
              window.focus();
          }, function(dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
              
              $('#modal_form').modal('hide');
              $("#btnsimpaneditpasien").attr('disabled', true);
              $("#noreg").val(data.noreg);
            }
          });
          
          $('#modal_form').modal('hide');
          $("#btnsimpaneditpasien").attr('disabled', true);
          $("#noreg").val(data.noreg);

        }
      } else if(data.status == 2){
        swal({
            title: "PASIEN",
            html: "Pasien atas nama : <b>"+data.nm+"</b><br>"+
            "SUDAH TERDAFTAR, SILAHKAN CEK DI LIST",
            type: "error",
            confirmButtonText: "OK"
          }).then((value) => {
            // window.location.reload();
            return;
          });
      }else{
        swal({
          title: "DATA PASIEN",
          html: "Data gagal teregistrasi",
          type: "error",
          confirmButtonText: "OK" 
        }).then((value) => {
          $('#modal_form').modal('hide');
        });
      }
      // husain change
      // if (data.status == 0) {
      //   swal({
      //     title: "DATA PASIEN",
      //     html: "Data berhasil teregistrasi",
      //     type: "success",
      //     confirmButtonText: "OK"
      //   }).then((value) => {
      //     $('#modal_form').modal('hide');
      //   });
      //   $("#noreg").val(data.noreg);
      //   $("#btnsimpaneditpasien").attr('disabled', true);
      // } else {
      //   swal({
      //     title: "DATA PASIEN",
      //     html: "Data gagal teregistrasi",
      //     type: "error",
      //     confirmButtonText: "OK"
      //   }).then((value) => {
      //     $('#modal_form').modal('hide');
      //   });
      // }
    }
  });

}

function Batalkan(id) {
  swal({
    //title: 'PENDAFTARAN',
    text: "Alasan Dibatalkan : ",
    type: 'info',
    input: 'text',
    showCancelButton: true,
    confirmButtonClass: 'btn btn-success',
    cancelButtonClass: 'btn btn-danger m-l-10',
    confirmButtonText: 'Ya, Batalkan',
    cancelButtonText: 'Tidak',
  }).then(function(alasan) {
    $.ajax({
      type: 'POST',
      dataType: "json",
      data: {
        alasan: alasan
      },
      url: '<?php echo base_url()?>PendaftaranVRS/pembatalan/' + id,
      success: function(response) {
        if (response.status == 1) {
          swal(
            'Updated!',
            'Pembatalan berhasil.',
            'success'
          )
        } else {
          swal(
            'Failed!',
            'Pembatalan gagal',
            'failed'
          )
        }
        reload_table();
      }
    });
  });
}

/**
 * Script untuk memilih pasien lama
 * 
 */
function pasienLama(el){
  var pasienLama = document.querySelector('#pasien_lama')

  el.classList.add('hidden')
  pasienLama.classList.remove('hidden')
}
</script>