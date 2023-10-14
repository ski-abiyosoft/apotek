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
            <span class="title-web">APOTEK <small>Mutasi Barang Antar Gudang</small>
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
                <a href="<?php echo base_url();?>inventory_mutasi_gudang">
                    Daftar Mutasi
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Update Mutasi Antar Gudang
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>*Data Update
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
                                Mutasi Barang
                            </a>
                        </li>
                        <!--li class="">
								<a href="#tab2" data-toggle="tab">                                   
								   <i class="fa fa-info-circle"></i>
								   Info
								</a>
							</li-->
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
                                                value="<?php echo date('Y-m-d', strtotime($header->movedate));?>" />

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Transfer No.</label>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Otomatis" name="nomorbukti"
                                                class="form-control" value="<?= $header->moveno;?>" readonly>

                                        </div>
                                    </div>
                                </div>



                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang Asal</label>
                                        <div class="col-md-9">
                                            <select id="gudang_asal" name="gudang_asal"
                                                class="form-control select2_el_farmasi_depo" onchange="getpermohonan()"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <?php
																  if($header->dari){
																  $namagudang = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan;?>
                                                <option value="<?= $header->dari;?>"><?= $namagudang;?></option>
                                                <?php }
																?>
                                            </select>

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Permohonan</label>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Otomatis" name="nomohon"
                                                class="form-control" value="<?= $header->mohonno;?>" readonly>

                                        </div>



                                    </div>
                                </div>



                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Gudang Tujuan</label>
                                        <div class="col-md-9">
                                            <select id="gudang_tujuan" name="gudang_tujuan"
                                                class="form-control select2_el_farmasi_depo" onchange="getpermohonan()"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <?php
																if($header->ke){
																  $namagudang = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan;?>
                                                <option value="<?= $header->ke;?>"><?= $namagudang;?></option>
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
                                                <th width="10%" style="text-align: center">Tgl. Kadaluarsa</th>
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
                                                            class="select2_el_farmasi_baranggud form-control"
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
                                                            value="<?= number_format($row->qtymove);?>"
                                                            id="qty<?= $no;?>" type="text"
                                                            class="form-control rightJustified"></td>
                                                    <td width="10%"><input name="sat[]" id="sat<?= $no;?>" type="text"
                                                            value="<?= $row->satuan;?>" class="form-control "
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="10%"><input name="harga[]"
                                                            onchange="totalline(<?= $no;?>)"
                                                            value="<?= number_format($row->harga);?>"
                                                            id="harga<?= $no;?>" type="text"
                                                            class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="15%"><input name="total[]"
                                                            onchange="totalline(<?= $no;?>)"
                                                            value="<?= number_format($row->totalharga);?>"
                                                            id="total<?= $no;?>" type="text"
                                                            class="form-control rightJustified"
                                                            onkeypress="return tabE(this,event)"></td>
                                                    <td width="10%"><input name="expire[]" id="expire<?= $no;?>"
                                                            type="date"
                                                            value="<?= date('Y-m-d',strtotime($row->exp_date));?>"
                                                            class="form-control " onkeypress="return tabE(this,event)">
                                                    </td>
                                                    <td width="10%"><input name="note[]" id="note<?= $no;?>" type="text"
                                                            value="<?= $row->keterangan;?>" class="form-control "
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


                                <button type="button" onclick="savey()" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>

                                <div class="btn-group">
                                    <button type="button" class="btn green"
                                        onclick="this.form.reset();location.reload();"><i
                                            class="fa fa-pencil-square-o"></i> Data Baru</button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn red" href="<?php echo base_url('Inventory_mutasi_gudang/')?>">
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
$(window).on("load", function() {
    var gud = $("#gudang_asal").val();
    initailizeSelect2_farmasi_baranggud(gud);
});
var idrow = <?= $jumdata + 1 ?>;

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
        ")' class='select2_el_farmasi_baranggud form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow + " type='text' class='form-control' >";
    td5.innerHTML = "<input name='total[]'    id=total" + idrow + " type='text' class='form-control rightJustified' >";
    td6.innerHTML = "<input name='expire[]'    id=expire" + idrow + " type='date' class='form-control' >";
    td7.innerHTML = "<input name='note[]'    id=note" + idrow + " type='text' class='form-control' >";
    var gud = $("#gudang_asal").val();
    initailizeSelect2_farmasi_baranggud(gud);
    total();
    idrow++;
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
            $('#harga' + vid).val(formatCurrency1(data.hargajual));
            totalline(vid);
        }
    });
}

