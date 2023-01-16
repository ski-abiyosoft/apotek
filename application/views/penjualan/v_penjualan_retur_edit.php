<?php
$this->load->view('template/header');
$this->load->view('template/body');
foreach ($header as $rowh) {
};
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Penjualan <small>Retur</small>
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
                <a class="title-white" href="<?php echo base_url(); ?>penjualan_retur">
                    Daftar Retur Penjualan
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
            <i class="fa fa-reorder"></i>Edit Data
        </div>
        <!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->

    </div>

    <div class="portlet-body form">
        <form id="frmPenjualan" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Retur
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">
                                   Info
								</a>
							</li-->

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pelanggan</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="hidden" name="cust" id="cust" value="<?= $rowh->rekmed; ?>">
                                                <select id="cust" name="cust" class="form-control select2_el_pasien input-large" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>
                                                    <?php
                                                    if ($rowh->rekmed) {
                                                        $datapasien = data_master('tbl_pasien', array('rekmed' => $rowh->rekmed));
                                                    ?>
                                                        <option value=" <?= $rowh->rekmed; ?>">
                                                            <?= $rowh->rekmed . ' | ' . $datapasien->namapas; ?>
                                                        </option>
                                                    <?php } else { ?>
                                                        <option value=""></option>
                                                    <?php } ?>
                                                </select>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Retur #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti" id="nomorbukti" value="<?php echo $rowh->returno; ?>" readonly>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo input-large" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                    <?php if ($rowh->gudang) {
                                                        $namagudang = data_master('tbl_depo', array('depocode' => $rowh->gudang))->keterangan; ?>
                                                        <option value="<?= $rowh->gudang; ?>"><?= $namagudang; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Resep</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" name="kodesi" class="form-control input-medium" value="<?php echo $rowh->resepno; ?>" readonly />
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
                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($rowh->tglretur)); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Alasan</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" name="alasan" class="form-control input-medium" value="<?php echo $rowh->alasan; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead>
                                            <th width="5%" style="text-align: center">Hapus</th>
                                            <th width="35%" style="text-align: center">Nama Barang</th>
                                            <th width="10%" style="text-align: center">Kuantitas</th>
                                            <th width="10%" style="text-align: center">Satuan</th>
                                            <th width="15%" style="text-align: center">Harga</th>
                                            <th width="5%" style="text-align: center">PPN</th>
                                            <th width="10%" style="text-align: center">Diskon (Rp)</th>
                                            <th width="15%" style="text-align: center">Total Harga</th>

                                        </thead>

                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($detil as $row) { ?>
                                                <tr id="rj_tr<?= $no; ?>">
                                                    <td width="5%">
                                                        <button type='button' onclick='hapusBarisIni(<?= $no; ?>); totalline(<?= $no; ?>);' class='btn red'><i class='fa fa-trash-o'></i></button>
                                                    </td>
                                                    <td width="35%">
                                                        <select name="kode[]" id="kode<?= $no; ?>" class="select2_el_farmasi_barang form-control input-largex" onchange="showbarangname(this.value, <?= $no; ?>)">
                                                            <option value="<?= $row->kodebarang; ?>"><?= "[ ".$row->kodebarang." ] - [ ".$row->namabarang." ] - [ ".$row->satuan." ]"; ?></option>
                                                        </select>
                                                    </td>
                                                    <td width="10%">
                                                        <input name="qty[]" value="<?php echo $row->qtyretur; ?>" onchange="totalline(<?php echo $no; ?>);total();" id="qty<?php echo $no; ?>" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td width="10%">
                                                        <input name="sat[]" value="<?php echo $row->satuan; ?>" id="sat<?php echo $no; ?>" type="text" class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="15%">
                                                        <input name="harga[]" value="<?php echo number_format($row->price); ?>" onchange="totalline(<?php echo $no; ?>)" id="harga<?php echo $no; ?>" type="text" class="form-control rightJustified">
                                                    </td>
                                                    <td>
                                                        <input type='checkbox' checked class='form-control' id='ppn<?= $no; ?>' name='ppn[]' disabled>
                                                    </td>
                                                    <td width="5%">
                                                        <input name="disc[]" value="<?php echo number_format($row->discountrp); ?>" onchange="totalline(<?php echo $no; ?>);total()" id="disc<?php echo $no; ?>" type="text" class="form-control rightJustified ">
                                                    </td>
                                                    <td width="15%">
                                                        <input name="jumlah[]" value="<?= $row->totalrp ?>" id="jumlah<?php echo $no; ?>" type="text" class="form-control rightJustified" size="40%" onchange="total()">
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
                                                <!-- <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button> -->
                                            </div>
                                        </div>


                                    </div>


                                </div>
                            </div>



                        </div>
                        <!-- tab1-->

                        <div class="tab-pane" id="tab2">


                        </div>
                        <!--tab2-->



                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="wells">


                                <button id="btnsimpan" name="btnsimpan" type="button" onclick="savey()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>

                                <div class="btn-group">
                                    <a href="<?php echo base_url() ?>penjualan_retur/entri" class="btn btn-success">
                                        <i class="fa fa-plus"></i>
                                        Data Baru
                                    </a>

                                </div>

                                <div class="btn-group">
                                    <a href="<?= base_url('penjualan_retur') ?>" class="btn btn red"><i class="fa fa-undo"></i><b> KEMBALI </b></a>

                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-4 invoice-block">
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
                                        <td width="59" align="right"><strong><span id="_vppn"></span></strong>
                                            <input type="hidden" name="_ppn" id="_ppn" value="<?= $pajak ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="40%"><strong>TOTAL</strong></td>
                                        <td width="1%"><strong>:</strong></td>
                                        <td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
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
    var idrow = <?php echo $jumdata1 + 1; ?>;

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
        td1.innerHTML = "<button type='button' onclick='hapusBarisIni(" + idrow + "); totalline(" + idrow + ");' class='btn red'><i class='fa fa-trash-o'></i></button>";
        td2.innerHTML = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barang form-control' ><option value=''>--- Pilih Barang ---</option></select>";
        td3.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow + ");total();' value='1'  type='text' class='form-control rightJustified'  >";
        td4.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
        td5.innerHTML = "<input name='harga[]'  id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0'  type='text' class='form-control rightJustified'>";
        td6.innerHTML = "<input type='checkbox' checked class='form-control' id='ppn" + idrow + "' name='ppn[]' disabled>";
        td7.innerHTML = "<input name='disc[]'   id=disc" + idrow + " onchange='total();totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified'  >";
        td8.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%'>";
        initailizeSelect2_farmasi_barang();
        idrow++;
    }

    function hapusBarisIni(param) {
        $("#rj_tr" + param).remove();
        totalline(param);
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
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getbarang/" + str, true);
        xhttp.send();
    }



    function changeqty(id) {
        var qty = $("#qty" + id).val();
        var discx = $("#disc" + id).val();
        var disc = Number(parseInt(discx.replaceAll(',', '')));
        var kode = $("#kode" + id).val();
        var gudang = $('[name="gudang"]').val()
        var returno = $('[name="nomorbukti"]').val()
        $.ajax({
            url: "<?= site_url('Penjualan_retur/data_awal_retur/?kode=') ?>" + kode + "&gudang=" + gudang + "&returno=" + returno,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var qty_awal = Number(data.qtyretur).toFixed(0);
                var disc_awal = Number(data.discountrp).toFixed(0);
                harga_peritem = disc_awal / qty_awal;
                disc_new = qty * harga_peritem;
                $("#disc" + id).val(separateComma(disc_new.toFixed(0)));
                // console.log(data)
            }
        });
        total();
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
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getharga/" + str, true);
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
            }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>penjualan_retur/getbarangname/" + str, true);
        xhttp.send();
    }

    function showbarangname(str, id) {
        var xhttp;
        var vid = id;
        $('#sat' + vid).val('');
        $('#harga' + vid).val(0);
        var customer = $('#cust').val();
        $.ajax({
            url: "<?php echo base_url(); ?>penjualan_retur/getinfobarang/?kode=" + str + "&cust=" + customer,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#sat' + vid).val(data.satuan1);
                $('#harga' + vid).val(formatCurrency1(data.hargajual));
                totalline(vid);
            }
        });

    }

    function savey() {
        var tanggal = $('[name="tanggal"]').val();
        var nomor = $('[name="nomorbukti"]').val();
        var totalx = $('#_vtotal').text();
        var total = Number(parseInt(totalx.replaceAll(',', '')));
        var gudang = $('[name="gudang"]').val();

        if (nomor == "" || total == "0.00" || total == "" || gudang == "" || gudang == null) {
            swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?= site_url(); ?>Penjualan_retur/update_one/?total=' + total,
                type: 'POST',
                data: $('#frmPenjualan').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data)
                    var nomor = data.nomor;
                    var table = document.getElementById('datatable');
                    rowCount = table.rows.length;
                    for (i = 1; i < rowCount; i++) {
                        var row = table.rows[i];
                        var kode = row.cells[1].children[0].value;
                        var qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
                        var sat = row.cells[3].children[0].value;
                        var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
                        var disc = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
                        var jumlah = Number(row.cells[7].children[0].value.replace(/[^0-9\.]+/g, ""));
                        $.ajax({
                            url: '<?= site_url(); ?>Penjualan_retur/update_multi/?kode=' + kode + '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&disc=' + disc + '&jumlah=' + jumlah + '&nobukti=' + nomor,
                            type: 'POST',
                            data: $('#frmPenjualan').serialize(),
                            dataType: 'JSON',
                            // success: function(data){
                            //     console.log(data)
                            // }
                        });
                    }
                    swal({
                        title: "RETUR PENJUALAN",
                        html: "<p> No. Bukti   : <b>" + nomor + "</b> </p>" + "Tanggal :  " + tanggal + "</b> </p>" + "Total Biaya:" + " " + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>penjualan_retur";
                    });
                }
            });
        }
    }

    function save() {
        var tanggal = $('[name="tanggal"]').val();
        var nomor = $('[name="nomorbukti"]').val();
        var total = $('#_vtotal').text();
        var gudang = $('[name="gudang"]').val();

        if (nomor == "" || total == "0.00" || total == "" || gudang == "" || gudang == null) {
            swal('RETUR PENJUALAN', 'Data Belum Lengkap/Belum ada transaksi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('penjualan_retur/save/2') ?>',
                data: $('#frmPenjualan').serialize(),
                type: 'POST',

                success: function(data) {
                    // console.log(data);
                    swal({
                        title: "RETUR PENJUALAN",
                        html: "<p> No. Bukti   : <b>" + nomor + "</b> </p>" +
                            "Tanggal :  " + tanggal + "</b> </p>" + "Total Biaya:" + " " + total,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>penjualan_retur";
                    });

                },
                error: function(data) {
                    swal('RETUR PENJUALAN', 'Data gagal disimpan ...', '');


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

    var pajak = <?= $pajak; ?>;

    function total() {

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;

        tjumlah = 0;
        tdiskon = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            jumlah = row.cells[2].children[0].value;
            harga = row.cells[4].children[0].value;
            diskon = row.cells[6].children[0].value;
            var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
            var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
            var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
            row.cells[7].children[0].value = separateComma((jumlah1 * harga1 - diskon1).toFixed(0));

            tjumlah = tjumlah + eval(jumlah1 * harga1);

            diskonrp = jumlah1 * harga1 - diskon1;


            // diskon         = eval((diskon1/100)*jumlah1*harga1);
            diskon = eval(diskon1);
            tdiskon += diskon1;


        }
        var ppn_ = $("#_ppn").val();

        tjumlah2 = (tjumlah - tdiskon) / ppn_;

        tppn2 = (tjumlah - tdiskon) * pajak;

        tppn = 0;

        var xyz = tjumlah - tdiskon;

        var ppnx = tjumlah * pajak;

        var tot = tjumlah + tdiskon + tppn;

        var totx = xyz;
        // console.log(totx);

        var ttl = tjumlah + tppn;
        var new_ttot = separateComma(Number(tjumlah).toFixed(0));
        var new_xyz = separateComma(Number(xyz).toFixed(0));
        var new_ppnx = separateComma(Number(ppnx).toFixed(0));
        var new_tjumlah = separateComma(Number(tjumlah2).toFixed(0));
        var new_tdiskon = separateComma(Number(tdiskon).toFixed(0));
        var new_tppn2 = separateComma(Number(tppn2).toFixed(0));
        var new_ttl = separateComma(Number(ttl).toFixed(0));
        var new_totx = separateComma(Number(totx).toFixed(0));

        // document.getElementById("_vsubtotal").innerHTML = new_tjumlah;
        document.getElementById("_vsubtotal").innerHTML = new_ttot;
        document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
        // document.getElementById("_vppn").innerHTML = new_tppn2;
        document.getElementById("_vppn").innerHTML = separateComma(tppn2.toFixed(0));
        // document.getElementById("_vtotal").innerHTML = new_ttl;
        document.getElementById("_vtotal").innerHTML = new_totx;

        if (tot > 0) {
            document.getElementById("btnsimpan").disabled = false;
            //  document.getElementById("btncetak").disabled=false;
        } else {
            document.getElementById("btnsimpan").disabled = true;
            //  document.getElementById("btncetak").disabled=true;
        }




    }

    function totalline(id) {

        // var table = document.getElementById('datatable');
        // var row = table.rows[id];
        // var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
        // jumlah = row.cells[2].children[0].value * harga;
        // diskon = (row.cells[6].children[0].value / 100) * jumlah;
        // tot = jumlah - diskon;
        // row.cells[7].children[0].value = formatCurrency1(tot);
        // total();

        var table = document.getElementById('datatable');
        var rowCount = table.rows.length;
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            var qty = Number(row.cells[2].children[0].value.replace(/[^0-9\.]+/g, ""));
            var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
            var diskon = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
            tot = (qty * harga) - diskon;
            row.cells[7].children[0].value = separateComma(tot);
            total();
        }

    }



    function _urlcetak() {
        var baseurl = "<?php echo base_url() ?>";
        var param = $('[name="nomorbukti"]').val();
        return baseurl + 'penjualan_retur/cetak/' + param;
    }

    window.onload = function() {
        document.getElementById('nomorbukti').focus();
        var jumdata = <?php echo $jumdata1 + 1; ?>;
        for (i = 1; i < jumdata; i++) {
            totalline(i);
        }
    };

    total();
</script>


</body>

</html>