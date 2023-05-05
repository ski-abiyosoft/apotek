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
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url(); ?><?= $url; ?>">
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
                                <?= $submodul; ?>
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
                                            <select id="supp" name="supp" class="form-control select2_el_vendor" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" onchange="getpo()">

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">AMBIL PO</label>
                                        <div class="col-md-1">
                                            <input type="checkbox" value="1" name="cekpo" id="cekpo" checked="checked" class="form-control" onchange="cekpo2();">
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
                                            <?php if ($this->session->userdata("user_level") != 1) {
                                                $cek = "readonly";
                                            } else {
                                                $cek = "";
                                            } ?>
                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" <?= $cek; ?> />

                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor PO#</label>
                                        <div id="nopo" class="col-md-9">
                                            <!-- <select id="nomorpo" name="nomorpo" class="form-control select2_el_farmasi_po2" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" onchange="getdatapo(this.value);gethpo(this.value);">
                                            </select> -->
                                            <select id="nomorpo" name="nomorpo" class="form-control select2_logpo" data-placeholder="Pilih Vendor Dahulu" onchange="getdatapo(this.value);gethpo(this.value);">
                                                <option value="">Pilih Vendor Dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tgl. Tukar</label>
                                        <div class="col-md-4">

                                            <input id="tanggaltukar" name="tanggaltukar" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang <font color="red">*</font></label>
                                        <div class="col-md-4">
                                            <select id="gudang" name="gudang" class="form-control select2_el_logistik_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event);" onchange="cekgudang(this.value)">
                                            </select>
                                        </div>

                                        <label class="col-md-2 control-label">BAPB No.</label>
                                        <div class="col-md-3">
                                            <input type="text" name="noterima" id="noterima" class="form-control" value="AUTO" readonly>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Materai</label>
                                        <div class="col-md-9">
                                            <select name="materai" id="materai" class="form-control select2me" onchange="cekmtr()">
                                                <option value="0">Tanpa Materai</option>
                                                <!-- <option value="3000">3000</option>
																<option value="6000">6000</option> -->
                                                <option value="10000">10000</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Ref</label>
                                        <div class="col-md-3">
                                            <input type="text" name="ref" id="ref" class="form-control" value="0" disabled>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kurs</label>
                                        <div class="col-md-4">
                                            <select name="kurs" id="kurs" class="form-control">
                                                <option value="IDR">IDR</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </div>
                                        <label class="col-md-2 control-label">Rate</label>
                                        <div class="col-md-3">
                                            <input type="text" name="rate" id="rate" class="form-control" value="0" readonly>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Faktur
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="nofaktur" id="nofaktur" class="form-control" value="">
                                        </div>
                                        <label class="col-md-2 control-label">SJ No.
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" placeholder="" name="nomorsj" id="nomorsj" value="">
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Diskon (%)</label>
                                        <div class="col-md-4">
                                            <input id="diskonpr" name="diskonpr" class="form-control" type="text" value="0" />
                                        </div>
                                        <label class="col-md-2 control-label">Rp</label>
                                        <div class="col-md-3">
                                            <input id="diskonrp" name="diskonrp" class="form-control" type="text" value="0" />
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pembayaran
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="pembayaran" name="pembayaran" id="pembayaran" class="form-control select2_el_pembayaran" data-placeholder="Pilih..." onchange="cekjt(this.value)">
                                            </select>
                                        </div>

                                    </div>
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ongkir</label>
                                        <div class="col-md-4">
                                            <input type="text" name="ongkir" id="ongkir" class="form-control" value="0">
                                        </div>
                                        <label class="col-md-2 control-label">Kemasan</label>
                                        <div class="col-md-3">
                                            <input type="text" name="kemasan" id="kemasan" class="form-control" value="0">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jatuh Tempo</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="jatuhtempo" id="jatuhtempo" value="" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" style="overflow: auto; white-space: nowrap; display: inline-block;">

                                        <thead class="page-breadcrumb breadcrumb">
                                            <th class="title-white" width="5%" style="text-align: center" id="kh">Delete</th>
                                            <th class="title-white" width="15%" style="text-align: center;">Nama Barang</th>
                                            <th class="title-white" width="10%" style="text-align: center">Qty</th>
                                            <th class="title-white" width="5%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="13%" style="text-align: center">Harga</th>
                                            <th class="title-white" width="5%" style="text-align: center">Disc %</th>
                                            <th class="title-white" width="13%" style="text-align: center">Disc Rp</th>
                                            <th class="title-white" style="text-align: center">Tax</th>
                                            <th class="title-white" style="text-align: center">Total Harga</th>
                                            <th class="title-white" style="text-align: center">Expire</th>
                                            <th class="title-white" style="text-align: center">PO No</th>
                                        </thead>

                                        <tbody id="datatable_body">
                                            <tr>
                                                <td id="kolom1">
                                                    <button type='button' onclick="hapusBarisIni(1)" class='btn red' id="btnhapus1"><i class='fa fa-trash-o'>
                                                </td>
                                                <td>
                                                    <select name="kode[]" id="kode1" class="select2_el_log_baranggud form-control input-largex" onchange="showbarangname(this.value, 1);"></select>
                                                </td>
                                                <td>
                                                    <input name="qty[]" onchange="totalline(1);total()" value="1" id="qty1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td>
                                                    <input name="sat[]" id="sat1" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly>
                                                </td>
                                                <td>
                                                    <input name="harga[]" onchange="totalline(1); total(); cekharga(1);" value="0" id="harga1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td>
                                                    <input name="disc[]" onchange="totalline(1);total();changedisc(1)" value="0" id="disc1" type="text" class="form-control rightJustified">
                                                </td>
                                                <td>
                                                    <input name="discrp[]" onchange="totalline(1);total(); changediscrp(1)" value="0" id="discrp1" type="text" class="form-control rightJustified ">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="tax[]" id="tax1" class="form-control" onchange="totalline(1);total(); cektax(1)">
                                                    <input type='hidden' name='tax_hide[]' id='tax_hide1' value='0' class='form-control'>
                                                </td>

                                                <td>
                                                    <input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" readonly>
                                                </td>
                                                <td>
                                                    <input name="expire[]" onchange="totalline(1);total()" value="<?= date('Y-m'); ?>" min="<?= date('Y-m'); ?>" id="expire1" type="month" class="form-control">
                                                </td>
                                                <td>
                                                    <input name="po[]" onchange="totalline(1);total()" value="" id="po1" type="text" class="form-control">
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <?php
                                    $pjkx = $this->db->get('tbl_pajak')->result();
                                    foreach ($pjkx as $pjk) {
                                        $pj = $pjk->prosentase;
                                    }
                                    ?>
                                    <input type="hidden" value="<?= $pj; ?>" name="vatrp" id="vatrp">

                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                                <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                            </div>
                                        </div>


                                    </div>


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


                                <!-- ceksave() -->
                                <button type="button" onclick="savex()" class="btn blue"><i class="fa fa-save"></i>
                                    <b>Simpan </b></button>

                                <div class="btn-group">
                                    <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru </b></button>
                                </div>

                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('logistik_bapb/') ?>"><i class="fa fa-undo"></i><b>
                                            KEMBALI </b></a>
                                </div>

                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
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
                                        <input type="hidden" id="ppn2_" name="ppn2_" value="<?= $ppn['prosentase']; ?>">
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




