<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>

<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>


<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Finance <small>Daftar Piutang</small>
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
                <a href="<?php echo base_url();?>piutang">
                    Data Piutang
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    Daftar Piutang :
                    <span>
                        <b><?= $data->cust_nama; ?></b>
                    </span>
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar" style="display: flex;justify-content: space-between;">
                    <form id="form-filter">
                        <div class="btn-group flex">
                            <div class="flex">
                                <div>
                                    <input class="hidden" type="radio" name="jenis" id="poli" value="POLI" <?= $input->jenis == 'POLI' ? 'checked' : ''; ?>>
                                    <label class="btn btn-default <?= $input->jenis == 'POLI' ? 'btn-active' : ''; ?>" for="poli">Rawat Jalan</label>
                                </div>
                                <div>
                                    <input class="hidden" type="radio" name="jenis" id="inap" value="INAP" <?= $input->jenis == 'INAP' ? 'checked' : ''; ?>>
                                    <label class="btn btn-default <?= $input->jenis == 'INAP' ? 'btn-active' : ''; ?>" for="inap">Rawat Inap</label>
                                </div>
                                <div>
                                    <input class="hidden" type="radio" name="jenis" id="all" value="all" <?= $input->jenis == 'all' ? 'checked' : ''; ?>>
                                    <label class="btn btn-default <?= $input->jenis == 'all' ? 'btn-active' : ''; ?>" for="all">Semua</label>
                                </div>
                            </div>
                            <div class="btn-group flex">
                                <div style='margin-left:15px;padding:8px;'>Dari</div>
                                <input id="startdate" name="fromdate" class="form-control input-medium" type="date"
                                    value="<?= $input->fromdate; ?>" required />
                                <div style='padding:8px;'>s./d.</div>
                                <input id="enddate" name="todate" class="form-control input-medium" type="date"
                                    value="<?= $input->todate; ?>" required />
                                &nbsp;&nbsp;
                                <input type="submit" name="proses" class="btn btn-primary" onclick="serialize_form()"
                                    value="Proses">
                            </div>
                        </div>
                    </form>
                    <div class="btn-group" style="float:left;">
                        <a href="<?= base_url()."piutang/export/" ?>" class="btn btn-danger">Print</i></a>
                        <a href="<?= base_url()."piutang/export/" ?>"
                            class="btn btn-success">Excel</i></a>
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered" id="ar_detail">
                    <thead>
                        <tr>
                            <th>No Faktur</th>
                            <th>No Reg</th>
                            <th>Rekmed</th>
                            <th>No Doc</th>
                            <th>Nama Pasien</th>
                            <th>Penjamin</th>
                            <th>Jenis</th>
                            <th>Tgl Posting</th>
                            <th>Jatuh Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Inacbg</th>
                            <th>Total Bayar</th>
                            <th>Saldo Piutang</th>
                            <th>No Invoice</th>
                        </tr>
                    </thead>
                    <tbody id="data-piutang">
                        <tr class="no-sort">
                            <td style="display: none;">&nbsp;</td>
                            <td class="report-data text-bold" style="text-align: center;" colspan="12">Saldo Awal</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td style="display: none;">&nbsp;</td>
                            <td class="report-data align-right text-bold" style="text-align: right;" id="saldo-awal">Rp. <?= number_format($data->starting_balance ?? 0, 2, ',', '.'); ?></td>
                            <td class="report-data"> - </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="12" style="text-align: center;">Saldo Akhir</td>
                            <td style="text-align: right; font-weight: bold; font-size: 10px;" id="saldo-akhir">Rp. <?= number_format($data->new_balance ?? $data->starting_balance ?? 0, 2, ',', '.'); ?></td>
                            <td> - </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div id="loading">
    <p>Sedang mengambil data ...</p>
</div>

<?php
  $this->load->view('template/footero');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>


<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>"
    type="text/javascript"></script>

<script>

var rupiah = new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR"});
$(document).ready( function () {
    $('.btn-default').each(function () {
        $(this).click(function () {
            $('.btn-default').removeClass('btn-active')
            $(this).addClass('btn-active')
        })
    })
} );

function serialize_form() {
    $('#form-filter').submit(function (event) {
        event.preventDefault()
        window.location.href = '?' + $('#form-filter').serialize()
    })
}

var vendor = JSON.parse('<?= json_encode($data) ?>') 
var url = '<?= base_url() ?>'
var totalPage = 0
var input = JSON.parse('<?= json_encode($input) ?>')

vendor.transactions = 0
vendor.new_balance = parseInt(vendor.starting_balance ?? 0)

function deployData(data, targetBody) {
    data.forEach(transaction => {
            targetBody.insertAdjacentHTML('beforeend', 
            /* html */
            `
                <tr>
                    <td style='font-size: 10px; text-align: center;'>${transaction.fakturno}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.noreg}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.rekmed}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.dokument}</td>
                    <td style='font-size: 10px'>${transaction.namapas}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.cust_id}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.asal}</td>
                    <td style='font-size: 10px; text-align: center;'>${moment(transaction.tglposting).format('DD-MMM-YYYY')}</td>
                    <td style='font-size: 10px; text-align: center;'>${moment(transaction.tgljatuhtempo).format('DD-MMM-YYYY')}</td>
                    <td style='font-size: 10px; text-align: right;'>${rupiah.format(transaction.jumlahhutang)}</td>
                    <td style='font-size: 10px; text-align: right;'>${rupiah.format(transaction.inacbg)}</td>
                    <td style='font-size: 10px; text-align: right;'>${rupiah.format(transaction.jumlahbayar)}</td>
                    <td style='font-size: 10px; text-align: right;'>${rupiah.format(vendor.new_balance += parseFloat(parseFloat(transaction.jumlahhutang) + parseFloat(transaction.inacbg) - parseFloat(transaction.jumlahbayar)))}</td>
                    <td style='font-size: 10px; text-align: center;'>${transaction.invoiceno}</td>
                </tr>
            `
            )
    });
    $('#saldo-akhir').text(rupiah.format(vendor.new_balance))
}

$.ajax(url + '/piutang/transactions', {
    data: input,
    dataType: 'json',
    success: function (data) {
        totalPage = data.total_page
        totalData = data.total_data
        getTransactions(totalPage)
        var check = setInterval(function (total) {
            var child = document.getElementById('data-piutang').children.length
            if (child == (parseInt(total) + 1)){
                $('#loading').css('display', 'none')
                $('#ar_detail').dataTable({
                    bSort: false
                })
                clearInterval(check)
            }
        }, 5000, totalData)
    }
})

function getTransactions (totalPage) {
	if (totalPage < 0) {
		return;
	}
    
    input.page = totalPage
    
    if(input.page > 0) {
        $.ajax(url + '/piutang/transactions', {
        data: input,
        dataType: 'json',
        success: function (data) {
            deployData(data.data, document.getElementById('data-piutang'))
        }
    })
    }

    getTransactions(totalPage - 1);
}
</script>

<style>
.btn-active {
    background: #ccc;
}
.report-data{
    text-align: center;
    font-size: 10px;
}
.align-left{
    text-align: left;
}
.align-right{
    text-align: right;
}
th {
    text-align: center;
}
.hidden{
    display: none;
}
.flex{
    display: flex;
}
#loading{
    height: 100vh;
    left: 0;
    top: 0;
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    position: fixed;
    background-color: white;
}
#loading>p{
    font-size: 20px;
}
</style>