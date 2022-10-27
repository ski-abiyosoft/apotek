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
            <span class="title-web">FARMASI <small>Laporan Pembelian Farmasi</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">

            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="../home.php">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">
                    Farmasi
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">
                    Laporan
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="note note-success">
            <p>
                Laporan - laporan untuk Pembelian Farmasi
                <br>
            </p>
        </div>

        <br>

        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i>Parameter Laporan
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">LAPORAN</label>
                                    <div class="col-md-9">
                                        <select id="idlap" name="idlap" class="select2me bs-select form-control" data-show-subtext="true" data-placeholder="Pilih...">
                                            <optgroup>
                                                <option data-subtext="101" value="101">01. LAPORAN PEMBELIAN BARANG
                                                    (DETAIL INVOICE)</option>
                                                <option data-subtext="102" value="102">02. LAPORAN PEMBELIAN BARANG
                                                    (REKAP INVOICE)</option>
                                                <option data-subtext="103" value="103">03. REKAP PEMBELIAN BARANG
                                                    PERSUPPLIER DAN ITEM</option>
                                                <option data-subtext="104" value="104">04. REKAP PEMBELIAN BARANG PER
                                                    SUPPLIER TOTAL</option>
                                                <option data-subtext="105" value="105">05. LAPORAN PEMBELIAN BARANG PER
                                                    ITEM (TOTAL)</option>
                                                <option data-subtext="106" value="106">06. LAPORAN STATUS ORDER
                                                    PEMBELIAN</option>
                                                <option data-subtext="107" value="107">07. LAPORAN RETURN PEMBELIAN
                                                </option>
                                                <option data-subtext="108" value="108">08. LAPORAN HUTANG GUDANG FARMASI
                                                </option>

                                            </optgroup>
                                        </select>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">KLINIK</label>
                                    <div class="col-md-9">
                                        <input style="background-color:#99ff33; color:black" type="text" name="cabang" id="cabang" class="form-control" disabled>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">TANGGAL</label>
                                    <div class="col-md-1">
                                        <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">s/d</label>
                                    <div class="col-md-1">
                                        <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">VENDOR</label>
                                    <div class="col-md-9">
                                        <select name="vendor" id="vendor" class="select2_el_vendor form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        &nbsp;
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <!-- <a class="btn btn-sm blue print_laporan" onclick="javascript:_urlcetak('0');"><i title="CETAK PDF" class="glyphicon glyphicon-file"></i><b> LAYAR </b></a> -->

                                <!-- <a class="btn btn-sm red print_laporan" onclick="javascript:_urlcetak('1');"><i
                                        title="CETAK PDF" class="glyphicon glyphicon-print"></i><b> PDF </b></a> -->
                                <a class=" btn btn-sm red print_laporan  print_laporan" id="cetak" href="#report" data-toggle="modal">Cetak PDF</a>

                                <a class="btn btn-sm green print_laporan" onclick="exp()"><i title=" CETAK PDF" class="fa fa-download"></i><b> EXCEL </b></a>

                                <br />
                                <h4>
                                    <div class="err" id="resultss"></div>
                                </h4>

                                <div>
                                    <img id="proses" src="<?php echo base_url(); ?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden" />
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
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
    cabb();

    function cabb() {
        $.ajax({
            url: "<?php echo base_url(); ?>app/search_cabang2",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data.text);
                $('#cabang').val(data.id);

            }
        });


    }

    function _urlcetak(cek) {
        var baseurl = "<?php echo base_url() ?>";
        var idlap = $('[name="idlap"]').val();
        var tgl1 = $('[name="tanggal1"]').val();
        var tgl2 = $('[name="tanggal2"]').val();
        var unit = $('[name="unit"]').val();
        var vendor = $('[name="vendor"]').val();


        if (vendor == "" || vendor == null) {
            swal({
                title: "VENDOR",
                html: "HARUS DI PILIH .!!!",
                type: "error",
                confirmButtonText: "OK"
            });
            return;
        } else {
            var param = '?idlap=' + idlap + '&tgl1=' + tgl1 + '&tgl2=' + tgl2 + '&vendor=' + vendor + '&cekk=' + cek;
            if (idlap == '101') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_101/' + param
                window.open(url, '');
            }
            if (idlap == '102') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_110/' + param
                window.open(url, '');
            }
            if (idlap == '103') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_109/' + param
                window.open(url, '');
            }
            if (idlap == '104') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_111/' + param
                window.open(url, '');
            }
            if (idlap == '105') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_112/' + param
                window.open(url, '');
            }
            if (idlap == '106') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_113/' + param
                window.open(url, '');
            }
            if (idlap == '107') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_114/' + param
                window.open(url, '');
            }
            if (idlap == '108') {
                url = baseurl + 'Pembelian_farmasi_laporan/ctk_115/' + param
                window.open(url, '');
            }
        }

    }
    $('.print_laporan').on("click", function() {
        var idlap = document.getElementById('idlap').value;
        var cabang = document.getElementById('cabang').value;
        var tanggal1 = document.getElementById('tanggal1').value;
        var tanggal2 = document.getElementById('tanggal2').value;
        var vendor = document.getElementById('vendor').value;
        $('.modal-title').text('CETAK LAPORAN PEMBELIAN BARANG');
        // console.log(tanggal1);
        $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>Pembelian_farmasi_laporan/cetak?idlap=' + idlap + '&cabang=' + cabang + '&tanggal1=' + tanggal1 + '&tanggal2=' + tanggal2 + '&vendor=' + vendor + '" frameborder="no" width="100%" height="520"></iframe>');
    });

    function exp() {
        var idlap = document.getElementById('idlap').value;
        var cabang = document.getElementById('cabang').value;
        var tanggal1 = document.getElementById('tanggal1').value;
        var tanggal2 = $('#tanggal2').val();
        var vendor = document.getElementById('vendor').value;
        // console.log(idlap);
        location.href = '<?= site_url('Pembelian_farmasi_laporan/excel/?idlap=') ?>' + idlap + '&cabang=' + cabang +
            '&tgl1=' + tanggal1 + '&tgl2=' + tanggal2 + '&vendor=' + vendor;
        // $.ajax({
        //     url: '<?= site_url('Pembelian_farmasi_laporan/excel?idlap=') ?>' + idlap + '&cabang=' + cabang +
        //         '&tgl1=' + tanggal1 + '&tgl2=' + tanggal2 + '&vendor=' + vendor,
        //     type: 'GET',
        //     dataType: 'JSON',
        //     success: function(data) {
        //         console.log(data);
        //     }
        // })
    }
</script>