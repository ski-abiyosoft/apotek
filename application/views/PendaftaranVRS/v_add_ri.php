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
      <span class="title-web">RS <small>Pendaftaran Pasien Rawat Inap</small>
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
                <select class="form-control select2_el_pasien input-medium" onChange="getinfopasien()" id="pasien"
                  name="pasien">
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
            <label class="col-md-3 control-label">Dokter DPJP <font color="red">*</font></label>
            <div class="col-md-9">
              <select name="dokter" class="form-control select2_dokter_ri" id="dokter">
                <option value="">-- Cari --</option>
                <?php foreach($dokter as $d) : ?>
                <option value="<?= $d->kodokter; ?>"><?= $d->kodokter; ?> | <?= $d->nadokter; ?></option>
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
            <label class="col-md-3 control-label">Pengirim <font color="red">*</font></label>
            <div class="col-md-9">
              <select class="form-control select2_el_dokter" style="width:100%;" id="pengirim" name="pengirim"></select>
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
            <label class="col-md-3 control-label">Jenis Pasien <font color="red">*</font></label>
            <div class="col-md-9">
              <select class="form-control select2_el_jenispasien" style="width:100%;" id="jenispasien"
                name="jenispasien" onChange="getRuang()">
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
        <div class="col-md-6">
          <!-- id="penjamin" -->
          <div class="form-group">
            <label class="col-md-3 control-label">Penjamin <font color="red"></font></label>
            <div class="col-md-9">
              <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin" name="vpenjamin">
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
            <label class="col-md-3 control-label">No. RM <font color="red"></font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="norm" name="norm" value="" placeholder="otomatis" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <!-- id="card" -->
          <div class="form-group">
            <label class="col-md-3 control-label">No. Kartu <font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="nocard" name="nocard" value="">
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
        <div class="col-md-6">
          <!-- id="rujukan" -->
          <div class="form-group">
            <label class="col-md-3 control-label">No. Rujukan <font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="norujukan" name="norujukan" value="">
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
        <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">No. BPJS <font color="red">*</font></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nocard" name="nocard" value="">
                        </div>
                    </div>
                </div> -->
        <div class="col-md-6">
          <!-- id="sep" -->
          <div class="form-group">
            <label class="control-label col-md-3">No. Sep<font color="red">*</font></label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="nosep" name="nosep" value="">
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
        <div class="col-md-6">
          <button class="btn green" style="float: right"><i class="fa fa-check-square"></i> Briging
            Vclaim</button>
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
        <div class="col-md-6">
          <div class="form-group">
            <label for="lokasi"><b>LOKASI TUJUAN RUANG KAMAR</b></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-md-4 control-label">Ruang <font color="red">*</font></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="ruang" name="ruang" onchange="gantiruang()"
                        onclick="gantiruang()">
                        <option value="">--- Pilih ---</option>
                        <?php foreach($daftar_ruang_inap as $row){ ?>
                        <option value="<?= $row->koderuang;?>"><?= $row->nama_bangsal;?> | <?= $row->namaruang;?>
                        </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-md-4 control-label">No Kamar/Bed <font color="red">*</font></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-6">
                          <input type="hidden" class="form-control" id="bed" name="bed">
                          <input type="text" class="form-control" id="bed1" name="bed1" readonly>
                        </div>
                        <div class="col-md-3">
                          <label class="control-label">Kelas <font color="red">*</font></label>
                        </div>
                        <div class="col-md-3">
                          <input type="text" class="form-control" id="kelas" name="kelas" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-secondary p-1" type="button" onclick="lihatruangkamar()"
                    style="width:100%; height:75px;">Lihat<br>Ruang</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
          <div class="form-group">
            <table id="susah">
              <tr>
                <td align="center" width="10%">
                  <label for="label">
                    <input type="checkbox" name="titip" id="titip">
                  </label>
                </td>
                <td style="margin:0 10px;">
                  <label for="label" style="margin-left:10px;"><b>DITITIPKAN</b></label>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="row" id="kamartitipan">
        <div class="col-md-6"></div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-md-4 control-label">Ruang <font color="red">*</font></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="rtitip" name="rtitip" onclick="gantiruang2()"
                        style="width:100%">
                        <option value="">--- Pilih ---</option>
                        <?php foreach($daftar_ruang_inap as $row){ ?>
                        <option value="<?= $row->koderuang;?>"><?= $row->nama_bangsal;?> | <?= $row->namaruang;?>
                        </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-md-4 control-label">No Kamar/Bed <font color="red">*</font></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-12">
                          <input type="text" class="form-control" id="ruangtitip" name="ruangtitip">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-secondary p-1" type="button" onclick="lihatruangtitip()"
                    style="width:100%; height:75px;">Lihat<br>Ruang</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-actions">
            <button id="btnsimpaneditpasien" type="button" onclick="register()" class="btn blue"><i
                class="fa fa-save"></i> <b>Simpan Data Pasien</b></button>
            <div class="btn-group">
              <button type="button" id="btncetak" class="btn btn-warning"> <i class="fa fa-print"></i>
                <b>Cetak</b></button>
              <button type="button" id="btncetak1" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i
                  class="fa fa-angle-down"></i></button>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="#" onclick="javascript:window.open(_urlcetak1(),'_blank');">
                    Kartu Pasien
                  </a>
                </li>
                <li>
                  <a href="#" onclick="javascript:window.open(_urlcetak2(),'_blank');">
                    Tracer Pasien
                  </a>
                </li>

              </ul>
            </div>
            <button class="btn green" type="button" onClick="window.location.reload();"><i class="fa fa-refresh"></i>
              Data Baru</button>
            <a class="btn red" href="<?php echo base_url('pendaftaranVRS')?>"><i class="fa fa-undo"></i><b>
                KEMBALI </b></a>
          </div>
        </div>
      </div>
    </form>
    <br>
  </div>
</div>

<div class="modal fade" id="modalruangkamar" role="dialog">
  <div class="modal-dialog modal-full modal-dialog-scrollable">
    <div class="modal-content" style="height:600px; overflow:auto;">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">INFORMASI DAN PILIH RUANG</h3>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="table_id">
            <thead>
              <tr>
                <th>No</th>
                <th>Bangsal</th>
                <th>Ruang</th>
                <th>Kamar</th>
                <th>Kelas</th>
                <th>Tarif</th>
                <th>Status</th>
                <th>Pasien</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach($daftar_ruang_inap as $dri) : ?>
              <?php if($dri->penuh == 1) { $kondisi_kamar = 'bg-danger'; } else { $kondisi_kamar = 'bg-success'; } ?>
              <tr class="<?= $kondisi_kamar; ?>">
                <td><?= $no++; ?></td>
                <td><?= $dri->nama_bangsal; ?></td>
                <td><?= $dri->namaruang; ?></td>
                <td><?= $dri->namakamar; ?></td>
                <td><?= $dri->namakelas; ?></td>
                <td>Rp. <span style="margin-right:0px;"><?= number_format($dri->tarif, 2); ?></span></td>
                <td><?php if($dri->penuh == 1) { echo 'terisi'; } else { echo 'kosong'; } ?></td>
                <td>
                  <?php if($dri->namapas != null | $dri->namapas != '') : ?>
                  <?= $dri->namapas; ?>
                  <?php else : ?>
                  -
                  <?php endif; ?>
                </td>
                <td>
                  <button type="button" class="btn btn-success btn-sm" id="select_abc"
                    data-kodekamar="<?= $dri->kodekamar; ?>" data-namakamar="<?= $dri->namakamar; ?>"
                    data-kelas="<?= $dri->kelas; ?>" data-ruang="<?= $dri->koderuang; ?>"
                    data-kondisi="<?= $dri->penuh; ?>">
                    <i class="fa fa-check"></i> pilih
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalruangtitip" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">INFORMASI DAN PILIH RUANG</h3>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="table_id">
            <thead>
              <tr>
                <th>No</th>
                <th>Bangsal</th>
                <th>Ruang</th>
                <th>Kamar</th>
                <th>Kelas</th>
                <th>Tarif</th>
                <th>Status</th>
                <th>Pasien</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach($daftar_ruang_inap as $dri) : ?>
              <?php if($dri->penuh == 1) { $kondisi_kamar = 'bg-danger'; } else { $kondisi_kamar = 'bg-success'; } ?>
              <tr class="<?= $kondisi_kamar; ?>">
                <td><?= $no++; ?></td>
                <td><?= $dri->nama_bangsal; ?></td>
                <td><?= $dri->namaruang; ?></td>
                <td><?= $dri->namakamar; ?></td>
                <td><?= $dri->namakelas; ?></td>
                <td>Rp. <span style="margin-right:0px;"><?= number_format($dri->tarif, 2); ?></span></td>
                <td><?php if($dri->penuh == 1) { echo 'terisi'; } else { echo 'kosong'; } ?></td>
                <td>
                  <?php if($dri->namapas != null | $dri->namapas != '') : ?>
                  <?= $dri->namapas; ?>
                  <?php else : ?>
                  -
                  <?php endif; ?>
                </td>
                <td>
                  <button type="button" class="btn btn-success btn-sm" id="select_abc2"
                    data-kodekamar="<?= $dri->kodekamar; ?>" data-namakamar="<?= $dri->namakamar; ?>"
                    data-kelas="<?= $dri->kelas; ?>" data-ruang="<?= $dri->koderuang; ?>"
                    data-kondisi="<?= $dri->penuh; ?>">
                    <i class="fa fa-check"></i> pilih
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
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
                <label class="col-md-3 control-label">Cabang  <font color="red">*</font></label>
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
                  <input type="date" class="form-control input-medium" onChange="tgllahir()" id="luptgllahir"
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
                <label class="col-md-3 control-label">Status <font color="red"></font></label>
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
                  <input type="text" class="form-control" id="luphp" name="luphp" value="">
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
                  <!-- <select class="form-control select2_el_provinsi" id="lupprovinsi" name="lupprovinsi"></select>	 -->
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
                  <!-- <select class="form-control" name="kabkota" id="kabkota"></select>	 -->
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
                  <!-- <select class="form-control" name="lupkecamatan" id="lupkecamatan"> -->
                  <!-- </select> -->
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
                  <!-- <select class="form-control" name="lupkelurahan" id="lupkelurahan" onClick="getKP()">
										<option value="">-- Pilih --</option>
									</select> -->
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

<script>
function hapussemua() {
  $('#pasien').empty();
  $('#poliklinik1').empty();
  $('#booking').val('');
  $('#dokter').empty();
  $('#noreg').val('');
  $('#ruang').empty();
  $('#pengirim').empty();
  $('#norm').val('');
  $('#jenispasien').empty();
  $('#noidentitas').val('');
  $('#vpenjamin').empty();
  $('#namapasien').val('');
  $('#nocard').val('');
  $('#umur123').val('0 tahun 0 bulan 0 hari');
  $('#jeniskelamin').val('');
  $('#sep').val('');
  $('#hp').val('');
  $('#lupnorm').val('');
  $('#luppreposition').val('').change();
  $('#lupnamapasien').val('');
  $('#lupidentitas').val('').change();
  $('#lupnoidentitas').val('');
  $('#lupnamapanggilan').val('');
  $('#lupnamakeluarga').val('');
  $('#luptempatlahir').val('');
  $('#luptgllahir').val('').change();
  $('#lupjeniskelamin').val('').change();
  $('#lupumur').val('0 tahun 0 bulan 0 hari');
  $('#lupstatus').val('').change();
  $('#lupwarganegara').val('').change();
  $('#lupagama').val('').change();
  $('#luppendidikan').val('').change();
  $('#lupgoldarah').val('').change();
  $('#luphobby').val('');
  $('#luppekerjaan').val('').change();
  $('#lupalamat1').val('');
  $('#luprt').val('');
  $('#luprw').val('');
  $('#lupalamat2').val('');
  $('#luphp').val('');
  $('#lupprovinsi').val('').change();
  $('#lupphone').val('');
  $('#kabkota').empty();
  $('#lupemail').val('');
  $('#lupkecamatan').empty();
  $('#lupfb').val('');
  $('#lupkelurahan').val('');
  $('#luptwitter').val('');
  $('#lupkodepos1').val('');
  $('#lupig').val('');
}

$('#kamartitipan').hide();
var checkbox = document.querySelector("input[name=titip]");
checkbox.addEventListener('change', function() {
  if (this.checked) {
    $('#kamartitipan').show(200);
  } else {
    $('#kamartitipan').hide(200);
    $('#rtitip').val('').change();
  }
});

function gantiruang() {
  var ruang = document.getElementById('ruang').value;
  $.ajax({
    url: "<?php echo base_url();?>PendaftaranVRS/gantiruang/?ruang=" + ruang,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      console.log(data);
      if (data.penuh == 1) {
        swal({
          title: "RUANGAN",
          html: "Sudah Penuh",
          type: "info",
          confirmButtonText: "OK"
        }).then((value) => {
          $('#ruang').val('').change();
        });
      } else {
        // console.log(data.namakamar);
        $('#bed').val(data.kodekamar);
        $('#bed1').val(data.namakamar);
        $('#kelas').val(data.kelas);
      }

    }
  });
}

function gantiruang2() {
  var ruang = document.getElementById('rtitip').value;
  $.ajax({
    url: "<?php echo base_url();?>PendaftaranVRS/gantiruang/?ruang=" + ruang,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      if (data.penuh == 1) {
        swal({
          title: "RUANGAN",
          html: "Sudah Penuh",
          type: "info",
          confirmButtonText: "OK"
        }).then((value) => {
          $('#ruang').val('').change();
        });
      } else {
        console.log(data.kodekamar);
        $('#rtitip').val(data.kodekamar).change();
        $('#ruangtitip').val(data.namakamar);
      }

    }
  });
}

