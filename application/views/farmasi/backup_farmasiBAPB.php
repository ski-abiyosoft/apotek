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
            <span class="title-web"><?= $modul; ?> <small><small><?= $submodul; ?></small>
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
                <a class="title-white" href="<?php echo base_url();?><?= $url;?>">
                    Daftar <?= $submodul; ?>
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Data
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
        <form id="frmpembelian" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                <?= $submodul;?>
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">
                                   Biaya Lain-Lain
								</a>
							</li-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Vendor
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="supp" name="supp" class="form-control select2_el_vendor"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)"
                                                onchange="getpo()" readonly>
                                                <?php
														if($header->vendor_id){
															$data = data_master("tbl_vendor", array('vendor_id' => $header->vendor_id))->vendor_name;  
														}
														?>
                                                <option value="<?= $header->vendor_id;?>"><?= $data;?></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor PO#</label>
                                        <div class="col-md-9">
                                            <select id="nomorpo" name="nomorpo"
                                                class="form-control select2_el_farmasi_po" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)" onchange="getdatapo(this.value)">
                                                <?php
														if($header->po_no){
															$data = data_master("tbl_barangdterima", array('po_no' => $header->po_no))->po_no;  
														}
													?>

                                                <option value="<?= $header->po_no;?>"><?= $data;?></option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tgl. Terima
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">

                                            <input id="tanggal" name="tanggal" class="form-control input-medium"
                                                type="date"
                                                value="<?php echo date('Y-m-d', strtotime($header->terima_date));?>" />

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <select id="gudang" name="gudang"
                                                class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)">
                                                <?php
														if($header->gudang){
															$data = data_master("tbl_depo", array('depocode' => $header->gudang))->keterangan;  
														}
														?>
                                                <option value="<?= $header->gudang;?>"><?= $data;?></option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 control-label">BAPB No.</label>
                                        <div class="col-md-3">
                                            <input type="text" name="noterima" id="noterima" class="form-control"
                                                value="<?= $header->terima_no; ?>" readonly>
                                        </div>

                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tgl. Tukar</label>
                                        <div class="col-md-4">

                                            <input id="tanggaltukar" name="tanggaltukar"
                                                class="form-control input-medium" type="date"
                                                value="<?php echo date('Y-m-d', strtotime($header->tgltukar));?>" />

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Faktur</label>
                                        <div class="col-md-4">
                                            <input type="text" name="nofaktur" id="nofaktur" class="form-control"
                                                value="<?= $header->invoice_no;?>">
                                        </div>
                                        <label class="col-md-2 control-label">SJ No.</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" placeholder="" name="nomorsj"
                                                id="nomorsj" value="<?= $header->sj_no;?>">
                                        </div>

                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Materai</label>
                                        <div class="col-md-4">
                                            <select name="materai" id="materai" class="form-control select2me"
                                                onchange="cekmaterai()">
                                                <option <?= ($header->materai==0?'selected':'') ?> value="0">Tanpa
                                                    Materai</option>
                                                <!-- <option <?= ($header->materai==3000?'selected':'') ?> value="3000">3000</option>
													<option <?= ($header->materai==6000?'selected':'') ?> value="6000">6000</option> -->
                                                <option <?= ($header->materai==10000?'selected':'') ?> value="10000">
                                                    10000</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pembayaran
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="pembayaran" name="pembayaran"
                                                class="form-control select2_el_pembayaran" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)" onchange="cekjt(this.value)">
                                                <?php
														if($header->term){
															$data = data_master("tbl_setinghms", array('lset' => 'PAYM', 'kodeset' => $header->term))->keterangan;  
														}
														?>
                                                <option value="<?= $header->term;?>"><?= $data;?></option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kurs</label>
                                        <div class="col-md-4">
                                            <input type="text" name="kurs" class="form-control"
                                                value="<?= $header->kurs;?>">
                                        </div>
                                        <label class="col-md-2 control-label">Rate</label>
                                        <div class="col-md-3">
                                            <input type="text" name="rate" class="form-control"
                                                value="<?= $header->kursrate;?>">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jatuh Tempo
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="jatuhtempo" id="jatuhtempo"
                                                value="<?php echo date('Y-m-d', strtotime($header->due_date));?>"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Diskon (%)</label>
                                        <div class="col-md-3">
                                            <input id="diskonpr" name="diskonpr" class="form-control" type="text"
                                                value="0" />
                                        </div>
                                        <label class="col-md-2 control-label">Rp</label>
                                        <div class="col-md-4">
                                            <input id="diskonrp" name="diskonrp" class="form-control" type="text"
                                                value="0" />
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ongkir</label>
                                        <div class="col-md-4">
                                            <input type="text" name="ongkir" id="ongkir" class="form-control"
                                                value="<?= $header->ongkir;?>">
                                        </div>
                                        <label class="col-md-2 control-label">Kemasan</label>
                                        <div class="col-md-3">
                                            <input type="text" name="kemasan" id="kemasan" class="form-control"
                                                value="<?= $header->bkemasan;?>">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">

                                        <thead class="page-breadcrumb breadcrumb">
                                            <th class="title-white" width="20%" style="text-align: center">Nama Barang
                                            </th>
                                            <th class="title-white" width="10%" style="text-align: center">Qty</th>
                                            <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="10%" style="text-align: center">Harga</th>
                                            <th class="title-white" width="5%" style="text-align: center">Diskon</th>
                                            <th class="title-white" width="10%" style="text-align: center">Diskon Rp
                                            </th>
                                            <th class="title-white" width="5%" style="text-align: center">Tax</th>
                                            <th class="title-white" width="15%" style="text-align: center">Total Harga
                                            </th>
                                            <th class="title-white" width="10%" style="text-align: center">Expire</th>
                                            <th class="title-white" width="10%" style="text-align: center">PO No</th>

                                        </thead>

                                        <tbody>
                                            <?php
										$no = 1;
										foreach($detil as $row){
										?>
                                            <tr>
                                                <td width="20%">
                                                    <!-- <select name="kode[]" id="kode<?php echo $no;?>" class="select2_el_farmasi_barang form-control input-largex" onchange="showbarangname(this.value, 1)">
                                                        <option value="<?= $row->kodebarang;?>"><?= $row->namabarang;?></option>
                                                    </select> -->
                                                    <select name="kode[]" id="kode<?php echo $no;?>"
                                                        class="select2_el_farmasi_barang form-control input-largex"
                                                        onchange="showbarangname(this.value, <?= $no;?>)">
                                                        <option value="<?= $row->kodebarang;?>"><?= $row->namabarang;?>
                                                        </option>
                                                    </select>
                                                </td>

                                                <td width="5%">
                                                    <!-- <input name="qty[]" onchange="totalline(<?= $no;?>);total()" value="<?= $row->qty_terima;?>" id="qty<?= $no;?>" type="text" class="form-control rightJustified"> -->
                                                    <input name="qty[]" onchange="totalline(<?= $no;?>);total()"
                                                        value="<?= number_format($row->qty_terima);?>"
                                                        id="qty<?php echo $no;?>" type="text"
                                                        class="form-control rightJustified">
                                                </td>
                                                <td width="10%">
                                                    <!-- <input name="sat[]" id="sat<?= $no;?>" value="<?= $row->satuan;?>" type="text" class="form-control " onkeypress="return tabE(this,event)"> -->
                                                    <input name="sat[]" id="sat<?php echo $no;?>"
                                                        value="<?= $row->satuan;?>" type="text" class="form-control"
                                                        onkeypress="return tabE(this,event)">
                                                </td>
                                                <td width="10%">
                                                    <!-- <input name="harga[]" onchange="totalline(<?= $no;?>);total()" value="<?= $row->price;?>" id="harga<?= $no;?>" type="text" class="form-control rightJustified"> -->
                                                    <input name="harga[]"
                                                        onchange="totalline(<?= $no;?>);total();cekharga(<?= $no;?>);"
                                                        value="<?=number_format($row->price);?>"
                                                        id="harga<?php echo $no;?>" type="text"
                                                        class="form-control rightJustified">
                                                </td>
                                                <td width="5%">
                                                    <!-- <input name="disc[]" onchange="totalline(<?= $no;?>);total()" value="<?= $row->discount;?>" id="disc<?= $no;?>" type="text" class="form-control rightJustified "> -->
                                                    <input name="disc[]"
                                                        onchange="totalline(<?= $no;?>);total(); cekdisc(<?= $no; ?>)"
                                                        value="<?= $row->discount;?>" id="disc<?php echo $no;?>"
                                                        type="text" class="form-control rightJustified ">
                                                </td>
                                                <td width="10%">
                                                    <!-- <input name="discrp[]" onchange="totalline(<?= $no;?>);total()" value="<?= $row->discountrp;?>" id="discrp<?= $no;?>" type="text" class="form-control rightJustified "> -->
                                                    <input name="discrp[]"
                                                        onchange="totalline(<?= $no;?>);total(); cekdiscrp(<?= $no; ?>)"
                                                        value="<?= number_format($row->discountrp);?>"
                                                        id="discrp<?php echo $no;?>" type="text"
                                                        class="form-control rightJustified ">
                                                </td>
                                                <td>
                                                    <!-- <input type="checkbox" name="tax[]" <?= ($row->vat==1?'checked':'') ?> id="tax<?= $no;?>" class="form-control" onchange="totalline(<?= $no;?>);total()"> -->
                                                    <input type="checkbox" name="tax[]"
                                                        <?= ($row->vat==1?'checked':'') ?> id="tax<?php echo $no;?>"
                                                        class="form-control" onchange="totalline(<?= $no;?>);total()"
                                                        value="<?= $row->vat; ?>" readonly>
                                                    <input type="hidden" name="taxz[]" id="taxz1" class="form-control"
                                                        onchange="totalline(<?= $no; ?>);total();cektax(<?= $no; ?>)"
                                                        onclick="cektax(<?= $no; ?>)" value="<?= $row->vat; ?>">
                                                </td>

                                                <td width="15%">
                                                    <!-- <input name="jumlah[]" value="<?= $row->totalrp;?>" id="jumlah<?= $no;?>" type="text" class="form-control rightJustified" size="40%"> -->
                                                    <input name="jumlah[]" value="<?= number_format($row->totalrp);?>"
                                                        id="jumlah<?php echo $no;?>" type="text"
                                                        class="form-control rightJustified" size="40%" readonly>
                                                </td>
                                                <td width="10%">
                                                    <!-- <input name="expire[]" onchange="totalline(<?= $no;?>);total()" value="<?= date('Y-m-d',strtotime($row->exp_date));?>" id="expire<?= $no;?>" type="date" class="form-control"> -->
                                                    <input name="expire[]" onchange="totalline(<?= $no;?>);total()"
                                                        value="<?= date('Y-m-d',strtotime($row->exp_date));?>"
                                                        id="expire<?php echo $no;?>" type="date" class="form-control">
                                                </td>
                                                <td width="10%">
                                                    <!-- <input name="po[]" onchange="totalline(<?= $no;?>);total()" value="<?= $row->po_no;?>" id="po<?= $no;?>" type="text" class="form-control"> -->
                                                    <input name="po[]" onchange="totalline(<?= $no;?>);total()"
                                                        value="<?= $row->po_no;?>" id="po<?php echo $no;?>" type="text"
                                                        class="form-control">
                                                </td>
                                            </tr>
                                            <?php $no++; } ?>
                                        </tbody>
                                    </table>

                                    <!-- <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i
                                                        class="fa fa-plus"></i> </button>
                                                <button type="button" onclick="hapus()" class="btn red"><i
                                                        class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>
                                    </div> -->


                                </div>
                            </div>
                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">

                            <div class="row">
                                <div class="col-xs-9">

                                </div>
                            </div>
                        </div>
                        <!-- tab2-->

                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-9">
                            <div class="wells">

                                <div class="btn-group">
                                    <button type="button" onclick="savex()" class="btn blue"><i class="fa fa-save"></i>
                                        <b>Simpan </b></button>
                                </div>

                                <!-- <div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button> 			
									</div> -->

                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('farmasi_bapb/')?>"><i
                                            class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>



                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                        id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-3 invoice-block">
                            <div class="well">
                                <table id="tabeltotal">
                                    <tr>
                                        <td width="40%"><strong>SUB TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>MATERAI</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vmaterai"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>DISKON</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>PPN</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vppn"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                                        <input type="hidden" id="ppn2_" name="ppn2_" value="<?= $ppn2->prosentase;?>">
                                    </tr>

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
function cektax(id) {
    var taxz = $('#tax' + id).is(':checked');
    if (taxz == false) {
        var tax = 0;
    } else {
        var tax = 1;
    }
    console.log(tax);
}

