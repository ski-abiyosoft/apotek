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
								        echo $periode;
                                    ?>
                        </b>
                    </span>
                </div>
                <div class="pull-right">
                    <div class="caption">
                        <?php 
                                    echo $vendor == '' ? 'Semua Vendor' : $vendor;
                                ?>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">
                        <?php if($akses->uadd){?>
                        <a href="<?php echo base_url()?>hutang/entri" class="btn btn-success">
                            <i class="fa fa-plus"></i>
                            Faktur Baru
                        </a>
                        <?php } ?>
                    </div>

                    <div class="btn-group pull-right">
                        <!-- <a href="<?php echo base_url()?>hutang/export" class="btn btn-success">  -->
                        <a href="<?php echo base_url('hutang/export?startdate='.$startdate.'&enddate='.$enddate.'&vendor='.$vendorid); ?>"
                            target="_blank" class="btn btn-success">
                            <!-- style='margin-right:10px;'> -->
                            <i class="fa fa-download"></i> Excel
                        </a>
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
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat blue">
                            <div class="visual">
                                <i class="fa fa-barcodex"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <?php echo number_format($total_hutang,0, ',','.');?>

                                </div>

                                <div class="desc">
                                    HUTANG TERBENTUK PERIODE KINI
                                </div>
                            </div>

                            <a data-toggle="modal" class="more"
                                href="<?= base_url('hutang/detailTotalHutang?startdate='.$startdate.'&enddate='.$enddate) ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat red">
                            <div class="visual">
                                <i class="fa fa-printx"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <?php echo number_format($hutang_jatuh_tempo,0, ',','.');?>

                                </div>
                                <div class="desc">
                                    HUTANG JATUH TEMPO
                                </div>
                            </div>

                            <a data-toggle="modal" class="more"
                                href="<?= base_url('hutang/detailHutangjatuhTempo?startdate='.$startdate.'&enddate='.$enddate) ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat green">
                            <div class="visual">
                                <i class="fa fa-shopping-cartx"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <?php echo number_format($rencana_bayar,0, ',','.');?>

                                </div>
                                <div class="desc">
                                    RENCANA BAYAR PERIODE KINI
                                </div>
                            </div>
                            <a data-toggle="modal" class="more"
                                href="<?= base_url('hutang/detailRencanaBayar?startdate='.$startdate.'&enddate='.$enddate) ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat yellow">
                            <div class="visual">
                                <i class="fa fa-shopping-cartx"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <?php echo number_format($realisasi_pembayaran,0, ',','.');?>
                                </div>
                                <div class="desc">
                                    REALISASI PEMBAYARAN PERIODE KINI
                                </div>
                            </div>
                            <a data-toggle="modal" class="more"
                                href="<?= base_url('hutang/detailRealisasiPembayaran?startdate='.$startdate.'&enddate='.$enddate) ?>">
                                Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <br>

                <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center">Cab.</th>
                            <th style="text-align: center">Vendor</th>
                            <th style="text-align: center">Saldo Awal</th>
                            <th style="text-align: center">Penambahan Periode Kini</th>
                            <th style="text-align: center">Pembayaran Periode Kini</th>
                            <th style="text-align: center">Saldo Akhir</th>
                            <th style="text-align: center">Details</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                                      if($keu != ''){
                                       $nomor = 1;
                                       foreach ($keu as $row)
                                       {   
									     
									     ?>

                        <tr class="show1">
                            <td align="center"><?php echo $this->session->userdata("unit") ?></td>
                            <td align="left"><?php echo "$row->vendor_id | $row->vendor_name";?></td>
                            <td class="text-right"><?php echo "Rp " . accounting_number($row->saldo_awal ?? 0);?></td>
                            <td class="text-right"><?php echo "Rp " . accounting_number($row->totaltagihan);?></td>
                            <td class="text-right"><?php echo "Rp " . accounting_number($row->totalbayar);?></td>
                            <td class="text-right"><?php echo "Rp " . accounting_number($row->saldo_awal + $row->totaltagihan - $row->totalbayar);?></td>
                            <td align="center"><a class="btn blue" href="<?= base_url('hutang/details/'.$row->vendor_id.'?startdate='.$startdate.'&enddate='.$enddate) ?>"><i class="fa fa-info-circle"></i> Details</a></td>
                        </tr>
                        <?php
                                $nomor++;
                            } 
                        }?>


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

    $('#keuangan-keluar-list').DataTable()
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