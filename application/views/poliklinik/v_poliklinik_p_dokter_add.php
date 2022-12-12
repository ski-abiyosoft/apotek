<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    
    date_default_timezone_set("Asia/Jakarta");

    // if(!isset($_GET["noreg"]) && !isset($_GET["rekmed"])){
    //     echo "<script>location.href='/poliklinik'</script>";
    // } else {
    //     if($_GET["noreg"] == "" || $_GET["rekmed"] == ""){
    //         echo "<script>location.href='/poliklinik'</script>";
    //     }
    // }
?>

<!-- <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet"> -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<style>
    .dropdownitem {cursor:pointer}
    .activerbtn {background:green !important; color:#fff !important}
    .racikanFilter {display:none}
    .showracikan {display:block}
    .modal {
        text-align: center;
        padding: 0!important;
    }
    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px; /* Adjusts for spacing */
    }
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
    .elab-form, .emed-form, .erad-form {display:none}
    @media screen and (max-width:720px){
        .modal-content {width:100%;margin:auto}
    }
</style>

<style>
    .loadingdr {display:none;position:fixed;width:100%;height:100%; top:0;left:0;right:0;bottom:0;background-color:rgba(255,255,255,0.8);z-index:999;display:none}
    .loadingdr-text {position:absolute;top:50%;left:50%;font-size:50px;color:black;transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%)}
</style>

<div class="loadingdr">
    <div class="loadingdr-text">Loading...</div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;- 
            <span class="title-web">e-HMS <small>Poliklinik</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url('poliklinik');?>">Poliklinik</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">Pemeriksaan Dokter</a>
            </li>
        </ul>
    </div>
</div>