function cekmaterai() {
    var materai = $('#materai').val();
    document.getElementById("_vmaterai").innerHTML = separateComma(materai);
    var totalx = $('#_vtotal').text();
    var total = Number(parseInt(totalx.replaceAll(',', '')));
    if (materai == 10000) {
        var total_new = total + Number(materai);
        document.getElementById("_vtotal").innerHTML = separateComma(total_new);
    } else if (materai == 0) {
        var totala = $('#_vtotal').text();
        var totalb = Number(parseInt(totala.replaceAll(',', '')));
        var total_new = totalb - 10000;
        document.getElementById("_vtotal").innerHTML = separateComma(total_new);
    }
    // console.log(total);
}

function cekdiscrp(id) {
    var cekppn2 = $('#ppn2_').val() / 100;
    var tmateraix = $("#materai").val();
    var tmaterai = Number(tmateraix);
    var hargax = $('#harga' + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var qtyx = $('#qty' + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    $('#disc' + id).val(0);
    var jumlah = qty * harga;
    var discrpx = $('#discrp' + id).val();
    var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
    var jumlah_new = jumlah - discrp;
    $('#discrp' + id).val(formatCurrency1(discrp));
    $('#jumlah' + id).val(formatCurrency1(jumlah_new));
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    var subtotal = 0;
    var subtotal1 = 0;
    var diskon = 0;
    for (i = 1; i < rowCount; i++) {
        var qty1x = $('#qty' + i).val();
        var qty1 = Number(parseInt(qty1x.replaceAll(',', '')));
        var harga1x = $('#harga' + i).val();
        var harga1 = Number(parseInt(harga1x.replaceAll(',', '')));
        var discrp1x = $('#discrp' + i).val();
        var discrp1 = Number(parseInt(discrp1x.replaceAll(',', '')));
        var jumlah1x = $('#jumlah' + i).val();
        var jumlah1 = Number(parseInt(jumlah1x.replaceAll(',', '')));
        subtotal = qty * harga;
        subtotal1 += subtotal;
        diskon += discrp1;
    }
    var subtotal2 = $('#_vsubtotal').text();
    var subtotal3 = Number(parseInt(subtotal2.replaceAll(',', '')));
    var diskon2 = $('#_vdiskon').text();
    var diskon3 = Number(parseInt(diskon2.replaceAll(',', '')));
    var ppn = subtotal3 * cekppn2;
    var total = subtotal3 + tmaterai - diskon3 + ppn;
    console.log(total);
    document.getElementById("_vdiskon").innerHTML = separateComma(diskon3);
    document.getElementById("_vsubtotal").innerHTML = separateComma(subtotal3);
    document.getElementById("_vppn").innerHTML = separateComma(ppn);
    document.getElementById("_vtotal").innerHTML = separateComma(total);
}

function cekdisc(id) {
    var cekppn2 = $('#ppn2_').val() / 100;
    var tmateraix = $("#materai").val();
    var tmaterai = Number(tmateraix);
    var hargax = $('#harga' + id).val();
    var harga = Number(parseInt(hargax.replaceAll(',', '')));
    var disc = $('#disc' + id).val();
    var qtyx = $('#qty' + id).val();
    var qty = Number(parseInt(qtyx.replaceAll(',', '')));
    var discrp = harga * qty * disc / 100;
    var jum_ori = qty * harga;
    var jumlah = jum_ori - discrp;
    $('#discrp' + id).val(formatCurrency1(discrp));
    $('#jumlah' + id).val(formatCurrency1(jumlah));
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    var subtotal = 0;
    var diskon = 0;
    for (i = 1; i < rowCount; i++) {
        var jumtabx = $('#jumlah' + i).val();
        var jumtab = Number(parseInt(jumtabx.replaceAll(',', '')));
        var discrpr = $('#discrp' + i).val();
        var discrpr = Number(parseInt(discrpr.replaceAll(',', '')));
        subtotal += jumtab;
        diskon += discrpr;
    }
    var ppn = subtotal * cekppn2;
    var tot = subtotal + tmaterai - diskon + ppn;
    document.getElementById("_vdiskon").innerHTML = separateComma(diskon);
    document.getElementById("_vsubtotal").innerHTML = separateComma(subtotal);
    document.getElementById("_vppn").innerHTML = separateComma(ppn);
    document.getElementById("_vtotal").innerHTML = separateComma(tot);
}

function cekharga(id) {
    var cekppn2 = $('#ppn2_').val() / 100;
    var harga = document.getElementById('harga' + id + '').value;
    var hargax = parseInt(harga.replaceAll(',', ''));
    var qty = document.getElementById('qty' + id + '').value;
    var qtyx = parseInt(qty.replaceAll(',', ''));
    var kode = document.getElementById('kode' + id + '').value;
    var tmateraix = $("#materai").val();
    var tmaterai = Number(tmateraix);
    var jumlah = harga * qty;
    totalline(id);
    $.ajax({
        url: "<?php echo base_url();?>Farmasi_bapb/cekharga/?kode=" + kode + "&harga=" + hargax,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            var vtotal = $('#_vtotal').text();
            var total = parseInt(vtotal.replaceAll(',', ''));
            if (total >= '5000000') {
                $('#materai').val("10000").change();
            }
            if (data.status == 1) {
                swal({
                    title: "HARGA BARANG",
                    html: "Harga tidak boleh lebih kecil dari harga sebelumnya",
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    tqty = 0;
                    tjumlah = 0;
                    tdiskon = 0;
                    subtot = 0;
                    var sub_total = 0;
                    var diskonnet = 0;
                    var jumlah_new = data.harga * qty;
                    var table = document.getElementById('datatable');
                    var rowCount = table.rows.length;
                    // let abc = data.harga;
                    var sub_total = 0;
                    var diskonnet = 0;
                    $('#harga' + id + '').val(formatCurrency1(data.harga));
                    for (var i = 1; i < rowCount; i++) {
                        var row = table.rows[i];

                        var qtyc = $("#qty" + i).val();
                        var hargac = $("#harga" + i).val();
                        var diskonc = $("#disc" + i).val();
                        var diskonrpc = $("#discrp" + i).val();
                        var taxz = $("#tax" + i).val();
                        var jumlahc = $("#jumlah" + i).val();
                        var qtyb = parseInt(qtyc.replaceAll(',', ''));
                        var diskonrpb = parseInt(diskonrpc.replaceAll(',', ''));
                        var jumlahb = Number(jumlahc.replace(/[^0-9\.]+/g, ""));
                        var hargab = Number(hargac.replace(/[^0-9\.]+/g, ""));
                        var diskonb = Number(diskonc.replace(/[^0-9\.]+/g, ""));
                        var harga_satuan = hargac;
                        subtot += (hargab * qtyb);
                        sub_total += Number(parseInt(jumlahc.replaceAll(',', '')));
                        diskonnet += Number(diskonrpb);
                        // console.log(diskonrpc);
                    }
                    var dsc = (data.harga * qty) * diskonb / 100;
                    $('#jumlah' + id + '').val(formatCurrency1(jumlah_new));
                    $('#discrp' + id + '').val(formatCurrency1(dsc));
                    jumtot = data.harga * qty - dsc;
                    $('#jumlah' + id + '').val(formatCurrency1(jumtot));
                    ppn = subtot * cekppn2;
                    tot = subtot + tmaterai - diskonnet + ppn;
                    console.log(subtot);
                    document.getElementById("_vdiskon").innerHTML = separateComma(diskonnet);
                    document.getElementById("_vsubtotal").innerHTML = separateComma(subtot);
                    document.getElementById("_vppn").innerHTML = separateComma(ppn);
                    document.getElementById("_vtotal").innerHTML = separateComma(tot);
                });
            } else if (data.status == 2) {
                tqty = 0;
                tjumlah = 0;
                tdiskon = 0;
                diskonnet = 0;
                diskonnet1 = 0;
                var sub_total = 0;
                var jumlah_new2 = harga * qty;
                var table = document.getElementById('datatable');
                var rowCount = table.rows.length;
                for (var i = 1; i < rowCount; i++) {
                    var row = table.rows[i];
                    var qtyc = $("#qty" + i).val();
                    var hargac = $("#harga" + i).val();
                    var diskonc = $("#disc" + i).val();
                    var diskonrpc = $("#discrp" + i).val();
                    var taxz = $("#tax" + i).val();
                    var jumlahc = $("#jumlah" + i).val();
                    var qtyb = Number(qtyc.replaceAll(',', ''));
                    var jumlahb = Number(jumlahc.replaceAll(',', ''));
                    var hargab = Number(hargac.replaceAll(',', ''));
                    var diskonb = Number(diskonc.replaceAll(',', ''));
                    var harga_satuan = hargac;
                    sub_total += parseInt(harga_satuan.replaceAll(',', ''));
                    diskonnet += parseInt(diskonrpc.replaceAll(',', ''));
                    var dsc = (hargax * qty) * diskonb / 100;
                    tot = hargax * qtyx - dsc;
                    $('#discrp' + id + '').val(formatCurrency1(dsc));
                    var discxe = $('#discrp' + id + '').val(formatCurrency1(dsc));
                    $('#harga' + id + '').val(formatCurrency1(harga));
                    $('#jumlah' + id + '').val(formatCurrency1(tot));
                }
                for (x = 1; x < rowCount; x++) {
                    hargav = $('#discrp' + x).val();
                    diskonnet1 += Number(parseInt(hargav.replaceAll(',', '')));
                }
                var subtotx = $('#_vsubtotal').text();
                var subtot = parseInt(subtotx.replaceAll(',', ''));
                var ppn = subtot * cekppn2;
                var tot = subtot + tmaterai - diskonnet1 + ppn;
                document.getElementById("_vdiskon").innerHTML = separateComma(diskonnet1);
                document.getElementById("_vppn").innerHTML = separateComma(ppn);
                document.getElementById("_vtotal").innerHTML = separateComma(tot);
            }
        }
    });
}

$(document).ready(function() {
    var cekppn2 = $('#ppn2_').val() / 100;
    var tmateraix = $("#materai").val();
    var vtotal = $('#_vtotal').text();
    var xtotal = parseInt(vtotal.replaceAll(',', ''));
    if (xtotal >= '5000000') {
        $('#materai').val('10000').change();
    }
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;
    for (var i = 1; i < rowCount; i++) {
        var harga = $('#harga' + i).val();
        $('#harga' + i).val(formatCurrency1(harga));
        var discrp = $('#discrp' + i).val();
        $('#discrp' + i).val(formatCurrency1(discrp));
        var jumlah = $('#jumlah' + i).val();
        $('#jumlah' + i).val(formatCurrency1(jumlah));
        var row = table.rows[i];
        var jumlah = $("#qty" + i).val();
        var harga = $("#harga" + i).val();
        var diskon = $("#disc" + i).val();
        var diskonrp = $("#discrp" + i).val();
        var taxz = $("#tax" + i).val();
        var subtotal = $("#jumlah" + i).val();
        var jumlah1 = Number(parseInt(jumlah.replaceAll(',', '')));
        var harga1 = Number(parseInt(harga.replaceAll(',', '')));
        var diskon1 = Number(parseInt(diskon.replaceAll(',', '')));
        var diskon2 = Number(parseInt(diskonrp.replaceAll(',', '')));
        var tax1 = Number(taxz);
        var subtotal1 = Number(parseInt(subtotal.replaceAll(',', '')));
        tjumlah = tjumlah + eval(jumlah1 * harga1);
        tjumlahx = jumlah1 * harga1;
        diskon = eval((diskon1 / 100) * jumlah1 * harga1);
        tdiskon = tdiskon + diskon2;
        if (tax1 == 1) {
            tppn = tppn + (eval(subtotal1 * cekppn2));
        }
    }
    var tmaterai = Number(tmateraix);

    var abc = Number(tjumlah - tdiskon + tppn);
    if (tmaterai == 10000) {
        var tmattotal = abc + 10000 - 20000;
    } else {
        var tmattotal = abc;
    }
    ppnf = tjumlah * cekppn2;
    tot = ((tjumlah + tmaterai) - tdiskon) + tppn;
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
    document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai);
    document.getElementById("_vppn").innerHTML = separateComma(tppn);
    document.getElementById("_vtotal").innerHTML = separateComma(tot);
});
var idrow = <?= $jumdata+1;?>;

