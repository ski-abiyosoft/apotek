<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>


<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>&nbsp;</span>
            -
            <span class="title-web"><?= $modul;?> <small><?= $submodul;?></small> &nbsp</span>
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
                    Edit Data
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i><b>*Tampil Data</b>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Vendor
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="supp" name="supp" class="form-control select2_el_vendor"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)"
                                                readonly="true" disabled>
                                                <?php 
																  if($header->vendor_id){ 
																   $datavendor = data_master('tbl_vendor',array('vendor_id' => $header->vendor_id)); ?>
                                                <option value="<?= $header->vendor_id;?>">
                                                    <?= $datavendor->vendor_name;?></option>
                                                <?php }
																?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang / Depo <font color="red">*</font>
                                        </label>
                                        <div class="col-md-9">
                                            <select id="gudang" name="gudang"
                                                class="form-control select2_el_logistik_depo"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)" disabled>

                                                <?php 
																if($header->gudang){ 
																$datavendor = data_master('tbl_depo',array('depocode' => $header->gudang)); ?>
                                                <option value="<?= $header->gudang;?>">
                                                    <?= $datavendor->keterangan;?></option>
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
                                        <label class="col-md-3 control-label">Tanggal PO
                                            <font color="red">*</font>

                                        </label>
                                        <div class="col-md-4">

                                            <input id="tanggal" name="tanggal" class="form-control input-medium"
                                                type="date"
                                                value="<?php echo date('Y-m-d', strtotime($header->po_date));?>" readonly/>

                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Ref
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" placeholder=""
                                                name="noref" id="noref" value="<?= $header->ref_no;?>" readonly>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal Kirim</label>
                                        <div class="col-md-4">
                                            <input id="tanggalkirim" name="tanggalkirim"
                                                class="form-control input-medium" type="date"
                                                value="<?php echo date('Y-m-d', strtotime($header->ship_date));?>" readonly/>
                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kurs</label>
                                        <div class="col-md-4">
                                            <input type="text" name="kurs" class="form-control"
                                                value="<?= $header->kurs;?>" readonly>
                                        </div>
                                        <label class="col-md-2 control-label">Rate</label>
                                        <div class="col-md-3">
                                            <input type="text" name="rate" class="form-control"
                                                value="<?= $header->kursrate;?>" readonly>
                                        </div>

                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Dikirim Via</label>
                                        <div class="col-md-4">

                                            <select name="dikirimvia" id="dikirimvia" value="<?= $header->ship_via;?>"
                                                class="form-control" disabled>
                                                <option <?= $header->ship_via == 'Call' ? 'selected':'' ;?>
                                                    value="Call">
                                                    Call
                                                </option>
                                                <option <?= $header->ship_via == 'Reff PBF' ? 'selected':'' ;?>
                                                    value="Reff PBF">Reff PBF</option>
                                                <option <?= $header->ship_via == 'WA' ? 'selected':'' ;?> value="WA">
                                                    WA</option>
                                                <option <?= $header->ship_via == 'SMS' ? 'selected':'' ;?> value="SMS">
                                                    SMS</option>
                                                <!-- <option <?= $header->ship_via == 'SMS' ? 'selected':'' ;?>value=" SMS ">
                                                    SMS</option> -->
                                                <option <?= $header->ship_via == 'Email' ? 'selected':'' ;?>
                                                    value="Email">Email</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor PO #</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-medium" name="nomorbukti"
                                                id="nomorbukti" value="<?= $nomorpo;?>" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">

                                        <thead class="breadcrumb">
                                            <th class="title-white" width="35%" style="text-align: center">Nama Barang +
                                                Qty Stock All Depo/Unit</th>
                                            <th class="title-white" width="15%" style="text-align: center">Kuantitas
                                            </th>
                                            <th class="title-white" width="15%" style="text-align: center">Satuan</th>
                                            <th class="title-white" width="15%" style="text-align: center">Harga</th>
                                            <!-- <th class="title-white" width="10%" style="text-align: center" hidden="true">Diskon</th>
														<th class="title-white" width="5%" style="text-align: center" hidden="true">Tax</th> -->
                                            <th class="title-white" width="15%" style="text-align: center">Total Harga
                                            </th>

                                        </thead>

                                        <tbody>
                                            <?php
													$no=1;
													foreach($detil as $row){?>
                                            <tr>

                                                <td>
                                                    <select name="kode[]" id="kode<?php echo $no;?>"
                                                        class="select2_el_log_baranggud form-control input-largex"
                                                        onchange="showbarangname(this.value, <?= $no ?>)" disabled>
                                                        <option value="<?= $row->kodebarang;?>"><?= $row->namabarang;?>
                                                        </option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input name="qty[]" onchange="totalline(<?php echo $no;?>);total()"
                                                        value="<?= $row->qty_po;?>" id="qty<?php echo $no;?>"
                                                        type="text" class="form-control rightJustified" readonly>
                                                </td>
                                                <td>
                                                    <input name="sat[]" id="sat<?php echo $no;?>" type="text"
                                                        class="form-control " value="<?= $row->satuan;?>"
                                                        onkeypress="return tabE(this,event)" readonly>
                                                </td>
                                                <td>
                                                    <input name="harga[]"
                                                        onchange="totalline(<?php echo $no;?>);total()"
                                                        value="<?= number_format($row->price_po);?>"
                                                        id="harga<?php echo $no;?>" type="text"
                                                        class="form-control rightJustified" readonly>
                                                </td>
                                                <!-- <td  >
															<input name="disc[]"   onchange="totalline(<?php echo $no;?>);total()" value="<?= $row->discount;?>" id="disc<?php echo $no;?>" type="text" class="form-control rightJustified "  >
														</td>
                                                        <td>
															<input type="checkbox" name="tax[]" id="tax<?php echo $no;?>" class="form-control" <?= ($row->vat=='1'?'checked':'')?> onchange="totalline(<?php echo $no;?>);total()">
														</td> -->

                                                <td>
                                                    <input name="jumlah[]" id="jumlah<?php echo $no;?>" type="text"
                                                        value="<?= number_format($row->total);?>"
                                                        class="form-control rightJustified" size="40%" readonly>
                                                </td>

                                            </tr>
                                            <?php $no++; } ?>
                                        </tbody>
                                    </table>


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
                                    <a class="btn red" href="<?php echo base_url('logistik_po')?>"><i
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
var gud = $('#gudang').val();
initailizeSelect2_log_baranggud();

var idrow = <?php echo $jumdata+1;?>;
var rowCount;
var arr = [1];

function tambah() {

    var gud = $('#gudang').val();
    var table = document.getElementById('datatable');
    rowCount = table.rows.length;
    arr.push(idrow);

    var x = document.getElementById('datatable').insertRow(rowCount);
    var td0 = x.insertCell(0);
    var td1 = x.insertCell(1);
    var td2 = x.insertCell(2);
    var td3 = x.insertCell(3);
    var td4 = x.insertCell(4);
    td0.innerHTML = "<select name='kode[]' id=kode" + idrow +
        " class='select2_el_log_baranggud form-control input-largex' onchange='showbarangname(this.value, " + idrow +
        ")'></select>";
    td1.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td2.innerHTML = "<input name='sat[]' id=sat" + idrow + " type='text' class='form-control'  readonly>";
    td3.innerHTML = "<input name='harga[]' id=harga" + idrow + " onchange='totalline(" + idrow +
        ") value='0'  type='text' class='form-control rightJustified' readonly>";
    td4.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%' readonly>";
    initailizeSelect2_log_baranggud(gud);
    idrow++;
}


function showbarangname(str, id) {

    var xhttp;
    var vid = id;
    var gudang = $("#gudang").val();
    $.ajax({
        url: "<?php echo base_url();?>logistik_po/getinfobarang/?kode=" + str + '&gudang=' + gudang,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data)
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hargabeli));
            totalline(vid);
        }
    });


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