<form id="form_periksa_dokter" class="form-horizontal" method="post">

    <div id="suketsakit" class="modal fade" role="dialog">
        <div class="modal-dialog modal-centered modal-md">

            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <center>
                        <h4 style="color:green;margin:auto auto 20px auto"><b>Surat Keterangan Sakit</b></h4>
                    </center>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="ijindari">Dari Tanggal</label>
                        <div class="col-sm-7 input-group">
                            <input type="date" onchange="cek(this.value)" class="form-control" id="ijindari" name="ijindari" value="<?= isset($ttv->ijindari)? $ttv->ijindari : date("Y-m-d") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="ijinsampai">Sampai Tanggal</label>
                        <div class="col-sm-7 input-group">
                            <input type="date" onchange="cek(this.value)" class="form-control" id="ijinsampai" name="ijinsampai" value="<?= isset($ttv->ijinsampai)? $ttv->ijinsampai : date("Y-m-d") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="sakitselama">Izin Sakit Selama</label>
                        <div class="col-sm-7 input-group">
                            <input type="number" class="form-control" id="sakitselama" name="sakitselama" value="<?= isset($ttv->ijinsakit)? $ttv->ijinsakit : "0" ?>">
                            <span class="input-group-addon">hari</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="signature-pad">
                            <table border="1" width="100%">
                                <tr>
                                    <td align="center"><label class="control-label ">&nbsp;<b> Tanda Tangan Digital</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <canvas width="570" height="200" ></canvas>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <button type="button" data-action="clear" class="btn btn-sm red pull-right">
                                        <i class="fa fa-trash-o"></i> Clear</button>
                                    </td>
                                </tr>
                            </table>
                            
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">&nbsp;</div>
                        <div class="col-sm-7 input-group">
                            <span class="suket_success"></span>
                            <!-- <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>&emsp; -->
                            <button id="btnsimpan" type="button" onclick="save_suketsakit()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>&emsp;

                            <button type="button" class="btn btn-warning" id="print_suketsakit" <?= isset($ttv->ijinsakit)? "" : "disabled" ?> onclick="_urlcetak();"><i class="fa fa-print"></i>&nbsp; Print</button>&emsp;

                            <button type="button"  class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;<b>Batal<b></button>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="suketsehat" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <h4 style="color:green;margin:auto auto 20px auto"><b>Surat Keterangan Dokter</b></h4>
                    </center>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="butawarna">Buta Warna</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="butawarna" id="butawarna" value="<?= isset($ttv->butawarna)? $ttv->butawarna : "" ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="sehat">Hasil Pemeriksaan</label>
                        <div class="col-sm-7">
                            <select class="form-control" name="sehat" id="sehat">
                                <option value="1" <?= isset($ttv->sehat)? ($ttv->sehat == "1")? "selected" : "" : "" ?>>Sehat</option>
                                <option value="0" <?= isset($ttv->sehat)? ($ttv->sehat == "0")? "selected" : "" : "" ?>>Tidak Sehat</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="ketsehat">&nbsp;</label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="ketsehat" id="ketsehat" rows="3" placeholder="Keterangan sehat" style="resize:none !important"><?= isset($ttv->ketsehat)? $ttv->ketsehat : "" ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="ketsehatuntuk">Untuk Keperluan</label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="ketsehatuntuk" id="ketsehatuntuk" rows="3" style="resize:none !important"><?= isset($ttv->ketsehatuntuk)? $ttv->ketsehatuntuk : "" ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="signature-pad2">
                            <table border="1" width="100%">
                                <tr>
                                    <td align="center"><label class="control-label ">&nbsp;<b> Tanda Tangan Digital</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <canvas width="570" height="200" ></canvas>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <button type="button" data-action="clear2" class="btn btn-sm red pull-right">
                                        <i class="fa fa-trash-o"></i> Clear</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">&nbsp;</div>
                        <div class="col-sm-7 input-group">
                            <span class="suket_sehat_success"></span>
                            <!-- <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button>&emsp; -->
                            &emsp; <button type="button" class="btn blue" onclick="save_suketsehat()"><i class="fa fa-save fa-fw"></i>&nbsp; Simpan</button>&emsp;
                            
                            <button type="button" class="btn btn-warning" <?= isset($ttv->sehat)? "" : "disabled" ?> onclick="_urlcetak2()"><i class="fa fa-print"></i>&nbsp; Print</button>&emsp;

                            <button type="button"  class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;<b>Batal<b></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i><b>Pemeriksaan Dokter</b>
                    </div>
                </div>
                <div class="portlet-body">			
                    <div class="row form-section modal-title">
                        <div class="col-md-4">
                            <h3 style="color:green" align="left"><b>EMR-RAWAT JALAN</b></h3>
                        </div>
                        <div class="col-md-<?= $status_kasir == 0 ? "5" : "8" ?>">
                            <h3 style="color:green;font-weight:bold" align="left" id="doktertitle"><b>
                                <?php
                                    if(isset($ttv->kodokter)){
                                        if($ttv->kodokter == "" || $ttv->kodokter == null){
                                            $kodokter = $data_pas->kodokter;
                                        } else {
                                            $kodokter = $ttv->kodokter;
                                        }
                                    } else {
                                        $kodokter = $data_pas->kodokter;
                                    }

                                    // if($ttv->kodokter == "" || $ttv->kodokter == null){
                                    //     $kodokter = $data_pas->kodokter;
                                    // } else {
                                    //     $kodokter = $ttv->kodokter;
                                    // }

                                    // if(isset($ttv->kodokter)){
                                    //     $polipas        = $ttv->kodepos;
                                    //     $kodokter       = $ttv->kodokter;
                                    //     $unit           = $this->session->userdata("unit");
                                    //     echo data_master("dokter", array("kodokter" => $kodokter, "koders" => $unit, "kopoli" => $polipas))->nadokter;
                                    // } else {
                                    //     $polipas        = $data_pas->kodepos;
                                    //     $kodokter       = $data_pas->kodokter;
                                    //     $unit           = $this->session->userdata("unit");
                                    //     echo data_master("dokter", array("kodokter" => $kodokter, "koders" => $unit, "kopoli" => $polipas))->nadokter;
                                    // }
                                    
                                    // if(isset($data_pas->kodokter)){
                                        $polipas        = isset($ttv->kodepos)? $ttv->kodepos : $data_pas->kodepos;
                                        // $kodokter       = isset($ttv->kodokter)? ($ttv->kodokter == "")? $data_pas->kodokter : $ttv->kodokter : $data_pas->kodokter;
                                        $unit           = $this->session->userdata("unit");
                                        echo data_master("dokter", array("kodokter" => $kodokter, "koders" => $unit, "kopoli" => $polipas))->nadokter;
                                    // }
                                ?>
                            </b></h3>
                        </div>
                        <?php if($status_kasir == 0): ?>
                        <div class="col-md-3">
                            <h3 style="color:green" align="left">
                                <select type="text" class="selectpicker" class="form-control" id="selectdr" name="selectdr" style="margin-top:20px" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                    <?php
                                        $polipas        = isset($ttv->kodepos)? $ttv->kodepos : $data_pas->kodepos;
                                        $unit           = $this->session->userdata("unit");

                                        $query_drpoli   = $this->db->query("SELECT * FROM dokter WHERE koders = '$unit' AND kopoli = '$polipas'")->result();
                                        foreach($query_drpoli as $drpval){
                                            // if(isset($ttv->kodokter)){
                                            //     if($ttv->kodokter == ""){
                                            //         $dokterls   = $data_pas->kodokter;
                                            //     } else {
                                            //         $dokterls   = $ttv->kodokter;
                                            //     }
                                            //     $dokterls   = $ttv->kodokter;
                                            // } else {
                                            //     $dokterls   = $data_pas->kodokter;
                                            // }

                                            // echo "<option>". $dokterls ."</option>";

                                            if($drpval->kodokter == $kodokter){
                                                echo "<option value='$drpval->kodokter' selected>". $drpval->nadokter ."</option>";
                                            } else {
                                                echo "<option value='$drpval->kodokter'>". $drpval->nadokter ."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </h3>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>No Registrasi</b></label>
                                <div class="col-md-8">
                                    <input value="<?= isset($ttv->noreg)? $ttv->noreg : $data_pas->noreg ?>" readonly id="noreg_dok" name="noreg_dok" class="form-control" maxlength="10" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>Tgl Periksa</b></label>
                                <div class="col-md-8">
                                    <input readonly id="tgl_dok" name="tgl_dok" class="form-control" type="date" value="<?= isset($ttv->tglperiksa)? $ttv->tglperiksa : date("Y-m-d") ?>">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>No Rekmed</b></label>
                                <div class="col-md-8">
                                    <input value="<?= isset($ttv->rekmed)? $ttv->rekmed : $data_pas->rekmed ?>" readonly id="rekmed_dok" name="rekmed_dok" class="form-control" maxlength="100" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>Tujuan</b></label>
                                <div class="col-md-8">
                                    <select type="text" class="form-control" id="poli_dok" name="poli_dok" readonly>
                                        <option value="<?= $data_pas->kodepos ?>"><?= data_master("tbl_namapos", array("kodepos" => $data_pas->kodepos))->namapost ?></option>
                                    </select>
                                    <!-- <input readonly value="<?= isset($ttv->kodepos)? data_master("tbl_namapos", array("kodepos" => $ttv->kodepos))->namapost : data_master("tbl_namapos", array("kodepos" => $data_pas->kodepos))->namapost ?>" id="poli_dok" name="poli_dok" class="form-control" type="text">
                                    <span class="help-block"></span> -->
                                </div>
                            </div>
                        </div>
                    </div>   
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>Nama Pasien</b></label>
                                <div class="col-md-8">
                                    <input readonly value="<?= $data_pas->namapas ?>" id="nampas_dok" name="nampas_dok"  class="form-control" maxlength="100" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:red"><b>Alergi</b></label>
                                <div class="col-md-8">
                                    <textarea style="resize:none" id="alergi_dok" name="alergi_dok"  placeholder="" class="form-control"><?= isset($ttv->alergi)? $ttv->alergi : "" ?></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>Umur </b></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="umur" name="umur" value="" readonly>	
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>Gudang BHP</b></label>
                                <div class="col-md-8">
                                    <select type="text" class="form-control" id="gudang_bhp" name="gudang_bhp">
                                        <option value="">- PILIH GUDANG -</option>
                                        <?php
                                            $unit       = $this->session->userdata("unit");
                                            $list_depo  = $this->db->query("SELECT * FROM tbl_depo ORDER BY keterangan ASC")->result();
                                            $get_gudang = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '". $_GET["noreg"] ."' GROUP BY notr AND koders = '$unit'")->row();
                                            foreach($list_depo as $ld_val){
                                                if($ld_val->depocode == $get_gudang->gudang){
                                                    echo "<option value='$get_gudang->gudang' selected>". data_master("tbl_depo", array("depocode" => $get_gudang->gudang))->keterangan ."</option>";
                                                } else {
                                                    echo "<option value='$ld_val->depocode'>$ld_val->keterangan</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            &nbsp;
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" style="color:green"><b>No Kartu BPJS</b></label>
                                <div class="col-md-8">
                                    <input type="" class="form-control" value="<?= isset($ttv->noreg)? data_master("tbl_regist", array("noreg" => $ttv->noreg, "koders" => $ttv->koders))->nobpjs : "" ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="row" style="margin:20px 50px 20px 50px !important">
                        <div class="col-sm-4">
                            <label style="color:green;font-weight:bold;display:block;padding-bottom:5px">Riwayat Keluarga</label>
                            <textarea style="resize:none" name="riwayat_keluarga" class="form-control" rows="4"><?= $riwayat_pasien->riwayat_keluarga ?></textarea>
                        </div>
                        <div class="col-sm-4">
                            <label style="color:green;font-weight:bold;display:block;padding-bottom:5px">Riwayat Penyakit</label>
                            <textarea style="resize:none" name="riwayat_penyakit" class="form-control" rows="4"><?= $riwayat_pasien->riwayat_penyakit ?></textarea>
                        </div>
                        <div class="col-sm-4">
                            <label style="color:green;font-weight:bold;display:block;padding-bottom:5px">Kesimpulan Pemeriksaan Fisik</label>
                            <textarea style="resize:none" name="simpulfisik" placeholder="" class="form-control" rows="4" ><?= isset($ttv->pfisik)? $ttv->pfisik : "" ?></textarea>
                        </div>
                    </div>
        
                    <br />

                    <div class="row">
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
                                        <input class="form-control" type="checkbox"  id="ceknyeri1" name="ceknyeri" onclick="c_ceknyeri(1)" value="1" <?= isset($ttv->nyeri)? ($ttv->nyeri == 1)? "checked" : "" : "checked" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td class="rightJustified" align="center"> 
                                        <input class="form-control" type="checkbox"  id="ceknyeri2" name="ceknyeri" onclick="c_ceknyeri(2)" value="2" <?= isset($ttv->nyeri)? ($ttv->nyeri == 2)? "checked" : "" : "" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td class="rightJustified" align="center"> 
                                        <input class="form-control" type="checkbox"  id="ceknyeri3" name="ceknyeri" onclick="c_ceknyeri(3)" value="3" <?= isset($ttv->nyeri)? ($ttv->nyeri == 3)? "checked" : "" : "" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td class="rightJustified" align="center"> 
                                        <input class="form-control" type="checkbox"  id="ceknyeri4" name="ceknyeri" onclick="c_ceknyeri(4)" value="4" <?= isset($ttv->nyeri)? ($ttv->nyeri == 4)? "checked" : "" : "" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td class="rightJustified" align="center"> 
                                        <input class="form-control" type="checkbox"  id="ceknyeri5" name="ceknyeri" onclick="c_ceknyeri(5)" value="5" <?= isset($ttv->nyeri)? ($ttv->nyeri == 5)? "checked" : "" : "" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td class="rightJustified" align="center"> 
                                        <input class="form-control" type="checkbox" id="ceknyeri6" name="ceknyeri" onclick="c_ceknyeri(6)" value="6" <?= isset($ttv->nyeri)? ($ttv->nyeri == 6)? "checked" : "" : "" ?>>
                                    </td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>

                            </tr>
                        </table>
                    </div>

                    <h3 class="form-section col-md-6" style="color:green"><b></b></h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet box blue" style="padding-bottom:0px !important">
                                <div class="portlet-title">
                                    <div class="caption"><i class="fa fa-reorder"></i><b></b></div>
                                    <div class="tools"><a href="" class="collapse"></a>
                                </div>
                            </div>
                            <div class="portlet-body">	
                                <div id="frmpasien" class="form-horizontal" method="post">
                                    <div class="form-body">
                                        <div class="tabbable tabbable-custom tabbable-full-width">
                                            <?php

                                                if(isset($_GET["tab"])){
                                                    if($_GET["tab"] == "elab"){
                                                        $tab1   = "";
                                                        $tab4   = "active";
                                                        $tab5   = "";
                                                        $tab6   = "";
                                                    } else 
                                                    if($_GET["tab"] == "erm"){
                                                        $tab1   = "";
                                                        $tab4   = "";
                                                        $tab5   = "active";
                                                        $tab6   = "";
                                                    } else 
                                                    if($_GET["tab"] == "rad"){
                                                        $tab1   = "";
                                                        $tab4   = "";
                                                        $tab5   = "";
                                                        $tab6   = "active";
                                                    } else 
                                                    if($_GET["tab"] == ""){
                                                        echo "<script>location.href='/poliklinik/pemeriksaan_dokter/?noreg=". $this->input->get("noreg") ."&rekmed=". $this->input->get("rekmed") ."'</scrip>";
                                                    }
                                                } else {
                                                    $tab1   = "active";
                                                    $tab4   = "";
                                                    $tab5   = "";
                                                    $tab6   = "";
                                                }

                                            ?>
                                            <ul class="nav nav-tabs">
                                                <li class="" id="ases">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab1" data-toggle="tab">
                                                        <b>ASSESMENT AWAL</b>
                                                    </a>
                                                </li>
                                                <li class="<?= $tab1 ?>" id="sb">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab2" data-toggle="tab">
                                                        <b>SOAP & BILLING</b></a>
                                                </li>
                                                <li class="" id="resep">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab3" data-toggle="tab">
                                                        <b>e-RESEP</b>
                                                    </a>
                                                </li>
                                                <li class="<?= $tab4 ?>" id="lab">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab4" data-toggle="tab">
                                                        <b>e-LAB</b>
                                                    </a>
                                                </li>
                                                <li class="<?= $tab6 ?>" id="rad">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab7" data-toggle="tab">
                                                        <b>e-RAD</b>
                                                    </a>
                                                </li>
                                                <li class="<?= $tab5 ?>" id="erm">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab5" data-toggle="tab">
                                                        <b>e-ELEKTROMEDIS</b>
                                                    </a>
                                                </li>
                                                <li class="" id="hispas">
                                                    <a href="<?= "/poliklinik/pemeriksaan_dokter/". $_SERVER["QUERY_STRING"] ?>#tab6" data-toggle="tab">
                                                        <b>HISTORY PASIEN</b>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane" id="tab1">
                                                </div>
                                                <div class="tab-pane <?= $tab1 ?>" id="tab2">
                                                    <div class="portlet-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-striped table-bordered " cellspacing="0" cellpadding="0" style="overflow: auto;" border="1" width="100%">
                                                            <tr>
                                                                <td align="center" style="color:green">
                                                                <label for=""><b>Keluhan Awal</b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                <label for=""><b>Pemeriksaan</b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                <label for=""><b>Diagnosa Masuk</b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                <label for=""><b>Terapi/ Resep</b></label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" >
                                                                <textarea style="resize:none" class="form-control" name="kelawal" id="kelawal" cols="auto" rows="5"><?= isset($ttv->keluhanawal)? $ttv->keluhanawal : "" ?></textarea>
                                                                </td>
                                                                <td align="center" >
                                                                <textarea style="resize:none" class="form-control" name="pemeriksaan" id="pemeriksaan" cols="auto" rows="5"><?= isset($ttv->pfisik)? $ttv->pfisik : "" ?></textarea>
                                                                </td>
                                                                <td align="center" >
                                                                <textarea style="resize:none" class="form-control" name="diagmas" id="diagmas" cols="auto" rows="5"><?= isset($ttv->diagnosa)? $ttv->diagnosa : "" ?></textarea>
                                                                </td>
                                                                <td align="center" >
                                                                <textarea style="resize:none" class="form-control" name="teresep" id="teresep" cols="auto" rows="5"><?= isset($ttv->resep)? $ttv->resep : "" ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border: none;">&nbsp;</td>
                                                                <td style="border: none;">&nbsp;</td>
                                                                <td style="border: none;">&nbsp;</td>
                                                                <td style="border: none;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" style="color:green">
                                                                    <label for=""><b>Diagnosa (Free Text)<b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                    <label for=""><b>Tindakan (Free Text)<b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                    <label for=""><b>Saran Anjuran<b></label>
                                                                </td>
                                                                <td align="center" style="color:green">
                                                                    <label for=""><b>Surat Keterangan<b></label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center">
                                                                <textarea style="resize:none" class="form-control" name="diagnosa" id="diagnosa" cols="auto" rows="5"><?= isset($ttv->diagu)? $ttv->diagu : "" ?></textarea>
                                                                </td>
                                                                <td align="center">
                                                                <textarea style="resize:none" class="form-control" name="tindu" id="tindu" cols="auto" rows="5"><?= isset($ttv->tindu)? $ttv->tindu : "" ?></textarea>
                                                                </td>
                                                                <td align="center" >
                                                                <textarea style="resize:none" class="form-control" name="anjuran" id="anjuran" cols="auto" rows="5"><?= isset($ttv->anjuran)? $ttv->anjuran : "" ?></textarea>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn green" style="display:block;width:100%;margin:auto;font-weight:bold" data-toggle="modal" data-target="#suketsakit">Surat Keterangan Sakit</button>
                                                                    <br />
                                                                    <button type="button" class="btn green" style="display:block;width:100%;margin:auto;font-weight:bold" data-toggle="modal" data-target="#suketsehat">Surat Keterangan Sehat</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                            </table>
                                                            <table id="datatable_diagnosa" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                                                                <h4 style="color:green"><b>Diagnosa ICD 10 dan Procedure ICD 9 coding</b></h4>
                                                                <thead class="page-breadcrumb breadcrumb">
                                                                    <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                                                    <th class="title-white" width="30%" style="text-align: center">JENIS </th>
                                                                    <th class="title-white" width="45%" style="text-align: center">Icd 10/ Icd 9 diagnosa</th>
                                                                    <th class="title-white" width="20%" style="text-align: center">Utama</th>

                                                                </thead>

                                                                <tbody id="diagnosa_row">
                                                                    <?php if($statusicd == "undone"): ?>
                                                                    <tr id="diagnosa_tr1">
                                                                        <td align="center" >
                                                                            <button type='button' onclick="hapusBaris_diagnosa(1)" class='btn red'><i class='fa fa-trash-o'></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>
                                                                            <select name="jenis_diag[]" id="jenis_diag1" class="select2_el_jnsicd form-control input-largex" onchange="getdiag(this.value,1)">
                                                                            </select>

                                                                        </td>

                                                                        <td>
                                                                            <select name="diag[]" id="diag1" class="select2_el_icdind form-control input-largex" onchange="pcare_diag_trigger(this.value)">
                                                                            </select>

                                                                        </td>
                                                                        <td>
                                                                            <input name="utama[]" id="utama1" type="checkbox" class="form-control " onclick="cekutm(this.value,1)">
                                                                            <input name="utama_hide[]" id="utama_hide1" type="hidden" class="form-control" >

                                                                        </td>

                                                                    </tr>
                                                                    <?php else: $no = 1; foreach($detilicd as $dkey => $dval):?>
                                                                    <tr id="diagnosa_tr<?= $no ?>">
                                                                        <td align="center" >
                                                                            <button type='button' onclick="hapusBaris_diagnosa(<?= $no ?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>
                                                                            <select name="jenis_diag[]" id="jenis_diag<?= $no ?>" class="select2_el_jnsicd form-control input-largex" onchange="getdiag(this.value,<?= $no ?>)">
                                                                                <option value="<?= $dval->jns ?>" selected><?= data_master("tbl_setinghms", (array("kodeset" => $dval->jns)))->keterangan ?></option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select name="diag[]" id="diag<?= $no ?>" class="select2_el_icdind form-control input-largex" onchange="pcare_diag_trigger(this.value)">
                                                                                <option value="<?= $dval->icdcode ?>" selected><?= $dval->nmdiag ?></option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input name="utama[]" id="utama<?= $no ?>" type="checkbox" class="form-control " onclick="cekutm(this.value,<?= $no ?>)" <?= isset($dval->utama)? ($dval->utama == 1)? "checked" : "" : "" ?>>
                                                                            <input name="utama_hide[]" id="utama_hide<?= $no ?>" type="hidden" class="form-control" value="<?= isset($dval->utama)? ($dval->utama == 1)? 1 : 0 : "" ?>">
                                                                        </td>
                                                                    </tr>
                                                                    <?php $no++; endforeach; endif; ?>
                                                                </tbody>
                                                            </table>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-xs-9">
                                                                                <div class="wells">
                                                                                    <button type="button" onclick="tambahdiag()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Diagnosa</b> </button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-right: none;">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                            <table id="datatable_poli_tindakan" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable " border="1">
                                                                <h4 style="color:green" ><b>Billing (Silahkan Diisi Harga Dan Tindakan Sesuai Harga Yang Tersedia   )</b></h4>
                                                                <thead class="page-breadcrumb breadcrumb">
                                                                    <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                                                    <th class="title-white" width="35%" style="text-align: center">TINDAKAN
                                                                    </th>
                                                                    <th class="title-white" width="20%" style="text-align: center">HARGA</th>
                                                                    <th class="title-white" width="20%" style="text-align: center">DOKTER</th>
                                                                    <th class="title-white" width="20%" style="text-align: center">PARAMEDIS</th>

                                                                </thead>
                                                                <tbody id="tindakan_row">
                                                                    <?php if($statusbill == "undone"): ?>
                                                                    <tr id="tindakan_tr1">
                                                                        <td align="center" >
                                                                            <button type='button' onclick="hapusBaris_tindakan(1)" class='btn red'><i class='fa fa-trash-o'></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>
                                                                            <select name="kode[]" id="kode1"
                                                                                class="select2_el_poli_tindakan form-control input-largex" onchange="show_tindakan(this.value, 1)">
                                                                            </select>

                                                                        </td>

                                                                        <td>
                                                                            <input name="hrg[]" id="hrg1" type="text" class="form-control rightJustified" readonly>
                                                                        </td>

                                                                        <td>
                                                                            <select name="dokter[]" class="select2_el_dokter form-control" id="dokter1">
                                                                                <option value="<?= $kodokter ?>" selected><?= data_master("dokter", array("kodokter" => $kodokter, "koders" => $this->session->userdata("unit"), "kopoli" => $polipas))->nadokter ?></option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select name="paramedis[]" class="select2_el_perawat form-control" id="paramedis1"></select>
                                                                        </td>

                                                                        

                                                                    </tr>
                                                                    <?php else: $no2 = 1; foreach($detilbilling as $bkey => $bval):?>
                                                                        <tr id="tindakan_tr<?= $no2 ?>">
                                                                            <td align="center" >
                                                                                <button type='button' onclick="hapusBaris_tindakan(<?= $no2 ?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                                                                </button>
                                                                            </td>
                                                                            <td>
                                                                                <select name="kode[]" id="kode<?= $no2 ?>" class="select2_el_poli_tindakan form-control input-largex" onchange="show_tindakan(this.value, <?= $no2 ?>)">
                                                                                    <option value="<?= $bval->kodetarif ?>"><?= $bval->nm_tin ?></option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input name="hrg[]" id="hrg<?= $no2 ?>" type="text" class="form-control rightJustified" value="<?= isset($bval->tarifrs)? number_format($bval->tarifrs+$bval->tarifdr+$bval->paramedis+$bval->obatpoli, 2, '.', ',') : "" ?>" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <select name="dokter[]" id="dokter<?= $no2 ?>" type="text" class="select2_el_dokter form-control">
                                                                                    <?php
                                                                                        if(isset($bval->kodokter)){
                                                                                    ?>
                                                                                            <!-- echo "<option value='$bval->kodokter'>". data_master("tbl_dokter", array("kodokter" => $bval->kodokter, "koders" => $this->session->userdata("unit")))->nadokter ."</option>"; -->
                                                                                            <option value="<?= $bval->kodokter ?>" selected><?= data_master("dokter", array("kodokter" => $bval->kodokter, "koders" => $this->session->userdata("unit"), "kopoli" => $polipas))->nadokter ?></option>
                                                                                            
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="paramedis[]" id="paramedis<?= $no2 ?>" type="text" class="select2_el_perawat form-control">
                                                                                    <?php
                                                                                        if(isset($bval->koperawat)){
                                                                                    ?>
                                                                                        <!-- echo "<option value='$bval->kodokter'>". data_master("tbl_dokter", array("kodokter" => $bval->koperawat, "koders" => $this->session->userdata("unit")))->nadokter ."</option>"; -->
                                                                                        <option value="<?= $bval->koperawat ?>" selected><?= data_master("perawat", array("kodokter" => $bval->koperawat, "koders" => $this->session->userdata("unit"), "kopoli" => $polipas))->nadokter ?></option>
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    <?php $no2++; endforeach; endif; ?>
                                                                </tbody>
                                                            </table>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-xs-9">
                                                                                <div class="wells">
                                                                                    <button type="button" onclick="tambah_billing()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Billing</b> </button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-right: none;">&nbsp;</td>
                                                                </tr>
                                                            </table>

                                                            <table id="datatable_poli_alkes" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                                                                <!-- <h4 style="color:green" ><b>Bahan Habis Pakai Dan Alkes Di Ruang Praktek Yang Dipakai</b></h4> -->
                                                                <h4 style="color:green" ><b>Barang Habis Pakai</b></h4>
                                                                <thead class="page-breadcrumb breadcrumb">
                                                                    <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                                                    <th class="title-white" width="15%" style="text-align: center">DI BEBANKAN
                                                                    </th>
                                                                    <th class="title-white" width="30%" style="text-align: center">NAMA BARANG</th>
                                                                    <th class="title-white" width="10%" style="text-align: center">SATUAN</th>
                                                                    <th class="title-white" width="10%" style="text-align: center">QTY</th>
                                                                    <th class="title-white" width="15%" style="text-align: center">HARGA</th>
                                                                    <th class="title-white" width="15%" style="text-align: center">TOTAL HARGA</th>

                                                                </thead>

                                                                <tbody id="poli_alkes_row">
                                                                    <?php if($statusalkes == "undone"): ?>
                                                                    <!-- <tr id="alkes_tr1">
                                                                        <td width="2%"  align="center" >
                                                                            <button type='button' onclick="hapusBaris_alkes(1)" class='btn red'><i class='fa fa-trash-o'></i> </button> 
                                                                        </td>
                                                                        <td width="15%">
                                                                            <input name="bbn[]" id="bbn1" type="checkbox" class="form-control" onclick="cekbbn(this.value,1)">
                                                                            <input name="bbn_hide[]" id="bbn_hide1" type="hidden" class="form-control" value="0">
                                                                        </td>
                                                                        <td width="30%">
                                                                            <select name="kdalkes[]" id="kdalkes1" class="select2_el_alkes form-control input-largex" onchange="show_alkes(this.value, 1)">

                                                                            </select>
                                                                        </td>
                                                                        <td width="10%">
                                                                            <input name="satalkes[]" id="satalkes1" type="text" class="form-control" readonly>
                                                                        </td>
                                                                        <td width="10%">
                                                                            <input name="qtyalkes[]" id="qtyalkes1" type="number" class="form-control " onkeyup="qty(1)">
                                                                        </td>
                                                                        <td width="15%">
                                                                            <input name="hrgalkes[]" id="hrgalkes1" type="text" class="form-control rightJustified" readonly>
                                                                        </td>
                                                                        <td width="18%">
                                                                            <input name="totalkes[]" id="totalkes1" type="text" class="form-control rightJustified" readonly>
                                                                        </td>
                                                                    </tr> -->
                                                                    <?php else: $no3 = 1; foreach($detilalkes as $dakey => $daval):?>
                                                                    <tr id="alkes_tr<?= $no3 ?>">
                                                                        <td width="2%"  align="center" >
                                                                            <button type='button' onclick="hapusBaris_alkes(<?= $no3 ?>)" class='btn red'><i class='fa fa-trash-o'></i>
                                                                            </button>                                        
                                                                        </td>
                                                                        <td width="15%">
                                                                            <input name="bbn[]" id="bbn<?= $no3 ?>" type="checkbox" class="form-control" onclick="cekbbn(this.value,<?= $no3 ?>)" <?= isset($daval->dibebankan)? ($daval->dibebankan == 1)? "checked" : "" : "" ?>>
                                                                            <input name="bbn_hide[]" id="bbn_hide<?= $no3 ?>" type="hidden" class="form-control" value="<?= isset($daval->dibebankan)? ($daval->dibebankan == 1)? 1 : 0 : "" ?>">
                                                                        </td>
                                                                        <td width="30%">
                                                                            <select name="kdalkes[]" id="kdalkes<?= $no3 ?>" class="select2_el_alkes form-control input-largex" onchange="show_alkes(this.value, <?= $no3 ?>)">
                                                                                <option value="<?= $daval->kodeobat ?>" selected><?= data_master("tbl_barang", array("kodebarang" => $daval->kodeobat))->namabarang ?></option>
                                                                                <?php
                                                                                    // if(isset($daval->kodeobat)){
                                                                                    //     echo "<option value='$bval->kodeobat' selected>". data_master("tbl_logbarang", array("kodebarang" => $daval->kodeobat))->namabarang ."</option>";
                                                                                    // }
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td width="10%">
                                                                            <input name="satalkes[]" id="satalkes<?= $no3 ?>" type="text" class="form-control" value="<?= isset($daval->satuan)? $daval->satuan : "" ?>" readonly>
                                                                        </td>
                                                                        <td width="10%">
                                                                            <input name="qtyalkes[]" id="qtyalkes<?= $no3 ?>" type="number" class="form-control" value="<?= isset($daval->qty)? $daval->qty : "" ?>" onkeyup="qty(<?= $no3 ?>)">
                                                                        </td>
                                                                        <td width="15%">
                                                                            <input name="hrgalkes[]" id="hrgalkes<?= $no3 ?>" type="text" class="form-control rightJustified" value="<?= isset($daval->harga)? $daval->harga : "" ?>" readonly>
                                                                        </td>
                                                                        <td width="18%">
                                                                            <input name="totalkes[]" id="totalkes<?= $no3 ?>" type="text" class="form-control rightJustified" value="<?= isset($daval->totalharga)? $daval->totalharga : "" ?>" readonly>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $no3++; endforeach; endif; ?>
                                                                </tbody>
                                                            </table>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-xs-9">
                                                                                <div class="wells">
                                                                                    <button type="button" onclick="tambah_alkes()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Bhp/ Alkes</b> </button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-right: none;">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-bottom:0px !important">
                                                        <div class="col-md-12" style="margin-bottom:0px !important">		
                                                            <div class="form-actions" style="margin-bottom:0px !important">
                                                                <?php if($status_kasir == 0){ ?><button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button><?php } ?>
                                                                <div class="btn-group">
                                                                    <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                </div>
                                                            </div>							
                                                        </div>															
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab3">
                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-sm-6">&nbsp;</div>
                                                            <div class="col-sm-6">
                                                                <div style="margin-bottom:20px">
                                                                    <h5 style="color:green"><b>Data E-Resep Yang Sudah Dibuat</b></h5>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-striped table-condensed table-scrollable">
                                                                            <thead class="page-breadcrumb breadcrumb">
                                                                                <tr>
                                                                                    <th class="title-white">Eresep No</th>
                                                                                    <th class="title-white">Tanggal</th>
                                                                                    <th class="title-white">Status</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php if($status_dop == "undone"){ ?>
                                                                                    <tr>
                                                                                        <td style="text-align:center;color:red" colspan="3">Belum AdaData</td>
                                                                                    </tr>
                                                                                <?php } else { foreach($data_dop as $ddk => $ddv){ ?>
                                                                                    <tr>
                                                                                        <td><?= $ddv->orderno ?></td>
                                                                                        <td><?= date("Y-m-d", strtotime($ddv->tglorder)) ?></td>
                                                                                        <td><?= $ddv->proses ?></td>
                                                                                    </tr>
                                                                                <?php } } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-sm-3"><h4 style="color:green"><b>E-ORDER RESEP</b></h4></div>
                                                            <div class="col-sm-4">
                                                                <div class="row">
                                                                    <div class="col-sm-4" style="padding-top:5px">
                                                                        <label for="inputPassword6" class="col-form-label">No Eresep</label>
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="noeresep" id="noeresep" value="<?= isset($orderperiksa->orderno)? $orderperiksa->orderno : "0" ?>" readonly>
                                                                        <input type="hidden" name="noeresephide" id="noeresephide" value="<?= isset($orderperiksa->orderno)? $orderperiksa->orderno : "0" ?>">
                                                                        <input type="hidden" name="doktereresep" value="<?= $data_pas->kodokter ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <div class="row">
                                                                    <div class="col-sm-3" style="padding-top:5px">
                                                                        <label for="inputPassword6" class="col-form-label">Gudang</label>
                                                                    </div>
                                                                    <div class="col-sm-9">
                                                                        <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo input-medium" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" onchange="setkodebarang()" readonly>
                                                                            <?php if(isset($orderperiksa->gudang)): ?>
                                                                                <option value="<?= $orderperiksa->gudang ?>"><?= data_master("tbl_depo", array("depocode" => $orderperiksa->gudang))->keterangan; ?></option>
                                                                            <?php endif; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table id="datatable_obatterapi" class="table table-bordered table-striped">
                                                                <thead class="page-breadcrumb breadcrumb">
                                                                    <tr style="text-center">
                                                                        <th class="title-white" style="width:2%">Delete</th>
                                                                        <th class="title-white" style="width:2%">Kronis</th>
                                                                        <th class="title-white" style="width:25%">Nama Obat & Terapi</th>
                                                                        <th class="title-white" style="width:7%">Satuan</th>
                                                                        <th class="title-white" style="width:2%">Jmlh Obat/Hari</th>
                                                                        <th class="title-white" style="width:7%">Qty Dosis</th>
                                                                        <th class="title-white" style="width:7%">Jmlh Hari</th>
                                                                        <th class="title-white" style="width:7%">Jmlh Obat</th>
                                                                        <th class="title-white" style="width:15%">Aturan Pakai</th>
                                                                        <th class="title-white" style="width:25%">Keterangan</th>
                                                                        <!-- <th class="title-white">Validasi Farmasi</th> -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="eresep_row">
                                                                    <?php if($status_eresep == "undone"){ ?>
                                                                    <tr id="eresep_tr1">
                                                                        <td>
                                                                            <button type='button' onclick="hapusBaris_eresep(1)" class='btn red'><i class='fa fa-trash-o'></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <input name="kronis[]" id="kronis1" type="checkbox" class="form-control " onclick="cekkronis(this.value,1)">
                                                                            <input name="kronis_hide[]" id="kronis_hide1" type="hidden" class="form-control" value="0">
                                                                            <input name="harga_ot[]" id="hargaer1" type="hidden" value="">
                                                                            <input name="totalharga_ot[]" id="totalhargaer1" type="hidden" value="">
                                                                        </td>
                                                                        <td>
                                                                            <select name="obat_terapi[]" id="obat_terapi1" class="select2_el_farmasi_baranggud form-control input-medium" onchange="show_obatterapi(this.value, 1)"></select>
                                                                        </td>
                                                                        <td>
                                                                            <input name="satuan_ot[]" id="satuan_ot1" class="form-control" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_obat_hari[]" id="jml_obat_hari1" onkeyup="jmlobat(1)" value=""  class="form-control">
                                                                        </td>
                                                                        <td>
                                                                            <input name="qty_minumum[]" id="qty_minumum1" onkeyup="jmlobat(1)" value="" class="form-control">
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_hari[]" id="jml_hari1" onkeyup="jmlobat(1)" value="" class="form-control">
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_obat[]" id="jml_obat1" class="form-control" onkeyup="qtyer(1)" value="">
                                                                        </td>
                                                                        <td>
                                                                            <!-- <select name="aturan_pakai[]" id="aturan_pakai1" class="form-control">
                                                                            <?php
                                                                                $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup='ATURANPAKAI' ")->result();
                                                                                foreach ($data as $row){ 
                                                                            ?>
                                                                            <option value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                            <?php } ?>
                                                                            </select> -->
                                                                            <input name="autran_pakai[]" id="autran_pakai1" class="form-control" rows="2" style="resize:none">
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="keterangan[]" id="keterangan1" class="form-control" rows="2" style="resize:none"></textarea>
                                                                        </td>
                                                                        <!-- <td>
                                                                            <textarea name="valid_farmasi[]" id="valid_farmasi1" class="form-control" rows="2" style="resize:none"></textarea>
                                                                        </td> -->
                                                                    </tr>
                                                                    <?php } else { $noeresep = 1; foreach($detileresep as $derval){?>
                                                                        <tr id="eresep_tr<?= $noeresep ?>">
                                                                        <td>
                                                                            <button type='button' onclick="hapusBaris_eresep(<?= $noeresep ?>)" class='btn red'><i class='fa fa-trash-o'></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <input name="kronis[]" id="kronis<?= $noeresep ?>" type="checkbox" class="form-control " onclick="cekkronis(this.value,<?= $noeresep ?>)" <?= ($derval->kronis == 1)? "checked" : "" ?>>
                                                                            <input name="kronis_hide[]" id="kronis_hide<?= $noeresep ?>" type="hidden" class="form-control" value="<?= ($derval->kronis == 1)? 1 : 0 ?>">
                                                                            <input name="harga_ot[]" id="hargaer<?= $noeresep ?>" type="hidden" value="<?= $derval->harga ?>">
                                                                            <input name="totalharga_ot[]" id="totalhargaer<?= $noeresep ?>" type="hidden" value="<?= $derval->totalharga ?>">
                                                                        </td>
                                                                        <td>
                                                                            <select name="obat_terapi[]" id="obat_terapi<?= $noeresep ?>" class="select2_el_farmasi_baranggud form-control input-medium" onchange="show_obatterapi(this.value, <?= $noeresep ?>)">
                                                                                <option value="<?= $derval->kodeobat ?>" selected><?= $derval->namaobatnya ?></option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input name="satuan_ot[]" id="satuan_ot<?= $noeresep ?>" class="form-control" readonly value="<?= $derval->satuan ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_obat_hari[]" id="jml_obat_hari<?= $noeresep ?>" class="form-control" onkeyup="jmlobat(1)" value="<?= str_replace(".00", "", $derval->qty_perhari) ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input name="qty_minumum[]" id="qty_minumum<?= $noeresep ?>" class="form-control" onkeyup="jmlobat(1)" value="<?= str_replace(".00", "", $derval->qty_minum) ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_hari[]" id="jml_hari<?= $noeresep ?>" class="form-control" onkeyup="jmlobat(1)" value="<?= str_replace(".00", "", $derval->jml_hari) ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input name="jml_obat[]" id="jml_obat<?= $noeresep ?>" class="form-control" onkeyup="qtyer(<?= $noeresep ?>)" value="<?= str_replace(".00", "", $derval->qty) ?>" >
                                                                        </td>
                                                                        <td>
                                                                            <!-- <select name="aturan_pakai[]" id="aturan_pakai<?= $noeresep ?>" class="form-control">
                                                                            <?php
                                                                                $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup='ATURANPAKAI' ")->result();
                                                                                foreach ($data as $row){ 
                                                                                    if($row->apocode == $derval->aturanpakai){
                                                                            ?>
                                                                                <option value="<?= $row->apocode; ?>" selected><?= $row->aponame; ?></option>
                                                                            <?php } else { ?>
                                                                                <option value="<?= $row->apocode; ?>"><?= $row->aponame; ?></option>
                                                                            <?php } } ?>
                                                                            </select> -->
                                                                            <input name="autran_pakai[]" id="autran_pakai<?= $noeresep ?>" class="form-control" rows="2" style="resize:none" value="<?= $derval->aturanpakai ?>">
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="keterangan[]" id="keterangan<?= $noeresep ?>" class="form-control" rows="2" style="resize:none"><?= $derval->keterangan ?></textarea>
                                                                        </td>
                                                                        <!-- <td>
                                                                            <textarea name="valid_farmasi[]" id="valid_farmasi<?= $noeresep ?>" class="form-control" rows="2" style="resize:none"><?= $derval->validfarmasi ?></textarea>
                                                                        </td> -->
                                                                    </tr>
                                                                    <?php $noeresep++; } } ?>
                                                                </tbody>
                                                            </table>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-xs-9">
                                                                                <div class="wells">
                                                                                    <button type="button" onclick="tambah_obatterapi()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Resep</b> </button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-right: none;">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <br /><br />
                                                        <h4 style="color:green"><b>Racikan</b>
                                                            <div class="dropdown" id="myRacikanbtn" style="display:inline-block;margin-left:20px">
                                                                <button class="btn btn-outline-primary dropdown-toggle" id="rbtn" type="button" data-toggle="dropdown">
                                                                    Racikan ke 1 &nbsp;<span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdownitem activerbtn" id="racikan1" onclick="filterRacikan('1');changeRbtn(1)">Racikan ke 1</a></li>
                                                                    <li><a class="dropdownitem" id="racikan2" onclick="filterRacikan('2');changeRbtn(2)">Racikan ke 2</a></li>
                                                                    <li><a class="dropdownitem" id="racikan3" onclick="filterRacikan('3');changeRbtn(3)">Racikan ke 3</a></li>
                                                                </ul>
                                                            </div>
                                                        </h4>

                                                        <!-- Racikan 1 -->
                                                        <div class="racikanFilter 1 portlet box purple" style="margin-bottom:0px !important">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <span class="title-white"><b>RACIKAN KE - 1</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body form">
                                                                <div class="form-body">
                                                                    <table class="table" style="width:100%;margin:auto">
                                                                        <tbody>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Nama Racik</td>
                                                                                <td style="width:80%"><input type="text" class="form-control" name="nama_racik1" style="width:100%" value="<?= isset($orderperiksa->racik1)? $orderperiksa->racik1 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Qty Jadi</td>
                                                                                <td style="width:80%"><input type="number" style="width:100%" class="form-control" name="qty_jadi1" value="<?= isset($orderperiksa->qtyjadi_racik1)? $orderperiksa->qtyjadi_racik1 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Aturan Pakai</td>
                                                                                <!-- <td style="width:80%"><textarea type="text" style="width:100%;resize:none !important" class="form-control" name="aturan_pakai1" rows="2"><?= isset($orderperiksa->aturan_pakai_racik_racik1)? $orderperiksa->aturan_pakai_racik_racik1 : "" ?></textarea></td> -->
                                                                                <td><select type="text" class="form-contol" name="aturan_pakai1">
                                                                                    <option value="">- Pilih Aturan Pakai -</option>
                                                                                    <?php
                                                                                        foreach($aturan_pakai->result() as $ap){
                                                                                            if(isset($orderperiksa->aturan_pakai_racik1)){
                                                                                                if($ap->apocode== $orderperiksa->aturan_pakai_racik1){
                                                                                                    echo "<option value='". $orderperiksa->aturan_pakai_racik1 ."' selected>". data_master("tbl_barangsetup", array("apocode" => $orderperiksa->aturan_pakai_racik1))->aponame ."</option>";
                                                                                                } else {
                                                                                                    echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                                }
                                                                                            } else {
                                                                                                echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <div class="table-responsive" style="margin-bottom:10px">
                                                                        <table style="width:100%;margin:auto" class="table table-hover table-striped table-bordered table-scrollable">
                                                                            <thead>
                                                                                <tr class="page-breadcrumb breadcrumb">
                                                                                    <th style="width:5%" class="title-white">Delete</th>
                                                                                    <th style="width:30%" class="title-white">Nama Bahan/Obat</th>
                                                                                    <th style="width:15%" class="title-white">Satuan</th>
                                                                                    <th style="width:10%" class="title-white">Qty Racik</th>
                                                                                    <th style="width:10%" class="title-white">Qty Jual</th>
                                                                                    <th style="width:15%" class="title-white">Harga</th>
                                                                                    <th style="width:15%" class="title-white">Total Harga</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="racikan1_row">
                                                                                <?php if($status_racik1 == "undone"){ ?>
                                                                                    <tr id="racikan1_tr1">
                                                                                        <td><input type="hidden" name="r1racikid" value="1"><button class="btn red" type="button" onclick="hapusBaris_racikan1(1)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td><select class="form-select select2_el_farmasi_baranggud input-large" name="r1nama_obat[]" id="r1nama_obat1" onchange="racikan1Detail(this.value,1)"></select></td>
                                                                                        <td><input type="text" class="form-control" name="r1satuan[]" id="r1satuan1" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r1qtyracik[]" id="r1qtyracik1"></td>
                                                                                        <td><input type="number" class="form-control" name="r1qtyjual[]" id="r1qtyjual1" onkeyup="qtyracik1(1)"></td>
                                                                                        <td><input type="text" class="form-control" name="r1harga[]" id="r1harga1"></td>
                                                                                        <td><input type="text" class="form-control" name="r1totalharga[]" id="r1totalharga1"></td>
                                                                                    </tr>
                                                                                <?php } else { $nora1 = 1; foreach($detil_racik1 as $dra1){ ?>
                                                                                    <tr id="racikan1_tr<?= $nora1 ?>">
                                                                                        <td><input type="hidden" name="r1racikid" value="1"><button class="btn red" type="button" onclick="hapusBaris_racikan1(<?= $nora1 ?>)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td>
                                                                                            <select class="form-select select2_el_farmasi_baranggud input-large" name="r1nama_obat[]" id="r1nama_obat<?= $nora1 ?>" onchange="racikan1Detail(this.value,<?= $nora1 ?>)">
                                                                                                <option value="<?= $dra1->kodeobat ?>"><?= $dra1->namaobatr1 ?></option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td><input type="text" class="form-control" name="r1satuan[]" id="r1satuan<?= $nora1 ?>" value="<?= $dra1->satuan ?>" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r1qtyracik[]" id="r1qtyracik<?= $nora1 ?>" value="<?= str_replace(".00", "", $dra1->qty_racik) ?>"></td>
                                                                                        <td><input type="number" class="form-control" name="r1qtyjual[]" id="r1qtyjual<?= $nora1 ?>" onkeyup="qtyracik1(<?= $nora1 ?>)" value="<?= str_replace(".00", "", $dra1->qty_jual) ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r1harga[]" id="r1harga<?= $nora1 ?>" value="<?= number_format($dra1->harga, 2, '.', ',') ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r1totalharga[]" id="r1totalharga<?= $nora1 ?>" value="<?= number_format($dra1->totalharga, 2, '.', ',') ?>"></td>
                                                                                    </tr>
                                                                                <?php $nora1++; } } ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-xs-9">
                                                                                            <div class="wells">
                                                                                                <button type="button" onclick="tambah_racikan1()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah</b> </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-right: none;">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <label for="r1manualracik" class="form-label" style="font-weight:bold">Manual Racikan</label>
                                                                    <textarea type="text" class="form-control" name="r1manualracik" id="r1manualracik" style="resize:none"><?= isset($orderperiksa->manual_racik1)? $orderperiksa->manual_racik1 : "" ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Racikan 2 -->
                                                        <div class="racikanFilter 2 portlet box purple" style="margin-bottom:0px !important">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <span class="title-white"><b>RACIKAN KE - 2</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body form">
                                                                <div class="form-body">
                                                                    <table class="table" style="width:100%;margin:auto">
                                                                        <tbody>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Nama Racik</td>
                                                                                <td style="width:80%"><input type="text" class="form-control" name="nama_racik2" style="width:100%" value="<?= isset($orderperiksa->racik2)? $orderperiksa->racik2 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Qty Jadi</td>
                                                                                <td style="width:80%"><input type="number" style="width:100%" class="form-control" name="qty_jadi2" value="<?= isset($orderperiksa->qtyjadi_racik2)? $orderperiksa->qtyjadi_racik2 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Aturan Pakai</td>
                                                                                <!-- <td style="width:80%"><textarea type="text" style="width:100%;resize:none !important" class="form-control" name="aturan_pakai2" rows="2"><?= isset($orderperiksa->aturan_pakai_racik_racik2)? $orderperiksa->aturan_pakai_racik_racik2 : "" ?></textarea></td> -->
                                                                                <td><select type="text" class="form-contol" name="aturan_pakai2">
                                                                                    <option value="">- Pilih Aturan Pakai -</option>
                                                                                    <?php
                                                                                        foreach($aturan_pakai->result() as $ap){
                                                                                            if(isset($orderperiksa->aturan_pakai_racik2)){
                                                                                                if($ap->apocode== $orderperiksa->aturan_pakai_racik2){
                                                                                                    echo "<option value='". $orderperiksa->aturan_pakai_racik2 ."' selected>". data_master("tbl_barangsetup", array("apocode" => $orderperiksa->aturan_pakai_racik2))->aponame ."</option>";
                                                                                                } else {
                                                                                                    echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                                }
                                                                                            } else {
                                                                                                echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <div class="table-responsive" style="margin-bottom:10px">
                                                                        <table style="width:100%;margin:auto" class="table table-hover table-striped table-bordered table-scrollable">
                                                                            <thead>
                                                                                <tr class="page-breadcrumb breadcrumb">
                                                                                    <th style="width:5%" class="title-white">Delete</th>
                                                                                    <th style="width:30%" class="title-white">Nama Bahan/Obat</th>
                                                                                    <th style="width:15%" class="title-white">Satuan</th>
                                                                                    <th style="width:10%" class="title-white">Qty Racik</th>
                                                                                    <th style="width:10%" class="title-white">Qty Jual</th>
                                                                                    <th style="width:15%" class="title-white">Harga</th>
                                                                                    <th style="width:15%" class="title-white">Total Harga</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="racikan2_row">
                                                                                <?php if($status_racik2 == "undone"){ ?>
                                                                                    <tr id="racikan2_tr1">
                                                                                        <td><button class="btn red" type="button" onclick="hapusBaris_racikan2(1)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td><select class="form-select select2_el_farmasi_baranggud input-large" name="r2nama_obat[]" id="r2nama_obat1" onchange="racikan2Detail(this.value,1)"></select></td>
                                                                                        <td><input type="text" class="form-control" name="r2satuan[]" id="r2satuan1" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r2qtyracik[]" id="r2qtyracik1"></td>
                                                                                        <td><input type="number" class="form-control" name="r2qtyjual[]" id="r2qtyjual1" onkeyup="qtyracik2(1)"></td>
                                                                                        <td><input type="text" class="form-control" name="r2harga[]" id="r2harga1"></td>
                                                                                        <td><input type="text" class="form-control" name="r2totalharga[]" id="r2totalharga1"></td>
                                                                                    </tr>
                                                                                <?php } else { $nora2 = 1; foreach($detil_racik2 as $dra2){ ?>
                                                                                    <tr id="racikan2_tr<?= $nora2 ?>">
                                                                                        <td><button class="btn red" type="button" onclick="hapusBaris_racikan2(<?= $nora2 ?>)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td>
                                                                                            <select class="form-select select2_el_farmasi_baranggud input-large" name="r2nama_obat[]" id="r2nama_obat<?= $nora2 ?>" onchange="racikan2Detail(this.value,<?= $nora2 ?>)">
                                                                                                <option value="<?= $dra2->kodeobat ?>"><?= $dra2->namaobatr2 ?></option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td><input type="text" class="form-control" name="r2satuan[]" id="r2satuan<?= $nora2 ?>" value="<?= $dra2->satuan ?>" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r2qtyracik[]" id="r2qtyracik<?= $nora2 ?>" value="<?= str_replace(".00", "", $dra2->qty_racik) ?>"></td>
                                                                                        <td><input type="number" class="form-control" name="r2qtyjual[]" id="r2qtyjual<?= $nora2 ?>" onkeyup="qtyracik2(<?= $nora2 ?>)" value="<?= str_replace(".00", "", $dra2->qty_jual) ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r2harga[]" id="r2harga<?= $nora2 ?>" value="<?= number_format($dra2->harga, 2, '.', ',') ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r2totalharga[]" id="r2totalharga<?= $nora2 ?>" value="<?= number_format($dra2->totalharga, 2, '.', ',') ?>"></td>
                                                                                    </tr>
                                                                                <?php $nora2++; } } ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-xs-9">
                                                                                            <div class="wells">
                                                                                                <button type="button" onclick="tambah_racikan2()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah</b> </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-right: none;">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <label for="r2manualracik" class="form-label" style="font-weight:bold">Manual Racikan</label>
                                                                    <textarea type="text" class="form-control" name="r2manualracik" id="r2manualracik" style="resize:none"><?= isset($orderperiksa->manual_racik2)? $orderperiksa->manual_racik2 : "" ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Racikan 3 -->
                                                        <div class="racikanFilter 3 portlet box purple" style="margin-bottom:0px !important">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <span class="title-white"><b>RACIKAN KE - 3</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body form">
                                                                <div class="form-body">
                                                                    <table class="table" style="width:100%;margin:auto">
                                                                        <tbody>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Nama Racik</td>
                                                                                <td style="width:80%"><input type="text" class="form-control" name="nama_racik3" style="width:100%" value="<?= isset($orderperiksa->racik3)? $orderperiksa->racik3 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Qty Jadi</td>
                                                                                <td style="width:80%"><input type="number" style="width:100%" class="form-control" name="qty_jadi3" value="<?= isset($orderperiksa->qtyjadi_racik3)? $orderperiksa->qtyjadi_racik3 : "" ?>"></td>
                                                                            </tr>
                                                                            <tr style="background:#87ceeb">
                                                                                <td align="right" style="width:20%;font-weight:bold;">Aturan Pakai</td>
                                                                                <!-- <td style="width:80%"><textarea type="text" style="width:100%;resize:none !important" class="form-control" name="aturan_pakai3" rows="2"><?= isset($orderperiksa->aturan_pakai_racik_racik3)? $orderperiksa->aturan_pakai_racik_racik3 : "" ?></textarea></td> -->
                                                                                <td><select type="text" class="form-contol" name="aturan_pakai3">
                                                                                    <option value="">- Pilih Aturan Pakai -</option>
                                                                                    <?php
                                                                                        foreach($aturan_pakai->result() as $ap){
                                                                                            if(isset($orderperiksa->aturan_pakai_racik3)){
                                                                                                if($ap->apocode== $orderperiksa->aturan_pakai_racik3){
                                                                                                    echo "<option value='". $orderperiksa->aturan_pakai_racik3 ."' selected>". data_master("tbl_barangsetup", array("apocode" => $orderperiksa->aturan_pakai_racik3))->aponame ."</option>";
                                                                                                } else {
                                                                                                    echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                                }
                                                                                            } else {
                                                                                                echo "<option value='". $ap->apocode ."'>". $ap->aponame ."</option>";
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <div class="table-responsive" style="margin-bottom:10px">
                                                                        <table style="width:100%;margin:auto" class="table table-hover table-striped table-bordered table-scrollable">
                                                                            <thead>
                                                                                <tr class="page-breadcrumb breadcrumb">
                                                                                    <th style="width:5%" class="title-white">Delete</th>
                                                                                    <th style="width:30%" class="title-white">Nama Bahan/Obat</th>
                                                                                    <th style="width:15%" class="title-white">Satuan</th>
                                                                                    <th style="width:10%" class="title-white">Qty Racik</th>
                                                                                    <th style="width:10%" class="title-white">Qty Jual</th>
                                                                                    <th style="width:15%" class="title-white">Harga</th>
                                                                                    <th style="width:15%" class="title-white">Total Harga</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="racikan3_row">
                                                                                <?php if($status_racik3 == "undone"){ ?>
                                                                                    <tr id="racikan3_tr1">
                                                                                        <td><button class="btn red" type="button" onclick="hapusBaris_racikan3(1)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td><select class="form-select select2_el_farmasi_baranggud input-large" name="r3nama_obat[]" id="r3nama_obat1" onchange="racikan3Detail(this.value,1)"></select></td>
                                                                                        <td><input type="text" class="form-control" name="r3satuan[]" id="r3satuan1" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r3qtyracik[]" id="r3qtyracik1"></td>
                                                                                        <td><input type="number" class="form-control" name="r3qtyjual[]" id="r3qtyjual1" onkeyup="qtyracik3(1)"></td>
                                                                                        <td><input type="text" class="form-control" name="r3harga[]" id="r3harga1"></td>
                                                                                        <td><input type="text" class="form-control" name="r3totalharga[]" id="r3totalharga1"></td>
                                                                                    </tr>
                                                                                <?php } else { $nora3 = 1; foreach($detil_racik3 as $dra3){ ?>
                                                                                    <tr id="racikan3_tr<?= $nora3 ?>">
                                                                                        <td><button class="btn red" type="button" onclick="hapusBaris_racikan3(<?= $nora3 ?>)" name="" id=""><i class="fa fa-trash-o"></i></button></td>
                                                                                        <td>
                                                                                            <select class="form-select select2_el_farmasi_baranggud input-large" name="r3nama_obat[]" id="r2nama_obat<?= $nora3 ?>" onchange="racikan3Detail(this.value,<?= $nora3 ?>)">
                                                                                                <option value="<?= $dra3->kodeobat ?>"><?= $dra3->namaobatr3 ?></option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td><input type="text" class="form-control" name="r3satuan[]" id="r3satuan<?= $nora3 ?>" value="<?= $dra3->satuan ?>" readonly></td>
                                                                                        <td><input type="number" class="form-control" name="r3qtyracik[]" id="r3qtyracik<?= $nora3 ?>" value="<?= str_replace(".00", "", $dra3->qty_racik) ?>"></td>
                                                                                        <td><input type="number" class="form-control" name="r3qtyjual[]" id="r3qtyjual<?= $nora3 ?>" onkeyup="qtyracik3(<?= $nora3 ?>)" value="<?= str_replace(".00", "", $dra3->qty_jual) ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r3harga[]" id="r3harga<?= $nora3 ?>" value="<?= number_format($dra3->harga, 2, '.', ',') ?>"></td>
                                                                                        <td><input type="text" class="form-control" name="r3totalharga[]" id="r3totalharga<?= $nora3 ?>" value="<?= number_format($dra3->totalharga, 2, '.', ',') ?>"></td>
                                                                                    </tr>
                                                                                <?php $nora3++; } } ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-xs-9">
                                                                                            <div class="wells">
                                                                                                <button type="button" onclick="tambah_racikan3()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah</b> </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-right: none;">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <label for="r3manualracik" class="form-label" style="font-weight:bold">Manual Racikan</label>
                                                                    <textarea type="text" class="form-control" name="r3manualracik" id="r3manualracik" style="resize:none"><?= isset($orderperiksa->manual_racik3)? $orderperiksa->manual_racik3 : "" ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-bottom:0px !important">
                                                        <div class="col-md-12" style="margin-bottom:0px !important">		
                                                            <div class="form-actions" style="margin-bottom:0px !important">
                                                                <?php if($status_kasir == 0){ ?><button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button><?php } ?>
                                                                <div class="btn-group">
                                                                    <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                </div>
                                                            </div>							
                                                        </div>															
                                                    </div>
                                                </div>
</form><!-- FORM SOAP - ERESEP -->
                                                <div class="tab-pane <?= $tab4 ?>" id="tab4">
                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <form id="frmelab" method="post">
                                                                    <h4 style="color:green"><b>E-ORDER LABORATORIUM</b></h4>
                                                                    <div class="table-responsive" id="elab-table">
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead class="page-breadcrumb breadcrumb">
                                                                                <tr class="title-white text-center">
                                                                                    <th style="width:50%">No Order</th>
                                                                                    <th style="width:30%">Total Item</th>
                                                                                    <th style="width:20%">Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="daftar_order_elab">
                                                                                <?php if($status_elab == "done"): $noelab = 1; foreach($data_orderlab as $dolkey => $dolval):?>
                                                                                    <tr id='order_elab_tr<?= $noelab ?>'>
                                                                                    <td><input type='text' class='form-control input-large' id='row_noelab<?= $noelab ?>' value='<?= $dolval->orderno ?>' readonly></td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                            $query_count_elab   = $this->db->query("SELECT * FROM tbl_elab WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->num_rows();
                                                                                            echo $query_count_elab ." item";
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class='text-center'>
                                                                                        <button class='btn blue' onclick='openOrderElab(<?= $noelab ?>)' type='button'><i class='fa fa-eye'></i></button>&nbsp;
                                                                                        <button class='btn red' onclick='hapusBarisOrderElab(<?= $noelab ?>)' type='button'><i class='fa fa-trash'></i></button></td>
                                                                                    </tr>
                                                                                <?php $noelab++; endforeach; endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php if($status_kasir == 0){ ?>
                                                                        <div style="margin-top:15px">
                                                                            <button type="button" class="btn green" onclick="tambah_order_elab()"><i class="fa fa-plus fa-fw"></i>&nbsp; Order Baru</button>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>

                                                                    <div id="elab-form-area">
                                                                        <?php if($status_elab == "done"): $noelab2 = 1; foreach($data_orderlab as $dolkey => $dolval):?>
                                                                            <div class='elab-form' id='elab-form<?= $noelab2 ?>'>
                                                                                <button type='button' class='btn red' onclick='closeOrderLab(<?= $noelab2 ?>)'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label for="elab_no">No E-Lab</label>
                                                                                        <input type="text" id="form_noelab<?= $noelab2 ?>" name="elab_no[]" class="form-control" value="<?= $dolval->orderno ?>" readonly>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="elab_tanggal">Tanggal</label>
                                                                                        <input type="date" id="elab_tanggal" name="elab_tanggal[]" class="form-control" value="<?= date("Y-m-d", strtotime($dolval->tglorder)) ?>">
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="elab_jam">Jam</label>
                                                                                        <input type="time" id="elab_jam" name="elab_jam[]" class="form-control" value="<?= $dolval->jamorder ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="table-responsive" style="z-index:999 !important">
                                                                                    <table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%" id="table_elab<?= $noelab2 ?>">
                                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                                            <tr class="title-white">
                                                                                                <th style="width:5%">Delete</th>
                                                                                                <th style="width:40%">Pemeriksaan</th>
                                                                                                <th style="width:25%">Harga</th>
                                                                                                <th style="width:30%">Notes</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                                $elab_list  = $this->db->query("SELECT * FROM tbl_elab WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                                $noelablist = 1;
                                                                                                foreach($elab_list as $elkey => $elval){
                                                                                            ?>
                                                                                            <tr id="elab_tr<?= $noelab2 . $noelablist ?>">
                                                                                                <td>
                                                                                                    <button type="button" class="btn red" onclick="hapusBarisElab(<?= $noelab2 . $noelablist ?>)"><i class="fa fa-trash-o"></i></button></td>
                                                                                                    <input type="hidden" id="elabtin_orderno<?= $noelab2 . $noelablist ?>" name="elabtin_orderno[]" value="<?= $dolval->orderno ?>">
                                                                                                    <input type="hidden" id="elabtin_tindakan<?= $noelab2 . $noelablist ?>" name="elabtin_tindakan[]" value="<?= $elval->tindakan ?>">
                                                                                                    <input type="hidden" id="elabtin_tarifrs<?= $noelab2 . $noelablist ?>" name="elabtin_tarifrs[]" value="<?= $elval->tarifrs ?>">
                                                                                                    <input type="hidden" id="elabtin_tarifdr<?= $noelab2 . $noelablist ?>" name="elabtin_tarifdr[]" value="<?= $elval->tarifdr ?>">
                                                                                                <td>
                                                                                                    <select type="text" class="form-control selectpicker input-medium" name="elabtin_kode[]" id="elabtin_kode<?= $noelab2 . $noelablist ?>" data-title="Pilih Tarif" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" onchange="show_tindakan_elab(this.value, <?= $noelab2 . $noelablist ?>)">
                                                                                                        <?php foreach($list_elab as $leval): ?>
                                                                                                            <?php if($leval->kodeid == $elval->kodetarif){ ?>
                                                                                                                <option value="<?= $leval->kodeid ?>" selected><?= $leval->text ?></opiton>
                                                                                                            <?php } else { ?>
                                                                                                                <option value="<?= $leval->kodeid ?>"><?= $leval->text ?></opiton>
                                                                                                            <?php } ?>
                                                                                                        <?php endforeach; ?>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td><input type="text" class="form-control" name="elabtin_harga[]" id="elabtin_harga<?= $noelab2 . $noelablist ?>" value="<?= $elval->tarifrs + $elval->tarifdr ?>" readonly></td>
                                                                                                <td><input type="text" class="form-control" name="elabtin_catatan[]" id="elabtin_catatan<?= $noelab2 . $noelablist ?>" value="<?= $elval->keterangan ?>"></td>
                                                                                            </tr>
                                                                                            <?php $noelablist++; } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div style="margin-top:15px;width:auto">
                                                                                    <button type="button" class="btn green" onclick="tambah_elab(<?= $noelab2 ?>)"><i class="fa fa-plus"></i>&nbsp; Tambah</button>
                                                                                </div>
                                                                            </div>
                                                                        <?php $noelab2++; endforeach; endif; ?>
                                                                    </div>

                                                                    <div class="row" style="margin-bottom:0px !important">
                                                                        <div class="col-md-12" style="margin-bottom:0px !important">		
                                                                            <div class="form-actions" style="margin-bottom:0px !important">
                                                                                <?php if($status_kasir == 0){ ?><button id="btnsimpanelab" type="button" onclick="save_elab()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button><?php } ?>
                                                                                <div class="btn-group">
                                                                                    <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                                </div>
                                                                            </div>							
                                                                        </div>															
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="col-sm-5">
                                                                <?php
                                                                    if($status_elab == "done"): foreach($data_orderlab as $dolkey => $dolval):
                                                                ?>
                                                                    <div id="<?= $dolval->orderno ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Detail Pemeriksaan No Order : <b><?= $dolval->orderno ?></b></h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <ul>
                                                                                    <?php
                                                                                        $modal_elab_list    = $this->db->query("SELECT * FROM tbl_elab WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                        foreach($modal_elab_list as $melval){
                                                                                            echo "<li>". $melval->kodetarif ." - ". $melval->tindakan ." - Rp ". number_format($melval->tarifrs + $melval->tarifdr, 0, ',', '.') ." - Ket : ". $melval->keterangan ."</li>";
                                                                                        }
                                                                                    ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; endif; ?>
                                                                <h4 style="color:green"><b>Data Order yang Sudah Dibuat</b></h4>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                            <tr class="title-white">
                                                                                <th>No E-Lab</th>
                                                                                <th>Pemeriksaan</th>
                                                                                <th>Status</th>
                                                                                <th>Hasil</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="daftarOrderElab">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane <?= $tab5 ?>" id="tab5">
                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <form id="frmemed" method="post">
                                                                    <h4 style="color:green"><b>E-ORDER ELEKTROMEDIS</b></h4>
                                                                    <div class="table-responsive" id="emed-table">
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead class="page-breadcrumb breadcrumb">
                                                                                <tr class="title-white text-center">
                                                                                    <th style="width:50%">No Medis</th>
                                                                                    <th style="width:30%">Unit</th>
                                                                                    <th style="width:20%">Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="daftar_order_emed">
                                                                                <?php if($status_emed == "done"): $noemed = 1; foreach($data_orderemed as $dorkey => $dorval):?>
                                                                                    <tr id='order_emed_tr<?= $noemed ?>'>
                                                                                    <td><input type='text' class='form-control input-large' id='row_noemed<?= $noemed ?>' value='<?= $dorval->orderno ?>' readonly></td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                            $get_kodepos    = $this->db->query("SELECT * FROM tbl_emedis WHERE notr = '$dorval->orderno' LIMIT 1")->row();

                                                                                            if(empty($get_kodepos)){
                                                                                                echo "-";
                                                                                            } else {
                                                                                                echo $get_kodepos->namapost;
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class='text-center'>
                                                                                        <button class='btn blue' onclick='openOrderEmed(<?= $noemed ?>)' type='button'><i class='fa fa-eye'></i></button>&nbsp;
                                                                                        <button class='btn red' onclick='hapusBarisOrderEmed(<?= $noemed ?>)' type='button'><i class='fa fa-trash'></i></button></td>
                                                                                    </tr>
                                                                                <?php $noemed++; endforeach; endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php if($status_kasir == 0){ ?>
                                                                        <div style="margin-top:15px">
                                                                            <button type="button" class="btn green" onclick="tambah_order_emed()"><i class="fa fa-plus fa-fw"></i>&nbsp; Order Baru</button>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>

                                                                    <div id="emed-form-area">
                                                                        <?php if($status_emed == "done"): $noemed2 = 1; foreach($data_orderemed as $dolkey => $dolval):?>
                                                                            <div class='emed-form' id='emed-form<?= $noemed2 ?>'>
                                                                                <button type='button' class='btn red' onclick='closeOrderRad(<?= $noemed2 ?>)'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label for="emed_no">No Medis</label>
                                                                                        <input type="text" id="form_noemed<?= $noemed2 ?>" name="emed_no[]" class="form-control" value="<?= $dolval->orderno ?>" readonly>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="emed_tanggal">Tanggal</label>
                                                                                        <input type="date" id="emed_tanggal" name="emed_tanggal[]" class="form-control" value="<?= date("Y-m-d", strtotime($dolval->tglorder)) ?>">
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="emed_jam">Jam</label>
                                                                                        <input type="time" id="emed_jam" name="emed_jam[]" class="form-control" value="<?= $dolval->jamorder ?>">
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <?php
                                                                                            $get_kodepos    = $this->db->query("SELECT * FROM tbl_emedis WHERE notr = '$dolval->orderno' LIMIT 1")->row();
                                                                                        ?>
                                                                                        <label for="emed_unit">Unit</label>
                                                                                        <select type="text" class="form-control selectpicker input-medium" name="emed_unit[]" id="emed_unit<?= $noemed2 ?>" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                                                                            <option value="-">--- Pilih Unit ---</option>
                                                                                            <?php foreach($emed_unit as $euval): ?>
                                                                                                <?php if(isset($get_kodepos->kodepos)): if($euval->id == $get_kodepos->kodepos): ?>
                                                                                                    <option value="<?= $euval->id ?>" selected><?= $euval->text ?></opiton>
                                                                                                <?php else: ?>
                                                                                                    <option value="<?= $euval->id ?>"><?= $euval->text ?></opiton>
                                                                                                <?php endif; else: ?>
                                                                                                    <option value="<?= $euval->id ?>"><?= $euval->text ?></opiton>
                                                                                                <?php endif; ?>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="table-responsive" style="z-index:999 !important">
                                                                                    <table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%" id="table_emed<?= $noemed2 ?>">
                                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                                            <tr class="title-white">
                                                                                                <th style="width:5%">Delete</th>
                                                                                                <th style="width:40%">Pemeriksaan</th>
                                                                                                <th style="width:25%">Harga</th>
                                                                                                <th style="width:30%">Notes</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                                $emed_list  = $this->db->query("SELECT * FROM tbl_emedis WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                                $noemedlist = 1;
                                                                                                foreach($emed_list as $elkey => $elval){
                                                                                            ?>
                                                                                            <tr id="emed_tr<?= $noemed2 . $noemedlist ?>">
                                                                                                <td>
                                                                                                    <button type="button" class="btn red" onclick="hapusBarisEmed(<?= $noemed2 . $noemedlist ?>)"><i class="fa fa-trash-o"></i></button></td>
                                                                                                    <input type="hidden" id="emedtin_orderno<?= $noemed2 . $noemedlist ?>" name="emedtin_orderno[]" value="<?= $dolval->orderno ?>">
                                                                                                    <input type="hidden" id="emedtin_tindakan<?= $noemed2 . $noemedlist ?>" name="emedtin_tindakan[]" value="<?= $elval->tindakan ?>">
                                                                                                    <input type="hidden" id="emedtin_tarifrs<?= $noemed2 . $noemedlist ?>" name="emedtin_tarifrs[]" value="<?= $elval->tarifrs ?>">
                                                                                                    <input type="hidden" id="emedtin_tarifdr<?= $noemed2 . $noemedlist ?>" name="emedtin_tarifdr[]" value="<?= $elval->tarifdr ?>">
                                                                                                <td>
                                                                                                    <select type="text" class="select2_el_tarif_erad form-control input-medium" name="emedtin_kode[]" id="emedtin_kode<?= $noemed2 . $noemedlist ?>" onchange="show_tindakan_emed(this.value, <?= $noemed2 . $noemedlist ?>)">
                                                                                                        <option value="<?= $elval->kodetarif ?>">
                                                                                                            <?php
                                                                                                                echo "[ ". data_master("tbl_tarifh", array("kodetarif" => $elval->kodetarif, "kodepos" => $elval->kodepos))->kodetarif ." ] - [ ". data_master("tbl_tarifh", array("kodetarif" => $elval->kodetarif, "kodepos" => $elval->kodepos))->tindakan ."]";
                                                                                                            ?>
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td><input type="text" class="form-control" name="emedtin_harga[]" id="emedtin_harga<?= $noemed2 . $noemedlist ?>" value="<?= number_format($elval->tarifrs + $elval->tarifdr, 2, '.', ',') ?>" readonly></td>
                                                                                                <td><input type="text" class="form-control" name="emedtin_catatan[]" id="emedtin_catatan<?= $noemed2 . $noemedlist ?>" value="<?= $elval->keterangan ?>"></td>
                                                                                            </tr>
                                                                                            <?php $noemedlist++; } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div style="margin-top:15px;width:auto">
                                                                                    <button type="button" class="btn green" onclick="tambah_emed(<?= $noemed2 ?>)"><i class="fa fa-plus"></i>&nbsp; Tambah</button>
                                                                                </div>
                                                                            </div>
                                                                            <?php if(isset($get_kodepos->kodepos)): ?>
                                                                                <script>
                                                                                    $(window).on("load", function(){
                                                                                        initailizeSelect2_tarif_erad("<?= $get_kodepos->kodepos ?>");
                                                                                    });
                                                                                </script>
                                                                            <?php endif; ?>
                                                                        <?php $noemed2++; endforeach; endif; ?>
                                                                    </div>

                                                                    <div class="row" style="margin-bottom:0px !important">
                                                                        <div class="col-md-12" style="margin-bottom:0px !important">		
                                                                            <div class="form-actions" style="margin-bottom:0px !important">
                                                                                <?php if($status_kasir == 0){ ?><button id="btnsimpanemed" type="button" onclick="save_emed()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button><?php } ?>
                                                                                <div class="btn-group">
                                                                                    <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                                </div>
                                                                            </div>							
                                                                        </div>															
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="col-sm-5">
                                                                <?php
                                                                    if($status_emed == "done"): foreach($data_orderemed as $dolkey => $dolval):
                                                                ?>
                                                                    <div id="<?= $dolval->orderno ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Detail Pemeriksaan No Medis : <b><?= $dolval->orderno ?></b></h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <ul>
                                                                                    <?php
                                                                                        $modal_emedis_list    = $this->db->query("SELECT * FROM tbl_emedis WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                        foreach($modal_emedis_list as $melval){
                                                                                            echo "<li>". $melval->kodetarif ." - ". $melval->tindakan ." - Rp ". number_format($melval->tarifrs + $melval->tarifdr, 0, ',', '.') ." - Ket : ". $melval->keterangan ."</li>";
                                                                                        }
                                                                                    ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; endif; ?>
                                                                <h4 style="color:green"><b>Data Order yang Sudah Dibuat</b></h4>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                            <tr class="title-white">
                                                                                <th>No Medis</th>
                                                                                <th>Pemeriksaan</th>
                                                                                <th>Status</th>
                                                                                <th>Hasil</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="daftarOrderEmed">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane <?= $tab6 ?>" id="tab7">
                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <form id="frmerad" method="post">
                                                                    <h4 style="color:green"><b>E-ORDER RADIOLOGI</b></h4>
                                                                    <div class="table-responsive" id="erad-table">
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead class="page-breadcrumb breadcrumb">
                                                                                <tr class="title-white text-center">
                                                                                    <th style="width:80%">No E-Radiologi</th>
                                                                                    <th style="width:20%">Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="daftar_order_erad">
                                                                                <?php if($status_erad == "done"): $noerad = 1; foreach($data_ordererad as $dorkey => $dorval):?>
                                                                                    <tr id='order_erad_tr<?= $noerad ?>'>
                                                                                        <td><input type='text' class='form-control input-large' id='row_noerad<?= $noerad ?>' value='<?= $dorval->orderno ?>' readonly></td>
                                                                                        <td class='text-center'>
                                                                                            <button class='btn blue' onclick='openOrderErad(<?= $noerad ?>)' type='button'><i class='fa fa-eye'></i></button>&nbsp;
                                                                                            <button class='btn red' onclick='hapusBarisOrderErad(<?= $noerad ?>)' type='button'><i class='fa fa-trash'></i></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php $noerad++; endforeach; endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php if($status_kasir == 0){ ?>
                                                                        <div style="margin-top:15px">
                                                                            <button type="button" class="btn green" onclick="tambah_order_erad()"><i class="fa fa-plus fa-fw"></i>&nbsp; Order Baru</button>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>

                                                                    <div id="erad-form-area">
                                                                        <?php if($status_erad == "done"): $noerad2 = 1; foreach($data_ordererad as $dolkey => $dolval):?>
                                                                            <div class='erad-form' id='erad-form<?= $noerad2 ?>'>
                                                                                <button type='button' class='btn red' onclick='closeOrderErad(<?= $noerad2 ?>)'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label for="erad_no">No E-Radiologi</label>
                                                                                        <input type="text" id="form_noerad<?= $noerad2 ?>" name="erad_no[]" class="form-control" value="<?= $dolval->orderno ?>" readonly>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="erad_tanggal">Tanggal</label>
                                                                                        <input type="date" id="erad_tanggal" name="erad_tanggal[]" class="form-control" value="<?= date("Y-m-d", strtotime($dolval->tglorder)) ?>">
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="erad_jam">Jam</label>
                                                                                        <input type="time" id="erad_jam" name="erad_jam[]" class="form-control" value="<?= $dolval->jamorder ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="table-responsive" style="z-index:999 !important">
                                                                                    <table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%">
                                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                                            <tr class="title-white">
                                                                                                <th style="width:5%">Delete</th>
                                                                                                <th style="width:40%">Pemeriksaan</th>
                                                                                                <th style="width:25%">Harga</th>
                                                                                                <th style="width:30%">Notes</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="table_erad<?= $noerad2 ?>">
                                                                                            <?php
                                                                                                $erad_list  = $this->db->query("SELECT * FROM tbl_eradio WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                                $noeradlist = 1;
                                                                                                foreach($erad_list as $elkey => $elval){
                                                                                            ?>
                                                                                            <tr id="erad_tr<?= $noerad2 . $noeradlist ?>">
                                                                                                <td>
                                                                                                    <button type="button" class="btn red" onclick="hapusBarisErad(<?= $noerad2 . $noeradlist ?>)"><i class="fa fa-trash-o"></i></button></td>
                                                                                                    <input type="hidden" id="eradtin_orderno<?= $noerad2 . $noeradlist ?>" name="eradtin_orderno[]" value="<?= $dolval->orderno ?>">
                                                                                                    <input type="hidden" id="eradtin_tindakan<?= $noerad2 . $noeradlist ?>" name="eradtin_tindakan[]" value="<?= $elval->tindakan ?>">
                                                                                                    <input type="hidden" id="eradtin_tarifrs<?= $noerad2 . $noeradlist ?>" name="eradtin_tarifrs[]" value="<?= $elval->tarifrs ?>">
                                                                                                    <input type="hidden" id="eradtin_tarifdr<?= $noerad2 . $noeradlist ?>" name="eradtin_tarifdr[]" value="<?= $elval->tarifdr ?>">
                                                                                                <td>
                                                                                                    <select type="text" class="select2_el_tarif_erad2 form-control input-medium" name="eradtin_kode[]" id="eradtin_kode<?= $noerad2 . $noeradlist ?>" onchange="show_tindakan_erad(this.value, <?= $noerad2 . $noeradlist ?>)">
                                                                                                        <option value="<?= $elval->kodetarif ?>">
                                                                                                            <?php
                                                                                                                echo "[ ". data_master("tbl_tarifh", array("kodetarif" => $elval->kodetarif, "kodepos" => "RADIO"))->kodetarif ." ] - [ ". data_master("tbl_tarifh", array("kodetarif" => $elval->kodetarif, "kodepos" => "RADIO"))->tindakan ."]";
                                                                                                            ?>
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td><input type="text" class="form-control" name="eradtin_harga[]" id="eradtin_harga<?= $noerad2 . $noeradlist ?>" value="<?= number_format($elval->tarifrs + $elval->tarifdr, 2, '.', ',') ?>" readonly></td>
                                                                                                <td><input type="text" class="form-control" name="eradtin_catatan[]" id="eradtin_catatan<?= $noerad2 . $noeradlist ?>" value="<?= $elval->keterangan ?>"></td>
                                                                                            </tr>
                                                                                            <?php $noeradlist++; } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div style="margin-top:15px;width:auto">
                                                                                    <button type="button" class="btn green" onclick="tambah_erad(<?= $noerad2 ?>)" id="tambahErad<?= $noerad2 ?>"><i class="fa fa-plus"></i>&nbsp; Tambah</button>
                                                                                </div>
                                                                            </div>
                                                                        <?php $noerad2++; endforeach; endif; ?>
                                                                    </div>

                                                                    <div class="row" style="margin-bottom:0px !important">
                                                                        <div class="col-md-12" style="margin-bottom:0px !important">		
                                                                            <div class="form-actions" style="margin-bottom:0px !important">
                                                                                <?php if($status_kasir == 0){ ?><button id="btnsimpanerad" type="button" onclick="save_erad()" class="btn blue"><i class="fa fa-save"></i><b> Simpan</b></button><?php } ?>
                                                                                <div class="btn-group">
                                                                                    <a class="btn red" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                                                                </div>
                                                                            </div>							
                                                                        </div>															
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="col-sm-5">
                                                                <?php
                                                                    if($status_erad == "done"): foreach($data_ordererad as $dolkey => $dolval):
                                                                ?>
                                                                    <div id="<?= $dolval->orderno ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Detail Pemeriksaan No Medis : <b><?= $dolval->orderno ?></b></h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <ul>
                                                                                    <?php
                                                                                        $modal_erad_list  = $this->db->query("SELECT * FROM tbl_eradio WHERE notr = '$dolval->orderno' AND noreg = '$dolval->noreg'")->result();
                                                                                        foreach($modal_erad_list as $melval){
                                                                                            echo "<li>". $melval->kodetarif ." - ". $melval->tindakan ." - Rp ". number_format($melval->tarifrs + $melval->tarifdr, 0, ',', '.') ." - Ket : ". $melval->keterangan ."</li>";
                                                                                        }
                                                                                    ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; endif; ?>
                                                                <h4 style="color:green"><b>Data Order yang Sudah Dibuat</b></h4>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead class="page-breadcrumb breadcrumb">
                                                                            <tr class="title-white">
                                                                                <th>No E-Radiologi</th>
                                                                                <th>Pemeriksaan</th>
                                                                                <th>Status</th>
                                                                                <th>Hasil</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="daftarOrderErad">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab6">
                                                    <h4 style="color:green;margin:auto auto 20px auto;position:relative">
                                                        <span style="position:absolute;right:0;font-weight:bold">Total : <?= number_format($total_hispas, 0, ',', '.') ." Kunjungan" ?></span>
                                                        <b>History Kunjungan</b>
                                                    </h4>
                                                    <div class="table-responsive">
                                                        <table id="datatable_hispas" class="table table-bordered table-striped table-condensed table-scrollable">
                                                            <thead class="page-breadcrumb breadcrumb">
                                                                <tr class="title-white">
                                                                    <th>Asal</th>
                                                                    <th>Tanggal</th>
                                                                    <th>Poli</th>
                                                                    <th>Dokter</th>
                                                                    <th>Keluhan Awal</th>
                                                                    <th>Pemeriksaan</th>
                                                                    <th>Tindakan</th>
                                                                    <th>Diagnosa Masuk</th>
                                                                    <th>Diagnosa ICD</th>
                                                                    <th>Terapi</th>
                                                                    <th>Saran Anjuran</th>
                                                                    <th>Resep/Real</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if($status_hispas == "undone"): ?>
                                                                <?php else: foreach($data_hispas as $dhkey => $dhval): ?>
                                                                    <div id="resepDetail<?= $dhval->id ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times fa-2x"></i></button>
                                                                                    <h4 class="modal-title">Detail Resep/Real</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <?php
                                                                                        $query_dhhr = $this->db->query("SELECT * FROM tbl_apohresep WHERE noreg = '$dhval->noreg' AND koders = '$dhval->koders'");
                                                                                        if($query_dhhr->num_rows() != 0){
                                                                                            $data_dhhr  = $query_dhhr->row();
                                                                                            $query_dhdr = $this->db->query("SELECT * FROM tbl_apodresep WHERE eresepno = '$data_dhhr->eresepno'");
                                                                                            echo "<ul>";
                                                                                            foreach($query_dhdr->result() as $dhdrval){
                                                                                                echo "<li>". $dhdrval->namabarang ." - ". number_format($dhdrval->qty, 0, ',', '.') ." - ". $dhdrval->satuan ."</li>";
                                                                                            }
                                                                                            echo "</ul>";
                                                                                        } else {
                                                                                            echo "<div style='padding:10px 0px 10px 0px;text-align:center;font-weight:bold'>Belum Ada Resep</div>";
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="detailDiagnosa<?= $dhval->id ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times fa-2x"></i></button>
                                                                                    <h4 class="modal-title">Detail Diagnosa ICD</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <?php
                                                                                        $query_dhicd = $this->db->query("SELECT * FROM tbl_icdtr WHERE noreg = '$dhval->noreg' AND koders = '$dhval->koders'");
                                                                                        if($query_dhicd->num_rows() != 0){
                                                                                            echo "<ul>";
                                                                                            foreach($query_dhicd->result() as $dhicdval){
                                                                                                echo "<li>". $dhicdval->icdcode ." - ". data_master("tbl_icdinb", array("code" => $dhicdval->icdcode))->str ."</li>";
                                                                                            }
                                                                                            echo "</ul>";
                                                                                        } else {
                                                                                            echo "<div style='padding:10px 0px 10px 0px;text-align:center;font-weight:bold'>Belum Ada Diagnosa ICD</div>";
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                                $dhasal = data_master("tbl_namers", array("koders" => $dhval->koders))->namars;
                                                                                echo $dhasal;
                                                                            ?>
                                                                        </td>
                                                                        <td><?= date("d-m-Y", strtotime($dhval->tglperiksa)) ?></td>
                                                                        <td>
                                                                            <?php
                                                                                $dhpoli = data_master("tbl_namapos", array("kodepos" => $dhval->kodepos))->namapost;
                                                                                echo $dhpoli;
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                                // $dhgpas = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE AND koders = '". $this->session->userdata("unit") ."'")->row();
                                                                                if($dhval->kodokter == "" || $dhval->kodokter == null){
                                                                                    echo "-";
                                                                                } else {
                                                                                    // $dhdok  = data_master("tbl_dokter", array("kodokter" => $dhval->kodokter, "koders" => $dhval->koders))->nadokter;
                                                                                    $check_dokter__hispas = $this->db->query("SELECT * FROM dokter WHERE kodokter = '$dhval->kodokter' AND koders = '$dhval->koders' AND kopoli = '$dhval->kodepos'")->num_rows();
                                                                                    if($check_dokter__hispas == 0){
                                                                                        echo "-";
                                                                                    } else {
                                                                                        echo data_master("dokter", array("kodokter" => $dhval->kodokter, "koders" => $dhval->koders, "kopoli" => $dhval->kodepos))->nadokter;
                                                                                    }
                                                                                }                                                                                
                                                                            ?>
                                                                        </td>
                                                                        <td><?= $dhval->keluhanawal ?></td>
                                                                        <td><?= $dhval->pfisik ?></td>
                                                                        <td><?= $dhval->tindu ?></td>
                                                                        <td><?= $dhval->diagnosa ?></td>
                                                                        <td><button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#detailDiagnosa<?= $dhval->id ?>">Detail</button></td>
                                                                        <td><?= $dhval->resep ?></td>
                                                                        <td><?= $dhval->anjuran ?></td>
                                                                        <td><button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#resepDetail<?= $dhval->id ?>">Detail</button></td>
                                                                    </tr>
                                                                <?php endforeach; endif; ?>
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
                </div>    
            </div>

        </div>
    </div>

<!-- <br></div></div></div>   </form> </div></div> --><!-- </div> -->

<br />

<?php
    // $this->load->view('template/footer');  
?>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_periode');
?>

<!-- <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script> -->
<!-- <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script> -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

<script>
    // PCARE JS //

    function error_alert(message){
        return swal({
            title: "POLIKLINIK",
            html: message,
            type: "error",
            confirmButtonText: "Tutup", 
            confirmButtonColor: "red"
        });
    }

    function pcare_diag_trigger(param){
        if(param == "" || param == null){
            console.error("ICD error : failed to trigger icd diagnostics for pcare, undefined icd code");
        } else {

            $.ajax({
                url: "/poliklinik/get_icd_for_pcare/"+ param,
                type: "GET",
                dataType: "JSON",
                success: function(res){
                    if(res.status == "success"){
                        $("#jenis_diagnosa1").val(param);
                        $("#diagnosa1").val(res.string);
                    } else 
                    if(res.status == "error"){
                        error_alert(res.message);
                    } else {
                        error_alert("results have been issued<br />but undefined result status");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    error_alert("failed to trigger icd diagnostics for pcare (client)");
                }
            })

        }
    }

    // PCARE JS //
    
    <?php if(isset($_GET["tab"])){ if($_GET["tab"] == "elab" || $_GET["tab"] == "rad" || $_GET["tab"] == "erm"){ ?>
        var $target = $('html,body'); 
        $target.animate({scrollTop: $target.height()}, 1000);
    <?php } } ?>

    $("#selectdr").on("change", function(){
        var data_post   = {
            kodokter: $(this).val(),
            noreg: '<?= $this->input->get("noreg") ?>',
            kodepos: '<?= $polipas ?>'
        };
        $.ajax({
            url: "/poliklinik/plklnkdrpoli/",
            data: data_post,
            type: "POST",
            dataType: "JSON",
            beforeSend: function(){
                $(".loadingdr").attr("style", "display:block !important");
            },
            success: function(data){
                $("#doktertitle").html("<b>"+ data.dokter +"</b>");
                $(".loadingdr").attr("style", "display:none !important");
                window.location.reload();
            },
            error: function(){
                $("#doktertitle").html("<b>Undefined</b>");
                $(".loadingdr").attr("style", "display:none !important");
                window.location.reload();
            }
        });
    });

    $("#btneresep").on("click", function(){
        var postt   = $("#testeresep").serialize();
        console.log(postt);
    });
    
    var wrapper    = document.getElementById("signature-pad"),
    clearButton    = wrapper.querySelector("[data-action=clear]"),
    saveButton     = wrapper.querySelector("[data-action=save]"),
    canvas         = wrapper.querySelector("canvas"),
    signaturePad;

    var wrapper   = document.getElementById("signature-pad2"),
    clearButton2  = wrapper.querySelector("[data-action=clear2]"),
    saveButton2   = wrapper.querySelector("[data-action=save2]"),
    canvas2       = wrapper.querySelector("canvas"),
    signaturePad2;

    function _urlcetak()
    {	
        var unit    = '<?= $this->session->userdata('unit') ?>';
        var noreg   = '<?= $this->input->get('noreg') ?>';
        var rekmed  = '<?= $this->input->get('rekmed') ?>';
        var baseurl = "<?php echo base_url()?>";
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>Poliklinik/bersihkan_ttd",
            success: function(data){
            
            if (signaturePad.isEmpty()) {

                $("#errors").addClass('shake');
                $("#errors").show();
                $("#errors").delay(4000).hide(200, function() {
                    $("#errors").hide();
                });
                $('#errors').html('Isi Tanda Tangan Dahulu');

                } else {

                $('#error').html('');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>Poliklinik/simpan_ttd",
                    data: {image: signaturePad.toDataURL()},
                    success: function(data){
                        signaturePad.clear();
                        var var1    = baseurl+'poliklinik/cetak_suket/'+unit+'/'+noreg+'/'+rekmed+'/'+data+'/sakit';

                        window.open(var1,'_blank');

                    }
                });
            }
            
            }
        });


        // window.open('/poliklinik/cetak_suket/<?= $this->session->userdata('unit') ?>/<?= $this->input->get('noreg') ?>/<?= $this->input->get('rekmed') ?>/sakit', 'blank')


    }

    function _urlcetak2()
    {	
        var unit    = '<?= $this->session->userdata('unit') ?>';
        var noreg   = '<?= $this->input->get('noreg') ?>';
        var rekmed  = '<?= $this->input->get('rekmed') ?>';
        var baseurl = "<?php echo base_url()?>";

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>Poliklinik/bersihkan_ttd",
            success: function(data){

            if (signaturePad2.isEmpty()) {

                $("#errors").addClass('shake');
                $("#errors").show();
                $("#errors").delay(4000).hide(200, function() {
                    $("#errors").hide();
                });
                $('#errors').html('Isi Tanda Tangan Dahulu');

            } else {

                $('#error').html('');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>Poliklinik/simpan_ttd",
                    data: {image: signaturePad2.toDataURL()},
                    success: function(data){
                        signaturePad2.clear();
                        var var1    = baseurl+'poliklinik/cetak_suket/'+unit+'/'+noreg+'/'+rekmed+'/'+data+'/sehat';

                        window.open(var1,'_blank');

                    }
                });
            }

            }
        });

        // window.open('/poliklinik/cetak_suket/<?= $this->session->userdata('unit') ?>/<?= $this->input->get('noreg') ?>/<?= $this->input->get('rekmed') ?>/sakit', 'blank')


    }

    signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
		signaturePad.clear();
	});

    signaturePad2 = new SignaturePad(canvas2);
	clearButton2.addEventListener("click", function (event) {
		signaturePad2.clear();
	});
    
    // INITIALIZE
    $(window).on("load", function(){
        console.log($("#testeresep").serialize());
        var gudangstart = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(''+gudangstart+'');

        <?php if($eresep == "undone"): ?>
        last_eresep();
        <?php endif; ?>
        
        var gudang_bhp  = $("#gudang_bhp").val();
        select2_el_alkes(gudang_bhp);

        get_elab_order();
        get_emed_order();
        get_erad_order();
        initailizeSelect2_tarif_erad2();

        //

        initailizeSelect2_dokter('<?= $polipas ?>');
        initailizeSelect2_perawat('<?= $polipas ?>');
    });

    <?php if($statusicd == "undone"): ?>
        var idrowDiag   = 2;
    <?php else: ?>
        var idrowDiag   = <?= $jumdataicd+1 ?>;
    <?php endif; ?>

    <?php if($statusbill == "undone"): ?>
        var idrowBill   = 2;
    <?php else: ?>
        var idrowBill   = <?= $jumdatabill+1 ?>;
    <?php endif; ?>

    <?php if($statusalkes == "undone"): ?>
        var idrowAlkes   = 2;
    <?php else: ?>
        var idrowAlkes   = <?= $jumdataalkes+1 ?>;
    <?php endif; ?>

    <?php if($status_eresep == "undone"): ?>
        var idrowObt   = 2;
    <?php else: ?>
        var idrowObt   = <?= $jumdataer+1 ?>;
    <?php endif; ?>

    <?php if($status_racik1 == "undone"): ?>
        var idrowRck1   = 2;
    <?php else: ?>
        var idrowRck1   = <?= $jumdataracik1+1 ?>;
    <?php endif; ?>
    
    <?php if($status_racik2 == "undone"): ?>
        var idrowRck2   = 2;
    <?php else: ?>
        var idrowRck2   = <?= $jumdataracik2+1 ?>;
    <?php endif; ?>

    <?php if($status_racik3 == "undone"): ?>
        var idrowRck3   = 2;
    <?php else: ?>
        var idrowRck3   = <?= $jumdataracik3+1 ?>;
    <?php endif; ?>

    <?php if($status_elab == "undone"): ?>
        var idrowElabOrder   = 1;
    <?php else: ?>
        var idrowElabOrder   = <?= $total_elab ?> + 1;
    <?php endif; ?>

    <?php if($status_emed == "undone"): ?>
        var idrowEmedOrder  = 1;
    <?php else: ?>
        var idrowEmedOrder   = <?= $total_emed ?> + 1;
    <?php endif; ?>

    <?php if($status_erad == "undone"): ?>
        var idrowEradOrder  = 1;
    <?php else: ?>
        var idrowEradOrder   = <?= $total_erad ?> + 1;
    <?php endif; ?>

    $(document).ready(function() {
        $("#datatable_hispas").DataTable({
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
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
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ]
            },
        });

        // $('#datatable_hispas_wrapper .dataTables_filter').attr("style", "margin-top:50px")
        $('#datatable_hispas_wrapper .dataTables_filter input').addClass("form-control input-medium"); // modify table search input
        $('#datatable_hispas_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        // $('#datatable_hispas_wrapper .dataTables_length').attr("style", "float:left;margin-top:20px");
        
        var tgllahire   = '<?= date("Y-m-d", strtotime($data_pas->tgllahir))?>'.substring(0, 10);
        var tglmasuk    = '<?= $data_pas->tglmasuk?>'.substring(0, 10);
        var kodpos      = '<?= $data_pas->kodepos ?>';
        var doaa        = '<?= isset($ttv->dead)? $ttv->dead : 0 ?>';
        var usia        = hitung_usia(tgllahire);

        if(doaa==1){
            $('[name="nadi"]').prop("readonly", true);
            $('[name="nafas"]').prop("readonly", true);
            $('[name="oksi"]').prop("readonly", true);
            $('[name="suhu"]').prop("readonly", true);
            $('[name="tekanan"]').prop("readonly", true);
            $('[name="tekanan1"]').prop("readonly", true);
        }
        $('#umur').val(usia);
        $('#tgl_dok').val(tglmasuk);
        initailizeSelect2_poli_tindakan(kodpos);
    });

    function get_elab_order(){
        var daftarOrderElab = $("#daftarOrderElab");
        $.ajax({
            url: "/poliklinik/data_order_elab/<?= $this->input->get("noreg") ?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                daftarOrderElab.html("<tr><td colspan='4' class='text-center'>Sedang memuat data..</td></tr>");
            },
            success: function(data){
                daftarOrderElab.html("");

                if(data.status == "null"){
                    daftarOrderElab.html("<tr><td colspan='4' class='text-center' style='color:red'>Belum Ada Data</td></tr>");
                } else 
                if(data.status == "success"){
                    $.each(data.result, function( key, value ) {
                        daftarOrderElab.append("<tr>"+
                        "<td>"+ value.orderno +"</td>"+
                        "<td class='text-center'><button type='button' class='btn blue btn-xs' data-toggle='modal' data-target='#"+ value.orderno +"'>detail</button></td>"+
                        "<td>"+ value.proses +"</td>"+
                        "<td class='text-center'><button type='button' class='btn btn-info btn-xs'>lihat</button></td>"+
                        "</tr>");
                    });
                }
            },
            error: function(){
                daftarOrderElab.html("<tr><td colspan='4' class='text-center'>Gagal memuat data..</td></tr>");
            }
        });
    }

    function get_emed_order(){
        var daftarOrderEmed = $("#daftarOrderEmed");
        $.ajax({
            url: "/poliklinik/data_order_emed/<?= $this->input->get("noreg") ?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                daftarOrderEmed.html("<tr><td colspan='4' class='text-center'>Sedang memuat data..</td></tr>");
            },
            success: function(data){
                daftarOrderEmed.html("");

                if(data.status == "null"){
                    daftarOrderEmed.html("<tr><td colspan='4' class='text-center' style='color:red'>Belum Ada Data</td></tr>");
                } else 
                if(data.status == "success"){
                    $.each(data.result, function( key, value ) {
                        daftarOrderEmed.append("<tr>"+
                        "<td>"+ value.orderno +"</td>"+
                        "<td class='text-center'><button type='button' class='btn blue btn-xs' data-toggle='modal' data-target='#"+ value.orderno +"'>detail</button></td>"+
                        "<td>"+ value.proses +"</td>"+
                        "<td class='text-center'><button type='button' class='btn btn-info btn-xs'>lihat</button></td>"+
                        "</tr>");
                    });
                }
            },
            error: function(){
                daftarOrderElab.html("<tr><td colspan='4' class='text-center'>Gagal memuat data..</td></tr>");
            }
        });
    }

    function get_erad_order(){
        var daftarOrderEmed = $("#daftarOrderErad");
        $.ajax({
            url: "/poliklinik/data_order_erad/<?= $this->input->get("noreg") ?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                daftarOrderEmed.html("<tr><td colspan='4' class='text-center'>Sedang memuat data..</td></tr>");
            },
            success: function(data){
                daftarOrderEmed.html("");

                if(data.status == "null"){
                    daftarOrderEmed.html("<tr><td colspan='4' class='text-center' style='color:red'>Belum Ada Data</td></tr>");
                } else 
                if(data.status == "success"){
                    $.each(data.result, function( key, value ) {
                        daftarOrderEmed.append("<tr>"+
                        "<td>"+ value.orderno +"</td>"+
                        "<td class='text-center'><button type='button' class='btn blue btn-xs' data-toggle='modal' data-target='#"+ value.orderno +"'>detail</button></td>"+
                        "<td>"+ value.proses +"</td>"+
                        "<td class='text-center'><button type='button' class='btn btn-info btn-xs'>lihat</button></td>"+
                        "</tr>");
                    });
                }
            },
            error: function(){
                daftarOrderElab.html("<tr><td colspan='4' class='text-center'>Gagal memuat data..</td></tr>");
            }
        });
    }

    // GET DETAIL
    function show_tindakan(str, id) {
        var vid       = id;
        var kodokter    = '<?= $data_pas->kodokter ?>';
        var nadokter    = '<?= $data_pas->nadokter ?>';
        var kodepos     = $("#poli_dok").val();
        
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_tin/"+ str +"/?kodepos="+ kodepos,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // Masalah Total
                // var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli)+eval(data.feemedispoli)+eval(data.bhppoli);
                // var totalharga  = data.cost;
                var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli)+eval(data.feemedispoli)+eval(data.bhppoli);
                $('#hrg' + vid).val(formatCurrency1(totalharga));
                $('#dokter' + vid).html("<option value='"+ kodokter +"'>"+ nadokter +"</option>");
            }
        });
    }

    function show_alkes(str, id) {
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_alkes/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                $('#satalkes' + vid).val(data.satuan1);
                $('#hrgalkes' + vid).val(formatCurrency1(data.hargabeli));
                $('#qtyalkes' + vid).val(1);
                $('#totalkes' + vid).val(formatCurrency1(1*data.hargabeli));
            }
        });
    }

    function show_obatterapi(str, id){
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_obatterapi/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#satuan_ot' + vid).val(data.satuan1);
                $('#hargaer' + vid).val(data.hargajual);
            }
        });
    }

    function getdiag(cekjns,rows){   
        if(cekjns=='DG01'){
            var sab= 'ICD10_1998';
        }else if(cekjns=='DG02'){
            var sab= 'ICD9CM_2005';
        }else{
            var sab= '';
        }
        initailizeSelect2_icdind(sab);
        
    }

    function racikan1Detail(str, id){
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_obatterapi/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#r1satuan' + vid).val(data.satuan1);
                $('#r1harga' + vid).val(formatCurrency1(data.hargajual));
            }
        });
    }

    function racikan2Detail(str, id){
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_obatterapi/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#r2satuan' + vid).val(data.satuan1);
                $('#r2harga' + vid).val(formatCurrency1(data.hargajual));
            }
        });
    }

    function racikan3Detail(str, id){
        var vid       = id;
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_obatterapi/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#r3satuan' + vid).val(data.satuan1);
                $('#r3harga' + vid).val(formatCurrency1(data.hargajual));
            }
        });
    }

    function show_tindakan_elab(str, id) {
        var vid     = id;
        var kodepos = $("#poli_dok").val();
        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_lab/"+ str +"/?kopoli="+ kodepos,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli);
                $('#elabtin_harga' + vid).val(formatCurrency1(totalharga));
                $('#elabtin_tindakan' + vid).val(data.tindakan);
                $('#elabtin_tarifrs' + vid).val(data.tarifrspoli);
                $('#elabtin_tarifdr' + vid).val(data.tarifdrpoli);
            }
        });
    }

    function show_tindakan_emed(str, id) {
        var vid       = id;
        var kodepos     = $("#poli_dok").val();

        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_med/"+ str +"/?kodepos=" + kodepos,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli);
                $('#emedtin_harga' + vid).val(formatCurrency1(totalharga));
                $('#emedtin_tindakan' + vid).val(data.tindakan);
                $('#emedtin_tarifrs' + vid).val(data.tarifrspoli);
                $('#emedtin_tarifdr' + vid).val(data.tarifdrpoli);
            }
        });
    } 

    function show_tindakan_erad(str,id){
        var vid       = id;
        var kodepos     = $("#poli_dok").val();

        $.ajax({
            url: "<?php echo base_url();?>Poliklinik/getpoli_rad/"+ str +"/?kodepos=" + kodepos,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var totalharga=eval(data.tarifrspoli)+eval(data.tarifdrpoli);
                $('#eradtin_harga' + vid).val(formatCurrency1(totalharga));
                $('#eradtin_tindakan' + vid).val(data.tindakan);
                $('#eradtin_tarifrs' + vid).val(data.tarifrspoli);
                $('#eradtin_tarifdr' + vid).val(data.tarifdrpoli);
            }
        });
    }

    // ELEMENT TRIGGER
    function c_ceknyeri(cekjns){   
        if(cekjns==1){
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==2){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==3){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==4){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==5){
            $('#ceknyeri1').prop('checked', false);
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri6').prop('checked', false);
        }else if(cekjns==6){
            $('#ceknyeri2').prop('checked', false);
            $('#ceknyeri3').prop('checked', false);
            $('#ceknyeri4').prop('checked', false);
            $('#ceknyeri5').prop('checked', false);
            $('#ceknyeri1').prop('checked', false);
        }else{
            swal({
                title: "Perasaan Nyeri",
                html: "<p>HARUS DI PILIH !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
            return;
        } 
        
    }
    
    function cekbbn(str, id){   
        var vid   = id;
        
        var cekbbn2 = $('#bbn' + vid).is(':checked');
        if(cekbbn2===true){
            $('#bbn_hide' + vid).val(1);
        }else{
            $('#bbn_hide' + vid).val(0);
        }
        
    }

    function cekkronis(str, id){   
        var vid   = id;
        
        var cekkronis = $('#kronis' + vid).is(':checked');
        if(cekkronis===true){
            $('#kronis_hide' + vid).val(1);
        }else{
            $('#kronis_hide' + vid).val(0);
        }
        
    }

    function cekutm(str, id){   
        var vid   = id;
        
        var cekutm2 = $('#utama' + vid).is(':checked');
        if(cekutm2===true){
            $('#utama_hide' + vid).val(1);
        }else{
            $('#utama_hide' + vid).val(0);
        }
        
    }

    function setkodebarang() {
        var gudang = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(gudang);
    }

    function last_eresep() {
        $.ajax({
            url: "/Poliklinik/get_last_number/",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                <?php if($eresep == "undone"): ?>
                $("#noeresep").val("Auto");
                $("#noeresephide").val(data.noresep);
                <?php else: ?>
                $("#noeresep").val('<?= $orderperiksa->orderno ?>');
                <?php endif; ?>
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                console.error(data.noresep);
            }
        });
    }

    function last_elab(param){
        $.ajax({
            url: "/Poliklinik/get_last_number_elab/",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                $("#row_noelab"+param).val("Tunggu...");
                $("#form_noelab"+param).val("Tunggu...");
                $("#elabtin_orderno"+param).val("Tunggu...");
                $("#btnsimpanelab").prop("disabled", true);
            },
            success: function(data) {
                $("#row_noelab"+param).val("No order sedang dibuat...");
                setTimeout(() => {
                    $("#row_noelab"+param).val(data.noelab);
                    $("#form_noelab"+param).val(data.noelab);
                    $("#elabtin_orderno"+param).val(data.noelab);
                    $("#btnsimpanelab").prop("disabled", false);
                    save_elab("front");
                }, 1000);
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                $("#row_noelab"+param).val("Gagal Memuat...");
                $("#form_noelab"+param).val("Gagal Memuat...");
                $("#elabtin_orderno"+param).val("Gagal Memuat...");
                $("#btnsimpanelab").prop("disabled", true);
            }
        });
    }

    function last_emed(param){
        $.ajax({
            url: "/Poliklinik/get_last_number_emed/",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                $("#row_noemed"+param).val("Tunggu...");
                $("#form_noemed"+param).val("Tunggu...");
                $("#emedtin_orderno"+param).val("Tunggu...");
                $("#btnsimpanemed").prop("disabled", true);
            },
            success: function(data) {
                $("#row_noemed"+param).val("No order sedang dibuat...");
                setTimeout(() => {
                    $("#row_noemed"+param).val(data.noemed);
                    $("#form_noemed"+param).val(data.noemed);
                    $("#emedtin_orderno"+param).val(data.noemed);
                    $("#btnsimpanemed").prop("disabled", false);
                    save_emed("front");
                }, 1000);
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                $("#row_noemed"+param).val("Gagal Memuat...");
                $("#form_noemed"+param).val("Gagal Memuat...");
                $("#emedtin_orderno"+param).val("Gagal Memuat...");
                $("#btnsimpanemed").prop("disabled", true);
            }
        });
    }

    function last_erad(param){
        $.ajax({
            url: "/Poliklinik/get_last_number_erad/",
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                $("#row_noerad"+param).val("Tunggu...");
                $("#form_noerad"+param).val("Tunggu...");
                $("#eradtin_orderno"+param).val("Tunggu...");
                $("#btnsimpanerad").prop("disabled", true);
            },
            success: function(data) {
                $("#row_noerad"+param).val("No order sedang dibuat...");
                setTimeout(() => {
                    $("#row_noerad"+param).val(data.noerad);
                    $("#form_noerad"+param).val(data.noerad);
                    $("#eradtin_orderno"+param).val(data.noerad);
                    $("#btnsimpanerad").prop("disabled", false);
                    save_erad("front");
                }, 1000);
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                $("#row_noerad"+param).val("Gagal Memuat...");
                $("#form_noerad"+param).val("Gagal Memuat...");
                $("#eradtin_orderno"+param).val("Gagal Memuat...");
                $("#btnsimpanerad").prop("disabled", true);
            }
        });
    }

    // DATATABLE

    function tambahdiag(){
        var kodpos    = '<?= $data_pas->kodepos ?>';
        var table   = $("#diagnosa_row");

        table.append("<tr id='diagnosa_tr"+ idrowDiag +"'>"+
        "<td><button type='button' onclick=hapusBaris_diagnosa("+idrowDiag+") class='btn red  justify'><i class='fa fa-trash-o'></i></button></td>"+
        "<td><select name='jenis_diag[]' id=jenis_diag"+idrowDiag+" class='select2_el_jnsicd form-control input-largex' onchange='getdiag(this.value,"+idrowDiag+")'></select></td>"+
        "<td><select name='diag[]' id=diag"+idrowDiag+" class='select2_el_icdind form-control input-largex' onchange='pcare_diag_trigger(this.value)'> </select></td>"+
        "<td><input name='utama[]' id=utama"+idrowDiag+" type='checkbox' class='form-control' onclick='cekutm(this.value,"+idrowDiag+")'>"+
        "<input name='utama_hide[]' id='utama_hide"+idrowDiag+"' type='hidden' class='form-control'></td>"+
        "</tr>");

        initailizeSelect2_jnsicd();
        idrowDiag++;
    }

    function tambah_billing(){
        var kodpos    = '<?= $data_pas->kodepos ?>';
        var table   = $("#tindakan_row");

        table.append("<tr id='tindakan_tr"+ idrowBill +"'>"+
        "<td><button type='button' onclick='hapusBaris_tindakan("+idrowBill+")' class='btn red'><i class='fa fa-trash-o'></i> </button></td></td>"+
        "<td><select name='kode[]' id=kode"+idrowBill+" class='select2_el_poli_tindakan form-control input-largex' onchange='show_tindakan(this.value, "+idrowBill+")'> </select></td>"+
        "<td><input name='hrg[]' id=hrg"+idrowBill+" class='form-control rightJustified' readonly></td>"+
        "<td><select name='dokter[]' id=dokter"+idrowBill+" type='text' class='form-control select2_el_dokter'><option value='<?= $kodokter ?>' selected><?= data_master("dokter", array("kodokter" => $kodokter, "koders" => $this->session->userdata("unit"), "kopoli" => $polipas))->nadokter ?></option></select></td>"+
        // "<td><select name='paramedis[]' id=paramedis"+idrowBill+" type='text' class='form-control select2_el_perawat'><option value='<?= $kodokter ?>' selected><?= data_master("dokter", array("kodokter" => $kodokter, "koders" => $this->session->userdata("unit"), "kopoli" => $polipas))->nadokter ?></option></select></td>"+
        "<td><select name='paramedis[]' id=paramedis"+idrowBill+" type='text' class='form-control select2_el_perawat'></select></td>"+
        "</tr>");

        initailizeSelect2_poli_tindakan(kodpos);
        initailizeSelect2_dokter('<?= $polipas ?>');
        initailizeSelect2_perawat('<?= $polipas ?>');
        idrowBill++;
    }

    function tambah_alkes(){
        var gudang  = $("#gudang_bhp").val();

        if(gudang == ""){
            swal({
                title: 'BHP & ALKES',
                html: "Gudang Harus Dipilih",
                type: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: 'red'
            })
        } else {
            var table   = $("#poli_alkes_row");

            table.append("<tr id='alkes_tr"+ idrowAlkes +"'>"+
            "<td><button type='button' onclick='hapusBaris_alkes("+idrowAlkes+")' class='btn red'><i class='fa fa-trash-o'></i> </button></td>"+
            "<td><input name='bbn[]' id=bbn"+idrowAlkes+" type='checkbox' class='form-control' onclick='cekbbn(this.value,"+idrowAlkes+")'> <input name='bbn_hide[]' value='1' id='bbn_hide"+idrowAlkes+"' type='hidden' class='form-control'></td>"+
            "<td><select name='kdalkes[]' id=kdalkes"+idrowAlkes+" class='select2_el_alkes form-control input-largex' onchange='show_alkes(this.value, "+idrowAlkes+")'> </select></td>"+
            "<td><input name='satalkes[]' id=satalkes"+idrowAlkes+" type='text' class='form-control' readonly></td>"+
            "<td><input name='qtyalkes[]' id=qtyalkes"+idrowAlkes+" type='number' class='form-control ' onkeyup='qty("+idrowAlkes+")'></td>"+
            "<td><input name='hrgalkes[]' id=hrgalkes"+idrowAlkes+" type='text' class='form-control rightJustified' readonly></td>"+
            "<td><input name='totalkes[]' id=totalkes"+idrowAlkes+" type='text' class='form-control rightJustified' readonly></td>"+
            "</tr>");

            // initailizeSelect2_farmasi_barang();
            
            var gudang_bhp  = $("#gudang_bhp").val();
            select2_el_alkes(gudang_bhp);
            idrowAlkes++;
        }
    }

    function tambah_obatterapi(){
        var table   = $("#eresep_row");

        table.append("<tr id='eresep_tr"+ idrowObt +"'>"+
        "<td><button type='button' onclick='hapusBaris_eresep("+ idrowObt +")' class='btn red'><i class='fa fa-trash-o'></i></button></td>"+
        '<td><input name="kronis[]" id="kronis'+ idrowObt +'" type="checkbox" class="form-control " onclick="cekkronis(this.value,'+ idrowObt +')"><input name="kronis_hide[]" id="kronis_hide'+ idrowObt +'" type="hidden" class="form-control" value="0"><input name="harga_ot[]" id="hargaer'+ idrowObt +'" type="hidden" value=""><input name="totalharga_ot[]" id="totalhargaer'+ idrowObt +'" type="hidden" value=""></td>'+
        '<td><select name="obat_terapi[]" id="obat_terapi'+ idrowObt +'" class="select2_el_farmasi_baranggud form-control input-medium" onchange="show_obatterapi(this.value, '+ idrowObt +')"></select></td>'+
        '<td><input name="satuan_ot[]" id="satuan_ot'+ idrowObt +'" class="form-control" readonly></td>'+
        '<td><input name="jml_obat_hari[]" id="jml_obat_hari'+ idrowObt +'" class="form-control" onkeyup="jmlobat('+ idrowObt +')" value=""></td>'+
        '<td><input name="qty_minumum[]" id="qty_minumum'+ idrowObt +'" class="form-control" onkeyup="jmlobat('+ idrowObt +')" value=""></td>'+
        '<td><input name="jml_hari[]" id="jml_hari'+ idrowObt +'" class="form-control" onkeyup="jmlobat('+ idrowObt +')" value=""></td>'+
        '<td><input name="jml_obat[]" id="jml_obat'+ idrowObt +'" class="form-control" value="" onkeyup="qtyer('+ idrowObt +')" ></td>'+
        '<td><input name="autran_pakai[]" id="autran_pakai'+ idrowObt +'" class="form-control" rows="2" style="resize:none"></td>'+
        '<td><textarea name="keterangan[]" id="keterangan'+ idrowObt +'" class="form-control" rows="2" style="resize:none"></textarea></td>'+
        "</tr>");

        var gudang  = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(""+ gudang +"");
        idrowObt++;
    }

    function tambah_racikan1(){
        var table   = $("#racikan1_row");

        table.append("<tr id='racikan1_tr"+ idrowRck1 +"'>"+
        "<td><input type='hidden' name='r1racikid' value='1'><button type='button' onclick='hapusBaris_racikan1("+ idrowRck1 +")' class='btn red'><i class='fa fa-trash-o'></i></button></td>"+
        '<td><select class="form-select select2_el_farmasi_baranggud input-large" name="r1nama_obat[]" id="r1nama_obat'+ idrowRck1 +'" onchange="racikan1Detail(this.value,'+ idrowRck1 +')"></select></td>'+
        '<td><input type="text" class="form-control" name="r1satuan[]" id="r1satuan'+ idrowRck1 +'" readonly></td>'+
        '<td><input type="number" class="form-control" name="r1qtyracik[]" id="r1qtyracik'+ idrowRck1 +'"></td>'+
        '<td><input type="number" class="form-control" name="r1qtyjual[]" id="r1qtyjual'+ idrowRck1 +'" onkeyup="qtyracik1('+ idrowRck1 +')"></td>'+
        '<td><input type="text" class="form-control" name="r1harga[]" id="r1harga'+ idrowRck1 +'"></td>'+
        '<td><input type="text" class="form-control" name="r1totalharga[]" id="r1totalharga'+ idrowRck1 +'"></td>'+
        "</tr>");

        var gudang  = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(""+ gudang +"");
        idrowRck1++;
    }

    function tambah_racikan2(){
        var table   = $("#racikan2_row");

        table.append("<tr id='racikan2_tr"+ idrowRck2 +"'>"+
        "<td><input type='hidden' name='r2racikid' value='2'><button type='button' onclick='hapusBaris_racikan2("+ idrowRck2 +")' class='btn red'><i class='fa fa-trash-o'></i></button></td>"+
        '<td><select class="form-select select2_el_farmasi_baranggud input-large" name="r2nama_obat[]" id="r2nama_obat'+ idrowRck2 +'" onchange="racikan2Detail(this.value,'+ idrowRck2 +')"></select></td>'+
        '<td><input type="text" class="form-control" name="r2satuan[]" id="r2satuan'+ idrowRck2 +'" readonly></td>'+
        '<td><input type="number" class="form-control" name="r2qtyracik[]" id="r2qtyracik'+ idrowRck2 +'"></td>'+
        '<td><input type="number" class="form-control" name="r2qtyjual[]" id="r2qtyjual'+ idrowRck2 +'" onkeyup="qtyracik2('+ idrowRck2 +')"></td>'+
        '<td><input type="text" class="form-control" name="r2harga[]" id="r2harga'+ idrowRck2 +'"></td>'+
        '<td><input type="text" class="form-control" name="r2totalharga[]" id="r2totalharga'+ idrowRck2 +'"></td>'+
        "</tr>");

        var gudang  = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(""+ gudang +"");
        idrowRck2++;
    }

    function tambah_racikan3(){
        var table   = $("#racikan3_row");

        table.append("<tr id='racikan3_tr"+ idrowRck3 +"'>"+
        "<td><input type='hidden' name='r3racikid' value='3'><button type='button' onclick='hapusBaris_racikan3("+ idrowRck3 +")' class='btn red'><i class='fa fa-trash-o'></i></button></td>"+
        '<td><select class="form-select select2_el_farmasi_baranggud input-large" name="r3nama_obat[]" id="r3nama_obat'+ idrowRck3 +'" onchange="racikan3Detail(this.value,'+ idrowRck2 +')"></select></td>'+
        '<td><input type="text" class="form-control" name="r3satuan[]" id="r3satuan'+ idrowRck3 +'" readonly></td>'+
        '<td><input type="number" class="form-control" name="r3qtyracik[]" id="r3qtyracik'+ idrowRck3 +'"></td>'+
        '<td><input type="number" class="form-control" name="r3qtyjual[]" id="r3qtyjual'+ idrowRck3 +'" onkeyup="qtyracik3('+ idrowRck3 +')"></td>'+
        '<td><input type="text" class="form-control" name="r3harga[]" id="r3harga'+ idrowRck3 +'"></td>'+
        '<td><input type="text" class="form-control" name="r3totalharga[]" id="r3totalharga'+ idrowRck3 +'"></td>'+
        "</tr>");

        var gudang  = $("#gudang").val();
        initailizeSelect2_farmasi_baranggud(""+ gudang +"");
        idrowRck3++;
    }

    function tambah_elab(param){
        var table       = $("#table_elab"+ param);
        var noelab      = $("#form_noelab"+ param).val();
        var rowcount    = $("#table_elab"+ param +" tr").length;

        var idrowElab       = rowcount;

        table.append('<tr id="elab_tr'+ param + idrowElab +'">'+
        '<td>'+
            '<button type="button" class="btn red" onclick="hapusBarisElab('+ param + idrowElab +')"><i class="fa fa-trash-o"></i></button>'+
            '<input type="hidden" name="elabtin_orderno[]" id="elabtin_orderno'+ param +  idrowElab+'" value="'+noelab+'">'+
            '<input type="hidden" id="elabtin_tindakan'+ param  + idrowElab +'" name="elabtin_tindakan[]" value="">'+
            '<input type="hidden" id="elabtin_tarifrs'+ param  + idrowElab +'" name="elabtin_tarifrs[]" value="">'+
            '<input type="hidden" id="elabtin_tarifdr'+ param  + idrowElab +'" name="elabtin_tarifdr[]" value="">'+
        '</td>'+
        "<td><select type='text' class='form-control input-medium' name='elabtin_kode[]' id='elabtin_kode"+ param + idrowElab +"' data-live-search='true' data-width='100%' onkeypress='return tabE(this,event)' onchange='show_tindakan_elab(this.value, "+ param + idrowElab +")'><option value='>--- Pilih Tindakan ---</option><?php foreach($list_elab as $leval): ?><option value='<?= $leval->kodeid ?>'><?= $leval->text ?></opiton><?php endforeach; ?></select></td>"+
        '<td><input type="text" class="form-control" name="elabtin_harga[]" id="elabtin_harga'+ param + idrowElab +'" readonly></td>'+
        '<td><input type="text" class="form-control" name="elabtin_catatan[]" id="elabtin_catatan'+ param + idrowElab +'"></td>'+
        '</tr>');

        $('#elabtin_kode'+ param + idrowElab).selectpicker();

        idrowElab++;
    }

    function tambah_emed(param){
        var table       = $("#table_emed"+ param);
        var noemed      = $("#form_noemed"+ param).val();
        var rowcount    = $("#table_emed"+ param +" tr").length;
        var unit        = $("#emed_unit"+param).val();

        var idrowEmed       = rowcount;

        table.append('<tr id="emed_tr'+ param + idrowEmed+'">'+
                        '<td>'+
                            '<button type="button" class="btn red" onclick="hapusBarisEmed('+ param + idrowEmed+')"><i class="fa fa-trash-o"></i></button>'+
                            '<input type="hidden" id="emedtin_orderno'+ param + idrowEmed+'" name="emedtin_orderno[]" value="'+noemed+'">'+
                            '<input type="hidden" id="emedtin_tindakan'+param+ idrowEmed+'" name="emedtin_tindakan[]" value="">'+
                            '<input type="hidden" id="emedtin_tarifrs'+param+ idrowEmed+'" name="emedtin_tarifrs[]" value="">'+
                            '<input type="hidden" id="emedtin_tarifdr'+param+ idrowEmed+'" name="emedtin_tarifdr[]" value="">'+
                        '</td>'+
                        '<td>'+
                            '<select type="text" class="select2_el_tarif_erad form-control input-medium" name="emedtin_kode[]" id="emedtin_kode'+ param + idrowEmed+'" onchange="show_tindakan_emed(this.value, '+ param + idrowEmed+')"></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="emedtin_harga[]" id="emedtin_harga'+ param + idrowEmed+'" readonly></td>'+
                        '<td><input type="text" class="form-control" name="emedtin_catatan[]" id="emedtin_catatan'+ param + idrowEmed+'"></td>'+
                    '</tr>');

        initailizeSelect2_tarif_erad(unit);

        idrowEmed++;
    }

    function tambah_erad(param){
        var table       = $("#table_erad"+ param);
        var noerad      = $("#form_noerad"+ param).val();
        var rowcount    = $("#table_erad"+ param +" tr").length;

        var idrowErad       = rowcount;

        table.append('<tr id="erad_tr'+ param + idrowErad+'">'+
                        '<td>'+
                            '<button type="button" class="btn red" onclick="hapusBarisErad('+ param + idrowErad+')"><i class="fa fa-trash-o"></i></button>'+
                            '<input type="hidden" id="eradtin_orderno'+ param + idrowErad+'" name="eradtin_orderno[]" value="'+noerad+'">'+
                            '<input type="hidden" id="eradtin_tindakan'+param+ idrowErad+'" name="eradtin_tindakan[]" value="">'+
                            '<input type="hidden" id="eradtin_tarifrs'+param+ idrowErad+'" name="eradtin_tarifrs[]" value="">'+
                            '<input type="hidden" id="eradtin_tarifdr'+param+ idrowErad+'" name="eradtin_tarifdr[]" value="">'+
                        '</td>'+
                        '<td>'+
                            '<select type="text" class="select2_el_tarif_erad2 form-control input-medium" name="eradtin_kode[]" id="eradtin_kode'+ param + idrowErad+'" onchange="show_tindakan_erad(this.value, '+ param + idrowErad+')"></select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control" name="eradtin_harga[]" id="eradtin_harga'+ param + idrowErad+'" readonly></td>'+
                        '<td><input type="text" class="form-control" name="eradtin_catatan[]" id="eradtin_catatan'+ param + idrowErad+'"></td>'+
                    '</tr>');

        initailizeSelect2_tarif_erad2();

        idrowErad++;
    }

    function tambah_order_elab(){
        var list_order  = $("#daftar_order_elab");
        var form_order  = $("#elab-form-area");

        list_order.append("<tr id='order_elab_tr"+ idrowElabOrder +"'>"+
        "<td><input type='text' class='form-control input-large' id='row_noelab"+ idrowElabOrder +"' value=''></td>"+
        "<td class='text-center'><span class='btn label label-success'>BARU</span></td>"+
        "<td class='text-center'>"+
            "<button class='btn blue' onclick='openOrderElab("+ idrowElabOrder +")' type='button'><i class='fa fa-eye'></i></button>&nbsp;"+
            "<button class='btn red' onclick='hapusBarisOrderElab("+ idrowElabOrder +")' type='button'><i class='fa fa-trash'></i></button></td>"+
        "</tr>");

        form_order.append("<div class='elab-form' id='elab-form"+idrowElabOrder+"'>"+
        "<button type='button' class='btn red' onclick='closeOrderLab("+idrowElabOrder+")'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />"+
        '<div class="row">'+
            '<div class="col-sm-4">'+
                '<label for="elab_no">No E-Lab</label>'+
                '<input type="text" id="form_noelab'+ idrowElabOrder +'" name="elab_no[]" class="form-control" readonly>'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="elab_tanggal">Tanggal</label>'+
                '<input type="date" id="elab_tanggal" name="elab_tanggal[]" class="form-control" value="<?= date("Y-m-d") ?>">'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="elab_jam">Jam</label>'+
                '<input type="time" id="elab_jam" name="elab_jam[]" class="form-control" value="<?= date("H:i:s") ?>">'+
            '</div>'+
        '</div>'+
        '<div class="table-responsive" style="z-index:999 !important">'+
            '<table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%" id="table_elab'+ idrowElabOrder +'">'+
                '<thead class="page-breadcrumb breadcrumb">'+
                    '<tr class="title-white">'+
                        '<th style="width:5%">Delete</th>'+
                        '<th style="width:40%">Pemeriksaan</th>'+
                        '<th style="width:25%">Harga</th>'+
                        '<th style="width:30%">Notes</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+
                    '<tr id="elab_tr'+ idrowElabOrder +'1">'+
                        '<td>'+
                            '<button type="button" class="btn red" onclick="hapusBarisElab('+ idrowElabOrder +'1)"><i class="fa fa-trash-o"></i></button>'+
                            '<input type="hidden" id="elabtin_orderno'+ idrowElabOrder +'1" name="elabtin_orderno[]" value="">'+
                            "<input type='hidden' id='elabtin_tindakan"+idrowElabOrder+"1' name='elabtin_tindakan[]' value='>"+
                            '<input type="hidden" id="elabtin_tarifrs'+idrowElabOrder+'1" name="elabtin_tarifrs[]" value="">'+
                            '<input type="hidden" id="elabtin_tarifdr'+idrowElabOrder+'1" name="elabtin_tarifdr[]" value="">'+
                        '</td>'+
                        '<td>'+
                            "<select type='text' class='form-control selectpicker input-medium' name='elabtin_kode[]' id='elabtin_kode"+ idrowElabOrder +"1' data-live-search='true' data-width='100%' onkeypress='return tabE(this,event)' onchange='show_tindakan_elab(this.value, "+ idrowElabOrder +"1)'><option value=''>--- Pilih Tindakan ---</option><?php foreach($list_elab as $leval): ?><option value='<?= $leval->kodeid ?>'><?= $leval->text ?></opiton><?php endforeach; ?></select>"+
                        '</td>'+
                '</tbody>'+
            '</table>'+
        '</div>'+
        '<div style="margin-top:15px;width:auto">'+
            '<button type="button" class="btn green" onclick="tambah_elab('+ idrowElabOrder +')" id="tambahElab'+ idrowElabOrder +'"><i class="fa fa-plus"></i>&nbsp; Tambah</button>'+
        '</div>'+
        "</div>");

        last_elab(idrowElabOrder);
        // check_order_elab();
        $('#elabtin_kode'+ idrowElabOrder +'1').selectpicker();
        idrowElabOrder++
    }

    function tambah_order_emed(){
        var list_order  = $("#daftar_order_emed");
        var form_order  = $("#emed-form-area");

        list_order.append("<tr id='order_emed_tr"+ idrowEmedOrder +"'>"+
        "<td><input type='text' class='form-control input-large' id='row_noemed"+ idrowEmedOrder +"' value=''></td>"+
        "<td class='text-center'></td>"+
        "<td class='text-center'>"+
            "<button class='btn blue' onclick='openOrderEmed("+ idrowEmedOrder +")' type='button'><i class='fa fa-eye'></i></button>&nbsp;"+
            "<button class='btn red' onclick='hapusBarisOrderEmed("+ idrowEmedOrder +")' type='button'><i class='fa fa-trash'></i></button></td>"+
        "</tr>");

        form_order.append("<div class='emed-form' id='emed-form"+idrowEmedOrder+"'>"+
        "<button type='button' class='btn red' onclick='closeOrderRad("+idrowEmedOrder+")'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />"+
        '<div class="row">'+
            '<div class="col-sm-4">'+
                '<label for="emed_no">No Medis</label>'+
                '<input type="text" id="form_noemed'+ idrowEmedOrder +'" name="emed_no[]" class="form-control" readonly>'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="emed_tanggal">Tanggal</label>'+
                '<input type="date" id="emed_tanggal" name="emed_tanggal[]" class="form-control" value="<?= date("Y-m-d") ?>">'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="emed_jam">Jam</label>'+
                '<input type="time" id="emed_jam" name="emed_jam[]" class="form-control" value="<?= date("H:i:s") ?>">'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="emed_unit">Unit</label>'+
                '<select type="text" class="form-control selectpicker input-medium" name="emed_unit[]" id="emed_unit'+ idrowEmedOrder +'" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)"><option value="-">--- Pilih Unit ---</option><?php foreach($emed_unit as $euval): ?><option value="<?= $euval->id ?>"><?= $euval->text ?></opiton><?php endforeach; ?></select>'+
            '</div>'+
        '</div>'+
        '<div class="table-responsive" style="z-index:999 !important">'+
            '<table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%">'+
                '<thead class="page-breadcrumb breadcrumb">'+
                    '<tr class="title-white">'+
                        '<th style="width:5%">Delete</th>'+
                        '<th style="width:40%">Pemeriksaan</th>'+
                        '<th style="width:25%">Harga</th>'+
                        '<th style="width:30%">Notes</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody id="table_emed'+ idrowEmedOrder +'">'+
                    // '<tr id="emed_tr'+ idrowEmedOrder +'1">'+
                    //     '<td>'+
                    //         '<button type="button" class="btn red" onclick="hapusBarisEmed('+ idrowEmedOrder +'1)"><i class="fa fa-trash-o"></i></button>'+
                    //         '<input type="hidden" id="emedtin_orderno'+ idrowEmedOrder +'1" name="emedtin_orderno[]" value="">'+
                    //         '<input type="hidden" id="emedtin_tindakan'+idrowEmedOrder+'1" name="emedtin_tindakan[]" value="">'+
                    //         '<input type="hidden" id="emedtin_tarifrs'+idrowEmedOrder+'1" name="emedtin_tarifrs[]" value="">'+
                    //         '<input type="hidden" id="emedtin_tarifdr'+idrowEmedOrder+'1" name="emedtin_tarifdr[]" value="">'+
                    //     '</td>'+
                    //     '<td>'+
                    //         '<select type="text" class="select2_el_tarif_erad form-control input-medium" name="emedtin_kode[]" id="emedtin_kode'+ idrowEmedOrder +'1" onchange="show_tindakan_emed(this.value, '+ idrowEmedOrder +'1)"></select>'+
                    //     '</td>'+
                    //     '<td><input type="text" class="form-control" name="emedtin_harga[]" id="emedtin_harga'+ idrowEmedOrder +'1" readonly></td>'+
                    //     '<td><input type="text" class="form-control" name="emedtin_catatan[]" id="emedtin_catatan'+ idrowEmedOrder +'1"></td>'+
                    // '</tr>'+
                '</tbody>'+
            '</table>'+
        '</div>'+
        '<div style="margin-top:15px;width:auto">'+
            '<button type="button" class="btn green" onclick="tambah_emed('+ idrowEmedOrder +')" id="tambahEmed'+ idrowEmedOrder +'"><i class="fa fa-plus"></i>&nbsp; Tambah</button>'+
        '</div>'+
        "</div>");

        var unit    = $("#emed_unit"+ idrowEmedOrder).val();

        last_emed(idrowEmedOrder);
        $("#emed_unit"+ idrowEmedOrder).selectpicker();
        initailizeSelect2_tarif_erad(unit);
        idrowEmedOrder++
    }

    function tambah_order_erad(){
        var list_order  = $("#daftar_order_erad");
        var form_order  = $("#erad-form-area");

        list_order.append("<tr id='order_erad_tr"+ idrowEradOrder +"'>"+
        "<td><input type='text' class='form-control input-large' id='row_noerad"+ idrowEradOrder +"' value='' style='width:100%'></td>"+
        "<td class='text-center'>"+
            "<button class='btn blue' onclick='openOrderErad("+ idrowEradOrder +")' type='button'><i class='fa fa-eye'></i></button>&nbsp;"+
            "<button class='btn red' onclick='hapusBarisOrderErad("+ idrowEradOrder +")' type='button'><i class='fa fa-trash'></i></button></td>"+
        "</tr>");

        form_order.append("<div class='erad-form' id='erad-form"+idrowEradOrder+"'>"+
        "<button type='button' class='btn red' onclick='closeOrderErad("+idrowEradOrder+")'><i class='fa fa-angle-left fa-fw'></i>&nbsp; Kembali</button><br /><br />"+
        '<div class="row">'+
            '<div class="col-sm-4">'+
                '<label for="erad_no">No E-Radiologi</label>'+
                '<input type="text" id="form_noerad'+ idrowEradOrder +'" name="erad_no[]" class="form-control" readonly>'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="erad_tanggal">Tanggal</label>'+
                '<input type="date" id="erad_tanggal" name="erad_tanggal[]" class="form-control" value="<?= date("Y-m-d") ?>">'+
            '</div>'+
            '<div class="col-sm-4">'+
                '<label for="erad_jam">Jam</label>'+
                '<input type="time" id="erad_jam" name="erad_jam[]" class="form-control" value="<?= date("H:i:s") ?>">'+
            '</div>'+
        '</div>'+
        '<div class="table-responsive" style="z-index:999 !important">'+
            '<table class="table table-bordered table-striped table-condensed table-scrollable" style="width:100%">'+
                '<thead class="page-breadcrumb breadcrumb">'+
                    '<tr class="title-white">'+
                        '<th style="width:5%">Delete</th>'+
                        '<th style="width:40%">Pemeriksaan</th>'+
                        '<th style="width:25%">Harga</th>'+
                        '<th style="width:30%">Notes</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody id="table_erad'+ idrowEradOrder +'">'+
                '</tbody>'+
            '</table>'+
        '</div>'+
        '<div style="margin-top:15px;width:auto">'+
            '<button type="button" class="btn green" onclick="tambah_erad('+ idrowEradOrder +')" id="tambahErad'+ idrowEradOrder +'"><i class="fa fa-plus"></i>&nbsp; Tambah</button>'+
        '</div>'+
        "</div>");

        // var unit    = $("#emed_unit"+ idrowEmedOrder).val();
        last_erad(idrowEradOrder);
        // $("#emed_unit"+ idrowEmedOrder).selectpicker();
        initailizeSelect2_tarif_erad("PUMUM");
        idrowEradOrder++
    }

    function openOrderElab(param){
        $("#elab-table").attr("style", "display:none");
        $("#elab-form"+ param).attr("style", "display:block");
    }

    function closeOrderLab(param){
        $("#elab-table").attr("style", "display:block");
        $("#elab-form"+ param).attr("style", "display:none");
        $(".elab-form").attr("style", "display:none");
    }

    function openOrderEmed(param){
        $("#emed-table").attr("style", "display:none");
        $("#emed-form"+ param).attr("style", "display:block");
    }

    function closeOrderRad(param){
        $("#emed-table").attr("style", "display:block");
        $("#emed-form"+ param).attr("style", "display:none");
        $(".emed-form").attr("style", "display:none");
    }

    function openOrderErad(param){
        $("#erad-table").attr("style", "display:none");
        $("#erad-form"+ param).attr("style", "display:block");
    }

    function closeOrderErad(param){
        $("#erad-table").attr("style", "display:block");
        $("#erad-form"+ param).attr("style", "display:none");
        $(".erad-form").attr("style", "display:none");
    }

    function hapusBarisOrderEmed(param){
        var noreg       = '<?= $this->input->get("noreg") ?>';
        var orderno     = $("#row_noemed"+ param).val();

        swal({
            title: 'E-ORDER ELEKTROMEDIS',
            html: 'Apakah Anda yakin<br />Ingin menghapus order ini ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
        }).then(() => {
            $.ajax({
                url: "/poliklinik/delete_emed/"+ noreg +"/"+ orderno,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                    if(data.status == "unregistered"){
                        $("#order_emed_tr"+ param).remove();
                        $("#emed-form"+ param).remove();
                    } else 
                    if(data.status == "success"){
                        swal({
                            title: 'E-ORDER ELEKTROMEDIS',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Berhasil Dihapus...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green'
                        }).then(() => {
                            $("#order_emed_tr"+ param).remove();
                            $("#emed-form"+ param).remove();
                            get_emed_order();
                        });
                    } else 
                    if(data.status == "failed"){
                        swal({
                            title: 'E-ORDER ELEKTROMEDIS',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Gagal Dihapus...",
                            type: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'red'
                        })
                    }
                },
                error: function(){
                    $("#order_emed_tr"+ param).remove();
                    $("#emed-form"+ param).remove();
                }
            });
        });
    }

    function hapusBarisOrderElab(param){
        var noreg       = '<?= $this->input->get("noreg") ?>';
        var orderno     = $("#row_noelab"+ param).val();

        swal({
            title: 'E-ORDER LABORATORIUM',
            html: 'Apakah Anda yakin<br />Ingin menghapus order ini ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
        }).then(() => {
            $.ajax({
                url: "/poliklinik/delete_elab/"+ noreg +"/"+ orderno,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                    if(data.status == "unregistered"){
                        $("#order_elab_tr"+ param).remove();
                        $("#elab-form"+ param).remove();
                    } else 
                    if(data.status == "success"){
                        swal({
                            title: 'E-ORDER LABORATORIUM',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Berhasil Dihapus...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green'
                        }).then(() => {
                            $("#order_elab_tr"+ param).remove();
                            $("#elab-form"+ param).remove();
                            get_elab_order();
                        });
                    } else 
                    if(data.status == "failed"){
                        swal({
                            title: 'E-ORDER LABORATORIUM',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Gagal Dihapus...",
                            type: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'red'
                        })
                    }
                },
                error: function(){
                    $("#order_elab_tr"+ param).remove();
                    $("#elab-form"+ param).remove();
                }
            });
        });
    }

    function hapusBarisOrderErad(param){
        var noreg       = '<?= $this->input->get("noreg") ?>';
        var orderno     = $("#row_noerad"+ param).val();

        swal({
            title: 'E-ORDER RADIOLOGI',
            html: 'Apakah Anda yakin<br />Ingin menghapus order ini ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
        }).then(() => {
            $.ajax({
                url: "/poliklinik/delete_erad/"+ noreg +"/"+ orderno,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                    if(data.status == "unregistered"){
                        $("#order_erad_tr"+ param).remove();
                        $("#erad-form"+ param).remove();
                    } else 
                    if(data.status == "success"){
                        swal({
                            title: 'E-ORDER RADIOLOGI',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Berhasil Dihapus...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green'
                        }).then(() => {
                            $("#order_erad_tr"+ param).remove();
                            $("#erad-form"+ param).remove();
                            get_erad_order();
                        });
                    } else 
                    if(data.status == "failed"){
                        swal({
                            title: 'E-ORDER RADIOLOGI',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+ 
                                "<br>Gagal Dihapus...",
                            type: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'red'
                        })
                    }
                },
                error: function(){
                    $("#order_erad_tr"+ param).remove();
                    $("#erad-form"+ param).remove();
                }
            });
        });
    }

    function hapusBaris_diagnosa(param){
        $("#diagnosa_tr"+ param).remove();
    }

    function hapusBaris_tindakan(param){
        $("#tindakan_tr"+ param).remove();
    }

    function hapusBaris_alkes(param){
        <?php
            $check_list = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '". $this->input->get("noreg") ."' AND koders = '". $this->session->userdata("unit") ."'")->num_rows();
        ?>
        <?php if($check_list == 0): ?>
            $("#alkes_tr"+ param).remove();
        <?php else: ?>
            var kodebarang  = $("#kdalkes"+ param).val();
            var gudbhp      = $("#gudang_bhp").val();
            var qty         = $("#qtyalkes"+ param).val();

            swal({
                title: 'BHP & ALKES',
                html: 'Apakah Anda yakin<br />Ingin menghapus ini ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
            }).then(() => {
                $.ajax({
                    url: "/poliklinik/return_bhp",
                    data: {qty: qty,noreg: '<?= $this->input->get("noreg") ?>', kodebarang: kodebarang, gudang: gudbhp},
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                        if(data.status){
                            swal({
                                title: "BHP & ALKES",
                                html: "Berhasil di hapus",
                                type: "success",
                                confirmButtonText: "OK" 
                            }).then(() => {
                                $("#alkes_tr"+ param).remove();
                            });
                        } else {
                            swal({
                                title: "BHP & ALKES",
                                html: "Gagal di hapus",
                                type: "error",
                                confirmButtonText: "OK" 
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        swal({
                            title: "BHP & ALKES",
                            html: "Gagal di hapus, kesalahan sistem",
                            type: "error",
                            confirmButtonText: "OK" 
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });
        <?php endif; ?>
    }

    function hapusBaris_eresep(param){
        $("#eresep_tr"+ param).remove();
    }

    function hapusBaris_racikan1(param){
        $("#racikan1_tr"+ param).remove();
    }

    function hapusBaris_racikan2(param){
        $("#racikan2_tr"+ param).remove();
    }

    function hapusBaris_racikan3(param){
        $("#racikan3_tr"+ param).remove();
    }

    function hapusBarisElab(param){
        $("#elab_tr"+ param).remove();
    }

    function hapusBarisEmed(param){
        $("#emed_tr"+ param).remove();
    }

    function hapusBarisErad(param){
        $("#erad_tr"+ param).remove();
    }

    // EXECUTION
    function back(){
        var thiloc = window.location;
        window.close(thiloc);
    }

    function save(){	    
        var post_form       = $('#form_periksa_dokter').serialize();
        var cek             = '<?= $statuspu ?>';
        var v_noreg_dok     = $('[name="noreg_dok"]').val();
        var v_rekmed_dok    = $('[name="rekmed_dok"]').val();
        var v_kelawal       = $('[name="kelawal"]').val();
        var v_pemeriksaan   = $('[name="pemeriksaan"]').val();
        var v_diagmas       = $('[name="diagmas"]').val();
        var v_teresep       = $('[name="teresep"]').val();
        var v_diagnosa      = $('[name="diagnosa"]').val();
        var v_tindu         = $('[name="tindu"]').val();
        var v_anjuran       = $('[name="anjuran"]').val();
        var v_namapass      = $('[name="nampas_dok"]').val();

        var tabel_diag      = document.getElementById('diagnosa_row');
        var row_tabel_diag  = tabel_diag.rows.length;
        var qtyc            = $("#jenis_diag1").val();
        var tabel_diagx     = document.getElementById('tindakan_row');
        var row_tabel_diagx = tabel_diag.rows.length;
        var qtyx            = $("#kode1").val();

        if (row_tabel_diag==0 || row_tabel_diag== null){
        swal({
                title: "List Diagnosa ICD",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 

        if (qtyc==0 || qtyc== null){
        swal({
                title: "List Diagnosa ICD2",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 

        if (row_tabel_diagx==0 || row_tabel_diagx== null){
        swal({
                title: "Billing Tindakan",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 

        if (qtyx==0 || qtyx== null){
        swal({
                title: "Billing Tindakan",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        
        if (v_kelawal=='' || v_kelawal== null){
        swal({
                title: "KELUHAN AWAL",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_pemeriksaan=='' || v_pemeriksaan== null){
        swal({
                title: "PEMERIKSAAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_diagmas=='' || v_diagmas== null){
        swal({
                title: "DIAGNOSA MASUK",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_teresep=='' || v_teresep== null){
        swal({
                title: "TERAPI RESEP",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_diagnosa=='' || v_diagnosa== null){
        swal({
                title: "DIAGNOSA",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_tindu=='' || v_tindu== null){
        swal({
                title: "TINDAKAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",  
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if (v_anjuran=='' || v_anjuran== null){
        swal({
                title: "SARAN ANJURAN",
                html: "<p>HARUS DI ISI !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        
        
                
        $.ajax({
            <?php if($statuspu == "undone"){
                echo 'url: "'. base_url() .'poliklinik/ajax_add_dokter/1"'; 
            } else { 
                echo 'url: "'. base_url() .'poliklinik/ajax_add_dokter/0"'; 
            } ?>,
            data        : post_form,
            type        : 'POST',
            dataType    : "json",
            success:function(data){ 
            // data1 = JSON.parse(data);
            // alert(data1.status);
                if(data.status=='0'){   
                    swal({
                        title: "DATA PEMERIKSAAN DOKTER",
                        html: 
                            "<p> Nama   : <b>"+v_namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil di Perbarui...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                                location.href='/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>';
                    });	
                }else{
                    swal({
                        title: "DATA PEMERIKSAAN DOKTER",
                        html: 
                            "<p> Nama   : <b>"+v_namapass+"</b> </p>"+ 
                            "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                            "<br>Berhasil Tersimpan...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                                location.href = "<?php echo base_url()?>poliklinik/pemeriksaan_dokter/?noreg="+v_noreg_dok+"&rekmed="+v_rekmed_dok+"";
                    });	
                }			
                                    
        
            },
            error:function(data){
                alert("error");
                swal('EMR','Data gagal disimpan ...','');   	
            }
        });
    }

    function save_elab(type){
        var post_form   = $("#frmelab").serialize();
        var dokter      = $("#selectdr").val();
        var kodepos     = $("#poli_dok").val();
        
        $.ajax({
            url: "/poliklinik/add_elab/<?= $this->input->get("noreg") ?>/<?= $this->input->get("rekmed") ?>/"+ dokter +"/"+ kodepos,
            data: post_form,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(data.status == "success"){
                    if(type == "front"){
                        swal({
                            title: 'E-ORDER LABORATORIUM',
                            html: 
                                "<br /><br />Order berhasil dibuat",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                        }).then(() => {
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=elab';
                            get_elab_order();
                        });
                    } else {
                        swal({
                            title: 'E-ORDER LABORATORIUM',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+
                                "Nama   : <b>"+data.nama+"</b><br />"+ 
                                "No. Register   : <b>"+data.noreg+"</b><br />"+
                                "<br>Berhasil Tersimpan...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                        }).then(() => {
                            get_elab_order();
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=elab';
                        });
                    }
                } else 
                if(data.status == "failed"){
                    swal("E-ORDER LABORATORIUM", "Gagal Disimpan (1)...", "");
                }
            },
            error: function(){
                swal("E-ORDER LABORATORIUM", "Gagal Disimpan (2)...", "");
            }
        });
    }

    function save_emed(type){
        var post_form   = $("#frmemed").serialize();
        var dokter      = $("#selectdr").val();
        var kodepos     = $("#poli_dok").val();
        
        $.ajax({
            url: "/poliklinik/add_emed/<?= $this->input->get("noreg") ?>/<?= $this->input->get("rekmed") ?>/"+ dokter +"/"+ kodepos,
            data: post_form,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(data.status == "success"){
                    if(type == "front"){
                        swal({
                            title: 'E-ORDER ELEKTROMEDIS',
                            html: 
                                "<br /><br />Order berhasil dibuat",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                        }).then(() => {
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=erm';
                            get_emed_order();
                        });
                    } else {
                        swal({
                            title: 'E-ORDER ELEKTROMEDIS',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+
                                "Nama   : <b>"+data.nama+"</b><br />"+ 
                                "No. Register   : <b>"+data.noreg+"</b><br />"+
                                "<br>Berhasil Tersimpan...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                        }).then(() => {
                            get_emed_order();
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=erm';
                        });
                    }
                } else 
                if(data.status == "failed"){
                    swal("E-ORDER ELEKTROMEDIS", "Gagal Disimpan (1)...", "");
                }
            },
            error: function(){
                swal("E-ORDER ELEKTROMEDIS", "Gagal Disimpan (2)...", "");
            }
        });
    }

    function save_erad(type){
        var post_form   = $("#frmerad").serialize();
        var dokter      = $("#selectdr").val();
        var kodepos     = $("#poli_dok").val();
        
        $.ajax({
            url: "/poliklinik/add_erad/<?= $this->input->get("noreg") ?>/<?= $this->input->get("rekmed") ?>/"+ dokter +"/"+ kodepos,
            data: post_form,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(data.status == "success"){
                    if(type == "front"){
                        swal({
                            title: 'E-ORDER RADIOLOGI',
                            html: 
                                "<br /><br />Order berhasil dibuat",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                            allowOutsideClick: false
                        }).then(() => {
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=rad';
                            get_emed_order();
                        });
                    } else {
                        swal({
                            title: 'E-ORDER RADIOLOGI',
                            html: 
                                "<br /><br />No Order   : <b>"+data.orderno+"</b><br />"+
                                "Nama   : <b>"+data.nama+"</b><br />"+ 
                                "No. Register   : <b>"+data.noreg+"</b><br />"+
                                "<br>Berhasil Tersimpan...",
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'green',
                        }).then(() => {
                            get_emed_order();
                            location.href = '/poliklinik/pemeriksaan_dokter/?noreg=<?= $this->input->get("noreg") ?>&rekmed=<?= $this->input->get("rekmed") ?>&tab=rad';
                        });
                    }
                } else 
                if(data.status == "failed"){
                    swal("E-ORDER RADIOLOGI", "Gagal Disimpan (1)...", "");
                }
            },
            error: function(){
                swal("E-ORDER RADIOLOGI", "Gagal Disimpan (2)...", "");
            }
        });
    }

    function save_suketsakit(){
        var noreg       = '<?= $this->input->get("noreg") ?>';
        var rekmed      = '<?= $this->input->get("rekmed") ?>';
        var koders      = '<?= $this->session->userdata("unit") ?>';
        var tgl_prks    = $("#tgl_dok").val();
        var dr          = $("#selectdr").val();
        var ijinsakit   = $("#sakitselama").val();
        var ijindari    = $("#ijindari").val();
        var ijinsampai  = $("#ijinsampai").val();

        $.ajax({
            url: "/poliklinik/add_suket/sakit",
            data: {noreg: noreg, rekmed: rekmed, koders: koders, tgl_periksa: tgl_prks, kodokter: dr, ijinsakit: ijinsakit, ijindari: ijindari, ijinsampai: ijinsampai},
            type: "POST",
            dataType: "JSON",
            success: function(data){
                switch(data.status){
                    case "type" : 
                        swal({
                            title: "Surat Keterangan Sakit",
                            html: "Gagal Disimpan",
                            type: "error",
                            confirmButtonText: "OK" 
                        });
                        break;
                    case "success"  : 
                        $("#print_suketsakit").prop("disabled", false); 
                        break;
                    case "failed"   : 
                        swal({
                            title: "Surat Keterangan Sakit",
                            html: "Gagal Disimpan",
                            type: "error",
                            confirmButtonText: "OK" 
                        }); 
                        break;
                }
                
                $(".suket_success")
                    .html("Surat Keterangan Sakit Berhasil Dibuat")
                    .attr("style", "padding-bottom:20px;color:green;display:block");

                setTimeout(function(){
                    $(".suket_success").html("").removeAttr("style");
                }, 3000);
            },
            error: function(data){
                swal({
                    title: "Surat Keterangan Sakit",
                    html: "Gagal Disimpan (Error System)",
                    type: "error",
                    confirmButtonText: "OK" 
                });
            }
        });
    }

    function save_suketsehat(){
        var noreg           = '<?= $this->input->get("noreg") ?>';
        var rekmed          = '<?= $this->input->get("rekmed") ?>';
        var koders          = '<?= $this->session->userdata("unit") ?>';
        var tgl_prks        = $("#tgl_dok").val();
        var dr              = $("#selectdr").val();
        var butawarna       = $("#butawarna").val();
        var sehat           = $("#sehat").val();
        var ketsehat        = $("#ketsehat").val();
        var ketsehatuntuk   = $("#ketsehatuntuk").val();

        $.ajax({
            url: "/poliklinik/add_suket/sehat",
            data: {noreg: noreg, rekmed: rekmed, koders: koders, tgl_periksa: tgl_prks, kodokter: dr, butawarna: butawarna, sehat: sehat, ketsehat: ketsehat, ketsehatuntuk: ketsehatuntuk},
            type: "POST",
            dataType: "JSON",
            success: function(data){
                switch(data.status){
                    case "type" : 
                        swal({
                            title: "Surat Keterangan Dokter",
                            html: "Gagal Disimpan",
                            type: "error",
                            confirmButtonText: "OK" 
                        });
                        break;
                    case "success"  : 
                        $("#print_suketsakit").prop("disabled", false); 
                        break;
                    case "failed"   : 
                        swal({
                            title: "Surat Keterangan Dokter",
                            html: "Gagal Disimpan",
                            type: "error",
                            confirmButtonText: "OK" 
                        }); 
                        break;
                }
                
                $(".suket_sehat_success")
                    .html("Surat Keterangan Dokter Berhasil Dibuat")
                    .attr("style", "padding-bottom:20px;color:green;display:block");

                setTimeout(function(){
                    $(".suket_sehat_success").html("").removeAttr("style");
                }, 3000);
            },
            error: function(data){
                swal({
                    title: "Surat Keterangan Dokter",
                    html: "Gagal Disimpan (Error System)",
                    type: "error",
                    confirmButtonText: "OK" 
                });
            }
        });
    }

    function numeric_restruct2(param){
        var resone;

        resone = param.toString().split(".").join("");

        return resone;
    }

    function qty(param){
        var qty     = $("#qtyalkes"+ param).val();
        var harga   = numeric_restruct($("#hrgalkes"+ param).val());
        var total   = eval(harga) * eval(qty);
        var actual  = Number(total).toFixed(2);
        var actual2 = actual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#totalkes"+ param).val(actual2);
    }

    function qtyracik1(param){
        var qty     = $("#r1qtyjual"+ param).val();
        var harga   = numeric_restruct($("#r1harga"+ param).val());
        var total   = eval(harga) * eval(qty);
        var actual  = Number(total).toFixed(2);
        var actual2 = actual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#r1totalharga"+ param).val(actual2);
    }

    function qtyracik2(param){
        var qty     = $("#r2qtyjual"+ param).val();
        var harga   = numeric_restruct($("#r2harga"+ param).val());
        var total   = eval(harga) * eval(qty);
        var actual  = Number(total).toFixed(2);
        var actual2 = actual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#r2totalharga"+ param).val(actual2);
    }

    function qtyracik3(param){
        var qty     = $("#r3qtyjual"+ param).val();
        var harga   = numeric_restruct($("#r3harga"+ param).val());
        var total   = eval(harga) * eval(qty);
        var actual  = Number(total).toFixed(2);
        var actual2 = actual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#r3totalharga"+ param).val(actual2);
    }

    function qtyer(param){
        var qty     = $("#jml_obat"+ param).val();
        var harga   = numeric_restruct($("#hargaer"+ param).val());
        var total   = eval(harga) * eval(qty);
        var actual  = Number(total).toFixed(2);
        var actual2 = actual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#totalhargaer"+ param).val(total);
    }

    function jmlobat(param){
        var jml_obat_hari1    = $("#jml_obat_hari"+ param).val();
        var qty_minumum1      = $("#qty_minumum"+ param).val();
        var jml_hari1         = $("#jml_hari"+ param).val();
        var total             = eval(jml_obat_hari1) * eval(qty_minumum1) * eval(jml_hari1);
        $("#jml_obat"+ param).val(total);
        $("#autran_pakai"+ param).val(jml_obat_hari1+' x '+qty_minumum1);
        qtyer(param);
    }

    filterRacikan("1");
    function filterRacikan(c) {
        var x, i;
        x = document.getElementsByClassName("racikanFilter");
        if (c == "all") c = "";
        for (i = 0; i < x.length; i++) {
            removeRacikan(x[i], "showracikan");
            if (x[i].className.indexOf(c) > -1) addRacikan(x[i], "showracikan");
        }
    }

    function addRacikan(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++){
            if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
        }
    }

    function removeRacikan(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++) {
            while (arr1.indexOf(arr2[i]) > -1) {
                arr1.splice(arr1.indexOf(arr2[i]), 1);     
            }
        }
        element.className = arr1.join(" ");
    }

    function changeRbtn(param){
        var valuenya    = $("#racikan"+ param).html();
        $("#rbtn").html(valuenya +" <span class='caret'></span>");
    }
    
    function cek(param){
        var ijindari    = $('[name="ijindari"]').val();
        var ijinsampai  = $('[name="ijinsampai"]').val();

        $.ajax({
            url         : "/poliklinik/cek_tgl/",
            data        : {'ijindari':ijindari,'ijinsampai':ijinsampai},
            type        : "POST",
            dataType    : "JSON",
            success: function(data){
                if(data.jarak<0){
                    swal({
                        title: "Tanggal Akhir ",
                        html: "<p>Harus Lebih Besar Dari Tanggal Awal</p>",
                        type: "error",
                        confirmButtonText: "OK" 
                    });    
                    return;
                }else{
                    $('[name="sakitselama"]').val(data.jarak+1);
                }
            },
            error: function(){
            }
        });        // alert($hari);
    }

    var btnContainer = document.getElementById("myRacikanbtn");
    var btns = btnContainer.getElementsByClassName("dropdownitem");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(){
            var current = document.getElementsByClassName("activerbtn");
            current[0].className = current[0].className.replace(" activerbtn", "");
            this.className += " activerbtn";
        });
    }

</script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->
