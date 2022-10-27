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
            <span class="title-web">Farmasi <small>Retur Pembelian</small>
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
                <a class="title-white" href="<?php echo base_url(); ?>pembelian_retur">
                    Daftar Retur Pembelian
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Entri Retur
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
        <!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->
    </div>
    <div class="portlet-body form">
        <form id="frmpembelian" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Retur
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Info
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pemasok</label>
                                        <div class="col-md-6">
                                            <select id="supp" name="supp" class="form-control select2_el_vendor" onchange="showpo()">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Retur #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?php echo $nomor; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. BAPB</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select name="kodepu" id="kodepu" class="form-control input-medium select2me" onchange="getpoheader();getpo()">
                                                </select>
                                                <!-- <span class="input-group-btn">
																<a class="btn-sm btn green" onclick="getpoheader();getpo()"><i class="fa fa-refresh"></i></a>
																</span>	 -->

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal</label>
                                        <div class="col-md-4">
                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" id="gudang" name="gudang" readonly>
                                            <input class="form-control" type="hidden" id="gudang1" name="gudang1">

                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <!-- <tr>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 5%;">Delete</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 5%;">Nama Barang</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 5%;">Kuantitas</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 10%;">Satuan</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 10%;">Harga</th>
                                                <th class="title-white" width="10%"
                                                    style="text-align: center; width: 5%;">Tax</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 5%;"></th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 5%;">Diskon %</th>
                                                <th class="title-white" width="5%"
                                                    style="text-align: center; width: 20%;">Disc Rp</th>
                                                <th class="title-white" width="15%"
                                                    style="text-align: center; width: 10%;">Total</th>
                                            </tr> -->
                                            <tr>
                                                <th style="color:white; text-align:center">Hapus</th>
                                                <th style="color:white; text-align:center">Nama Barang</th>
                                                <th style="color:white; text-align:center">Kuantitas</th>
                                                <th style="color:white; text-align:center">Satuan</th>
                                                <th style="color:white; text-align:center">Harga</th>
                                                <th style="color:white; text-align:center">Tax</th>
                                                <th style="color:white; text-align:center"></th>
                                                <th style="color:white; text-align:center">Diskon</th>
                                                <th style="color:white; text-align:center">Disc Rp</th>
                                                <th style="color:white; text-align:center">Total</th>
                                            </tr>

                                            <thead>

                                            <tbody>
                                                <tr id="retur_no1">

                                                    <td>
                                                        <button id="btnhapus1" type='button' onclick=hapusBarisIni(1) class='btn red' disabled><i class='fa fa-trash-o'>
                                                    </td>
                                                    <td width="20%">
                                                        <select name="kode[]" id="kode1" class="select2_el_farmasi_barang form-control" onchange="showbarangname(this.value, 1)" readonly>
                                                            <option value="">--- Pilih Barang ---</option>
                                                        </select>
                                                        <!-- <input type="text" name="kode[]" id="kode1" class="form-control"
                                                            readonly> -->
                                                    </td>
                                                    <td width="10%">
                                                        <input name="qty[]" onchange="totalline(1);total();cekqty(1)" value="1" id="qty1" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td width="10%">
                                                        <input name="sat[]" id="sat1" type="text" class="form-control" onkeypress="return tabE(this,event)" readonly>
                                                    </td>
                                                    <td width="15%">
                                                        <input name="harga[]" onchange="totalline(1)" value="0" id="harga1" type="text" class="form-control rightJustified" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="tax[]" value='0' id="tax1" class="form-control" onchange="totalline(1);total();">
                                                    </td>
                                                    <td>
                                                        <a class="btn default" id="lupharga1" data-toggle="modal" href="#lupharga" onclick="getidharga(this.id)" readonly>
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </td>
                                                    <td width="5%">
                                                        <input name="disc[]" onchange="totalline(1);total();cekdisc(1)" value="0" id="disc1" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td>
                                                        <input name="discrp[]" onchange="totalline(1);total();cekdiscrp(1)" value="0" id="discrp1" type="text" class="form-control rightJustified ">
                                                    </td>
                                                    <td width="15%">
                                                        <input name="jumlah[]" id="jumlah1" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                                                    </td>

                                                </tr>
                                            </tbody>
                                    </table>

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
                                <div class="col-md-12">


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="form_ppn">
                                                <label class="col-md-3 control-label">PPN</label>
                                                <div class="col-md-4">
                                                    <select name="sppn" id="sppn" class="form-control select2me input-small" onchange="total()">
                                                        <option value="Y">Ya</option>
                                                        <option value="T" selected>Tidak</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Keterangan</label>
                                                <div class="col-md-4">
                                                    <textarea row="3" class="form-control input-xlarge" placeholder="" name="keterangan" id="keterangan" maxlength="100"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                        <!--tab2-->



                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="wells">


                                <button type="button" onclick="savex()" class="btn blue"><i class="fa fa-save"></i>
                                    <b>Simpan</b></button>

                                <div class="btn-group">
                                    <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> <b>Data Baru</b></button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('pembelian_retur') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-4 invoice-block">
                            <div class="well">
                                <table border="0">
                                    <tr>
                                        <td width="40%"><strong>SUB TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right" id="cobaaja"><strong><span id="_vsubtotal"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>DISKON</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <td width="10%"><strong>PPN</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="20" align="right"><strong><span id="_vppn"></span></strong></td>
                                    </tr>

                                    <tr>
                                        <td width="40%"><strong>TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
                                        <input type="hidden" class="form-control input-medium" name="_vtotalx" id="_vtotalx">
                                    </tr>

                                    <?php
                                    $pjkx = $this->db->get('tbl_pajak')->result();
                                    foreach ($pjkx as $pjk) {
                                        $pj = $pjk->prosentase;
                                    }
                                    ?>
                                    <input type="hidden" value="<?= $pj; ?>" name="vatrp" id="vatrp">

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
    $("#form_ppn").hide();
    var idrow = 2;
    var rowCount;
    var arr = [1];

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

    // function tambah() {
    //     var x = document.getElementById('datatable').insertRow(idrow);
    //     var td1 = x.insertCell(0);
    //     var td2 = x.insertCell(1);
    //     var td3 = x.insertCell(2);
    //     var td4 = x.insertCell(3);
    //     var td5 = x.insertCell(4);
    //     var td6 = x.insertCell(5);
    //     var td7 = x.insertCell(6);
    //     var td8 = x.insertCell(7);
    //     var td9 = x.insertCell(8);
    //     var td10 = x.insertCell(9);
    //     td1.innerHTML = "<button type='button' onclick=hapusBarisIni(1) class='btn red'><i class='fa fa-trash-o'>";
    //     td2.innerHTML = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
    //     td3.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
    //     td4.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly >";
    //     td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly>";
    //     td6.innerHTML = "<input name='tax[]'  id=tax" + idrow + " onchange='totalline(" + idrow + ");total()' value='0' type='checkbox' class='form-control rightJustified'>";
    //     td7.innerHTML = "<a class='btn default' id=lupharga" + idrow + " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a> ";
    //     td8.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
    //     td9.innerHTML = "<input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ");total()' value='0'  type='text' class='form-control rightJustified'  >";
    //     td10.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified'>";
    //     initailizeSelect2_farmasi_barang();
    //     idrow++;
    // }

    function tambah() {
        var table = $("#datatable");

        table.append("<tr id='retur_tr" + idrow + "'>" +
            "<td><button id='btnhapus" + idrow + "' type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
            "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select></td>" +
            "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'></td>" +
            "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly></td>" +
            "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly></td>" +
            "<td><input name='tax[]'  id=tax" + idrow + " onchange='totalline(" + idrow + ");total()' value='0' type='checkbox' class='form-control rightJustified'></td>" +
            "<td><a class='btn default' id=lupharga" + idrow + " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a></td>" +
            "<td><input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
            "<td><input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
            "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified'></td>" +
            "</tr>");
        initailizeSelect2_farmasi_barang();
        idrow++;
    }

    function cekqty(id) {
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var discrp = qty * harga * disc / 100;
        var jumlah = qty * harga - discrp;
        $('#discrp' + id).val(formatCurrency1(discrp));
        $('#qty').val(qty.toFixed(0));
        // separateComma(diskon.toFixed(0))
        $('#qty' + id).val(separateComma(qty.toFixed(0)));
        $('#jumlah' + id).val(formatCurrency1(jumlah));
        total();
    }

    function cekdisc(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var discrp = qty * harga * disc / 100;
        if (disc == 0) {
            $("#discrp" + id).val(separateComma(0));
        } else {
            $("#discrp" + id).val(separateComma(discrp.toFixed(0)));
        }
        total();
    }


    function cekdiscrp(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var discrpx = $("#discrp" + id).val();
        var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
        $("#disc" + id).val(0);
        $("#discrp" + id).val(formatCurrency1(discrp));
        var subtotal = qty * harga;
        var jumlah = subtotal - discrp;
        $('#jumlah' + id).val(formatCurrency1(jumlah));
        total();
    }

    function showbarang(str) {
        var xhttp;
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
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur/getbarang/" + str, true);
        xhttp.send();
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
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getharga/" + str, true);
        xhttp.send();
    }

    function showbarangname1(str, id) {
        var xhttp;
        if (str == "") {
            document.getElementById("nama" + id).value = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nama" + id).value = this.responseText;
                document.getElementById("jumdata").value = this.jumdata;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur/getbarangname/" + str, true);
        xhttp.send();
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

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?php echo base_url(); ?>pembelian_retur/getinfobarang/" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var qty = $('#qty' + id).val();
                $('#sat' + id).val(data.satuan1);
                $('#harga' + id).val(formatCurrency1(data.hargabeli));
                var harga = data.hargabeli;
                var jumlah = Number(parseInt(qty.replaceAll(',', ''))) * data.hargabeli;
                $('#jumlah' + id).val(formatCurrency1(jumlah));
                totalline(id);
            }
        });


    }

    function getidharga(id) {
        return;
        var vid = id.substring(8);
        document.getElementById("nopilihharga").value = vid;
        var supp = document.getElementById("supp").value;
        var item = document.getElementById("kode" + vid).value;
        var param = supp + '~' + item;
        showharga(param);
    }


    function post_harga(v1, v2) {
        id = document.getElementById("nopilihharga").value;
        document.getElementById("sat" + id).value = v2;
        document.getElementById("harga" + id).value = v1;
        totalline(id);
    }


    function getnobukti() {
        var xhttp;

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nomorbukti").value = this.responseText;
            }
        };

        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getnobukti", true);
        xhttp.send();
    }

    function savex() {
        var noform = $('[name="nomorbukti"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var gudang = $('[name="gudang1"]').val();
        var total = $('#_vtotal').text();
        if (noform == "" || total == "" || total == "0.00" || gudang == null) {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?= site_url() ?>Pembelian_retur/save_one',
                data: $('#frmpembelian').serialize(),
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    var no = data.nomor;
                    //rincian
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    var pj = parseInt($("#vatrp").val()) / 100;
                    totvatrp = 0;
                    diskontotal = 0;
                    for (i = 1; i < rowCount; i++) {
                        var row = table.rows[i];
                        kode = row.cells[1].children[0].value;
                        qtyx = row.cells[2].children[0].value;
                        var qty = Number(qtyx.replace(/[^0-9\.]+/g, ""));
                        sat = row.cells[3].children[0].value;
                        hargax = row.cells[4].children[0].value;
                        var harga = Number(hargax.replace(/[^0-9\.]+/g, ""));
                        discx = row.cells[7].children[0].value;
                        var disc = Number(discx.replace(/[^0-9\.]+/g, ""));
                        discrpx = row.cells[8].children[0].value;
                        var discrp = Number(discrpx.replace(/[^0-9\.]+/g, ""));
                        // var kode = $("#kode" + i).val();
                        // var qtyx = $("#qty" + i).val();
                        // var qty = Number(qtyx.replace(/[^0-9\.]+/g, ""));
                        // var sat = $("#sat" + i).val();
                        // var hargax = $("#harga" + i).val();
                        // var harga = Number(hargax.replace(/[^0-9\.]+/g, ""));
                        // var disc = $("#disc" + i).val();
                        // var discrpx = $("#discrp" + i).val();
                        // var discrp = Number(discrpx.replace(/[^0-9\.]+/g, ""));
                        // var taxx = $('#tax' + i).is(':checked');
                        if (row.cells[5].children[0].checked == true) {
                            var vat = 1;
                        } else {
                            var vat = 0;
                        }
                        // var tax = $("#tax"+i).val();
                        // var jumlahx = $("#jumlah" + i).val();
                        var jumlahx = row.cells[9].children[0].value;
                        var jumlah = Number(jumlahx.replace(/[^0-9\.]+/g, ""));
                        if (vat == 1) {
                            var vatrp = jumlah * pj;
                        } else {
                            var vatrp = 0;
                        }
                        // console.log('kode : '+kode+', qty : '+qty+', sat : '+sat+', harga : '+harga+', disc : '+disc+', discrp : '+discrp+', tax : '+vat+', jumlah : '+jumlah+', pajak : '+pj+', taxrp : '+vatrp);
                        $.ajax({
                            url: '<?= site_url() ?>Pembelian_retur/save_multi/?kode=' + kode + '&qty=' +
                                qty + '&sat=' + sat + '&harga=' + harga + '&disc=' + disc + '&discrp=' +
                                discrp + '&tax=' + vat + '&jumlah=' + jumlah + '&taxrp=' + vatrp +
                                '&retur_no=' + no,
                            data: $('#frmpembelian').serialize(),
                            type: 'POST',
                            dataType: 'JSON',
                            success: function(data) {
                                console.log(data);
                            }
                        });
                    }
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + data.nomor + "</b> </p>" + "Tanggal :  " +
                            tanggal + '<br> Total : ' + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>pembelian_retur";
                    });
                }
            });
        }
    }

    function save() {
        var noform = $('[name="nomorbukti"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var gudang = $('[name="gudang"]').val();
        var total = $('#_vtotal').text();
        // console.log(total);
        if (noform == "" || total == "" || total == "0.00" || gudang == null) {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            console.log($('#frmpembelian').serialize())

            $.ajax({
                url: '<?php echo site_url('pembelian_retur/save/1') ?>',
                data: $('#frmpembelian').serialize(),
                type: 'POST',

                success: function(data) {
                    data1 = JSON.parse(data);
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + data1.nomor + "</b> </p>" +
                            "Tanggal :  " + tanggal + '<br> Total : ' + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>pembelian_retur";
                    });

                },
                error: function(data) {
                    swal('RETUR PEMBELIAN', 'Data gagal disimpan ...', '');


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

    function hapusBarisIni(param) {
        $("#retur_tr" + param).remove();
        total();
    }

    // function hapusBarisIni(param) {

    //     // console.log(param);
    //     // console.log(arr.indexOf(param))
    //     var x = document.getElementById('datatable').deleteRow(arr.indexOf(param) + 1);
    //     arr.splice(arr.indexOf(param), 1);

    //     rowCount--;
    //     // console.log('rowCount ' + rowCount);
    //     total();
    // }


    function total_before() { // fungsi subtotal sebelumnya

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        tdiskon = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[7].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

            tjumlah = tjumlah + eval(jumlah1 * harga1);

            diskon = eval((diskon1 / 100) * jumlah1 * harga1);

            tdiskon = tdiskon + diskon;


        }

        var cppn = document.getElementById("sppn").value;
        if (cppn == "T") {
            tppn = (tjumlah - tdiskon) * 11 / 100;
        } else {
            tppn = 0;
        }

        // var dpp = (tjumlah - tdiskon + tppn) - diskon * 111/100;
        // console.log(dpp);


        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah);
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon);
        document.getElementById("_vppn").innerHTML = separateComma(tppn);
        document.getElementById("_vtotal").innerHTML = separateComma(tjumlah - tdiskon + tppn);
        $('[name="_vtotalx"]').val(tjumlah - tdiskon + tppn);
        // console.log($('[name="_vtotalx"]').val());
    }

    var cekppn2 = '<?php echo $cekppn2; ?>';

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
            var row = table.rows[i];

            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[7].children[0].value;
            diskonrp = row.cells[8].children[0].value;
            subtotal = row.cells[9].children[0].value;

            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            var diskon2 = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
            var subtotal1 = Number(subtotal.replace(/[^0-9\.]+/g, ""));


            tjumlah = tjumlah + eval(jumlah1 * harga1);

            diskon = eval((diskon1 / 100) * jumlah1 * harga1);

            tdiskon = tdiskon + diskon2;
            if (document.getElementById('tax' + i) !== null && document.getElementById('tax' + i).checked === true) {
                tppn = tppn + (eval((jumlah1 * harga1 - diskon2))) * cekppn2;
            }

        }
        // console.log(tjumlah)
        var tmaterai = Number(tmateraix);

        var abc = Number(tjumlah - tdiskon + tppn);
        if (tmaterai == 10000) {
            var tmattotal = abc + tmaterai;
        } else {
            var tmattotal = abc;
        }
        document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
        // document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai);
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
        document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
        document.getElementById("_vtotal").innerHTML = separateComma(tmattotal.toFixed(0));

        $('[name="_vtotalx"]').val(tjumlah - tdiskon + tppn);
        $('[name="_vppn"]').val(tppn);

    }

    function totalline(id) {
        // console.log(totalline());
        // var table = document.getElementById('datatable');
        // var row = table.rows[id];
        // jumlah = row.cells[1].children[0].value * row.cells[3].children[0].value;
        // diskon = (row.cells[6].children[0].value / 100) * jumlah;
        // tot = jumlah - diskon;
        // row.cells[8].children[0].value = formatCurrency1(tot);
        // total();

        var table = document.getElementById('datatable');
        var row = table.rows[id];
        var disc = $('#disc' + id).val();
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $('#harga' + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        jumlah = qty * harga;
        diskon = (disc / 100) * jumlah;
        tot = jumlah - diskon;
        $("#jumlah" + id).val(separateComma(tot.toFixed(0)));
        total();

        // var table = document.getElementById('datatable');
        // var row = table.rows[arr.indexOf(id) + 1];
        // jumlah = row.cells[2].children[0].value * row.cells[4].children[0].value;
        // diskon = (row.cells[7].children[0].value / 100) * jumlah;
        // tot = jumlah - diskon;
        // row.cells[9].children[0].value = tot;
        // total();
        // var subtotal = $('#_vsubtotal').html();

    }



    function showpo() {
        var xhttp;
        var str = $('[name="supp"]').val();

        if (str == "") {
            document.getElementById("kodepu").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("kodepu").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>pembelian_retur/getlistpo/" + str, true);
        xhttp.send();
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

    function getpo() {
        var xhttp;
        var str = $('[name=kodepu]').val();
        if (str == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
            $('[id=harga1]').val('');
            $('[id=disc1]').val('');
            $('[id=discrp1]').val('');
            totalline(1);
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>pembelian_retur/getpo/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    for (i = 0; i <= data.length - 1; i++) {
                        if (i == 1) {
                            $("#btnhapus" + i).attr("disabled", true);
                        }
                        hapusBarisIni(i);
                    }

                    for (i = 0; i <= data.length - 1; i++) {
                        if (i > 0) {
                            tambah();
                        }

                        x = i + 1;

                        var option = $("<option selected></option>").val(data[i].kodebarang).text(data[i]
                            .namabarang);
                        $('#kode' + x).append(option).trigger('change');

                        document.getElementById("kode" + x).value = data[i].kodebarang;
                        document.getElementById("qty" + x).value = formatCurrency1(data[i].qty_terima);
                        document.getElementById("sat" + x).value = data[i].satuan;
                        document.getElementById("harga" + x).value = formatCurrency1(data[i].price);
                        // document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("disc" + x).value = data[i].discount;
                        document.getElementById("discrp" + x).value = formatCurrency1(data[i].discountrp);
                        if (data[i].vat == 1) {
                            document.getElementById("tax" + x).checked = true;
                        }
                        // $('#discrp'+x).val(data[i].discountrp);
                        console.log(data[i].discountrp);
                        totalline(x);

                    }

                }
            });
        }
    }

    function getpoheader() {
        var xhttp;
        var str = $('[name=kodepu]').val();
        if (str == "") {} else {
            $.ajax({
                url: "<?php echo base_url(); ?>pembelian_retur/getpoheader/" + str,
                type: "GET",
                dataType: "JSON",

                success: function(data) {
                    // alert(data.terima_date1);      
                    $('[name="sppn"]').val(data.sppn);
                    $('#gudang').val(data.nm_gud);
                    $('#gudang1').val(data.gudang);
                    $('#tanggal').val(data.terima_date1);
                }
            });
        }
    }


    window.onload = function() {
        document.getElementById('nomorbukti').focus();
    };
</script>


</body>

</html>