function savey() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var gudang_tujuan = $('[name="gudang_tujuan"]').val();
    var ttl = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    var table = document.getElementById('datatable');
    rowCount = table.rows.length;

    for(x = 1; x < (rowCount - 1); x++) {
        expire = $('#expire' + x).val();
        if(expire == "" || expire == null) {
            swal({
                title: "EXPIRE",
                html: "Tidak boleh kosong",
                type: "error",
                confirmButtonText: "OK"
            })
            return
        }
    }
    $.ajax({
        url: "<?= site_url('inventory_mutasi_gudang/update_one') ?>",
        data: $('#frmpenjualan').serialize(),
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data.status == 1) {
                for (i = 1; i < rowCount - 1; i++) {
                    var kode = $('#kode' + i).val();
                    var qty = $('#qty' + i).val();
                    var sat = $('#sat' + i).val();
                    var hargax = $('#harga' + i).val();
                    var harga = Number(parseInt(hargax.replaceAll(',', '')));
                    var totalx = $('#total' + i).val();
                    var total = Number(parseInt(totalx.replaceAll(',', '')));
                    var expire = $('#expire' + i).val();
                    var note = $('#note' + i).val();
                    //     console.log('kode : '+kode+', qty : '+qty+', sat : '+sat+', harga : '+harga+', total : '+total+', expire : '+expire+', note : '+note)
                    $.ajax({
                        url: "<?= site_url('inventory_mutasi_gudang/update_multi/?kode='); ?>" +
                            kode + '&qty=' + qty + '&sat=' + sat + '&harga=' + harga + '&total=' +
                            total + '&expire=' + expire + '&note=' + note + '&nomorbukti=' + data
                            .nomorbukti + '&keterangan=' + data.keterangan,
                        data: $('#frmpenjualan').serialize(),
                        type: 'POST',
                        dataType: 'JSON',
                    });
                }
                swal({
                    title: "MUTASI GUDANG",
                    html: "No. Mutasi : " + data.nomorbukti + "</b> </p>Tanggal : " + tanggal +
                        "</b> </p>Total : " + ttl,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>inventory_mutasi_gudang";
                });
            }
        }
    });
}

function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var gudang_tujuan = $('[name="gudang_tujuan"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || gudang_tujuan == "" || total == "" || total == "0.00") {
        swal('MUTASI GUDANG', 'gudang belum diisi ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('inventory_mutasi_gudang/save/2')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "MUTASI GUDANG",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>inventory_mutasi_gudang";
                });

            },
            error: function(data) {
                swal('MUTASI GUDANG', 'Data gagal disimpan ...', '');
            }
        });
    }
}

function hapus() {
    if (idrow > <?= $jumdata + 1 ?>) {
        var x = document.getElementById('datatable').deleteRow(idrow - 1);
        idrow--;
    }
}

total();

function totalline(id) {
    var table = document.getElementById('datatable');
    var row = table.rows[id];
    var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
    jumlah = row.cells[1].children[0].value * harga;

    row.cells[4].children[0].value = formatCurrency1(jumlah);
    total();
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

    document.getElementById("vtotal").value = formatCurrency1(tjumlah);


}

function checkstock(param) {
    var gudang = $("#gudang_asal").val();
    var kodebarang = $("#" + param).val();

    $.ajax({
        url: "/farmasi_pbb/checkstock/?kode=" + kodebarang + "&gudang=" + gudang,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(kodebarang + " - " + gudang)
            if (data.status == 0) {
                swal({
                    title: "Kesalahan",
                    html: "Terdapat Barang Yang Kosong",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $(".save").prop("disabled", true);
            } else
            if (data.stock == 0) {
                swal({
                    title: "Kesalahan",
                    html: "Stock Tidak Cukup",
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $(".save").prop("disabled", true);
            } else {
                $(".save").prop("disabled", false);
            }
        }
    });
}
</script>



</body>

</html>