<div class="modal fade" id="modalharga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CEK ULANG HARGA</h4>
            </div>
            <div class="modal-body">
                <div class="datatable-modal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tidak Sesuai</button>
                <button type="button" onclick="save()" class="btn btn-primary">Sesuai</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('template/footer');
?>


<script>
    function cekgudang(v) {
        // console.log(v);
        initailizeSelect2_log_baranggud(v);
    }

    $(window).on("load", function() {
        initailizeSelect2_log_baranggud(null);
    });

    function cekmtr() {
        var tmateraix = $("#materai").val();
        var tmaterai = parseInt(tmateraix.replaceAll(',', ''));
        var ttotalx = $("#_vtotal").text();
        var ttotal = parseInt(ttotalx.replaceAll(',', ''));
        document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai);
        if (ttotalx != '') {
            if (tmaterai == '10000') {
                var totnew = Number(tmaterai) + Number(ttotal);
                document.getElementById("_vtotal").innerHTML = separateComma(totnew);
            } else {
                var totnew = Number(ttotal) - 10000;
                document.getElementById("_vtotal").innerHTML = separateComma(totnew);
            }
        } else {
            document.getElementById("_vtotal").innerHTML = separateComma(tmaterai);
        }
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

    function cekharga(id) {
        var harga = document.getElementById('harga' + id + '').value;
        var hargax = parseInt(harga.replaceAll(',', ''));
        var qty = document.getElementById('qty' + id + '').value;
        var kode = document.getElementById('kode' + id + '').value;
        var jumlah = harga * qty;
        totalline(id);
        $.ajax({
            url: "<?php echo base_url(); ?>Logistik_bapb/cekharga/?kode=" + kode + "&harga=" + hargax,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                var vtotal = $('#_vtotal').text();
                var total = parseInt(vtotal.replaceAll(',', ''));
                // if (total >= '5000000') {
                //     $('#materai').val("10000").change();
                // }
                if (data.status == 1) {
                    swal({
                        title: "HARGA BARANG",
                        html: "Harga tidak boleh lebih kecil dari harga sebelumnya",
                        type: "info",
                        confirmButtonText: "OK"
                    });
                    tqty = 0;
                    tjumlah = 0;
                    tdiskon = 0;
                    var sub_total = 0;
                    var diskonnet = 0;
                    var jumlah_new = data.harga * qty;
                    var table = document.getElementById('datatable');
                    var rowCount = table.rows.length;
                    // let abc = data.harga;
                    $('#harga' + id + '').val(separateComma(data.harga));
                    for (var i = 1; i < rowCount; i++) {
                        var row = table.rows[i];
                        qtyc = row.cells[2].children[0].value;
                        hargac = row.cells[4].children[0].value;
                        jumlahc = row.cells[8].children[0].value;
                        diskonc = row.cells[5].children[0].value;
                        diskonrpc = row.cells[6].children[0].value;
                        var jumlahb = Number(jumlahc.replace(/[^0-9\.]+/g, ""));
                        var hargab = Number(hargac.replace(/[^0-9\.]+/g, ""));
                        var diskonb = Number(diskonc.replace(/[^0-9\.]+/g, ""));
                        var harga_satuan = hargac;
                        sub_total += Number(parseInt(harga_satuan.replaceAll(',', '')));
                        diskonnet += Number(parseInt(diskonrpc.replaceAll(',', '')));
                    }
                    var dsc = data.harga * diskonc / 100;
                    $('#jumlah' + id).val(separateComma(jumlah_new));
                    $('#discrp' + id).val(dsc);
                    document.getElementById("_vdiskon").innerHTML = separateComma(diskonnet.toFixed(
                        0));
                    document.getElementById("_vsubtotal").innerHTML = separateComma(sub_total
                        .toFixed(0));
                    document.getElementById("_vtotal").innerHTML = separateComma(sub_total.toFixed(
                        0));
                } else if (data.status == 2) {
                    tqty = 0;
                    tjumlah = 0;
                    tdiskon = 0;
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

                        // qtyc               = row.cells[2].children[0].value;
                        // hargac             = row.cells[4].children[0].value;
                        // jumlahc            = row.cells[8].children[0].value;
                        // diskonc            = row.cells[5].children[0].value;
                        // diskonrpc          = row.cells[6].children[0].value;
                        var jumlahb = Number(jumlahc.replace(/[^0-9\.]+/g, ""));
                        var hargab = Number(hargac.replace(/[^0-9\.]+/g, ""));
                        var diskonb = Number(diskonc.replace(/[^0-9\.]+/g, ""));
                        var harga_satuan = hargac;
                        sub_total += Number(parseInt(harga_satuan.replaceAll(',', '')));
                        diskonnet += Number(parseInt(diskonrpc.replaceAll(',', '')));
                    }
                    var dsc = (hargac * diskonc) / 100;

                    $('#harga' + id).val(separateComma(harga));
                    $('#jumlah' + id).val(separateComma(jumlah_new2));
                }
            }
        });
    }

    var idrow = 2;
    var rowCount;
    var arr = [1];

    function tambah() {

        var gud = $("#gudang").val();

        var table = document.getElementById('datatable');
        rowCount = table.rows.length;
        arr.push(idrow);

        var x = document.getElementById('datatable').insertRow(rowCount);
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
        var td11 = x.insertCell(10);

        td1.innerHTML = "<td id='kolom" + idrow + "'><button type='button' onclick=hapusBarisIni(" + idrow + ") id=btnhapus" + idrow + " class='btn red'><i class='fa fa-trash-o'></i></button></td>";
        td2.innerHTML = "<select name='kode[]'  id=kode" + idrow + " class='select2_el_log_baranggud form-control' onchange='showbarangname(this.value," + idrow + ")' >";
        td3.innerHTML = "<input name='qty[]'  id=qty" + idrow + " onchange='totalline(" + idrow + "); changeqty(" + idrow + ")' value=1  type='text' class='form-control rightJustified'>";
        td4.innerHTML = "<input name='sat[]' id=sat" + idrow + " type='text' class='form-control' readonly>";
        td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ");cekharga(" + idrow + ");' value='0'  type='text' class='form-control rightJustified'> ";
        td6.innerHTML = "<input name='disc[]' id=disc" + idrow + " onchange='totalline(" + idrow + ");total();changedisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'>";
        td7.innerHTML = "<input name='discrp[]' id=discrp" + idrow + " onchange='totalline(" + idrow + ");changediscrp(" + idrow + ");' value='0'  type='text' class='form-control rightJustified'>";
        td8.innerHTML = "<input type='checkbox' name='tax[]' id='tax" + idrow + "' onchange='totalline(" + idrow + ");total(); cektax(" + idrow + ")' class='form-control'><input type='hidden' name='tax_hide[]' id='tax_hide" + idrow + "' value='0' class='form-control'>";
        td9.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";
        td10.innerHTML = "<input name='expire[]'  id=expire" + idrow + " onchange='totalline(" + idrow + ")' value='<?= date('Y-m'); ?>' min='<?= date('Y-m'); ?>'  type='month' class='form-control'>";
        td11.innerHTML = "<input name='po[]'  id=po" + idrow + " onchange='totalline(" + idrow + ") value=''  type='text' class='form-control'>";
        initailizeSelect2_log_baranggud(gud);
        idrow++;
        var vtotal = $('#_vtotal').text();
        var total = parseInt(vtotal.replaceAll(',', ''));
    }

    $(".select2_logpo").select2();

    function cek_tax(id) {
        var table = document.getElementById('datatable');
        var row = table.rows[arr.indexOf(id) + 1];
        var cekk = row.cells[5].children[0].value;
        var vtotal = $('#_vtotal').text();
        var total = parseInt(vtotal.replaceAll(',', ''));
    }

    function cektax(id) {
        if (document.getElementById('tax' + id).checked == true) {
            $('#tax_hide' + id).val(1);
        } else {
            $('#tax_hide' + id).val(0);
        }
        var cek = $("#tax_hide").val();
    }

    function cekjt(str) {

        var strr = str;
        var tglter = document.getElementById('tanggal').value;

        $.ajax({
            url: "<?php echo base_url(); ?>logistik_bapb/cekhari/?kode=" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                inputHari = data.valuerp;
                var hariKedepan = new Date(new Date(tglter).getTime() + (inputHari * 24 * 60 * 60 * 1000));
                document.getElementById('jatuhtempo').value = hariKedepan.toISOString().slice(0, 10);
                var vtotal = $('#_vtotal').text();
                var total = parseInt(vtotal.replaceAll(',', ''));
                // if (total >= '5000000') {
                //     $('#materai').val("10000").change();
                // }
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
                '<select id="nomorpo" name="nomorpo" class="form-control select2_logpo" data-placeholder="Pilih Vendor Dahulu" onkeypress="return tabE(this,event)" onchange="getdatapo(this.value);gethpo(this.value);"><option value="">Pilih Vendor Dahulu</option></select>'
            );
            $(".select2_logpo").select2();

        }
        var vtotal = $('#_vtotal').text();
        var total = parseInt(vtotal.replaceAll(',', ''));
        // if (total >= '5000000') {
        //     $('#materai').val("10000").change();
        // }


    }


    function showbarangname(str, id) {

        var xhttp;
        var vid = id;
        $.ajax({
            // url: "<?php echo base_url(); ?>logistik_bapb/getinfobarang/?kode=" + str,
            url: "<?php echo base_url(); ?>logistik_bapb/getinfobarang2/" + str,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                // console.log(data);
                $('#sat' + vid).val(data.satuan1);
                var harga = Number(parseInt(data.hargabeli.replaceAll(',', '')));
                $('#harga' + vid).val(separateComma(harga.toFixed(0)));
                totalline(vid);
                var vtotal = $('#_vtotal').text();
                var total = parseInt(vtotal.replaceAll(',', ''));
                // if (total >= '5000000') {
                //     $('#materai').val("10000").change();
                // }
            }
        });
    }

    function ceksave() {
        swal({
            title: 'CEK PERUBAHAN HARGA',
            html: 'Yakin ingin merubah harga ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-success',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function() {
            $('#modalharga').modal('show');
            const table1 = $('#datatable');
            const table2 = $('.datatable-modal');
            const row_items = table1[0].cloneNode(true);
            table2[0].appendChild(row_items);
            var table = document.getElementById('datatable');
            rowCount = table.rows.length;
            arr.push(idrow);
            var id = idrow - 1;
            if ($('.datatable-modal #datatable').length <= 1) {
                for (i = 1; i <= id; i++) {
                    $(".datatable-modal #btnhapus" + i).attr('disabled', true);
                    $(".datatable-modal #kode" + i).attr('disabled', true);
                    $(".datatable-modal #qty" + i).attr('readonly', true);
                    $(".datatable-modal #sat" + i).attr('readonly', true);
                    $(".datatable-modal #harga" + i).attr('readonly', true);
                    $(".datatable-modal #disc" + i).attr('readonly', true);
                    $(".datatable-modal #discrp" + i).attr('readonly', true);
                    $(".datatable-modal #tax" + i).attr('disabled', true);
                    $(".datatable-modal #jumlah" + i).attr('readonly', true);
                    $(".datatable-modal #expire" + i).attr('readonly', true);
                    $(".datatable-modal #po" + i).attr('readonly', true);
                }
            } else if ($('.datatable-modal #datatable').length > 1) {
                var dm = $(".datatable-modal #datatable");
                dm[0].remove();
                for (i = 1; i <= id; i++) {
                    $(".datatable-modal #btnhapus" + i).attr('disabled', true);
                    $(".datatable-modal #kode" + i).attr('disabled', true);
                    $(".datatable-modal #qty" + i).attr('readonly', true);
                    $(".datatable-modal #sat" + i).attr('readonly', true);
                    $(".datatable-modal #harga" + i).attr('readonly', true);
                    $(".datatable-modal #disc" + i).attr('readonly', true);
                    $(".datatable-modal #discrp" + i).attr('readonly', true);
                    $(".datatable-modal #tax" + i).attr('disabled', true);
                    $(".datatable-modal #jumlah" + i).attr('readonly', true);
                    $(".datatable-modal #expire" + i).attr('readonly', true);
                    $(".datatable-modal #po" + i).attr('readonly', true);
                }
            }
        }, function(dismiss) {
            save();
        });
    }

    function savex() {
        $('#modalharga').modal('hide');
        var supp        = $('[name="supp"]').val();
        var matt        = $('[name="materai"]').val();
        var nomorpo     = $('[name="nomorpo"]').val();
        var gudang      = $('[name="gudang"]').val();
        var tanggal     = $('[name="tanggal"]').val();
        var nomor       = $('[name="nomorbukti"]').val();
        var pemb        = $('[name="pembayaran"]').val();
        var fakt        = $('[name="nofaktur"]').val();
        var sjj         = $('[name="nomorsj"]').val();
        var total       = $('#_vtotal').text();
        var ppn_123     = $('#_vppn').text();
        var table       = document.getElementById('datatable');
        var rowCount    = table.rows.length;
        var jfalse      = 0;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            var tax = row.cells[7].children[1].value;
            if (tax == 0) {
                jfalse += 1;
            }
            var expire    = $("#expire" + i).val(); 
            if (expire == '' || expire == null) {
                swal({
                title: "Expired Date",
                html: "<p>HARUS DI isi</p>",
                type: "error",
                confirmButtonText: "OK"
                });
                return;
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
            title               : 'TAX',
            html                : $kett,
            type                : 'question',
            showCancelButton    : true,
            confirmButtonClass  : 'btn btn-success',
            cancelButtonClass   : 'btn btn-success',
            cancelButtonColor   : '#d33',
            confirmButtonText   : 'Sesuai',
            cancelButtonText    : 'Belum'
        }).then(function() {
            swal({
                title               : 'MATERAI',
                html                : "Apakah Pilihan Materai Sudah Sesuai ? <strong><p><br>" + matte,
                type                : 'question',
                showCancelButton    : true,
                confirmButtonClass  : 'btn btn-success',
                cancelButtonClass   : 'btn btn-success',
                cancelButtonColor   : '#d33',
                confirmButtonText   : 'Sesuai',
                cancelButtonText    : 'Belum'
            }).then(function() {
                swal({
                    title               : 'UBAH HARGA',
                    html                : "Apakah Yakin Ingin Merubah Harga ?",
                    type                : 'question',
                    showCancelButton    : true,
                    confirmButtonClass  : 'btn btn-success',
                    cancelButtonClass   : 'btn btn-success',
                    cancelButtonColor   : '#d33',
                    confirmButtonText   : 'Ya',
                    cancelButtonText    : 'Tidak'
                }).then(function() {
                    if (gudang == '' || gudang == null) {
                        swal({
                            title   : "GUDANG",
                            html    : "<p>HARUS DI ISI !</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (supp == '' || supp == null) {
                        swal({
                            title   : "Nama Vendor",
                            html    : "<p>HARUS DI PILIH !</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (pemb == '' || pemb == null) {
                        swal({
                            title   : "Pembayaran",
                            html    : "<p>HARUS DI PILIH !</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (matt == '' || matt == null) {
                        swal({
                            title   : "Materai",
                            html    : "<p>HARUS DI PILIH</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (sjj == '' || sjj == null) {
                        swal({
                            title   : "No. Surat Jalan",
                            html    : "<p>HARUS DI isi</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (fakt == '' || fakt == null) {
                        swal({
                            title   : "FAKTUR",
                            html    : "<p>HARUS DI ISI</p>",
                            type    : "error",
                            confirmButtonText   : "OK"
                        });
                        return;
                    }
                    if (nomor == "" || total == "0.00" || total == "" || supp == null || nomorpo == null || gudang ==
                        null) {
                        swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
                    } else {
                        save_x();
                    }
                }, function(dismiss) {
                    if (nomor == "" || total == "0.00" || total == "" || supp == null || nomorpo == null || gudang ==
                        null) {
                        swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
                    } else {
                        save_x();
                    }
                });
            });
        });
    }

    function save_x() {
        $('#modalharga').modal('hide');
        var supp        = $('[name="supp"]').val();
        var matt        = $('[name="materai"]').val();
        var nomorpo     = $('[name="nomorpo"]').val();
        var gudang      = $('[name="gudang"]').val();
        var tanggal     = $('[name="tanggal"]').val();
        var nomor       = $('[name="nomorbukti"]').val();
        var pemb        = $('[name="pembayaran"]').val();
        var fakt        = $('[name="nofaktur"]').val();
        var sjj         = $('[name="nomorsj"]').val();
        var total       = $('#_vtotal').text();
        var ppn_123     = $('#_vppn').text();
        var table       = document.getElementById('datatable');
        var rowCount    = table.rows.length;
        var jfalse      = 0;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            var tax = row.cells[7].children[1].value;
            if (tax == 0) {
                jfalse += 1;
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
        $.ajax({
            url   : '<?= site_url() ?>Logistik_bapb/save_one',
            data  : $('#frmpembelian').serialize(),
            type  : 'POST',
            dataType    : 'JSON',
            success: function(data) {
                if (data.status == '1') {
                    swal({
                        title   : "BAPB",
                        html    : "<p> Nomor Faktur : <b>" + data.nomor + "</b> <br> GANDA </p>",
                        type    : "error",
                        confirmButtonText   : "OK"
                    });
                } else if (data.status == 2) {
                    //rincian
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    var pj = parseInt($("#vatrp").val()) / 100;
                    totvatrp = 0;
                    diskontotal = 0;
                    for (i = 1; i < rowCount; i++) {
                        var row       = table.rows[i];
                        var kode      = row.cells[1].children[0].value;
                        var qtyx      = row.cells[2].children[0].value;
                        var qty       = Number(qtyx.replace(/[^0-9\.]+/g, ""));
                        var sat       = row.cells[3].children[0].value;
                        var hargax    = row.cells[4].children[0].value;
                        var harga     = Number(hargax.replace(/[^0-9\.]+/g, ""));
                        var discx     = row.cells[5].children[0].value;
                        var disc      = Number(discx.replace(/[^0-9\.]+/g, ""));
                        var discrpx   = row.cells[6].children[0].value;
                        var discrp    = Number(discrpx.replace(/[^0-9\.]+/g, ""));
                        var vat       = row.cells[7].children[1].value;
                        var jumlahx   = row.cells[8].children[0].value;
                        var jumlah    = Number(jumlahx.replace(/[^0-9\.]+/g, ""));
                        var expire    = row.cells[9].children[0].value;
                        var po        = row.cells[10].children[0].value;
                        if (vat == 1) {
                            if (matt == 0) {
                                var vatrp = jumlah * pj;
                            } else {
                                var vatrp = Number((jumlah * pj) + matt);
                            }
                        } else {
                            var vatrp = 0;
                        }
                        // console.log('kode : '+kode+', qty : '+qty+', sat : '+sat+', harga : '+harga+', disc : '+disc+', discrp : '+discrp+', vat : '+vat+', jumlah : '+jumlah+', expire : '+expire+', po : '+po+', pajak : '+pj+', vatrp : '+vatrp);
                        $.ajax({
                            url: '<?= site_url() ?>Logistik_bapb/save_multi/?kode=' + kode + '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&disc=' + disc + '&discrp=' + discrp + '&vat=' + vat + '&jumlah=' + jumlah + '&expire=' + expire + '&po=' + po + '&vatrp=' + vatrp,
                            data: $('#frmpembelian').serialize(),
                            type: 'POST',
                            dataType: 'JSON',
                        });
                        totvatrp += vatrp;
                        diskontotal += discrp;
                    }
                    $.ajax({
                        url: '<?= site_url() ?>Logistik_bapb/save_one_u/?totvatrp=' + totvatrp + '&totaltagihan=' + Number(parseInt(total.replaceAll(',', ''))) + '&ppnrp=' + Number(parseInt(ppn_123.replaceAll(',', ''))) + '&diskontotal=' + diskontotal,
                        data: $('#frmpembelian').serialize(),
                        type: 'POST',
                        dataType: 'JSON',
                    });
                    swal({
                        title: "BAPB",
                        html: "<p> No. Bukti : <b>" + data.nomor + "</b></p>" + "Tanggal : " + tanggal + "<br> Total : " + total,
                        type: "success",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>Logistik_bapb";
                    });
                }
            }
        });
    }


    function save() {
        $('#modalharga').modal('hide');
        var supp = $('[name="supp"]').val();
        var matt = $('[name="materai"]').val();
        var nomorpo = $('[name="nomorpo"]').val();
        var gudang = $('[name="gudang"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var nomor = $('[name="nomorbukti"]').val();
        var pemb = $('[name="pembayaran"]').val();
        var fakt = $('[name="nofaktur"]').val();
        var sjj = $('[name="nomorsj"]').val();
        var total = $('#_vtotal').text();
        var ppn = $('#_vppn').text();
        // var subtotal      = $('#_vsubtotal').text();
        // var diskon      = $('#_vdiskon').text();
        // var taxx       = $('#tax1').is(':checked');

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;
        var jfalse = 0;


        for (var i = 1; i < rowCount; i++) {

            var row = table.rows[i];
            var taxx = $('#tax' + i).is(':checked');

            if (document.getElementById('tax' + i).checked == false) {
                jfalse = jfalse + 1;
            }


        }

        if (matt == 0) {
            matte = "Tanpa Materai";
        } else {
            matte = matt;
        }

        // if(taxx==true){ 
        // 	taxxe="PAKAI";
        // }else{
        // 	taxxe="TIDAK PAKAI";
        // }

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
                title: 'MATERAI',
                html: "Apakah Pilihan Materai Sudah Sesuai ? <strong><p><br>" + matte,
                type: 'question',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-success',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sesuai',
                cancelButtonText: 'Belum'
            }).then(function() {
                if (gudang == '' || gudang == null) {
                    swal({
                        title: "GUDANG",
                        html: "<p>HARUS DI ISI !</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (supp == '' || supp == null) {
                    swal({
                        title: "Nama Vendor",
                        html: "<p>HARUS DI PILIH !</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (pemb == '' || pemb == null) {
                    swal({
                        title: "Pembayaran",
                        html: "<p>HARUS DI PILIH !</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (matt == '' || matt == null) {
                    swal({
                        title: "Materai",
                        html: "<p>HARUS DI PILIH</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (sjj == '' || sjj == null) {
                    swal({
                        title: "No. Surat Jalan",
                        html: "<p>HARUS DI isi</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (fakt == '' || fakt == null) {
                    swal({
                        title: "FAKTUR",
                        html: "<p>HARUS DI ISI</p>",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                if (nomor == "" || total == "0.00" || total == "" || supp == null || nomorpo == null ||
                    gudang == null) {
                    swal('BAPB', 'Data Belum Lengkap/Belum ada transaksi ...', '');
                } else {
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    arr.push(idrow);
                    swal({
                        title: 'UBAH HARGA',
                        text: "Yakin ingin merubah harga ?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                    }).then((value) => {

                        for (i = 1; i < rowCount; i++) {
                            var row = table.rows[i];
                            var hargax = $('#harga' + i).val();
                            var harga = parseInt(hargax.replaceAll(',', ''));
                            var kode = $('#kode' + i).val();
                            var qty = $('#qty' + i).val();
                            // var sat = $('#sat'+i).val();
                            // var disc = $('#disc'+i).val();
                            // var po = $('#po'+i).val();
                            // var expire = $('#expire'+i).val();
                            // var discrp = $('#discrp'+i).val();
                            // var jumlah = $('#jumlah'+i).val();
                            // var taxx = $('#tax'+i+':checked').val();
                            // var taxx = $('#tax'+i).val();
                            // if(taxx == 0){
                            // 	tax = 1;
                            // } else {
                            // 	tax = 0;
                            // }
                            // console.log(kode);
                            // console.log(qty);
                            // console.log(sat);
                            // console.log(harga);
                            // console.log(disc);
                            // console.log(Number(parseInt(discrp.replaceAll(',',''))));
                            // console.log(tax);
                            // console.log(Number(parseInt(jumlah.replaceAll(',',''))));
                            // console.log(expire);
                            // console.log(po);
                            // console.log(Number(parseInt(subtotal.replaceAll(',',''))));
                            // console.log(Number(parseInt(diskon.replaceAll(',',''))));
                            // console.log(Number(parseInt(ppn.replaceAll(',',''))));
                            // console.log(Number(parseInt(total.replaceAll(',',''))));
                            $.ajax({
                                // url:"<?php echo site_url('logistik_bapb/save/1?harga=') ?>"+harga+"&kode="+kode+"&qty="+qty+"&sat="+sat+"&disc="+disc+"&po="+po+"&expire="+expire+"&tax="+tax+"&total="+total,
                                url: "<?php echo site_url('logistik_bapb/ajax_add/1?harga=') ?>" +
                                    harga + "&kode=" + kode,
                                data: $('#frmpembelian').serialize(),
                                type: 'POST',
                                dataType: "json",
                                success: function(data) {
                                    // console.log(data);
                                    // data1 = JSON.parse(data);
                                    // alert(data.nomor);
                                    swal({
                                        title: "BAPB",
                                        html: "<p> No. Bukti   : <b>" + data
                                            .nomor + "</b> </p>" +
                                            "Tanggal :  " + tanggal +
                                            '<br>Total : ' + total,
                                        type: "success",
                                        confirmButtonText: "OK"
                                    }).then((value) => {
                                        location.href =
                                            "<?php echo base_url() ?>logistik_bapb";
                                    });
                                    if (data1.status == '1') {
                                        swal({
                                            title: "BAPB",
                                            html: "<p> Nomor Faktur : <b>" +
                                                fakt + "</b> <br> GANDA </p>",
                                            type: "error",
                                            confirmButtonText: "OK"
                                        });
                                    } else {
                                        swal({
                                            title: "BAPB",
                                            html: "<p> No. Bukti   : <b>" +
                                                data1.nomor + "</b> </p>" +
                                                "Tanggal :  " + tanggal,
                                            type: "success",
                                            confirmButtonText: "OK"
                                        }).then((value) => {
                                            location.href =
                                                "<?php echo base_url() ?>logistik_bapb";
                                        });
                                    }
                                },
                                error: function(data) {
                                    swal('BAPB', 'Data gagal disimpan ...', '');
                                }
                            });
                        }
                    });
                }
            });
        });
    }

    function hapus() {
        if (idrow > 2) {
            var x = document.getElementById('datatable').deleteRow(idrow - 1);
            idrow--;
            total();
        }
        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));
        // if (xtotal >= '5000000') {
        //     $('#materai').val("10000").change();
        // }
    }

    function hapusBarisIni(param) {
        var x = document.getElementById('datatable').deleteRow(arr.indexOf(param) + 1);
        arr.splice(arr.indexOf(param), 1);
        rowCount--;
        total();
        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));
    }

    function total() {
        var tmateraix = $("#materai").val();

        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));
        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        tdiskon = 0;
        tppn = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[5].children[0].value;
            diskonrp = row.cells[6].children[0].value;
            subtotal = row.cells[8].children[0].value;

            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            var diskon2 = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
            var subtotal1 = Number(subtotal.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1 * harga1);

            diskon = eval((diskon1 / 100) * jumlah1 * harga1);

            tdiskon = tdiskon + diskon2;

            var cekppnx = $('#ppn2_').val();
            var cekppn = Number(cekppnx);
            var cekppn2 = cekppn / 100;

            var tax_hide = row.cells[7].children[1].value;
            if (document.getElementById('tax' + arr[i - 1]).checked == true) {
                tppn += (subtotal1 * cekppn2);
            }

        }
        var tmaterai = Number(tmateraix);

        var abc = Number(tjumlah - tdiskon + tppn);
        if (tmaterai == 10000) {
            var tmattotal = abc + tmaterai;
        } else {
            var tmattotal = abc;
        }

        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
        document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai.toFixed(0));
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
        document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
        document.getElementById("_vtotal").innerHTML = separateComma(tmattotal.toFixed(0));
    }

    function changedisc(id) {
        var harga = $('#harga' + id).val();
        var qty = $('#qty' + id).val();
        if ($('#disc' + id).val() == 0) {
            $('#discrp' + id).val(0);
            totalline(id);
        }
    }

    function changediscrp(id) {
        var harga = $('#harga' + id).val();
        var qty = $('#qty' + id).val();
        $('#disc' + id).val(0);
        totalline(id);
    }

    // function cekrp(id) {
    //     $('#discrp' + id + '').attr('readonly', true);
    //     tppn = 0;
    //     // arr.indexOf(idrow)
    //     var table = document.getElementById('datatable');
    //     var row = table.rows[arr.indexOf(id) + 1];
    //     jumlah = row.cells[2].children[0].value * harga;
    //     diskon = row.cells[5].children[0].value;

    //     if (diskon > 0) {
    //         diskon = (row.cells[5].children[0].value / 100) * jumlah;
    //         // alert('aaa');

    //     } else {

    //         diskon = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
    //         // alert('bbb');    
    //     }
    //     // alert(diskon);    

    //     row.cells[6].children[0].value = separateComma(diskon.toFixed(0));
    //     total();
    //     totalline(id);
    //     //    }
    //     var vtotal = $('#_vtotal').text();
    //     var xtotal = parseInt(vtotal.replaceAll(',', ''));
    //     // if (xtotal >= '5000000') {
    //     //     $('#materai').val("10000").change();
    //     // }

    // }

    function totalline(id) {
        var table = document.getElementById('datatable');
        var row = table.rows[arr.indexOf(id) + 1];
        var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
        var diskonrp = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
        jumlah = row.cells[2].children[0].value * harga;
        diskon = (row.cells[5].children[0].value / 100) * jumlah;
        jumlahz = jumlah * harga - diskon;
        var tax_hide = row.cells[7].children[1].value;

        if (eval(diskon) > 0) {
            diskon = ($("#disc" + id).val() / 100) * jumlah;
            row.cells[6].children[0].value = formatCurrency1(diskon);
            tot = jumlah - diskon;
        } else {
            var diskon = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
            tot = jumlah - diskon;
        }

        row.cells[6].children[0].value = formatCurrency1(diskon);
        row.cells[8].children[0].value = formatCurrency1(tot);

        total();

        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));
    }

    function getpo() {

        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));

        var xhttp;
        var str = $('[name=supp]').val();
        if (str == "") {

        } else {
            $.ajax({
                url: "<?= site_url('Logistik_bapb/getnomorpox/'); ?>" + str,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    var opt = data;
                    var nomorpox = $("#nomorpo");
                    nomorpox.empty();
                    $("#nomorpo").append("<option selected value=''>-</option>");
                    $(opt).each(function() {
                        var option = $("<option/>");
                        option.val(this.id);
                        option.html(this.text);
                        nomorpox.append(option);
                    });
                }
            });
            // initailizeSelect2_farmasi_po(str);
            // initailizeSelect2_farmasi_po2(str);

        }
    }

    function getdatapo2(str) {
        var vtotal = $('#_vtotal').text();
        var xtotal = parseInt(vtotal.replaceAll(',', ''));

        var xhttp;
        //var str = $('[name=nomorpo]').val();
        $('#datatable tbody').empty();
        //   alert('aaa');
        if (str == "") {

        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>logistik_bapb/getdatapo/?nopo=" + str,
                type: "GET",
                success: function(data) {
                    initailizeSelect2_farmasi_barang();
                    $('#datatable tbody').append(data);
                    total();

                }
            });
        }
    }


    function gethpo(str) {
        var xhttp;
        if (str == "") {
            $('[id=gudang]').val('');
            $('[id=ref]').val('');
            $('[id=kurs]').val('');
            $('[id=rate]').val('');
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>logistik_bapb/gethpo/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    var selectElement = document.getElementById('gudang');
                    var opt = document.createElement('option');
                    opt.value = data.gudang;
                    opt.innerHTML = data.gud_name;
                    selectElement.removeChild(selectElement.lastChild);
                    selectElement.appendChild(opt);

                    $('#ref').val(data.ref_no);
                    $('#kurs').val(data.kurs);
                    $('#rate').val(data.kursrate);


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
            var vtotal = $('#_vtotal').text();
            var xtotal = parseInt(vtotal.replaceAll(',', ''));
            // if (xtotal >= '5000000') {
            //     $('#materai').val("10000").change();
            // }
            totalline(1);
        } else {
            var vtotal = $('#_vtotal').text();
            var xtotal = parseInt(vtotal.replaceAll(',', ''));
            // if (xtotal >= '5000000') {
            //     $('#materai').val("10000").change();
            // }
            $.ajax({
                url: "<?php echo base_url(); ?>logistik_bapb/getpo/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    // console.log(data)
                    for (i = 0; i <= data.length - 1; i++) {
                        hapus();
                    }

                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah();
                        }

                        x = i + 1;

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');

                        if (data[i].vat == 1) {
                            document.getElementById("tax" + x).checked = true;
                        }
                        document.getElementById("qty" + x).value = data[i].qty_po
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = data[i].price_po;
                        document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("po" + x).value = str;;
                        document.getElementById("disc" + x).value = 0;

                    }

                }
            });
        }
    }
</script>


</body>

</html>