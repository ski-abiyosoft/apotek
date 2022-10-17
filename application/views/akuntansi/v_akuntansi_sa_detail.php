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
            <span class="title-web">Finance <small>Kas & Bank</small>
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
                    Kas & Bank
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <center>
                    <h3 style='margin-top:0px;'>
                        <div>Mutasi Kas & Bank</div>
                        <div>Akun <?php echo $akun_kas_bank; ?></div>
                        <div>Periode <?php echo date('d-m-Y', strtotime($startdate)); ?> s.d. <?php echo date('d-m-Y', strtotime($enddate)); ?></div>
                    </h3>
                </center>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar" style="display: flex;justify-content: space-between;">
                    <div class="btn-group">
                      
                    </div>
                    
                    <div class="btn-group pull-right">
                        <a href="<?php echo base_url('Akuntansi_sa/export?accountno='.($accountno).'&startdate='.$startdate.'&enddate='.$enddate.'&akun_kas_bank='.$akun_kas_bank);?>"
                            class="btn btn-success">Excel</i></a>
                    </div>
                </div>

                <table class="table table-striped table-hover table-bordered" >
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center">Tanggal</th>
                            <th style="text-align: center">No Bukti</th>
                            <th style="text-align: center">Cek/Giro</th>
                            <th style="text-align: center">Kelompok</th>
                            <th style="text-align: center">Ketarangan</th>
                            <th style="text-align: center">Kas Masuk</th>
                            <th style="text-align: center">Kas Keluar</th>
                            <th style="text-align: center">Saldo Kas</th>
                            <th style="text-align: center">Lihat Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center" colspan="7"><strong>Saldo awal</strong></td>
                            <td style="text-align: right"><strong>Rp. <?= number_format(intval($saldo_awal), 0, ',', '.'); ?></strong></td>
                            <td style="text-align: center">-</td>
                        </tr>
                        <?php
                            if (count($keu) > 0):
                                foreach ($keu as $row):                                       
                        ?>
                                    <!-- <tr class="show1" id="row_<?php echo $row->nobukti; ?>"> -->
                                    <tr class="">
                                        <td align="center"><?php echo date('d-m-Y',strtotime($row->tanggal)); ?></td>
                                        <td align="center"><?php echo $row->nobukti; ?></td>
                                        <td align="center"><?php echo $row->cekgiro; ?></td>
                                        <td align="center"><?php echo $row->kelompok; ?></td>
                                        <td align="center"><?php echo $row->keterangan; ?></td>
                                        <td align="right">Rp. <?php echo number_format($row->kasmasuk,0,'.',','); ?></td>
                                        <td align="right">Rp. <?php echo number_format($row->kaskeluar,0,'.',','); ?></td>
                                        <td align="right">Rp. <?php echo number_format($row->saldokas,0,'.',','); ?></td>
                                        <td align="center">
                                            <a class="btn btn-sm btn-success" id="<?php echo $row->nobukti; ?>" href="#report" onclick="id_cetak('<?php echo $row->nobukti; ?>','<?php echo $row->kelompok; ?>')" data-toggle="modal" title="Detail">View</a>	
                                        </td>
                                    </tr>
                        <?php                    
                                endforeach;
                            else:
                        ?>
                            <tr>
                                <td style="text-align: center" colspan="9">Tidak ada mutasi selama periode ini.</td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="text-align: center" colspan="7"><strong>Saldo akhir</strong></td>
                            <td style="text-align: right"><strong>Rp. <?= number_format(intval($saldo_akhir), 0, ',', '.'); ?></strong></td>
                            <td style="text-align: center">-</td>
                        </tr>
                    </tbody>
                    <!-- <tfoot>

                        <td style="text-align:right">Total:</td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>
                        <td ></td>
                    </tfoot> -->

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
});

function currencyFormat(num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}


function id_cetak(id, kelompok) {
    var link = '';
    if(kelompok === 'KAS MASUK') link = 'keuangan_masuk/cetak/' + id;
    else if(kelompok === 'AR') link = 'penjualan_penerimaan/cetak/' + id;
    else if(kelompok === 'MUTASI MASUK') link = 'keuangan_transfer/cetak/' + id;
    else if(kelompok === 'MUTASI KELUAR') link = 'keuangan_transfer/cetak/' + id;
    else if(kelompok === 'BAYAR HUTANG') link = 'pembelian_bayar/cetak/' + id;
    else link = 'keuangan_keluar/cetak/' + id;

    $("#simkeureport").html('<iframe src="<?php echo base_url();?>' + link +
        '" frameborder="no" width="100%" height="520"></iframe>');
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
                // "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {

                //     var iTotal = 0;
                //     var iTotal1 = 0;
                //     var iTotal2 = 0;
                //     var iTotal3 = 0;
                //     for (var i = 0; i < aaData.length; i++) {
                //         iTotal += aaData[i][1] * 1;
                //         iTotal1 += aaData[i][2] * 1;
                //         iTotal2 += aaData[i][3] * 1;
                //         iTotal3 += aaData[i][4] * 1;
                //     }


                //     var iTot = 0;
                //     var iTot1 = 0;
                //     var iTot2 = 0;
                //     var iTot3 = 0;
                //     for (var i = iStart; i < iEnd; i++) {

                //         var x = aaData[aiDisplay[i]][1];
                //         // var y = Number(x.replace(/[^0-9\.]+/g, ""));
                //         var y = Number(x.replaceAll(',', ""));
                //         iTot += y;

                //         var x1 = aaData[aiDisplay[i]][2];
                //         // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                //         var y2 = Number(x1.replaceAll(',', ""));
                //         iTot1 += y2;
                        
                //         var x1 = aaData[aiDisplay[i]][3];
                //         // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                //         var y2 = Number(x1.replaceAll(',', ""));
                //         iTot2 += y2;
                        
                //         var x1 = aaData[aiDisplay[i]][4];
                //         // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                //         var y2 = Number(x1.replaceAll(',', ""));
                //         iTot3 += y2;
                //     }

                //     var nCells = nRow.getElementsByTagName('td');
                //     nCells[1].innerHTML = currencyFormat(iTot);
                //     nCells[2].innerHTML = currencyFormat(iTot1);
                //     nCells[3].innerHTML = currencyFormat(iTot2);
                //     nCells[4].innerHTML = currencyFormat(iTot3);
                // }
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
    // var vendor = document.getElementById("vendor").value;
    var str = '2~' + tgl1 + '~' + tgl2;
    location.href = "<?php echo base_url();?>Akuntansi_sa/filter/" + str;
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

jQuery(document).ready(function() {
    TableEditable.init();
    //    ComponentsPickers.init();

});
</script>


<style>
.dashboard-stat .details_v2 .number {
    padding-top: 15px;
    text-align: right;
    font-size: 34px;
    line-height: 34px;
    letter-spacing: -1px;
    margin-bottom: 5px;
    font-weight: 300;
    color: #fff;
}

.dashboard-stat .details_v2 .desc {
    text-align: right;
    font-size: 16px;
    letter-spacing: 0px;
    font-weight: 300;
    color: #fff;
}
</style>