function save() {
    var tanggal = $('[name="tanggal"]').val();
    var nomor = $('[name="nomorbukti"]').val();
    var total = $('#_vtotal').text();

    if (nomor == "" || total == "0.00" || total == "") {
        swal('PURCHASE ORDER', 'Data Belum Lengkap/Belum ada transaksi ...', '');
    } else {

        $.ajax({
            url: "<?php echo site_url('Logistik_po/ajax_add/2')?>",
            data: $('#frmpembelian').serialize(),
            type: 'POST',
            success: function(data) {
                data1 = JSON.parse(data);
                swal({
                    title: "PURCHASE ORDER",
                    html: "<p> No. Bukti   : <b>" + data1.nomor + "</b> </p>" +
                        "Tanggal :  " + tanggal + "<b> </p>" + "Total:" + " " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>logistik_po";
                });

            },
            error: function(data) {
                swal('PESANAN PEMBELIAN', 'Data gagal disimpan ...', '');
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
    var x = document.getElementById('datatable').deleteRow(arr.indexOf(param));
    arr.splice(arr.indexOf(param));
    if (param != 1) {
        rowCount--;
    }
    total();
}

function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;
    tjumlah = 0;
    tdiskon = 0;
    tppn = 0;

    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[1].children[0].value;
        harga = row.cells[3].children[0].value;
        diskon = row.cells[4].children[0].value;


        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1 * harga1);

        // diskon      = eval((diskon1/100)*jumlah1*harga1);

        // tdiskon  = tdiskon + diskon;

        // if(document.getElementById('tax'+i).checked==true){
        // 	tppn = tppn + (eval(jumlah1*harga1)*0.1);
        // } 



    }

    //tppn = 0;
    document.getElementById("_vsubtotal").innerHTML = separateComma(tjumlah.toFixed(0));
    document.getElementById("_vdiskon").innerHTML = separateComma(tdiskon.toFixed(0));
    document.getElementById("_vppn").innerHTML = separateComma(tppn.toFixed(0));
    document.getElementById("_vtotal").innerHTML = separateComma((tjumlah - tdiskon + tppn).toFixed(0));


}

function totalline(id) {
    var table = document.getElementById('datatable');
    // var row = table.rows[arr.indexOf(id) + 1];
    var row = table.rows[id];

    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;
    //    diskon      = (row.cells[4].children[0].value/100)* jumlah;

    //    tot         = jumlah - diskon;


    //    if(document.getElementById('tax'+id).checked==true){	  
    // 	  tot = tot*1.1;
    //    } 

    row.cells[4].children[0].value = separateComma(jumlah.toFixed(0));
    total();



}


total();
</script>


</body>

</html>