// var idrow = 2;
var rowCount;
var arr = [1];

function tambah() {

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
    var td10 = x.insertCell(9);

    var akun = "<select name='kode[]' id=kode" + idrow +
        " class='select2_el_farmasi_barang form-control' onchange='showbarangname(this.value," + idrow + ")' >";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id='qty" + idrow + "' onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id='sat" + idrow + "' type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id='harga" + idrow + "' onchange='totalline(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified'>";
    td5.innerHTML = "<input name='disc[]'   id='disc" + idrow + "' onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td6.innerHTML = "<input name='discrp[]'   id='discrp" + idrow + "' onchange='totalline(" + idrow +
        ")' value='0'  type='text' class='form-control rightJustified'  >";
    td7.innerHTML = "<input type='checkbox' value='0' name='tax[]' id='tax" + idrow + "' onchange='totalline(" + idrow +
        ")' class='form-control'>";
    td8.innerHTML = "<input name='jumlah[]' id='jumlah" + idrow +
        "' type='text' class='form-control rightJustified' size='40%' readonly>";
    td9.innerHTML = "<input name='expire[]'  id=expire" + idrow + " onchange='totalline(" + idrow +
        ") value=''  type='date' class='form-control'>";
    td10.innerHTML = "<input name='po[]'  id=po" + idrow + " onchange='totalline(" + idrow +
        ") value=''  type='text' class='form-control'>";

    initailizeSelect2_farmasi_barang();
    idrow++;
}

