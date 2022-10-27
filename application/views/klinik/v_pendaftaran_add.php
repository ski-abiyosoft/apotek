<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    
    date_default_timezone_set("Asia/Jakarta");	
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">KLINIK <small>Pendaftaran</small>
        </h3>

    </div>
</div>
<form id="frmdaftar" class="form-horizontal" method="post">
    <!-- <input type="hidden" id="namapasien_hidden" name="namapasien_hidden" value=""> 
				<input type="hidden" id="idtr" name="idtr" value="">  -->
    <div class="row">

        <div class="col-md-6">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i><b>REGISTRASI</b>
                    </div>
                    <button halign="right" type="button" class="btn info "
                        style="float:right; margin-top:-5px;border: 1px solid white; color:blue;"
                        onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Reset
                        Daftar Baru</button>

                </div>

                <div class="portlet-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No. Reg <font color="red"></font></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="nodaftar" name="nodaftar"
                                        placeholder="Otomatis" value="" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">
                                    <a href="#" onclick="editpasien()"> Pasien </a>
                                    <font color="red">*</font>
                                </label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control select2_el_pasien" onchange="getinfopasien()"
                                            id="pasien" name="pasien">
                                        </select>
                                        <input type="hidden" id="idpasien">
                                        <span class="input-group-btn">
                                            <a class="btn-sm btn green" onclick="add_pasien()"><i
                                                    class="fa fa-plus"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tanggal Masuk <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="date" class="form-control input-medium" name="tanggal"
                                            placeholder="Otomatis" value="<?= date('Y-m-d');?>">
                                        <input type="time" class="form-control input-small" name="jam"
                                            placeholder="Otomatis" value="<?= date('H:i:s');?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>

                    </div>




                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i><b>TUJUAN DOKTER</b>
                    </div>

                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Poli <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select class="form-control select2_el_poli" id="poli" name="poli">
                                        <!-- <option value='KULIT'>UNIT KULIT</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Dokter <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select class="form-control select2me" id="dokter" name="dokter"
                                        onchange="getRuang()" onclick="getRuang()">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Ruang <font color="red">*</font></label>
                                <div class="col-md-5">
                                    <select class="form-control select2me" id="ruang" name="ruang">
                                        <option value="">--- Pilih ---</option>
                                        <?php $data = $this->db->get("tbl_ruangpoli")->result();
									foreach($data as $row){ ?>
                                        <option value="<?= $row->koderuang;?>"><?= $row->namaruang;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <label class="col-md-2 control-label">Antri No <font color="red"></font></label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="noantri" id="noantri" value="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pengirim <font color="red"></font></label>
                                <div class="col-md-9">
                                    <select class="form-control select2_el_dokter" id="pengirim" name="pengirim">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Jenis Pasien <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select class="form-control select2_el_jenispasien" id="jenispasien"
                                        name="jenispasien">
                                        <option value="">--- Pilih ---</option>
                                        <?php $jenis = $this->db->get_where("tbl_setinghms", array("lset" => 'JPAS'))->result();
										foreach($jenis as $row){ 
										$selected = ($row->kodeset==$data->jenispas?'selected':'');
										?>
                                        <option <?= $selected;?> value="<?= $row->kodeset;?>"><?= $row->keterangan;?>
                                        </option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="vpenjamin">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Penjamin <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select class="form-control select2_el_penjamin" id="penjamin" name="penjamin">
                                    </select>

                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>
    </div>
    <table style="width:100%;" border="0">
        <tr>
            <td width="10%">
                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                    <b>Simpan</b></button>
            </td>
            <td width="10%">
                <div class="btn-group">
                    <button type="button" id="btncetak" class="btn btn-warning"> <i class="fa fa-print"></i>
                        <b>Cetak</b></button>
                    <button type="button" id="btncetak1" class="btn btn-warning dropdown-toggle"
                        data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
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
            </td>
            <td width="80%" align="right">
                <!-- <button halign="right" type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Daftar Baru</button>   -->
            </td>
        </tr>

    </table>
</form>
</br>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i><b>Data Lengkap Pasien</b>
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                </div>

            </div>

            <div class="portlet-body">
                <form id="frmpasien" class="form-horizontal" method="post">
                    <input type="hidden" name="idpasien" id="idpasien2">
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
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">KLINIK ESTETIKA <font color="red">
                                                        *</font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control input-small" id="cabang"
                                                        name="cabang" readonly>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">No. Member <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="nomember"
                                                            name="nomember" placeholder="Otomatis" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Nama Pasien <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <div class="input-group">

                                                        <select class="form-control input-small" name="preposition"
                                                            id="preposition">
                                                            <?php
												foreach(setinghms('PREP') as $row){ ?>
                                                            <option value="<?= $row->kodeset;?>"><?= $row->keterangan;?>
                                                            </option>
                                                            <?php } ?>

                                                        </select>
                                                        <input type="text" class="form-control input-medium"
                                                            name="namapasien" id="namapasien" value="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Identitas <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <select class="form-control input-small" name="identitas"
                                                            id="identitas">
                                                            <option value="-">-</option>
                                                            <option value="KTP">KTP</option>
                                                            <option value="SIM">SIM</option>
                                                            <option value="PASPORT">PASPORT</option>
                                                            <option value="K_PELAJAR">K_PELAJAR</option>
                                                            <option value="KMAHASISWA">KMAHASISWA</option>

                                                        </select>
                                                        <input type="text" class="form-control input-medium"
                                                            name="noidentitas" id="noidentitas" value="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Panggilan <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="namapanggilan"
                                                        name="namapanggilan" value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Nama Keluarga <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="namakeluarga"
                                                        name="namakeluarga" value="">

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Tempat Lahir <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="tempatlahir"
                                                        name="tempatlahir" value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Tanggal Lahir <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control input-medium" id="tgllahir"
                                                        name="tgllahir" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Jenis Kelamin <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control input-small" id="jeniskelamin"
                                                        name="jeniskelamin">
                                                        <option value="P">Pria</option>
                                                        <option value="W">Wanita</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Umur <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control input-medium" id="umur"
                                                        name="umur" value="" readonly>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Status <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_statuspasien" name="status"
                                                        id="status">

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Warga Negara <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control input-small" id="warganegara"
                                                        name="warganegara">
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
                                                <label class="col-md-3 control-label">Agama <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_agama" id="agama"
                                                        name="agama">
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pendidikan <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_pendidikan" id="pendidikan"
                                                        name="pendidikan">
                                                    </select>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Gol. Darah <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">

                                                    <select class="form-control select2_el_goldarah" id="goldarah"
                                                        name="goldarah">
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Hobby <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="hobby" name="hobby"
                                                        value="">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pekerjaan <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_pekerjaan" id="pekerjaan"
                                                        name="pekerjaan">
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Info Klinik<font color="red">*
                                                    </font></label>
                                                <div class="col-md-4">
                                                    <select name="lupinfoklinik" id="lupinfoklinik"
                                                        class="form-control">
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
                                                <label class="col-md-3 control-label">Alamat Sesuai KTP <font
                                                        color="red">*</font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="alamat1" name="alamat1"
                                                        value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Kec / KabKota /Prov<font
                                                        color="red">*</font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="alamat2" name="alamat2"
                                                        value="">

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">RT/RW <font color="red">*</font>
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" id="rt" name="rt" value="">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" id="rw" name="rw" value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Provinsi <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2_el_provinsi" id="provinsi"
                                                        name="provinsi">
                                                    </select>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Kode Pos <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="kodepos" name="kodepos"
                                                        value="">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Kab/Kota <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="kota" id="kota">

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Handphone <font color="red">*
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="hp" name="hp" value="">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Kecamatan <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="kecamatan" id="kecamatan">
                                                    </select>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Phone <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Kelurahan <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="kelurahan" id="kelurahan">

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Email <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="email" name="email"
                                                        value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">FB <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="fb" name="fb" value="">

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Twitter <font color="red"></font>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="twitter" name="twitter"
                                                        value="">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Instagram <font color="red">
                                                    </font></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="ig" name="ig" value="">
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>

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
                                                            <input id="namakel" name="namakel" class="form-control"
                                                                type="text" value="" />

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Hubungan</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder=""
                                                                name="hubungan" id="hubungan" value="">
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
                                                                <option value="Pria">Pria</option>
                                                                <option value="Wanita">Wanita</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Alamat</label>
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="alamatkel"
                                                                id="alamatkel"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Pekerjaan</label>
                                                        <div class="col-md-9">
                                                            <select
                                                                class="form-control select2_el_pekerjaan input-large"
                                                                id="pekerjaankel" name="pekerjaankel">
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
                                                            <input id="phonekeluarga" name="phonekeluarga"
                                                                class="form-control" type="text" value="" />

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Handphone</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder=""
                                                                name="hpkeluarga" id="hpkeluarga" value="">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Email</label>
                                                        <div class="col-md-9">
                                                            <input id="emailkeluarga" name="emailkeluarga"
                                                                class="form-control" type="text" value="" />

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

                                <div class="tab-pane" id="tab3" style="padding: 20px;">
                                    <div class="row">
                                        <table id="datatable_histori" class="table  table-condensed table-scrollable">
                                            <thead class="breadcrumb">
                                                <th class="title-white" width="5%" style="text-align: center"> Cabang
                                                </th>
                                                <th class="title-white" width="10%" style="text-align: center">Tanggal
                                                </th>
                                                <th class="title-white" width="10%" style="text-align: center">Jam</th>
                                                <th class="title-white" width="15%" style="text-align: center">No. Reg
                                                </th>
                                                <th class="title-white" width="15%" style="text-align: center">Poli</th>
                                                <th class="title-white" width="15%" style="text-align: center">Dokter
                                                </th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">

                                    </div>
                                </div>

                            </div>



                            <div class="row-">




                            </div>
                        </div>
                    </div>


                    <div class="form-actions">
                        <button id="btnsimpaneditpasien" type="button" onclick="saveeditpasien()" class="btn blue"><i
                                class="fa fa-save"></i> <b>Simpan Data Pasien</b></button>

                        <a class="btn red" href="<?php echo base_url('Pendaftaran')?>"><i class="fa fa-undo"></i><b>
                                KEMBALI </b></a>
                    </div>



                </form>
            </div>

        </div>
    </div>
</div>

</br>


</div>
</div>
</div>

<?php
  $this->load->view('template/footer_tb');  
?>

<script>
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

function save() {
    var tanggal = $('[name="tanggal"]').val();
    var pasien = $('[name="pasien"]').val();
    var poli = $('[name="poli"]').val();
    var dokter = $('[name="dokter"]').val();
    var ruang = $('[name="ruang"]').val();
    var jenispasien = $('[name="jenispasien"]').val();
    var noantri = $('[name="noantri"]').val();
    var nomor = $('[name="nomember"]').val();
    var ccabang = document.getElementById('cabang').value;
    var cpreposition = document.getElementById('preposition').value;
    var cnamapasien = document.getElementById('namapasien').value;
    var cidentitas = document.getElementById('identitas').value;
    var cnoidentitas = document.getElementById('noidentitas').value;
    var ctempatlahir = document.getElementById('tempatlahir').value;
    var ctgllahir = document.getElementById('tgllahir').value;
    var cjeniskelamin = document.getElementById('jeniskelamin').value;
    var cstatus = document.getElementById('status').value;
    var cpendidikan = document.getElementById('pendidikan').value;
    var cgoldarah = document.getElementById('goldarah').value;
    var chobby = document.getElementById('hobby').value;
    var cpekerjaan = document.getElementById('pekerjaan').value;
    var calamat1 = document.getElementById('alamat1').value;
    var calamat2 = document.getElementById('alamat2').value;
    var crt = document.getElementById('rt').value;
    var crw = document.getElementById('rw').value;
    var cprovinsi = document.getElementById('provinsi').value;
    var ckota = document.getElementById('kota').value;
    var ckecamatan = document.getElementById('kecamatan').value;
    var ckelurahan = document.getElementById('kelurahan').value;
    var ckodepos = document.getElementById('kodepos').value;
    var chp = document.getElementById('hp').value;
    var ckelurahan = document.getElementById('kelurahan').value;
    var clupinfoklinik = document.getElementById('lupinfoklinik').value;
    var clupinfopas = document.getElementById('lupinfopas').value;

    if (poli == '' || poli == null) {
        swal({
            title: "POLI",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }
    if (dokter == '' || dokter == null) {
        swal({
            title: "DOKTER",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }
    if (ruang == '' || ruang == null) {
        swal({
            title: "RUANG",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }
    if (jenispasien == '' || jenispasien == null) {
        swal({
            title: "JENIS PASIEN",
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
            title: "CEK INFO KLINIK",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (ccabang == '') {
        swal({
            title: "CABANG",
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

    if (cidentitas == '') {
        swal({
            title: "IDENTITAS",
            html: " Harus Di Pilih .!!!",
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

    if (cjeniskelamin == '') {
        swal({
            title: "JENIS KELAMIN",
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

    if (calamat1 == '') {
        swal({
            title: "ALAMAT",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calamat2 == '') {
        swal({
            title: "Kecamatan / KabKota / Prov",
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

    if (cpendidikan == '') {
        swal({
            title: "Pendidikan",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (chp == '') {
        swal({
            title: "NOMOR HP",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (nomor == "") {
        swal('error', 'Data Belum Lengkap ...', 'error');
    } else {
        $.ajax({
            url: '<?php echo site_url('pasien/ajax_update')?>',
            data: $('#frmpasien').serialize(),
            type: 'POST',

            success: function(data) {

                swal({
                    title: "UPDATE DATA",
                    html: " Berhasil dilakukan !!!",
                    type: "success",
                    confirmButtonText: "OK"
                });

            },
            error: function(data) {
                swal({
                    title: "UPDATE DATA",
                    html: " Gagal dilakukan !!!",
                    type: "error",
                    confirmButtonText: "OK"
                });

            }
        });
    }



    if (pasien == "" || pasien == null) {
        swal('PENDAFTARAN', 'Data Belum Lengkap ...', '');
    } else {
        $.ajax({
            url: "<?php echo site_url('pendaftaran/ajax_add')?>",
            data: $('#frmdaftar').serialize(),
            type: 'POST',
            dataType: 'json',

            success: function(data) {
                swal(data.noreg, 'Pendaftaran berhasil ...', '');
                swal('', data.pesan, '');
                $('#nodaftar').val(data.noreg);
                swal({
                    title: "",
                    html: data.pesan,
                    type: "success",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>pendaftaran";
                });

                document.getElementById("btnsimpan").disabled = true;
                //document.getElementById("tersimpan").value="OK";
                /* 
		 swal({
					  title: "PENDAFTARAN",
					  html: "<p> Pasien   : <b>"+pasien+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>pendaftaran";
		         });				
	    */
            },
            error: function(data) {
                swal('PENDAFTARAN', 'Data gagal disimpan ...', '');

            }
        });
    }
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
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable 
    var url;

    var cluppreposition = document.getElementById('luppreposition').value;
    var clupkelamin = document.getElementById('lupkelamin').value;
    var clupnamapasien = document.getElementById('lupnamapasien').value;
    var cluptelp = document.getElementById('luptelp').value;
    var cluphp = document.getElementById('luphp').value;
    var cekhp = cluphp.substring(0, 3);
    var cluptempatlahir = document.getElementById('luptempatlahir').value;
    var cluptgllahir = document.getElementById('luptgllahir').value;
    var clupstatus = document.getElementById('lupstatus').value;
    var clupalamat = document.getElementById('lupalamat').value;
    var clupidentitas = document.getElementById('lupidentitas').value;
    var clupnoidentitas = document.getElementById('lupnoidentitas').value;


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


        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable 
        return;
    }

    if (clupkelamin == '') {
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
    if (clupalamat == '') {
        $('#modal_form').modal('hide');
        swal({
            title: "ALAMAT",
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
            title: "Nomor Hp",
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


    url = "<?php echo site_url('pasien/ajax_add')?>";

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
            if (data.status) //if success close modal and reload ajax table
            {
                if (data.value == 1) {

                    $('#idtr').val(data.idtr);
                    var selectElement = null;
                    selectElement = document.getElementById('pasien');
                    var opt = document.createElement('option');
                    opt.value = data.rekmed;
                    opt.innerHTML = data.rekmed + ' | ' + data.nama + ' | ' + data.alamat;
                    selectElement.removeChild(selectElement.lastChild);
                    selectElement.appendChild(opt);
                    $('#nomember').val(data.rekmed);

                    getinfopasien();

                    $('#modal_form').modal('hide');
                    swal({
                        title: "DATA PASIEN",
                        html: " Berhasil dibuat <br/> dengan Nomor Member <br/><b><span style='color:green;font-size:30px;font-weight:bold;'>" +
                            data.rekmed + "</span></b>",
                        type: "success",
                        confirmButtonText: "OK"

                    });

                    document.getElementById("btncetak").disabled = false;
                    document.getElementById("btncetak1").disabled = false;

                } else {
                    // swal('PASIEN','Data Pasien sudah terdaftar ...','');
                    $('#modal_form').modal('hide');
                    swal({
                        title: "PASIEN",
                        html: " Data Pasien sudah terdaftar .!!",
                        type: "error",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        $('#modal_form').modal('show');
                    });
                    //   alert('Pasien sudah terdaftar');
                }
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass(
                        'has-error'
                    ); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[
                        i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 


        },

        error: function(jqXHR, textStatus, errorThrown) {
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 

        }

    });
}

function editpasien() {
    var vid = $('#idpasien').val();
    if (vid != "") {
        location.href = "<?= base_url()?>pasien/update/" + vid;
    }
}

function getinfopasien() {
    var xhttp;
    var vid = $('#pasien').val();

    $.ajax({
        url: "<?php echo base_url();?>pasien/getinfopasien/?id=" + vid, // _directRegist
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            // $('#namapasien_hidden').val(data.namapas);

            $('#idpasien').val(data.idtr);
            $('#idpasien2').val(data.idtr);
            $('#namapasien').val(data.namapas);
            $('#nomember').val(data.rekmed);
            $('#namapanggilan').val(data.namapanggilan);
            $('#namakeluarga').val(data.namakeluarga);
            $('#tgllahir').val(data.tanggallahir);
            $('#tempatlahir').val(data.tempatlahir);
            $('#alamat1').val(data.alamat);
            $('#alamat2').val(data.alamat2);
            $('#agama').val(data.agama);
            $('#goldarah').val(data.goldarah);
            $('#hp').val(data.handphone);
            $('#phone').val(data.phone);
            $('#identitas').val(data.idpas);
            $('#noidentitas').val(data.noidentitas);
            $('#warganegara').val(data.wn);
            $('#jeniskelamin').val(data.jkel);
            $('#twitter').val(data.twit);
            $('#fb').val(data.fb);
            $('#ig').val(data.ig);
            $('#hobby').val(data.hoby);
            $('#namakel').val(data.orhub);
            $('#hubungan').val(data.hubungan);
            $('#alamatkel').val(data.alamathub);
            $('#emailkeluarga').val(data.emailhub);
            $('#phonekeluarga').val(data.phonehub);
            $('#hpkeluarga').val(data.hphub);
            $('#rt').val(data.rt);
            $('#rw').val(data.rw);
            $('#kodepos').val(data.kodepos);
            $('#lupinfoklinik').val(data.iklinik);
            $('#lupinfopas').val(data.cekiklinik);

            var selectElement = document.getElementById('preposition');
            var opt = document.createElement('option');
            opt.value = data.preposisi;
            opt.innerHTML = data.namapreposisi;
            selectElement.appendChild(opt);
            $('#preposition').val(data.preposisi);

            var selectElement = document.getElementById('status');
            var opt = document.createElement('option');
            opt.value = data.status;
            opt.innerHTML = data.namastatus;
            selectElement.appendChild(opt);
            $('#status').val(data.status);

            // var selectElement = document.getElementById('idpas');
            // var opt = document.createElement('option');
            // opt.value = data.idpas;
            // opt.innerHTML = data.namaidpas;
            // selectElement.appendChild(opt);			
            // $('#identitas').val(data.idpas);

            var selectElement = document.getElementById('goldarah');
            var opt = document.createElement('option');
            opt.value = data.goldarah;
            opt.innerHTML = data.goldarah;
            selectElement.appendChild(opt);

            var selectElement = document.getElementById('cabang');
            var opt = document.createElement('option');
            opt.value = data.koders;
            opt.innerHTML = data.namars;
            selectElement.appendChild(opt);

            if (data.propinsi != '') {
                var selectElement = document.getElementById('provinsi');
                var opt = document.createElement('option');
                opt.value = data.propinsi;
                opt.innerHTML = data.namaprop;
                selectElement.appendChild(opt);
            }


            if (data.kabupaten != '') {
                var selectElement = document.getElementById('kota');
                var opt = document.createElement('option');
                opt.value = data.kabupaten;
                opt.innerHTML = data.namakab;
                opt.selected;
                selectElement.appendChild(opt);
            }

            if (data.kecamatan != '') {
                var selectElement = document.getElementById('kecamatan');
                var opt = document.createElement('option');
                opt.value = data.kecamatan;
                opt.innerHTML = data.namakec;
                selectElement.appendChild(opt);
            }

            if (data.kelurahan != '') {
                var selectElement = document.getElementById('kelurahan');
                var opt = document.createElement('option');
                opt.value = data.kelurahan;
                opt.innerHTML = data.namadesa;
                selectElement.appendChild(opt);
            }

            var selectElement = document.getElementById('agama');
            var opt = document.createElement('option');
            opt.value = data.agama;
            opt.innerHTML = data.namaagama;
            selectElement.appendChild(opt);


            var selectElement = document.getElementById('pendidikan');
            var opt = document.createElement('option');
            opt.value = data.pendidikan;
            opt.innerHTML = data.namapendidikan;
            selectElement.appendChild(opt);

            var selectElement = document.getElementById('pekerjaan');
            var opt = document.createElement('option');
            opt.value = data.pekerjaan;
            opt.innerHTML = data.namapekerjaan;
            selectElement.appendChild(opt);

            var selectElement = document.getElementById('pekerjaankel');
            var opt = document.createElement('option');
            opt.value = data.pekerjaanhub;
            opt.innerHTML = data.namapekerjaan;
            selectElement.appendChild(opt);

            $('#email').val(data.email);
            $('#goldarah').val(data.goldarah);
            $('#tgllahir').trigger('change');

            gethistori();
        }
    });


}

function saveeditpasien() {
    var nomor = $('[name="nomember"]').val();
    var ccabang = document.getElementById('cabang').value;
    var cpreposition = document.getElementById('preposition').value;
    var cnamapasien = document.getElementById('namapasien').value;
    var cidentitas = document.getElementById('identitas').value;
    var cnoidentitas = document.getElementById('noidentitas').value;
    var ctempatlahir = document.getElementById('tempatlahir').value;
    var ctgllahir = document.getElementById('tgllahir').value;
    var cjeniskelamin = document.getElementById('jeniskelamin').value;
    var cstatus = document.getElementById('status').value;
    var cpendidikan = document.getElementById('pendidikan').value;
    var cgoldarah = document.getElementById('goldarah').value;
    var chobby = document.getElementById('hobby').value;
    var cpekerjaan = document.getElementById('pekerjaan').value;
    var calamat1 = document.getElementById('alamat1').value;
    var calamat2 = document.getElementById('alamat2').value;
    var crt = document.getElementById('rt').value;
    var crw = document.getElementById('rw').value;
    var cprovinsi = document.getElementById('provinsi').value;
    var ckota = document.getElementById('kota').value;
    var ckecamatan = document.getElementById('kecamatan').value;
    var ckelurahan = document.getElementById('kelurahan').value;
    var ckodepos = document.getElementById('kodepos').value;
    var chp = document.getElementById('hp').value;
    var ckelurahan = document.getElementById('kelurahan').value;
    var clupinfoklinik = document.getElementById('lupinfoklinik').value;
    var clupinfopas = document.getElementById('lupinfopas').value;


    if (ccabang == '') {
        swal({
            title: "CABANG",
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

    if (cidentitas == '') {
        swal({
            title: "IDENTITAS",
            html: " Harus Di Pilih .!!!",
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

    if (cjeniskelamin == '') {
        swal({
            title: "JENIS KELAMIN",
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

    if (cpendidikan == '') {
        swal({
            title: "Pendidikan",
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
            title: "CEK INFO KLINIK",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calamat1 == '') {
        swal({
            title: "ALAMAT",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (calamat2 == '') {
        swal({
            title: "Kecamatan / KabKota / Prov",
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

    if (chp == '') {
        swal({
            title: "NOMOR HP",
            html: " Tidak Boleh Kosong .!!!",
            type: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    if (nomor == "") {
        swal('', 'Data Belum Lengkap ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('pasien/ajax_update')?>',
            data: $('#frmpasien').serialize(),
            type: 'POST',

            success: function(data) {

                swal({
                    title: "UPDATE DATA",
                    html: " Berhasil dilakukan !!!",
                    type: "success",
                    confirmButtonText: "OK"
                });

            },
            error: function(data) {
                swal({
                    title: "UPDATE DATA",
                    html: " Gagal dilakukan !!!",
                    type: "error",
                    confirmButtonText: "OK"
                });

            }
        });
    }
}

$('#tgllahir').on('change', function() {
    var birthDate = new Date(this.value);
    var usia = hitung_usia(birthDate);
    $('#umur').val(usia);
});
</script>

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
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Preposisi</label>
                                    <div class="col-md-6">

                                        <select class="form-control input-small" name="luppreposition"
                                            id="luppreposition">
                                            <?php
									foreach(setinghms('PREP') as $row){ ?>
                                            <option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
                                            <?php } ?>

                                        </select>



                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Kelamin</label>
                                    <div class="col-md-3">
                                        <select name="lupkelamin" id="lupkelamin" class="form-control">
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>

                                        </select>
                                    </div>
                                    <label class="control-label col-md-3">Warna Negara</label>
                                    <div class="col-md-3">
                                        <select name="lupwarganegara" id="lupwarganegara" class="form-control">
                                            <option value="WNI" selected>WNI</option>
                                            <option value="WNA">WNA</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Pasien</label>
                                    <div class="col-md-9">
                                        <input name="lupnamapasien" required="required" id="lupnamapasien"
                                            placeholder="Nama Pasien" onkeyup="myFunction()" class="form-control"
                                            type="text">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Telpon</label>
                                    <div class="col-md-3">
                                        <input name="luptelp" id="luptelp" placeholder="Nomor Telp" class="form-control"
                                            maxlength="" type="text">
                                    </div>
                                    <label class="control-label col-md-2">Handphone</label>
                                    <div class="col-md-4">
                                        <input name="luphp" id="luphp" placeholder="Dimulai +62 Tanpa 0"
                                            class="form-control" maxlength="" type="text">

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Tempat Lahir</label>
                                    <div class="col-md-3">
                                        <input name="luptempatlahir" id="luptempatlahir" placeholder="Tempat Lahir" class="form-control" type="text" onkeyup="myFunction()">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Tgl. Lahir</label>
                                    <div class="col-md-4">
                                        <input id="luptgllahir" name="luptgllahir" placeholder="Tanggal Lahir"
                                            class="form-control" type="date">
                                        <input id="lupumur" name="lupumur" placeholder="" class="form-control"
                                            type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="lupstatus" name="lupstatus">
                                            <?php
									foreach(setinghms('STAT') as $row){ ?>
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
                                    <label class="control-label col-md-3">Alamat</label>
                                    <div class="col-md-9">
                                        <input name="lupalamat" id="lupalamat" placeholder="Alamat Pasien" class="form-control" type="text" onkeyup="myFunction()">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Identitas</label>
                                    <div class="col-md-3">
                                        <select name="lupidentitas" id="lupidentitas" class="form-control">
                                            <option value="-">-</option>
                                            <option value="KTP">KTP</option>
                                            <option value="SIM">SIM</option>
                                            <option value="PASPORT">PASPORT</option>
                                            <option value="K_PELAJAR">K_PELAJAR</option>
                                            <option value="KMAHASISWA">KMAHASISWA</option>


                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">Nomor</label>
                                    <div class="col-md-4">
                                        <input type="text" name="lupnoidentitas" id="lupnoidentitas"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_pasien()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</body>

</html>

<script>
$('#luptgllahir').on('change', function() {
    var birthDate = new Date(this.value);
    var usia = hitung_usia(birthDate);
    $('#lupumur').val(usia);
});

$('#vpenjamin').hide();

$('#jenispasien').change(function() {
    var jenis = $(this).val();

    if (jenis == 'PAS2' || jenis == 'PAS3') {
        $('#vpenjamin').show();
    } else {
        $('#vpenjamin').hide();

    }
});

function myFunction() {
    var x = document.getElementById("lupnamapasien");
    x.value = x.value.toUpperCase();

    var y = document.getElementById("luptempatlahir");
    y.value = y.value.toUpperCase();

    var z = document.getElementById("lupalamat");
    z.value = z.value.toUpperCase();
}

function _urlcetak1() {
    var baseurl = "<?php echo base_url()?>";
    var noreg = $('#nodaftar').val();
    return baseurl + 'pendaftaran/cetak1/?noreg=' + noreg;
}

function _urlcetak2() {
    var baseurl = "<?php echo base_url()?>";
    var noreg = $('#nodaftar').val();
    return baseurl + 'pendaftaran/cetak2/?noreg=' + noreg;
}


document.getElementById("btncetak").disabled = true;
document.getElementById("btncetak1").disabled = true;

function gethistori() {
    var xhttp;
    var str = $('#pasien').val();
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

        if ($.fn.dataTable.isDataTable('#datatable_histori')) {
            table.destroy();
        } else {
            console.log('falsee');
        }

        table = $('#datatable_histori').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('pendaftaran/get_histori_with_datatable/?rekmed='); ?>" + str,
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

function getRuang() {
    $("#ruang").val("1").change();
    $('#jenispasien').val("PAS1").change();
}

function getkecamatan(kode) {
    $.ajax({
        url: "<?php echo base_url();?>app/namakecamatan/?kode=" + kode,
        type: "GET",
        success: function(data) {
            cek = data;
            var selectElement = document.getElementById('kecamatan');
            var opt = document.createElement('option');
            opt.value = kode;
            opt.innerHTML = data;
            selectElement.appendChild(opt);
            getdesa(kode);
        }
    });
}

function getdesa(kode) {
    var option = "<option value=''>--- Pilih Kelurahan/Desa ---</option>"
    $.ajax({
        url: '<?php echo base_url() ?>app/getDesa/' + kode,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.message == "Success") {
                $.each(data.data, function(index, val) {
                    option += "<option value='" + val.kodedesa + "'>" + val.namadesa + "</option>";
                });
                $('#kelurahan').html(option);
            }
        }
    });
}

$('#noidentitas').on('change', function() {
    var noktp = this.value;
    console.log('noktp : ' + noktp);
    var prov = noktp.substring(0, 2);
    var kota = noktp.substring(0, 4);
    var kec = noktp.substring(0, 6);
    getprovinsi(prov);
    getkota(kota);
    getkecamatan(kec);
    $('#kecamatan').click();
});

$('#lupnoidentitas').on('change', function() {
    var ceknoktp = this.value;
    console.log('ceknoktp : ' + ceknoktp);
    var prov = ceknoktp.substring(0, 2);
    var kota = ceknoktp.substring(0, 4);
    var kec = ceknoktp.substring(0, 6);
    getprovinsi(prov);
    getkota(kota);
    getkecamatan(kec);
    $('#kecamatan').click();
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



function getHistory(noreg) {
    $('#history_panel').load("<?php echo base_url();?>pendaftaran/history_pasien/?noreg=" + noreg);
    $('#history_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Historis Pasien'); // Set Title to Bootstrap modal title	
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