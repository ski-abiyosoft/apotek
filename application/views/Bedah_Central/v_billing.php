    <?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        .sub-title {font-size:18px;font-weight:bold;display:block}
        .form-label {margin-top:5px;font-weight:bold}
    </style>

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">&nbsp;<?= $unit ?>&nbsp;</span>&nbsp;-
                &nbsp;<span class="title-web"><?=$menu;?> <small> <?= $submenu;?></small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url();?>dashboard"
                        class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
                <li><a href="/<?= $link ?>" class="title-white"><?=$menu;?> </a></a>&nbsp;<i class="fa fa-angle-right"
                        style="color:#fff"></i></li>
                <li><a href="#" class="title-white"><?=$submenu;?> </a></a></li>
            </ul>
        </div>
    </div>

    <div class="portlet">
        <div class="portlet-title">
            <div class="caption">ENTRI <?= strtoupper($submenu) ?> OPERASI</div>
        </div>
    </div>

    <form id="frmbill">
        <input type="hidden" name="nojadwal" value="<?= $data->nojadwal ?>">
        <input type="hidden" name="noreg" value="<?= $data->noreg ?>">
        <input type="hidden" name="rekmed" value="<?= $data->rekmed ?>">

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">
                        <div class="row form-horizontal">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="nojadwal">No Jadwal</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="<?= isset($header->nojadwal)? $header->nojadwal : $data->nojadwal ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="noreg">No Reg</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="<?= isset($header->noreg)? $header->noreg : $data->noreg ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="rekmed">Rekmed</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="<?= isset($header->rekmed)? $header->rekmed :$data->rekmed ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="namapas">Nama Pasien</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="namapas" id="namapas" value="<?= $data->namapas ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label col-sm-3"></label>
                                    <div class="col-sm-9">
                                        <label style="margin-top:0;padding-top:5px" class="checkbox-inline"><input type="checkbox" name="paket" id="paket" onchange="checkbox('paket')" value="0" <?= isset($header->paket)? ($header->paket == 1)? "checked" : "" : "" ?>>&emsp; Paket</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="tgloperasi">Tgl Operasi</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="tgloperasi" id="tgloperasi" value="<?= isset($header->tgloperasi)? date("Y-m-d", strtotime($header->tgloperasi)) : date("Y-m-d", strtotime($data->tgloperasi)) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="jenisop">Jam Operasi</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="jamoperasi" id="jamoperasi" value="<?= isset($header->jam)? $header->jam : $data->jam ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="jenispas">Jenis Pasien</label>
                                    <div class="col-sm-9">
                                        <select type="text" class="form-control" name="jenispas" id="jenispas">
                                            <option value="<?= $data->jenispas ?>"><?= ($data->jenispas == "PAS1")? "UMUM" : $data->cust_nama ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="lokasiop">Lokasi Operasi</label>
                                    <div class="col-sm-9">
                                        <select id="ruangop" name="ruangop" class="selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)">
                                            <option>-- Pilih Data --</option>
                                                <?php
                                                    foreach($ruangop as $val){
                                                        if($val->koderuang == $data->ruangok){
                                                            echo "<option value='$val->koderuang' selected>$val->namaruang</option>";
                                                        } else {
                                                            echo "<option value='$val->koderuang'>$val->namaruang</option>";
                                                        }
                                                    }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-1">&nbsp;</div>
                            <div class="col-sm-10" style="margin:30px 0 30px 0 !important">
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="">Tindakan Utama</label>
                                    <div class="col-sm-9">
                                        <select type="text" id="tarif1" name="tarifutama" class="form-control selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style='color:#222 !important' onchange="gettindakan()">
                                            <option value="">--- Pilih Data ---</option>
                                            <?php
                                                foreach($tarif as $tkey => $tval){
                                                    if($status == "done"){
                                                        $tindakanutama = $deftindak;
                                                    } else {
                                                        $tindakanutama = $data->kodetarif;
                                                    }

                                                    if($tval->kodetarif == $tindakanutama){
                                            ?>
                                                <option value="<?= $tval->kodetarif ?>" selected><?= $tval->ket ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $tval->kodetarif ?>"><?= $tval->ket ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="">Tindakan Ke-2</label>
                                    <div class="col-sm-9">
                                        <select type="text" id="tarif2" name="tarif2" class="form-control selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style='color:#222 !important' onchange="gettindakan()">
                                            <option value="">--- Pilih Data ---</option>
                                            <?php
                                                foreach($tarif as $dkey => $dval):
                                            ?>
                                                <option value="<?= $dval->kodetarif ?>"><?= $dval->ket ?></option>
                                            <?php 
                                                endforeach; 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 form-label" for="">Tindakan Ke-3</label>
                                    <div class="col-sm-9">
                                        <select type="text" id="tarif3" name="tarif3" class="form-control selectpicker" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style='color:#222 !important' onchange="gettindakan()">
                                            <option value="">--- Pilih Data ---</option>
                                            <?php
                                                foreach($tarif as $dkey => $dval):
                                            ?>
                                                <option value="<?= $dval->kodetarif ?>"><?= $dval->ket ?></option>
                                            <?php 
                                                endforeach; 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">&nbsp;</div>

                            <div class="col-sm-12" style="margin:0 0 30px 0 !important">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr>
                                                <th class="title-white text-center">Ok</th>
                                                <th class="title-white text-center">Tindakan</th>
                                                <th class="title-white text-center">Komponen Jasa</th>
                                                <th class="title-white text-center">Dokter & Petugas Medis</th>
                                                <th class="title-white text-center">Tarif Rp</th>
                                                <th class="title-white text-center">Cito</th>
                                                <th class="title-white text-center">Cito Rp</th>
                                                <th class="title-white text-center">Penyulit</th>
                                                <th class="title-white text-center">Penyulit Rp</th>
                                                <th class="title-white text-center">Total Rp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kodetarif[]" id="kodetarif1" value="">
                                                    <input type="hidden" name="tarifkey[]" id="tarifkey1" value="">
                                                    <input type='checkbox' name='ok[]' id='ok' checked>
                                                </td>
                                                <td>Section</td>
                                                <td>Jasa Operator</td>
                                                <td>
                                                    <select type="text" class="form-control" name="kodokter[]">
                                                        <option value="<?= $data->droperator ?>"><?= data_master("tbl_dokter", array("kodokter" => $data->droperator))->nadokter ?></option>
                                                    </select>
                                                    <!-- <select class="selectpicker" name="kodokter[]" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style="max-height:10px !important">
                                                        <option>-- Pilih Data --</option>
                                                            <?php
                                                                foreach($listdokter as $val){
                                                                    if($val->kodokter == $data->droperator){
                                                                        echo "<option value='$val->kodokter' selected>$val->nadokter</option>";
                                                                    } else {
                                                                        echo "<option value='$val->kodokter'>$val->nadokter</option>";
                                                                    }
                                                                }
                                                            ?>
                                                    </select> -->
                                                </td>
                                                <td><input type="text" name="tarifrp[]" id="tarifrp1" onkeyup="formating('tarifrp1'); total(1)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='cito[]' id='cito1'></td>
                                                <td><input type="text" name="citorp[]" id="citorp1" onkeyup="formating('citorp1'); total(1)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='penyulit[]' id='penyulit[]'></td>
                                                <td><input type="text" name="penyulitrp[]" id="penyulitrp1" onkeyup="formating('penyulitrp1'); total(1)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td><input type="text" name="totalrp[]" id="totalrp1" onkeyup="formating('totalrp1')" class="form-control total" value="0" style="padding:5px !important;height:25px !important"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kodetarif[]" id="kodetarif2" value="">
                                                    <input type="hidden" name="tarifkey[]" id="tarifkey2" value="">
                                                    <input type='checkbox' name='ok[]' id='ok' checked>
                                                </td>
                                                <td>Section</td>
                                                <td>Jasa As Operator</td>
                                                <td>
                                                    <select type="text" class="form-control" name="kodokter[]">
                                                        <option value="<?= $data->asdroperator ?>"><?= data_master("tbl_dokter", array("kodokter" => $data->asdroperator))->nadokter ?></option>
                                                    </select>
                                                    <!-- <select class="selectpicker" name="kodokter[]" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style="max-height:10px !important">
                                                        <option>-- Pilih Data --</option>
                                                            <?php
                                                                foreach($listdokter as $val){
                                                                    if($val->kodokter == $data->asdroperator){
                                                                        echo "<option value='$val->kodokter' selected>$val->nadokter</option>";
                                                                    } else {
                                                                        echo "<option value='$val->kodokter'>$val->nadokter</option>";
                                                                    }
                                                                }
                                                            ?>
                                                    </select> -->
                                                </td>
                                                <td><input type="text" name="tarifrp[]" id="tarifrp2" onkeyup="formating('tarifrp2'); total(2)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='cito[]' id='cito2'></td>
                                                <td><input type="text" name="citorp[]" id="citorp2" onkeyup="formating('citorp2'); total(2)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='penyulit[]' id='penyulit[]'></td>
                                                <td><input type="text" name="penyulitrp[]" id="penyulitrp2" onkeyup="formating('penyulitrp2'); total(2)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td><input type="text" name="totalrp[]" id="totalrp2" onkeyup="formating('totalrp2')" class="form-control total" value="0" style="padding:5px !important;height:25px !important"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kodetarif[]" id="kodetarif3" value="">
                                                    <input type="hidden" name="tarifkey[]" id="tarifkey3" value="">
                                                    <input type='checkbox' name='ok[]' id='ok' checked>
                                                </td>
                                                <td>Section</td>
                                                <td>Jasa Anestesi</td>
                                                <td>
                                                    <select type="text" class="form-control" name="kodokter[]">
                                                        <option value="<?= $data->dranestasi ?>"><?= data_master("tbl_dokter", array("kodokter" => $data->dranestasi))->nadokter ?></option>
                                                    </select>
                                                    <!-- <select class="selectpicker" name="kodokter[]" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style="max-height:10px !important">
                                                        <option>-- Pilih Data --</option>
                                                            <?php
                                                                foreach($listdokter as $val){
                                                                    if($val->kodokter == $data->dranestasi){
                                                                        echo "<option value='$val->kodokter' selected>$val->nadokter</option>";
                                                                    } else {
                                                                        echo "<option value='$val->kodokter'>$val->nadokter</option>";
                                                                    }
                                                                }
                                                            ?>
                                                    </select> -->
                                                </td>
                                                <td><input type="text" name="tarifrp[]" id="tarifrp3" onkeyup="formating('tarifrp3'); total(3)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='cito[]' id='cito3'></td>
                                                <td><input type="text" name="citorp[]" id="citorp3" onkeyup="formating('citorp3'); total(3)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='penyulit[]' id='penyulit[]'></td>
                                                <td><input type="text" name="penyulitrp[]" id="penyulitrp3" onkeyup="formating('penyulitrp3'); total(3)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td><input type="text" name="totalrp[]" id="totalrp3" onkeyup="formating('totalrp3')" class="form-control total" value="0" style="padding:5px !important;height:25px !important"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kodetarif[]" id="kodetarif4" value="">
                                                    <input type="hidden" name="tarifkey[]" id="tarifkey4" value="">
                                                    <input type='checkbox' name='ok[]' id='ok' checked>
                                                </td>
                                                <td>Section</td>
                                                <td>Jasa As Anestesi</td>
                                                <td>
                                                    <select class="selectpicker" name="kodokter[]" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style="max-height:10px !important">
                                                        <option>-- Pilih Data --</option>
                                                            <?php
                                                                foreach($listdokter as $val){
                                                                    if($val->kodokter == $data->asdranestasi){
                                                                        echo "<option value='$val->kodokter' selected>$val->nadokter</option>";
                                                                    } else {
                                                                        echo "<option value='$val->kodokter'>$val->nadokter</option>";
                                                                    }
                                                                }
                                                            ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="tarifrp[]" id="tarifrp4" onkeyup="formating('tarifrp4'); total(4)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='cito[]' id='cito4'></td>
                                                <td><input type="text" name="citorp[]" id="citorp4" onkeyup="formating('citorp4'); total(4)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='penyulit[]' id='penyulit[]'></td>
                                                <td><input type="text" name="penyulitrp[]" id="penyulitrp4" onkeyup="formating('penyulitrp4'); total(4)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td><input type="text" name="totalrp[]" id="totalrp4" onkeyup="formating('totalrp4')" class="form-control total" value="0" style="padding:5px !important;height:25px !important"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kodetarif[]" id="kodetarif5" value="">
                                                    <input type="hidden" name="tarifkey[]" id="tarifkey5" value="">
                                                    <input type='checkbox' name='ok[]' id='ok' checked>
                                                </td>
                                                <td>Section</td>
                                                <td>Jasa Kamar</td>
                                                <td>
                                                    <select class="selectpicker" name="kodokter[]" data-live-search="true" data-width="100%" onkeypress="return tabE(this,event)" style="max-height:10px !important">
                                                        <option>-- Pilih Data --</option>
                                                            <?php
                                                                foreach($listdokter as $val){
                                                                    echo "<option value='$val->kodokter'>$val->nadokter</option>";
                                                                }
                                                            ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="tarifrp[]" id="tarifrp5" onkeyup="formating('tarifrp5'); total(5)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='cito[]' id='cito5'></td>
                                                <td><input type="text" name="citorp[]" id="citorp5" onkeyup="formating('citorp5'); total(5)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td class="text-center"><input type='checkbox' name='penyulit[]' id='penyulit[]'></td>
                                                <td><input type="" name="penyulitrp[]" id="penyulitrp5" onkeyup="formating('penyulitrp5'); total(5)" class="form-control" value="0" style="padding:5px !important;height:25px !important"></td>
                                                <td><input type="text" name="totalrp[]" id="totalrp5" onkeyup="formating('totalrp5')" class="form-control total" value="0" style="padding:5px !important;height:25px !important"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table" style="width:100%;margin:auto;border:none !important">
                                        <tbody>
                                            <tr>
                                                <td style="width:80%;text-align:right;border:none !important"><label class="form-label">Total Tindakan</label></td>
                                                <td style="border:none !important"><input type="text" class="form-control total" name="grandtotal" id="grandtotal" <?= ($status == "done")? "value=''" : "value='0'" ?> readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="sub-title" style="margin:0 0 15px 0 !important">BHP OPERASI</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="datatable">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <tr>
                                                <th class="title-white text-center" style="width:5%">Aksi</th>
                                                <th class="title-white text-center" style="width:5%">Bill</th>
                                                <th class="title-white text-center" style="width:18%">Lokasi Barang</th>
                                                <th class="title-white text-center" style="width:25%">Nama Obat/Alkes</th>
                                                <th class="title-white text-center" style="width:10%">Satuan</th>
                                                <th class="title-white text-center" style="width:7%">QTY</th>
                                                <th class="title-white text-center" style="width:15%">Harga</th>
                                                <th class="title-white text-center" style="width:15%">Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($status == "done"): ?>
                                                <?php
                                                    $no = 1;
                                                    foreach($bhp as $bkey => $bval){
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <center>
                                                                <button type='button' onclick="hapusBarisIni(<?= $no ?>)" class='btn red'><i class='fa fa-trash-o'></i></button>
                                                            </center>
                                                        </td>
                                                        <td style="text-align:center">
                                                            <input type="checkbox" name="bill[]" id="bill<?= $no ?>" onchange="checkbox('bill<?= $no ?>')" value="<?= ($bval->bill == 1)? 1 : 0 ?>" style="margin-top:10px;" <?= ($bval->bill == 1)? "checked" : "" ?>>
                                                        </td>
                                                        <td>
                                                            <select name="lokasi[]" id="lokasi<?= $no ?>" class="select2_el_farmasi_depo form-control input-largex" onchange="lokasi(<?= $no ?>)">
                                                                <option value="<?= $bval->gudang ?>" selected><?= data_master("tbl_depo", array("depocode" => $bval->gudang))->keterangan ?></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="barang[]" id="barang<?= $no ?>" class="select2_el_farmasi_baranggud form-control input-largex" onchange="showbarangname(this.value, <?= $no ?>)">
                                                                <option value="<?= $bval->kodeobat ?>" selected><?= data_master("tbl_barang", array("kodebarang" => $bval->kodeobat))->namabarang ?></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="satuan[]" id="satuan<?= $no ?>" value="<?= $bval->satuan ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="qty[]" id="qty<?= $no ?>" onkeyup="qty(<?= $no ?>)" value="<?= str_replace(".00", "", $bval->qty) ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="harga[]" id="harga<?= $no ?>" value="<?= number_format($bval->harga, 0, ',', '.') ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control totalharga total" name="totalharga[]" id="totalharga<?= $no ?>" value="<?= number_format($bval->totalharga, 0, ',', '.') ?>">
                                                        </td>
                                                    </tr>
                                                <?php $no++; } ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td>
                                                        <center>
                                                            <button type='button' onclick="hapusBarisIni(1)" class='btn red'><i class='fa fa-trash-o'></i></button>
                                                        </center>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="bill[]" id="bill1" onchange="checkbox('bill1')" value="0" style="margin-top:10px;">
                                                    </td>
                                                    <td>
                                                        <select name="lokasi[]" id="lokasi1" class="select2_el_farmasi_depo form-control input-largex" onchange="lokasi(1)">
                                                            <option value="FARMASI" selected>FARMASI TUNAI</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="barang[]" id="barang1" class="select2_el_farmasi_baranggud form-control input-largex" onchange="showbarangname(this.value, 1)"></select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="satuan[]" id="satuan1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="qty[]" id="qty1" onkeyup="qty(1)" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="harga[]" id="harga1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control totalharga total" name="totalharga[]" id="totalharga1">
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="margin:20px 0 0 0">
                                <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i></button>
                                </div>
                                <br /><br />
                                <div class="table-responsive">
                                    <table class="table" style="width:100%;margin:auto;border:none !important">
                                        <tbody>
                                            <tr>
                                                <td style="width:80%;text-align:right;border:none !important"><label class="form-label">Total Alkes</label></td>
                                                <td style="border:none !important"><input type="text" class="form-control total" name="totalbhp" id="totalbhp" value="0" readonly></td>
                                            </tr>
                                            <tr>
                                                <td style="width:80%;text-align:right;border:none !important"><label class="form-label">Total Biaya Operasi dan Alkes</label></td>
                                                <td style="border:none !important"><input type="text" class="form-control total" name="totalall" id="totalall" value="0" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="well" style="margin-bottom:0px !important;padding-bottom:50px">
                            <button class="btn green pull-right" type="button" id="save"><i class="fa fa-save"></i>&nbsp; <?= ($status == "done")? "Update" : "Simpan" ?></button>
                            <button class="btn red pull-right" type="button" onclick="back()" style="margin-right:20px"><i class="fa fa-times"></i>&nbsp; Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <br />

    <?php
        $this->load->view('template/footer');
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

    <script>
        $(window).on("load", function(){
            initailizeSelect2_farmasi_baranggud("FARMASI");
            <?php if($status == "done"): ?>
            gettindakan('<?= $deftindak ?>');
            totalbhp();
            <?php else: ?>
            gettindakan('<?= $data->kodetarif ?>');
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form   = $("#frmbill").serialize();
            console.log(post_form);
            $.ajax({
                <?php if($status == "done"): ?>
                    url : "/Bedah_Central/save_billing/1",
                <?php else: ?>
                    url : "/Bedah_Central/save_billing",
                <?php endif; ?>
                data : post_form,
                type : "POST",
                success : function(data){
                    swal({
                        <?php if($status == "done"): ?>
                            title: "Update Bill",
                            html: "Bill Berhasil Diupdate",
                        <?php else: ?>
                            title: "Entri Bill",
                            html: "Bill Berhasil Dibuat",
                        <?php endif; ?>
                        type: "success",
                        confirmButtonText: "OK"
                    }).then(function(){
                        location.href='/Bedah_Central/billing/<?= $data->nojadwal ?>';
                    });
                },
                error: function (jqXHR, textStatus, errorThrown){
                    alert(textStatus);
                }
            });
        });

        function gettindakan(param){
            <?php if($status == "done"): ?>
                var tarifutama  = param;
            <?php else: ?>
                var tarifutama  = $("#tarif1").val();
            <?php endif; ?>

            $.ajax({
                url: "/Bedah_Central/getinfotindakan/"+tarifutama,
                type: 'GET',
                dataType: 'JSON',
                success: function(data){
                    var tarifrp1 = $("#tarifrp1").val(data.drop);
                    var tarifrp2 = $("#tarifrp2").val(data.asdrop);
                    var tarifrp3 = $("#tarifrp3").val(data.dran);
                    var tarifrp4 = $("#tarifrp4").val(data.asdran);
                    var tarifrp5 = $("#tarifrp5").val(data.jasakamar);

                    var kodetarif1  = $("#kodetarif1").val(data.bedahtarif1);
                    var kodetarif2  = $("#kodetarif2").val(data.bedahtarif2);
                    var kodetarif3  = $("#kodetarif3").val(data.bedahtarif3);
                    var kodetarif4  = $("#kodetarif4").val(data.bedahtarif4);
                    var kodetarif5  = $("#kodetarif5").val(data.bedahtarif5);

                    var tarifkey1  = $("#tarifkey1").val(data.bedahkey1);
                    var tarifkey2  = $("#tarifkey2").val(data.bedahkey2);
                    var tarifkey3  = $("#tarifkey3").val(data.bedahkey3);
                    var tarifkey4  = $("#tarifkey4").val(data.bedahkey4);
                    var tarifkey5  = $("#tarifkey5").val(data.bedahkey5);

                    var totalrp1 = $("#totalrp1").val(data.drop);
                    var totalrp2 = $("#totalrp2").val(data.asdrop);
                    var totalrp3 = $("#totalrp3").val(data.dran);
                    var totalrp4 = $("#totalrp4").val(data.asdran);
                    var totalrp5 = $("#totalrp5").val(data.jasakamar);

                    var citocheck1  = $("#cito1").prop("checked", data.citocheck1);
                    var citocheck2  = $("#cito2").prop("checked", data.citocheck2);
                    var citocheck3  = $("#cito3").prop("checked", data.citocheck3);
                    var citocheck4  = $("#cito4").prop("checked", data.citocheck4);
                    var citocheck5  = $("#cito5").prop("checked", data.citocheck5);

                    var citorp1  = $("#citorp1").val(data.citorp1);
                    var citorp2  = $("#citorp2").val(data.citorp2);
                    var citorp3  = $("#citorp3").val(data.citorp3);
                    var citorp4  = $("#citorp4").val(data.citorp4);
                    var citorp5  = $("#citorp5").val(data.citorp5);

                    totalget();
                },
                error: function() {
                    swal({
                        title: "ERROR",
                        html: "Gagal Memuat Detail Tindakan",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }

        <?php if($status == "done"): ?>
            var idrow = <?= $jumdata+1 ?>;
        <?php else: ?>
            var idrow = 2;
        <?php endif; ?>
        var rowCount;
        var arr = [1];

        function back(){
            var thiloc  = window.location;
            window.close(thiloc);
        }

        function formating(param){
            var cost    = $("#"+param).val();
            return $("#"+param).val(cost.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        }

        function total(param){
            var tarif       = numeric_restruct2($("#tarifrp"+param).val());
            var cito        = numeric_restruct2($("#citorp"+param).val());
            var penyulit    = numeric_restruct2($("#penyulitrp"+param).val());

            var total       = eval(tarif) + eval(cito) + eval(penyulit);
            var actual      = total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $("#totalrp"+param).val(actual);

            var t1              = numeric_restruct2($("#totalrp1").val());
            var t2              = numeric_restruct2($("#totalrp2").val());
            var t3              = numeric_restruct2($("#totalrp3").val());
            var t4              = numeric_restruct2($("#totalrp4").val());
            var t5              = numeric_restruct2($("#totalrp5").val());
            var grandtotal      = eval(t1) + eval(t2) + eval(t3) + eval(t4) + eval(t5);
            var actual2         = grandtotal.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            if($("#tarif1").val() == ""){
                $("#grandtotal").val(actual2);
            } else {
                $.ajax({
                    url: "/Bedah_Central/getinfotindakan/"+$("#tarif1").val(),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(data){
                        var actual1 = numeric_restruct2(data.tarif);
                        var total   = eval(grandtotal) + eval(actual1);
                        $("#grandtotal").val(total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    }
                });
            }
            totalall();
        }

        function totalget(){
            var t1              = numeric_restruct2($("#totalrp1").val());
            var t2              = numeric_restruct2($("#totalrp2").val());
            var t3              = numeric_restruct2($("#totalrp3").val());
            var t4              = numeric_restruct2($("#totalrp4").val());
            var t5              = numeric_restruct2($("#totalrp5").val());
            var grandtotal      = eval(t1) + eval(t2) + eval(t3) + eval(t4) + eval(t5);
            var actual2         = grandtotal.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            if($("#tarif1").val() == ""){
                $("#grandtotal").val(actual2);
                $("#totalall").val(actual2);
            } else {
                $.ajax({
                    url: "/Bedah_Central/getinfotindakan/"+$("#tarif1").val(),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(data){
                        <?php if($status == "done"): ?>
                            var actual1 = numeric_restruct2(data.tarif);
                            var total   = eval(grandtotal) + eval(actual1);
                            var bhp     = numeric_restruct2($("#totalbhp").val());
                            var totaldone   = eval(total) + eval(bhp);
                            $("#grandtotal").val(total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                            $("#totalall").val(totaldone.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                        <?php else: ?>
                            var actual1 = numeric_restruct2(data.tarif);
                            var total   = eval(grandtotal) + eval(actual1);
                            $("#grandtotal").val(total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                            $("#totalall").val(total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                        <?php endif; ?>
                    }
                });
            }
            totalall();
        }

        function hapusBarisIni(param) {
            var x = document.getElementById('datatable').deleteRow(arr.indexOf(param) + 1);
            arr.splice(arr.indexOf(param), 1);

            rowCount--;
            totalbhp();
        }

        function showbarangname(str, id) {
            var xhttp;
            var vid = id;

            initailizeSelect2_farmasi_baranggud($("#lokasi"+ id).val());

            $.ajax({
                url: "<?php echo base_url();?>Bedah_Central/getinfobarang/?kode=" + str,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var harga   = data.hargajual;
                    var actual  = harga.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    $('#satuan' + vid).val(data.satuan1);
                    $('#harga' + vid).val(actual);
                    qty(id);
                    totalbhp();
                    checkstock($('[id="lokasi'+id+'"]').val(), data.kodebarang);
                }
            });
        }

        function checkstock(param1, param2) {
            $.ajax({
                url: "/Bedah_Central/checkstock/?kode=" + param2 + "&gudang=" + param1,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 0) {
                        swal({
                            title: "Kesalahan",
                            html: "stock kosong atau kurang dari 1",
                            type: "error",
                            confirmButtonText: "Ok"
                        });
                        $("[id='save']").prop("disabled", true);
                    } else if (data.status == 2) {
                        swal({
                            title: "Kesalahan",
                            html: "stock kosong atau kurang dari 1",
                            type: "error",
                            confirmButtonText: "Ok"
                        });
                        $("[id='save']").prop("disabled", true);
                    } else if (data.stock == 0) {
                        swal({
                            title: "Kesalahan 2",
                            html: "Stock Tidak Cukup",
                            type: "error",
                            confirmButtonText: "Ok"
                        });
                        $("[id='save']").prop("disabled", true);
                    } else {
                        $("[id='save']").prop("disabled", false);
                    }
                }
            });
        }

        function qty(param){
            var qty     = $("#qty"+ param).val();
            var harga   = numeric_restruct2($("#harga"+ param).val());
            var total   = eval(harga) * eval(qty);
            var actual  = total.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $("#totalharga"+ param).val(actual);
            totalbhp();
        }

        function totalbhp(){
            var totalrp = 0;

            $("tr .totalharga").each(function(index,value){
                currentRow  = parseInt(Number($(this).val().split(".").join("")));
                totalrp += currentRow
            });

            var totalbhp    = totalrp.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $("#totalbhp").val(totalbhp);
            totalall();
        }

        function totalall(){
            var total_tindakan  = numeric_restruct2($("#grandtotal").val());
            var total_bhp       = numeric_restruct2($("#totalbhp").val());

            var totalall        = eval(total_tindakan) + eval(total_bhp);
            var actual          = totalall.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            
            $("#totalall").val(actual);
        }

        function tambah(){

            var table = document.getElementById('datatable');
            rowCount = table.rows.length;
            arr.push(idrow);
            console.log(rowCount);

            var x = document.getElementById('datatable').insertRow(rowCount);
            var td1 = x.insertCell(0);
            var td2 = x.insertCell(1);
            var td3 = x.insertCell(2);
            var td4 = x.insertCell(3);
            var td5 = x.insertCell(4);
            var td6 = x.insertCell(5);
            var td7 = x.insertCell(6);
            var td8 = x.insertCell(7);

            var button  = "<td id='kolom" + idrow + "'><button type='button' onclick=hapusBarisIni(" + idrow + ") id=btnhapus" + idrow + " class='btn red'><i class='fa fa-trash-o'></td>";
            var bill    = '<center><input type="checkbox" name="bill[]" id="bill'+ idrow +'" onchange="checkbox(bill'+ idrow +')" value="0" style="margin-top:10px;"></center>';
            var barang  = '<select name="barang[]" id="barang'+ idrow +'" class="select2_el_farmasi_baranggud form-control input-largex" onchange="showbarangname(this.value, '+ idrow +')"></select>';
            var satuan  = '<input type="text" class="form-control" name="satuan[]" id="satuan'+ idrow +'">';
            var qty     = '<input type="text" class="form-control" name="qty[]" id="qty'+ idrow +'" onkeyup="qty('+ idrow +')" value="1">';
            var harga   = '<input type="text" class="form-control" name="harga[]" id="harga'+ idrow +'">';
            var totalh  = '<input type="text" class="form-control totalharga total" name="totalharga[]" id="totalharga'+ idrow +'">';
            var lokasi  = '<select name="lokasi[]" id="lokasi'+ idrow +'" class="select2_el_farmasi_depo form-control input-largex" onchange="lokasi('+ idrow +')"><option value="FARMASI" selected>FARMASI TUNAI</option></select>';

            td1.innerHTML = button;
            td2.innerHTML = bill;
            td4.innerHTML = barang;
            td5.innerHTML = satuan;
            td6.innerHTML = qty;
            td7.innerHTML = harga;
            td8.innerHTML = totalh;
            td3.innerHTML = lokasi;
            initailizeSelect2_farmasi_baranggud($("#lokasi"+ idrow).val());
            initailizeSelect2_farmasi_depo();
            idrow++;
        }

        function lokasi(param){
            var lokasi = $("#lokasi"+ param).val();
            initailizeSelect2_farmasi_baranggud(lokasi);
        }

        function checkbox(param){
            var checkbox    = $("#"+ param);

            if(checkbox.is(":checked")){
                $("#"+ param).val(1);
            } else {
                $("#"+ param).val(0);
            }
        }
    </script>