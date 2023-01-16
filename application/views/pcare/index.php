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
            <span class="title-web">e-HMS <small>Pendaftaran Pasien</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url('PendaftaranVRS');?>">Pendaftaran VRS</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>							
                <a class="title-white" href="">Briging Pendaftaran Pasien</a>
            </li>
        </ul>
    </div>
</div>

<form id="form_bridging" class="form-horizontal" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i><b>P-CARE BRIGING SYSTEM - ABIYOSOFT</b>
                    </div>
                </div> 

                <div class="portlet-body">	                        
                    <div class="form-body">  
                        <div class="tabbable tabbable-custom tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active" id="pcare_pendaftaran">
                                    <a href="#tab1" data-toggle="tab">
                                        Pendaftaran
                                    </a>
                                </li>
                                <!-- <li id="pcare_kunjungan" style="display:none">
                                    <a href="#tab3" data-toggle="tab">
                                        Kunjungan
                                    </a>
                                </li> -->
                                <li class="" id="rj">
                                    <a href="#tab2" onclick="tab2()" data-toggle="tab">
                                        History
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- TAB TAB PENDAFTARAN -->
                            <div class="tab-pane active" id="tab1">
                                <?php if($noreg == ""): ?>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div style="padding-left:50px" class="form-group">
                                                <label style="margin-top:5px;padding-left:12px" class="col-sm-3">Status Bridging</label>
                                                <div class="col-sm-8">
                                                    <div class="alert alert-warning">Bridging Mandiri</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div style="padding-left:50px" class="form-group">
                                                <label style="margin-top:5px;padding-left:12px" class="col-sm-3">Status Bridging</label>
                                                <div class="col-sm-8">
                                                    <div class="alert alert-info">Bridging Pasien Terdaftar : <b><?= $noreg ?></b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Faskes</label>
                                            <div class="col-md-8">
                                                <!-- <input readonly value="<?= $this->session->userdata('unit'); ?>" id="faskes" name="faskes" class="form-control" type="text"> -->
                                                <select type="text" class="form-control" name="kodeRs" id="kodeRs">
                                                    <option value="<?= $this->session->userdata("kdppk") ?>" selected><?= $this->session->userdata("unit") ?></option>
                                                </select>
                                                <input type="hidden" name="kdProviderPelayanan" id="kdProviderPelayanan" value="<?= $this->session->userdata("kdppk") ?>">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Tanggal</label>
                                            <div class="col-md-8">
                                                <input readonly value="<?= date("Y-m-d") ?>" id="tglDaftar" name="tglDaftar" class="form-control" type="date">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <table border="0" width="100%">
                                                <tr>
                                                    <td width="18%">
                                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Pendaftaran</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="tipe1" name="tipe_daftar" value="1" <?= $noreg ? "checked" : "" ?>>
                                                    </td>
                                                    <td width="10%">
                                                        <label class="col-md-3" for="tipe1" style="margin-top: 5px;padding-left: 12px;">Baru</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="tipe2" name="tipe_daftar" value="2" <?= !$noreg ? "checked" : "" ?>>
                                                    </td>
                                                    <td >
                                                        <label class="col-md-3" for="tipe2" style="margin-top: 5px;padding-left: 12px;">Rujukan</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="18%">
                                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Searching</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="cari1" name="jenis_kartu" value="noka" checked>
                                                    </td>
                                                    <td width="8%">
                                                        <label for="cari1" style="margin-top: 5px;padding-left: 12px;">No. Kartu</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="cari2" name="jenis_kartu" value="nik">
                                                    </td>
                                                    <td width="10%">
                                                        <label for="cari2" style="margin-top: 5px;padding-left: 12px;">NIK</label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="18%">
                                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Cari</label>
                                                    </td>
                                                    <td colspan="4" width="20%">
                                                        <input type="hidden" id="noreg" value="<?= $noreg ?>">
                                                        <input placeholder="cari..." id="carii" name="no_kartu" class="form-control" type="text" value="<?= $noreg ? data_master("tbl_regist", ["noreg" => $noreg])->nobpjs : "" ?>">
                                                        <span class="help-block"></span>
                                                    </td>
                                                    <td width="%"> &nbsp;
                                                        <button id="btn_search" type="button" onclick="search()" class="btn green"><i class="fa fa-search fa-fw"></i>&nbsp; <b>Ambil Data</b></button>
                                                            
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- SEARCH RESULT HIDDEN -->
                                <input type="hidden" name="kdProviderPeserta" id="kdProviderPeserta" value="">
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No Kartu BPJS</label>
                                            <div class="col-md-8">
                                                <input value="<?= $noreg ? data_master("tbl_regist", ["noreg" => $noreg])->nobpjs : "" ?>" id="noKartuPeserta" name="noKartuPeserta" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Nama Peserta</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="namaPeserta" id="namaPeserta" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Status Peserta</label>
                                            <div class="col-md-8">
                                                <input readonly value="" id="status" name="status" class="form-control" maxlength="10" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Tgl Lahir</label>
                                            <div class="col-md-8">
                                                <input readonly value="" id="tglLahir" name="tglLahir" class="form-control" maxlength="100" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>                    
                                </div>   
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Jenis Kelamin</label>
                                            <div class="col-md-8">
                                                <select type="text" class="form-control" name="sex" id="sex">
                                                    <option value="">- Pilih Jenis Kelamin -</option>
                                                    <option value="L">Pria</option>
                                                    <option value="P">Wanita</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">PPK Umum</label>
                                            <div class="col-md-8">
                                                <select type="text" class="form-control" id="ppkUmum" name="ppkUmum">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No HP</label>
                                            <div class="col-md-8">
                                                <input readonly value="" id="noHp" name="noHp" class="form-control"  type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No RM</label>
                                            <div class="col-md-8">
                                                <input readonly value="" id="rekmed" name="rekmed" class="form-control"  type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="col-md-12" style="border-bottom: 2px solid #d4ddec;"><b></b></h3>
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <table border="0" width="100%" >
                                                <tr>
                                                    <td width="18%">
                                                        <label style="margin-top: 5px;padding-left: 12px;">Jenis Kunjungan</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="k_sakit" name="kunjSakit" onchange="change_poli(this.value)" value="1">
                                                    </td>
                                                    <td width="18%">
                                                        <label for="k_sakit" style="margin-top: 5px;padding-left: 12px;">Kunjungan Sakit</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="k_sehat" name="kunjSakit" onchange="change_poli(this.value)" value="0">
                                                    </td>
                                                    <td>
                                                        <label for="k_sehat" style="margin-top: 5px;padding-left: 12px;">Kunjungan Sehat</label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="18%">
                                                        <label style="margin-top: 5px;padding-left: 12px;">Perawatan</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="rajal" name="kdTkp" value="10"></td>
                                                    <td width="18%">
                                                        <label for="rajal_" style="margin-top: 5px;padding-left: 12px;">Rawat Jalan</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="ranap" name="kdTkp" value="20">
                                                    </td>
                                                    <td width="18%">
                                                        <label for="ranap" style="margin-top: 5px;padding-left: 12px;">Rawat Inap</label>
                                                    </td>
                                                    <td width="2%">
                                                        <input style="transform: scale(1.3); margin: -7px 10px 0px 15px;" type="radio" id="propre" name="kdTkp" value="50">
                                                    </td>
                                                    <td width="22%">
                                                        <label for="propre" style="margin-top: 5px;padding-left: 12px;">Promotif Preventif</label>
                                                    </td>
                                                    <td width="56%">
                                                        <label for="tipe2" style="margin-top: 5px;padding-left: 12px;"></label>
                                                    </td>
                                                    
                                                </tr>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No registrasi</label>
                                            <div class="col-md-5">
                                                <input name="noReg" id="noReg" class="form-control" type="text" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Poli Tujuan</label>
                                            <div class="col-md-5">
                                                <select class="form-control" id="kdPoli" name="kdPoli">
                                                </select>	 
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Keluhan Awal</label>
                                            <div class="col-md-5">
                                                <textarea name="keluhan" id="keluhan" class="form-control" type="text"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Tinggi Badan</label>
                                            <div class="col-md-5">
                                                <input name="tinggiBadan" id="tinggiBadan" class="form-control" type="number" value="">
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label" >cm</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group" >
                                            <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Berat Badan</label>
                                            <div class="col-md-5">
                                                <input name="beratBadan" id="beratBadan" class="form-control" type="number" value="">
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label" >kg</label>                                
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group" style="padding-left: 50px;">
                                        <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Lingkar Perut</label>
                                        <div class="col-md-5">
                                            <input name="lingkarPerut" id="lingkarPerut" class="form-control" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label" >cm</label>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-group" >
                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">IMT</label>
                                        <div class="col-md-5">
                                            <input name="imt" id="imt" class="form-control" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label" >KG/M2</label>
                                    </div>
                                    </div>
                                </div>

                                <h3 class="col-md-8" style="color:blue;"><b>Tekanan Darah</b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group" style="padding-left: 50px;">
                                        <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Sistole</label>
                                        <div class="col-md-5">
                                            <input name="sistole" id="sistole" class="form-control" max="250" min="40" placeholder="40-250" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label" >mmHg</label>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Diastole</label>
                                        <div class="col-md-5">
                                            <input name="diastole" id="diastole" class="form-control" max="180" min="30" placeholder="30-180" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label" >mmHg</label>
                                    </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group" style="padding-left: 50px;">
                                        <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Respitori Rate</label>
                                        <div class="col-md-5">
                                            <input name="respRate" id="respRate" class="form-control" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label">/minute</label>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-group" >
                                        <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Heart Rate</label>
                                        <div class="col-md-5">
                                            <input name="heartRate" id="heartRate" class="form-control" max="160" min="30" placeholder="30-160" type="number" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label">bpm</label>
                                    </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="padding-left: 50px;">
                                            <label class="col-md-2" style="color:blue;margin-top: 5px;padding-left: 12px;"><b>Respon web pcare</b></label>
                                            <div class="col-md-9">
                                                <!-- <div style="width:100%;margin:10pxauto;padding:10px;border: 1px solid black;max-height:200px;overflow:auto"> -->
                                                <pre id="bridging_result" style="width:100%;margin:10pxauto;border: 1px solid black;max-height:200px;overflow:auto"></pre>
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">		
                                        <div class="form-actions">
                                            <?php if(0 == 0){?>
                                                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>
                                            <?php }else{ ?>
                                                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Update</b></button>
                                            <?php } ?>

                                            <div class="btn-group">
                                                <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                            </div>
                                                
                                        </div>							
                                    </div>															
                                </div>	
                            </div>
                            <!-- TAB TAB PENDAFTARAN -->
                            
                            <!-- TAB HISTORY -->
                            <div class="tab-pane" id="tab2">
                                <div class="portlet-body">
                                    <div class="table-toolbar">
                                        <div class="row" style="margin:25px 0px 25px 0px;width:auto">
                                            <form>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" value="<?= $this->session->userdata("kdppk") ." - ". $this->session->userdata("unit") ?>" readonly>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="date" class="form-control" id="date_filter" value="<?= date("Y-m-d") ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn-success" onclick="filterData()">Proses</button>
                                                </div>
                                                <div class="col-sm-3">&nbsp;</div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover table-condensed table-scrollable" cellspacing="0" width="100%" id="table-test">
                                            <thead class="page-breadcrumb breadcrumb">
                                                <tr>
                                                    <th class="title-white">No.</th>
                                                    <th class="title-white">No. Kartu</th>
                                                    <th class="title-white">Nama Peserta</th>
                                                    <th class="title-white">Kelamin</th>
                                                    <th class="title-white">Usia</th>
                                                    <th class="title-white">Poli</th>
                                                    <th class="title-white">Sumber</th>
                                                    <th class="title-white">Status</th>
                                                    <th class="title-white">Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- TAB HISTORY -->
                        </div>
                    </div>  
                </div>
            </div>  
        </div>   
    </div>   
