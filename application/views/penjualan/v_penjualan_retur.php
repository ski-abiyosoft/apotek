<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>


<!-- <link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/> -->
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet"
    type="text/css" />
    <style>
        .editbuttondouble:before{
            color: #fff;
            float: left;
            font-size: 18px;
            line-height: 16px;
            margin: 0;
            text-indent: 0;
        }
    </style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Farmasi <small>Retur Penjualan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">

            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">
                    Penjualan
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url();?>penjualan_retur">
                    Data Retur
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
                    Daftar Retur Penjualan -
                    <span>
                        <b><?php 
								   echo $periode;?></b>
                    </span>
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">


                    <div class="btn-group">
                        <?php if($akses->uadd){?>
                            <?php 
                            $cek =  $this->session->userdata('user_level'); 
                            if($cek==0){?> 
                            <?php }else{ ?>

                                <a href="<?php echo base_url()?>penjualan_retur/entri" class="btn btn-success">
                                    <i class="fa fa-plus"></i>
                                    <b>Transaksi Baru</b>
                                </a>

                            <?php } ?>
                        
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
                <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="title-white" style="text-align: center">Cab.</th>
                            <th class="title-white" style="text-align: center" width="150">User ID</th>
                            <th class="title-white" style="text-align: center">No. Retur</th>
                            <th class="title-white" style="text-align: center">No. Resep</th>
                            <th class="title-white" style="text-align: center">Rekmed</th>
                            <th class="title-white" style="text-align: center">Nama Pasien</th>
                            <th class="title-white" style="text-align: center">Jumlah Rp</th>
                            <th class="title-white" style="text-align: center">Tanggal</th>
                            <th class="title-white" style="text-align: center">Gudang</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                        $nomor = 1;
                        foreach ($keu as $row) : ?>
                        <tr class="show1" id="row_<?php echo $row->returno;?>">
                            <td align="center"><?php echo $row->koders;?></td>
                            <td align="center"><?php echo $row->username; ?></td>
                            <td align="center"><?php echo $row->returno;?></td>
                            <td align="center"><?php echo $row->resepno;?></td>
                            <td align="center"><?php echo $row->rekmed;?></td>
                            <td align="center"><?php echo $row->namapas;?></td>
                            <td>Rp. <span align="right"><?php echo number_format($row->totalrp);?></span></td>
                            <!-- <td>Rp. <span align="right"><?php echo number_format($row->totalnet);?></span></td> -->
                            <td align="center"><?php echo date('d-m-Y',strtotime($row->tglretur));?></td>
                            <td><?php echo $row->gudang;?></td>
                            <td style="text-align: center">
                                <?php if ($row->posting=='0') : ?>
                                    <!-- <a type="button" class="btn btn-sm btn-primary editbuttondouble" href="<?php echo base_url()?>penjualan_retur/edit/<?php echo $row->returno;?>">edit</a> -->
                                    <?php 
                                    $cek =  $this->session->userdata('user_level'); 
                                    if($cek==0){?> 
                                        <a type="button" class="btn btn-sm btn-primary" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                    <?php }else{ ?>

                                        <a type="button" class="btn btn-sm btn-primary" href="<?php echo base_url('penjualan_retur/edit/').$row->returno; ?>" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>

                                    <?php } ?>
<<<<<<< HEAD

=======
>>>>>>> 2b048ebe2756fae81a8015c3e19900a3e1c0e10a
                                <?php endif;?>
                            </td>
                            <td style="text-align: center">
                                <?php if ($row->posting=='0') : ?>
                                    <a target="_blank" type="button" class="btn btn-sm btn-warning" 
                                    href="<?php echo base_url()?>penjualan_retur/cetak/?id=<?php echo $row->returno;?>"><i class="glyphicon glyphicon-print"></i></a>
                                <?php endif;?>
                            </td>
                            <td style="text-align: center">
                                <?php if ($row->posting=='0') : ?>
<<<<<<< HEAD

=======
>>>>>>> 2b048ebe2756fae81a8015c3e19900a3e1c0e10a
                                    <?php 
                                    $cek =  $this->session->userdata('user_level'); 
                                    if($cek==0){?> 
                                        <a type="button" class="btn btn-sm btn-danger" href="javascript:"><i class="glyphicon glyphicon-trash"></i></a>
                                    <?php }else{ ?>
                                        <a type="button" class="delete btn btn-sm btn-danger" href="javascript:"><i class="glyphicon glyphicon-trash"></i></a>
                                    <?php } ?>
                                    
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php endforeach; ?>


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
                        url: "<?php echo base_url(); ?>penjualan_retur/hapus/" + mydata,
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
        $('.modal-title').text('Penjualan');

        var param = this.id;

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>penjualan_retur/cetak/' + param +
            '" frameborder="no" width="100%" height="420"></iframe>');
    });
});

function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var str = '2~' + tgl1 + '~' + tgl2;
    location.href = "<?php echo base_url();?>penjualan_retur/filter/" + str;
}


jQuery(document).ready(function() {
    TableEditable.init();
    //    ComponentsPickers.init();

});
</script>