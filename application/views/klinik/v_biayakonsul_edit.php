<?php
$this->load->view('template/header');
$this->load->view('template/body');
date_default_timezone_set("Asia/Jakarta");
?>
<style>
    .btn-group a {
        color: white;
        text-decoration: none;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            <span class="title-web"> Kasir <small>Pembayaran Jasa Konsultasi dan Resep</small>
            </span>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url(); ?>kasir_konsul">
                    Daftar Pembayaran
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Tindakan & Pembayaran
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>Pembayaran Konsultasi dan Tindakan
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmkonsul" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabsx nav-pills">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Entry Tindakan
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Pembayaran
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab3" data-toggle="tab">
                                   Farmasi
								</a>
							</li-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="note note-info">

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">No. Registrasi <font color="red">*
                                                </font></label>
                                            <div class="col-md-9">
                                                <select class="form-control select2_el_registrasi" id="noreg" name="noreg" onchange="getdataregistrasi()" disabled>
                                                    <?php
                                                    if ($register->noreg) { ?>
                                                        <option value="<?= $register->noreg; ?>"><?= $register->noreg; ?>
                                                        </option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Klinik<font color="red">*</font>
                                            </label>
                                            <div class="col-md-9">
                                                <?php 
                                                if($register->kodepos){
                                                    $sql = $this->db->get_where('tbl_namapos', ['kodepos' => $register->kodepos])->row();
                                                }
                                                ?>
                                                <input type="text" id="reg_klinik" name="reg_klinik" readonly class="form-control" value="<?= $sql->kodepos?>" placeholder="<?= $sql->namapost; ?>">
                                                <!-- <select class="form-control select2_el_poli" id="reg_klinik" name="reg_klinik" onchange="getdataklinik()"> -->
                                                    <?php
                                                    // if ($register->kodepos) {
                                                        // $namapoli = data_master('tbl_namapos', array('kodepos' => $register->kodepos))->namapost; ?>
                                                        <!-- <option value="<?= $register->kodepos; ?>"><?= $namapoli; ?></option> -->
                                                    <?php 
                                                    // }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Tanggal & Jam <font color="red">*
                                                </font></label>
                                            <div class="col-md-9">
                                                <input type="datetime-local" value="<?= date('Y-m-d\TH:m', strtotime($register->tglmasuk)); ?>" class="form-control" id="reg_tanggal" name="reg_tanggal" readonly>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="note note-success">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">No. MR <font color="red"></font>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="pasien" name="pasien" value="<?= $register->rekmed; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Nama Pasien <font color="red"></font>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" value="<?= $kasir->namapas; ?>" class="form-control" id="reg_namapasien" name="reg_namapasien" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Poli/Unit <font color="red"></font>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" value="<?= $register->kodepos; ?>" class="form-control" id="reg_poli" name="reg_poli" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">No.Hp <font color="red"></font>
                                            </label>

                                            <div class="col-md-7">
                                                <input type="text" value="<?= $kasir->handphone; ?>" class="form-control" id="reg_hp" name="reg_hp" readonly>
                                            </div>

                                            <div class="col-md-2">
                                                <input checked=checked type="checkbox" id="reg_cekhp" name="reg_cekhp" value="1" class="form-control">
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span class="label label-info title-white">KONSULTASI DAN TINDAKAN</span>
                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead class="breadcrumb">
                                            <th class="title-white" width="25%" style="text-align: center">Tindakan</th>
                                            <th class="title-white" width="15%" style="text-align: center">Dokter</th>
                                            <th class="title-white" width="15%" style="text-align: center">Perawat</th>
                                            <th class="title-white" width="15%" style="text-align: center">Total Jasa
                                                (Rp)</th>
                                            <th class="title-white" width="5%" style="text-align: center">Diskon %</th>

                                            <th class="title-white" width="10%" style="text-align: center">Total Diskon
                                            </th>
                                            <th class="title-white" width="15%" style="text-align: center">Total Net
                                                (Rp)</th>

                                        </thead>

                                        <tbody>
                                            <?php
                                            $no =1;
                                            foreach ($konsuld as $detil) { ?>
                                                <tr>
                                                    <td width="25%">
                                                        <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_tarif_tindakan form-control input-largex" onchange="showbarangname(this.value, <?= $no; ?>)">
                                                            <?php
                                                            if ($detil->kodetarif) { ?>
                                                                <option value="<?= $detil->kodetarif; ?>">
                                                                    <?= $detil->kodetarif . ' | ' . $detil->tindakan; ?>
                                                                </option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td width="10%">
                                                        <select name="dokter[]" id="dokter<?= $no; ?>" class="select2_el_dokter form-control input-largex">
                                                            <?php
                                                            if ($detil->kodokter) {
                                                                $namadr = data_master('tbl_dokter', array('kodokter' => $detil->kodokter))->nadokter; ?>
                                                                <option value="<?= $detil->kodokter; ?>">
                                                                    <?= $detil->kodokter . ' | ' . $namadr; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td width="10%">
                                                        <select name="perawat[]" id="perawat<?= $no; ?>" class="select2_el_perawat form-control input-largex">
                                                            <?php
                                                            if ($detil->koperawat) {
                                                                $namadr = data_master('tbl_dokter', array('kodokter' => $detil->koperawat))->nadokter; ?>
                                                                <option value="<?= $detil->koperawat; ?>">
                                                                    <?= $detil->koperawat . ' | ' . $namadr; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </td>

                                                    <td width="10%">
                                                        <input name="harga[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($detil->tarifrs + $detil->tarifdr + $detil->obatpoli + $detil->paramedis, 2); ?>" id="harga<?= $no; ?>" type="text" class="form-control rightJustified">
                                                        <input name="feemedis[]" value="<?= number_format($detil->paramedis, 2) ;?>" id="feemedis<?= $no; ?>" type="hidden" class="form-control rightJustified" readonly>
                                                        <input name="bhp[]" value="<?= number_format($detil->obatpoli, 2) ;?>" id="bhp<?= $no; ?>" type="hidden" class="form-control rightJustified" readonly>
                                                        <input name="tarifrs[]" value="<?= number_format($detil->tarifrs, 2) ;?>" id="tarifrs<?= $no; ?>" type="hidden" class="form-control rightJustified" readonly>
                                                        <input name="tarifdr[]" value="<?= number_format($detil->tarifdr, 2) ;?>" id="tarifdr<?= $no; ?>" type="hidden" class="form-control rightJustified" readonly>
                                                    </td>

                                                    <td width="7%">
                                                        <input name="disc1[]" onchange="totalline(<?= $no; ?>)" value="<?= $detil->diskpr; ?>" id="disc1<?= $no; ?>" type="text" class="form-control rightJustified ">
                                                    </td>

                                                    <td width="10%">
                                                        <input name="disc2[]" onchange="totalline(<?= $no; ?>)" value="<?= number_format($detil->diskrp, 2); ?>" id="disc2<?= $no; ?>" type="text" class="form-control rightJustified ">
                                                    </td>

                                                    <td width="20%">
                                                        <input name="jumlah[]" id="jumlah<?= $no; ?>" type="text" value="<?= number_format(($detil->tarifrs + $detil->tarifdr + $detil->obatpoli + $detil->paramedis) - $detil->diskrp, 2); ?>" class="form-control rightJustified" size="40%" onchange="total()">
                                                    </td>

                                                </tr>
                                            <?php $no++; } ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i></button>
                                    <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 invoice-block">
                                    </br>
                                    <span class="label label-info"><b>ADM PASIEN BARU</b></span>
                                    <table id="adm" class="table  table-condensed table-scrollable">
                                        <thead class="breadcrumb">
                                            <th class="title-white" width="25%" style="text-align: center">Pasien</th>
                                            <th class="title-white" width="15%" style="text-align: center">Total (Rp)
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input align="left" type="text" class="form-control" value="ADM PASIEN BARU" readonly=""></td>
                                                <td><input type="text" class="form-control rightJustified" name="admrp1" id="admrp1" value="<?= $kasir->adm; ?>" onchange="total_net()" readonly=""></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xs-4 invoice-block">
                                    </br>
                                    <span class="label label-info title-white">RESEP YANG SDH DITEBUS</span>
                                    <table id="datatable_resep" class="table  table-condensed table-scrollable">
                                        <thead class="breadcrumb">
                                            <th class="title-white" width="25%" style="text-align: center">Nomor Resep
                                            </th>
                                            <th class="title-white" width="15%" style="text-align: center">Nilai Resep (Rp)</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xs-4 invoice-block pull-right">
                                    <div class="well">
                                        <table>
                                            <tr>
                                                <td width="40%"><strong>TOTAL ADM</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vsubadm"></span></strong></td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>TOTAL KONSUL</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>TOTAL RESEP</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vtotalresep"></span></strong></td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>TOTAL DISK /R</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vtotalresepdisc"></span></strong></td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>TOTAL DISK TIN/KON</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vtotaldiskon"></span></strong></td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>GRAND TOTAL</strong></td>
                                                <td width="1%"><strong>:</strong></td>
                                                <td width="59" align="right"><strong><span id="_vtotal"></span></strong>
                                                </td>
                                            </tr>
                                            <input type="hidden" id="tersimpan">
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <!--div class="wells"-->
                                    <div class="form-actions">


                                        <!--button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> UPDATE</button>
									<button id="btnbayar" type="button" onclick="bayar()" class="btn yellow"><i class="fa fa-money"></i> BAYAR</button-->
                                        <?php
                                        $level = $this->session->userdata('user_level');
                                        // var_dump($level); die;
                                        if ($level == 1 || $level == 2) { ?>
                                        <!-- script original -->
                                            <!-- <div class="btn-group">
                                                <button type="button" id="btnsimpan" class="btn green">
                                                    <b>
                                                        <i class="fa fa-save"></i></i> <a href="<?= site_url('Kasir_konsul/update/' . $id) ?>">UPDATE</a>
                                                    </b>
                                                </button>
                                            </div> -->
                                            
                                            <!-- hsuain change -->
                                            <input type="hidden" name="kondisi" id="kondisi" value="<?= $id; ?>">
                                            <div class="btn-group">
                                                <button type="button" id="btnsimpan" class="btn green" onclick="update_data()">
                                                    <b>
                                                        <i class="fa fa-save"></i> UPDATE
                                                    </b>
                                                </button>
                                            </div>
                                            <!-- end husain -->

                                        <?php } ?>

                                        <!-- <div class="btn-group">
                                            <button type="button" class="btn green"
                                                onclick="this.form.reset();location.reload();"><b><i
                                                        class="fa fa-pencil-square-o"></i> TAMBAH</b></button>
                                        </div> -->
                                        <a href="<?= base_url('kasir_konsul') ?>" class="btn btn red"><b><i class="fa fa-undo"></i> KEMBALI</b></a>
                                        <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan...
                                            </span> <span id="success" style="display:none; color:#0C0">Data sudah
                                                disimpan...</span></h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">
                            <!--form id="frmbayar" class="form-horizontal" method="post"-->
                            <!-- harimas -->
                            <div class="col-md-12">
                                <div class="portlet box blue">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-reorder"></i><b>KWITANSI</b>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table">
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <!-- <tr>
                                                        <td>&nbsp;</td>
                                                        <td>NO. KWITANSI</td>
                                                        <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="<?= $kasir->nokwitansi; ?>" readonly></td>

                                                        <td>FAKTUR PAJAK</td>
                                                        <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="<?= $kasir->fakturpajak; ?>">
                                                        </td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>TGL & JAM BAYAR</td>
                                                        <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i', strtotime($kasir->tglbayar)); ?>">
                                                        </td>
                                                        <td>ADM PASIEN BARU RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="admrp" id="admrp" value="<?= number_format($kasir->adm, 0, '.', ','); ?>" onchange="total_net()" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>KONSUL/TINDAKAN RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="tindakanrp" id="tindakanrp" value="<?= number_format($kasir->totalpoli, 0, '.', ','); ?>" onchange="total_net()" readonly></td>

                                                        <td>RESEP RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="reseprp" id="reseprp" value="<?= number_format($kasir->totalresep, 0, '.', ','); ?>" onchange="total_net()" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>TOTAL RP</td>
                                                        <td><input type="text" class="form-control total rightJustified" name="totalrp" id="totalrp" value="<?= number_format($kasir->totalpoli + $kasir->totalresep, 0, '.', ',') ?>" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr> -->
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>NO. KWITANSI</td>
                                                        <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="<?= $kasir->nokwitansi; ?>" readonly></td>

                                                        <td>FAKTUR PAJAK</td>
                                                        <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="<?= $kasir->fakturpajak; ?>">
                                                        </td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>TGL & JAM BAYAR</td>
                                                        <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i', strtotime($kasir->tglbayar)); ?>"></td>
                                                        <td>ADM PASIEN BARU RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="admrp" id="admrp" value="<?= number_format($kasir->adm, 2); ?>" onchange="total_net()" readonly=""></td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>KONSUL/TINDAKAN RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="tindakanrp" id="tindakanrp" value="0" onchange="total_net()" readonly></td>

                                                        <td>RESEP RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="reseprp_1" id="reseprp_1" value="0" onchange="total_net()" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>LABORATORIUM  RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="totalrp_lab" id="totalrp_lab" value="0" readonly></td>
                                                        <td>RADIOLOGI RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="radiorp" id="radiorp" value="0" onchange="total_net()" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr>

                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>PENUNJANG  RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="penunjangrp" id="penunjangrp" value="0" readonly></td>
                                                        <td>BEDAH RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="bedahrp" id="bedahrp" value="0" onchange="total_net()" readonly></td>
                                                        <td>&nbsp;</td>

                                                    </tr>

                                                    <tr style="border-top:none;">
                                                        <td>&nbsp;</td>
                                                        <td>TOTAL TRANSAKSI</td>
                                                        <td><input type="text" class="form-control total rightJustified" name="totalrp_transaksi" id="totalrp_transaksi" value="0" readonly></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>

                                                    </tr>

                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table" width="100%" border="0">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <tr>
                                                    <td bgcolor="grey" class="title-white"><b>&nbsp;ADA PROMO ?</b></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold;" width="10%" valign="justify" halign="justify">&nbsp;ADA
                                                        <input type="checkbox" name="adapromo" class="form-control cekpromo" value=1 onclick="cekpro(1)" id="adapromo">
                                                    </td>

                                                    <td style="font-weight:bold;" width="10%" valign="justify" halign="justify">TIDAK
                                                        <input type="checkbox" checked="checked" name="adapromo" class="form-control cekpromo" onclick="cekpro(2)" value=0 id="tidakadapromo">
                                                    </td>
                                                    <td width="80%"></td>
                                                </tr>

                                            </div>
                                        </div>
                                    </table>
                                </div>
                                <!-- husain -->
                                <hr>
                                <div class="row" style="margin-bottom: 20px;">
                                    <div class="col-md-12">
                                        <table border="0" width="70%">
                                        <tr>
                                            <td width="15%"><b>Jenis Bayar</b></td>
                                            <?php if($register->jenispas) : ?>
                                            <td width="5%" style="text-align:center;">
                                            <input type="radio" id="j_umum" name="j_jaminan" <?php if($register->jenispas == 'PAS1') { echo 'checked'; } else { echo ''; } ?> class="form-control" onclick="cek_umum()">
                                            </td>
                                            <td width="15%">UMUM</td>
                                            <td width="5%" style="text-align:center;">
                                            <input type="radio" id="j_jaminan" name="j_jaminan" <?php if($register->jenispas != 'PAS1') { echo 'checked'; } else { echo ''; } ?> class="form-control" onclick="cek_jaminan()">
                                            </td>
                                            <td width="15%">JAMINAN</td>
                                            <?php endif; ?>
                                        </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 20px;" id="show_jaminan">
                                    <div class="col-md-12" style="border: 1px solid black;border-collapse: collapse;">
                                        <h5><b>Jaminan</b></h5>
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Penjamin</label>
                                                <div class="col-md-7">
                                                    <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin" name="vpenjamin">
                                                        <?php
                                                        if ($pap->cust_id) { 
                                                            $cek = $this->db->get_where('tbl_penjamin', ['cust_id' => $pap->cust_id])->row();
                                                            ?>
                                                            <option value="<?= $pap->cust_id; ?>"><?= $cek->cust_nama; ?>
                                                            </option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Tercover Rp</label>
                                                <div class="col-md-7">
                                                    <?php 
                                                    if($pap){
                                                        if($pap->jumlahhutang) { 
                                                            $tercover = number_format($pap->jumlahhutang, 2); 
                                                        } else { 
                                                            $tercover = number_format(0,2); 
                                                        } 
                                                    } else {
                                                        $tercover = number_format(0,2); 
                                                    }
                                                    ?>
                                                    <input id="tercover_rp" name="tercover_rp" class="form-control input-medium" type="text" style="text-align: right" value="<?= $tercover; ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">COB</label>
                                                <div class="col-md-7">
                                                    <select class="form-control select2_el_penjamin" style="width:100%;" id="vpenjamin2" name="vpenjamin2">
                                                        <?php
                                                        if ($pap->cust_id2) { 
                                                            $cek2 = $this->db->get_where('tbl_penjamin', ['cust_id' => $pap->cust_id2])->row();
                                                            ?>
                                                            <option value="<?= $pap->cust_id2; ?>"><?= $cek2->cust_nama; ?>
                                                            </option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-5 control-label">Tercover Rp</label>
                                                <div class="col-md-7">
                                                    <?php 
                                                    if($pap){
                                                        if($pap->nilaiklaim2) { 
                                                            $tercover2 = number_format($pap->nilaiklaim2, 2); 
                                                        } else { 
                                                            $tercover2 = number_format(0,2); 
                                                        } 
                                                    } else {
                                                        $tercover2 = number_format(0,2); 
                                                    }
                                                    ?>
                                                    <input id="tercover_rp2" name="tercover_rp2" class="form-control input-medium" type="text" style="text-align: right" value="<?= $tercover2; ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end husain -->
                                <div id="tabpromo" class="portlet box blue">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-reorder"></i><b>PROMO</b>
                                        </div>
                                    </div>

                                    <div class="portlet-body">
                                        <table class="table table-hover table-striped table-bordered table-condensed table-scrollable" width="100%" border="0">
                                                <thead class="breadcrumb">
                                                    <tr >
                                                    <th class="title-white" style="text-align: center" width="10%" halign="justify"><b>No</b></th>
                                                    <th class="title-white" style="text-align: center" width="30%" halign="justify"><b>Promo</b></th>
                                                    <th class="title-white" style="text-align: center" width="30%" halign="justify"><b>Hadiah</b></th>
                                                    <th class="title-white" style="text-align: center" width="30%" halign="justify"><b>Qty</b></th>
                                                    </tr>
                                                    
                                                </thead>
                                                    <tr>

                                                        <td align="center"> 1 </td>
                                                        <td align="center">
                                                            <select name="promo[]" class="form-control select2_el_promo input-medium">
                                                                <option value="">--- Pilih Promo1 ---</option>
                                                            </select>
                                                        </td>

                                                        <td align="center">
                                                            <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
                                                                <option value="">--- Pilih Hadiah1 ---</option>
                                                            </select>
                                                        </td>
                                                        <td align="center"><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>


                                                    </tr>

                                                    <tr>
                                                        <td align="center"> 2 </td>
                                                        <td align="center">
                                                            <select name="promo[]" class="form-control select2_el_promo input-medium">
                                                                <option value="">--- Pilih Promo1 ---</option>
                                                            </select>
                                                        </td>

                                                        <td align="center">
                                                            <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
                                                                <option value="">--- Pilih Hadiah1 ---</option>
                                                            </select>
                                                        </td>
                                                        <td align="center"><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>


                                                    </tr>

                                                    <tr>
                                                        <td align="center"> 3 </td>
                                                        <td align="center">
                                                            <select name="promo[]" class="form-control select2_el_promo input-medium">
                                                                <option value="">--- Pilih Promo1 ---</option>
                                                            </select>
                                                        </td>

                                                        <td align="center">
                                                            <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
                                                                <option value="">--- Pilih Hadiah1 ---</option>
                                                            </select>
                                                        </td>
                                                        <td align="center"><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>


                                                    </tr>

                                                    <tr>
                                                        <td align="center"> 4 </td>
                                                        <td align="center">
                                                            <select name="promo[]" class="form-control select2_el_promo input-medium">
                                                                <option value="">--- Pilih Promo1 ---</option>
                                                            </select>
                                                        </td>

                                                        <td align="center">
                                                            <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
                                                                <option value="">--- Pilih Hadiah1 ---</option>
                                                            </select>
                                                        </td>
                                                        <td align="center"><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>


                                                    </tr>

                                                    <tr>
                                                        <td align="center"> 5 </td>
                                                        <td align="center">
                                                            <select name="promo[]" class="form-control select2_el_promo input-medium">
                                                                <option value="">--- Pilih Promo1 ---</option>
                                                            </select>
                                                        </td>

                                                        <td align="center">
                                                            <select name="hadiah[]" class="form-control select2_el_hadiah input-medium">
                                                                <option value="">--- Pilih Hadiah1 ---</option>
                                                            </select>
                                                        </td>
                                                        <td align="center"><input type="text" class="form-control rightJustified" name="qtyhadiah[]" id="qtyhadiah1" value="0"></td>


                                                    </tr>


                                        </table>
                                    </div>
                                </div>
                            </div>


                            <!-- end harimas -->
                            <div class="row">

                                <!-- <div class="col-md-4">
									   <div class="portlet ">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="label label-info">KWITANSI</span>												
											</div>																						
										</div>
										
										<div class="portlet-body form">									
										    <div class="form-body">
											 	
											  <table class="table">
											    <tr>
												  <td>NO. KWITANSI</td>												  
												  <td><input type="text" class="form-control" name="nokwitansi" id="nokwitansi" value="<?= $kasir->nokwitansi; ?>" readonly></td>
												  
												</tr>
												<tr>
												  <td>FAKTUR PAJAK</td>												  
												  <td><input type="text" class="form-control" name="fakturpajak" id="fakturpajak" value="<?= $kasir->fakturpajak; ?>"></td>
												  
												</tr>
												<tr>
												  <td>TGL & JAM BAYAR</td>												  
												  <td><input type="datetime-local" class="form-control" name="tglbayar" id="tglbayar" value="<?= date('Y-m-d\TH:i', strtotime($kasir->tglbayar)); ?>"></td>
												  
												</tr>
												<tr>
												  <td>ADM RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="admrp" id="admrp" value="<?= number_format($kasir->adm, 0, '.', ','); ?>" onchange="total_net()"></td>
												  
												</tr>
												<tr>
												  <td>KONSUL/TINDAKAN RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="tindakanrp" id="tindakanrp" value="<?= number_format($kasir->totalpoli, 0, '.', ','); ?>" onchange="total_net()" readonly></td>
												  
												</tr>
												<tr>
												  <td>RESEP RP</td>												  
												  <td><input type="text" class="form-control rightJustified" name="reseprp" id="reseprp" value="<?= number_format($kasir->totalresep, 0, '.', ','); ?>" onchange="total_net()" readonly></td>
												  
												</tr>
												<tr>
												  <td>TOTAL RP</td>												  
												  <td><input type="text" class="form-control total rightJustified" name="totalrp" id="totalrp" value="<?= number_format($kasir->totalsemua, 0, '.', ','); ?>" readonly></td>
												  
												</tr>
											  </table>
											   
											</div>
										 
										</div>
										</div>
									</div>
									
									<div class="col-md-2">
									</div> -->

                                <!-- <div class="col-md-4">
									   <div class="portlet ">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-reorder"></i>
												<span class="label label-success">PROMO</span>												
											</div>
											
											
										</div>
										
										<div class="portlet-body form">									
										    <div class="form-body">
											   <table class="table">
											    <tr>
												  <td><input type="checkbox" name="adapromo" value="Y"> Ada Promo</td>												  
												  <td></td>
												  
												</tr>
												<tr>
												  <td>PROMO 1</td>												  
												  <td>
												     <select name="promo1" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo1 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 1</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah1" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah1 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah1" id="qtyhadiah1" value="0"></td>
												  
												</tr>
												<tr>
												  <td>PROMO 2</td>												  
												  <td>
												     <select name="promo2" class="form-control select2_el_promo input-medium">
													   <option value="">--- Pilih Promo2 ---</option>
													 </select>
												  </td>
												  
												</tr>
												<tr>
												  <td>HADIAH 2</td>												  
												  <td>QTY</td>
												  
												</tr>
												<tr>
												  <td>
												     <select name="hadiah2" class="form-control select2_el_hadiah input-medium">
													   <option value="">--- Pilih Hadiah2 ---</option>
													 </select>
												  </td>												  
												  <td><input type="text" class="form-control rightJustified" name="qtyhadiah2" id="qtyhadiah2" value="0"></td>
												  
												</tr>
												
											  </table>
											</div>
										 
										</div>
										</div>
									</div> -->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-reorder"></i>
                                                <span class="title-white">PENGURANGAN</span>
                                            </div>


                                        </div>

                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <table class="table">
                                                    <tr bgcolor="#f6f5ff">

                                                        <td>DISKON TIN/KON</td>
                                                        <!-- <td>
													  <input type="text" class="form-control rightJustified" name="diskonpr" id="diskonpr" value="<?= number_format($kasir->diskon, 0, '.', ','); ?>" onchange="total_net()">
												  </td> -->
                                                        <td>
                                                            <!-- <input type="text" class="form-control rightJustified" name="diskonrp" id="diskonrp" value="<?= number_format($kasir->diskonrp, 0, '.', ','); ?>" onchange="total_net()"> -->
                                                            <input type="text" class="form-control rightJustified" name="diskonrp" id="diskonrp" value="0" onchange="total_net()">
                                                        </td>

                                                        <td>DISKON RESEP</td>
                                                        <!-- <td><input type="text" class="form-control rightJustified" name="diskonpr" id="diskonpr" value="0" onchange="total_net()"></td> -->
                                                        <td><input type="text" readonly class="form-control rightJustified" name="diskonresep" id="diskonresep" value="0" onchange="total_net()"></td>


                                                    </tr>
                                                    <tr bgcolor="#f6f5ff">
                                                        <td>REFUND RP</td>
                                                        <td>
                                                            <input type="text" class="form-control rightJustified" name="refund" id="refund" value="<?= number_format($kasir->refund, 0, '.', ','); ?>" onchange="total_net()">
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>

                                                    <?php if (empty($vs)) : ?>
                                                        <tr bgcolor="#f6f5ff">
                                                            <td>VOUCHER</td>
                                                            <td class="text-danger">TIDAK MENGGUNAKAN VOUCHER</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php else : ?>
                                                        <tr bgcolor="#f6f5ff">
                                                            <td>VOUCHER SOURCE</td>
                                                            <td>
                                                                <select type="text" name="vouchersource" class="form-control">
                                                                    <option value="<?= $kasir->cust_id ?>">
                                                                        <?= $vs->cust_nama ?></option>
                                                                </select>
                                                                <!-- <select name="vouchersource" id="vouchersource" class="form-control select2_el_vouchersource input-medium"></select> -->
                                                            </td>
                                                        </tr>
                                                        <?php if (strpos($kasir->novoucher1, "Pilih Voucher") == FALSE) : ?>
                                                            <tr bgcolor="#f6f5ff">
                                                                <td>VOUCHER CODE 1</td>
                                                                <td><input type="text" class="form-control" name="vouchercode1" id="vouchercode1" value="<?= ($kasir->novoucher1 == null) ? "" : $kasir->novoucher1 ?>" onchange="nominal_voucher('1')" onkeyup="nominal_voucher('1')" readonly></td>
                                                                <td><input type="text" class="form-control vrp rightJustified" name="voucherrp1" id="voucherrp1" placeholder="Voucher RP" value="<?= (empty($hargavoucher1->nominal)) ? "" : number_format($hargavoucher1->nominal, 2) ?>" onchange="total_net()" onkeyup="total_net()" readonly>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if (strpos($kasir->novoucher2, "Pilih Voucher") == FALSE) : ?>
                                                            <tr bgcolor="#f6f5ff">
                                                                <td>VOUCHER CODE 2</td>
                                                                <td><input type="text" class="form-control" name="vouchercode2" id="vouchercode2" value="<?= ($kasir->novoucher2 == null) ? "" : $kasir->novoucher2 ?>" onchange="nominal_voucher('2')" onkeyup="nominal_voucher('2')" readonly></td>
                                                                <td><input type="text" class="form-control vrp rightJustified" name="voucherrp2" id="voucherrp2" placeholder="Voucher RP" value="<?= (empty($hargavoucher2->nominal)) ? "" : number_format($hargavoucher2->nominal, 2) ?>" onkeyup="total_net()" readonly></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if (strpos($kasir->novoucher3, "Pilih Voucher") == FALSE) : ?>
                                                            <tr bgcolor="#f6f5ff">
                                                                <td>VOUCHER CODE 3</td>
                                                                <td><input type="text" class="form-control" name="vouchercode3" id="vouchercode3" value="<?= ($kasir->novoucher3 == null) ? "" : $kasir->novoucher3 ?>" onchange="nominal_voucher('3')" onkeyup="nominal_voucher('3')" readonly></td>
                                                                <td><input type="text" class="form-control vrp rightJustified" name="voucherrp3" id="voucherrp3" placeholder="Voucher RP" value="<?= (empty($hargavoucher3->nominal)) ? "" : number_format($hargavoucher3->nominal, 2) ?>" onkeyup="total_net()" readonly></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>

                                                    <tr bgcolor="#f6f5ff">
                                                        <td>UANG MUKA</td>
                                                        <td><input type="text" class="form-control rightJustified" name="uangmuka" id="uangmuka" value="<?= number_format($kasir->uangmuka, 0, '.', ','); ?>" onchange="total_net()"></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <!-- <td> <input type="button" class="btn btn-info" value="CEK DP" onclick="getuangmuka()"></td> -->

                                                    </tr>


                                                    <tr bgcolor="#f6f5ff">
                                                        <td>TOTAL NET RP</td>
                                                        <td><input type="text" class="form-control rightJustified" name="totalnet" id="totalnet" value="<?= number_format($kasir->totalsemua, 0, '.', ','); ?>" readonly></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>

                                                    </tr>


                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet ">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-reorder"></i>
                                                <span class="label label-warning title-white">PEMBAYARAN</span>
                                            </div>


                                        </div>
                                        <span class="label label-info title-white">ELECTRONIC
                                            (DEBET/CREDIT/TRANFER/EMONEY)</span>
                                        <div class="portlet-body form">

                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table width="100%" id="datatable_pembayaran" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                            <thead class="breadcrumb">
                                                                <th class="title-white" width="20%" style="text-align: center">Bank/Provier</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Pay Type</th>
                                                                <th class="title-white" width="15%" style="text-align: center">No. Kartu/Member</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Approval Code</th>
                                                                <th class="title-white" width="15%" style="text-align: center">Nilai Transaksi</th>
                                                                <th class="title-white" width="10%" style="text-align: center">Adm %</th>
                                                                <th class="title-white" width="20%" style="text-align: center">Grand Total</th>

                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td width="20%">
                                                                        <select name="bayar_bank[]" id="bayar_bank1" class="select2_el_kasbank form-control input-large">
                                                                        </select>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <select name="bayar_tipe[]" id="bayar_tipe1" class="form-control select2">
                                                                            <option value="1">DEBIT</option>
                                                                            <option value="2">CREDIT CARD</option>
                                                                            <option value="3">TRANFER</option>
                                                                        </select>
                                                                    </td>
                                                                    <td width="15%"><input name="bayar_nokartu[]" class="form-control"></td>
                                                                    <td width="10%"><input name="bayar_trvalid[]" onchange="totalline_bayar(1)" value="0" type="text" class="form-control rightJustified"></td>
                                                                    <td width="15%"><input name="bayar_nilai[]" onchange="totalline_bayar(1)" value="0" type="text" class="form-control rightJustified "></td>
                                                                    <td width="10%"><input name="bayar_adm[]" onchange="totalline_bayar(1)" value="0" type="text" class="form-control rightJustified "></td>
                                                                    <td width="20%"><input name="bayar_total[]" type="text" class="form-control rightJustified" readonly></td>

                                                                </tr>

                                                            </tbody>

                                                        </table>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!--button type="button" onclick="tambah_bayar()" class="btn green"><i class="fa fa-plus"></i></button>
													<button type="button" onclick="hapus_bayar()"  class="btn red"><i class="fa fa-trash-o"></i></button-->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <table class="table">

                                        <tr>
                                            <td>TOTAL ELECTRONIC RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="totalelectronicrp" id="totalelectronicrp" value="<?= number_format($kasir->bayarcard, 0, '.', ','); ?>" readonly>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>SELISIH</td>
                                            <td><input type="text" class="form-control rightJustified" name="selisihrp" id="selisihrp" value="<?= number_format($kasir->selisihrp, 2) ?>" onchange="total_net()" readonly></td>

                                        </tr>

                                        <tr>
                                            <td>TOTAL TUNAI RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="totaltunairp" id="totaltunairp" value="<?= number_format($kasir->bayarcash, 2); ?>" onchange="total_net()" readonly></td>

                                        </tr>
                                        <tr>
                                            <td>UANG PASIEN RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="uangpasienrp" id="uangpasienrp" value="<?= number_format($kasir->totalbayar - $kasir->kembali, 2); ?>" onchange="total_net()" readonly></td>

                                        </tr>
                                        <tr>
                                            <td>KEMBALI RP</td>
                                            <td><input type="text" class="form-control rightJustified" name="kembalirp" id="kembalirp" value="<?= number_format($kasir->kembali, 2); ?>" readonly>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>SUDAH TERIMA DARI</td>
                                            <td><input type="text" class="form-control rightJustified" name="terimadari" id="terimadari" value="<?= $kasir->dibayaroleh; ?>" readonly></td>
                                        </tr>
                                        <!-- husain add -->
                                        <tr>
                                            <td>KEKURANGAN BIAYA</td>
                                            <td><input type="text" class="form-control total rightJustified" name="biaya_tambah" id="biaya_tambah" value="0" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>BAYAR KEKURANGAN</td>
                                            <td><input type="text" class="form-control total rightJustified" name="biaya_kurang" id="biaya_kurang" value="0" onchange="bayar_kurang()"></td>
                                        </tr>
                                        <tr>
                                            <td>KEMBALIAN KE PASIEN</td>
                                            <td><input type="text" class="form-control total rightJustified" name="kembali_pasien" id="kembali_pasien" value="0" readonly></td>
                                        </tr>
                                        <!-- end husain -->
                                    </table>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <!--div class="wells"-->
                                    <div class="form-actions">


                                        <!--button id="btnsimpan_bayar" type="button" onclick="save_bayar()" class="btn blue"><i class="fa fa-save"></i> UPDATE</button-->

                                        <!-- husain add -->
                                        <?php $level = $this->session->userdata('user_level');
                                        // var_dump($level); die;
                                        if ($level == 1 || $level == 2) { ?>
                                        <button id="btnsimpan_bayar" type="button" onclick="save_bayarx()" class="btn blue"><i class="fa fa-save"></i> <b>RE-PROSES</b></button>
                                        <?php } ?>
                                        <!-- end husain -->
                                        <a class="btn yellow print_laporan" onclick="javascript:_urlcetak();"><i class="fa fa-print"></i> <b>CETAK KWITANSI</b></a>

                                        <!-- script original -->
                                        <!-- <a class="btn yellow print_laporan" onclick="javascript:cekisiobat();"><i class="fa fa-print"></i> <b>CETAK KWITANSI OBAT</b></a> -->
                                        
                                        <!-- husain change -->
                                        <a class="btn yellow print_laporan" onclick="javascript:cekpenjamin();"><i class="fa fa-print"></i> <b>CETAK DOKUMEN JAMINAN</b></a>
                                        <!-- end husain -->

                                        <a href="<?= base_url('kasir_konsul') ?>" class="btn btn red"><b><i class="fa fa-undo"></i>KEMBALI</b></a>
                                        <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan...
                                            </span> <span id="success" style="display:none; color:#0C0">Data sudah
                                                disimpan...</span></h4>
                                    </div>
                                </div>
                            </div>

                            <!--/form-->

                        </div>
        </form>
    </div>
    <!--tab2-->


    <!-- tab2-->

</div>
<!--tab-->



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
?>

<script>
    $("#btnsimpan").hide();
    $("#btnsimpan_bayar").hide();
    $("#show_jaminan").hide();
    if(document.getElementById('j_umum').checked == true){
    $("#show_jaminan").hide();
    }
    if(document.getElementById('j_jaminan').checked == true){
    $("#show_jaminan").show();
    }
    function cek_umum(){
        if(document.getElementById('j_umum').checked == true){
        $("#show_jaminan").hide();
        }
    }
    function cek_jaminan(){
        if(document.getElementById('j_jaminan').checked == true){
        $("#show_jaminan").show();
        }
    }
    $('#tabpromo').hide();
    $('#hisum').hide();

    function cekpro(param) {
        if (param == 1) {
            $('#tabpromo').show("1000");
            $("#tidakadapromo").prop("checked", false);
        } else if (param == 2) {
            $('#tabpromo').hide("1000");
            $("#adapromo").prop("checked", false);
        }
    }


    var idrow = '<?= $jumlah + 1; ?>';
    var idrow2 = 2;

    function tambah() {
        var x = document.getElementById('datatable').insertRow(idrow);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);

        var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_tarif_tindakan form-control' ></select>";
        var dokter = "<select name='dokter[]' id=dokter" + idrow + " class='select2_el_dokter form-control' ></select>";
        var perawat = "<select name='perawat[]' id=perawat" + idrow + " class='select2_el_perawat form-control' ></select>";
        td1.innerHTML = akun;
        td2.innerHTML = dokter;
        td3.innerHTML = perawat;
        td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified'><input name='feemedis[]'  id=feemedis" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='bhp[]'  id=bhp" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='tarifrs[]'  id=tarifrs" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly><input name='tarifdr[]'  id=tarifdr" + idrow + " value='0'  type='hidden' class='form-control rightJustified' readonly>";
        td5.innerHTML = "<input name='disc1[]'   id=disc1" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
        td6.innerHTML = "<input name='disc2[]'   id=disc2" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
        td7.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%'>";
        poli = $('#reg_klinik').val();
        initailizeSelect2_tarif_tindakan(poli);
        initailizeSelect2_dokter();
        initailizeSelect2_perawat();
        idrow++;
    }

    function tambah_bayar() {
        var x = document.getElementById('datatable_pembayaran').insertRow(idrow2);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);
        var td7 = x.insertCell(6);

        var akun = "<select name='lkode[]' id=lkode" + idrow2 +
            " class='select2_el_kasbank form-control' ><option value=''>--- Pilih Akun ---</option></select>";
        td1.innerHTML = akun;
        td2.innerHTML =
            "<select name='bayar_tipe[]' id='bayar_tipe" + idrow2 + " class='form-control select2'>" +
            "<option value='1'>DEBIT</option>" +
            "<option value='2'>CREDIT CARD</option>" +
            "<option value='3'>TRANFER</option>" +
            "</select>";

        td3.innerHTML = "<input name='bayar_nokartu[]' class='form-control'>";
        td4.innerHTML = "<input name='bayar_trvalid[]'  onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified'>";
        td5.innerHTML = "<input name='bayar_nilai[]'   onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified'>";
        td6.innerHTML = "<input name='bayar_adm[]'   onchange='totalline_bayar(" + idrow2 +
            ")' value='0' type='text' class='form-control rightJustified'>";
        td7.innerHTML = "<input name='bayar_total[]'  type='text' class='form-control rightJustified' readonly>";
        initailizeSelect2_kasbank();
        idrow2++;
    }


    function showharga(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("dafhargabeli").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dafhargabeli").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_faktur/getharga/" + str, true);
        xhttp.send();
    }


    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $('#sat' + vid).val('');
        $('#harga' + vid).val(0);
        $('#feemedis' + vid).val(0);
        $('#bhp' + vid).val(0);
        $('#jumlah' + vid).val(0);
        $.ajax({
        url: "<?php echo base_url(); ?>kasir_konsul/getinfotindakan/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            // var checkx = data.tindakan;
            // var check = checkx.toLowerCase();
            // var same_text = 'konsultasi'+' '+'+';
            // var found = check.indexOf(same_text) > -1;
            // if(found){
            //   $('#perawat'+id).attr('disabled', false);
            // } else {
            //   $('#perawat'+id).attr('disabled', true);
            // }
            //$('#sat'+vid).val(data.satuan);
            var feemedis = Number(parseInt((data.feemedispoli).replaceAll(',','')));
            var bhp = Number(parseInt((data.bhp).replaceAll(',','')));
            var tarifrs = Number(parseInt((data.tarifrspoli).replaceAll(',','')));
            var tarifdr = Number(parseInt((data.tarifdrpoli).replaceAll(',','')));
            $('#harga' + vid).val(formatCurrency1(data.totaljasa));
            $('#feemedis' + vid).val(formatCurrency1(feemedis));
            $('#bhp' + vid).val(formatCurrency1(bhp));
            $('#tarifrs' + vid).val(formatCurrency1(tarifrs));
            $('#tarifdr' + vid).val(formatCurrency1(tarifdr));
            totalline(id);
        }
        });

    }

    function save() {
        //var tanggal   = $('[name="tanggal"]').val(); 
        var poli = $('[name="reg_klinik"]').val();
        var nomor = $('[name="noreg"]').val();
        var total = $('#_vtotal').text();

        if (nomor == "" || total == "0.00" || total == "") {
            swal('', 'Data Belum Lengkap / Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('kasir_konsul/ajax_add') ?>',
                data: $('#frmkonsul').serialize(),
                type: 'POST',

                success: function(data) {
                    document.getElementById("btnsimpan").disabled = true;
                    document.getElementById("tersimpan").value = "OK";

                    swal({
                        title: "PEMBAYARAN KONSUL DAN TINDAKAN",
                        html: "<p> No. Bukti   : <b>" + nomor + "</b> </p>" +
                            "POLI " + poli,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        bayar();
                    });

                },
                error: function(data) {
                    swal('', 'Data gagal disimpan ...', '');

                }
            });
        }
    }

    function save_bayar() {
        var nomor = $('[name="noreg"]').val();
        var total = $('#totalnet').val();

        if (nomor == "" || total == "0.00" || total == "") {
            swal('', 'Data Belum Lengkap / Belum ada pembayaran ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('kasir_konsul/ajax_add_bayar') ?>',
                data: $('#frmkonsul').serialize(),
                type: 'POST',
                dataType: 'json',

                success: function(data) {
                    document.getElementById("btnsimpan_bayar").disabled = true;
                    //document.getElementById("tersimpan_bayar").value="OK";

                    swal({
                        title: "KWITANSI PEMBAYARAN",
                        html: "<p> No. Bukti   : <b>" + data.nomor + "</b> </p>",
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.reload();
                    });

                },
                error: function(data) {
                    swal('', 'Data gagal disimpan ...', '');

                }
            });
        }
    }



    function hapus() {
        if (idrow > 2) {
            var x = document.getElementById('datatable').deleteRow(idrow - 1);
            idrow--;
            total();
        }
    }

    function hapus_bayar() {
        if (idrow2 > 2) {
            var x = document.getElementById('datatable_pembayaran').deleteRow(idrow2 - 1);
            idrow2--;
            total();
        }
    }



    function total() {

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        tdiskon = 0;
        tperawatan = 0;

        for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[6].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1);

        diskon = row.cells[5].children[0].value;
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tdiskon = tdiskon + eval(diskon1);

        perawatan = row.cells[3].children[0].value;
        var perawatan1 = Number(perawatan.replace(/[^0-9\.]+/g, ""));

        tperawatan = tperawatan + eval(perawatan1);

        }
        
        
        var table_resep = document.getElementById('datatable_resep');
        var rowCount_resep = table_resep.rows.length;
        tjumlah_resep = 0;
        tjumlah_resep_disc = 0;

        for (var i = 1; i < rowCount_resep; i++) {
        var row = table_resep.rows[i];

        jumlah = row.cells[1].children[0].value;
        jumlahdisc = row.cells[1].children[1].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var jumlah1disc = Number(jumlahdisc.replace(/[^0-9\.]+/g, ""));
        tjumlah_resep = tjumlah_resep + eval(jumlah1);
        tjumlah_resep_disc = tjumlah_resep_disc + eval(jumlah1disc);

        }

        tresep = tjumlah_resep;
        var vadmrp = $('#admrp').val(); //saya nambah ini
        var _vsubadm = Number(vadmrp.replace(/[^0-9\.]+/g, ""));
        document.getElementById("_vsubadm").innerHTML = formatCurrency1(vadmrp); //saya nambah ini
        //    document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);saya ganti
        document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tperawatan);
        document.getElementById("tindakanrp").value = formatCurrency1(tperawatan);
        document.getElementById("_vtotalresep").innerHTML = formatCurrency1(<?= $kasir->totalresep; ?>);
        document.getElementById("_vtotalresepdisc").innerHTML = formatCurrency1(<?= $discr; ?>);
        document.getElementById("_vtotaldiskon").innerHTML = formatCurrency1(tdiskon);
        var totdiscresep = Number(parseInt(($("#_vtotalresepdisc").text()).replaceAll(',','')));
        var totresep = Number(parseInt(($("#_vtotalresep").text()).replaceAll(',','')));
        tresepx = totresep;
        document.getElementById("_vtotal").innerHTML = formatCurrency1(_vsubadm+tperawatan+tresepx-tdiskon);
        $("#totalrp_transaksi").val(formatCurrency1(_vsubadm+tperawatan+tresepx-tdiskon));
        $("#totalnet").val(formatCurrency1(_vsubadm+tperawatan+tresepx-tdiskon));
        $("#reseprp_1").val(formatCurrency1(tresepx));
        $("#diskonrp").val(formatCurrency1(tdiskon));
        var _vtota = tjumlah + tresep;
        //    document.getElementById("_vtotal").innerHTML=formatCurrency1(tperawatan);

        if (tjumlah > 0) {
        document.getElementById("btnsimpan").disabled = false;
        } else {
        document.getElementById("btnsimpan").disabled = true;
        }

    }

    function totalline(id) {

        var table = document.getElementById('datatable');
        var row = table.rows[id];
        var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
        diskon = (row.cells[4].children[0].value / 100) * harga;
        tot = harga - diskon;
        row.cells[5].children[0].value = formatCurrency1(diskon);
        row.cells[6].children[0].value = formatCurrency1(tot);
        total();

    }

    function totalline_bayar(id) {

        var table = document.getElementById('datatable_pembayaran');
        var row = table.rows[id];
        var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
        var adm = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
        rpadm = (row.cells[5].children[0].value / 100) * harga;
        tot = harga + rpadm;
        row.cells[6].children[0].value = formatCurrency1(tot);
        total_bayar();

    }

    function total_bayar() {

        var table = document.getElementById('datatable_pembayaran');
        var rowCount = table.rows.length;

        tjumlah = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[6].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1);

        }

        document.getElementById("totalelectronicrp").value = formatCurrency1(tjumlah);



    }


    function getdataklinik() {
        var xhttp;
        var str = $('[name=reg_klinik]').val();
        if (str == "") {

        } else {
            initailizeSelect2_register(str);

        }
    }

    function getdataregistrasi() {
        var xhttp;
        var str = $('[name=noreg]').val();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>kasir_konsul/getdataregistrasi/?noreg=" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    //$('#kodepoli').val(data.kodepos);
                    $('#pasien').val(data.rekmed);
                    $('#reg_namapasien').val(data.namapas);
                    $('#reg_poli').val(data.kodepos);
                    $('#pasien_bayar').val(data.rekmed);
                    $('#noreg_bayar').val(str);
                    initailizeSelect2_tarif_tindakan(data.kodepos);
                    getdataresep();
                }
            });
        }
    }


    function getuangmuka() {
        var xhttp;
        $('#hisum').show("1000");
        var str = $('[name=noreg]').val();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>kasir_konsul/getuangmuka/?noreg=" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    $('#uangmuka').val(formatCurrency1(data.totalsemua));
                    total_net();
                }
            });
        }
    }


    function getdataresep() {
        var xhttp;
        var str = $('[name=noreg]').val();
        $('#datatable_resep tbody').empty();
        if (str == "") {
            
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>kasir_konsul/getdataresep/?noreg=" + str,
                type: "GET",
                success: function(data) {
                    $('#datatable_resep tbody').append(data);
                }
            });
        }
    }


    // function bayar(){	
    // 	var totaldiskon = document.getElementById("_vtotaldiskon").innerHTML;
    // 	var tindakan = document.getElementById("_vsubtotal").innerHTML;
    // 	var resep = document.getElementById("_vtotalresep").innerHTML
    // 	var total = document.getElementById("_vtotal").innerHTML
    //     $('#tindakanrp').val(tindakan);
    // 	$('#reseprp').val(resep);
    // 	$('#totalrp').val(total);
    // 	total_net();	
    // 	$('.nav-pills a[href="#tab2"]').tab('show'); 

    // }

    function bayar() {
        var totaldiskon = document.getElementById("_vtotaldiskon").innerHTML;
        var tindakan = document.getElementById("_vsubtotal").innerHTML;
        var resep = document.getElementById("_vtotalresep").innerHTML;
        var total = document.getElementById("_vtotal").inneHTML;
        var totalresepdisc = document.getElementById("_vtotalresepdisc").innerHTML;
        $('#diskonrp').val(totaldiskon);
        $('#tindakanrp').val(tindakan);
        $('#reseprp').val(resep);
        $('#totalrp').val(total);
        $('#diskonresep').val(totalresepdisc);
        total_net();
        $('.nav-pills a[href="#tab2"]').tab('show');

    }

    function total_net() {
        var adm = $('#admrp').val();
        var tindakan = $('#tindakanrp').val();
        var resep = $('#reseprp_1').val();

        var vadm = Number(adm.replace(/[^0-9\.]+/g, ""));
        var vtindakan = Number(tindakan.replace(/[^0-9\.]+/g, ""));
        var vresep = Number(resep.replace(/[^0-9\.]+/g, ""));

        var vtotalrp = eval(vadm) + eval(vtindakan) + eval(vresep);
        $('#totalrp_transaksi').val(formatCurrency1(vtotalrp));

        var totalrp = $('#totalrp_transaksi').val();
        var totalsemua = Number(totalrp.replace(/[^0-9\.]+/g, ""));
        var diskonrp = $('#diskonrp').val();
        var uangmukarp = $('#uangmuka').val();
        var refundrp = $('#refund').val();
        var voucherrp1x = $('#voucherrp1').val();
        var voucherrp2x = $('#voucherrp2').val();
        var voucherrp3x = $('#voucherrp3').val();

        if(voucherrp1x == '' || voucherrp1x == null || voucherrp1x == false){
            var voucherrp1 = "0.00";
        } else {
            var voucherrp1 = voucherrp1x;
        }
        if(voucherrp2x == '' || voucherrp2x == null || voucherrp2x == false){
            var voucherrp2 = "0.00";
        } else {
            var voucherrp2 = voucherrp2x;
        }
        if(voucherrp3x == '' || voucherrp3x == null || voucherrp3x == false){
            var voucherrp3 = "0.00";
        } else {
            var voucherrp3 = voucherrp3x;
        }

        var vdiskonrp = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
        var vuangmukarp = Number(uangmukarp.replace(/[^0-9\.]+/g, ""));
        var vrefundrp = Number(refundrp.replace(/[^0-9\.]+/g, ""));

        var vvoucherrp1 = Number(voucherrp1.replace(/[^0-9\.]+/g, ""));
        var vvoucherrp2 = Number(voucherrp2.replace(/[^0-9\.]+/g, ""));
        var vvoucherrp3 = Number(voucherrp3.replace(/[^0-9\.]+/g, ""));

        var totalnet = eval(totalsemua) - eval(vdiskonrp) - eval(vuangmukarp) - eval(vrefundrp) - eval(vvoucherrp1) - eval(vvoucherrp2) - eval(vvoucherrp3);

        // $('#totalnet').val(formatCurrency1(totalnet));

        var bayarcredit = $('#totalelectronicrp').val();
        var bayartunai = $('#totaltunairp').val();
        var uangpasienrp = $('#uangpasienrp').val();

        var vbayarcredit = Number(bayarcredit.replace(/[^0-9\.]+/g, ""));
        var vbayartunai = Number(bayartunai.replace(/[^0-9\.]+/g, ""));
        var vuangpasien = Number(uangpasienrp.replace(/[^0-9\.]+/g, ""));

        var kembali = (eval(vbayarcredit) + eval(vbayartunai) + eval(vuangpasien)) - eval(totalnet);
        var selisihrpx = $("#selisihrp").val();
        var selisihrp = Number(parseInt(selisihrpx.replaceAll(',','')));
        var kembalirpx = $("#kembalirp").val();
        var kembalirp = Number(parseInt(kembalirpx.replaceAll(',','')));
        var total_uang = vuangpasien + selisihrp;
        var total_newx = $("#totalnet").val();
        var total_new = Number(parseInt(total_newx.replaceAll(',','')));
        var penambahan = eval(total_new - total_uang);
        $('#biaya_tambah').val(formatCurrency1(penambahan));
        // $('#kembalirp').val(formatCurrency1(kembali));
    }

    // husain add
    function bayar_kurang(){
        var tanggunganx = $("#biaya_tambah").val();
        var tanggungan = Number(parseInt(tanggunganx.replaceAll(',','')));
        var kurangx = $("#biaya_kurang").val();
        var kurang = Number(parseInt(kurangx.replaceAll(',','')));
        var total = kurang - tanggungan;
        if(total > 0){
            $('#kembali_pasien').val(formatCurrency1(total));
            // $('#biaya_tambah').val(formatCurrency1(0));
        } else {
            $('#kembali_pasien').val(formatCurrency1(0));
            // $('#biaya_tambah').val(formatCurrency1(total));
        }
        $("#biaya_kurang").val(formatCurrency1(kurang));
    }
    // end husain

    function cekisiobat() {
        var nokwitansi = $('[name="nokwitansi"]').val();
        var noreg = $('[name="noreg"]').val();

        $.ajax({
            url: "/kasir_konsul/cek_isiobat/",
            type: "POST",
            dataType: "JSON",
            data: {
                kwi: nokwitansi,
                regg: noreg
            },
            success: function(data) {
                if (data.status == 0) {
                    swal({
                        title: "KWITANSI OBAT",
                        html: "<p>Untuk Kwitansi <br><b>" + nokwitansi + "</b><br> Tidak Ada obat </p>",
                        confirmButtonText: "OK"
                    });
                } else {
                    _urlcetak2();
                }
            }
        });
    }

    function _urlcetak() {
        var baseurl = "<?php echo base_url() ?>";
        var nokwitansi = $('[name="nokwitansi"]').val();
        var noreg = $('[name="noreg"]').val();
        var baseurl = "<?php echo base_url() ?>";

        // script original
        // var var1 = baseurl + 'kasir_konsul/cetak/?kwitansi=' + nokwitansi + '&noreg=' + noreg;

        // husain change
        var var1 = baseurl + 'kasir_konsul/cetak_kwitansi/?kwitansi=' + nokwitansi + '&noreg=' + noreg;
        // end husain
        window.open(var1, '_blank');
    }

    function cekpenjamin() {
        var baseurl = "<?php echo base_url() ?>";
        var nokwitansi = $('[name="nokwitansi"]').val();
        var noreg = $('[name="noreg"]').val();
        var baseurl = "<?php echo base_url() ?>";
        var var1 = baseurl + 'kasir_konsul/cetak_jaminan/?kwitansi=' + nokwitansi + '&noreg=' + noreg;
        window.open(var1, '_blank');
    }


    window.onload = function() {
        //document.getElementById('btnsimpan').disabled=true;
        document.getElementById('tersimpan').value = "";
        getdataresep();

    };

    // getdataresep();
    total();
