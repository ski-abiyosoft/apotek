<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?= $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Farmasi <small>Retur Pembelian</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?= base_url(); ?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?= base_url(); ?>pembelian_retur">
                    Daftar Retur Pembelian
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Edit Retur
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i> Edit Data
        </div>
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
                                        <label class="col-md-3 control-label">Vendor</label>
                                        <div class="col-md-6">
                                            <select id="supp" name="supp" class="form-control select2_el_vendor input-large" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <?php
                                                if ($header->vendor_id) {
                                                    $datavendor = data_master('tbl_vendor', array('vendor_id' => $header->vendor_id)); ?>
                                                    <option value="<?= $header->vendor_id; ?>"><?= $datavendor->vendor_name; ?>
                                                    </option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Retur #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?= $header->retur_no; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. BAPB</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-medium" name="kodepu" id="kodepu" value="<?= $header->terima_no; ?> " readonly>
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
                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?= date('Y-m-d', strtotime($header->retur_date)); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                        <div class="col-md-6">
                                            <?php $gudang = $this->db->get_where('tbl_depo', ['depocode' => $header->gudang])->row_array(); ?>
                                            <input id="gudang1" value="<?= $gudang['keterangan']; ?>" name="gudang1" class="form-control  input-large" readonly>
                                            <input id="gudang" value="<?= $header->gudang; ?>" name="gudang" type="hidden" class="form-control  input-large" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead class="page-breadcrumb breadcrumb">
                                            <!-- <th class="title-white" width="5%" style="text-align: center">Hapus</th> -->
                                            <th class="title-white" width="20%" style="text-align: center">Nama Barang</th>
                                            <th class="title-white" width="10%" style="text-align: center">Kuantitas</th>
                                            <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="10%" style="text-align: center">Harga</th>
                                            <th class="title-white" width="5%" style="text-align: center">Tax</th>
                                            <th class="title-white" width="10%" style="text-align: center">Tax Rp</th>
                                            <th class="title-white" width="5%" style="text-align: center">Diskon %</th>
                                            <th class="title-white" width="10%" style="text-align: center">Diskon Rp</th>
                                            <th class="title-white" width="15%" style="text-align: center">Total Harga</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($detil as $row) {
                                            ?>
                                                <tr id="retur_tr<?= $no; ?>">
                                                    <!-- <td>
                                                        <button type='button' onclick=hapusBarisIni(<?= $no; ?>) class='btn red'><i class='fa fa-trash-o'></i></button>
                                                    </td> -->
                                                    <td>
                                                        <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_barangdata form-control input-largex" onchange="showbarangname(this.value, <?= $no; ?>)">
                                                            <option value="<?= $row->kodebarang; ?>"><?= $row->namabarang; ?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input name="qty[]" value="<?= number_format($row->qty_retur); ?>" onchange="totalline(<?= $no; ?>);total(); cekqty(<?= $no ?>)" id="qty<?= $no; ?>" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td>
                                                        <input name="sat[]" value="<?= $row->satuan; ?>" id="sat<?= $no; ?>" type="text" class="form-control " onkeypress="return tabE(this,event)" readonly>
                                                    </td>
                                                    <td>
                                                        <input name="harga[]" value="<?= number_format($row->price); ?>" onchange="totalline(<?= $no; ?>);total()" id="harga<?= $no; ?>" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" <?= ($row->tax == 1 ? 'checked' : '') ?> name="tax[]" value='<?= $row->tax; ?>' id="tax<?= $no ?>" class="form-control" onchange="totalline(<?= $no ?>);total();">
                                                    </td>
                                                    <td>
                                                        <input name="pajak[]" value="<?= number_format($row->taxrp); ?>" onchange="totalline(<?= $no; ?>);total()" id="pajak<?= $no; ?>" type="text" class="form-control rightJustified" readonly>
                                                    </td>
                                                    <td>
                                                        <input name="disc[]" value="<?= $row->discount; ?>" onchange="totalline(<?= $no; ?>);total();cekdisc(<?= $no ?>)" id="disc<?= $no; ?>" type="text" class="form-control rightJustified ">
                                                    </td>
                                                    <td>
                                                        <input name="discrp[]" onchange="totalline(<?= $no ?>);total();cekdiscrp(<?= $no ?>)" value="<?= number_format($row->discountrp); ?>" id="discrp<?= $no ?>" type="text" class="form-control rightJustified ">
                                                    </td>
                                                    <td>
                                                        <input name="jumlah[]" id="jumlah<?= $no; ?>" value="<?= number_format($row->totalrp, 2) ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly>
                                                    </td>
                                                </tr>
                                            <?php $no++;
                                            } ?>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <div class="wells">
                                                <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                                <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
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
                                </div>
                            </div>
                        </div>
                        <!--tab2-->
                    </div>
                    <!--tab-->
                    <div class="row">
                        <div class="col-xs-9">
                            <div class="wells">
                                <button type="button" onclick="savey()" class="btn blue"><i class="fa fa-save"></i><b>Simpan</b></button>
                                <!--a class="btn yellow print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" ><i class="fa fa-print"></i> Cetak</a-->
                                <div class="btn-group">
                                    <a href="<?= base_url() ?>pembelian_retur/entri" class="btn btn-success">
                                        <i class="fa fa-plus"></i>
                                        Data Baru
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?= base_url('pembelian_retur') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>
                        <div class="col-xs-3 invoice-block">
                            <div class="well">
                                <table>
                                    <tr>
                                        <td width="40%"><strong>SUB TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
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

<?php
$this->load->view('template/footer');
?>

<script>
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

    function cekqty(id) {
        var disc = $('#disc' + id).val();
        if (disc != 0) {
            var qtyx = $('#qty' + id).val();
            var qty = Number(parseInt(qtyx.replaceAll(',', '')));
            var hargax = $('#harga' + id).val();
            var harga = Number(parseInt(hargax.replaceAll(',', '')));
            discountrp = qty * harga * disc / 100;
            $('#discrp' + id).val(separateComma(discountrp));
            jumlah = qty * harga - discountrp;
            $('#jumlah' + id).val(separateComma(jumlah));
            totalline(id);
        } else {
            var qtyx = $('#qty' + id).val();
            var qty = Number(parseInt(qtyx.replaceAll(',', '')));
            var hargax = $('#harga' + id).val();
            var harga = Number(parseInt(hargax.replaceAll(',', '')));
            var discrpx = $('#discrp' + id).val();
            var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
            jumlah = qty * harga - discrp;
            $('#jumlah' + id).val(separateComma(jumlah));
            totalline(id);
        }
    }


    // $(document).ready(function() {
    // var table = document.getElementById('datatable');
    // var rowCount = table.rows.length;
    // for(i = 1; i < rowCount; i++){
    //     var qtyx = $("#qty"+i).val();
    //     var qty = Number(parseInt(qtyx.replaceAll(',','')));
    //     $("#qty"+i).val(qty);
    //     var hargax = $("#harga"+i).val();
    //     var harga = Number(parseInt(hargax.replaceAll(',','')));
    //     $("#harga"+i).val(separateComma(harga));
    //     var hitung_jml = qty * harga;
    //     console.log(hitung_jml);
    //     $("#jumlah"+i).val(hitung_jml);
    // }
    // });


    var idrow = <?= $jumdata1 + 1; ?>;
    var rowCount;
    var arr = [1];

    function cekdisc(id) {
        var qtyx = $("#qty" + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $("#harga" + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var discrp = qty * harga * disc / 100;
        $("#discrp" + id).val(separateComma(discrp.toFixed(0)));
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
        $("#discrp" + id).val(separateComma(discrp));
        tot = qty * harga - discrp;
        // console.log(tot)
        $('#jumlah' + id).val(separateComma(tot));
        // total();
    }



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
        // var td10 = x.insertCell(9);
        td1.innerHTML = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
            ")' class='select2_el_farmasi_barangdata form-control' ><option value=''>--- Pilih Barang ---</option></select>";
        td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
        td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly >";
        td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'>";
        td5.innerHTML = "<input name='tax[]'  id=tax" + idrow + " onchange='totalline(" + idrow + ");total()' value='0' type='checkbox' class='form-control rightJustified'>";
        td6.innerHTML = "<input name='pajak[]' onchange='totalline(" + idrow + ");total()' value='0' id='pajak" + idrow + "' type='text ' class='form-control rightJustified ' readonly>";
        td7.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
        td8.innerHTML = "<input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
        td9.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' readonly value='0'>";
        initailizeSelect2_farmasi_barang();
        initailizeSelect2_farmasi_barangdata();
        idrow++;
    }

    // function tambah() {
    //     var table = $("#datatable");

    //     table.append("<tr id='retur_tr" + idrow + "'>" +
    //         "<td><button type='button' onclick=hapusBarisIni(" + idrow + ") class='btn red'><i class='fa fa-trash-o'></i> </button></td>" +
    //         "<td><select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barangdata form-control' ><option value=''>--- Pilih Barang ---</option></select></td>" +
    //         "<td><input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();cekqty(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'></td>" +
    //         "<td><input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly></td>" +
    //         "<td><input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'></td>" +
    //         "<td><input name='tax[]'  id=tax" + idrow + " onchange='totalline(" + idrow + ");total()' value='0' type='checkbox' class='form-control rightJustified'></td>" +
    //         "<td><input name='pajak[]'  id=pajak" + idrow + " onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly></td>" +
    //         "<td><a class='btn default' id=lupharga" + idrow + " data-toggle='modal' href='#lupharga' onclick='getidharga(this.id)'><i class='fa fa-search'></i></a></td>" +
    //         "<td><input name='disc[]'   id=disc" + idrow + " onchange='totalline(" + idrow + ");total();cekdisc(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
    //         "<td><input name='discrp[]'   id=discrp" + idrow + " onchange='totalline(" + idrow + ");cekdiscrp(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  ></td>" +
    //         "<td><input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' readonly></td>" +
    //         "</tr>");
    //     initailizeSelect2_farmasi_barangdata();
    //     idrow++;
    // }

    // function hapusBarisIni(param) {
    //     $("#retur_tr" + param).remove();
    //     total();
    // }

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
        xhttp.open("GET", "<?= base_url(); ?>pembelian_retur/getbarang/" + str, true);
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
        xhttp.open("GET", "<?= base_url(); ?>pembelian_pesanan/getharga/" + str, true);
        xhttp.send();
    }

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $.ajax({
            url: "<?= base_url(); ?>pembelian_retur/getinfobarang/" + str,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(separateComma(data.hargabeli));
                $('#pajak' + vid).val(data.taxrp);
                totalline(vid);
            }
        });

    }


    function getidharga(id) {
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

    function savey() {
        var noform = $('[name="nomorbukti"]').val();
        var retur_no = $('#nomorbukti').val();
        var tanggal = $('[name="tanggal"]').val();
        var total = $('#_vtotal').text();
        var ppn_123 = $('#_vppn').text();
        if (noform == "" || total == "" || total == "0.00") {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?= site_url() ?>Pembelian_retur/update_one/?retur_no=' + retur_no,
                data: $('#frmpembelian').serialize(),
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data);
                    //rincian
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    var pj = parseInt($("#vatrp").val()) / 100;
                    totvatrp = 0;
                    diskontotal = 0;
                    for (i = 1; i < rowCount; i++) {
                        var kode = $("#kode" + i).val();
                        var qtyx = $("#qty" + i).val();
                        var qty = Number(qtyx.replace(/[^0-9\.]+/g, ""));
                        var sat = $("#sat" + i).val();
                        var hargax = $("#harga" + i).val();
                        var harga = Number(hargax.replace(/[^0-9\.]+/g, ""));
                        var disc = $("#disc" + i).val();
                        var discrpx = $("#discrp" + i).val();
                        var discrp = Number(discrpx.replace(/[^0-9\.]+/g, ""));
                        var taxx = $('#tax' + i).is(':checked');
                        if (taxx == true) {
                            var vat = 1;
                        } else {
                            var vat = 0;
                        }
                        // var tax = $("#tax"+i).val();
                        var jumlahx = $("#jumlah" + i).val();
                        var jumlah = Number(jumlahx.replace(/[^0-9\.]+/g, ""));
                        if (vat == 1) {
                            var vatrp = jumlah * pj;
                        } else {
                            var vatrp = 0;
                        }
                        // console.log('kode : '+kode+', qty : '+qty+', sat : '+sat+', harga : '+harga+', disc : '+disc+', discrp : '+discrp+', tax : '+vat+', jumlah : '+jumlah+', pajak : '+pj+', taxrp : '+vatrp);
                        $.ajax({
                            url: '<?= site_url() ?>Pembelian_retur/update_multi/?kode=' + kode +
                                '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&disc=' + disc +
                                '&discrp=' + discrp + '&tax=' + vat + '&jumlah=' + jumlah + '&taxrp=' +
                                vatrp + '&retur_no=' + retur_no,
                            data: $('#frmpembelian').serialize(),
                            type: 'POST',
                            dataType: 'JSON',
                            success: function(data) {
                                console.log(data);
                            }
                        });
                        totvatrp += vatrp;
                        diskontotal += discrp;
                    }
                    $.ajax({
                        url: '<?= site_url() ?>Pembelian_retur/update_one_u/?totvatrp=' + totvatrp +
                            '&totaltagihan=' + Number(parseInt(total.replaceAll(',', ''))) + '&ppnrp=' +
                            Number(parseInt(ppn_123.replaceAll(',', ''))) + '&retur_no=' + retur_no +
                            '&diskontotal=' + diskontotal,
                        data: $('#frmpembelian').serialize(),
                        type: 'POST',
                        dataType: 'JSON',
                        success: function(data) {
                            // console.log(data);
                            swal({
                                title: "BAPB",
                                html: "<p> No. Bukti : <b>" + data.nomor + "</b></p>" +
                                    "Tanggal : " + tanggal + "<br> Total : " + total,
                                type: "success",
                                confirmButtonText: "OK"
                            }).then((value) => {
                                location.href =
                                    "<?= base_url() ?>Pembelian_retur";
                            });
                        }
                    });
                }
            });
        }
    }

    function save() {
        var noform = $('[name="nomorbukti"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var total = $('#_vtotal').text();
        if (noform == "" || total == "" || total == "0.00") {
            swal('RETUR PEMBELIAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?= site_url('pembelian_retur/save/2') ?>',
                data: $('#frmpembelian').serialize(),
                type: 'POST',

                success: function(data) {
                    data1 = JSON.parse(data);
                    swal({
                        title: "RETUR PEMBELIAN",
                        html: "<p> No. Retur   : <b>" + data1.nomor + "</b> </p>" +
                            "Tanggal :  " + tanggal + "</b> </p>" +
                            "Total: " + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?= base_url() ?>pembelian_retur";
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
        tsub = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];

            // jumlah = $('#qty' + i).val();
            // harga = $('#harga' + i).val();
            // diskon = $('#disc' + i).val();
            // diskonrp = $('#discrp' + i).val();
            // taxrp = $('#pajak' + i).val();
            // subtotal = $('#jumlah' + i).val();

            jumlah = row.cells[1].children[0].value;
            harga = row.cells[3].children[0].value;
            pajak = row.cells[5].children[0].value;
            diskon = row.cells[6].children[0].value;
            diskonrp = row.cells[7].children[0].value;

            var pajak1 = Number(pajak.replace(/[^0-9\.]+/g, ""));
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            var diskon2 = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
            var diskonx = (jumlah1 * harga1) * diskon1 / 100;

            subtotal1 = (jumlah1 * harga1) - diskonx;

            row.cells[8].children[0].value = separateComma(subtotal1);

            // var subtotal1 = Number(subtotal.replace(/[^0-9\.]+/g, ""));
            // row.cells[3].children[0].value = (separateComma(harga1));
            // row.cells[7].children[0].value = (separateComma(jumlah1 * harga1 * diskon1/100));
            // row.cells[8].children[0].value = (separateComma(subtotal1));
            // alert(subtotal1)
            // var subtotal1 = jumlah1 * harga1;


            tjumlah = tjumlah + eval(jumlah1 * harga1);
            diskon = eval((diskon1 / 100) * jumlah1 * harga1);

            tdiskon += diskon2;
            tsub += subtotal1;
            // if (document.getElementById('tax' + i) != null || document.getElementById('tax' + i).checked == true) {
            //     // if (row.cells[5].children[0].checked === true) {
            //     pajakx = row.cells[5].children[0].value;
            //     var pajakx1 = Number(pajakx.replace(/[^0-9\.]+/g, ""));
            //     tppn += (pajakx1);
            //     pajak = subtotal1 * cekppn2;
            //     $("#pajak" + i).val(separateComma(pajak));
            // } else {
            //     $("#pajak" + i).val(separateComma(0));
            // }
            tppn += pajak1;

        }
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



    // function total() {

    //     var tmateraix = $("#materai").val();

    //     var vtotal = $('#_vtotal').text();
    //     var xtotal = parseInt(vtotal.replaceAll(',', ''));
    //     if (xtotal >= '5000000') {
    //         $('#materai').val('10000').change();
    //     }
    //     var table = document.getElementById('datatable');
    //     var rowCount = table.rows.length;

    //     tjumlah = 0;
    //     tdiskon = 0;
    //     tppn = 0;

    //     for (var i = 1; i < rowCount; i++) {
    //         var row = table.rows[i];

    //         jumlah = row.cells[1].children[0].value;
    //         harga = row.cells[3].children[0].value;
    //         diskon = row.cells[6].children[0].value;
    //         diskonrp = row.cells[7].children[0].value;
    //         subtotal = row.cells[8].children[0].value;

    //         var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
    //         var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
    //         var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
    //         var diskon2 = Number(diskonrp.replace(/[^0-9\.]+/g, ""));
    //         var subtotal1 = Number(subtotal.replace(/[^0-9\.]+/g, ""));
    //         row.cells[3].children[0].value = (separateComma(harga1));
    //         row.cells[7].children[0].value = (separateComma(jumlah1 * harga1 * diskon1 / 100));
    //         row.cells[8].children[0].value = (separateComma(jumlah1 * harga1 - diskon2));
    //         // var subtotal1 = jumlah1 * harga1;

    //         tjumlah = tjumlah + eval(jumlah1 * harga1);

    //         diskon = eval((diskon1 / 100) * jumlah1 * harga1);

    //         tdiskon = tdiskon + diskon2;
    //         if (document.getElementById('tax' + i) !== null && document.getElementById('tax' + i).checked === true) {
    //             tppn = tppn + (eval((jumlah1 * harga1 - diskon))) * cekppn2;
    //         }

    //     }
    //     var tmaterai = Number(tmateraix);

    //     var abc = Number(tjumlah - tdiskon + tppn);
    //     if (tmaterai == 10000) {
    //         var tmattotal = abc + tmaterai;
    //     } else {
    //         var tmattotal = abc;
    //     }
    //     document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
    //     // document.getElementById("_vmaterai").innerHTML = separateComma(tmaterai);
    //     document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
    //     document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
    //     document.getElementById("_vtotal").innerHTML = separateComma(tmattotal.toFixed(0));

    // }

    var cekppn2 = '<?= $cekppn2; ?>';

    function cekdisc(id) {
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $('#harga' + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var diskon = (qty * harga) * disc / 100;
        $("#discrp" + id).val(separateComma(diskon));
        total();
    }

    function cekdiscrp(id) {
        var discrpx = $("#discrp" + id).val();
        var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var hargax = $('#harga' + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        var diskon = (qty * harga) - discrp;
        $("#disc" + id).val(0);
        $("#discrp" + id).val(separateComma(diskon));
        total();
    }

    function totalline(id) {

        var table = document.getElementById('datatable');
        var row = table.rows[id];
        var qtyx = $('#qty' + id).val();
        var qty = Number(parseInt(qtyx.replaceAll(',', '')));
        var discrpx = $('#discrp' + id).val();
        var discrp = Number(parseInt(discrpx.replaceAll(',', '')));
        var hargax = $('#harga' + id).val();
        var harga = Number(parseInt(hargax.replaceAll(',', '')));
        if (document.getElementById('tax' + id).checked == true) {
            var pajak = ((qty * harga) - discrp) * cekppn2;
            $("#pajak" + id).val(separateComma(pajak));
        } else {
            $("#pajak" + id).val(separateComma(0));
        }
        total();

    }



    function _urlcetak() {
        var baseurl = "<?= base_url() ?>";
        var param = $('[name="nomorbukti"]').val();
        return baseurl + 'pembelian_retur/cetak/' + param;
    }


    window.onload = function() {
        document.getElementById('nomorbukti').focus();
        // var jumdata = <?= $jumdata1 + 1; ?>;
        // for (i = 1; i < jumdata; i++) {
        //     totalline(i);
        // }
        // total();
    };

    $(document).ready(function() {
        total();
    });
</script>

<div class="modal fade" id="lupharga" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="nopilihharga">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Daftar Harga Pembelian</h4>
                    <h5><strong><span id="namabarangharga"></span></strong></h5>
                </div>
                <div class="modal-body">
                    <div id="dafhargabeli"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btntutup" class="btn red" data-dismiss="modal">Tutup</button>
                </div>
        </div>
    </div>
</div>



</body>

</html>