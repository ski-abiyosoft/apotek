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
            <span class="title-web">Finance <small>Pembayaran Hutang</small>
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
                <a href="<?php echo base_url();?>pembelian_bayar">
                    Daftar Pembayaran Hutang
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Entri Pembayaran
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
        <form id="frmpembayaran" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Pembayaran
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. AP<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control rightJustified" name="nomorbukti"
                                                id="nomorbukti" value="<?php echo $nomorbukti;?>" readonly>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Kas/Bank<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <!-- select2_el_akundiskonadjust -->
                                            <select id="kasbank" name="kasbank"
                                                class="form-control rightJustified select2_el_kasbank"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">

                                            </select>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Vendor<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select id="vendor" name="vendor"
                                                class="form-control rightJustified select2_el_vendor"
                                                data-placeholder="Pilih..." onchange="hapus_semua()">
                                            </select>
                                            <!-- <span class="input-group-btn">
                                                    <a class="btn blue" onclick="getfaktur()"><i
                                                            class="fa fa-download"></i></a>
                                                </span> -->

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jenis Bayar<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select id="jenis_bayar" name="jenis_bayar"
                                                class="form-control rightJustified select2me"
                                                data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <option value="" disabled selected>Pilih Jenis</option>
                                                <?php
                                                    foreach($jenis_pembayaran as $key){
                                                        echo "<option value='".$key->id."'>".$key->jenis."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal Bayar<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input id="tanggal_bayar" name="tanggal_bayar"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />

                                        </div>



                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Cek/Giro<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control rightJustified" name="cekgiro"
                                                id="cekgiro" value="">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Keterangan<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control rightJustified" name="keterangan"
                                                id="keterangan" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jumlah Bayar<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <!-- <div class="input-group"> -->
                                            <input type="text" class="form-control rightJustified" name="jumlah_bayar"
                                                id="jumlah_bayar" value="0" readonly>
                                            <!-- <span class="input-group-btn">
                                                    <a class="btn green" onclick="total()"><i
                                                            class="fa fa-refresh"></i></a>
                                                </span> -->
                                            <!-- </div> -->

                                        </div>
                                    </div>
                                </div>

                            </div>

                            Filter
                            <hr>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Dari Tanggal<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input id="startdate" name="startdate" class="form-control rightJustified"
                                                type="date" value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Sampai Tanggal<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input id="enddate" name="enddate"
                                                class="form-control rightJustified" type="date"
                                                value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <br>
                            
                            <br /><br />
                            <button type="button" onclick="tambah_data()" class="btn green"><i class="fa fa-plus"></i>
                                Tambah Faktur</button>
                            &nbsp;&nbsp;&nbsp;<span id="alertVendor" style='color:red;'></span>
                            <br /><br />

                            <div class="row">
                                <div class="col-md-12">

                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead class="breadcrumb">
                                            <tr>
                                                <!-- <th style="text-align: center">No BPAB/No Transaksi</th> -->
                                                <th style="text-align: center">No Faktur</th>
                                                <th style="text-align: center">No Tukar Faktur</th>
                                                <th style="text-align: center">Tgl Faktur</th>
                                                <th style="text-align: center">Jatuh Tempo</th>
                                                <th style="text-align: center">Total Faktur Rp</th>
                                                <th style="text-align: center">Jumlah Bayar</th>
                                                <!-- <th style="text-align: center">Diskon Rp</th>
                                                <th style="text-align: center">Akun Diskon</th>
                                                <th style="text-align: center">Adjustment Rp</th>
                                                <th style="text-align: center">Akun Adjustment</th> -->
                                                <th style="text-align: center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="daftar_tagihan_pilihan"></tbody>
                                    </table>


                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- tab1-->



                </div>
                <!--tab-->

                <div class="row">
                    <div class="col-xs-8">
                        <div class="wells">


                            <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                Simpan</button>

                            <div class="btn-group">
                                <button type="button" class="btn green"
                                    onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i>
                                    Data Baru</button>
                            </div>
                            <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i
                                    class="fa fa-undo"></i> Kembali</button>
                            <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                    id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                        </div>
                    </div>

                    <div class="col-xs-4 invoice-block">
                        <div class="well">
                            <table>
                                <!-- <tr>
                                    <td width="40%"><strong>SUB TOTAL</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59" align="right"><strong><span id="_vsubtotal"></span></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%"><strong>DISKON</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59" align="right"><strong><span id="_vdiskon"></span></strong></td>
                                </tr> -->
                                <tr>
                                    <td width="40%"><strong>Jumlah Bayar</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59" align="right"><strong><span id="jumlah_bayar2"></span></strong></td>
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
  $this->load->view('template/currency'); 
?>
<script>
function currencyFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

var data_tagihan;

function tambah_data(id, accountno) {
    // console.log('cek : ' + accountno);
    save_method = 'update';
    // $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    var kasbank = $('#kasbank').val();
    var vendor = $('#vendor').val();
    var jenis_bayar = $('#jenis_bayar').val();
    var tanggal_bayar = $('#tanggal_bayar').val();
    var cekgiro = $('#cekgiro').val();
    var keterangan = $('#keterangan').val();
    var jumlah_bayar = $('#jumlah_bayar').val();
    var startdate = $('#startdate').val();
    var enddate = $('#enddate').val();
    if (
        kasbank === '' || kasbank === null ||
        vendor === '' || vendor === null ||
        jenis_bayar === '' || jenis_bayar === null ||
        cekgiro === '' || jenis_bayar === null ||
        keterangan === '' || jenis_bayar === null ||
        jumlah_bayar === '' || jenis_bayar === null
    ) {
        vendor = '';
        $('#alertVendor').text('Mohon isi form di atas terlebih dahulu');
    } else {

        $('#alertVendor').text('');
        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('pembelian_bayar/getData?vendor=')?>" + vendor+"&startdate="+startdate+"&enddate="+enddate,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                data_tagihan = data;
                var pilih = $('#formDaftarTagihan').serializeArray();
                console.log(pilih);

                $('#daftar_tagihan').empty();
                $.each(data, function(key, value) {

                    var checked = '';
                    var find;
                    find = pilih.some(element => {
                        if ('pilih[' + value.terima_no + ']' === element.name) {
                            return 'checked';
                        }
                    });

                    if (find) checked = 'checked';

                    var input_checkbox = '';
                    if (value.lunas == 0) {
                        input_checkbox = "<input type='checkbox' id='pilih[" + value.terima_no +
                            "]' name='pilih[" + value.terima_no + "]' " + checked +
                            " onclick='hapusAlert()'>";
                    }


                    $('#daftar_tagihan').append("<tr>\
                                <td style='text-align: center'>" + input_checkbox + "</td>\
                                <td style='text-align: center'>" + value.terima_no + "</td>\
                                <td style='text-align: center'>" + value.invoice_no + "</td>\
                                <td style='text-align: center'>" + moment(value.tglinvoice).format('DD/MM/YYYY') + "</td>\
                                <td style='text-align: center'>" + moment(value.duedate).format('DD/MM/YYYY') + "</td>\
                                <td style='text-align: center'>" + currencyFormat(value.totaltagihan) + "</td>\
                                </tr>");

                });

                $('#modal_form_large').modal('show'); // show bootstrap modal when complete loaded
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }
}


var tagihan_terpilih;

function pilih_tagihan() {

    console.log($('#daftar_tagihan_pilihan').val());
    var fdt = $('#formDaftarTagihan').serialize();
    if (fdt == '') {
        $('#alertPilih').text('Mohon pilih tagihan terlebih dahulu!');
    } else {
        var vendor = $('#vendor').val();
        if (vendor === null) vendor = '';
        $.ajax({
            url: '<?php echo site_url('hutang_tukarfaktur/pilih_daftar_tagihan/')?>' + vendor,
            data: $('#formDaftarTagihan').serialize(),
            type: 'POST',
            dataType: "JSON",
            success: function(data) {
                tagihan_terpilih = data;
                $('#daftar_tagihan_pilihan').empty();
                var i = 0;
                $.each(data, function(key, value) {

                    // dihapus sementara
                    // <td style='text-align: center'><input type='text' id='diskonrp[]' name='diskonrp[]' value='0' class='form-control rightJustified' data-type='currency'></td>\
                    // <td style='text-align: center'>\
                    //     <select id='akundiskon" + i + "' name='akundiskon[]' class='form-control  select2_el_akundiskonadjust' data-placeholder='Pilih...' >\
                    //     </select>\
                    // </td>\
                    // <td style='text-align: center'><input type='text' id='adjustment[]' name='adjustment[]' value='0' class='form-control rightJustified' data-type='currency'></td>\
                    // <td style='text-align: center'>\
                    //     <select id='akunjust" + i + "' name='akunjust[]' class='form-control  select2_el_akundiskonadjust' data-placeholder='Pilih...' >\
                    //     </select>\
                    // </td>\

                    $('#daftar_tagihan_pilihan').append("<tr>\
                                    <td style='text-align: center'>" + value.invoice_no + "</td>\
                                    <td style='text-align: center'>" + value.notukar + "</td>\
                                    <td style='text-align: center'>" + moment(value.tglinvoice).format('DD/MM/YYYY') + "</td>\
                                    <td style='text-align: center'>" + moment(value.duedate).format('DD/MM/YYYY') + "</td>\
                                    <td style='text-align: center'>" + currencyFormat(value.totaltagihan) + "</td>\
                                    <td style='text-align: center'><input type='text' id='dibayar[]' name='dibayar[]' value='0' onkeyup='hitungJumlahBayar();' class='form-control rightJustified'  data-type='currency'></td>\
                                    <td style='text-align: center'><a href='#' onclick=hapus_tagihan('" + (value
                            .terima_no) + "')>Hapus</a></td>\
                                    <input type='hidden' id='terima_no[]' name='terima_no[]' value='" + value
                        .terima_no + "'>\
                                    <input type='hidden' id='notukar[]' name='notukar[]' value='" + value
                        .notukar + "'>\
                                    <input type='hidden' id='invoice_no[]' name='invoice_no[]' value='" + value
                        .invoice_no + "'>\
                                    <input type='hidden' id='tglinvoice[]' name='tglinvoice[]' value='" + value
                        .tglinvoice + "'>\
                                    <input type='hidden' id='duedate[]' name='duedate[]' value='" + value.duedate + "'>\
                                    <input type='hidden' id='totaltagihan[]' name='totaltagihan[]' value='" + value
                        .totaltagihan + "'>\
                                    </tr>");

                    i++;
                });


                initailizeSelect2_akundiskonadjust();
                convert_currency2();
            },
            error: function(data) {
                swal('Pilih Daftar Tagihan Gagal', 'Failed ...', '');
            }
        });

        $('#modal_form_large').modal('toggle');

    }
}

function hitungJumlahBayar() {
    var dibayar = $("input[name='dibayar[]']").map(function() {
        return $(this).val();
    }).get();
    console.log(dibayar);
    // console.log(dibayar.reduce(myFunc));
    var jumlah_bayar = dibayar.reduce(myFunc);
    $('#jumlah_bayar').val(currency_format(jumlah_bayar));
    $('#jumlah_bayar2').text(currency_format(jumlah_bayar));
}

function myFunc(total, num) {
    if (num === '') num = '0';

    var num1 = parseInt(total.replaceAll(',', ''));
    var num2 = parseInt(num.replaceAll(',', ''));

    return (num1 + num2);
}

function hapusAlert() {
    $('#alertPilih').text('');
}

function hapus_semua() {
    tagihan_terpilih = null;
    // detail = tagihan_terpilih;
    $('#daftar_tagihan_pilihan').empty();
    // $("input[name='pilih[]']").val("");
}

async function hapus_tagihan(id) {
    var i = 0;
    var index;
    $.each(tagihan_terpilih, function(key, value) {
        if (value.terima_no === id) index = i;
        i++;
    });

    tagihan_terpilih.splice(index, 1);
    dibayar = $("input[name='dibayar[]']").map(function() {
        return $(this).val();
    }).get();
    diskonrp = $("input[name='diskonrp[]']").map(function() {
        return $(this).val();
    }).get();
    akundiskon = $("select[name='akundiskon[]']").map(function() {
        return $(this).val();
    }).get();
    adjustment = $("input[name='adjustment[]']").map(function() {
        return $(this).val();
    }).get();
    akunjust = $("select[name='akunjust[]']").map(function() {
        return $(this).val();
    }).get();

    console.log($("input[name='dibayar[]']").map(function() {
        return $(this).val();
    }).get());
    dibayar.splice(index, 1);
    diskonrp.splice(index, 1);
    akundiskon.splice(index, 1);
    adjustment.splice(index, 1);
    akunjust.splice(index, 1);

    var akundiskon_name = [];
    var akunjust_name = [];
    await $.ajax({
        url: "<?php echo base_url();?>pembelian_bayar/getAkunDiskon/",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);

            for (var i = 0; i < akundiskon.length; i++) {
                $.each(data, function(key, value) {
                    console.log(value.accountno + " | " + value.acname);
                    if (akundiskon[i] === value.accountno) {
                        akundiskon_name.push(value.acname);
                    }
                    if (akunjust[i] === value.accountno) {
                        akunjust_name.push(value.acname);
                    }
                });
            }
        }
    });


    var no = 0;
    $('#daftar_tagihan_pilihan').empty();
    $.each(tagihan_terpilih, function(key, value) {
        if (value.terima_no != id) {

            var tghn;
            if (typeof value.totaltagihan === 'undefined') tghn = value.totalhutang;
            else tghn = value.totaltagihan;

            console.log(value.totalhutang);
            console.log(value.totaltagihan);

            // <td style='text-align: center'><input type='text' id='diskonrp[]' name='diskonrp[]' value='" +
            //     diskonrp[no] + "' class='form-control rightJustified'></td>\
            //                         <td style='text-align: center'>\
            //                             <select id='akundiskon[]' name='akundiskon[]' class='form-control  select2_el_akundiskonadjust' data-placeholder='Pilih...' >\
            //                                 <option value='" + akundiskon[no] + "' selected>" + akundiskon[no] +
            //     " | " + akundiskon_name[no] +
            //     " </option>\
            //                             </select>\
            //                         </td>\
            //                         <td style='text-align: center'><input type='text' id='adjustment[]' name='adjustment[]' value='" +
            //     adjustment[
            //         no] + "' class='form-control'></td>\
            //                         <td style='text-align: center'>\
            //                             <select id='akunjust[]' name='akunjust[]' class='form-control ' data-placeholder='Pilih...' >\
            //                                 <option value='" + akunjust[no] + "' selected>" + akunjust[no] + " | " +
            //     akunjust_name[no] + " </option>\
            //                             </select>\
            //                         </td>\

            $('#daftar_tagihan_pilihan').append("<tr>\
                            <td style='text-align: center'>" + value.invoice_no + "</td>\
                                    <td style='text-align: center'>" + value.notukar + "</td>\
                                    <td style='text-align: center'>" + moment(value.tglinvoice).format('DD/MM/YYYY') + "</td>\
                                    <td style='text-align: center'>" + moment(value.duedate).format('DD/MM/YYYY') + "</td>\
                                    <td style='text-align: center'>" + currencyFormat(value.totaltagihan) +
                "</td>\
                                    <td style='text-align: center'><input type='text' id='dibayar[]' name='dibayar[]'  value='" +
                dibayar[no] +
                "' class='form-control rightJustified'></td>\
                                    <td style='text-align: center'><a href='#' onclick=hapus_tagihan('" + (value
                    .terima_no) + "')>Hapus</a></td>\
                                        <input type='hidden' id='terima_no[]' name='terima_no[]' value='" + value
                .terima_no + "'>\
                                        <input type='hidden' id='invoice_no[]' name='invoice_no[]' value='" + value
                .invoice_no + "'>\
                                        <input type='hidden' id='tglinvoice[]' name='tglinvoice[]' value='" + value
                .tglinvoice + "'>\
                                        <input type='hidden' id='duedate[]' name='duedate[]' value='" + value.duedate + "'>\
                                        <input type='hidden' id='totaltagihan[]' name='totaltagihan[]' value='" + value
                .totaltagihan + "'>\
                                </tr>");
        }
    });

    var index2;
    var j = 0;
    var pilih = $('#formDaftarTagihan').serializeArray();

    $.each(pilih, function(key, value) {
        if (value.name === 'pilih[' + id + ']') index2 = j;
        j++;
    });
    pilih.splice(index2, 1);

    $('#daftar_tagihan').empty();
    $.each(data_tagihan, function(key, value) {
        var checked = '';
        var find;
        find = pilih.some(element => {
            console.log('pilih[' + value.terima_no + '] === ' + element.name);
            if ('pilih[' + value.terima_no + ']' === element.name) {
                return 'checked';
            }
        });

        if (find) checked = 'checked';
        $('#daftar_tagihan').append("<tr>\
                <td style='text-align: center'><input type='checkbox' id='pilih' name='pilih[" + value.terima_no +
            "]' " + checked + " onclick='hapusAlert()'></td>\
                <td style='text-align: center'>" + value.terima_no + "</td>\
                <td style='text-align: center'>" + value.invoice_no + "</td>\
                <td style='text-align: center'>" + moment(value.tglinvoice).format('DD/MM/YYYY') + "</td>\
                <td style='text-align: center'>" + moment(value.duedate).format('DD/MM/YYYY') + "</td>\
                <td style='text-align: center'>" + currencyFormat(value.totaltagihan) + "</td>\
                </tr>");

    });

}