</script>


<!-- husain add new script update -->
<script>
    $("#btnsimpan_bayar").attr("disabled", true);
    function update_data(){
        var kondisi = $("#kondisi").val();
        var poli = $('[name="reg_klinik"]').val();
        var nomor = $('[name="noreg"]').val();
        var nohp = $('#reg_hp').val();
        var total = $('#_vtotal').text();
        var cekhp = $('#reg_cekhp').is(':checked');
        var tresepx = $('#_vtotalresep').text();
        if (nohp == '') {
            swal({
                title: "No Hp Masih Kosong",
                html: "<p> No.Hp : <b>" + nohp + "</b> </p>" +
                "CEK LAGI",
                type: "error",
                confirmButtonText: "OK"
            });
            return;
        }
        if (cekhp === false) {
            swal({
                title: "Apakah Nomor HP Pasien",
                html: "<p> <br><b>" + nohp +
                "<br></b><br>Sudah Sesuai ???  <br>Checklist Di samping No.HP Jika Sudah Sesuai</p>",
                type: "question",
                confirmButtonText: "OK"
            });
            return;
        }
        if (nomor == "" || total == "0.00" || total == "" || nohp == '') {
            swal({
                title: "Data Belum Lengkap / Belum ada transaksi ...",
                type: "info",
                confirmButtonText: "OK"
            }).then((value) => {
                return;
            });
        } else {
            swal({
                title: 'UBAH DATA',
                text: "Yakin ingin mengubah data transaksi ini ?",
                type: 'info',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-success',
                confirmButtonColor: '#227dff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'UBAH',
                cancelButtonText: 'TIDAK',
            }).then(function() {
                $.ajax({
                    url: "<?= site_url('Kasir_konsul/edit_hpoli?kondisi='); ?>"+kondisi,
                    type: "POST",
                    data: ($('#frmkonsul').serialize()),
                    dataType: "JSON",
                    success: function(data){
                        if(data.status == 1){
                            var table = document.getElementById('datatable');
                            var rowCount = table.rows.length;
                            for (i = 1; i < rowCount; i++) {
                                var kode = $("#kode" + i).val();
                                var dokter = $("#dokter" + i).val();
                                var perawat = $("#perawat" + i).val();
                                var hargax = $("#harga" + i).val();
                                var harga = Number(parseInt(hargax.replaceAll(',', '')));
                                var feemedisx = $("#feemedis" + i).val();
                                var feemedis = Number(parseInt(feemedisx.replaceAll(',', '')));
                                var bhpx = $("#bhp" + i).val();
                                var bhp = Number(parseInt(bhpx.replaceAll(',', '')));
                                var tarifrsx = $("#tarifrs" + i).val();
                                var tarifrs = Number(parseInt(tarifrsx.replaceAll(',', '')));
                                var tarifdrx = $("#tarifdr" + i).val();
                                var tarifdr = Number(parseInt(tarifdrx.replaceAll(',', '')));
                                var disc1 = $("#disc1" + i).val();
                                var disc2x = $("#disc2" + i).val();
                                var disc2 = Number(parseInt(disc2x.replaceAll(',', '')));
                                var jumlahx = $("#jumlah" + i).val();
                                var jumlah = Number(parseInt(jumlahx.replaceAll(',', '')));

                                var param = "?kondisi="+kondisi+"&kode="+kode+"&dokter="+dokter+"&perawat="+perawat+"&harga="+harga+"&feemedis="+feemedis+"&bhp="+bhp+"&tarifrs="+tarifrs+"&tarifdr="+tarifdr+"&disc1="+disc1+"&disc2="+disc2+"&jumlah="+jumlah;
                                $.ajax({
                                    url : "<?php echo site_url('Kasir_konsul/edit_kasir')?>"+param,
                                    type: "POST",
                                    data: ($('#frmkonsul').serialize()),
                                    dataType: "JSON",
                                    success: function(data){
                                    }
                                });
                            }
                            document.getElementById("btnsimpan").disabled = true;
                            document.getElementById("tersimpan").value = "OK";
                            swal({
                                title: "BIAYA SUDAH TERBENTUK",
                                html: "<p>Akan Di Proses Dengan Nilai Nominal <br><b>" + total + "</b></p><br><p>Dengan Biaya Resep Racik <b>" + tresepx + "</b>",
                                type: "info",
                                confirmButtonText: "OK"
                            }).then((value) => {
                                bayarx();
                            });
                        } else {

                        }
                    }
                });
            });
        }
    }

    function bayarx() {
        var totaldiskon = document.getElementById("_vtotaldiskon").innerHTML;
        var tindakan = document.getElementById("_vsubtotal").innerHTML;
        var resep = document.getElementById("_vtotalresep").innerHTML;
        var totalresepdisc = document.getElementById("_vtotalresepdisc").innerHTML;
        var total = $('#_vtotal').text();
        $('#diskonrp').val(totaldiskon);
        $('#tindakanrp').val(tindakan);
        $('#reseprp_1').val(resep);
        $('#totalrp').val(total);
        $('#diskonresep').val(totalresepdisc);
        $('.nav-pills a[href="#tab2"]').tab('show');
        total_net();
        $("#btnsimpan_bayar").attr("disabled", false);
    }
</script>
<!-- end husain -->



</body>

</html>