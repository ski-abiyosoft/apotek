<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    
    date_default_timezone_set("Asia/Jakarta");

    $fill_perawat   = $this->session->flashdata("isi_perawat");

    if(isset($fill_perawat)){
        echo "<script>alert('$fill_perawat')</script>";
    }

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
                            <a class="title-white" href="<?php echo base_url();?>dashboard">
                                Awal
                            </a>
                            <i style="color:white;" class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a class="title-white" href="<?php echo base_url('PendaftaranVRS');?>">
                                Pendaftaran VRS
                                                        </a>
                            <i style="color:white;" class="fa fa-angle-right"></i>
                        </li>
                        <li>							
                            <a class="title-white" href="">
                                Briging Pendaftaran Pasien
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <form id="form_periksa_perawat" class="form-horizontal" method="post">
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
                                        <li class="active" id="reservasi">
                                            <a href="#tab1" data-toggle="tab">
                                                Pendaftaran
                                            </a>
                                        </li>
                                        <li class="" id="rj">
                                            <a href="#tab2" onclick="tab2()" data-toggle="tab">
                                                History
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Faskes</label>
                                                    <div class="col-md-8">
                                                        <input readonly value="<?= $this->session->userdata('unit'); ?>" id="faskes" name="faskes" class="form-control" type="text">
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
                                                        <input readonly value="<?= date('Y-m-d', strtotime($data_pas->tglmasuk)); ?>" id="noreg_per" name="noreg_per" class="form-control"type="date">
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
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="tipe1" name="tipe_daftar" value="1" <?php 
                                                                if(!empty($data_pas->namapas)){ 
                                                                    if($data_pas->namapas == 1){ ?> checked="checked" <?php 
                                                                    } else{

                                                                    ?> checked="checked" <?php 
                                                                    }
                                                                } ?>></td>
                                                            <td width="10%">
                                                                <label class="col-md-3" for="tipe1" style="margin-top: 5px;padding-left: 12px;">Baru</label>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="tipe2" name="tipe_daftar" value="2" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == 2){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
                                                            </td>
                                                            <td >
                                                                <label class="col-md-3" for="tipe2" style="margin-top: 5px;padding-left: 12px;">Rujukan</label>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td width="18%">
                                                                <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Cari</label>
                                                            </td>
                                                            <td colspan="4" width="20%">
                                                                <input readonly placeholder="cari..." id="carii" name="carii" class="form-control" type="text">
                                                                <span class="help-block"></span>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="cari1" name="cari_" value="2" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == 2){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
                                                            </td>
                                                            <td width="8%">
                                                                <label for="cari1" style="margin-top: 5px;padding-left: 12px;">No. KK</label>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="cari2" name="cari_" value="2" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == $data_pas->namapas){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
                                                            </td>
                                                            <td width="10%">
                                                                <label for="cari2" style="margin-top: 5px;padding-left: 12px;">NIK</label>
                                                            </td>
                                                            <td >
                                                                <label class="col-md-3" for="tipe2" style="margin-top: 5px;padding-left: 12px;"></label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>                    
                                        </div>   
                                    
                                    
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No Kartu BPJS</label>
                                                    <div class="col-md-8">
                                                        <input value="<?= $data_pas->nobpjs ?>" id="nobpjss" name="nobpjss" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Nama Peserta</label>
                                                    <div class="col-md-8">
                                                        <input value="<?= $data_pas->namapas ?>" id="namapas" name="namapas" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Status Peserta</label>
                                                    <div class="col-md-8">
                                                        <input readonly value="Peserta" id="stat_p" name="stat_p" class="form-control" maxlength="10" type="text">
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
                                                        <input readonly value="<?= date('Y-m-d', strtotime($data_pas->tgllahir)); ?>" id="tgllahir" name="tgllahir" class="form-control" maxlength="100" type="date">
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
                                                        <input 
                                                        <?php 
                                                        if($data_pas->jkel=='W' || $data_pas->jkel=='w'){
                                                            ?> value="Wanita"<?php
                                                        }else{
                                                            ?> value="Pria"<?php 
                                                        }  ?> id="jkel" name="jkel" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">PPK Umum</label>
                                                    <div class="col-md-8">
                                                        <select type="text" class="form-control" id="ppk" name="ppk" readonly>
                                                            <option value="<?= $data_pas->kodepos ?>"><?= data_master("tbl_namapos", array("kodepos" => $data_pas->kodepos))->namapost ?></option>
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
                                                        <input readonly value="<?= $data_pas->handphone ?>" id="nohp" name="nohp" class="form-control"  type="text">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">No RM</label>
                                                    <div class="col-md-8">
                                                        <input readonly value="<?= $data_pas->rekmed ?>" id="nohp" name="nohp" class="form-control"  type="text">
                                                        
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
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="k_sakit" name="tipe_kunj" value="1" <?php 
                                                                if(!empty($data_pas->namapas)){ 
                                                                    if($data_pas->namapas == $data_pas->namapas){ ?> checked="checked" <?php 
                                                                    } else{

                                                                    ?> checked="checked" <?php 
                                                                    }
                                                                } ?>></td>
                                                            <td width="18%">
                                                                <label for="k_sakit" style="margin-top: 5px;padding-left: 12px;">Kunjungan Sakit</label>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="k_sehat" name="tipe_kunj" value="2" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == 2){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
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
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="rajal_" name="tipe_rawat" value="1" <?php 
                                                                if(!empty($data_pas->namapas)){ 
                                                                    if($data_pas->namapas == 1){ ?> checked="checked" <?php 
                                                                    } else{

                                                                    ?> checked="checked" <?php 
                                                                    }
                                                                } ?>></td>
                                                            <td width="18%">
                                                                <label for="rajal_" style="margin-top: 5px;padding-left: 12px;">Rawat Jalan</label>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="ranap" name="tipe_rawat" value="2" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == 2){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
                                                            </td>
                                                            <td width="18%">
                                                                <label for="ranap" style="margin-top: 5px;padding-left: 12px;">Rawat Inap</label>
                                                            </td>
                                                            <td width="2%">
                                                                <input style="transform: scale(1.3); margin: -7px 10px 0px -15px;" type="radio" id="propre" name="tipe_rawat" value="3" <?php 
                                                                if(!empty($data_pas->namapas)){
                                                                    if($data_pas->namapas == 2){
                                                                        ?> checked="checked" <?php 
                                                                    }
                                                                } ?>>
                                                            </td>
                                                            <td width="18%">
                                                                <label for="propre" style="margin-top: 5px;padding-left: 12px;">Promotif Preventif</label>
                                                            </td>
                                                            <td width="60%">
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
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Poli Tujuan</label>
                                                    <div class="col-md-5">
                                                        <select class="form-control select2_el_poli" id="poli_tujuan" name="poli_tujuan" onchange="update(); cekruang()">
                                                            <?php if($data_pas->kodepos){ 
                                                                $vpoli = data_master('tbl_namapos', array('kodepos' => $data_pas->kodepos));
                                                            ?>
                                                            <option value="<?= $data_pas->kodepos;?>"><?= $data_pas->kodepos.' | '.$vpoli->namapost;?></option>
                                                            <?php } ?>
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
                                                        <input name="keluhan" value="<?= $ttv->nafas ?>" placeholder="0" class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group" style="padding-left: 50px;">
                                                    <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Tinggi Badan</label>
                                                    <div class="col-md-5">
                                                        <input name="tb" value="<?= $ttv->oksigen ?>" placeholder="0" class="form-control" type="number">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <label class="control-label" style="">cm</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group" >
                                                    <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Berat Badan</label>
                                                    <div class="col-md-5">
                                                        <input name="bb" value="<?= $ttv->bmi ?>" placeholder="bb" class="form-control" type="number">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <label class="control-label" style="">kg</label>                                
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group" style="padding-left: 50px;">
                                                <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Lingkar Perut</label>
                                                <div class="col-md-5">
                                                    <input name="lp" value="<?= $ttv->suhu ?>" placeholder="0" class="form-control" type="number">
                                                    <span class="help-block"></span>
                                                </div>
                                                <label class="control-label" style="">cm</label>
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                            <div class="form-group" >
                                                <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">IMT</label>
                                                <div class="col-md-5">
                                                    <input name="imt" value="<?= $ttv->bmiresult ?>" placeholder="0" class="form-control" type="number" >
                                                    <span class="help-block"></span>
                                                </div>
                                                <label class="control-label" style="">KG/M2</label>
                                            </div>
                                            </div>
                                        </div>

                                        <h3 class="col-md-8" style="color:blue;"><b>Tekanan Darah</b></h3>

                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group" style="padding-left: 50px;">
                                                <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Sistole</label>
                                                <div class="col-md-5">
                                                    <input name="sistole" value="<?= $ttv->suhu ?>" placeholder="0" class="form-control" type="number">
                                                    <span class="help-block"></span>
                                                </div>
                                                <label class="control-label" style="">mmHg</label>
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Diastole</label>
                                                <div class="col-md-5">
                                                    <input name="diastole" value="<?= $ttv->bmiresult ?>" placeholder="0" class="form-control" type="number" >
                                                    <span class="help-block"></span>
                                                </div>
                                                <label class="control-label" style="">mmHg</label>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group" style="padding-left: 50px;">
                                                <label class="col-md-4" style="margin-top: 5px;padding-left: 12px;">Respitori Rate</label>
                                                <div class="col-md-5">
                                                    <input name="resr" value="<?= $ttv->suhu ?>" placeholder="0" class="form-control" type="number">
                                                    <span class="help-block"></span>
                                                </div>
                                                <label class="control-label">/minute</label>
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                            <div class="form-group" >
                                                <label class="col-md-3" style="margin-top: 5px;padding-left: 12px;">Heart Rate</label>
                                                <div class="col-md-5">
                                                    <input name="hr" value="<?= $ttv->bmiresult ?>" placeholder="0" class="form-control" type="number" >
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
                                                    <div class="col-md-6"><br>
                                                        <textarea style="border: 2px solid #8faad7;" name="simpulfisik"  class="form-control" cols="10" rows="5"><?= $ttv->pfisik ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">		
                                                <div class="form-actions">
                                                    <?php if($ttv->nadi == 0){?>
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
                                    
                                    <div class="tab-pane" id="tab2">
                                        <div class="portlet-body">
                                            <div class="table-toolbar">
                                                <div class="btn-group"> 
                                                    <?php 
                                                    $cek =  $this->session->userdata('user_level'); 
                                                    if($cek==0){?> 
                                                    <?php }else{ ?>

                                                            <a href="<?php echo base_url()?>PendaftaranVRS/entri_rj" class="btn btn-success">
                                                                <i class="fa fa-plus"></i> Daftarkan Pasien
                                                            </a>

                                                    <?php } ?>
                                                </div>
                                                <div class="btn-group pull-right" id="filter">
                                                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right">
                                                            <li>
                                                                <a data-toggle="modal" href="#lupperiode">Filter Data</a>			
                                                            </li>			
                                                        </ul>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="tablepc" class="table table-striped table-bordered table-hover table-condensed table-scrollable" cellspacing="0" width="100%">

                                                    <thead class="page-breadcrumb breadcrumb">
                                                        <tr>
                                                            <th class="title-white text-center" width="5%">No.</th>
                                                            <th class="title-white text-center" width="10%">No Kartu</th>
                                                            <th class="title-white text-center" width="25">Nama Peserta</th>
                                                            <th class="title-white text-center" width="10%">Jenis Kelamin</th>
                                                            <th class="title-white text-center" width="5%">Usia</th>
                                                            <th class="title-white text-center" width="10%">Poli</th>
                                                            <th class="title-white text-center" width="10%">Sumber</th>
                                                            <th class="title-white text-center" width="10%">Status</th>
                                                            <th class="title-white text-center" width="15%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
    $(document).ready(function() {

        var tgllahire   = '<?= $data_pas->tgllahir?>'.substring(0, 10);
        var tglmasuk    = '<?= $data_pas->tglmasuk?>'.substring(0, 10);
        var doaa         = 1;
        var birthDate   = new Date(tgllahire);
        var usia        = hitung_usia(birthDate);
        if(doaa==1){
            $('[name="nadi"]').prop("readonly", true);
            $('[name="nafas"]').prop("readonly", true);
            $('[name="oksi"]').prop("readonly", true);
            $('[name="suhu"]').prop("readonly", true);
            $('[name="tekanan"]').prop("readonly", true);
            $('[name="tekanan1"]').prop("readonly", true);
        }
        $('#umur').val(usia);
        $('#tgl_l_per').val(tgllahire);
        $('#tgl_per').val(tglmasuk);

    });

    $('#berat, #tinggi').keyup(function(){
        
        var vberat    = $('#berat').val();
        var vtinggi   = $('#tinggi').val();

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

        $('[name="bmi"]').val(bmi2);
        $('[name="bmi_result"]').val(bmi_res);
        
        
    }); 

    function cekdoa()
    {
        
        var cekdoa2 = $('#doa').is(':checked');
        if(cekdoa2===true){            
            $('[name="nadi"]').prop("readonly", true);
            $('[name="nafas"]').prop("readonly", true);
            $('[name="oksi"]').prop("readonly", true);
            $('[name="suhu"]').prop("readonly", true);
            $('[name="tekanan"]').prop("readonly", true);
            $('[name="tekanan1"]').prop("readonly", true);
            $('[name="nadi"]').val(0);
            $('[name="nafas"]').val(0);
            $('[name="oksi"]').val(0);
            $('[name="suhu"]').val(0);
            $('[name="tekanan"]').val(0);
            $('[name="tekanan1"]').val(0);
            $('[name="doa_hidden"]').val(1);

        }else{
            $('[name="nadi"]').prop("readonly", false);
            $('[name="nafas"]').prop("readonly", false);
            $('[name="oksi"]').prop("readonly", false);
            $('[name="suhu"]').prop("readonly", false);
            $('[name="tekanan"]').prop("readonly", false);
            $('[name="tekanan1"]').prop("readonly", false);
            $('[name="nadi"]').val('');
            $('[name="nafas"]').val('');
            $('[name="oksi"]').val('');
            $('[name="suhu"]').val('');
            $('[name="tekanan"]').val('');
            $('[name="tekanan1"]').val('');
            $('[name="doa_hidden"]').val(0);
        }
    }

    function back()
    {
        var thiloc = window.location;
        window.close(thiloc);
    }

    function save()
    {	    
        return;
        var v_nadi          = $('[name="nadi"]').val();
        var v_berat         = $('[name="berat"]').val();
        var v_nafas         = $('[name="nafas"]').val();
        var v_tinggi        = $('[name="tinggi"]').val();
        var v_oksi          = $('[name="oksi"]').val();
        var v_bmi           = $('[name="bmi"]').val();
        var v_bmi_result    = $('[name="bmi_result"]').val();
        var v_suhu          = $('[name="suhu"]').val();
        var v_tekanan       = $('[name="tekanan"]').val();
        var v_tekanan1      = $('[name="tekanan1"]').val();
        var v_doa           = $('[name="doa"]').is(':checked');
        var v_simpulfisik   = $('[name="simpulfisik"]').val();
        var namapass        = $('[name="nampas_per"]').val();
        var doa_hidden      = $('[name="doa_hidden"]').val();


        if (v_nadi=='' || v_nadi== null){
        swal({
                title: "NADI",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_berat=='' || v_berat== null){
        swal({
                title: "BERAT BADAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_nafas=='' || v_nafas== null){
        swal({
                title: "PERNAFASAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_tinggi=='' || v_tinggi== null){
        swal({
                title: "TINGGI BADAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_oksi=='' || v_oksi== null){
        swal({
                title: "SPO2",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_bmi=='' || v_bmi== null){
        swal({
                title: "BMI",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_bmi_result=='' || v_bmi_result== null){
        swal({
                title: "BMI RESULT",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_suhu=='' || v_suhu== null){
        swal({
                title: "SUHU",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_tekanan=='' || v_tekanan== null){
        swal({
                title: "TEKANAN DARAH ATAS",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_tekanan1=='' || v_tekanan1== null){
        swal({
                title: "TEKANAN DARAH BAWAH",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_simpulfisik=='' || v_simpulfisik== null){
        swal({
                title: "KESIMPULAN FISIK",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        
                
        $.ajax({				
            url:"<?php echo site_url('poliklinik/ajax_add_per/1')?>",				
            data:$('#form_periksa_perawat').serialize(),				
            type:'POST',
            dataType : "json",
            success:function(data){ 
            // data1 = JSON.parse(data);
            // alert(data1.status);
                if(data.status=='1'){   
                    swal({
                        title: "DATA Pendaftaran Pasien",
                        html: 
                            "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil di Perbarui...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                            location.reload();
                    });	
                }else{
                    swal({
                        title: "DATA Pendaftaran Pasien",
                        html: 
                            "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil Tersimpan...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                            location.reload();
                    });	
                }			
                                    
        
            },
            error:function(data){
                swal('EMR','Data gagal disimpan ...','');   	
            }
        });
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

</script>

</body>
</html> 