function cekjt(str) {

    var strr = str;
    var tglter = document.getElementById('tanggal').value;

    $.ajax({
        url: "<?php echo base_url();?>farmasi_bapb/cekhari/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            inputHari = data.valuerp;
            var hariKedepan = new Date(new Date(tglter).getTime() + (inputHari * 24 * 60 * 60 * 1000));
            document.getElementById('jatuhtempo').value = hariKedepan.toISOString().slice(0, 10);
        }
    });


}

function cekpo2() {

    var cekpoo = $('#cekpo').is(':checked');
    var isi = 'NonPO';
    var supp = $('[name="supp"]').val();


    if (cekpoo == false) {
        $('#nopo').html('<input class="form-control" name="nomorpo" value="' + isi + '" disabled />');

    } else {

        $('[name="supp"]').val('');
        document.getElementById("supp").innerHTML = ("");
        $('#nopo').html(
            '<select id="nomorpo" name="nomorpo" class="form-control select2_el_farmasi_po" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" onchange="getdatapo(this.value)"></select>'
        );

    }


}

function showbarangname(str, id) {

    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>farmasi_po/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hargabeli));
            totalline(vid);
        }
    });
}

// function savex() {
//     var matt = $('[name="materai"]').val();
//     var table = document.getElementById('datatable');
//     var rowCount = table.rows.length;
//     var nomor = $('[name="noterima"]').val();