$(document).ready(function() {
  $(".select2_dokter_ri").select2();
});

$('#table_id').DataTable({
  "lengthMenu": [
    [50, 100, 200, -1],
    [50, 100, 200, 'semua']
  ],
  "oLanguage": {
    "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
    "sInfoEmpty": "",
    "sInfoFiltered": " - Dipilih dari _MAX_ data",
    "sSearch": "Pencarian Data : ",
    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
    "sLengthMenu": "_MENU_ Baris",
    "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
    "oPaginate": {
      "sPrevious": "Sebelumnya",
      "sNext": "Berikutnya"
    }
  },
});

function lihatruangkamar() {
  $('#modalruangkamar').modal('show');
}

function lihatruangtitip() {
  $('#modalruangtitip').modal('show');
}

$(document).ready(function() {
  $(document).on('click', '#select_abc', function() {
    var $kodekamar = $(this).data('kodekamar');
    var $kondisi = $(this).data('kondisi');
    var $kelas = $(this).data('kelas');
    var $ruang = $(this).data('ruang');
    var $namakamar = $(this).data('namakamar');
    if ($kondisi == 1) {
      swal({
        title: "KAMAR/BED",
        html: "Kamar sudah ada penghuninya",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $('#modalruangkamar').modal('show');
      });
      $('#bed').val('');
      $('#kelas').val('');
      $('#ruang').val('');
    } else {
      $('#bed').val($namakamar);
      $('#kelas').val($kelas);
      $('#ruang').val($ruang).change();
    }
    $('#modalruangkamar').modal('hide');
  });
});