function save() {
    var nomor_bukti = $('#nomor_bukti').val();
    var vendor = $('#vendor').val();
    var kasbank = $('#kasbank').val();
    var jenis_bayar = $('#jenis_bayar').val();
    var tanggal_bayar = $('#tanggal_bayar').val();
    var cekgiro = $('#cekgiro').val();
    var keterangan = $('#keterangan').val();
    var terima_no = $("input[name='terima_no[]']").map(function() {
        return $(this).val();
    }).get();
    var dibayar = $("input[name='dibayar[]']").map(function() {
        return $(this).val();
    }).get();
    var diskonrp = $("input[name='diskonrp[]']").map(function() {
        return $(this).val();
    }).get();
    var akundiskon = $("input[name='akundiskon[]']").map(function() {
        return $(this).val();
    }).get();
    var adjustment = $("input[name='adjustment[]']").map(function() {
        return $(this).val();
    }).get();
    var akunjust = $("input[name='akunjust[]']").map(function() {
        return $(this).val();
    }).get();

    var jumlah_bayar = $('#jumlah_bayar').val();

    console.log(akundiskon);
    console.log(akunjust);
    console.log(dibayar);


    if (
        nomor_bukti == "" ||
        vendor == "" || vendor == null ||
        kasbank == "" || kasbank == null ||
        jenis_bayar == "" || jenis_bayar == null ||
        tanggal_bayar == "" || tanggal_bayar == null ||
        cekgiro == "" || cekgiro == null ||
        keterangan == "" ||
        terima_no.length == 0 ||
        dibayar.length == 0 
        // ||
        // diskonrp.length == 0 ||
        // adjustment.length == 0




        // akundiskon.length == 0 ||
        // akunjust.length == 0
    ) {
        swal('Pembayaran Hutang', 'Data belum diisi ...', '');
    } else {
        console.log('masuk');
        console.log($('#frmpembayaran').serialize());
        console.log(terima_no);

        $.ajax({
            url: '<?php echo site_url('pembelian_bayar/save/1')?>',
            data: $('#frmpembayaran').serialize(),
            type: 'POST',

            success: function(data) {

                console.log(data);
                var dt = JSON.parse(data);
                if (dt.status === true) {
                    swal({
                        title: "Pembayaran Hutang",
                        html: "<p> No. Bukti   : <b>" + dt.data + "</b> </p>" +                            
                            "<p>Tanggal :  " + moment(tanggal_bayar).format('DD/MM/YYYY') + "</p>" +
                            "<p><b>Rp " + jumlah_bayar + "</b></p>",
                        type: "info",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?php echo base_url()?>pembelian_bayar";
                    });
                } else {
                    swal('Pembayaran Hutang', 'Data gagal disimpan (' + dt.data + ')...', '');
                }

            },
            error: function(data) {
                swal('FINANCE-Pembayaran Hutang', 'Data gagal disimpan (Err)...', '');


            }
        });
    }
}



