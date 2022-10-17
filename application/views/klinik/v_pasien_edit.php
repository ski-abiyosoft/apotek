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
            <span class="title-web">KLINIK <small>Data Pasien</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url('pasien');?>">
                    Daftar Pasien
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Edit Data Pasien
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
        <form id="frmpasien" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Data Diri
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Data Keluarga
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab3" data-toggle="tab">
                                Historis Kunjungan Pasien
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab3" data-toggle="tab">
                                   Lainnya
								</a>
							</li-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label">Cabang  <font color="red">*</font></label> -->
                                        <label class="col-md-3 control-label">EDIT DI CAB. <font color="red">*</font>
                                        </label>

                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-small" id="cabang"
                                                name="cabang" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Member <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nomember" id="nomember"
                                                    placeholder="Otomatis" value="<?= $data->rekmed;?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Pasien <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <select class="form-control input-small" name="preposition"
                                                    id="preposition">
                                                    <?php
																    foreach(setinghms('PREP') as $row){ ?>
                                                    <option <?= ($data->preposisi==$row->kodeset?'selected':'');?>
                                                        value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
                                                    <?php } ?>

                                                </select>
                                                <input type="text" class="form-control input-medium" name="namapasien"
                                                    id="namapasien" value="<?= $data->namapas;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Identitas <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <select class="form-control input-small" id="identitas"
                                                    name="identitas">
                                                    <option <?= ($data->idpas=='-'?'selected':'')?> value="-">-</option>
                                                    <option <?= ($data->idpas=='KTP'?'selected':'')?> value="KTP">KTP
                                                    </option>
                                                    <option <?= ($data->idpas=='SIM'?'selected':'')?> value="SIM">SIM
                                                    </option>
                                                    <option <?= ($data->idpas=='PASPORT'?'selected':'')?>
                                                        value="PASPORT">PASPORT</option>
                                                    <option
                                                        <?= ($data->idpas=='K_PELAJAR'?'selected':'')?>value="K_PELAJAR">
                                                        K_PELAJAR</option>
                                                    <option <?= ($data->idpas=='KMAHASISWA'?'selected':'')?>
                                                        value="KMAHASISWA">KMAHASISWA</option>

                                                </select>
                                                <input type="text" class="form-control input-medium" name="noidentitas"
                                                    id="noidentitas" value="<?= $data->noidentitas;?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Panggilan <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="namapanggilan"
                                                value="<?= $data->namapanggilan;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Keluarga <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="namakeluarga"
                                                value="<?= $data->namakeluarga;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tempat Lahir <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="tempatlahir"
                                                value="<?= $data->tempatlahir;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal Lahir <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control input-medium" id="tgllahir"
                                                name="tgllahir" value="<?= date('Y-m-d',strtotime($data->tgllahir));?>">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jenis Kelamin <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control input-small" name="jeniskelamin"
                                                id="jeniskelamin">
                                                <option <?= ($data->jkel=='P'?'selected':'')?> value="P">Pria</option>
                                                <option <?= ($data->jkel=='W'?'selected':'')?> value="W">Wanita</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Umur <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-medium" id="umur" name="umur"
                                                value="">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Status <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <select class="form-control select2_el_statuspasien" name="status"
                                                id="status">
                                                <?php 
																   if($data->status){ 
																    $status = data_master('tbl_setinghms', array('kodeset' => $data->status))->keterangan;
																   ?>
                                                <option value="<?= $data->status;?>"><?= $status;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Warga Negara <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control input-small" name="warganegara">
                                                <option <?= ($data->wn=='WNI'?'selected':'')?> value="WNI">WNI</option>
                                                <option <?= ($data->wn=='WNA'?'selected':'')?>value="WNA">WNA</option>
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
                                            <select class="form-control select2_el_agama" id="agama" name="agama">
                                                <?php 
																   if($data->agama){ 
																    $agama = data_master('tbl_setinghms', array('kodeset' => $data->agama))->keterangan;
																   ?>
                                                <option value="<?= $data->agama;?>"><?= $agama;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pendidikan <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control select2_el_pendidikan" id="pendidikan"
                                                name="pendidikan">
                                                <?php 
																   if($data->pendidikan){ 
																    $pendidikan = data_master('tbl_setinghms', array('kodeset' => $data->pendidikan))->keterangan;
																   ?>
                                                <option value="<?= $data->pendidikan;?>"><?= $pendidikan;?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gol. Darah <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control select2_el_goldarah" id="goldarah"
                                                name="goldarah">
                                                <?php 
																   if($data->goldarah){ 
																    $gol = data_master('tbl_setinghms', array('kodeset' => $data->goldarah))->keterangan;
																   ?>
                                                <option value="<?= $data->goldarah;?>"><?= $gol;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Hobby <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="hobby"
                                                value="<?= $data->hoby;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pekerjaan <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control select2_el_pekerjaan" id="pekerjaan"
                                                name="pekerjaan" value="<?= $data->pekerjaan;?>">
                                                <?php 
																   if($data->pekerjaan){ 
																    $pekerjaan = data_master('tbl_setinghms', array('kodeset' => $data->pekerjaan))->keterangan;
																   ?>
                                                <option value="<?= $data->pekerjaan;?>"><?= $pekerjaan;?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Info Klinik<font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <select name="lupinfoklinik" id="lupinfoklinik" class="form-control">
                                                <option value="1">Kerabat</option>
                                                <option value="2">Teman</option>
                                                <option value="3">Sahabat</option>
                                                <option value="4">Kenalan</option>
                                                <option value="5">Sosial Media</option>

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="lupinfopas" id="lupinfopas" class="form-control">
                                                <option value="1">Pasien</option>
                                                <option value="2">Bukan Pasien</option>
                                                <option value="3">Ads di Sosial Media</option>
                                                <option value="4">KOL di Sosial Media</option>
                                                <option value="5">IG</option>
                                                <option value="6">FB</option>
                                                <option value="7">Tiktok</option>
                                                <option value="8">Twitter</option>
                                                <option value="9">Website</option>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Alamat Sesuai KTP <font color="red">*
                                            </font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="alamat1" name="alamat1"
                                                value="<?= $data->alamat;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kec / KabKota /Prov <font color="red">*
                                            </font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="alamat2" name="alamat2"
                                                value="<?= $data->alamat2;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">RT/RW <font color="red">*</font></label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" id="rt" name="rt"
                                                value="<?= $data->rt;?>">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" id="rw" name="rw"
                                                value="<?= $data->rw;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Provinsi <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <select class="form-control select2_el_provinsi" id="provinsi"
                                                name="provinsi">
                                                <?php 
																   if($data->propinsi){ 
																    $propinsi = data_master('tbl_propinsi', array('kodeprop' => $data->propinsi))->namaprop;
																   ?>
                                                <option value="<?= $data->propinsi;?>"><?= $propinsi;?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kab/Kota <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="kota" id="kota">
                                                <?php 
																   if($data->kabupaten){ 
																    $kabupaten = data_master('tbl_kabupaten', array('kodekab' => $data->kabupaten))->namakab;
																   ?>
                                                <option value="<?= $data->kabupaten;?>"><?= $kabupaten;?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kecamatan <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="kecamatan" id="kecamatan">
                                                <?php 
																   if($data->kecamatan){ 
																    $kecamatan = data_master('tbl_kecamatan', array('kodekec' => $data->kecamatan))->namakec;
																   ?>
                                                <option value="<?= $data->kecamatan;?>"><?= $kecamatan;?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kelurahan <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="kelurahan" id="kelurahan">
                                                <?php 
																   if($data->kelurahan){ 
																    $kelurahan = data_master('tbl_desa', array('kodedesa' => $data->kelurahan))->namadesa;
																   ?>
                                                <option value="<?= $data->kelurahan;?>"><?= $kelurahan;?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kode Pos <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="kodepos" name="kodepos"
                                                value="<?= $data->kodepos;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Phone <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="phone"
                                                value="<?= $data->phone;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Handphone <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="hp" name="hp"
                                                value="<?= $data->handphone;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="email"
                                                value="<?= $data->email;?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">FB <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="fb" value="<?= $data->fb;?>">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Twitter <font color="red"></font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="twitter"
                                                value="<?= $data->twit?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Instagram <font color="red"></font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="ig" value="<?= $data->ig;?>">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Alasan di Edit <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="alasan" name="alasan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Kartu Penjamin
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="nocard" name="nocard" value="<?= $data->nocard;?>">
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">
                            <h4>Keluarga yang dapat dihubungi dalam keadaan darurat</h4>
                            </br>
                            <div class="row">

                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Nama</label>
                                                <div class="col-md-9">
                                                    <input id="namakel" name="namakel" class="form-control" type="text"
                                                        value="<?= $data->orhub;?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Hubungan</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder=""
                                                        name="hubungan" id="hubungan" value="<?= $data->hubungan;?>">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Jenis Kelamin</label>
                                                <div class="col-md-4">
                                                    <select name="jkkeluarga" id="jkkeluarga"
                                                        class="form-control select2me- input-medium">
                                                        <option <?= ($data->jkelhub=='P'?'selected':'')?> value="P">Pria
                                                        </option>
                                                        <option <?= ($data->jkelhub=='W'?'selected':'')?> value="W">
                                                            Wanita</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Alamat</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="alamatkel"
                                                        id="alamatkel"><?= $data->alamathub;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pekerjaan</label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_pekerjaan input-large"
                                                        id="pekerjaankel" name="pekerjaankel">
                                                        <?php 
													   if($data->pekerjaanhub){ 
														$pekerjaan = data_master('tbl_setinghms', array('kodeset' => $data->pekerjaanhub))->keterangan;
													   ?>
                                                        <option value="<?= $data->pekerjaanhub;?>"><?= $pekerjaan;?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Phone</label>
                                                <div class="col-md-9">
                                                    <input id="phonekeluarga" name="phonekeluarga" class="form-control"
                                                        type="text" value="<?= $data->phonehub;?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Handphone</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder=""
                                                        name="hpkeluarga" id="hpkeluarga" value="<?= $data->hphub;?>">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Email</label>
                                                <div class="col-md-9">
                                                    <input id="emailkeluarga" name="emailkeluarga" class="form-control"
                                                        type="text" value="<?= $data->emailhub;?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <!--div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Facebook</label>
													<div class="col-md-9">
														<input type="text" class="form-control" placeholder="" name="fbkeluarga"  value="">
													</div>

												</div>
											</div-->
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--tab2-->

                        <!-- <div class="tab-pane" id="tab3">	
							    <div class="row">
							   
								</div>
                                <div class="row">	
                                 	  
								</div>
							</div> -->
                        <!-- tab2-->

                        <div class="tab-pane" id="tab3" style="padding: 20px;">
                            <div class="row">
                                <table id="datatable_histori" class="table  table-condensed table-scrollable">
                                    <thead class="breadcrumb">
                                        <th width="5%" style="text-align: center">Cabang</th>
                                        <th width="10%" style="text-align: center">Tanggal</th>
                                        <th width="10%" style="text-align: center">Jam</th>
                                        <th width="15%" style="text-align: center">No. Reg</th>
                                        <th width="15%" style="text-align: center">Poli</th>
                                        <th width="15%" style="text-align: center">Dokter</th>
                                    </thead>
                                    <tbody id="datatable_histori_bd">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">

                            </div>
                        </div>

                    </div>
                    <!--tab-->



                    <div class="row-">

                        <div class="form-actions">

                            <input type="hidden" name="idpasien" value="<?= $id;?>">
                            <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i
                                    class="fa fa-save"></i> Simpan</button>

                            <div class="btn-group">
                                <!-- <button type="button" class="btn red" onclick="this.form.reset();location.reload();"><i class="fa fa-undo"></i> Kembali</button> -->

                                <a class="btn red" href="<?php echo base_url('pasien/')?>"><i class="fa fa-undo"></i><b>
                                        KEMBALI </b></a>
                            </div>

                            <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                    id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>

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
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
?>


<?php
//   $this->load->view('template/footer');  
//   $this->load->view('template/footero'); 
?>

<script>


gethistori();
cabb();

function cabb() {
    var vid = 'aaa';
    $.ajax({
        url: "<?php echo base_url();?>app/search_cabang2/?id=" + vid,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data.text);
            $('#cabang').val(data.id);

        }
    });
}

function gethistori() {
    var xhttp;
    var str = '<?php echo $data->rekmed; ?>';

    console.log(str);

    $('#datatable_histori tbody').empty();
    if (str == "") {

    } else {
        // $.ajax({
        //     url : "<?php echo base_url();?>pendaftaran/gethistori/?rekmed="+str,
        //     type: "GET",        
        //     success: function(data)
        //     {		      		  
        //       $('#datatable_histori tbody').append(data);
        // 	}
        // });
		
		
    // //datatables
    var table = $('#datatable_histori').DataTable({

		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.

		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('pendaftaran/get_histori_with_datatable/?rekmed='); ?>"+str,
			"type": "POST"
		},

		"oLanguage": {
			"sEmptyTable": "Tidak ada data",
			"sInfoEmpty": "Tidak ada data",
			"sInfoFiltered": " - Dipilih dari _MAX_ data",
			"sSearch": "Pencarian Data : ",
			"sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
			"sLengthMenu": "_MENU_ Baris",
			"sZeroRecords": "Tida ada data",
			"oPaginate": {
				"sPrevious": "Sebelumnya",
				"sNext": "Berikutnya"
			}
		},

		"aLengthMenu": [
			[5, 15, 20, -1],
			[5, 15, 20, "Semua"] // change per page values here
		],

		//Set column definition initialisation properties.
		"columnDefs": [{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
			{
				"targets": [3], //last column
				"className": "text-center",
			},
		],
		});
        
    }

}

function save() {
    var nomor = $('[name="nomember"]').val();
    var noidentitas = $('[name="noidentitas"]').val();

    var ccabang = document.getElementById('cabang').value;
    var cnomember = document.getElementById('nomember').value;
    var cpreposition = document.getElementById('preposition').value;
    var cnamapasien = document.getElementById('namapasien').value;
    var cnoidentitas = document.getElementById('noidentitas').value;
    var ctgllahir = document.getElementById('tgllahir').value;
    var cpendidikan = document.getElementById('pendidikan').value;
    var cpekerjaan = document.getElementById('pekerjaan').value;
    var clupinfoklinik = document.getElementById('lupinfoklinik').value;
    var clupinfopas = document.getElementById('lupinfopas').value;
    var calamat1 = document.getElementById('alamat1').value;
    var calamat2 = document.getElementById('alamat2').value;
    var crt = document.getElementById('rt').value;
    var crw = document.getElementById('rw').value;
    var cprovinsi = document.getElementById('provinsi').value;
    var ckelurahan = document.getElementById('kelurahan').value;
    var chp = document.getElementById('hp').value;
    var calasan = document.getElementById('alasan').value;
    var cnocard = document.getElementById('nocard').value;

    //-- TAMBAHAN PROTEKSI DIY --//
    if (ccabang == '') {
        swal({
            title: "CABANG",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cnomember == '') {
        swal({
            title: "NOMOR MEMBER",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cpreposition == '') {
        swal({
            title: "PREPOSISI",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cnamapasien == '') {
        swal({
            title: "NAMA PASIEN",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cnoidentitas == '') {
        swal({
            title: "NOMOR IDENTITAS",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (ctgllahir == '') {
        swal({
            title: "TANGGAL LAHIR",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cpendidikan == '') {
        swal({
            title: "PENDIDIKAN",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cpekerjaan == '') {
        swal({
            title: "PEKERJAAN",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (clupinfoklinik == '') {
        swal({
            title: "INFO KLINIK",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (clupinfopas == '') {
        swal({
            title: "INFO KLINIK 2",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calamat1 == '') {
        swal({
            title: "Alamat Sesuai KTP",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calamat2 == '') {
        swal({
            title: "Kec / KabKota /Prov",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (crt == '') {
        swal({
            title: "RT",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (crw == '') {
        swal({
            title: "RW",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (cprovinsi == '') {
        swal({
            title: "PROVINSI",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    // if (ckelurahan==''){
    // swal({
    //           title: "KELURAHAN",
    //           html: " Tidak Boleh Kosong .!!!",
    //           type: "error",
    //           confirmButtonText: "OK" 
    //      });    
    // return;
    // } 

    if (chp == '') {
        swal({
            title: "NOMOR HP",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calasan == '') {
        swal({
            title: "ALASAN EDIT",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (nomor == "" || noidentitas == "") {
        swal('', 'Data Belum Lengkap ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('pasien/ajax_update')?>',
            data: $('#frmpasien').serialize(),
            type: 'POST',

            success: function(data) {
                swal('', 'Update Data berhasil ...', '');
            },
            error: function(data) {
                swal('', 'Data gagal disimpan ...', '');

            }
        });
    }
}

$('#tgllahir').on('change', function() {
    var birthDate = new Date(this.value);
    var usia = hitung_usia(birthDate);
    $('#umur').val(usia);
});

$('#tgllahir').trigger('change');


function getprovinsi(kode) {
    $.ajax({
        url: "<?php echo base_url();?>app/namaprovinsi/?kode=" + kode,
        type: "GET",
        success: function(data) {
            var selectElement = document.getElementById('provinsi');
            var opt = document.createElement('option');
            opt.value = kode;
            opt.innerHTML = data;
            selectElement.appendChild(opt);
        }
    });
}

function getkota(kode) {
    $.ajax({
        url: "<?php echo base_url();?>app/namakota/?kode=" + kode,
        type: "GET",
        success: function(data) {
            var selectElement = document.getElementById('kota');
            var opt = document.createElement('option');
            opt.value = kode;
            opt.innerHTML = data;
            selectElement.appendChild(opt);
        }
    });
}

function getkecamatan(kode) {
    $.ajax({
        url: "<?php echo base_url();?>app/namakecamatan/?kode=" + kode,
        type: "GET",
        success: function(data) {
            var selectElement = document.getElementById('kecamatan');
            var opt = document.createElement('option');
            opt.value = kode;
            opt.innerHTML = data;
            selectElement.appendChild(opt);
        }
    });
}

$('#noidentitas').on('change', function() {
    var noktp = this.value;
    var prov = noktp.substring(0, 2);
    var kota = noktp.substring(0, 4);
    var kec = noktp.substring(0, 6);

    getprovinsi(prov);
    getkota(kota);
    getkecamatan(kec);


});

$('#preposition').on('change', function() {

    //var prep = document.getElementById("preposition").options[preposition.selectedIndex].innerHTML;	
    var prep = this.value;
    $.ajax({
        url: "<?php echo base_url();?>app/getvaluesetinghms/?kode=" + prep,
        type: "GET",
        dataType: 'json',
        success: function(data) {
            var hasil = eval(data.data.valuerp);
            if (hasil == 2) {
                $('#jeniskelamin').val('W');
            } else {
                $('#jeniskelamin').val('P');
            }

        }
    });

});


function getHistory(noreg,rekmed) {
    $('#history_panel').load("<?php echo base_url();?>pendaftaran/history_pasien/?noreg="+ noreg+"&rekmed=" + rekmed);
    $('#history_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Historis Pasien'); // Set Title to Bootstrap modal title	
}


function hitung_usia( tgllahir ){
	var birthDate = new Date(tgllahir);	
	const EPOCH = new Date(0);
	const EPOCH_YEAR = EPOCH.getUTCFullYear();
	const EPOCH_MONTH = EPOCH.getUTCMonth();
	const EPOCH_DAY = EPOCH.getUTCDate();

	const diff = new Date(Date.now() - birthDate.getTime());

	var years =  Math.abs(diff.getUTCFullYear() - EPOCH_YEAR);
	var months=  Math.abs(diff.getUTCMonth() - EPOCH_MONTH);
	var days  =  Math.abs(diff.getUTCDate() - EPOCH_DAY);			
	var age   = years+' Tahun '+months+' Bulan '+days+' Hari';
	return age;
}

</script>


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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>

</html>