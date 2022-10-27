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
            <span class="title-web">Finance <small>Tukar Faktur</small>
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
                <a href="">
                    Finance
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>hutang">
                    Tukar Faktur
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>*Tukar Faktur dan Entry Rencana Bayar
        </div>
    </div>

    <div class="portlet-body form">
        <form id="frm" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Tukar Faktur
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No. Transaksi<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" id="nomor_transaksi" name="nomor_transaksi"
                                                class="form-control rightJustified" value="<?php echo $nomor;?>"
                                                readonly>

                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Vendor<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <select id="vendor" name="vendor" class="form-control select2_el_vendor"
                                                data-placeholder="Pilih..." onchange="hapus_semua()">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Buat<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_buat" name="tanggal_buat" class="form-control rightJustified"
                                                type="date" value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Ambil<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_ambil" name="tanggal_ambil"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Catatan<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <textarea id="catatan" name="catatan" class="form-control" rows="4" cols="50"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Rencana Bayar<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal_rencana_bayar" name="tanggal_rencana_bayar"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Rekening<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <!-- <textarea id="rekening" name="rekening" class="form-control" rows="4" cols="50"></textarea> -->
                                            <select id="rekening" name="rekening" class="form-control select2_el_rekening_vendor"
                                                data-placeholder="Pilih...">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        Filter
                        <hr>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Dari Tanggal<font color="red">*</font></label>
                                    <div class="col-md-6">
                                        <input id="startdate" name="startdate" class="form-control rightJustified"
                                            type="date" value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Sampai Tanggal<font color="red">*</font></label>
                                    <div class="col-md-6">
                                        <input id="enddate" name="enddate"
                                            class="form-control rightJustified" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br>

                        <button type="button" onclick="tambah_tagihan()" class="btn green"><i class="fa fa-plus"></i> Tambah Tagihan</button>
                        &nbsp;&nbsp;&nbsp;<span id="alertVendor" style='color:red;'></span>
                        <br/><br/>
                        <table class="table table-striped table-hover table-bordered" id="">
                            <thead class="breadcrumb">
                                <tr>
                                    <th style="text-align: center">No BPAB/No Transaksi</th>
                                    <th style="text-align: center">No Faktur Supplier</th>
                                    <th style="text-align: center">Tgl Transaksi</th>
                                    <th style="text-align: center">Tgl Jatuh Tempo</th>
                                    <th style="text-align: center">Jumlah Tagihan</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="daftar_tagihan_pilihan"></tbody>
                        </table>
                    </div>
                    <!--tab-->

                    <div class="row form-actions">
                        <div class="col-xs-8">
                            <div class="wells">
                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>
                                <button type="button" class="btn green"
                                    onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i>
                                    Data Baru</button>
                                <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i
                                        class="fa fa-undo"></i> Kembali</button>
                                <!-- <button type="button" class="btn yellow" onclick=""><i class="fa fa-print"></i> Cetak</button> -->
                                <input type="hidden" id="id" name="id" class="form-control rightJustified">

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
  $this->load->view('template/v_report');
?>

<script>


