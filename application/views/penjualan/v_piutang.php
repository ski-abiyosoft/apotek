<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>


<link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css" />
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
                    Piutang / AR -
                    <span><b>
                            <?php 
								   echo $periode;?>
                    </span></b>
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">


                    <div class="btn-group">
                        <?php if($akses->uadd){?>
                        <a href="<?php echo base_url()?>piutang/entri" class="btn btn-success">
                            <i class="fa fa-plus"></i>
                            Tambah Saldo Piutang
                        </a>
                        <?php } ?>
                    </div>

                    <div class="btn-group pull-right">
                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dashboard-stat blue">
                            <div class="" style='color:white;'>
                                <br>
                                <center>
                                    <div style="font-size:16px;">
                                        TOTAL PIUTANG TERBENTUK
                                    </div>
                                    <div style="font-size:14px;">
                                        Rp <?php echo number_format($totalPiutang,0, ',','.');?>
                                    </div>
                                </center>
                                <br>
                            </div>

                            <a data-toggle="modal" class="more"
                                href="<?= base_url('piutang/detailPiutangTerbentuk') ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dashboard-stat green">
                            <div class="" style='color:white;'>
                                <br>
                                <center>
                                    <div style="font-size:16px;">
                                        PIUTANG ASURANSI/PT
                                    </div>
                                    <div style="font-size:14px;">
                                        Rp <?php echo number_format($totalAsuransi,0, ',','.');?>
                                    </div>
                                </center>
                                <br>
                            </div>

                            <a data-toggle="modal" class="more"
                                href="<?= base_url('piutang/detailPiutangAsuransi_pt') ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dashboard-stat yellow">
                            <div class="" style='color:white;'>
                                <br>
                                <center>
                                    <div style="font-size:16px;">
                                        PIUTANG BPJS
                                    </div>
                                    <div style="font-size:14px;">
                                        TERBENTUK SIMRS Rp <?php echo number_format($totalSimrs,0, ',','.');?>
                                    </div>
                                    <div style="font-size:14px;">
                                        CASEMIX / INACBG Rp <?php echo number_format($totalInacbg,0, ',','.');?>
                                    </div>
                                </center>
                                <br>
                            </div>

                            <a data-toggle="modal" class="more" href="<?= base_url('piutang/detailPiutang_bpjs') ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <br>

                <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center">Penjamin</th>
                            <th style="text-align: center">Saldo Awal Piutang</th>
                            <th style="text-align: center">Total Piutang</th>
                            <th style="text-align: center">Casemix / INACBG</th>
                            <th style="text-align: center">Dibayar</th>
                            <th style="text-align: center">Saldo Akhir Piutang</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                            $nomor = 1;
                            foreach ($keu as $row): ?>
                        <tr class="show1">
                            <td><?php echo $row->cust_nama;?></td>
                            <td align="right"><?php echo number_format($row->starting_balance,0,'.',',');?></td>
                            <td align="right"><?php echo number_format($row->total_piutang,0,'.',',');?></td>
                            <td align="right"><?php echo number_format($row->total_casemix,0,'.',',');?></td>
                            <td align="right"><?php echo number_format($row->total_bayar,0,'.',',');?></td>
                            <td align="right"><?php echo number_format($row->starting_balance + $row->mutation,0,'.',',');?></td>
                            <td style="text-align: center">
                                <a class="btn btn-sm btn-warning"
                                    href="<?php echo base_url()?>piutang/detail_piutang/<?= $row->cust_id . '?fromdate=' . $startdate . '&todate=' . $enddate; ?>">Detail Piutang</a>
                            </td>
                            <td style="text-align: center">
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url()?>piutang/invoice/<?php echo $row->cust_id."?fromdate=".$startdate."&todate=".$enddate; ?>">Invoice</a>
                            </td>
                        </tr>
                        <?php
                            $nomor++;
                            endforeach; ?>
                    </tbody>
                    <tfoot>
                        <td style="text-align:right">Total:</td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td>&nbsp;</td>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="lupperiode" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
	<div class="modal-content">
		<span id="nopilih">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">Periode Data</h4>											
		</div>
		<div class="modal-body">										 		  
		  <form action="<?= base_url('piutang'); ?>" class="form-horizontal" method="GET">
				<div class="form-group">
					<label class="col-md-4 control-label">Mulai</label>
					<div class="col-md-6">
					  <input id="tanggal1" name="fromdate" class="form-control input-medium" type="date" value="<?php echo $startdate;?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">s/d</label>
					<div class="col-md-6">
					   <input id="tanggal2" name="todate" class="form-control input-medium" type="date" value="<?php echo $enddate;?>" />
					</div>
				</div>
                <button type="submit" style="display: block; margin: 0 auto;" id="btnfilter" class="btn green">Buka Data</button>
		 </form>
		</div>						
	</div>									
