<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">Voucher <small>Voucher Baru</small></h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>pembuatan_voucher">Voucher</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">Voucher Baru</a>
            </li>
        </ul>
    </div>
</div>

<button class="btn btn-primary btn-lg" style="margin:0 0 20px 0" onclick="location.href='/pembayaran_voucher'"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Kembali</button>

<div class="portlet box blue" style="margin-bottom:30px !important;padding-bottom:0px !important">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-reorder"></i>Data Baru</div>
    </div>

    <div class="portlet-body form">
        <form id="formvoucher" class="form-horizontal" method="post">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">Untuk Cabang <font color="red">*</font>
                                    </label>
                                    <div class="col-md-6">
                                        <select type="text" name="cabang" class="select2_el_cabang_all form-control" id="cabang">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">Jenis Voucher <font color="red">*</font>
                                    </label>
                                    <div class="col-md-6">
                                        <select type="text" class="form-control" name="jenisvouc" id="jenisvouc" required>
                                            <option>- Pilih Jenis Voucher -</option>
                                            <?php
                                            foreach ($penjamin as $pjkey => $pjval) {
                                                echo '<option value="' . $pjval->cust_id . '">' . $pjval->cust_nama . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">Tanggal Kirim <font color="red">*</font>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date("Y-m-d") ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">No Kirim <font color="red">*</font></label>
                                    <div class="col-md-8">
                                        <input type="hidden" name="hidenokirim" id="hidenokirim">
                                        <input type="text" class="form-control" name="nokirim" id="nokirim" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">No Kirim <font color="red">*</font></label>
                                    <div class="col-md-8">
                                        <input type="hidden" name="nokirim" id="nokirim">
                                        <input type="text" class="form-control" name="nokirim1" id="nokirim1">
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label">Catatan <font color="red">*</font></label>
                                    <div class="col-md-8">
                                        <textarea rows="3" style="resize:none" type="text" class="form-control" name="keterangan" id="keterangan" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row" style="padding:0 25px 0 25px">
                <div class="col-md-12">
                    <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                        <thead>
                            <tr>
                                <th width="50%" style="text-align: center">Nomor Voucher</th>
                                <th width="50%" style="text-align: center">Nominal Voucher RP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="50%">
                                    <input type="text" class="form-control" name="novoucher[]" id="novoucher" required>
                                </td>
                                <td width="50%">
                                    <input type="text" class="form-control" name="nominal[]" id="nominal" data-type="currency" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row" style="padding:5px 0 0 0">
                        <div class="col-xs-9">
                            <div class="wells">
                                <button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i>
                                </button>
                                <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding:10px 25px 25px 25px;width:auto">
                <button class="btn btn-primary" type="button" id="newvoucher"><i class="fa fa-plus"></i> Buat Voucher
                    Lagi</button>
                <button class="btn btn-primary" type="button" id="savevoucher" onclick="save()"><i class="fa fa-save"></i>
                    Simpan</button>
                <button class="btn btn-warning" type="button" id="cetak"><i class="fa fa-print"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>

<script>
    $(window).on("load", function() {
        sequentNokirim();
        $('#formvoucher').trigger("reset");
    });

    $("#newvoucher").hide();
    $("#cetak").hide();

    var jenisvouc = $("#jenisvouc").val();
    var keterangan = $("#keterangan").val();

    var idrow = 2;
    var idrow2 = 2;

    function tambah() {
        var x = document.getElementById('datatable').insertRow(idrow);
        var td1 = x.insertCell(0);
        var td2 = x.insertCell(1);

        td1.innerHTML = "<input name='novoucher[]' id='novoucher' type='text' class='form-control'>";
        td2.innerHTML = "<input name='nominal[]' id='nominal' type='text' data-type='currency' class='form-control'>";
        idrow++;

        datatype();
    }

    function datatype() {

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

    }


    function formatCurrency(input, blur) {
        var input_val = input.val();

        if (input_val === "") {
            return;
        }

        var original_len = input_val.length;

        var caret_pos = input.prop("selectionStart");

        if (input_val.indexOf(".") >= 0) {

            var decimal_pos = input_val.indexOf(".");

            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);

            if (blur === "blur") {
                right_side += "00";
            }

            right_side = right_side.substring(0, 2);

            input_val = "" + left_side + "." + right_side;

        } else {
            input_val = formatNumber(input_val);
            input_val = "" + input_val;

            if (blur === "blur") {
                input_val += ".00";
            }
        }

        input.val(input_val);


        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    function hapus() {
        if (idrow > 2) {
            var x = document.getElementById('datatable').deleteRow(idrow - 1);
            idrow--;
        }
    }

    function sequentNokirim() {
        var cabval = $("#cabang").val();
        $.ajax({
            url: "/pembuatan_voucher/get_last_number/",
            type: "GET",
            dataType: "JSON",
            data: {
                cabang: cabval
            },
            success: function(data) {
                $("#nokirim").val("Auto");
                $("#hidenokirim").val(data.nokir);
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                console.error(data.nokir);
            }
        });
    }

    function secondSequent() {
        var cabval = $("#cabang").val();
        $.ajax({
            url: "/pembuatan_voucher/get_recent_number/",
            type: "GET",
            dataType: "JSON",
            data: {
                cabang: cabval
            },
            success: function(data) {
                $("#nokirim").val(data.nokir);
                $("#hidenokirim").val(data.nokir);
            },
            error: function(data, xhr, ajaxOptions, thrownError) {
                console.error(data.nokir);
            }
        });
    }

    $("#cetak").on("click", function(e) {
        e.preventDefault();
        var recent_voucher = $("#formvoucher #hidenokirim").val();
        window.open("<?= base_url() ?>pembuatan_voucher/cetak/?no_kirim=" + recent_voucher, "_blank");
    })

    // $("#savevoucher").on("click", function(e) {
    //     e.preventDefault();
    function save() {
        var jenisvouc = $("#jenisvouc").val();
        var keterangan = $("#keterangan").val();
        var novoucher = $("[id='novoucher']").val();
        var nominal = $("[id='nominal']").val();

        if (jenisvouc == "" || keterangan == "" || novoucher == "" || nominal == "") {
            swal({
                html: "Data masih kosong",
                type: "error",
                confirmButtonText: "Ok"
            });
        } else {

            // console.log($("#formvoucher").serialize());

            $.ajax({
                url: "/pembuatan_voucher/save/",
                type: "POST",
                dataType: "json",
                data: $("#formvoucher").serialize(),
                success: function(data) {

                    // console.log(data)
                    if (data.status == 1) {
                        secondSequent();
                        swal({
                            html: "Voucher Berhasil Di Tambahkan",
                            type: "success",
                            confirmButtonText: "Ok"
                        });

                        $("#savevoucher").hide();
                        $("#newvoucher").show();
                        $("#cetak").show();
                        $("#formvoucher input").prop("disabled", true);
                        $("#formvoucher select").prop("disabled", true);
                        $("#formvoucher textarea").prop("disabled", true);
                        $(".wells").hide();
                    } else {
                        swal({
                            html: "Nomor Voucher Tidak Boleh Sama",
                            type: "warning",
                            confirmButtonText: "Ok"
                        });
                    }
                    // if (data.status == true) {
                    //     secondSequent();
                    //     swal({
                    //         html: "Voucher Berhasil Di Tambahkan",
                    //         type: "success",
                    //         confirmButtonText: "Ok"
                    //     });

                    //     $("#savevoucher").hide();
                    //     $("#newvoucher").show();
                    //     $("#cetak").show();
                    //     $("#formvoucher input").prop("disabled", true);
                    //     $("#formvoucher select").prop("disabled", true);
                    //     $("#formvoucher textarea").prop("disabled", true);
                    //     $(".wells").hide();
                    // } else {
                    //     swal({
                    //         html: "Nomor Voucher Tidak Boleh Sama",
                    //         type: "warning",
                    //         confirmButtonText: "Ok"
                    //     });
                    // }
                },
                error: function(data, xhr, ajaxOptions, thrownError) {
                    if (data.status == 0) {
                        swal({
                            html: "Voucher Gagal Di Tambahkan",
                            type: "Error",
                            confirmButtonText: "Coba Lagi"
                        });
                    }
                }
            });
        }
    }
    // });

    $("#newvoucher").on("click", function() {
        sequentNokirim();
        hapus();
        $('#formvoucher').trigger("reset");
        $("#savevoucher").show();
        $("#cetak").hide();
        $("#newvoucher").hide();
        $(".wells").show();
        $("#formvoucher input").prop("disabled", false);
        $("#formvoucher select").prop("disabled", false);
        $("#formvoucher textarea").prop("disabled", false);
    });
</script>
</body>

</html>