$(document).ready(function() {
  $(document).on('click', '#select_abc2', function() {
    var $rtitip = $(this).data('ruang');
    var $kondisi = $(this).data('kondisi');
    var $ruangtitip = $(this).data('namakamar');
    if ($kondisi == 1) {
      swal({
        title: "KAMAR/BED",
        html: "Kamar sudah ada penghuninya",
        type: "error",
        confirmButtonText: "OK"
      }).then((value) => {
        $('#modalruangtitip').modal('show');
      });
      $('#rtitip').val('').change();
      $('#ruangtitip').val('');
    } else {
      console.log($rtitip);
      $('#ruangtitip').val($ruangtitip);
      $('#rtitip').val($rtitip).change();
    }
    $('#modalruangtitip').modal('hide');
  });
});

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
      var noktp = this.value;
      var prov = noktp.substring(0, 2);
      var kotakab = noktp.substring(0, 4);
      var kec = noktp.substring(0, 6);
      getprov(prov);
      getkot(kotakab);
      getkec(kec);
      $('#lupkecamatan').click();
    });
  }
}

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

function update() {
  var select = document.getElementById('poliklinik1').value;
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
  // console.log(select);
  $.ajax({
    url: "<?= site_url('PendaftaranVRS/get_kecamatan');?>",
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
      // console.log(data);
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
      if (data.keluar == 0) {
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
        $('#ruang').empty();
        $('#vpenjamin').empty();
        $('#jenispasien').empty();
        $('#pengirim').empty();
        if (data.jenispas != 'PAS1') {
          $('#penjamin').show(200);
          $('#card').show(200);
          $('#sep').show(200);
          $('#rujukan').show(200);
        } else {
          $('#penjamin').hide(200);
          $('#card').hide(200);
          $('#sep').hide(200);
          $('#rujukan').hide(200);
        }
      }
      if (data.titip == 1) {
        $('#titip').prop('checked', true);
        $('#kamartitipan').show(200);
        $('#rtitip').val(data.ruangtitip).change();
        $('#ruangtitip').val(data.kamartitip);
      } else {
        $('#titip').attr('checked', false);
        $('#kamartitipan').hide(200);
      }
      $('#idpasien').val(data.idtr);
      $('#idpasien2').val(data.idtr);
      $('#booking').val(data.mjkn_token);
      $('#namapasien').val(data.namapas);
      $('#ruang').val(data.koderuang).change();
      $('#noreg').val(data.noreg);
      $('#norm').val(data.rekmed);
      $('#poliklinik1').val(data.kodepos).change();
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
      if (data.jenispas == '' || data.jenispas == null) {
        $('#penjamin').hide();
        $('#card').hide();
        $('#sep').hide();
        $('#rujukan').hide();
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
        opt.innerHTML = data.kodokter + ' | ' + data.nadokter;
        selectElement.appendChild(opt);
        $('#dokter').val(data.kodokter);
      }

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
  });
}