</form> 
    <!-- </div> -->

<?php
// $this->load->view('template/footer');  
    $this->load->view('template/footer_tb');
?>

<script>
    <?php if($noreg): ?>
        get_data('<?= $noreg ?>');
    <?php endif; ?>
    
    $(document).ready(function() {
        show_table();
    });

    function filterData(){
        $("#table-test").dataTable().fnDestroy();

        show_table();
    }

    function show_table(){
        var date_filter = $("#date_filter").val();

        $("#table-test").dataTable({
            bServerSide: true,
            bProcessing: true,
            sAjaxSource: `<?= base_url("pcare_api/get_pendaftaran") ?>`,
            aaSorting: [0, 'asc'],
            fnServerParams: function ( aoData ) {
                aoData.push( { "name": "tglDaftar", "value": date_filter ?? "<?= date("Y-m-d") ?>" } );
            },
            aoColumns: [
                {mData: "noUrut"},
                {mData: "noKartuPeserta"},
                {mData: "namaPeserta"},
                {
                    mData: "sex",
                    mRender: (data) => {
                        if (data == "P") return "Perempuan"
                        return "Laki-laki"
                    }
                },
                {
                    mData: "tglLahir",
                    mRender: (data) => {
                        var dob = new Date(data)
                        var diff = Date.now() - dob.getTime();
                        var year = new Date(diff).getUTCFullYear();

                        return Math.abs(year - 1970) + " Th";
                    }
                },
                {mData: "nmPoli"},
                {mData: "sumber"},
                {mData: "status"},
                {
                    mData: (source) => {
                        return {
                            id: source.id,
                            noKartuPeserta: source.noKartuPeserta
                        }
                    },
                    mRender: (data) => {
                        return /*html*/`
                        <button type="button" class="btn btn-danger" onclick="delete_data(${data.id}, '${data.noKartuPeserta}')">Hapus</button>
                        `
                    },
                    sClass: "text-center"
                },
            ]
        });
    }

    function search(){
        var v_noreg         = $("#noreg").val();
        var v_cari          = $('#carii').val();
        var v_jenis_kartu   = $('[name="jenis_kartu"]').val();

        $.ajax({				
            url         : `<?php echo site_url('api/pcare/peserta/')?>?jenis_kartu=${v_jenis_kartu}&no_kartu=${v_cari}`,
            type        : "GET",
            dataType    : "JSON",
            beforeSend: function(){
                $("#bridging_result").append("<p class='text-warning'>[GET]&emsp;&emsp;Sedang mengambil data nomor kartu : <b>"+ v_cari +"</b></p>");
            },
            success:function(data){
                if(data == null){
                    $("#bridging_result").append("<p class='text-danger'>[GET]&emsp;&emsp;Gagal mengambil data bridging : <b>data tidak ditemukan</b></p>");
                    $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
                } else {
                    $("#kdProviderPeserta").val(data.kdProviderPst);
                    $("#namaPeserta").val(data.nama);
                    $("#noKartuPeserta").val(data.noKartu);
                    $("#tglLahir").val(data.tglLahir);
                    $("#sex").val(data.sex).change();
                    $("#noHp").val(data.noHP);
                    $("#kdPoli").val("");
                    $("#bridging_result").append("<p class='text-success'>[GET]&emsp;&emsp;Berhasil mengambil data bridging : <b>Pendaftaran</b></p>");
                    $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
                }

                if(v_noreg != ""){
                    get_data(v_noreg);
                }
            },
            error:function(jqXHR){
                swal({
                    title: "Ambil Data",
                    html: "<p>Gagal mengambil data</p>"+ jqXHR.responseJSON.message,
                    type: "error",
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "red"
                });  

                $("#bridging_result").append("<p class='text-danger'>[GET]&emsp;&emsp;Gagal mengambil data bridging : <b>"+ jqXHR.responseJSON.message +"</b></p>");
                $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
                $("#bridging_result").append("<p class='text-danger'>[GET]&emsp;&emsp;Gagal mengambil data bridging</p>");
                $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
            }
        });
    }

    function get_data(noreg){
        $.ajax({
            url: "/pcare/get_rekam_medis/"+ noreg,
            type: "GET",
            dataType: "JSON",
            success: (res) => {
                if(res.status_rekmed == true){
                    $("#rekmed").val(res.data_rekmed.rekmed);
                    $("#noReg").val(res.data_rekmed.noreg);
                    if(res.status_pcare == false){
                        switch(res.data_rekmed.kodepos){
                            case "PUMUM" : 
                                $("#kdPoli").val("001").change();
                                break;
                            case "PGIGI" : 
                                $("#kdPoli").val("002").change();
                                break;
                            case "BIDAN" : 
                                $("#kdPoli").val("003").change();
                                break;
                            case "LABOR" : 
                                $("#kdPoli").val("004").change();
                                break;
                            default : 
                                console.error("Failed get POLI from medical record data");
                                break;
                        }

                        $("#keluhan").val(res.data_rekmed.keluhanawal);
                        $("#tinggiBadan").val(res.data_rekmed.tinggibadan);
                        $("#beratBadan").val(res.data_rekmed.beratbadan.split(".00").join(""));
                        imt(res.data_rekmed.tinggibadan, res.data_rekmed.beratbadan.split(".00").join(""));
                    }
                    
                    $("#bridging_result").append("<p class='text-success'>[GET]&emsp;&emsp;Berhasil mengambil data rekam medis : <b>"+ noreg +"</b></p>");
                    $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
                }

                if(res.status_pcare == true){
                    get_pendaftaran_pcare('<?= $noreg ?>');
                    // $("#pcare_kunjungan").attr("style", "display:show");
                    $("#status").val(res.data_pcare.status);

                    switch(res.data_pcare.kunjSakit){
                        case "1" : 
                            $("#k_sakit").prop("checked", true);
                            break;
                        case "0" : 
                            $("#k_sehat").prop("checked", true);
                            break;
                    }

                    switch(res.data_pcare.kdTkp){
                        case "10" : 
                            $("#uniform-rajal #rajal").prop("checked", true);
                            break;
                        case "20" : 
                            $("#uniform-ranap #ranap").prop("checked", true);
                            break;
                        case "50" : 
                            $("#uniform-propre #propre").prop("checked", true);
                            break;
                    }

                    switch(res.data_pcare.kdPoli){
                        case "001" : 
                            $("#kdPoli").val("001").change();
                            break;
                        case "002" : 
                            $("#kdPoli").val("002").change();
                            break;
                        case "003" : 
                            $("#kdPoli").val("003").change();
                            break;
                        case "004" : 
                            $("#kdPoli").val("004").change();
                            break;
                        default : 
                            console.error("Failed get POLI from medical record data");
                            break;
                    }

                    $("#keluhan").val(res.data_pcare.keluhan);
                    $("#tinggiBadan").val(res.data_pcare.tinggiBadan);
                    $("#beratBadan").val(res.data_pcare.beratBadan);
                    $("#imt").val(res.data_pcare.IMT);
                    $("#sistole").val(res.data_pcare.sistole);
                    $("#diastole").val(res.data_pcare.diastole);
                    $("#respRate").val(res.data_pcare.respRate);
                    $("#heartRate").val(res.data_pcare.heartRate);
                    $("#lingkarPerut").val(res.data_pcare.lingkarPerut);

                    $("#bridging_result").append("<p class='text-info'>[INFO]&emsp;Status bridging : <b>Update Data Pendaftaran</b>");
                    $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);

                    $("#bridging_result").append("<p class='text-success'>[GET]&emsp;&emsp;Berhasil mengambil data : <b>Pendaftaran</b></p>");
                    $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
                }
            },
            error: (jqHXR) => {
                
            }
        });
    }
    
    function get_pendaftaran_pcare(param){
        $.ajax({
            url: "/pcare/pcare_get_data_pas/"+ param,
            type: "GET",
            dataType: "JSON",
            success: (res) => {
                if(res.status == "success"){
                    $("#icd_result").html("");
                    $.each(res.data_diag, function(i, k){
                        $("#icd_result").append(""+
                            "<input class='form-control' type='text' name='kdDiag[]' id='kdDiag"+ i +"' value='"+ k.icd_code +"' readonly>"+
                            "<input class='form-control' type='text' name='nmDiag[]' id='nmDiag"+ i +"' value='"+ k.icd_name +"' readonly>"+
                        "");
                    });

                    $("#keluhan").val(res.data_pas.keluhan_awal);
                    $("#anamnesa").val(res.data_pas.keluhan_awal);
                    $('#beratBadan').val(res.data_pas.berat_badan);
                    $('#tinggiBadan').val(res.data_pas.tinggi_badan);
                    imt_trigger(res.data_pas.tinggi_badan, res.data_pas.berat_badan);
                } else 
                if(res.status == "empty"){
                    error_alert("PCare Diagnosa", res.message);
                    $("#icd_result").html(""+
                        "<input class='form-control' type='text' value='"+ res.message +"' readonly>"+
                    "");
                } else {
                    error_alert("PCare Diagnosa", "Undefined status result");
                    $("#icd_result").html(""+
                        "<input class='form-control' type='text' value='Undefined status result' readonly>"+
                    "");
                }
            },
            error: (jqXHR) => {
                //
            }
        });

        $.ajax({
            url: "/api/pcare/get_detail_pendaftaran/"+ param,
            type: "GET",
            dataType: "JSON",
            success: (res) => {
                console.log(res);
                $("#kodeRs").val(res.kodeRs);
                $("#tglDaftar").val(res.tglDaftar);
                $("#noReg").val(res.noReg);
                $("#noKartu").val(res.noKartuPeserta);
                $("#namaPeserta").val(res.namaPeserta);
                $("#status").val(res.status);
                $("#ppkUmum").val(res.kodeRs);
                $("#kdProviderPelayanan").val(res.kdProviderPelayanan);
                $("#bridging_result_kunjungan").append("<p class='text-success'>[GET]&emsp;&emsp;Berhasil mengambil data bridging : <b>Kunjungan</b></p>");
            },
            error: (jqXHR) => {
                console.error(jqXHR.responseJSON);
            }
        });
    }

    function save(){
        var post_form = $('#form_bridging').serialize();

        $.ajax({
            url         : "/api/pcare/create_pendaftaran",
            data        : post_form,
            type        : "POST",
            dataType    : "JSON",
            beforeSend  : function(){
                $("#bridging_result").append("<p class='text-warning'>[POST]&emsp;Sedang mengirim data</p>");
                $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
            },
            success     : function(res){
                console.log(res);
                $("#bridging_result").append("<p class='text-success'>[POST]&emsp;Berhasil mengirim data</p>");
                $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
            },
            error       : function (jqXHR, textStatus, errorThrown){
                var res = jqXHR.responseJSON;
                $("#bridging_result").append("<p class='text-danger'>[POST]&emsp;Gagal mengirim data : "+ jqXHR.responseJSON.message +"</p>");
                $('#bridging_result').animate({scrollTop: $('#bridging_result').height()}, 1000);
            }
        });
    }

    function save_pcare(){
        var post_form = $("#pcare_form").serialize();

        console.log(post_form);

        $.ajax({
            url: "/api/pcare/create_kunjungan/",
            data: post_form,
            type: "POST",
            dataType: "JSON",
            success: (res) => {
                console.log(res);
            },
            error: (jqXHR) => {
                console.error(jqXHR.responseJSON);
            }
        });
    }

    function delete_data(id, nocard){
        $.ajax({
            url     : "/api/pcare/delete_pendaftaran",
            data    : {id: id, no_kartu: nocard.toString()},
            type    : "POST",
            dataType: "JSON",
            success : function(res){
                console.log(res);
            },
            error   : function(jqXHR, textStatus, errorThrown){
                console.error(jqXHR.responseJSON);
            }
        });
    }

    function imt(vberat, vtinggi){
        if(vberat== null){
            var vberat=0;
        }else{
            var vberat=vberat;
        }

        if(vtinggi== null){
            var vtinggi=0;
        }else{
            var vtinggi=vtinggi;
        }

        // alert(vberat2);
        
        var tbb   = eval(vtinggi)/100;
        var bmi   = vberat/(tbb*tbb);   

        if(bmi>0){
            bmi2=Math.ceil(bmi);
        }else{
            bmi2=0;
        }
        
        if(bmi>1 && bmi<18.5){
            bmi_res='Under Weight';
        }else if(bmi>18.5 && bmi<=25){
            bmi_res='Normal Weight';
        }else if(bmi>25 && bmi<=30){
            bmi_res='Over Weight';
        }else if(bmi>30 && bmi<1000){
            bmi_res='Obese';
        }else{
            bmi_res='';
        }

        return $('[name="imt"]').val(bmi2);
    }

    function imt_trigger(vtinggi, vberat){
        if(vberat== null){
            var vberat=0;
        }else{
            var vberat=vberat;
        }

        if(vtinggi== null){
            var vtinggi=0;
        }else{
            var vtinggi=vtinggi;
        }

        // alert(vberat2);
        
        var tbb   = eval(vtinggi)/100;
        var bmi   = vberat/(tbb*tbb);   

        if(bmi>0){
            bmi2=Math.ceil(bmi);
        }else{
            bmi2=0;
        }
        
        if(bmi>1 && bmi<18.5){
            bmi_res='Under Weight';
        }else if(bmi>18.5 && bmi<=25){
            bmi_res='Normal Weight';
        }else if(bmi>25 && bmi<=30){
            bmi_res='Over Weight';
        }else if(bmi>30 && bmi<1000){
            bmi_res='Obese';
        }else{
            bmi_res='';
        }

        $('[name="imt"]').val(bmi2);
    }

    $('#beratBadan, #tinggiBadan').on("keyup", function(){
        var vberat    = $('#beratBadan').val();
        var vtinggi   = $('#tinggiBadan').val();

        if(vberat== null){
            var vberat=0;
        }else{
            var vberat=vberat;
        }

        if(vtinggi== null){
            var vtinggi=0;
        }else{
            var vtinggi=vtinggi;
        }

        // alert(vberat2);
        
        var tbb   = eval(vtinggi)/100;
        var bmi   = vberat/(tbb*tbb);   

        if(bmi>0){
            bmi2=Math.ceil(bmi);
        }else{
            bmi2=0;
        }
        
        if(bmi>1 && bmi<18.5){
            bmi_res='Under Weight';
        }else if(bmi>18.5 && bmi<=25){
            bmi_res='Normal Weight';
        }else if(bmi>25 && bmi<=30){
            bmi_res='Over Weight';
        }else if(bmi>30 && bmi<1000){
            bmi_res='Obese';
        }else{
            bmi_res='';
        }

        $('[name="imt"]').val(bmi2);
        // $('[name="imt"]').val(bmi_res);
        
        // console.log(bmi2 +" - "+ bmi_res);
    });

    $("#sistole").change(function() {
        var max = parseInt($(this).attr('max'));
        var min = parseInt($(this).attr('min'));
        if ($(this).val() > max)
        {
            $(this).val(max);
        }
        else if ($(this).val() < min)
        {
            $(this).val(min);
        }       
    }); 

    $("#diastole").change(function() {
        var max = parseInt($(this).attr('max'));
        var min = parseInt($(this).attr('min'));
        if ($(this).val() > max)
        {
            $(this).val(max);
        }
        else if ($(this).val() < min)
        {
            $(this).val(min);
        }       
    }); 

    $("#heartRate").change(function() {
        var max = parseInt($(this).attr('max'));
        var min = parseInt($(this).attr('min'));
        if ($(this).val() > max)
        {
            $(this).val(max);
        }
        else if ($(this).val() < min)
        {
            $(this).val(min);
        }       
    }); 

    function back(){
        var thiloc = window.location;
        window.close(thiloc);
    }

    function tab2(){
        tablepc = $('#tablepc').DataTable({ 
            destroy: true,
            "processing": true,
            "responsive":true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pendaftaranVRS/list_pcare/1')?>",
                "type": "POST"
            },
            "scrollCollapse": false,
            "paging":true,
            "oLanguage": {
                "sEmptyTable"   : "<div class='text-center'>Data Kosong</div>",
                "sInfoEmpty"    : "",
                "sInfoFiltered" : " - Dipilih dari _TOTAL_ data",
                "sSearch"       : "Pencarian Data : ",
                "sInfo"         : " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu"   : "_MENU_ Baris",
                "sZeroRecords"  : "<div class='text-center'>Tida ada data</div>",
                "oPaginate": {
                "sPrevious"     : "Sebelumnya",
                "sNext"         : "Berikutnya"
                }
            },		
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"]
            ],
            "columnDefs": [
                { 
                    "targets": [ -1 ], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });

        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

        $("textarea").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

        $("select").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        // tablepc.clear();
    }

    function change_poli(status){
        $.ajax({
            url         : "/pcare/poli_by_status/"+ status,
            type        : "GET",
            dataType    : "JSON",
            beforeSend  : function(){
                $("#kdPoli").empty();
            },
            success     : function(res){
                $.each(res, function(k, v){
                   $("#kdPoli").append("<option value='"+ v.kdPoli +"'>"+ v.nmPoli +"</option>");
                });
            },
            error       : function(jqXHR, textStatus, errorThrown){
                console.error("Error : Failed get 'Poli Tujuan'");
            }
        })
    }

    function error_alert(message){
        return swal({
            title: "POLIKLINIK",
            html: message,
            type: "error",
            confirmButtonText: "Tutup", 
            confirmButtonColor: "red"
        });
    }

</script>