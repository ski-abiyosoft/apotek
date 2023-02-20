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
            <span class="title-web">APOTEK <small>Laporan Penjualan Resep</small>
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
                    Farmasi
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>Laporan_penjualan">
                    Laporan Penjualan
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>Laporan - laporan untuk Penjualan Resep<br></p>
        </div>
        <br>
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i> Parameter Laporan
                </div>
            </div>
            <div class="portlet-body form">
                <form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dari Tanggal</label>
                                    <div class="col-md-9">
                                        <input id="dari" name="dari" class="form-control input-medium" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sampai Tanggal</label>
                                    <div class="col-md-9">
                                        <input id="sampai" name="sampai" class="form-control input-medium" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Store/depo</label>
                                    <div class="col-md-3">
                                        <select name="depo" id="depo" class="select2_depo form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Laporan</label>
                                    <div class="col-md-3">
                                        <select name="laporan" id="laporan" class="select2_laporan form-control">
                                            <option value="1">01 Laporan Penjualan Resep Perdokter</option>
                                            <option value="2">02 Laporan Rekap Penjualan Resep</option>
                                            <option value="3">03 Laporan Rincian Penjualan Resep</option>
                                            <option value="4">04 Laporan Analisa Penjualan Obat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a class="btn btn-sm red " onclick="cetak()">
                                    <i title=" CETAK PDF" class="fa fa-print"></i><b> CETAK </b>
                                </a>
                                <a class="btn btn-sm green " onclick="exp()">
                                    <i title=" EXPORT PDF" class="fa fa-download"></i><b> EXCEL </b>
                                </a>
                                <br/>
                                <h4>
                                    <div class="err" id="resultss"></div>
                                </h4>
                                <div>
                                    <img id="proses" src="<?php echo base_url();?>assets/img/loading-spinner-blue.gif"
                                        class="img-responsive" style="visibility:hidden" />
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
  $this->load->view('template/footer');
   $this->load->view('template/v_report');
?>

<script>
$('.select2_laporan').select2();

// $('.print_laporan').on("click", function() {
//     var laporan = document.getElementById('laporan').value;
//     if (laporan != '') {
//         var dari = document.getElementById('dari').value;
//         var sampai = document.getElementById('sampai').value;
//         var dari_jam = document.getElementById('dari_jam').value;
//         var sampai_jam = document.getElementById('sampai_jam').value;
//         var jenis = $('input[name="jenis"]:checked').val();
//         var depo = document.getElementById('depo').value;
//         $('.modal-title').text('CETAK LAPORAN PENJUALAN');
//         $("#simkeureport").html('<iframe src="<?php echo base_url();?>Laporan_penjualan/cetak?dari=' + dari + "&dari_jam="+dari_jam+ "&sampai_jam="+sampai_jam+
//             '&sampai=' + sampai + '&jenis=' + jenis + '&depo=' + depo + '&laporan=' + laporan + '" frameborder="no" width="100%" height="520"></iframe>');
//     }
// });

    function cetak() {
        var laporan = document.getElementById('laporan').value;
        if(laporan != ''){
            var dari        = document.getElementById('dari').value;
            var sampai      = document.getElementById('sampai').value;
            var depo        = document.getElementById('depo').value;
            var baseurl     = "<?php echo base_url() ?>";
            var urlnya      = baseurl + 'Laporan_penjualan/cetak2/?dari='+dari+'&sampai=' + sampai + '&depo=' + depo + '&laporan=' + laporan+"&pdf=1";
            window.open(urlnya, '_blank');
        }
    }

function exp() {
    var dari    = document.getElementById('dari').value;
    var sampai  = document.getElementById('sampai').value;
    var depo    = document.getElementById('depo').value;
    var laporan = document.getElementById('laporan').value;
    location.href   = '<?= site_url('Laporan_penjualan/cetak2/?dari=')?>' +dari +'&sampai=' + sampai + '&depo=' + depo + '&laporan=' + laporan+"&pdf=2";

}
</script>