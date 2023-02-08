<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">APOTEK <small>Stok Opname</small>
        </h3>

        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url(); ?>inventory_tso">Daftar Stok Opname</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">Entri Stok Opname</a>
            </li>
        </ul>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i> Data Baru
        </div>
    </div>

    <div class="portlet-body form">
        <form id="frmpenjualan" class="form-horizontal" method="post">
            <div class="form-body">

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
                            <label class="col-md-3 control-label">Yang Membuat</label>
                            <div class="col-md-6">
                                <input type="text" name="pic" class="form-control" value="<?= $pic; ?>">
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-md-3 control-label">No So</label>
                            <div class="col-md-6">
                                <input type="text" name="noSo" id="noSo" class="form-control">
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gudang</label>
                            <div class="col-md-6">
                                <select id="gudang" name="gudang" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onchange="changegud($(this).val())" onkeypress="return tabE(this,event)"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Jenis <small style="color:red">*</small></label>
                            <div class="col-md-6">
                                <select type="text" name="typestock" class="form-control">
                                    <option value="so" selected>Stock Opname</option>
                                    <option value="adjustment">Adjusment (Penyesuaian)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Yang Menyetujui</label>
                            <div class="col-md-6">
                                <select name="yangsetuju" id="yangsetuju" class="form-control select2_el_farmasi_user_2"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                            <thead class="breadcrumb">
                                <tr>
                                    <th class="title-white" width="20%" style="text-align: center">Kode/Nama Barang</th>
                                    <th class="title-white" width="20%" style="text-align: center">Saldo Akhir</th>
                                    <th class="title-white" width="20%" style="text-align: center">Hasil Hitung Fisik</th>
                                    <th class="title-white" width="10%" style="text-align: center">+/-</th>
                                    <th class="title-white" width="10%" style="text-align: center">Satuan</th>
                                    <th class="title-white" width="20%" style="text-align: center">Yang Merubah</th>
                                </tr>
                                <thead>
                                <tbody>
                                    <tr>
                                        <td width="20%">
                                            <!-- <select name="kode[]" id="kode1" class="select2_el_farmasi_baranggudso form-control" onchange="showbarangname(this.value, 1)"> -->
                                            <select name="kode[]" id="kode1" class="select2_el_farmasi_barangdata form-control" onchange="showbarangname(this.value, 1)">
                                                <option value="">--- Pilih Barang ---</option>
                                            </select>
                                        </td>
                                        <td width="20%"><input name="saldoakhir[]" value="0" id="saldoakhir1" type="text" class="form-control rightJustified" readonly></td>
                                        <td width="20%"><input name="qty[]" value="1" id="qty1" type="text" class="form-control rightJustified" onchange="totalline(1)"></td>
                                        <td width="10%"><input name="plusminus[]" id="plusminus1" type="text" class="form-control rightJustified" readonly>
                                        <td width="10%"><input name="sat[]" id="sat1" type="text" class="form-control "></td>
                                        <td width="20%">
                                            <select name="yangubah[]" id="yangubah1" class="form-control select2_el_farmasi_user"></select>
                                        </td>
                                    </tr>
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

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well">
                            <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                Simpan</button>
                            <div class="btn-group">
                                <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i>
                                    Data Baru</button>
                            </div>
                            <div class="btn-group">
                                <a class="btn red" href="<?php echo base_url('Inventory_tso') ?>"><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                            </div>
                            <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
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
    $(window).on("load", function() {
        initailizeSelect2_farmasi_baranggudso("");
    });

    var idrow = 2;

    function tambah() {
        var x = document.getElementById('datatable').insertRow(idrow);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);
        var td3 = x.insertCell(2);
        var td4 = x.insertCell(3);
        var td5 = x.insertCell(4);
        var td6 = x.insertCell(5);

        // var akun = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow +
        //     ")' class='select2_el_farmasi_baranggudso form-control' ><option value=''>--- Pilih Barang ---</option></select>";
        td1.innerHTML = "<select name='kode[]' id=kode" + idrow + " onchange='showbarangname(this.value," + idrow + ")' class='select2_el_farmasi_barangdata form-control' ><option value=''>--- Pilih Barang ---</option></select>";
        td2.innerHTML = "<input name='saldoakhir[]'    id='saldoakhir" + idrow + "' onchange='totalline(" + idrow + ")' value='0'  type='text' class='form-control rightJustified' readonly>";
        td3.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1'  type='text' class='form-control rightJustified'  >";
        td4.innerHTML = "<input name='plusminus[]'    id=plusminus" + idrow + " onchange='totalline(" + idrow + ")' type='text' class='form-control rightJustified'  readonly>";
        td5.innerHTML = "<input name='sat[]'    id=sat" + idrow + " type='text' class='form-control' >";
        td6.innerHTML = "<select name='yangubah[]' id='yangubah" + idrow + "' class='form-control select2_el_farmasi_user'></select>";
        // initailizeSelect2_farmasi_baranggudso($("#gudang").val());
        initailizeSelect2_farmasi_barangdata();
        initailizeSelect2_farmasi_user();
        idrow++;
    }

    function changegud(param) {
        initailizeSelect2_farmasi_baranggudso(param);
        console.log(param);
    }

    function totalline(id) {
        var qty = $('#qty' + id).val();
        var saldoakhir = $('#saldoakhir' + id).val();
        var art = qty - saldoakhir;
        $('#plusminus' + id).val(art);
    }

    function showbarangname(str, id) {
        var xhttp;
        var gudang = $("#gudang").val();
        var vid = id;
        $.ajax({
            url: "<?= site_url('Inventory_tso/validkan/'); ?>"+str+"/"+gudang,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(data.status == 1){
                    $.ajax({
                        url: "<?php echo base_url(); ?>inventory_tso/getinfobarang/" + str + "/?gudang=" + gudang,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data)
                            $('#sat' + vid).val(data.satuan1);
                            var qty = $('#qty' + vid).val();
                            $('#saldoakhir' + id).val(Math.round(data.salakhir));
                            var salakhirx = Number(parseInt(data.salakhir));
                            if (data.salakhir != null) {
                                var salakhir = salakhirx;
                            } else {
                                var salakhir = 0;
                            }
                            $('#sat' + id).val(data.satuan1);
                            var art = qty - salakhir;
                            $('#plusminus' + id).val(art);
                        }
                    });
                }
            }
        });
    }

    function save() {
        var noform = $('[name="pic"]').val();
        var tanggal = $('[name="tanggal"]').val();
        var setuju = $('#yangsetuju').val();
        if (noform == "") {
            swal('STOK OPNAME', 'Petugas belum diisi ...', '');
        } else {
            $.ajax({
                url: '<?php echo site_url('inventory_tso/save/1?setuju=') ?>' + setuju,
                data: $('#frmpenjualan').serialize(),
                type: 'POST',
                success: function(data) {
                    swal({
                        title: "STOK OPNAME",
                        html: "<p>Nama Petugas   : <b>" + noform + "</b> </p>" + "Tanggal :  " + tanggal,
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url() ?>inventory_tso";
                    });

                },
                error: function(data) {
                    swal('STOK OPNAME', 'Data gagal disimpan ...', '');
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

        console.log(str);
        if (str == "") {
            hapus();
            $('[id=kode1]').val('');
            $('[id=qty1]').val('');
            $('[id=sat1]').val('');
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>penjualan_pengiriman/getpo/" + str,
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

    // window.onload = function(){
    //         document.getElementById('nomorbukti').focus();
    // };
</script>



</body>

</html>