<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Logistik <small>Pemakaian Logistik</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:#fff" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">Awal</a>
                <i style="color:#fff" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url();?>logistik_keluar">Daftar Pemakaian</a>
                <i style="color:#fff" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">Detail Pemakaian</a>
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
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">

                <br />

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-4">
                                <input id="tanggal" name="tanggal" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d', strtotime($header->pakaidate));?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Logistik No.</label>
                            <div class="col-md-6">
                                <input type="text" placeholder="Otomatis" name="nomorbukti" class="form-control"
                                    value="<?= $header->pakaino;?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gudang</label>
                            <div class="col-md-9">
                                <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo"
                                    data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                    <?php
										if($header->gudang){
										$namagudang = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;
									?>
                                    <option value="<?= $header->gudang;?>"><?= $namagudang;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ket" name="ket"
                                    value="<?= $header->keterangan;?>">
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable"
                            class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                            <thead>
                                <tr>
                                    <th width="50%" style="text-align: center">Kode/Nama Barang</th>
                                    <th width="10%" style="text-align: center">Kuantitas</th>
                                    <th width="10%" style="text-align: center">Satuan</th>
                                    <th width="15%" style="text-align: center">Harga</th>
                                    <th width="15%" style="text-align: center">Total</th>
                                </tr>
                                <thead>
                                <tbody>
                                    <?php
									$no=1;
									foreach($detil as $row){
								?>
                                    <tr>
                                        <td>
                                            <select name="kode[]" id="kode<?= $no;?>"
                                                class="select2_el_log_barang form-control"
                                                onchange="showbarangname(this.value, <?= $no ?>)">
                                                <?php
													if($row->kodebarang){
													$barang = data_master('tbl_logbarang', array('kodebarang' => $row->kodebarang));
												?>
                                                <option value="<?= $row->kodebarang;?>">
                                                    <?= $barang->kodebarang.' | '.$barang->namabarang.' | '.$barang->satuan1;?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><input name="qty[]" onchange="totalline(<?= $no;?>)"
                                                value="<?= $row->qty;?>" id="qty<?= $no;?>" type="text"
                                                class="form-control rightJustified"></td>
                                        <td><input name="sat[]" id="sat<?= $no;?>" type="text"
                                                value="<?= $row->satuan;?>" class="form-control "
                                                onkeypress="return tabE(this,event)" readonly></td>
                                        <td><input name="harga[]" onchange="totalline(<?= $no;?>)"
                                                value="<?= number_format($row->harga, 2, ".", ",");?>"
                                                id="harga<?= $no;?>" type="text" class="form-control rightJustified"
                                                onkeypress="return tabE(this,event)" readonly></td>
                                        <td><input name="total[]" onchange="totalline(<?= $no;?>)"
                                                value="<?= number_format($row->total, 2, ".", ",");?>"
                                                id="total<?= $no;?>" type="text" class="form-control rightJustified"
                                                onkeypress="return tabE(this,event)" readonly></td>
                                    </tr>
                                    <?php $no++;} ?>
                                </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:right">TOTAL</td>
                                    <td><input type="text" class="form-control rightJustified" id="vtotal" readonly>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-xs-9">
                                <div class="wells">
                                    <button type="button" onclick="tambah()" class="btn green"><i
                                            class="fa fa-plus"></i> </button>
                                    <button type="button" onclick="hapus()" class="btn red"><i
                                            class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="well">

                                    <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                        Simpan</button>
                                    <div class="btn-group">
                                        <button type="button" class="btn green"
                                            onclick="location.href='/logistik_keluar'"><i
                                                class=" fa fa-pencil-square-o"></i>
                                            Reset</button>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn red" href="<?php echo base_url('Logistik_keluar/')?>">
                                            <i class="fa fa-undo"></i><b> KEMBALI </b>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
        </form>
    </div>
</div>
</div>

<!-- <div class="row">
					<div class="col-xs-12">
						<div class="well">		
							<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							<div class="btn-group">
								<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
							</div>
							<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
						</div>															
					</div>
				</div> -->

</div>
</form>

<!-- <div class="row">
			<div class="col-xs-12">
				<div class="alert alert-danger" style="margin:20px;width:auto">
					<b><i class="fa fa-times-circle fa-fw"></i>&nbsp;Data Tidak Bisa Di Ubah</b>
				</div>
				<button class="btn red btn-sm" type="button" onclick="location.href='<?= $_SERVER['HTTP_REFERER'] ?>'" style="margin:0 20px 20px 20px"><i class="fa fa-times fa-fw"></i>&nbsp;Tutup</button>
			</div>
		</div> -->
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

    var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        ")' class='select2_el_log_barang form-control' ></select>";
    td1.innerHTML = akun;
    td2.innerHTML = "<input name='qty[]'    id=qty" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  >";
    td3.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' readonly>";
    td4.innerHTML = "<input name='harga[]'  id=harga" + idrow +
        " type='text' class='form-control rightJustified' readonly>";
    td5.innerHTML = "<input name='total[]'    id=total" + idrow +
        " type='text' class='form-control rightJustified' readonly>";
    initailizeSelect2_log_barang();
    total();
    idrow++;
}

function showbarangname(str, id) {
    var xhttp;
    var vid = id;
    $.ajax({
        url: "<?php echo base_url();?>permohonan_log/getinfobarang/?kode=" + str,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#sat' + vid).val(data.satuan1);
            $('#harga' + vid).val(formatCurrency1(data.hargabeli));
            totalline(vid);
        }
    });
}


function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || total == "" || total == "0.00" || total == "") {
        swal('PEMAKAIAN LOGISTIK', 'gudang belum diisi ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('logistik_keluar/save/2')?>',
            data: $('#frmpenjualan').serialize(),
            type: 'POST',
            success: function(data) {
                // console.log
                swal({
                    title: "PEMAKAIAN LOGISTIK",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal + "<br>" + "Total: " + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>logistik_keluar";
                });

            },
            error: function(data) {
                swal('PEMAKAIAN LOGISTIK', 'Data gagal disimpan ...', '');
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
</script>

</body>

</html>