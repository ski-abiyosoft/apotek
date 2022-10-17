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
            <span class="title-web">Farmasi <small>Pemusnahan Barang Expire</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>farmasi_expire">
                    Daftar Pemusnahan Barang
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Edit Pemusnahan
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>*Edit Data
        </div>


    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                <i class="fa fa-file"></i>
                                Pemusnahan Barang Expire
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal</label>
                                        <div class="col-md-4">
                                            <input id="tanggal" name="tanggal" class="form-control input-medium"
                                                type="date"
                                                value="<?php echo date('Y-m-d', strtotime($header->tgl_ed));?>" />

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Expire No.</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" placeholder="Otomatis" name="nomorbukti"
                                                    class="form-control" value="<?= $header->ed_no;?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang</label>
                                        <div class="col-md-9">
                                            <select id="gudang_asal" name="gudang_asal"
                                                class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..."
                                                onkeypress="return tabE(this,event)">
                                                <?php
																  if($header->gudang){
																  $namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;?>
                                                <option value="<?= $header->gudang;?>"><?= $namagudang;?></option>
                                                <?php }
																?>
                                            </select>

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Keterangan</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="ket"
                                                value="<?= $header->keterangan;?>">

                                        </div>



                                    </div>
                                </div>



                            </div>


                            <div class="row">
                                <div class="col-md-12">

                                    <table id="datatable"
                                        class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                        <thead>
                                            <tr>
                                                <th width="30%" style="text-align: center">Kode/Nama Barang</th>
                                                <th width="10%" style="text-align: center">Kuantitas</th>
                                                <th width="10%" style="text-align: center">Satuan</th>
                                                <th width="10%" style="text-align: center">Harga</th>
                                                <th width="15%" style="text-align: center">Total</th>
                                                <th width="10%" style="text-align: center">Expire</th>
                                                <th width="20%" style="text-align: center">Keterangan</th>

                                            </tr>
                                            <thead>

                                            <tbody>
                                                <?php
													$no=1;
													foreach($detil as $row){ ?>
                                                <tr>
                                                    <td width="30%">
                                                        <select name="kode[]" id="kode<?= $no;?>"
                                                            class="select2_el_farmasi_barang form-control"
                                                            onchange="showbarangname(this.value, 1)">
                                                            <?php
																if($row->kodebarang){
																  $barang = data_master('tbl_barang', array('kodebarang' => $row->kodebarang));?>
                                                            <option value="<?= $row->kodebarang;?>">
                                                                <?= $barang->kodebarang.' | '.$barang->namabarang.' | '.$barang->satuan1;?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>

                                                    <td width="10%"><input name="qty[]" onchange="totalline(<?= $no;?>)"
                                                            value="<?= number_format($row->qty);?>" id="qty<?= $no;?>"
                                                            type="text" class="form-control rightJustified"></td>
                                                    <td width="10%"><input name="sat[]" id="sat<?= $no;?>" type="text"
                                                            value="<?= $row->satuan;?>" class="form-control "
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="10%"><input name="harga[]"
                                                            onchange="totalline(<?= $no;?>)"
                                                            value="<?= number_format($row->hpp);?>" id="harga<?= $no;?>"
                                                            type="text" class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="15%"><input name="total[]"
                                                            onchange="totalline(<?= $no;?>)"
                                                            value="<?= number_format($row->totalrp);?>"
                                                            id="total<?= $no;?>" type="text"
                                                            class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="10%"><input name="expire[]" id="expire<?= $no;?>"
                                                            type="date"
                                                            value="<?= date('Y-m-d',strtotime($row->exp_date));?>"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="10%"><input name="note[]" id="note<?= $no;?>" type="text"
                                                            value="<?= $row->rakno;?>" class="form-control "
                                                            onkeypress="return tabE(this,event)"></td>
                                                </tr>
                                                <?php $no++;} ?>
                                            </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align:right">TOTAL</td>
                                                <td><input type="text" class="form-control rightJustified" id="vtotal"
                                                        readonly></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
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

                            </div>

                        </div>
                        <!-- tab2-->

                    </div>
                    <!--tab-->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="well">


                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>

                                <div class="btn-group">
                                    <button type="button" class="btn green"
                                        onclick="this.form.reset();location.reload();"><i
                                            class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('farmasi_expire/')?>">
                                        <i class="fa fa-undo"></i><b> KEMBALI </b>
                                    </a>
                                </div>
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                        id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
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

function tambah() {
    var x = document.getElementById('datatable').insertRow(idrow);
    var td1 = x.insertCell(0);
    var td2 = x.insertCell(1);
    var td3 = x.insertCell(2);
    var td4 = x.insertCell(3);
    var td5 = x.insertCell(4);
    var td6 = x.insertCell(5);
    var td7 = x.insertCell(6);

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ")' class='select2_el_farmasi_barang form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " type='text' class='form-control' >";
    td5.innerHTML = "<input name='total[]'  id=total" + idrow + " type='text' class='form-control rightJustified' >";
    td6.innerHTML = "<input name='expire[]' id=expire" + idrow + " type='date' class='form-control' >";
    td7.innerHTML = "<input name='note[]'   id=note" + idrow + " type='text' class='form-control' >";
    initailizeSelect2_farmasi_barang();
    total();
    idrow++;
}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>farmasi_expire/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(separateComma(data.hpp));
            totalline(vid);
        }
    });


}


function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || total == "" || total == "0.00") {
        swal('PEMUSNAHAN BARANG EXPIRE', 'gudang belum diisi ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('farmasi_expire/save/2')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "PEMUSNAHAN BARANG EXPIRE",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal + "<br>" + "Total:" + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>farmasi_expire";
                });

            },
            error: function(data) {
                swal('PEMUSNAHAN BARANG EXPIRE', 'Data gagal disimpan ...', '');
            }
        });
    }
}

function hapus() {
    if (idrow > 2) {
        var x = document.getElementById('datatable').deleteRow(idrow - 1);
        idrow--;
    }
}




function showpo() {
    var xhttp;
    var str = $('[name="cust"]').val();

    if (str == "") {
        document.getElementById("kodeso").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("kodeso").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pengiriman/getlistpo/" + str, true);
    xhttp.send();
}

function getpo() {
    var xhttp;
    var str = $('[name=kodeso]').val();
    if (str == "") {
        hapus();
        $('[id=kode1]').val('');
        $('[id=qty1]').val('');
        $('[id=sat1]').val('');
    } else {
        $.ajax({
            url: "<?php echo base_url();?>penjualan_pengiriman/getpo/" + str,
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

                    var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i]
                        .namabarang);
                    $('#kode' + x).append(option).trigger('change');

                    document.getElementById("qty" + x).value = data[i].sisa;
                    document.getElementById("sat" + x).value = data[i].satuan;
                }




            }
        });
    }
}

window.onload = function() {
    document.getElementById('nomorbukti').focus();
};

total();

function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;

    row.cells[4].children[0].value = separateComma(jumlah);
    total();
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

    tjumlah = 0;
    for (var i = 1; i < rowCount - 1; i++) {
        var row = table.rows[i];

        ztotal = row.cells[4].children[0].value;

        var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1);

    }

    document.getElementById("vtotal").value = separateComma(tjumlah);


}
</script>



</body>

</html>