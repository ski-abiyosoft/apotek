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
            <span class="title-web">APOTEK <small>Kartu Stock</small>
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
                    Kartu Stock
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="note note-success">
            <p>
                Kartu Stock
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
                                    <label class="col-md-3 control-label">Mulai</label>
                                    <div class="col-md-2">

                                        <input id="tanggal1" name="tanggal1" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="" />

                                    </div>
                                    <label class="col-md-2 control-label">s/d</label>
                                    <div class="col-md-2">
                                        <input id="tanggal2" name="tanggal2" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                    </div>



                                </div>
                            </div>



                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cabang</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="cabang" id="cabang">
                                            <option value="<?= $this->session->userdata("unit") ?>" selected>
                                                <?= $cabang->namars ?></option>
                                        </select>
                                        <!--<select name="cabang" id="cabang" class="select2_el_cabang form-control" >
															  </select>	-->

                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Gudang</label>
                                    <div class="col-md-9">
                                        <select name="gudang" id="gudang" class="select2_el_farmasi_depo form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Kode Barang</label>
                                    <div class="col-md-9">
                                        <select name="kodebarang" id="kodebarang" class="select2_el_farmasi_baranggud form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>




                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                <!-- href="<?= site_url('Farmasi_kartustock/tampil') ?>" -->
                                <a class="btn btn-sm blue print_laporan" onclick="javascript:_urlcetak();"><i class="glyphicon glyphicon-file"></i><b>
                                        TAMPILKAN </b></a>
                                <!-- <a class="btn green print_laporan" onclick="_urlcetak();">CETAK</a> -->

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
    $(window).on("load", function() {
        initailizeSelect2_farmasi_baranggud("");
    });

    $("#gudang").on("change", function() {
        initailizeSelect2_farmasi_baranggud($(this).val());
    });

    function _urlcetak() {
        var baseurl = "<?php echo base_url() ?>";
        var barang = $('[name="kodebarang"]').val();
        var gudang = $('[name="gudang"]').val();
        var cbg = $('[name="cabang"]').val();
        var tgl1 = $('[name="tanggal1"]').val();
        var tgl2 = $('[name="tanggal2"]').val();
        var param = '?barang=' + barang + '&gudang=' + gudang + '&cabang=' + cbg + '&tgl1=' + tgl1 + '&tgl2=' + tgl2;

        // script original
        // if (barang == null || gudang == null || cbg == null) {
        // husain change
        if (gudang == null || cbg == null) {
            swal('', 'Pilihan belum lengkap', '');
        } else {
            // hasil = baseurl + 'farmasi_kartustock/cetak/' + param;
            hasil = baseurl + 'farmasi_kartustock/cetak2/' + param;
            window.open(hasil, '_blank');
        }
    }

    function _urltampil() {
        var baseurl = "<?php echo base_url() ?>";
        var kodebarang = $('[name="kodebarang"]').val();
        var gudang = $('[name="gudang"]').val();
        var cabang = $('[name="cabang"]').val();
        var tanggal1 = $('[name="tanggal1"]').val();
        var tanggal2 = $('[name="tanggal2"]').val();
        var param = '?barang=' + kodebarang + '&gudang=' + gudang + '&cabang=' + cabang + '&tgl1=' + tanggal1 + '&tgl2=' +
            tanggal2;


        if (kodebarang == null || gudang == null || cabang == null) {
            swal({
                title: "PILIHAN",
                html: "Belum lengkap",
                type: "error",
                confirmButtonText: "OK"
            });
        } else {
            hasil = baseurl + 'farmasi_kartustock/tampil/' + param;
            window.open(hasil, '_blank');
        }

    }
    // fuction _urltampil() {
    //     var baseurl = "<?php echo base_url() ?>";
    //     var barang = $('[name="kodebarang"]').val();
    //     var gudang = $('[name="gudang"]').val();
    //     var cbg = $('[name="cabang"]').val();
    //     var tgl1 = $('[name="tanggal1"]').val();
    //     var tgl2 = $('[name="tanggal2"]').val();
    //     var param = '?barang=' + barang + '&gudang=' + gudang + '&cabang=' + cbg + '&tgl1=' + tgl1 + '&tgl2=' + tgl2;

    //     if (barang == null || gudang == null || cbg == null) {
    //         swal('', 'Pilihan belum lengkap', '');
    //     } else {
    //         hasil = baseurl + 'farmasi_kartustock/tampil/' + param;
    //         window.open(hasil, '_blank');
    //     }
    // }
</script>