//     var supp = $('[name="supp"]').val();
//     var nomorpo = $('[name="nomorpo"]').val();
//     var total = $('#_vtotal').text();
//     var tanggal = $('[name="tanggal"]').val();

//     var jfalse = 0;
//     for (var i = 1; i < rowCount; i++) {
//         var row = table.rows[i];
//         var taxz = $('#tax' + i).is(':checked');
//         if (document.getElementById('tax' + i).checked == false) {
//             jfalse = jfalse + 1;
//         }
//     }
//     if (matt == 0) {
//         matte = "Tanpa Materai";
//     } else {
//         matte = matt;
//     }
//     if (jfalse == 0) {
//         $kett = "Di Pakai Di Semua List";
//     } else {
//         $kett = "Ada <b>" + jfalse + "</b> Yang Tidak menggunakan Tax";
//     }
//     swal({
//             title: 'TAX',
//             html: $kett,
//             type: 'question',
//             showCancelButton: true,
//             confirmButtonClass: 'btn btn-success',
//             cancelButtonClass: 'btn btn-success',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Sesuai',
//             cancelButtonText: 'Belum'
//         }).then(function() {
//                 swal({
//                         //title: 'PENDAFTARAN',
//                         text: "Alasan Dibatalkan : ",
//                         type: 'info',
//                         input: 'text',
//                         showCancelButton: true,
//                         confirmButtonClass: 'btn btn-success',
//                         cancelButtonClass: 'btn btn-danger m-l-10',
//                         confirmButtonText: 'Ya, Batalkan',
//                         cancelButtonText: 'Tidak',
//                     }).then(function(alasan) {
//                             var table = document.getElementById('datatable');
//                             var rowCount = table.rows.length;
//                             for (i = 1; i < rowCount; i++) {
//                                 var kode = $("#kode" + i).val();
//                                 var taxz = $('#tax' + i).is(':checked');
//                                 if (taxz == true) {
//                                     var tax = 1;
//                                 } else {
//                                     var tax = 0;
//                                 }
//                             }
//                             // if (nomor == "" || total == "0.00" || total == "" || supp == null || gudang ==
//                             //     null) {
//                             //     swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
//                             // } else {
//                             //     $.ajax({
//                             //         // url: '<?php echo base_url() ?>Farmasi_bapb/ubahdata/' + id,
//                             //         url: '<?php echo base_url() ?>Farmasi_bapb/ubahdata/2',
//                             //         type: 'POST',
//                             //         dataType: "json",
//                             //         data: {
//                             //             alasan: alasan
//                             //         },
//                             //         success: function(data) {
//                             //             console.log(data);
//                             //             data1 = JSON.parse(data);
//                             //             console.log(data1)
//                             //             swal({
//                             //                 title: "BAPB",
//                             //                 html: "<p> No. Bukti   : <b>" + data1
//                             //                     .nomor +
//                             //                     "</b> </p>" +
//                             //                     "Tanggal :  " + tanggal + "<b> </p>" +
//                             //                     "Total: " +
//                             //                     total,
//                             //                 html: "cek",
//                             //                 type: "info",
//                             //                 confirmButtonText: "OK"
//                             //             }).then((value) => {
//                             //                 location.href =
//                             //                     "<?php echo base_url() ?>farmasi_bapb";
//                             //             })