//----------------------------------------------------------------------------------------------------------------



var idrow = 2;

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

    td1.innerHTML = "<input name='faktur[]'      id=faktur" + idrow + " type='text' class='form-control'  readonly>";
    td2.innerHTML = "<input name='tglfaktur[]'   id=tglfaktur" + idrow + " type='text' class='form-control'  readonly>";
    td3.innerHTML = "<input name='totfaktur[]'   id=totfaktur" + idrow + " onchange='totalline(" + idrow +
        ")' value='1'  type='text' class='form-control rightJustified'  readonly>";
    td4.innerHTML = "<input name='hutang[]'      id=hutang" + idrow +
        " type='text' class='form-control rightJustified' readonly >";
    td5.innerHTML = "<input name='uangmuka[]'    id=uangmuka" + idrow +
        " type='text' class='form-control rightJustified' readonly >";
    td6.innerHTML = "<input name='bayar[]'       id=bayar" + idrow + " onchange='totalline(" + idrow +
        ")'  type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";
    td7.innerHTML = "<input name='disc[]'        id=disc" + idrow + " onchange='totalline(" + idrow +
        ")' type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";
    td8.innerHTML = "<input name='jumlah[]'      id=jumlah" + idrow +
        " type='text' class='form-control rightJustified' size='40%' readonly>";

    idrow++;
}



