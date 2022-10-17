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
        .filterBoxtitle {font-size:18px;font-weight:bold;display:block}
        .filterBox {display:none}
        .filterBoxshow {display:block}
        .form-label {font-weight:bold}
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
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Umur</td>
                            <td style="width:35%">
                                <?php
                                    $birth  = ($jadwal->tgllahir == "")? date("Y") : $jadwal->tgllahir;
                                    $today  = date("Y");
                                    $age    = $today - $birth;
                                    echo $age ." tahun";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Nama</td>
                            <td style="width:35%"><?= $jadwal->namapas ?></td>
                            <td style="font-weight:bold;background:#f5f5f5;width:15%">Kelas</td>
                            <td style="width:35%"><?= $jadwal->kelas ?></td>
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

    <form id="frmlo">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Dokter Operator</td>
                                        <td>
                                            <select type="text" id="droperator" name="droperator" class="form-control selectpicker" data-live-search="true"
                                            data-width="100%" onkeypress="return tabE(this,event)"
                                            style='color:#222 !important'>
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($dokter as $dkey => $dval):
                                                        if($statuslo == "done"){
                                                            $droperatorhead = $datalo->droperator;
                                                        } else {
                                                            $droperatorhead = $jadwal->droperator;
                                                        }

                                                        if($dval->kodokter == $droperatorhead):
                                                ?>
                                                    <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Pembiayaan</td>
                                        <td style="width:30%">
                                            <input type="text" class="form-control" name="pembiayaan" id="educost" value="<?= isset($datalo->pembiayaan)? number_format($datalo->pembiayaan, 0, ".", ",") : "" ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Dokter Anestesi</td>
                                        <td style="width:30%">
                                            <select type="text" id="dranestesi" name="dranestesi" class="form-control selectpicker" data-live-search="true"
                                            data-width="100%" onkeypress="return tabE(this,event)"
                                            style='color:#222 !important'>
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($dokter as $dkey => $dval):
                                                        if($statuslo == "done"){
                                                            $dranestasihead = $datalo->dranestesi;
                                                        } else {
                                                            $dranestasihead = $jadwal->dranestasi;
                                                        }

                                                        if($dval->kodokter == $dranestasihead):
                                                ?>
                                                    <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Mulai Jam</td>
                                        <td style="width:30%">
                                            <input type="time" class="form-control" name="mulai_jam" value="<?= isset($datalo->mulai_jam)? $datalo->mulai_jam : date("H:i:s") ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Perawat Asisten</td>
                                        <td style="width:30%">
                                            <select type="text" id="perawat_asisten1" name="perawat_asisten1" class="form-control selectpicker" data-live-search="true"
                                            data-width="100%" onkeypress="return tabE(this,event)"
                                            style='color:#222 !important;margin-bottom:10px !important'>
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($dokter as $dkey => $dval):
                                                        if($statuslo == "done"){
                                                            $asdroperatorhead = $datalo->perawat_asisten1;
                                                        } else {
                                                            $asdroperatorhead = $jadwal->asdroperator;
                                                        }

                                                        if($dval->kodokter == $asdroperatorhead):
                                                ?>
                                                    <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                            <select type="text" id="perawat_asisten2" name="perawat_asisten2" class="form-control selectpicker" data-live-search="true"
                                            data-width="100%" onkeypress="return tabE(this,event)"
                                            style='color:#222 !important'>
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($dokter as $dkey => $dval):
                                                        if($statuslo == "done"){
                                                            $asdroperatarhead = $datalo->perawat_asisten2;
                                                        } else {
                                                            $asdroperatarhead = $jadwal->asdroperatar;
                                                        }

                                                        if($dval->kodokter == $asdroperatarhead):
                                                ?>
                                                    <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Selesai Jam</td>
                                        <td style="width:30%">
                                            <input type="time" class="form-control" name="selesai_jam" value="<?= isset($datalo->selesai_jam)? $datalo->selesai_jam : date("H:i:s") ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Perawat Anestesi</td>
                                        <td style="width:30%">
                                            <select type="text" id="perawat_anestesi" name="perawat_anestesi" class="form-control selectpicker" data-live-search="true"
                                            data-width="100%" onkeypress="return tabE(this,event)"
                                            style='color:#222 !important'>
                                                <option value="">--- Pilih Data ---</option>
                                                <?php
                                                    foreach($dokter as $dkey => $dval):
                                                        if($statuslo == "done"){
                                                            $asdranestasihead = $datalo->perawat_anestesi;
                                                        } else {
                                                            $asdranestasihead = $jadwal->asdranestasi;
                                                        }

                                                        if($dval->kodokter == $asdranestasihead):
                                                ?>
                                                    <option value="<?= $dval->kodokter ?>" selected><?= $dval->nadokter ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $dval->kodokter ?>"><?= $dval->nadokter ?></option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Tanggal</td>
                                        <td style="width:30%">
                                            <input type="date" class="form-control" name="tanggal" value="<?= isset($datalo->tanggal)? $datalo->tanggal : date("Y-m-d") ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Diagnosa Pra Operative</td>
                                        <td style="width:30%">
                                            <textarea type="text" class="form-control" name="diagnosa_pra_operative" rows="2" style="resize:none"><?= isset($datalo->diagnosa_pra_operative)? $datalo->diagnosa_pra_operative : $praoperative ?></textarea>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">
                                            1. VOD
                                            <br />
                                            2. TOD
                                        </td>
                                        <td style="width:30%">
                                            <input type="text" class="form-control" name="vod" value="<?= isset($datalo->vod)? $datalo->vod : "" ?>">
                                            <br />
                                            <input type="text" class="form-control" name="tod" value="<?= isset($datalo->tod)? $datalo->tod : "" ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Diagnosa Post Operative</td>
                                        <td style="width:30%">
                                            <textarea type="text" class="form-control" name="diagnosa_post_operative" rows="2" style="resize:none"><?= isset($datalo->diagnosa_post_operative)? $datalo->diagnosa_post_operative : $postoperative ?></textarea>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">
                                            1. VOS
                                            <br />
                                            2. TOS
                                        </td>
                                        <td style="width:30%">
                                            <input type="text" class="form-control" name="vos" value="<?= isset($datalo->vos)? $datalo->vos : "" ?>">
                                            <br />
                                            <input type="text" class="form-control" name="tos" value="<?= isset($datalo->tos)? $datalo->tos : "" ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Metode Operasi</td>
                                        <td style="width:30%">
                                            <textarea type="text" class="form-control" name="metode_operasi" rows="2" style="resize:none"><?= isset($datalo->metode_operasi)? $datalo->metode_operasi : "" ?></textarea>
                                        </td>
                                        <td style="border-right:none !important;font-weight:bold;width:20%">Anestesi</td>
                                        <td style="width:30%">
                                            <select type="text" class="form-control" name="anestesi">
                                                <option value="">--- Pilih Data ---</option>
                                                <option value="Umum" <?= isset($datalo->anestesi)? ($datalo->anestesi == "Umum")? "selected" : "" : isset($jenisan)? ($jenisan == "Umum")? "selected" : "" : "" ?>>Umum</option>
                                                <option value="Lokal" <?= isset($datalo->anestesi)? ($datalo->anestesi == "Lokal")? "selected" : "" : isset($jenisan)? ($jenisan == "Lokal")? "selected" : "" : "" ?>>Lokal</option>
                                                <option value="Topikal" <?= isset($datalo->anestesi)? ($datalo->anestesi == "Topikal")? "selected" : "" : isset($jenisan)? ($jenisan == "Topikal")? "selected" : "" : "" ?>>Topikal</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td style="width:33.3333333333%">
                                            <p style="font-weight:bold">Pelaksanaan Operasi</p>
                                            <select type="text" class="form-control" name="pelaksanaan_operasi">
                                                <option value="" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "")? "selected" : "" : "" ?>>--- Pilih Data ---</option>
                                                <option value="Khusus" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Khusus")? "selected" : "" : "" ?>>Khusus</option>
                                                <option value="Mayor" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Mayor")? "selected" : "" : "" ?>>Mayor</option>
                                                <option value="Medium" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Medium")? "selected" : "" : "" ?>>Medium</option>
                                                <option value="Minor" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Minor")? "selected" : "" : "" ?>>Minor</option>
                                                <option value="Darurat" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Darurat")? "selected" : "" : "" ?>>Darurat</option>
                                                <option value="Biasa" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Biasa")? "selected" : "" : "" ?>>Biasa</option>
                                                <option value="Elektif" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Elektif")? "selected" : "" : "" ?>>Elektif</option>
                                                <option value="Poliklinik" <?= isset($datalo->pelaksanaan_operasi)? ($datalo->pelaksanaan_operasi == "Poliklinik")? "selected" : "" : "" ?>>Poliklinik</option>
                                            </select>
                                        </td>
                                        <td style="width:33.3333333333%">
                                            <p style="font-weight:bold">Kondisi Mata</p>
                                            <select type="text" class="form-control" name="kondisi_mata">
                                                <option value="" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "")? "selected" : "" : "" ?>>--- Pilih Data ---</option>
                                                <option value="Corneal Disease" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Corneal Disease")? "selected" : "" : "" ?>>Corneal Disease</option>
                                                <option value="High Myopia" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "High Myopia")? "selected" : "" : "" ?>>High Myopia</option>
                                                <option value="Lens Included Glaucoma" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Lens Included Glaucoma")? "selected" : "" : "" ?>>Lens Included Glaucoma</option>
                                                <option value="Posterior Syncohia" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Posterior Syncohia")? "selected" : "" : "" ?>>Posterior Syncohia</option>
                                                <option value="PXF" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "PXF")? "selected" : "" : "" ?>>PXF</option>
                                                <option value="Retinal Pathology" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Retinal Pathology")? "selected" : "" : "" ?>>Retinal Pathology</option>
                                                <option value="Shallow + A" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Shallow + A")? "selected" : "" : "" ?>>Shallow + AC</option>
                                                <option value="Subluxeted Lens" <?= isset($datalo->kondisi_mata)? ($datalo->kondisi_mata == "Subluxeted Lens")? "selected" : "" : "" ?>>Subluxeted Lens</option>
                                            </select>
                                        </td>
                                        <td style="width:33.3333333333%">
                                            <p style="font-weight:bold">Pengiriman Specimen</p>
                                            <select type="text" class="form-control" name="pengiriman_specimen">
                                                <option value="">--- Pilih Data ---</option>
                                                <option value="Ya" <?= isset($datalo->pengiriman_specimen)? ($datalo->pengiriman_specimen == "Ya")? "selected" : "" : isset($speciment)? ($speciment == "1")? "selected" : "" : "" ?>>Ya</option>
                                                <option value="Tidak" <?= isset($datalo->pengiriman_specimen)? ($datalo->pengiriman_specimen == "Tidak")? "selected" : "" : isset($speciment)? ($speciment == "2")? "selected" : "" : "" ?>>Tidak</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-striped" style="width:100%;margin:auto">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><div  class="filterBoxtitle">Uraian Pembedahan</div></td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="msics" <?= isset($datalo->msics)? ($datalo->msics == "on")? "checked" : "" : "" ?>>&nbsp; M.SICS</label></b></p>
                                            <ol style="font-size:10px !important">
                                                <li>Bersihkan Lap. Operasi</li>
                                                <li>Pasang Eye Speculum</li>
                                                <li>Irigasi dengan RL</li>
                                                <li>Anestasi dengan Lidocain 2%</li>
                                                <li>Buat Konjungtiva flap ⟶ Kauter Sampai Darah Berhenti</li>
                                                <li>Buat Frwan Insisi di Sklera Lanjutkan dengan Tannel, Tembus Kornea Untuk Side Port</li>
                                                <li>Beri Vision Blue, Irigasi dengan RL</li>
                                                <li>Beri Metil Celulose atau Visco Elastic Baru Capsuleroksis ⟶ Tembus Sklera Hydrodissection ⟶ Dengan Keratome Baru Luksir Nucleus Dengan Vectis</li>
                                                <li>Aspirasi Irigasi dengan Simcoe</li>
                                                <li>Injeksi IOL, Bersihkan Kamera Oculi Anterior dengan Simcoe</li>
                                                <li>Odemkan Kornea di Daerah Side Port</li>
                                                <li>injeksi Genta + Dexa Subkonjungtiva ⟶ Kauter Konjungtiva</li>
                                                <li>Beri Bethadine ⟶ Tutup Kain Kassa Steril / Kacamata Pelindung Tembus Pandang</li>
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <p><b>Riwayat Penyakit Lainnya :</b></p>
                                            <textarea type="text" class="form-control" name="riwayat_penyakit" rows="4" style="resize:none"><?= isset($datalo->riwayat_penyakit)? $datalo->riwayat_penyakit : "" ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="pachoemulsifikasi" <?= isset($datalo->pachoemulsifikasi)? ($datalo->pachoemulsifikasi == "on")? "checked" : "" : "" ?>>&nbsp; Pachoemulsifikasi</label></b></p>
                                            <ol style="font-size:10px !important">
                                                <li>Bersihkan Lap. Operasi</li>
                                                <li>Pasang Eye Speculum</li>
                                                <li>Insisi Kornea</li>
                                                <li>Lakukan Operasi Katarak Pachoemulsifikasi Sesuai dengan Standar Prosedur + IOL (Intra Okular Lensa)</li>
                                                <li>Beri Bethadine Tetes</li>
                                                <li>Tutup Kain Kassa Steril / Kacamata Pelindung Tembus Pandang</li>
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <p><b>Komplikasi / Operasi :</b></p>
                                            <textarea type="text" class="form-control" name="komplikasi_operasi" rows="4" style="resize:none"><?= isset($datalo->komplikasi_operasi)? $datalo->komplikasi_operasi : "" ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="pterigium" <?= isset($datalo->pterigium)? ($datalo->pterigium == "on")? "checked" : "" : "" ?>>&nbsp; Pterigium</label></b></p>
                                            <ol style="font-size:10px !important">
                                                <li>Bersihkan Lap. Operasi</li>
                                                <li>Pasang Eye Speculum</li>
                                                <li>Anestesi Topikal dengan Lidocain 2 %</li>
                                                <li>Gunting Pterigium di Daerah Limbus, dan Extirpati Pt, Sampai Kornea Sehingga Bersih</li>
                                                <li>Hentikan Jika Ada Pendarahan</li>
                                                <li>Beri Tetes Bethadine dan Zalf Chlorampenicol</li>
                                                <li>Tutup Kain Kassa Steril</li>
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <p><b>Catatan :</b></p>
                                            <textarea type="text" class="form-control" name="catatan" rows="4" style="resize:none"><?= isset($datalo->catatan)? $datalo->catatan : "" ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="trabeculektomy" <?= isset($datalo->trabeculektomy)? ($datalo->trabeculektomy == "on")? "checked" : "" : "" ?>>&nbsp; Trabeculektomy</label></b></p>
                                            <ol style="font-size:10px !important">
                                                <li>Bersihkan Lap. Operasi</li>
                                                <li>Pasang Eye Speculum</li>
                                                <li>Buat Flap Conjungtiva ⟶ Cauter</li>
                                                <li>Buat Flap Segi 4, ½ Tebal Sklera</li>
                                                <li>Gunting Trabekula Segitiga △</li>
                                                <li>Iridektomi</li>
                                                <li>Hecting Sklera dengan Nylon 10-0</li>
                                                <li>Hecting Conjungtiva Sampai Rapat</li>
                                                <li>Beri Bethadine</li>
                                                <li>Tutup Kassa Steril</li>
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <p><b>Therapi :</b></p>
                                            <textarea type="text" class="form-control" name="terapi" rows="4" style="resize:none"><?= isset($datalo->terapi)? $datalo->terapi : isset($therapi)? $therapi : "" ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="eviscerasi" <?= isset($datalo->eviscerasi)? ($datalo->eviscerasi == "on")? "checked" : "" : "" ?>>&nbsp; Eviscerasi</label></b></p>
                                            <ol style="font-size:10px !important">
                                                <li>Bersihkan Lap. Operasi</li>
                                                <li>Injeksi Peribulbar dengan Lidocain 2%</li>
                                                <li>Pasang Eye Speculum, Tembus Bola Mata di Daerah Limbus, Kemudian Gunting Kornea 360°</li>
                                                <li>Curetage Isi Bola Mata Sampai Bersih</li>
                                                <li>Pasang Tampon Sampai Darah Berhenti</li>
                                                <li>Hecting Sklera 3 Buah dengan Benang Slik</li>
                                                <li>Beri Bethadine dan Zalf Chlorampenicol Tampon</li>
                                                <li>Tutup dengan Kassa Steril</li>
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <p><b>Keratometri & Biometri (k1 & K2) :</b></p>
                                            <textarea type="text" class="form-control" name="k1" rows="2" style="resize:none" value="k1"><?= isset($datalo->k1)? $datalo->k1 : isset($k)? $k1 : "K1" ?></textarea>
                                            <textarea type="text" class="form-control" name="k2" rows="2" style="resize:none" value="k2"><?= isset($datalo->k2)? $datalo->k2 : isset($k)? $k2 : "K2" ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">
                                            <p><b><label class="checkbox-inline"><input type="checkbox" name="resimco" <?= isset($datalo->resimco)? ($datalo->resimco == "on")? "checked" : "" : "" ?>>&nbsp; Resimco</label></b></p>
                                            <ol style="font-size:10px !important">
                                                    
                                            </ol>
                                        </td>
                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label for="implantasi" class="form-label">Implantasi/IOL : 16, 17, 18, 19, 20, 21 :</label>
                                                <input type="text" class="form-control" name="implantasi" value="<?= isset($datalo->implantasi)? $datalo->implantasi : "" ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="jahitan" class="form-label">Jahitan</label>
                                                <select type="text" class="form-control" name="jahitan">
                                                    <option value="">--- Pilih ---</option>
                                                    <option value="Ya" <?= isset($datalo->jahitan)? ($datalo->jahitan == "Ya")? "selected" : "" : "" ?>>Ya</option>
                                                    <option value="Tidak" <?= isset($datalo->jahitan)? ($datalo->jahitan == "Tidak")? "selected" : "" : "" ?>>Tidak</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nomor" class="form-label">Nomor</label>
                                                <input type="text" class="form-control" name="nomor" value="<?= isset($datalo->nomor)? $datalo->nomor : "" ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                <input type="text" class="form-control" name="jumlah" value="<?= isset($datalo->jumlah)? $datalo->jumlah : "" ?>">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br />
                        <button type="button" class="btn green btn-md pull-right" id="save"><i class="fa fa-save fa-fw"></i>&nbsp; <?= ($statuslo == "undone")? "Simpan" : "Update" ?></button>
                        <button type="button" class="btn yellow btn-md pull-right" id="cetak" style="margin-right:15px"><i class="fa fa-print fa-fw"></i>&nbsp; Cetak</button>
                        <button type="button" class="btn red btn-md pull-right" onclick="back()" style="margin-right:15px"><i class="fa fa-times fa-fw"></i>&nbsp; Tutup</button>
                        <br /><br />
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
            <?php if($statuslo == "undone"): ?>
            $("#cetak").hide();
            <?php else: ?>
            $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetaklo/<?= $idcetak ?>', '_blank')");
            <?php endif; ?>
        });

        $("#educost").on("keyup", function(){
            var cost    = $(this).val();
            return $("#educost").val(cost.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        });

        $("#save").on("click", function(){
            var post_form = $("#frmlo").serialize();

            console.log(post_form);

            $.ajax({
                <?php if($statuslo == "undone"): ?>
                    url: "/Bedah_Central/save_laporan_operasi/",
                <?php else: ?>
                    url: "/Bedah_Central/save_laporan_operasi/1",
                <?php endif; ?>
                data: post_form,
                type: "POST",
                success: function(data){
                    if(data.status == 0){
                        swal({
                            html: "Laporan Operasi Gagal Dibuat",
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "red"
                        });
                    } else {
                        <?php if($statuslo == "undone"): ?>
                            swal({
                                html: "Laporan Operasi Berhasil Dibuat",
                                type: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "green"
                            }).then(function(){
                                $("#save").hide();
                                $("#cetak").show();
                                $("#cetak").attr("onclick", "window.open('/Bedah_Central/cetaklo/"+data.idcetak+"', '_blank')");
                            });
                        <?php else: ?>
                            swal({
                                html: "Laporan Operasi Berhasil Diupdate",
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
    </script>