var data_tagihan;
function tambah_tagihan(id, accountno)
{
    // console.log('cek : ' + accountno);
    save_method = 'update';
    // $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    
    
    var vendor = $('#vendor').val();
    var tanggal_buat = $('#tanggal_buat').val();
    var tanggal_ambil = $('#tanggal_ambil').val();
    var tanggal_rencana_bayar = $('#tanggal_rencana_bayar').val();
    var catatan = $('#catatan').val();
    var rekening = $('#rekening').val();
    var startdate = $('#startdate').val();
    var enddate = $('#enddate').val();
    if(
        vendor === '' || vendor === null ||
        tanggal_buat === '' || tanggal_buat === null ||
        tanggal_ambil === '' || tanggal_ambil === null ||
        tanggal_rencana_bayar === '' || tanggal_rencana_bayar === null ||
        catatan === '' || catatan === null || 
        rekening === '' || rekening === null
    ) {
        vendor = '';
        $('#alertVendor').text('Mohon isi form di atas terlebih dahulu');
    } else {
        
        $('#alertVendor').text('');
        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('hutang_tukarfaktur/getTagihan?vendor=')?>"+vendor+"&startdate="+startdate+"&enddate="+enddate,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {           
                data_tagihan = data;
                var pilih = $('#formDaftarTagihan').serializeArray();
                console.log(pilih);
                
                $('#daftar_tagihan').empty();
                $.each(data, function( key, value ) {

                    var checked = '';
                    var find;
                    find = pilih.some(element => {
                        if ('pilih['+value.terima_no+']' === element.name) {
                            return 'checked';
                        }
                    });

                    if(find) checked = 'checked';

                    var input_checkbox = '';
                    if(value.tukarfaktur == 0) {
                        input_checkbox = "<input type='checkbox' id='pilih["+value.terima_no+"]' name='pilih["+value.terima_no+"]' "+checked+" onclick='hapusAlert()'>";
                    }

                    $('#daftar_tagihan').append("<tr>\
                                <td style='text-align: center'>"+input_checkbox+"</td>\
                                <td style='text-align: center'>"+value.terima_no+"</td>\
                                <td style='text-align: center'>"+value.invoice_no+"</td>\
                                <td style='text-align: center'>"+moment(value.tglinvoice).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+moment(value.duedate).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+currencyFormat(value.totaltagihan)+"</td>\
                                </tr>");
                
                });
                
                $('#modal_form_large').modal('show'); // show bootstrap modal when complete loaded
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
}

var tagihan_terpilih;
function pilih_tagihan(){
    
    console.log($('#daftar_tagihan_pilihan').val());
    var fdt = $('#formDaftarTagihan').serialize();
    if(fdt == '') {
        $('#alertPilih').text('Mohon pilih tagihan terlebih dahulu!');
    } else {
        var vendor = $('#vendor').val();    
        if(vendor === null) vendor = '';
        $.ajax({
            url: '<?php echo site_url('hutang_tukarfaktur/pilih_daftar_tagihan/')?>'+vendor,
            data: $('#formDaftarTagihan').serialize(),
            type: 'POST',
            dataType: "JSON",
            success: function(data) {
                tagihan_terpilih = data;
                $('#daftar_tagihan_pilihan').empty();
                $.each(data, function( key, value ) {
                    $('#daftar_tagihan_pilihan').append("<tr>\
                                    <td style='text-align: center'>"+value.terima_no+"</td>\
                                    <td style='text-align: center'>"+value.invoice_no+"</td>\
                                    <td style='text-align: center'>"+moment(value.tglinvoice).format('DD/MM/YYYY')+"</td>\
                                    <td style='text-align: center'>"+moment(value.duedate).format('DD/MM/YYYY')+"</td>\
                                    <td style='text-align: center'>"+currencyFormat(value.totaltagihan)+"</td>\
                                    <td style='text-align: center'><a href='#' onclick=hapus_tagihan('"+(value.terima_no)+"')>Hapus</a></td>\
                                    <input type='hidden' id='terima_no[]' name='terima_no[]' value='"+value.terima_no+"'>\
                                    <input type='hidden' id='invoice_no[]' name='invoice_no[]' value='"+value.invoice_no+"'>\
                                    <input type='hidden' id='tglinvoice[]' name='tglinvoice[]' value='"+value.tglinvoice+"'>\
                                    <input type='hidden' id='duedate[]' name='duedate[]' value='"+value.duedate+"'>\
                                    <input type='hidden' id='totaltagihan[]' name='totaltagihan[]' value='"+value.totaltagihan+"'>\
                                    </tr>");
                });
            },
            error: function(data) {
                swal('Pilih Daftar Tagihan Gagal', 'Failed ...', '');
            }
        });
        
        $('#modal_form_large').modal('toggle');
    }
}

function hapus_semua(){
    tagihan_terpilih = null;
    // detail = tagihan_terpilih;
    $('#daftar_tagihan_pilihan').empty();
    // $("input[name='pilih[]']").val("");
}

function hapus_tagihan(id){
    var i = 0;
    var index;
    $.each(tagihan_terpilih, function( key, value ) {
        if(value.terima_no === id) index = i;
        i++;
    });

    tagihan_terpilih.splice(index, 1);
    
    $('#daftar_tagihan_pilihan').empty();
    $.each(tagihan_terpilih, function( key, value ) {
        if(value.terima_no != id){
            $('#daftar_tagihan_pilihan').append("<tr>\
                                <td style='text-align: center'>"+value.terima_no+"</td>\
                                <td style='text-align: center'>"+value.invoice_no+"</td>\
                                <td style='text-align: center'>"+moment(value.tglinvoice).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+moment(value.duedate).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+currencyFormat(value.totaltagihan)+"</td>\
                                <td style='text-align: center'><a href='#' onclick=hapus_tagihan('"+(value.terima_no)+"')>Hapus</a></td>\
                                    <input type='hidden' id='terima_no[]' name='terima_no[]' value='"+value.terima_no+"'>\
                                    <input type='hidden' id='invoice_no[]' name='invoice_no[]' value='"+value.invoice_no+"'>\
                                    <input type='hidden' id='tglinvoice[]' name='tglinvoice[]' value='"+value.tglinvoice+"'>\
                                    <input type='hidden' id='duedate[]' name='duedate[]' value='"+value.duedate+"'>\
                                    <input type='hidden' id='totaltagihan[]' name='totaltagihan[]' value='"+value.totaltagihan+"'>\
                                </tr>");
        }
    });

    var index2;
    var j = 0;
    var pilih = $('#formDaftarTagihan').serializeArray();
    console.log(pilih);
    $.each(pilih, function( key, value ) {
        if(value.name === 'pilih['+id+']') index2 = j;
        j++;
    });
    pilih.splice(index2, 1);
    
    $('#daftar_tagihan').empty();
    $.each(data_tagihan, function( key, value ) {
        var checked = '';
        var find;
        find = pilih.some(element => {
            console.log('pilih['+value.terima_no+'] === '+element.name);
            if ('pilih['+value.terima_no+']' === element.name) {
                return 'checked';
            }
        });

        if(find) checked = 'checked';
        $('#daftar_tagihan').append("<tr>\
                <td style='text-align: center'><input type='checkbox' id='pilih' name='pilih["+value.terima_no+"]' "+checked+" onclick='hapusAlert()'></td>\
                <td style='text-align: center'>"+value.terima_no+"</td>\
                <td style='text-align: center'>"+value.invoice_no+"</td>\
                <td style='text-align: center'>"+moment(value.tglinvoice).format('DD/MM/YYYY')+"</td>\
                <td style='text-align: center'>"+moment(value.duedate).format('DD/MM/YYYY')+"</td>\
                <td style='text-align: center'>"+currencyFormat(value.totaltagihan)+"</td>\
                </tr>");
    
    });
        
}

function hapusAlert(){

    $('#alertPilih').text('');
}

function isCharacterALetter(char) {
    return (/[a-zA-Z]/).test(char)
}

$(document).ready(function() {
    $('#jenis_ppn').change(function() {
        hitungPpn($('#jumlah_tagihan').val());
    });
});

$("input[data-type='currency']").on({
    blur: function() {
        var val = this.value.replaceAll(',', '').split('.');
        this.value = currencyFormat(val[0]);
    }
});

function currencyFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function sumTotaltagihan(total, num) {
  return Number(total) + Number(num);
}

function save() {
    // var nomor_transaksi         = $('#nomor_transaksi').val();
    var vendor                  = $('#vendor').val();
    var tanggal_buat            = $('#tanggal_buat').val();
    var tanggal_ambil           = $('#tanggal_ambil').val();
    var catatan                 = $('#catatan').val();
    var tanggal_rencana_bayar   = $('#tanggal_rencana_bayar').val();
    var rekening                = $('#rekening').val();
    var terima_no               = $("input[name='terima_no[]']").map(function(){return $(this).val();}).get();
    var totaltagihan            = $("input[name='totaltagihan[]']").map(function(){return $(this).val();}).get();
    
    // console.log(totaltagihan);
    var sum_total_tagihan = totaltagihan.reduce(sumTotaltagihan);

    // console.log(sum_total_tagihan);
    // console.log($('#frm').serialize());


    if (
        nomor_transaksi == "" ||
        vendor == "" || vendor == null ||
        tanggal_buat == "" || tanggal_buat == null ||
        tanggal_ambil == "" || tanggal_ambil == null ||
        catatan == "" || 
        tanggal_rencana_bayar == "" || tanggal_rencana_bayar == null ||
        rekening == "" || 
        terima_no.length == 0
    ) {
        swal('ENTRI DATA', 'Data Belum Lengkap...', '');
    } else {

        $.ajax({
            url: '<?php echo site_url('hutang_tukarfaktur/save/1')?>',
            data: $('#frm').serialize(),
            type: 'POST',

            success: function(data) {
                console.log(data);
                // console.log(data.rc);

                swal({
                    title: "ENTRI TUKAR FAKTUR",
                    html: "<p> No. Transaksi   : <b>" + data + "</b> </p>" +
                        // "Tanggal :  " + moment(tanggal_buat).format('DD/MM/YYYY'),
                         "<p>Tanggal :  " + moment(tanggal_buat).format('DD/MM/YYYY') + "</p>" +
                            "<p><b>Rp " + currencyFormat(sum_total_tagihan) + "</b></p>",
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>hutang_tukarfaktur";
                });
            },
            error: function(data) {
                swal('ENTRI DATA GAGAL', 'Data gagal disimpan ...', '');
            }
        });
    }
}


function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;

    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[1].children[0].value;
        harga = row.cells[3].children[0].value;
        diskon = row.cells[5].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1 * harga1);

        diskon = eval((diskon1 / 100) * jumlah1 * harga1);

        tdiskon = tdiskon + diskon;


    }

    var table2 = document.getElementById('datatable2');
    var rowCount2 = table2.rows.length;

    tbiaya = 0;

    for (var i = 1; i < rowCount2; i++) {
        var row = table2.rows[i];

        biaya = row.cells[1].children[0].value;
        var biaya1 = Number(biaya.replace(/[^0-9\.]+/g, ""));

        tbiaya = tbiaya + eval(biaya1);
    }

    var cppn = document.getElementById("sppn").value;
    if (cppn == "Y") {
        tppn = (tjumlah - tdiskon) * 0.1;
    } else {
        tppn = 0;
    }


    document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tjumlah);
    document.getElementById("_vdiskon").innerHTML = formatCurrency1(tdiskon);
    document.getElementById("_vbiayalain").innerHTML = formatCurrency1(tbiaya);
    document.getElementById("_vppn").innerHTML = formatCurrency1(tppn);
    document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah - tdiskon + tbiaya + tppn);


}

</script>


<div class="modal fade" id="modal_form_large" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Daftar Tagihan</h3>
            </div>
            <div class="modal-body">
                <form id="formDaftarTagihan" method="post">
                    <button type="button" id="btnSave" onclick="pilih_tagihan()" class="btn btn-primary">Proses Data Terpilih</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    &nbsp;&nbsp;
                    <span id='alertPilih' style='color:red;'></span>
                    <br><br>
                    <!-- <input type='hidden' id='vendor_id' name='vendor_id'></input> -->
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="breadcrumb">
                                <tr>
                                    <th style="text-align: center">Pilih</th>
                                    <th style="text-align: center">No BAPB / No Transaksi</th>
                                    <th style="text-align: center">No Faktur Supplier</th>
                                    <th style="text-align: center">Tgl Transaksi</th>
                                    <th style="text-align: center">Tgl Jatuh Tempo</th>
                                    <th style="text-align: center">Jumlah Tagihan</th>
                                </tr>
                        </thead>
                        <tbody id="daftar_tagihan"></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>



</body>

</html>