</div>								
</div>

<?php
  $this->load->view('template/footero');
  $this->load->view('template/v_report');
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
                    "sSearch": "Pencarian Data : ",
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
                    var iTotal2 = 0;
                    var iTotal3 = 0;
                    var iTotal4 = 0;
                    var iTotal5 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][1] * 1;
                        iTotal2 += aaData[i][2] * 1;
                        iTotal3 += aaData[i][3] * 1;
                        iTotal4 += aaData[i][4] * 1;
                        iTotal5 += aaData[i][5] * 1;
                    }

                    var iTot = 0;
                    var iTot2 = 0;
                    var iTot3 = 0;
                    var iTot4 = 0;
                    var iTot5 = 0;
                    for (var i = iStart; i < iEnd; i++) {
                        var x1 = aaData[aiDisplay[i]][1];
                        var x2 = aaData[aiDisplay[i]][2];
                        var x3 = aaData[aiDisplay[i]][3];
                        var x4 = aaData[aiDisplay[i]][4];
                        var x5 = aaData[aiDisplay[i]][5];
                        // /^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''

                        // var y1 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        // var y2 = Number(x2.replace(/[^0-9\.]+/g, ""));
                        // var y3 = Number(x3.replace(/[^0-9\.]+/g, ""));
                        // var y4 = Number(x4.replace(/[^0-9\.]+/g, ""));

                        var y1 = Number(x1.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        var y2 = Number(x2.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        var y3 = Number(x3.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        var y4 = Number(x4.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        var y5 = Number(x5.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));

                        iTot += y1;
                        iTot2 += y2;
                        iTot3 += y3;
                        iTot4 += y4;
                        iTot5 += y5;
                    }

                    var nCells = nRow.getElementsByTagName('td');
                    nCells[1].innerHTML = currencyFormat(iTot);
                    nCells[2].innerHTML = currencyFormat(iTot2);
                    nCells[3].innerHTML = currencyFormat(iTot3);
                    nCells[4].innerHTML = currencyFormat(iTot4);
                    nCells[5].innerHTML = currencyFormat(iTot5);
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

            function deleteRow(oTable, nRow) {
                if (confirm("Hapus Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = row_id.substring(4, 30);

                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "<?php echo base_url(); ?>penjualan_penerimaan/hapus/" + mydata,
                        cache: false,
                        data: mydata,
                        success: function() {
                            oTable.fnDeleteRow(nRow);
                            oTable.fnDraw();
                        },
                        error: function() {},
                        complete: function() {}
                    });

                }
            }

            $('#keuangan-keluar-list a.delete').live('click', function(e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if (nRow) {
                    deleteRow(oTable, nRow);
                    nEditing = null;

                }

            });


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

$(document).ready(function() {

    $('.print_laporan').on("click", function() {
        $('.modal-title').text('PEMBELIAN');

        var param = this.id;

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>piutang/cetak/' +
            param + '" frameborder="no" width="100%" height="420"></iframe>');
    });
});

function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var str = '2~' + tgl1 + '~' + tgl2;
    location.href = "<?php echo base_url();?>piutang/filter/" + str;
}


jQuery(document).ready(function() {
    TableEditable.init();
    ComponentsPickers.init();

});
</script>