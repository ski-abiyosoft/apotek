<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>


<link href="<?php echo base_url('assets/css/font_css.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet"
    type="text/css" />

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Finance <small>Daftar Hutang</small>
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
                    Data Hutang
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
                    Daftar Hutang -
                    <span>
                        <b>
                        <?php 
                                    echo "$vendor->vendor_id - $vendor->vendor_name";
                                ?>
                        </b>
                    </span>
                </div>
                <div class="pull-right">
                    <div class="caption" style="font-size: 16px;">
                    <?php 
								        echo $periode;
                                    ?>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div style="display: flex; justify-content: end; margin-bottom: 10px;">
                    <a 
                        class="btn red" 
                        href="<?= base_url("hutang_lap/pdf?laporan=1&fromdate=$startdate&todate=$enddate&cabang[]=$unit&vendor[]=$vendor->vendor_id") ?>"
                        style="margin-right: 10px; font-weight: bold;"
                        target="_blank"
                        > 
                        <i class="fa fa-file-pdf-o"></i> Cetak PDF
                    </a>
                    <a 
                        class="btn green" 
                        href="<?= base_url("hutang_lap/excel?laporan=1&fromdate=$startdate&todate=$enddate&cabang[]=$unit&vendor[]=$vendor->vendor_id") ?>"
                        style="margin-right: 10px; font-weight: bold;"
                        target="_blank"
                        > 
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </a>
                </div>
                <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                    <thead>
                        <tr class="breadcrumb">
                            <th style="text-align: center">No. BAPB</th>
                            <th style="text-align: center">No. Invoice</th>
                            <th style="text-align: center">Tgl. Invoice</th>
                            <th style="text-align: center">Jumlah Tagihan</th>
                            <th style="text-align: center">Dibayar</th>
                            <th style="text-align: center">Sisa</th>
                            <th style="text-align: center">Saldo Hutang</th>
                            <th style="text-align: center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: center" colspan="6">Saldo Awal (<?= local_date($startdate, 'dd MMMM yyyy') ?>)</th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="text-align: right;"><?= accounting_number($keu->saldo_awal) ?></th>
                            <th></th>
                        </tr>
                        <?php for ($i = 0; $i < count($keu->transactions); $i++): ?>
                            <?php $keu->saldo_awal += ($keu->transactions[$i]->totaltagihan - $keu->transactions[$i]->totalbayar) ?>
                            <tr>
                               <td align="center"><?= $keu->transactions[$i]->terima_no ?></td> 
                               <td align="center"><?= $keu->transactions[$i]->invoice_no ?></td> 
                               <td align="center"><?= local_date($keu->transactions[$i]->tglinvoice, 'dd MMMM yyyy') ?></td> 
                               <td style="text-align: right;"><?= accounting_number($keu->transactions[$i]->totaltagihan) ?></td> 
                               <td style="text-align: right;"><?= accounting_number($keu->transactions[$i]->totalbayar) ?></td> 
                               <td style="text-align: right;"><?= accounting_number($keu->transactions[$i]->totaltagihan - $keu->transactions[$i]->totalbayar) ?></td> 
                               <td style="text-align: right;"><?= accounting_number($keu->saldo_awal) ?></td> 
                               <td style="text-align: center;"><a class="btn blue" href="<?= base_url("hutang/cetak/{$keu->transactions[$i]->terima_no}") ?>" target="_blank"><i class="fa fa-print"></i> Cetak</a></td> 
                            </tr>
                        <?php endfor; ?>
                        <tr>
                            <th style="text-align: center" colspan="6">Saldo Akhir (<?= local_date($enddate, 'dd MMMM yyyy') ?>)</th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="display: none;"></th>
                            <th style="text-align: right;"><?= accounting_number($keu->saldo_awal) ?></th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<?php
  $this->load->view('template/footero');
//   $this->load->view('template/footer');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>

<style>
.bootstrap-select .dropdown-toggle:focus {
    outline: none;
    outline: none;
    outline-offset: none;
}
</style>

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


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css"> -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-select2.min.css')?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function() {

    $('.print_laporan').on("click", function() {
        $('.modal-title').text('HUTANG');

        var param = this.id;
        console.log(this.id);

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>hutang/cetak/' + param +
            '" frameborder="no" width="100%" height="520"></iframe>');
    });

    $('#keuangan-keluar-list').DataTable({
        bSort: false
    })
});

function currencyFormat(num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

var TableEditable = function() {

    return {

        //main function to initiate the module
        init: function() {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }


            var oTable = $('#keuangan-keluar-list').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,

                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data:",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "Sebelumnya",
                        "sNext": "Berikutnya"
                    }
                },
                "aoColumnDefs": [{
                    'bSortable': true,
                    'aTargets': [0]
                }],
                "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {

                    var iTotal = 0;
                    var iTotal1 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][7] * 1;
                        iTotal1 += aaData[i][8] * 1;
                    }


                    var iTot = 0;
                    var iTot1 = 0;
                    for (var i = iStart; i < iEnd; i++) {

                        var x = aaData[aiDisplay[i]][7];
                        // var y = Number(x.replace(/[^0-9\.]+/g, ""));
                        var y = Number(x.replaceAll(',', ""));
                        iTot += y;

                        var x1 = aaData[aiDisplay[i]][8];
                        // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        var y2 = Number(x1.replaceAll(',', ""));
                        iTot1 += y2;
                    }

                    var nCells = nRow.getElementsByTagName('td');
                    nCells[1].innerHTML = currencyFormat(iTot);
                    nCells[2].innerHTML = currencyFormat(iTot1);
                }
            });

            jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass(
                "form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass(
                "form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
                showSearchInput: false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-keluar-list_new').click(function(e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '', '', '',
                    '<a class="edit" href="">Edit</a>',
                    '<a class="cancel" data-mode="new" href="">Batal</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            // function deleteRow ( oTable, nRow)
            // {
            //     if (confirm("Hapus Data Ini?")) {

            //         var row_id = nRow.id;
            //         var mydata = row_id.substring(4,30);

            //         $.ajax( {
            //             dataType: 'html',
            //             type: "POST",
            //             url: "<?php echo base_url(); ?>hutang/hapus/"+mydata,	
            //             cache: false,
            //             data: mydata,
            //             success: function () {
            //                 oTable.fnDeleteRow( nRow );
            //                 oTable.fnDraw();
            //             },
            //             error: function() {},
            //             complete: function() {}
            //         } );

            //     }
            // }

            // $('#keuangan-keluar-list a.delete').live('click', function (e) {
            //     e.preventDefault();

            //     var nRow = $(this).parents('tr')[0];
            //     if ( nRow ) {
            //             deleteRow(oTable, nRow);
            //             nEditing = null;

            //         }

            //     });


            $('#keuangan-keluar-list a.cancel').live('click', function(e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-keluar-list a.edit').live('click', function(e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Simpan") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

}();


function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    location.href = `<?php echo base_url();?>hutang?start_date=${tgl1}&end_date=${tgl2}`;
}


function delete_data(id, terima_no) {
    if (confirm("Hapus Data Ini (" + terima_no + ")?")) {
        $.ajax({
            url: "<?php echo base_url(); ?>Hutang/hapus/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.status === true) {
                    swal('Hutang', 'Data berhasil dihapus...', '')
                        .then((value) => {
                            location.href = "<?php echo base_url()?>hutang";
                        });
                } else {
                    swal('Pembayaran Hutang', 'Data gagal dihapus...', '');
                }
            },
            error: function(data) {
                swal('Pembayaran Hutang', 'Data gagal dihapus...', '');
            }
        });
    }
}
</script>