<?php
  $this->load->view('template/header');
  $this->load->view('template/body');
?>

<!-- header -->
<div class="row">
  <div class="col-md-12">
    <h3 class="page-title">
      <span class="title-unit">
        &nbsp;<?php echo $this->session->userdata('unit'); ?>
      </span> 
      &nbsp;-
      <span class="title-web">e-HMS <small>IGD</small>
    </h3>
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <i style="color:white;" class="fa fa-home"></i>
        <a class="title-white" href="<?= site_url('home'); ?>">
          Awal
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= site_url('Igd'); ?>">
          Instalasi Gawat Darurat
        </a>
        <i style="color:white;" class="fa fa-angle-right"></i>
      </li>
      <li>
        <a class="title-white" href="<?= site_url('Igd/buat_triase'); ?>">
          Triage
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- body -->
<form id="form_triase" class="form-horizontal" method="post">
  <div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
      <div class="portlet box blue">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-reorder"></i><b>Pemeriksaan Triage</b>
          </div>
        </div>
        <div class="portlet-body">
          <div class="row" style="margin-bottom: -20px;">
            <div class="col-md-6"></div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green"><b>Tanggal</b></label>
                <div class="col-md-8">
                  <input value="<?= date("Y-m-d"); ?>" id="tglperiksa" name="tglperiksa" class="form-control" type="date" readonly>
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label col-md-4" style="color:green"><b>Jam</b></label>
                <div class="col-md-8">
                  <input value="<?= date("H:i:s"); ?>" id="jamperiksa" name="jamperiksa" class="form-control" type="text" readonly style="text-align: right;">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <label for="" class="h4 text-primary" style="font-weight: bold; margin-bottom: 30px;">DATA PASIEN</label>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="control-label col-md-4" style="color: green;"><b>Bed</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <select name="bed" id="bed" class="form-control select2_pilih" data-placeholder="Pilih Bed...">
                        <option value="">Pilih Bed...</option>
                        <?php foreach($bed as $b) : ?>
                          <option value="<?= $b->kodeset; ?>"><?= $b->keterangan; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="control-label col-md-4" style="color: green;"><b>Kode Triage</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <input type="text" id="kode_triase" name="kode_triase" class="form-control" placeholder="OTOMATIS" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Cari Pasien</b></label>
                    <div class="col-md-8">
                      <select name="pasien" id="pasien" class="select2_el_pasien form-control" style="width: 100%;" onchange="getinfopasien(this.value)"></select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>No Registrasi</b></label>
                    <div class="col-md-8">
                      <input type="text" name="noreg" id="noreg" class="form-control" readonly placeholder="OTOMATIS">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Nama Pasien</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <input type="text" id="namapas" name="namapas" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>No RM</b></label>
                    <div class="col-md-8">
                      <input type="text" name="rekmed" id="rekmed" class="form-control" readonly placeholder="OTOMATIS">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Dokter</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <!-- <select name="kodokter" id="kodokter" class="form-control select2_el_dokter"></select> -->
                      <select name="kodokter" id="kodokter" class="form-control select2_pilih" data-placeholder="Pilih Dokter...">
                        <option value="">Pilih Dokter...</option>
                        <?php foreach($dokter as $d) : ?>
                          <option value="<?= $d->kodokter; ?>"><?= $d->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Perawat</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <!-- <select name="koperawat" id="koperawat" class="form-control select2_el_perawat"></select> -->
                      <select name="koperawat" id="koperawat" class="form-control select2_pilih" data-placeholder="Pilih Perawat...">
                        <option value="">Pilih Perawat...</option>
                        <?php foreach($perawat as $p) : ?>
                          <option value="<?= $p->kodokter; ?>"><?= $p->nadokter; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <label for="" class="h4 text-primary" style="font-weight: bold; margin-bottom: 30px;">KELUHAN</label>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Alergi</b></label>
                    <div class="col-md-8">
                      <input type="text" name="alergi" id="alergi" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Keluhan Awal</b></label>
                    <div class="col-md-8">
                      <textarea name="keluhan_awal" id="keluhan_awal" class="form-control" rows="5" style="resize: none;"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table  border="0" style="width:100%;margin:auto">
                    <tr>
                      <td align="center" width="5%">&nbsp;</td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>0</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>1</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>2</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>3</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>4</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>5</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>6</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>7</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>8</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>9</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="2%" style="font-size:3px black;"><b>10</b></td>
                      <td align="center" width="2%">&nbsp;</td>
                      <td align="center" width="5%">&nbsp;</td>
                      <td width="40%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center" >&nbsp;</td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" style="border-top:3px solid #23c8c0; border-right:3px solid #23c8c0 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #23c8c0; border-right:3px solid #23c8c0 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #13ef9a; border-right:3px solid #13ef9a ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #0f67df; border-right:3px solid #0f67df ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #e45c10; border-right:3px solid #e45c10 ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #aa3d0c; border-right:3px solid #aa3d0c ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #fe060e; border-right:3px solid #fe060e ">&nbsp;</td>
                      <td align="center" style="border-top:3px solid #fe060e; border-right:3px solid #fe060e ">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td rowspan="3" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Tidak Nyeri </b></td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Nyeri Ringan </b></td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Nyeri Sedang </b></td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Nyeri Sedang </b></td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Nyeri Berat</b></td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" ><b>Nyeri Sangat Berat </b></td>
                      <td align="center" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center">&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #23c8c0;" >
                        <a>&#128515;</a>
                      </td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #13ef9a;">
                        <a>&#128578;</a>
                      </td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #0f67df;">
                        <a>&#128577;</a>
                      </td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #e45c10;">
                        <a>&#128543;</a>
                      </td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #aa3d0c;">
                        <a>&#128546;</a>
                      </td>
                      <td align="center" >&nbsp;</td>
                      <td align="center" colspan="3" style="font-size: 50px; background-color: #fe060e;">
                        <a>&#128534;</a> 
                      </td>
                      <td align="center" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox"  id="ceknyeri1" name="ceknyeri" onclick="c_ceknyeri(1)" value="1">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox"  id="ceknyeri2" name="ceknyeri" onclick="c_ceknyeri(2)" value="2">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox"  id="ceknyeri3" name="ceknyeri" onclick="c_ceknyeri(3)" value="3">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox"  id="ceknyeri4" name="ceknyeri" onclick="c_ceknyeri(4)" value="4">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox"  id="ceknyeri5" name="ceknyeri" onclick="c_ceknyeri(5)" value="5">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td class="rightJustified" align="center"> 
                        <input class="form-control" type="checkbox" id="ceknyeri6" name="ceknyeri" onclick="c_ceknyeri(6)" value="6">
                      </td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <label for="" class="h4 text-primary" style="font-weight: bold; margin-bottom: 30px;">TANDA VITAL</label>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-md-2">
                      <button class="btn btn-info" title="INFORMASI GCS" type="button" onclick="modal_gcs()"><i class="fa fa-info"></i></button>
                    </div>
                    <label class="control-label col-md-2" style="color:green"><b>GCS</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="row">
                            <label class="control-label col-md-5" style="color:green"><b>E</b> :</label>
                            <div class="col-md-7">
                              <input type="text" name="gcs_e" id="gcs_e" class="form-control" onchange="cek_gcs(1)">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="row">
                            <label class="control-label col-md-5" style="color:green"><b>V</b> :</label>
                            <div class="col-md-7">
                              <input type="tex" name="gcs_v" id="gcs_v" class="form-control" onchange="cek_gcs(2)">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="row">
                            <label class="control-label col-md-5" style="color:green"><b>M</b> :</label>
                            <div class="col-md-7">
                              <input type="text" name="gcs_m" id="gcs_m" class="form-control" onchange="cek_gcs(3)">
                            </div>
                          </div>
                        </div>
                      </div>
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Pupil</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5">
                          <input id="pupil1" name="pupil1" class="form-control" type="text" placeholder="mm">
                        </div>
                        <label class="control-label col-md-1"> / </label>
                        <div class="col-md-6">
                          <input id="pupil2" name="pupil2" class="form-control" type="text" placeholder="mm">
                        </div>
                        <span class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Reflex Cahaya</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5">
                          <input id="r_cahaya1" name="r_cahaya1" class="form-control" type="text">
                        </div>
                        <label class="control-label col-md-1"> / </label>
                        <div class="col-md-6">
                          <input id="r_cahaya2" name="r_cahaya2" class="form-control" type="text">
                        </div>
                        <span class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Tekanan Darah</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5">
                          <input id="t_darah1" name="t_darah1" class="form-control" type="text">
                        </div>
                        <label class="control-label col-md-1"> / </label>
                        <div class="col-md-6">
                          <input id="t_darah2" name="t_darah2" class="form-control" type="text">
                        </div>
                        <span class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Nadi</b> <span style="color: red;">*</span></label>
                    <div class="col-md-8">
                      <input id="nadi" name="nadi" class="form-control" type="text" placeholder="/menit">
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="col-md-3 control-label" style="color:green"><b>Reguler</b></label>
                    <div class="col-md-2">
                      <input type="checkbox" class="form-control" name="reg" id="reg" style="margin-left: 32px;">
                    </div>
                    <label for="" class="col-md-3 control-label" style="color:green"><b>Ireguler</b></label>
                    <div class="col-md-2">
                      <input type="checkbox" class="form-control" name="ireg" id="ireg" style="margin-left: -10px;">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Suhu</b></label>
                    <div class="col-md-8">
                      <input id="suhu" name="suhu" class="form-control" type="text" placeholder="Celcius">
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Pernapasan</b></label>
                    <div class="col-md-8">
                      <input id="pernapasan" name="pernapasan" class="form-control" type="text" placeholder="/menit">
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>SpO2</b></label>
                    <div class="col-md-8">
                      <input id="sp" name="sp" class="form-control" type="text" placeholder="%">
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-4" style="color:green"><b>Akral</b></label>
                    <div class="col-md-8">
                      <input id="akral" name="akral" class="form-control" type="text">
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <label for="" class="h4 text-primary" style="font-weight: bold; margin-bottom: 30px;">GANGGUANG PRILAKU</label>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table width="100%">
                      <tr>
                        <td width="10%">
                          <input type="checkbox" class="form-control" name="ganggu" id="ganggu">
                        </td>
                        <td width="20%">
                          <b style="color: green; font-weight: bold;">Tidak Terganggu</b>
                        </td>
                        <td width="10%">
                          <input type="checkbox" class="form-control" name="ganggu" id="ganggu">
                        </td>
                        <td width="20%">
                          <b style="color: green; font-weight: bold;">Ada Terganggu</b>
                        </td>
                        <td width="10%">
                          <input type="checkbox" class="form-control" name="ganggu" id="ganggu">
                        </td>
                        <td width="20%">
                          <b style="color: green; font-weight: bold;">Tidak Membahayakan</b>
                        </td>
                      </tr>
                      <tr>
                        <td width="10%">
                          <input type="checkbox" class="form-control" name="ganggu" id="ganggu">
                        </td>
                        <td width="20%" colspan="2">
                          <b style="color: green; font-weight: bold;">Membahayakan Diri sendiri/Orang lain</b>
                        </td>
                        <td width="10%">
                        </td>
                        <td colspan="2" width="20%">
                          <u style="margin-left: 40px; color: red; font-size: 16px">BILA ADA, LAKUKAN KAJIAN <b>RESTRAIN</b></u>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <label for="" class="h4 text-primary" style="font-weight: bold; margin-bottom: 30px;">SKALA FLACC (< 6 Tahun)</label>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                      <thead>
                        <tr>
                          <th style="text-align: center" width="13%">Pengkajian</th>
                          <th style="text-align: center" width="25%">0</th>
                          <th style="text-align: center" width="25%">1</th>
                          <th style="text-align: center" width="25%">2</th>
                          <th style="text-align: center" width="12%">Nilai</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Wajah</td>
                          <td>Tersenyum / tidak ada ekspresi khusus</td>
                          <td>Terkadang meringis / menarik diri</td>
                          <td>Sering menggetarkan dagu dan mengatupkan rahang</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala1" id="skala1" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>Kaki</td>
                          <td>Gerakan normal / relaksasi</td>
                          <td>Tidak tenang / tegang</td>
                          <td>Kaki untuk menendang / menarik diri</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala2" id="skala2" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>Aktifitas</td>
                          <td>Tidur, posisi normal, mudah bergerak</td>
                          <td>Gerakan menggeliat, berguling, kaku</td>
                          <td>Melengkungkan punggung / kaku / menghentak</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala3" id="skala3" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>Menangis</td>
                          <td>Tidak menangis (bangun tidur)</td>
                          <td>Mengerang, merengek-rengek</td>
                          <td>Menangis terus-menerus, terisak, menjerit</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala4" id="skala4" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>Bersuara</td>
                          <td>Bersuara normal, tenang</td>
                          <td>Tenang bila dipeluk, digendong atau diajak bicara</td>
                          <td>Sulit untuk menenangkan</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala5" id="skala5" class="form-control">
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4" style="text-align: right; font-weight: bold;">TOTAL SCORE</td>
                          <td>
                            <input style="text-align: right;" type="text" name="skala_total" id="skala_total" readonly value="0" class="form-control">
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label for="" class="control-label" style="font-weight: bold;">Kategori Triage</label>
                </div>
                <div class="col-md-5" style="margin-top: 5px;">
                  <div class="table-responsive">
                    <table width="100%" border="0">
                      <tr>
                        <td width="5%">
                          <input type="radio" name="triase1" id="triase1" style="margin-top: -8px;">
                        </td>
                        <td width="20%">
                          <div style="width: 50px; height: 20px; background: red; border-radius: 0;"></div>
                        </td>
                        <td width="5%">
                          <input type="radio" name="triase2" id="triase2" style="margin-top: -8px;">
                        </td>
                        <td width="20%">
                          <div style="width: 50px; height: 20px; background: yellow; border-radius: 0;"></div>
                        </td>
                        <td width="5%">
                          <input type="radio" name="triase3" id="triase3" style="margin-top: -8px;">
                        </td>
                        <td width="20%">
                          <div style="width: 50px; height: 20px; background: green; border-radius: 0;"></div>
                        </td>
                        <td width="5%">
                          <input type="radio" name="triase4" id="triase4" style="margin-top: -8px;">
                        </td>
                        <td width="20%">
                          <div style="width: 50px; height: 20px; background: black; border-radius: 0;"></div>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="col-md-5" style="text-align: right;">
                  <!-- <button class="btn btn-primary" type="button"><b>Pemeriksaan Fisik</b></button> -->
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <button class="btn" type="button" id="btnsimpan" style="width: 15%; background-color: green; color: white;" onclick="save()"><i class="fa fa-save"></i> Simpan</button>
              <a class="btn red" type="button" id="btnkembali" style="width: 15%;" href="<?= site_url('Igd'); ?>"><i class="fa fa-undo"></i> Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="modalgcs" data-toggle="modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog vertical-align-center2 modal-center modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-md-6">
            <div class="h4">Informasi Penilaian GCS</div>
          </div>
          <div class="col-md-6" style="text-align: right;">
            <button type="button" class="btn red" onclick="tutup()"><i class="fa fa-times"></i> Tutup</button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-9">
            <div class="h4" style="text-align: center; font-weight: bold;">Penilaian</div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead style="background-color: skyblue;">
                  <tr>
                    <th style="text-align: center;">Feature</th>
                    <th style="text-align: center;">Response</th>
                    <th style="text-align: center;">Score</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td rowspan="5">Best Eye Response</td>
                  </tr>
                  <tr>
                    <td>Open spontaneously</td>
                    <td style="text-align: right;">4</td>
                  </tr>
                  <tr>
                    <td>Open to verbal command</td>
                    <td style="text-align: right;">3</td>
                  </tr>
                  <tr>
                    <td>Open to pain</td>
                    <td style="text-align: right;">2</td>
                  </tr>
                  <tr>
                    <td>No eye opening</td>
                    <td style="text-align: right;">1</td>
                  </tr>
                  <tr>
                    <td rowspan="6">Best Verbal Response</td>
                  </tr>
                  <tr>
                    <td>Orientated</td>
                    <td style="text-align: right;">5</td>
                  </tr>
                  <tr>
                    <td>Confused</td>
                    <td style="text-align: right;">4</td>
                  </tr>
                  <tr>
                    <td>Inappropriate words</td>
                    <td style="text-align: right;">3</td>
                  </tr>
                  <tr>
                    <td>Incomprehensible sounds</td>
                    <td style="text-align: right;">2</td>
                  </tr>
                  <tr>
                    <td>No verbal response</td>
                    <td style="text-align: right;">1</td>
                  </tr>
                  <tr>
                    <td rowspan="7">Best Motor Response</td>
                  </tr>
                  <tr>
                    <td>Obey command</td>
                    <td style="text-align: right;">6</td>
                  </tr>
                  <tr>
                    <td>Localising pain</td>
                    <td style="text-align: right;">5</td>
                  </tr>
                  <tr>
                    <td>Withdrawal from pain</td>
                    <td style="text-align: right;">4</td>
                  </tr>
                  <tr>
                    <td>Flexion to pain</td>
                    <td style="text-align: right;">3</td>
                  </tr>
                  <tr>
                    <td>Extention to pain</td>
                    <td style="text-align: right;">2</td>
                  </tr>
                  <tr>
                    <td>No motor response</td>
                    <td style="text-align: right;">1</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-3">
            <div class="h4" style="text-align: center; font-weight: bold;">Total Score</div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead style="background-color: skyblue;">
                  <tr>
                    <th style="text-align: center">Keterangan</th>
                    <th style="text-align: center">Nilai</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Best score</td>
                    <td style="text-align: right">9 - 15</td>
                  </tr>
                  <tr>
                    <td>Commatosed</td>
                    <td style="text-align: right">4 - 8</td>
                  </tr>
                  <tr>
                    <td>Unresponsive</td>
                    <td style="text-align: right">0 - 3</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_periode');
?>

<!-- master -->
<script>
  $(".select2_pilih").select2();

  function modal_gcs(){
    $("#modalgcs").modal("show");
  }

  function tutup(){
    $("#modalgcs").modal("hide");
  }

  function cek_gcs(param){
    var e = $("#gcs_e").val();
    var v = $("#gcs_v").val();
    var m = $("#gcs_m").val();
    if(param == 1){
      if(e > 4){
        $("#gcs_e").val(4);
      }
    } else if(param == 2){
      if(v > 5){
        $("#gcs_v").val(5);
      }
    } else {
      if(m > 5){
        $("#gcs_m").val(6);
      }
    }
  }

  function getinfopasien(param) {
    $.ajax({
      url: "<?= site_url('Igd/datapasien/'); ?>"+param,
      type: "POST",
      dataType: "JSON",
      success: function(data){
        $("#rekmed").val(data.rekmed);
        $("#namapas").val(data.namapas);
      }
    });
  }
</script>

<!-- simpan -->
<script>
  function save() {}
</script>