//                             //         },
//                             //         error: function(data) {
//                             //             swal('BAPBP', 'Data gagal disimpan ...', '');
//                             //         }
//                             //     });
//                         }



function savex() {
    var matt = $('[name="materai"]').val();
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    var nomor = $('[name="noterima"]').val();

    var supp = $('[name="supp"]').val();
    var nomorpo = $('[name="nomorpo"]').val();
    var total = $('#_vtotal').text();
    var tanggal = $('[name="tanggal"]').val();

    var jfalse = 0;
    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];
        var taxz = $('#tax' + i).is(':checked');
        if (document.getElementById('tax' + i).checked == false) {
            jfalse = jfalse + 1;
        }
    }
    if (matt == 0) {
        matte = "Tanpa Materai";
    } else {
        matte = matt;
    }
    if (jfalse == 0) {
        $kett = "Di Pakai Di Semua List";
    } else {
        $kett = "Ada <b>" + jfalse + "</b> Yang Tidak menggunakan Tax";
    }
    swal({
        title: 'TAX',
        html: $kett,
        type: 'question',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-success',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sesuai',
        cancelButtonText: 'Belum'
    }).then(function() {
        swal({
            //title: 'PENDAFTARAN',
            text: "Alasan Dibatalkan : ",
            type: 'info',
            input: 'text',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
        }).then(function(alasan) {
            var table = document.getElementById('datatable');
            var rowCount = table.rows.length;
            for (i = 1; i < rowCount; i++) {
                var kode = $("#kode" + i).val();
                var taxz = $('#tax' + i).is(':checked');
                if (taxz == true) {
                    var tax = 1;
                } else {
                    var tax = 0;
                }
            }
            // if (nomor == "" || total == "0.00" || total == "" || supp == null || gudang == null) {
            //     swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
            // } else {

            $.ajax({
                // url: '<?php echo base_url()?>Farmasi_bapb/ubahdata/' + id,
                url: '<?php echo base_url()?>Farmasi_bapb/ubahdata/2',
                type: 'POST',
                dataType: "json",
                data: {
                    alasan: alasan
                },
                success: function(data) {
                    console.log(data);
                    // success: function(data) {
                    //     swal({
                    //         title: "BAPB",
                    //         // html: "<p> No. Bukti   : <b>" + data1.nomor +
                    //         //     "</b> </p>" +
                    //         //     "Tanggal :  " + tanggal + "<b> </p>" + "Total: " +
                    //         //     total,
                    //         html: "cek",
                    //         type: "info",
                    //         confirmButtonText: "OK"
                    //     }).then((value) => {
                    //         location.href = "<?php echo base_url()?>farmasi_bapb";
                    //     });

                    // },
                    //     error: function(data) {
                    //         swal('BAPBP', 'Data gagal disimpan ...', '');
                    //     }
                    // }
                }
            });
        });
    });
}