function post_harga(v1, v2) {
    id = document.getElementById("nopilihharga").value;
    document.getElementById("sat" + id).value = v2;
    document.getElementById("harga" + id).value = v1;
    totalline(id);
}


function getid(id) {
    document.getElementById("nopilih").value = id;
}

function getidakun(id) {
    document.getElementById("nopilihakun").value = id;
}


function getidharga(id) {
    var vid = id.substring(8);
    document.getElementById("nopilihharga").value = vid;
    var supp = document.getElementById("supp").value;
    var item = document.getElementById("kode" + vid).value;
    var param = supp + '~' + item;
    document.getElementById("namabarangharga").innerHTML = document.getElementById("namabarang" + vid).value;
    showharga(param);
}



function save2() {
    var noform = $('[name="nomorbukti"]').val();
    var tanggal = $('[name="tanggal"]').val();
    var jumlahbayar = $('[name="jumlahbayar"]').val();

    if (jumlahbayar == "0" || jumlahbayar == "0.00") {
        swal('FINANCE-Pembayaran Hutang', 'Jumlah pembayaran belum diisi ...', '');
    } else {
        $.ajax({
            url: '<?php echo site_url('pembelian_bayar/save/1')?>',
            data: $('#frmpembelian').serialize(),
            type: 'POST',

            success: function(data) {
                swal({
                    title: "FINANCE-Pembayaran Hutang",
                    html: "<p> No. Bukti   : <b>" + noform + "</b> </p>" +
                        "Tanggal :  " + tanggal,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>pembelian_bayar";
                });

            },
            error: function(data) {
                swal('FINANCE-Pembayaran Hutang', 'Data gagal disimpan ...', '');


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

function total() {

    var table = document.getElementById('datatable');
    var rowCount = table.rows.length;

    tjumlah = 0;
    tdiskon = 0;

    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];

        jumlah = row.cells[5].children[0].value;
        diskon = row.cells[6].children[0].value;
        var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
        var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));

        tjumlah = tjumlah + eval(jumlah1);
        tdiskon = tdiskon + eval(diskon1);
    }

    document.getElementById("_vsubtotal").innerHTML = formatCurrency1(tjumlah);
    document.getElementById("_vdiskon").innerHTML = formatCurrency1(tdiskon);
    document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah - tdiskon);
    document.getElementById("jumlahbayar").value = formatCurrency1(tjumlah - tdiskon);
}

