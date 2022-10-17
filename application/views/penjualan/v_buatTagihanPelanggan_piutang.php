<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/plugins/select2/select2.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css') ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Finance <small>Piutang</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">

            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url(); ?>dashboard">
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
                <a href="<?php echo base_url(); ?>piutang">
                    Piutang
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>Buat Tagihan ke Pelanggan/Penjamin
        </div>
    </div>

    <div class="portlet-body form">
        <form class="form-horizontal" method="post" id="invoice-form">
            <div class="form-body">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Buat Tagihan
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nama Pelanggan
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" value="<?= $vendor->cust_id . ' - ' . $vendor->cust_nama ?>" readonly>
                                            <input type="hidden" name="cust_id" id="cust_id" value="<?= $vendor->cust_id; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="col-md-4 control-label">Jenis<font color="red">*</font></span>
                                        <div class="d-flex gap-2">
                                            <div>
                                                <label class="d-flex" for="jenis1"><input class="input-radio" type="radio" name="jenis" id="jenis1" value="POLI"> Rawat Jalan</label>
                                            </div>
                                            <div>
                                                <label class="d-flex" for="jenis2"><input class="input-radio" type="radio" name="jenis" id="jenis2" value="INAP"> Rawat Inap</label>
                                            </div>
                                            <div>
                                                <label class="d-flex" for="jenis3"><input class="input-radio" type="radio" name="jenis" id="jenis3" value="all"> Semua</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No. Transaksi</label>
                                        <div class="col-md-6">
                                            <input type="text" id="invoiceno" name="invoiceno" class="form-control" value="Generated Automatically" readonly>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Periode pasien dari
                                            <font color="red">*</font>
                                        </label>
                                        <div class="col-md-6">
                                            <input id="dariperiode" name="dariperiode" class="form-control rightJustified" type="date" value="<?php echo $fromdate ?? date('Y-m-d') ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Tagihan</label>
                                        <div class="col-md-6">
                                            <input id="invoicedate" name="invoicedate" class="form-control rightJustified" type="date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sampai Tanggal<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="sampaiperiode" name="sampaiperiode" class="form-control rightJustified" type="date" value="<?= $todate ?? date('Y-m-d') ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Jatuh Tempo</label>
                                        <div class="col-md-6">
                                            <input id="duedate" name="duedate" class="form-control rightJustified" type="date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Catatan</label>
                                        <div class="col-md-6">
                                            <textarea id="keterangan" name="keterangan" class="form-control" rows="4" cols="50"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="button" onclick="pilih_pasien()" class="btn green"><i class="fa fa-refresh"></i>
                            Ambil Data Pasien
                        </button>
                        <button type="button" onclick="checkAll()" class="btn btn-warning" id="checklist" disabled><i class="fa fa-check-square-o"></i>
                            Pilih Semua
                        </button>
                        &nbsp;&nbsp;&nbsp;<span id="alertVendor" style='color:red;'></span>
                        <br /><br />
                        <div style="height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-hover table-bordered" id="datatable">
                                <thead class="breadcrumb">
                                    <tr>
                                        <th style="text-align: center">Ceklist</th>
                                        <th style="text-align: center">No Invoice</th>
                                        <th style="text-align: center">Noreg RM</th>
                                        <th style="text-align: center">Nama Pasien</th>
                                        <th style="text-align: center">Tanggal Berobat</th>
                                        <th style="text-align: center">Asal</th>
                                        <th style="text-align: center">No. Registrasi</th>
                                        <th style="text-align: center">No Doc/Sep</th>
                                        <th style="text-align: center">Jumlah Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody id="daftar_pasien">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row form-actions">
                        <div class="col-xs-8">
                            <div class="wells">
                                <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i>
                                    Simpan</button>
                                <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i class="fa fa-undo"></i> Kembali</button>
                                <input type="hidden" id="id" name="id" class="form-control rightJustified">
                                <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                            </div>
                        </div>

                        <div class="col-xs-4 invoice-block">
                            <div class="well">
                                <table id="tabeltotal">
                                    <tbody>
                                        <tr>
                                            <td width="40%"><strong>TOTAL TAGIHAN</strong></td>
                                            <td width="1%"><strong>:</strong></td>
                                            <td width="59%" align="right">
                                                <span id="_vtotaltagihan" style="font-weight: bold;">
                                                    <?php echo 'Rp ' . number_format($jumlahhutang ?? 0, 2, '.', ','); ?>
                                                </span>
                                            </td>
                                            <input type='hidden' id='jumlahrp' name='jumlahrp' value="0">
                                        </tr>
                                        <tr>
                                            <td style="display: flex!important;align-items: center!important;justify-content: space-between!important;">
                                                <div>
                                                    <strong>
                                                        DISKON
                                                    </strong>
                                                </div>
                                                <div style="margin-right: 5px;margin-left: 5px;">
                                                    <input type='text' id='diskon' name='diskon' class="form-control" onkeyup="calculateDiscount(this)">
                                                </div>
                                                <div>
                                                    %&nbsp;
                                                </div>
                                            </td>
                                            <td width="1%"><strong>:</strong></td>
                                            <td width="59" align="right">
                                                <span id="_vdiskon" style="font-weight: bold;">
                                                    Rp <?= number_format(0, 2, ',', '.'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="40%"><strong>TOTAL AKHIR</strong></td>
                                            <td width="1%"><strong>:</strong></td>
                                            <td width="59%" align="right">
                                                <span id="_vtotalnet" style="font-weight: bold;">
                                                    Rp <?= number_format(0, 2, '.', ','); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
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
$this->load->view('template/v_report');
?>

<?php
$cust = '';
?>

<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>" type="text/javascript">
</script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js') ?>" type="text/javascript"> </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>" type="text/javascript"></script>

<style>
    .d-flex {
        display: flex;
        align-items: center;
        justify-content: start;
        vertical-align: middle;
    }

    .gap-2 {
        gap: 25px;
    }

    .input-radio {
        transform: translateY(-10px);
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {

    });

    var idrow;
    var subtotal = 0;
    var url = "<?= base_url() ?>"
    var cabang = "<?= $cabang ?>"

    var rupiah = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    });

    $(document).ready(function() {
        $('.btn-default').each(function() {
            $(this).click(function() {
                $('.btn-default').removeClass('btn-active')
                $(this).addClass('btn-active')
            })
        })
    });


    function pilih_pasien() {
        var cust_id = $('input[name="cust_id"]').val();
        var jenis = $('input[name="jenis"]:checked').val();
        var dariperiode = $('#dariperiode').val();
        var sampaiperiode = $('#sampaiperiode').val();

        if (
            cust_id === null ||
            typeof(jenis) === "undefined" ||
            dariperiode === null || sampaiperiode === null ||
            dariperiode === '' || sampaiperiode === ''
        ) {
            swal('', 'Data belum lengkap...', '')
        } else {
            var vendor = JSON.parse('<?= json_encode($vendor) ?>')
            var url = '<?= base_url() ?>'
            var input = {
                fromdate: dariperiode,
                todate: sampaiperiode,
                jenis: $('input[name="jenis"]:checked').val()
            };

            function deployData(data, targetBody) {
                data.forEach(transaction => {
                    targetBody.insertAdjacentHTML('beforeend',
                        /* html */
                        `
                        <tr>
                            <td style='font-size: 10px; text-align: center;'><input class="pilih-pasien" onchange="updateTotal(this)" type="checkbox" name="id_pasien[]" value="${transaction.id}" data-tagihan="${transaction.jumlahhutang}"></td>
                            <td style='font-size: 10px; text-align: center;'>${transaction.invoiceno}</td>
                            <td style='font-size: 10px; text-align: center;'>${transaction.rekmed}</td>
                            <td style='font-size: 10px'>${transaction.namapas}</td>
                            <td style='font-size: 10px; text-align: center;'>${moment(transaction.tglposting).format('DD-MMM-YYYY')}</td>
                            <td style='font-size: 10px; text-align: center;'>${transaction.asal}</td>
                            <td style='font-size: 10px; text-align: center;'>${transaction.noreg}</td>
                            <td style='font-size: 10px; text-align: center;'>${transaction.dokument}</td>
                            <td style='font-size: 10px; text-align: right;'>${rupiah.format(transaction.jumlahhutang)}</td>
                        </tr>
                    `
                    )
                });
            }

            $.ajax(url + '/piutang/pilih_pasien/' + vendor.cust_id, {
                data: input,
                dataType: 'json',
                success: function(data) {
                    totalPage = data.total_page
                    totalData = data.total_data
                    activateButton(totalData)
                    document.getElementById('daftar_pasien').innerHTML = ''
                    getTransactions(totalPage)
                },
                error: function(jqXHR, status, error) {
                    console.log(error)
                }
            })

            function getTransactions(totalPage) {
                if (totalPage < 0) {
                    return;
                }

                input.page = totalPage

                if (input.page > 0) {
                    $.ajax(url + '/piutang/pilih_pasien/' + vendor.cust_id, {
                        data: input,
                        dataType: 'json',
                        success: function(data) {
                            deployData(data.data, document.getElementById('daftar_pasien'))
                        }
                    })
                }

                getTransactions(totalPage - 1);
            }
        }
    }

    function activateButton (data) {
        var button = document.getElementById('checklist')

        if (data > 0) button.disabled = false
        else button.disabled = true
    }

    function checkAll(){
        var input = document.querySelectorAll('.pilih-pasien')
        var isChecked = true

        input.forEach((item) => {if(item.checked == false) isChecked = false})
        
        if (isChecked){
            input.forEach((item) => {
                item.checked = false
            })
        }else{
            input.forEach((item) => {
                item.checked = true
            })
        }

        updateTotal()
    }

    function updateTotal(input = null) {
        if (input){
            if (input.checked) {
                var oldTotal = parseFloat($('#jumlahrp').val())
                $('#jumlahrp').val(oldTotal + parseFloat(input.dataset.tagihan))
                $('#_vtotaltagihan').text(rupiah.format(oldTotal + parseFloat(input.dataset.tagihan)))
            } else {
                var oldTotal = parseFloat($('#jumlahrp').val())
                $('#jumlahrp').val(oldTotal - parseFloat(input.dataset.tagihan))
                $('#_vtotaltagihan').text(rupiah.format(oldTotal - parseFloat(input.dataset.tagihan)))
            }
        }else {
            var input = document.querySelectorAll('.pilih-pasien')

            input.forEach((item) => {
                var oldTotal = parseFloat($('#jumlahrp').val())
                if (item.checked) {
                    var oldTotal = parseFloat($('#jumlahrp').val())
                    $('#jumlahrp').val(oldTotal + parseFloat(item.dataset.tagihan))
                    $('#_vtotaltagihan').text(rupiah.format(oldTotal + parseFloat(item.dataset.tagihan)))
                } else {
                    $('#jumlahrp').val(0)
                    $('#_vtotaltagihan').text(rupiah.format(0))
                }
            })
        }

        calculateDiscount(document.getElementById('diskon'))
    }

    function calculateDiscount(input){
        var totalRecievable = $('#jumlahrp').val()
        var discount = 0

        if (input.value.length > 0){
            if (input.value.includes(',')) discount = parseFloat(input.value.replace(',', '.'))
            else discount = parseFloat(input.value)
        }

        var discountAmount = discount / 100 * totalRecievable

        $('#_vdiskon').text(rupiah.format(discountAmount))
        $('#_vtotalnet').text(rupiah.format(totalRecievable - discountAmount))
    }

    function save() {
        var input = $('#invoice-form').serialize();
        
        $.ajax(url + 'piutang/save_tagihan_pelanggan/' + $('#cust_id').val(), {
            method: 'POST',
            data: input,
            dataType: 'json',
            success: function (data) {
                window.location.href = url + 'piutang/invoice/' + $('#cust_id').val() + '?fromdate=' + moment().startOf('month').format('YYYY-MM-DD') + '&todate=' + moment().endOf('month').format('YYYY-MM-DD')
            },
            error: function (jqXHR, textStatus, error){
                console.log(error)
            }
        })
    }
</script>

</body>

</html>