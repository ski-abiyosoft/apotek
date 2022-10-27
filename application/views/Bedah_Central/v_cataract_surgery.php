    <?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

    <style>
        .sub-title {font-size:14px;font-weight:bold;display:block}
        #myBtnContainer {margin-bottom:20px}
        .filterBoxtitle {font-size:18px;font-weight:bold;margin-bottom:10px !important;display:block}
        .filterBox {display:none}
        .filterBoxshow {display:block}
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
            <div class="caption"><?= strtoupper($submenu) ?></div>
        </div>
    </div>

    <div class="row" style="margin-bottom:20px">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered" style="width:100%;margin:auto">
                    <tbody>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">No Rekam Medis</td>
                            <td style="width:35%"><?= $jadwal->rekmed ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Tanggal Lahir</td>
                            <td style="width:35%"><?= $jadwal->tgllahir ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Nama</td>
                            <td style="width:35%"><?= $jadwal->namapas ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Alamat</td>
                            <td style="width:35%"><?= $jadwal->alamat ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Jenis Kelamin</td>
                            <td style="width:35%"><?= ($jadwal->jkel == "W")? "Wanita" : "Pria" ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%"></td>
                            <td style="width:35%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="frmcs">
        <input type="hidden" name="nojadwal" value="<?= $jadwal->nojadwal ?>">
        <input type="hidden" name="noreg" value="<?= $jadwal->noreg ?>">
        <input type="hidden" name="rekmed" value="<?= $jadwal->rekmed ?>">

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title" style="border-radius:0px !important">
                        <div class="caption"><i class="fa fa-reorder"></i>&nbsp;<b>Form</b></div>
                    </div>

                    <div class="portlet-body" style="border-radius:0px !important">
                        <div id="myBtnContainer">
                            <div class="filterBox 1">
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Cataract Surgery</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="cataract_surgery" value="OD" <?= isset($datacs->cataract_surgery)? ($datacs->cataract_surgery == "OD")? "checked" : "" : "" ?>>&nbsp; OD (Oculus Dextrus)&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="cataract_surgery" value="OS" <?= isset($datacs->cataract_surgery)? ($datacs->cataract_surgery == "Os")? "checked" : "" : "" ?>>&nbsp; OS (Oculus Sinister)</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Diabetics</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="diabetics" value="1" <?= isset($datacs->diabetics)? ($datacs->diabetics == "1")? "checked" : "" : "" ?>>&nbsp; Yes&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="diabetics" value="2" <?= isset($datacs->diabetics)? ($datacs->diabetics == "2")? "checked" : "" : "" ?>>&nbsp; No</label>
                                    </div>
                                </div>
                                <div class="row form-group form-horizontal">
                                    <label class="form-label col-sm-6">Pre-Operative Grade of Cataract Notes :</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="notes" rows="1" style="resize:none"><?= isset($datacs->notes)? $datacs->notes : "" ?></textarea>
                                    </div>
                                </div>
                                <div class="row form-group form-horizontal">
                                    <div class="col-sm-6" style="padding-left:0px !important">
                                        <label class="form-label col-sm-3">UCVA</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="ucva" value="<?= isset($datacs->ucva)? $datacs->ucva : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">AXL</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="axl" value="<?= isset($datacs->axl)? $datacs->axl : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">ACD</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="acd" value="<?= isset($datacs->acd)? $datacs->acd : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">LT</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="lt" value="<?= isset($datacs->lt)? $datacs->lt : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Formula</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="formula" value="<?= isset($datacs->formula)? $datacs->formula : "" ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6" style="padding-right:0px !important">
                                        <label class="form-label col-sm-3">BCVA</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="bcva" value="<?= isset($datacs->bcva)? $datacs->bcva : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Retinometri</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="retinometri" value="<?= isset($datacs->retinometri)? $datacs->retinometri : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-12">Dencity Cell Count</label>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">K1</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="k1" value="<?= isset($datacs->k1)? $datacs->k1 : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">K2</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="k2" value="<?= isset($datacs->k2)? $datacs->k2 : "" ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group form-horizontal">
                                    <label class="form-label col-sm-6">Target Emmetropia with IOL Power ⟶ A Constant</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="iolpowerconstant" value ="<?= isset($datacs->iolpowerconstant)? $datacs->iolpowerconstant : "" ?>">
                                    </div>
                                </div>
                                <br /><br /><br />
                                <div class="row form-group form-horizontal">
                                    <div class="col-sm-6" style="padding-left:0px !important">
                                        <label class="form-label col-sm-4">Intra Operative Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="intraoperativedate" value="<?= isset($datacs->intraoperativedate)? $datacs->intraoperativedate : date("Y-m-d") ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">OP Room</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="oproom" value="<?= isset($datacs->oproom)? $datacs->oproom : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Scrub</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="scrub" value="<?= isset($datacs->scrub)? $datacs->scrub : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Circulator</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="circulator" value="<?= isset($datacs->circulator)? $datacs->circulator : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                    </div>
                                    <div class="col-sm-6" style="padding-right:0px !important">
                                        <label class="form-label col-sm-3">Time</label>
                                        <div class="col-sm-9">
                                            <input type="time" class="form-control" name="intraoperativetime" value="<?= isset($datacs->intraoperativetime)? $datacs->intraoperativetime : date("H:i:s") ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-4">Type of Surgery</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="typeofsurgery" value="<?= isset($datacs->typeofsurgery)? $datacs->typeofsurgery : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Anesthesiologist</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="anesthesiologist" value="<?= isset($datacs->anesthesiologist)? $datacs->anesthesiologist : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                    </div>
                                </div>
                                <br /><br /><br />
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Anesthesia</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="anesthesia" value="Topical" <?= isset($datacs->anesthesia)? ($datacs->anesthesia == "Topical")? "checked" : "" : "" ?>>&nbsp;Topical&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="anesthesia" value="Sub-con/Tenon" <?= isset($datacs->anesthesia)? ($datacs->anesthesia == "Sub-con/Tenon")? "checked" : "" : "" ?>>&nbsp;Sub-con/Tenon&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="anesthesia" value="Blok" <?= isset($datacs->anesthesia)? ($datacs->anesthesia == "Blok")? "checked" : "" : "" ?>>&nbsp;Blok&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="anesthesia" value="General" <?= isset($datacs->anesthesia)? ($datacs->anesthesia == "General")? "checked" : "" : "" ?>>&nbsp;General</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Approach</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="approach" value="Temporal" <?= isset($datacs->approach)? ($datacs->approach == "Temporal")? "checked" : "" : "" ?>>&nbsp;Temporal&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="approach" value="Superior" <?= isset($datacs->approach)? ($datacs->approach == "Superior")? "checked" : "" : "" ?>>&nbsp;Superior&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="approach" value="Scleral Tunnel" <?= isset($datacs->approach)? ($datacs->approach == "Scleral Tunnel")? "checked" : "" : "" ?>>&nbsp;Scleral Tunnel&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="approach" value="Limbal" <?= isset($datacs->approach)? ($datacs->approach == "Limbal")? "checked" : "" : "" ?>>&nbsp;Limbal</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Intra COA</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="intracoa" value="Xylocard" <?= isset($datacs->intracoa)? ($datacs->intracoa == "Xylocard")? "checked" : "" : "" ?>>&nbsp;Xylocard&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="intracoa" value="Ephinephrine" <?= isset($datacs->intracoa)? ($datacs->intracoa == "Ephinephrine")? "checked" : "" : "" ?>>&nbsp;Ephinephrine&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="intracoa" value="Trypan Blue" <?= isset($datacs->intracoa)? ($datacs->intracoa == "Trypan Blue")? "checked" : "" : "" ?>>&nbsp;Trypan Blue</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Capsulatomy (CCC)</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="capsulatomy" value="Complete" <?= isset($datacs->capsulatomy)? ($datacs->capsulatomy == "Complete")? "checked" : "" : "" ?>>&nbsp;Complete&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="capsulatomy" value="Incomplete" <?= isset($datacs->capsulatomy)? ($datacs->capsulatomy == "Incomplete")? "checked" : "" : "" ?>>&nbsp;Incomplete&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="capsulatomy" value="Can Opener" <?= isset($datacs->capsulatomy)? ($datacs->capsulatomy == "Can Opener")? "checked" : "" : "" ?>>&nbsp;Can Opener</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Hydrodissection</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="hydrodissection" value="1" <?= isset($datacs->hydrodissection)? ($datacs->hydrodissection == "1")? "checked" : "" : "" ?>>&nbsp;Yes&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="hydrodissection" value="2" <?= isset($datacs->hydrodissection)? ($datacs->hydrodissection == "2")? "checked" : "" : "" ?>>&nbsp;No</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Nucleus Management</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="nucleus_management" value="Phaco" <?= isset($datacs->nucleus_management)? ($datacs->nucleus_management == "Phaco")? "checked" : "" : "" ?>>&nbsp;Phaco&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="nucleus_management" value="SICS" <?= isset($datacs->nucleus_management)? ($datacs->nucleus_management == "SICS")? "checked" : "" : "" ?>>&nbsp;SICS&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="nucleus_management" value="Manual ECCE" <?= isset($datacs->nucleus_management)? ($datacs->nucleus_management == "Manual ECCE")? "checked" : "" : "" ?>>&nbsp;Manual ECCE&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="nucleus_management" value="ICCE" <?= isset($datacs->nucleus_management)? ($datacs->nucleus_management == "ICCE")? "checked" : "" : "" ?>>&nbsp;ICCE</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Pacho Technique</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="pacho_technique" value="Stop & Chop" <?= isset($datacs->pacho_technique)? ($datacs->pacho_technique == "Stop & Chop")? "checked" : "" : "" ?>>&nbsp;Stop & Chop&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="pacho_technique" value="Horizontal Chop" <?= isset($datacs->pacho_technique)? ($datacs->pacho_technique == "Horizontal Chop")? "checked" : "" : "" ?>>&nbsp;Horizontal Chop&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="pacho_technique" value="Vertical Chop" <?= isset($datacs->pacho_technique)? ($datacs->pacho_technique == "Vertical Chop")? "checked" : "" : "" ?>>&nbsp;Vertical Chop&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="pacho_technique" value="D & C" <?= isset($datacs->pacho_technique)? ($datacs->pacho_technique == "D & C")? "checked" : "" : "" ?>>&nbsp;D & C</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">IOL Placement</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="iol_placement" value="Bag" <?= isset($datacs->iol_placement)? ($datacs->iol_placement == "Bag")? "checked" : "" : "" ?>>&nbsp;Bag&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="iol_placement" value="Sulcus" <?= isset($datacs->iol_placement)? ($datacs->iol_placement == "Sulcus")? "checked" : "" : "" ?>>&nbsp;Sulcus&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="iol_placement" value="Aphakia" <?= isset($datacs->iol_placement)? ($datacs->iol_placement == "Aphakia")? "checked" : "" : "" ?>>&nbsp;Aphakia&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="iol_placement" value="Other" <?= isset($datacs->iol_placement)? ($datacs->iol_placement == "Other")? "checked" : "" : "" ?>>&nbsp;Other</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Stitch</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="stitch" value="2" <?= isset($datacs->stitch)? ($datacs->stitch == "2")? "checked" : "" : "" ?>>&nbsp;No&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="stitch" value="1" <?= isset($datacs->stitch)? ($datacs->stitch == "1")? "checked" : "" : "" ?>>&nbsp;Yes (1-2-3-4-5-6-)</label>
                                    </div>
                                </div>
                                <div class="row form-group form-horizontal">
                                    <div class="col-sm-6" style="padding-left:0px !important">
                                        <label class="form-label col-sm-3">Final Incision</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="final_incision" value="<?= isset($datacs->final_incision)? $datacs->final_incision : "" ?>" placeholder="(mm)">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-4">Irigating Solution</label>
                                        <div class="col-sm-8">
                                            <input type="" class="form-control" name="irigating_solution" value="<?= isset($datacs->irigating_solution)? $datacs->irigating_solution : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Viscoelastic</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="viscoelastic" value="<?= isset($datacs->viscoelastic)? $datacs->viscoelastic : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Type of IOL</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="typeofiol" value="<?= isset($datacs->typeofiol)? $datacs->typeofiol : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                    </div>
                                    <div class="col-sm-6" style="padding-left:0px !important">
                                        <label class="form-label col-sm-3">Phaco Machine</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="pacho_machine" value="<?= isset($datacs->pacho_machine)? $datacs->pacho_machine : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Phaco Time</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="pacho_time" value="<?= isset($datacs->pacho_time)? $datacs->pacho_time : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">EPT</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="ept" value="<?= isset($datacs->ept)? $datacs->ept : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Complications</label>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="complication" value="1" <?= isset($datacs->complication)? ($datacs->complication == "1")? "checked" : "" : "" ?>>&nbsp; Yes&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="complication" value="2" <?= isset($datacs->complication)? ($datacs->complication == "2")? "checked" : "" : "" ?>>&nbsp; No</label>
                                    </div>
                                    <div class="col-sm-6 text-center"><b>IQL LABEL</b></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="posterion_capsule_rupture" <?= isset($datacs->posterion_capsule_rupture)? ($datacs->posterion_capsule_rupture == "on")? "checked" : "" : "" ?>>&nbsp;Posterior Capsule Rupture</label>
                                    </div>
                                    <div class="col-sm-9"></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="vitreous_prolapse" <?= isset($datacs->vitreous_prolapse)? ($datacs->vitreous_prolapse == "on")? "checked" : "" : "" ?>>&nbsp;Vitreous Prolapse</label>
                                    </div>
                                    <div class="col-sm-9">(During Phaco/Cortex Aspiration/IOL Implantation)</div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="vitrectomy" <?= isset($datacs->vitrectomy)? ($datacs->vitrectomy == "on")? "checked" : "" : "" ?>>&nbsp;Vitrectomy</label>
                                    </div>
                                    <div class="col-sm-9">(Manual/Machine)</div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="retained_lens_material" <?= isset($datacs->retained_lens_material)? ($datacs->retained_lens_material == "on")? "checked" : "" : "" ?>>&nbsp;Retained Lens Material</label>
                                    </div>
                                    <div class="col-sm-9">(Whole/More Than Half/Less Then half)</div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline"><input type="checkbox" name="cortex_left" <?= isset($datacs->cortex_left)? ($datacs->cortex_left == "on")? "checked" : "" : "" ?>>&nbsp;Cortex Left</label>
                                    </div>
                                    <div class="col-sm-9"></div>
                                </div>

                                <button type="button" class="btn green btn-md pull-right" onclick="filterSelection('2')">Selanjutnya &nbsp;<i class="fa fa-angle-right fa-fw"></i></button>
                                <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                                <br />
                            </div>

                            <div class="filterBox 2">
                                <div class="row form-group form-horizontal">
                                    <div class="col-sm-6" style="padding-left:0px !important">
                                        <label class="form-label col-sm-4">Post Operative Diagnose</label>
                                        <div class="col-sm-8">
                                            <input type="" class="form-control" name="post_operative_diagnose" value="<?= isset($datacs->post_operative_diagnose)? $datacs->post_operative_diagnose : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                        <label class="form-label col-sm-3">Therapy</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="therapy" value="<?= isset($datacs->therapy)? $datacs->therapy : "" ?>">
                                        </div>
                                        <br /><br /><br />
                                    </div>
                                    <div class="col-sm-6" style="padding-right:0px !important">
                                        <label class="form-label col-sm-3">Kode ICD</label>
                                        <div class="col-sm-9">
                                            <input type="" class="form-control" name="kodeicd" value="<?= isset($datacs->kodeicd)? $datacs->kodeicd : "" ?>">
                                        </div>
                                    </div>
                                </div>
                                <p><b>INSTRUCTIONS</b></p>
                                <ol>
                                    <li>
                                        Hindari mata dari air dan sabun selama&emsp;
                                        <input type="text" class="form-control" name="instruction_1" style="padding:0px;height:20px;width:10%;display:inline-block" value="<?= isset($datacs->instruction_1)? $datacs->instruction_1 : "" ?>">&emsp;Hari
                                    </li>
                                    <li>Jangan menggosok atau menekan mata</li>
                                    <li>Gunakan pelindung mata (DOP) selama 1 minggu atau kacamata selama 1 bulan pada waktu tidur, termasuk tidur siang</li>
                                    <li>Cuci tangan sebelum meneteskan obat mata</li>
                                    <li>Hindari daerah berdebu dan hewan peliharaan selama 2 minggu</li>
                                    <li style="padding:5px 0px 5px 0px">
                                        Kontrol hari&emsp;
                                        <input type="text" class="form-control" name="instruction_2" style="padding:0px;height:20px;width:20%;display:inline-block" value="<?= isset($datacs->instruction_2)? $datacs->instruction_2 : "" ?>">
                                    </li>
                                    <li style="padding:5px 0px 5px 0px">
                                        Tanggal&emsp;
                                        <input type="date" class="form-control" name="instruction_3" style="padding:0px;height:20px;width:20%;display:inline-block" value="<?= isset($datacs->instruction_3)? $datacs->instruction_3 :date("Y-m-d") ?>">
                                    </li>
                                    <li style="padding:5px 0px 5px 0px">
                                        Tempat&emsp;
                                        <input type="text" class="form-control" name="instruction_4" style="padding:0px;height:20px;width:20%;display:inline-block" value="<?= isset($datacs->instruction_4)? $datacs->instruction_4 : "" ?>">
                                    </li>
                                </ol>
                                <br /><br />
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Speciment</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" name="speciment" value="1" <?= isset($datacs->speciment)? ($datacs->speciment == "1")? "checked" : "" : "" ?>>&nbsp; Yes&emsp;</label>
                                        <label class="checkbox-inline"><input type="checkbox" name="speciment" value="2" <?= isset($datacs->speciment)? ($datacs->speciment == "2")? "checked" : "" : "" ?>>&nbsp; No</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="form-label col-sm-3" style="margin-top:0px">Jika (Yes)</label>
                                    <div class="col-sm-6">
                                        <textarea type="text" class="form-control" name="speciment_ket" rows="2" style="resize:none"><?= isset($datacs->speciment_ket)? $datacs->speciment_ket : "" ?></textarea>
                                    </div>
                                </div>

                                <br />
                                <button type="button" class="btn default btn-md pull-left" onclick="filterSelection('1')"><i class="fa fa-angle-left fa-fw"></i>&nbsp; Sebelumnya</button>
                                <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp;  <?= ($statuscs == "undone")? "Simpan" : "Update" ?></button>
                                <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                                <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
    </form>

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
            <?php if($statuscs == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakcs/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#save").on("click", function(){
            var post_form = $("#frmcs").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statuscs == "undone"): ?>
                    url: "/Bedah_Central/save_cataract_surgery/",
                <?php else: ?>
                    url: "/Bedah_Central/save_cataract_surgery/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Form Cataract Surgery Berhasil Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statuscs == "undone"): ?>
                            swal({
                                html: "Form Cataract Surgery Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetakcs/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Form Cataract Surgery Berhasil Diupdate",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            });
                        <?php endif; ?>
                    }
                },
                error: function(data, jqXHR, textStatus, errorThrown) {
                    swal({
                        html: textStatus,
                        type: "error",
                        confirmButtonText: "Close",
                        confirmButtonColor: "red"
                    });
                }
            })
        });

        filterSelection("1")
        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("filterBox");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                w3RemoveClass(x[i], "filterBoxshow");
                if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "filterBoxshow");
            }
        }

        function w3AddClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {
                    element.className += " " + arr2[i];
                }
            }
        }

        function w3RemoveClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++){
                while (arr1.indexOf(arr2[i]) > -1){
                    arr1.splice(arr1.indexOf(arr2[i]), 1);
                }
            }
            element.className = arr1.join(" ");
        }

        var btnContainer = document.getElementById("myBtnContainer");
        var btns = btnContainer.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                // var current = document.getElementsByClassName("btn green");
                // current[0].className = current[0].className.replace(" green", "");
                // this.className += " green";
            });
        }

        function back(){
            var thiloc = window.location;
            window.close(thiloc);
        }
    </script>