function totalline(id) {

    var table = document.getElementById('datatable');
    var row = table.rows[id];
    uangmuka = row.cells[4].children[0].value;
    jumlah = row.cells[5].children[0].value;
    diskon = row.cells[6].children[0].value;

    hutang = row.cells[3].children[0].value;

    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
    var diskon1 = Number(diskon.replace(/[^0-9\.]+/g, ""));
    var hutang1 = Number(hutang.replace(/[^0-9\.]+/g, ""));
    var uangmuka1 = Number(uangmuka.replace(/[^0-9\.]+/g, ""));

    if ((jumlah1 + uangmuka1) > hutang1) {
        swal('PEMBELIAN', 'Jumlah pembayaran melebihi Hutang ...', '');
        row.cells[5].children[0].value = 0;
    }


    tot = eval(jumlah1) - eval(diskon1);
    row.cells[7].children[0].value = formatCurrency1(tot);
    total();

}



function showfaktur() {
    var xhttp;
    var str = $('[name="supp"]').val();

    if (str == "") {
        document.getElementById("listpo").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("listpo").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>pembelian_bayar/getlistfaktur/" + str, true);
    xhttp.send();
}

function cekbayar(id) {
    var faktur = $('').val();
    var bayar = $('').val();


}

function getfaktur() {
    var xhttp;
    var str = $('[name=supp]').val();
    hapus();
    $('[id=faktur1]').val('');
    $('[id=tglfaktur1]').val('');
    $('[id=totfaktur1]').val('');
    $('[id=hutang1]').val('');
    $('[id=uangmuka1]').val('');
    $('[id=bayar1]').val('');
    $('[id=disc1]').val('');
    totalline(1);
    if (str == "") {

    } else {
        $.ajax({
            url: "<?php echo base_url();?>pembelian_bayar/getfaktur/" + str,
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
                    document.getElementById("faktur" + x).value = data[i].kodepu;
                    document.getElementById("tglfaktur" + x).value = data[i].tglpu;
                    document.getElementById("totfaktur" + x).value = formatCurrency1(data[i].total);
                    document.getElementById("hutang" + x).value = formatCurrency1(data[i].hutang);
                    document.getElementById("uangmuka" + x).value = formatCurrency1(data[i].uangmuka);
                    document.getElementById("bayar" + x).value = 0;
                    document.getElementById("disc" + x).value = 0;
                    totalline(x);
                }

            }
        });
    }
}


window.onload = function() {
    document.getElementById('nomorbukti').focus();
};
</script>



<div class="modal fade" id="modal_form_large" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Daftar Hutang - Tukar Faktur</h3>
            </div>
            <div class="modal-body">
                <form id="formDaftarTagihan" method="post">
                    <button type="button" id="btnSave" onclick="pilih_tagihan()" class="btn btn-primary">Proses Data
                        Terpilih</button>
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

<style>
/* .select2 select2-container select2-container--default select2-container--focus{
        width:165px !important;
    } */

.select2_el_akundiskonadjust {
    width: 165px !important;
}
</style>




</body>

</html>