function tgllahir() {
  // $('#luptgllahir').on('change', function() {		
  var birthDate = new Date($('#luptgllahir').val());
  var usia = hitung_usia(birthDate);
  $('#lupumur').val(usia);
  // });
}

function add_pasien() {
  save_method = 'add';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $('#modal_form').modal('show'); // show bootstrap modal
  $('.modal-title').text('Pasien Baru'); // Set Title to Bootstrap modal title
}

function save_pasien() {
  // modal
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
  // console.log(tgl);

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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
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
    $('#btnSave').text('save'); //change button text
    $('#btnSave').attr('disabled', false); //set button enable 
    return;
  }

  url = "<?php echo site_url('PendaftaranVRS/tambah_pasien_rawat_inap')?>";
  $.ajax({
    url: url,
    type: "POST",
    data: ($('#frmpasien2').serialize()),
    dataType: "JSON",
    success: function(data) {
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
      } else if (data.status == 1) {
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
      } else if (data.status == 2) {
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


$('#luppreposition').on('change', function() {

  //var prep = document.getElementById("preposition").options[preposition.selectedIndex].innerHTML;	
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

$('#luppreposition').on('change', function() {
  var prep = this.value;
  $.ajax({
    url: "<?php echo base_url();?>app/getvaluesetinghms/?kode=" + prep,
    type: "GET",
    dataType: 'json',
    success: function(data) {
      var hasil = eval(data.data.valuerp);
      if (hasil == 2) {
        $('#lupkelamin').val('W');
      } else {
        $('#lupkelamin').val('P');
      }

    }
  });

});

function register() {
  var norm = document.getElementById('norm').value;
  var tanggal = document.getElementById('tanggal').value;
  var jam = document.getElementById('jam').value;
  var jenispasien = document.getElementById('jenispasien').value;
  // var poliklinik = document.getElementById('poliklinik1').value;
  var penjamin = document.getElementById('vpenjamin').value;
  var dokter = document.getElementById('dokter').value;
  // var antrino = document.getElementById('antrino').value;
  var pengirim = document.getElementById('pengirim').value;
  var ruang = document.getElementById('ruang').value;
  var booking = document.getElementById('booking').value;
  var nocard = document.getElementById('nocard').value;
  var norujukan = document.getElementById('norujukan').value;
  var nosep = document.getElementById('nosep').value;
  var ruangtitip = document.getElementById('rtitip').value;
  var kamartitip = document.getElementById('ruangtitip').value;

  // alert
  // if (poliklinik == '') {
  //     $('#modal_form').modal('hide');
  //     swal({
  //         title: "POLIKLINIK",
  //         html: " Tidak Boleh Kosong .!!!",
  //         type: "error",
  //         confirmButtonText: "OK"
  //     }).then((value) => {
  //         $('#modal_form').modal('show');
  //     });
  //     $('#btnSave').text('save');
  //     $('#btnSave').attr('disabled', false);
  //     return;
  // }
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
    $('#btnSave').text('simpan');
    $('#btnSave').attr('disabled', false);
    return;
  }
  if (ruang == '') {
    $('#modal_form').modal('hide');
    swal({
      title: "RUANG",
      html: " Tidak Boleh Kosong .!!!",
      type: "error",
      confirmButtonText: "OK"
    }).then((value) => {
      $('#modal_form').modal('show');
    });
    $('#btnSave').text('simpan');
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
    $('#btnSave').text('simpan');
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
    $('#btnSave').text('simpan');
    $('#btnSave').attr('disabled', false);
    return;
  }
  if (pengirim == '') {
    $('#modal_form').modal('hide');
    swal({
      title: "PENGIRIM",
      html: " Tidak Boleh Kosong .!!!",
      type: "error",
      confirmButtonText: "OK"
    }).then((value) => {
      $('#modal_form').modal('show');
    });
    $('#btnSave').text('simpan');
    $('#btnSave').attr('disabled', false);
    return;
  }
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
    $('#btnSave').text('simpan');
    $('#btnSave').attr('disabled', false);
    return;
  }

  url = "<?php echo site_url('PendaftaranVRS/tambah_pasien_register_rawat_inap')?>";
  $.ajax({
    url: url,
    type: "POST",
    data: ($('#frmpasien').serialize()),
    dataType: "JSON",
    success: function(data) {
      // console.log(data);
      if (data.status == 0) {
        swal({
          title: "DATA PASIEN",
          html: "Data berhasil teregistrasi",
          type: "success",
          confirmButtonText: "OK"
        }).then((value) => {
          $('#modal_form').modal('hide');
        });
      } else if (data.status == 1) {
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
            html: "Data terdaftar berhasil diubah",
            type: "success",
            confirmButtonText: "OK"
          }).then((value) => {
            $('#modal_form').modal('hide');
          });
        });
      }
    }
  });
}
</script>