function save() {
    var supp = $('[name="supp"]').val();
    var nomorpo = $('[name="nomorpo"]').val();
    var gudang = $('[name="gudang"]').val();
    var tanggal = $('[name="tanggal"]').val();
    var nomor = $('[name="noterima"]').val();
    var total = $('#_vtotal').text();

    if (nomor == "" || total == "0.00" || total == "" || supp == null || gudang == null) {
        swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    } else {

        $.ajax({
            url: "<?php echo site_url('farmasi_bapb/ajax_add/2')?>",
            // url: "<?php echo site_url('farmasi_bapb/ubahdata/2')?>",
            data: $('#frmpembelian').serialize(),
            type: 'POST',
            success: function(data) {
                console.log(data);
                data1 = JSON.parse(data);
                console.log(data1);
                swal({
                    title: "BAPB",
                    html: "<p> No. Bukti   : <b>" + data1.nomor +
                        "</b> </p>" + "Tanggal :  " +
                        tanggal + " </b> </p>" + " " + "Total : " +
                        total + " </b> </p>",
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href =
                        "<?php echo base_url()?>farmasi_bapb";
                });
            },
            error: function(data) {
                swal('BAPB', 'Data gagal disimpan ...', '');
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

function separateComma(val) {
    var sign = 1;
    if (val < 0) {
        sign = -1;
        val = -val;
    }
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
    if (val.toString().includes('.')) {
        result = result + '.' + val.toString().split('.')[1];
    }
    return sign < 0 ? '-' + result : result;
}

function total() {
    var cekppn2 = $('#ppn2_').val() / 100;
    var tmateraix = $("#materai").val();

    var vtotal = $('#_vtotal').t
    ext();
    var xtotal = parseInt(vtotal.replaceAll(',', ''));
    if (xtotal >= '5000000') {
        $('#materai').val('10000').change();
    }
    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;

    for (var i = 1; i < rowCount; i++) {

        var jumlah = $("#qty" + i).val();
        var harga = $("#harga" + i).val();
        var diskon = $("#disc" + i).val();
        var diskonrp = $("#discrp" + i).val();
        var taxz = $("#tax" + i).val();
        var subtotal = $("#jumlah" + i).val();

        var jumlah1 = Number(parseInt(jumlah.replaceAll(',', '')));
        var harga1 = Number(parseInt(harga.replaceAll(',', '')));
        var diskon1 = Number(parseInt(diskon.replaceAll(',', '')));
        var diskon2 = Number(parseInt(diskonrp.replaceAll(',', '')));
        var tax1 = Number(taxz);
        var subtotal1 = Number(parseInt(subtotal.replaceAll(',', '')));

        tjumlah = tjumlah + eval(jumlah1 * harga1);

        diskon = eval((diskon1 / 100) * jumlah1 * harga1);

        tdiskon = tdiskon + diskon2;

        $.ajax({
            url: '<?php echo base_url();?>farmasi_bapb/cekppn',
            type: "GET",
            dataType: "json",
            success: function(data) {
                cekppn = data.prosentase;
                cekppn2 = cekppn / 100;
            }
        });
        if (document.getElementById('tax' + 1).value == 1) {
            tppn = tppn + (eval(subtotal1 * cekppn2));
        }

    }
    var tmaterai = Number(tmateraix);

    var abc = Number(tjumlah - tdiskon + tppn);
    if (tmaterai == 10000) {
        var tmattotal = abc + 10000 - 20000;
    } else {
        var tmattotal = abc;
    }

    //tppn = 0;
    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
    document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai);
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
    document.getElementById("_vppn").innerHTML = separateComma(tppn);
    document.getElementById("_vtotal").innerHTML = separateComma(tmattotal);

}

function totalline(id) {
    var cekppn2 = $('#ppn2_').val() / 100;
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;
    diskon = (row.cells[4].children[0].value / 100) * jumlah;

    tot = jumlah - diskon;


    if (document.getElementById('tax' + id).checked == true) {
        tot = tot * cekppn2;
    }

    row.cells[6].children[0].value = formatCurrency1(tot);
    total();



}

function getpo() {
    var xhttp;
    var str = $('[name=supp]').val();
    if (str == "") {

    } else {
        initailizeSelect2_farmasi_po(str);

    }
}

function getdatapo2(str) {
    var xhttp;
    //var str = $('[name=nomorpo]').val();
    $('#datatable tbody').empty();
    if (str == "") {

    } else {
        $.ajax({
            url: "<?php echo base_url();?>farmasi_bapb/getdatapo/?nopo=" + str,
            type: "GET",
            success: function(data) {
                initailizeSelect2_farmasi_barang();
                $('#datatable tbody').append(data);
                total();

            }
        });
    }
}


function getdatapo(str) {
    var xhttp;
    //var str = $('[name=kodepu]').val();
    if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=qty1]').val('');
        $('[id=sat1]').val('');
        $('[id=harga1]').val('');
        $('[id=disc1]').val('');
        totalline(1);
    } else {
        $.ajax({
            url: "<?php echo base_url();?>farmasi_bapb/getpo/" + str,
            type: "GET",
            dataType: "JSON",

            success: function(data) {
                for (i = 0; i <= data.length - 1; i++) {
                    hapus();
                }

                for (i = 0; i <= data.length - 1; i++) {
                    if (i > 0) {
                        tambah();
                    }

                    x = i + 1;

                    var option = $("<option selected></option>").val(data[i]
                        .kodebarang).text(data[i]
                        .namabarang);
                    $('#kode' + x).append(option).trigger('change');

                    if (data[i].vat == 1) {
                        document.getElementById("tax" + x).checked = true;
                    }
                    document.getElementById("qty" + x).value = data[i]
                        .qty_terima;
                    document.getElementById("sat" + x).value = data[i].satuan;
                    document.getElementById("harga" + x).value = data[i].price;
                    document.getElementById("disc" + x).value = data[i]
                        .discount;

                }

            }
        });
    }
}
</script>


</body>

</html>