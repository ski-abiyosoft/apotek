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
            <span class="title-web">Farmasi <small>Penjualan Resep</small>
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
                <a class="title-white" href="<?php echo base_url();?>penjualan_faktur">
                    Daftar Resep
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Resep
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>*Data Baru
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pembeli <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <select name="pembeli" class="form-control" onchange="getdataklinik()" readonly>
                                        <option <?= ($posting->kodepel=='RAJAL'?'selected':'')?> value="RAJAL">Rawat Jalan</option>
                                        <option <?= ($posting->kodepel=='RANAP'?'selected':'')?> value="RANAP">Rawat Inap</option>
                                        <option <?= ($posting->kodepel=='APOTIK'?'selected':'')?> value="APOTIK">Apotik</option>
                                        
                                        <!-- <option <?= ($posting->kodepel=='KULIT'?'selected':'')?> value="KULIT">Kulit
                                        </option>
                                        <option <?= ($posting->kodepel=='LOKAL'?'selected':'')?> value="LOKAL">Lokal
                                        </option>
                                        <option <?= ($posting->kodepel=='SPA'?'selected':'')?> value="SPA">Spa</option>
                                        <option <?= ($posting->kodepel=='GIGI'?'selected':'')?> value="GIGI">Gigi
                                        </option> -->
                                    </select>

                                </div>



                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Resep Dari <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select id="dokter" name="dokter" class="form-control select2_el_dokter"
                                        data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>
                                        <?php 
											if($header->kodokter){ 
											$namadr = data_master('tbl_dokter',array('kodokter' => $header->kodokter, "koders" => $this->session->userdata("unit")))->nadokter; ?>
                                        <option value="<?= $header->kodokter;?>"><?= $header->kodokter.' | '.$namadr;?>
                                        </option>
                                        <?php }
										?>
                                    </select>

                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">DEPO <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo"
                                        data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>
                                        <?php
											if($header->gudang){ 
											$namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;
											?>
                                        <option value="<?= $header->gudang;?>"><?= $namagudang;?></option>
                                        <?php }
										?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No. Resep <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <input type="text" id="noresep" name="noresep" value="<?= $header->resepno;?>"
                                        class="form-control">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">No. Registrasi <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <select id="noreg" name="noreg" class="form-control select2_el_registrasi"
                                        data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                        <?php
											if($header->noreg){ 
											$noreg = data_master('tbl_regist', array('noreg' => $header->noreg))->noreg;
											?>
                                        <option value="<?= $header->noreg;?>"><?= $noreg;?></option>
                                        <?php }
										?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tanggal <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <input id="tanggal" name="tanggal" class="form-control input-medium" type="date"
                                        value="<?php echo date('Y-m-d', strtotime($header->tglresep));?>" />

                                </div>
                                <!--label class="col-md-1 control-label">Jam<font color="red">*</font></label-->
                                <div class="col-md-3">
                                    <input type="time" class="form-control" name="jam" id="jam"
                                        value="<?= date('H:i:s', strtotime($header->jam));?>">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">



                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Member <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <select id="pasien" name="pasien" class="form-control select2_el_pasien"
                                        onchange="getinfopasien()" data-placeholder="Pilih..."
                                        onkeypress="return tabE(this,event)">
                                        <?php 
											if($header->rekmed){ 
											$datapasien = data_master('tbl_pasien',array('rekmed' => $header->rekmed)); ?>
                                        <option value="<?= $header->rekmed;?>">
                                            <?= $header->rekmed.' | '.$datapasien->namapas;?></option>
                                        <?php }
										?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Alamat Kirim <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <input type="text" name="alamat" id="alamat" class="form-control"
                                        value="<?= $datapasien->alamat;?>" readonly>
                                </div>


                            </div>
                        </div>

                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nama Pembeli <font color="red">*</font></label>
                                <div class="col-md-6">
                                    <input type="text" name="namapasien" id="namapasien" class="form-control"
                                        value="<?= $posting->namapas;?>" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Phone <font color="red">*</font></label>
                                <div class="col-md-9">
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="<?= $datapasien->handphone;?>" readonly>
                                </div>


                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Resep
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Racikan
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">



                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <th class="title-white" width="15%" style="text-align: center">Kode Barang
                                            </th>
                                            <th class="title-white" width="20%" style="text-align: center">Nama Barang
                                            </th>
                                            <th class="title-white" width="10%" style="text-align: center">Kuantitas
                                            </th>
                                            <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="10%" style="text-align: center">Harga</th>
                                            <th class="title-white" width="5%" style="text-align: center">PPN</th>
                                            <th class="title-white" width="5%" style="text-align: center">Disc. %</th>
                                            <th class="title-white" width="10%" style="text-align: center">Disc. Rp</th>
                                            <th class="title-white" width="15%" style="text-align: center">Total Harga
                                            </th>

                                        </thead>

                                        <tbody>
                                            <?php
													 $no = 1;
													 foreach($detil as $row){ 
													?>
                                            <tr>
                                                <td width="15%">
                                                    <select name="kode[]" id="kode<?=$no;?>"
                                                        class="select2_el_farmasi_barang form-control input-largex"
                                                        onchange="showbarangname(this.value, <?=$no;?>);cekstok(this.value)">
                                                        <option value="<?= $row->kodebarang;?>">
                                                            <?= $row->kodebarang.' | '.$row->namabarang1;?></option>
                                                    </select>
                                                </td>
                                                <td width="20%"><input name="nama[]"
                                                        onchange="totalline(<?=$no;?>);total()"
                                                        value="<?= $row->namabarang;?>" id="nama<?=$no;?>" type="text"
                                                        class="form-control"></td>
                                                <td width="10%"><input name="qty[]"
                                                        onchange="totalline(<?=$no;?>);total()"
                                                        value="<?= number_format($row->qty);?>" id="qty<?=$no;?>"
                                                        type="text" class="form-control rightJustified"></td>
                                                <td width="10%"><input name="sat[]" id="sat<?=$no;?>" type="text"
                                                        class="form-control " value="<?= $row->satuan;?>"
                                                        onkeypress="return tabE(this,event)"></td>
                                                <td width="10%"><input name="harga[]" onchange="totalline(<?=$no;?>)"
                                                        value="<?= angka_rp($row->price,2);?>" id="harga<?=$no;?>"
                                                        type="text" class="form-control rightJustified" readonly></td>
                                                <td><input type="checkbox" name="ppn[]"
                                                        <?= ($row->ppn==1?'checked':'')?> id="ppn<?=$no;?>"
                                                        class="form-control" onchange="totalline(<?=$no;?>);total()"
                                                        disabled></td>

                                                <td width="5%"><input name="disc[]" onchange="totalline(<?=$no;?>)"
                                                        value="<?= $row->discount;?>" id="disc<?=$no;?>" type="text"
                                                        class="form-control rightJustified "></td>
                                                <td width="10%"><input name="disc2[]" onchange="totalline(<?=$no;?>)"
                                                        value="<?= angka_rp($row->discrp,2);?>" id="disc2<?=$no;?>"
                                                        type="text" class="form-control rightJustified "></td>
                                                <td width="20%"><input name="jumlah[]"
                                                        value="<?= angka_rp($row->totalrp,2);?>" id="jumlah<?=$no;?>"
                                                        type="text" class="form-control rightJustified" size="40%"
                                                        onchange="total()" readonly></td>

                                            </tr>
                                            <?php $no++; } ?>
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i
                                                        class="fa fa-plus"></i> </button>
                                                <button type="button" onclick="hapus()" class="btn red"><i
                                                        class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>


                                    </div>


                                </div>
                            </div>



                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">
                            <div class="row">
                                <!-- harimas -->


                                <div class="col-md-12 form-body">
                                    <table class="table" border="0" width="100%">
                                        <tr bgcolor="#c7f2ff">

                                            <td width="10%" class="control-labelh rightJustified">RACIKAN KE

                                            </td>
                                            <td width="20%">
                                                <select id="cekracik" name="cekracik" class="form-control">

                                                    <option value="1" selected>1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>

                                            <td>&nbsp;</td>
                                            <!-- Diskon Resep -->


                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>

                                        </tr>


                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <div class="portlet box purple" id="racik1">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="title-white"><b>RACIKAN KE - 1</b></span>
                                            </div>

                                        </div>
                                        <!-- <?php 
                                        if($header_r);
                                        
                                        ?> -->

                                        </td>
                                        <?php
                                                        if($header_r == NULL){ ?>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <table class="table" border="0" width="100%">
                                                    <tr>
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#c7f2ff">

                                                        <td width="10%" class="control-labelh rightJustified">JENIS
                                                        </td>
                                                        <td width="20%" colspan="2">
                                                            <select id="jenis_1" name="jenis_1" class="form-control">
                                                                <?php 
													$data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='JENISRACIK' ")->result();
													foreach($data as $row){ ?>
                                                                <option value="<?= $row->apocode;?>">
                                                                    <?= $row->aponame;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>

                                                        <td width="15%" class="control-labelh rightJustified">NAMA
                                                            RACIKAN</td>
                                                        <td width="20%">
                                                            <input type="text" class="form-control " name="namaracik_1"
                                                                id="namaracik_1" value="" Placeholder="Nama">
                                                        </td>
                                                        <td> &nbsp; </td>
                                                        <td width="15%" class="control-labelh rightJustified">CARA
                                                            PAKAI
                                                        </td>
                                                        <td>
                                                            <select name="caraPAkai" id="caraPAkai"
                                                                class="form-control">
                                                                <option value=""> --- PILIH ----
                                                                </option>
                                                                <option value=""> DIMINUM </option>
                                                                <option value=""> DIOLES </option>
                                                                <option value=""> DITETES </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#c7f2ff">
                                                        <td class="control-labelh rightJustified">JUMLAH
                                                        </td>
                                                        <td width="8%">
                                                            <input type="number" class="form-control " name="jumracik_1"
                                                                id="jumracik_1" value="">

                                                        </td>

                                                        <td width="12%">
                                                            <select name="stajum_1" id="stajum_1" class="form-control">
                                                                <?php 
													$data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' ")->result();
													foreach($data as $row){ ?>
                                                                <option value="<?= $row->apocode;?>">
                                                                    <?= $row->aponame;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>

                                                        <td class="control-labelh rightJustified">ATURAN
                                                            PAKAI</td>
                                                        <td>
                                                            <select name="atpakai_1" id="atpakai_1"
                                                                class="form-control">
                                                                <?php 
                                                                 $data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
                                                                 foreach($data as $row){ ?>
                                                                <option value="<?= $row->apocode;?>">
                                                                    <?= $row->aponame;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>&nbsp;</td>

                                                        <td class="control-labelh rightJustified" type="hidden"
                                                            width="15%"></td>
                                                        <td>
                                                            <!-- <input type="text" class="form-control " name="atpakai_1"
                                                                id="atpakai_1" value="<?= $header_r->aturanpakai;?>"
                                                                type="hidden"> -->

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>

                                                </table>
                                                <table id="datatableobat_1"
                                                    class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                    <thead class="page-breadcrumb breadcrumb">
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Kode Obat</th>
                                                        <th class="title-white" width="20%" style="text-align: center">
                                                            Nama Obat</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Satuan</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Qty Racik</th>

                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Qty Jual</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Harga Jual</th>
                                                        <th class="title-white" width="15%" style="text-align: center">
                                                            Uang R</th>
                                                        <th class="title-white" width="15%" style="text-align: center">
                                                            Total Harga</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; foreach($detil_r as $rows) : ?>
                                                        <tr>
                                                            <td>
                                                                <select name="kodeo_1[]" id="kodeo1_<?=$no;?>"
                                                                    class="select2_el_farmasi_baranggud form-control input-largex"
                                                                    onchange="showbarangnameo_1(this.value, <?=$no;?>);cekstok_1(this.value, <?=$no;?>)">
                                                                    <option value="<?= $rows->resepno?>">
                                                                        <?= $rows->kodebarang. '|'. $rows->namabarang;?>
                                                                    </option>


                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input name="namao_1[]" id="namao1_<?=$no;?>" type="text"
                                                                    class="form-control"
                                                                    onkeypress="return tabE(this,event)"
                                                                    value="<?= $rows->namabarang;?>">
                                                            </td>

                                                            <td>
                                                                <input name="sato_1[]" id="sato1_<?=$no;?>" type="text"
                                                                    class="form-control "
                                                                    onkeypress="return tabE(this,event)"
                                                                    value="<?= $rows->satuan?>">
                                                            </td>

                                                            <td>
                                                                <input name="qty_racik_1[]" id="qty_racik1_<?=$no;?>"
                                                                    onchange="totallineo_1(<?=$no;?>);totalo_1()"
                                                                    value="<?= $rows->qtyr?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>

                                                            <td>
                                                                <input name="qty_jual_1[]" id="qty_jual1_<?=$no;?>"
                                                                    onchange="totallineo_1(<?=$no;?>);totalo_1()"
                                                                    value="<?= number_format($rows->qty) ?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>


                                                            <td>
                                                                <input name="hargaoju1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->price?>" id="hargaoju1_<?=$no;?>"
                                                                    type="text" class="form-control rightJustified"
                                                                    readonly>
                                                            </td>

                                                            <td>
                                                                <input name="uangr1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->uangr?>" id="uangr1_<?=$no;?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>

                                                            <td>
                                                                <input name="total_hrg1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->totalrp?>" id="total_hrg1_<?=$no;?>"
                                                                    type="text" class="form-control rightJustified"
                                                                    readonly>
                                                            </td>

                                                        </tr>
                                                        <?php $no++; endforeach; ?>

                                                    </tbody>
                                                </table>

                                                <table class="table" border="0" width="100%">

                                                    <tr class="wells">
                                                        <td colspan="6">
                                                            <button type="button" onclick="tambaho_1()"
                                                                class="btn green"><i class="fa fa-plus"></i>
                                                            </button>
                                                            <button type="button" onclick="hapuso_1()"
                                                                class="btn red"><i class="fa fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%" rowspan="6"
                                                            class="control-labelh leftJustified">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            Resep Manual Dari Dokter
                                                            <textarea type="text" class="form-control " name="resman_1"
                                                                id="resman_1" value="">
													</textarea><br>

                                                            <div class="wells">
                                                                <button id="btnsimpan" type="button"
                                                                    onclick="saveracik_1()" class="btn blue"><i
                                                                        class="fa fa-save"></i>
                                                                    <b>Posting
                                                                        Racik</b></button>
                                                                <!-- 													
													<div class="btn-group">
													<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></button>    
													</div> -->
                                                                <!-- <button id="btncetak" type="button" onclick="javascript:window.open(_urlcetak(),'_blank');" class="btn yellow"><i class="fa fa-print"></i> <b>Cetak</b></button> -->

                                                                <a href="<?= base_url('penjualan_faktur')?>"
                                                                    class="btn btn red"><i class="fa fa-undo"></i><b>
                                                                        KEMBALI </b></a>

                                                                <h4><span id="error"
                                                                        style="display:none; color:#F00">Terjadi
                                                                        Kesalahan... </span> <span id="success"
                                                                        style="display:none; color:#0C0"><b>Data
                                                                            sudah
                                                                            disimpan...</b></span>
                                                                </h4>
                                                            </div>


                                                        </td>
                                                        <td rowspan="6" width="30%">&nbsp;</td>
                                                    <tr>
                                                        <td width="10%" class="control-labelh leftJustified">TOTAL
                                                        </td>
                                                        <td width="6%">&nbsp;</td>
                                                        <td width="2%">&nbsp;</td>
                                                        <td width="15%">
                                                            <input type="text" class="form-control rightJustified"
                                                                name="toto_1" id="toto_1" value="o" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="control-labelh leftJustified">DISKON
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control rightJustified"
                                                                name="disknom_1" id="disknom_1" value="0"
                                                                onchange="totalo_1()">
                                                        </td>
                                                        <td class="control-labelh leftJustified">
                                                            <b>%</b>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control rightJustified"
                                                                name="disk_1" id="disk_1" value="0" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>

                                                        <td class="control-labelh leftJustified">

                                                            <label for="ppn">PPN</label>
                                                        </td>
                                                        <td>
                                                            <input class='form-control' type="checkbox" name="cek_ppn"
                                                                id="cek_ppn" onchange="cek_ppn2()">
                                                        </td>
                                                        <td>&nbsp;</td>

                                                        <td>
                                                            <input type="text" class="form-control rightJustified"
                                                                name="ppn_1" id="ppn_1" value="0" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="control-labelh leftJustified">ONGKOS
                                                            RACIK
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>
                                                            <input type="number" class="form-control rightJustified"
                                                                name="ongra_1" id="ongra_1" value=0>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="control-labelh leftJustified">
                                                            TOTAL+PPN
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>
                                                            <input type="text" class="form-control rightJustified"
                                                                name="totp_1" id="totp_1" value=0 readonly>
                                                        </td>
                                                    </tr>

                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <?php }else{ ?>

                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <table class="table" border="0" width="100%">
                                                    <tr>
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#c7f2ff">

                                                        <td width="10%" class="control-labelh rightJustified">JENIS</td>
                                                        <td width="20%" colspan="2">
                                                            <select id="jenis_1" name="jenis_1" class="form-control">
                                                                <?php 
													if($header_r->jenisracik){ 
													$datapasien = data_master('tbl_barangsetup',array('apocode' => $header_r->jenisracik)); ?>
                                                                <option value="<?= $header_r->jenisracik;?>">
                                                                    <?= $datapasien->aponame;?></option>
                                                                <?php }?>

                                                                <?php 
													$data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='JENISRACIK' and apocode<>'$header_r->jenisracik' ")->result();
													foreach($data as $row){ ?>
                                                                <option value="<?= $row->apocode;?>">
                                                                    <?= $row->aponame;?></option>
                                                                <?php } ?>
                                                            </select>

                                                        <td width=" 15%" class="control-labelh rightJustified">NAMA
                                                            RACIKAN
                                                        </td>
                                                        <td width="20%">
                                                            <input type="text" class="form-control " name="namaracik_1"
                                                                id="namaracik_1" value="<?= $header_r->namaracikan;?>">

                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td width="15%" class="control-labelh rightJustified">CARA PAKAI
                                                        </td>
                                                        <td>
                                                            <select name="carapakai" id="caraPAkai"
                                                                class="form-control">
                                                                <option value=""> --- PILIH ----</option>
                                                                <option value=""
                                                                    <?= ($header_r->aturanpakai=='DIMINUM'?'selected':'') ?>>
                                                                    DIMINUM </option>
                                                                <option value=""
                                                                    <?= ($header_r->aturanpakai=='DIOLES'?'selected':'') ?>>
                                                                    DIOLES </option>
                                                                <option value=""
                                                                    <?= ($header_r->aturanpakai=='DITETES'?'selected':'') ?>>
                                                                    DITETES </option>
                                                            </select>
                                                        </td>

                                                    </tr>
                                                    <tr bgcolor="#c7f2ff">
                                                        <td class="control-labelh rightJustified">JUMLAH</td>
                                                        <td width="8%">
                                                            <input type="number" class="form-control " name="jumracik_1"
                                                                id="jumracik_1" value="<?= $header_r->jumlahracik;?>">
                                                        </td>

                                                        <td width="12%">
                                                            <select name="stajum_1" id="stajum_1" class="form-control">
                                                                <?php 
													if($header_r->kemasanracik){ 
													$datapasien = data_master('tbl_barangsetup',array('apocode' => $header_r->kemasanracik)); ?>
                                                                <option value="<?= $header_r->kemasanracik;?>">
                                                                    <?= $datapasien->aponame;?></option>
                                                                <?php }?>

                                                                <?php 
													$data = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='KEMASANRACIK' and apocode<>'$header_r->kemasanracik' ")->result();
													foreach($data as $row){ ?>
                                                                <option value="<?= $row->apocode;?>">
                                                                    <?= $row->aponame;?></option>
                                                                <?php } ?>

                                                            </select>
                                                        </td>

                                                        <td class="control-labelh rightJustified">ATURAN PAKAI</td>
                                                        <td>
                                                            <input type="text" class="form-control " name="atpakai_1"
                                                                id="atpakai_1" value="<?= $header_r->aturanpakai;?>">

                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td class="control-labelh rightJustified" type="hidden"></td>
                                                        <td>
                                                            <!-- <input type="text" class="form-control " name="atpakai_1"
                                                                id="atpakai_1" value="<?= $header_r->aturanpakai;?>"
                                                                type="hidden"> -->

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>
                                                </table>
                                                <table id="datatableobat_1"
                                                    class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                    <thead class="page-breadcrumb breadcrumb">
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Kode Obat</th>
                                                        <th class="title-white" width="20%" style="text-align: center">
                                                            Nama Obat</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Satuan</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Qty Racik</th>

                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Qty Jual</th>
                                                        <th class="title-white" width="10%" style="text-align: center">
                                                            Harga Jual</th>
                                                        <th class="title-white" width="15%" style="text-align: center">
                                                            Uang R</th>
                                                        <th class="title-white" width="15%" style="text-align: center">
                                                            Total Harga</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
											$no=1;
											foreach($detil_r as $rows){
											?>

                                                        <tr>
                                                            <td>
                                                                <select name="kodeo_1[]" id="kodeo1_<?=$no;?>"
                                                                    class="select2_el_farmasi_baranggud form-control input-largex"
                                                                    onchange="showbarangnameo_1(this.value, <?=$no;?>);cekstok_1(this.value, <?=$no;?>)">
                                                                    <option value="<?= $rows->resepno?>">
                                                                        <?= $rows->kodebarang. '|'. $rows->namabarang;?>
                                                                    </option>


                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input name="namao_1[]" id="namao1_<?=$no;?>" type="text"
                                                                    class="form-control"
                                                                    onkeypress="return tabE(this,event)"
                                                                    value="<?= $rows->namabarang;?>">
                                                            </td>

                                                            <td>
                                                                <input name="sato_1[]" id="sato1_<?=$no;?>" type="text"
                                                                    class="form-control "
                                                                    onkeypress="return tabE(this,event)"
                                                                    value="<?= $rows->satuan?>">
                                                            </td>

                                                            <td>
                                                                <input name="qty_racik_1[]" id="qty_racik1_<?=$no;?>"
                                                                    onchange="totallineo_1(<?=$no;?>);totalo_1()"
                                                                    value="<?= $rows->qtyr?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>

                                                            <td>
                                                                <input name="qty_jual_1[]" id="qty_jual1_<?=$no;?>"
                                                                    onchange="totallineo_1(<?=$no;?>);totalo_1()"
                                                                    value="<?= number_format($rows->qty) ?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>


                                                            <td>
                                                                <input name="hargaoju1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->price?>" id="hargaoju1_<?=$no;?>"
                                                                    type="text" class="form-control rightJustified"
                                                                    readonly>
                                                            </td>

                                                            <td>
                                                                <input name="uangr1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->uangr?>" id="uangr1_<?=$no;?>" type="text"
                                                                    class="form-control rightJustified">
                                                            </td>

                                                            <td>
                                                                <input name="total_hrg1[]" onchange="totallineo_1(<?=$no;?>);"
                                                                    value="<?= $rows->totalrp?>" id="total_hrg1_<?=$no;?>"
                                                                    type="text" class="form-control rightJustified"
                                                                    readonly>
                                                            </td>

                                                        </tr>
                                                        <?php $no++;}?>

                                                    </tbody>
                                                </table>

                                                <table class="table" border="0" width="100%">

                                                    <tr class="wells">
                                                        <td colspan="6">
                                                            <button type="button" onclick="tambaho_1()"
                                                                class="btn green"><i class="fa fa-plus"></i> </button>
                                                            <button type="button" onclick="hapuso_1()"
                                                                class="btn red"><i class="fa fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%" rowspan="6"
                                                            class="control-labelh leftJustified">
                                                            &nbsp;&nbsp;&nbsp;&nbsp; Resep Manual Dari Dokter
                                                            <textarea type="text" class="form-control " name="resman_1"
                                                                id="resman_1" value="">
													</textarea><br>

                                                            <a href="<?= base_url('penjualan_faktur')?>"
                                                                class="btn btn red"><i class="fa fa-undo"></i><b>
                                                                    KEMBALI </b></a>

                                                            <h4><span id="error"
                                                                    style="display:none; color:#F00">Terjadi
                                                                    Kesalahan... </span> <span id="success"
                                                                    style="display:none; color:#0C0"><b>Data sudah
                                                                        disimpan...</b></span></h4>
                                            </div>


                                            </td>
                                            <td rowspan="6" width="30%">&nbsp;</td>
                                            <tr>
                                                <td width="10%" class="control-labelh leftJustified">TOTAL
                                                </td>
                                                <td width="6%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                                <td width="15%">

                                                    <?php
                                                    $totals = 0;
                                                    ?>

                                                    <!-- <strong><span id="_vsubtotal"></span></strong> -->
                                                    <input type="text" class="form-control rightJustified" name="toto_1"
                                                        id="toto_1" value="<?= number_format($header_r->subtotal)?>"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="control-labelh leftJustified">DISKON
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control rightJustified"
                                                        name="disknom_1" id="disknom_1" value="<?=$racik->diskon?>"
                                                        onchange="totalo_1()" readonly>
                                                </td>
                                                <td class="control-labelh leftJustified"><b>%</b></td>
                                                <td>
                                                    <input type="text" class="form-control rightJustified" name="disk_1"
                                                        id="disk_1" value="<?=number_format($racik->diskonrp,0)?>"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td class="control-labelh leftJustified">

                                                    <label for="ppn">PPN</label>
                                                </td>
                                                <td>
                                                    <input class='form-control' type="checkbox"
                                                        <?= ($header_r->ppn == 1?'checked':'') ?> name="cek_ppn"
                                                        id="cek_ppn" onchange="cek_ppn2()" value="" disabled>
                                                </td>
                                                <td>&nbsp;</td>

                                                <td>
                                                    <input type="text" class="form-control rightJustified" name="ppn_1"
                                                        id="ppn_1" value="<?=number_format($racik->ppnrp,0)?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="control-labelh leftJustified">ONGKOS RACIK
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <input type="text" class="form-control rightJustified"
                                                        name="ongra_1" id="ongra_1"
                                                        value="<?= number_format($racik->ongkosracik)?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="control-labelh leftJustified">TOTAL+PPN
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <input type="text" class="form-control rightJustified" name="totp_1"
                                                        id="totp_1" value="<?=number_format($racik->totalrp,0)?>"
                                                        readonly>
                                                </td>
                                            </tr>

                                            </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>


                            <!-- end tab 2 harimas -->


                        </div>
                        <!--tab2-->


                    </div>
                    <!--tab-->
                    <br>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="wells">

                                <?php if ($noedit != 1) { ?>
                                <button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i
                                        class="fa fa-save"></i> Reposting</button>
                                <?php } ?>


                                <a class="btn yellow print_laporan"
                                    onclick="javascript:window.open(_urlcetak(),'_blank');"><i class="fa fa-print"></i>
                                    Cetak</a>

                                <a href="<?= base_url('penjualan_faktur')?>" class="btn btn red"><i
                                        class="fa fa-undo"></i><b>
                                        KEMBALI </b></a>

                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                        id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-5 invoice-block">
                            <div class="well">
                                <table>
                                    <tr>
                                        <td width="40%"><strong>SUB TOTAL </strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>DISKON</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                                    </tr>
                                    <!-- <tr>
                                        <td width="40%"><strong>DPP</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vdpp"></span></strong></td>
                                    </tr> -->
                                    <tr>
                                        <td width="40%"><strong>PPN</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vppn"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>TOTAL RACIKAN</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vracik"></span></strong></td>
                                    </tr>

                                    <tr>
                                        <td width="40%"><strong>TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                                    </tr>
                                    <input type="hidden" id="tersimpan">
                                </table>
                            </div>
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
  $this->load->view('template/footer');  
?>

<script>
var idrow = "<?= $jumdata+1;?>";
var idrow2 = "<?= $jumdata+1;?>";

function tambah() {
    var gudang = $('[name="gudang"]').val();
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    var td9 = x.insertCell(8);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ");cekstok(this.value)' class='select2_el_farmasi_baranggud form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='nama[]'    id=nama" + idrow + " onchange='totalline(" + idrow +
        ")' value=''  type='text' class='form-control'  >";
    td3.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td4.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified' readonly>";
    td6.innerHTML = "<input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totalline(" + idrow +
        ")' class='form-control' disabled>";
    td7.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td8.innerHTML = "<input name='disc2[]'   id=disc2" + idrow + " onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td9.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%' readonly>";

    initailizeSelect2_farmasi_baranggud(gudang);
    idrow++;
}
function tambaho_1() {
    var gudang = $('[name="gudang"]').val();
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);
    var td8 = x.insertCell(7);
    var td9 = x.insertCell(8);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ");cekstok(this.value)' class='select2_el_farmasi_baranggud form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='nama[]'    id=nama" + idrow + " onchange='totallineo_1(" + idrow +
        ")' value=''  type='text' class='form-control'  >";
    td3.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totallineo_1(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td4.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totallineo_1(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified' readonly>";
    td6.innerHTML = "<input type='checkbox' name='ppn[]'    id=ppn" + idrow + " onchange='totallineo_1(" + idrow +
        ")' class='form-control' disabled>";
    td7.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totallineo_1(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td8.innerHTML = "<input name='disc2[]'   id=disc2" + idrow + " onchange='totallineo_1(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td9.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%' readonly>";

    initailizeSelect2_farmasi_baranggud(gudang);
    idrow++;
}

function tambah2() {
    var x = document.getElementById('datatable2').insertRow(idrow2);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);

    var akun = "<select name='lkode[]' id=lkode" + idrow2 +
        " class='select2_el form-control' ><option value=''>--- Pilih Akun ---</option></select>";

    td1.innerHTML = akun;
    td2.innerHTML = "<input name='ljumlah[]' id=ljumlah" + idrow2 + " onchange='totalline(" + idrow2 +
        ")' value='0'  onchange='total()' type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='lket[]'    id=lket" + idrow2 + " type='text' class='form-control' >";
    initailizeSelect2();
    idrow2++;
}


function showbarang(str) {
    var xhttp;
    var cust = $('[name="cust"]').val();
    var str = str + '~' + cust;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>penjualan_faktur/getbarang/" + str, true);
    xhttp.send();
}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $('#sat' + vid).val('');
    $('#harga' + vid).val(0);
    var customer = $('#cust').val();
    $.ajax({
        url: "<?php echo base_url();?>penjualan_faktur/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#nama' + vid).val(data.namabarang);
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hargajual));
            totalline(vid);
        }
    });

}


function save() {
    var tanggal = $('[name="tanggal"]').val();
    var gudang = $('[name="gudang"]').val();
    var pembeli = $('[name="pembeli"]').val();
    var total = $('#_vtotal').text();
    var nobukti = $('#noresep').val();

    if (gudang == "" || pembeli == "" || total == "0.00" || total == "") {
        swal('PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    } else {

        console.log($('#frmpenjualan').serialize())

        $.ajax({
            url: '<?php echo site_url('penjualan_faktur/save/2?vtotal=')?>' + total,
            data: $('#frmpenjualan').serialize(),
            type: 'POST',

            success: function(data) {
                //document.getElementById("btnsimpan").disabled=true;
                document.getElementById("tersimpan").value = "OK";

                swal({
                    title: "PENJUALAN RESEP",
                    html: "<p> No. Bukti   : <b>" + nobukti + "</b> </p>" +
                        "Tanggal :  " + tanggal + "<br>" + "Total: " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>penjualan_faktur";
                });

            },
            error: function(data) {
                swal('PENJUALAN', 'Data gagal disimpan ...', '');

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

function jumlah() {

}

function separateComma(val) {
    // remove sign if negative
    var sign = 1;
    if (val < 0) {
        sign = -1;
        val = -val;
    }
    // trim the number decimal point if it exists
    let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
    let len = num.toString().length;
    let result = '';
    let count = 1;

    for (let i = len - 1; i >= 0; i--) {
        result = num.toString()[i] + result;
        if (count % 3 === 0 && count !== 0 && i !== 0) {
            result = ',' + result;
        }
        count++;
    }

    // add number after decimal point
    if (val.toString().includes('.')) {
        result = result + '.' + val.toString().split('.')[1];
    }
    // return result with - sign if negative
    return sign < 0 ? '-' + result : result;
}

function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tdpp = 0;
    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;
    subtotal = 0;
    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[2].children[0].value;
        harga = row.cells[4].children[0].value;
        diskon = row.cells[7].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1 * harga1);
        tdpp = jumlah1 * 111;

        //diskon      = eval((diskon1/100)*jumlah1*harga1);
        diskon = eval(diskon1);
        tdiskon = tdiskon + diskon;


        var subtot = jumlah1 * harga1;
        subtotal += subtot;
        // var ongkosracik = document.getElementById('ongra_1');
        var ongkosracik = $('#totp_1').val();
        // totalracik = parseInt(documen.getElemetById('totp_1')).value;

        // var totalracik = 

        if (document.getElementById('ppn' + i).checked == true) {
            tppn = tppn + (eval(jumlah1 * harga1) * 0.11);
        }
    }
    $.ajax({
        url: '<?php echo base_url();?>farmasi_bapb/cekppn',
        type: "GET",
        dataType: "json",
        success: function(data) {
            cekppn = data.prosentase;
            cekppn2 = cekppn / 100;
            tjumlah2 = (tjumlah - tdiskon) / 11;
            tppn2 = (tjumlah2) * 11 / 100;
            var totalracikx = $('#totp_1').val();
            var totalracik = Number(parseInt(totalracikx.replaceAll(',','')));

            // document.getElementById("days")


            var ppn = (subtotal - tdiskon) * cekppn2;
            var total = (subtotal - tdiskon) + ppn;
            var totals = total + totalracik;
            console.log(totals);
            var dpp = (total - tdiskon) / (111 / 100);

            console.log(total);

            var ttl = tjumlah + tppn;
            var new_tjumlah = separateComma(Number(subtotal).toFixed(0));
            var new_tdiskon = separateComma(Number(tdiskon).toFixed(0));
            var new_dpp = separateComma(Number(dpp).toFixed(0));
            var new_tppn2 = separateComma(Number(ppn).toFixed(0));
            var new_ttl = separateComma(Number.parseInt(totals).toFixed(0));

            document.getElementById("_vsubtotal").innerHTML = new_tjumlah;
            document.getElementById("_vdiskon").innerHTML = new_tdiskon;
            // document.getElementById("_vdpp").innerHTML = new_dpp;
            document.getElementById("_vracik").innerHTML = ongkosracik;
            document.getElementById("_vppn").innerHTML = new_tppn2;
            document.getElementById("_vtotal").innerHTML = new_ttl;
        }
    });


    // document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah2);
    // document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
    // document.getElementById("_vppn").innerHTML = separateComma(tppn2);
    // document.getElementById("_vtotal").innerHTML = separateComma(tjumlah + tppn);

    if (tjumlah > 0) {
        document.getElementById("btnsimpan").disabled = false;
    } else {
        document.getElementById("btnsimpan").disabled = true;
    }

}

function totalline(id) {

    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[2].children[0].value * harga;
    vdiskon = (row.cells[6].children[0].value / 100) * jumlah;

    if (eval(vdiskon) > 0) {
        diskon = (row.cells[6].children[0].value / 100) * harga;
        row.cells[7].children[0].value = formatCurrency1(diskon);
        tot = harga - diskon;
    } else {
        var diskon = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
        tot = harga - diskon;
    }

    tot = jumlah - diskon;

    kode = row.cells[0].children[0].value;
    cekhargajual(kode, harga);

    if (document.getElementById('ppn' + id).checked == true) {
        tot = tot * 1.1;
    }

    row.cells[8].children[0].value = formatCurrency1(tot);
    total();

}

function getinfopasien() {
    var xhttp;
    var vid = $('#pasien').val();
    $.ajax({
        url: "<?php echo base_url();?>pasien/getinfopasien/?id=" + vid,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('#namapasien').val(data.namapas);
            $('#alamat').val(data.alamat);
            $('#phone').val(data.phone);


        }
    });


}


function getdataklinik() {
    var xhttp;
    var str = $('[name=pembeli]').val();

    if (str == "") {

    } else {
        initailizeSelect2_register(str);

    }
}

window.onload = function() {
    document.getElementById('noresep').focus();
    //document.getElementById('btncetak').disabled=true;
    document.getElementById('btnsimpan').disabled = true;
    document.getElementById('tersimpan').value = "";

};

total();

function _urlcetak() {
    var baseurl = "<?php echo base_url()?>";
    var nobukti = $('#noresep').val();
    return baseurl + 'penjualan_faktur/cetak/?nobukti=' + nobukti;
}

function cekstok(str) {
    var gudang = $('#gudang').val();
    var xhttp;
    var customer = $('#cust').val();
    $.ajax({
        url: "<?php echo base_url();?>penjualan_faktur/cekstok/?kode=" + str + '&gudang=' + gudang,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            if (data <= '0') {
                swal('PENJUALAN', 'Stok barang kurang ...', '');
            }
        }
    });

}

function cekhargajual(str, harga) {
    var gudang = $('#gudang').val();
    var xhttp;
    var customer = $('#cust').val();
    $.ajax({
        url: "<?php echo base_url();?>penjualan_faktur/cekharga/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            if (harga < data) {
                swal('PENJUALAN', 'Harga Jual Kurang dari HNA ...', '');
            }
        }
    });

}
</script>


</body>

</html>