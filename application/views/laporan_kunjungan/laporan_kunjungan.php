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
            <span class="title-web">Finance <small>Laporan Kunjungan Pasien</small>
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
                    Laporan Kunjungan Pasien
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
                    Laporan Kunjungan Pasien :

                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar" style="display: flex;justify-content: space-between;">

                    <form id="form-filter" action="<?php echo base_url(); ?>laporan_kunjungan/export" method="POST">
                        <div class="btn-group">
                            <div class="btn-group" style="display:flex;">
                                <div style='padding:8px;' >Berdasarkan:</div>
                                <select type="text" class="form-control" id="jenis_kunjungan" name="jenis_kunjungan" required>
                                    <option value="nilai_pembelanjaan">Nilai Pembelanjaan</option>
                                    <option value="frekuensi">Frekuensi</option>
                                </select>
                            </div>
                            <div class="btn-group" style="display:flex;margin-left:20px;">
                                <div style='padding:8px;'>Dari</div>
                                <input id="startdate" name="startdate" class="form-control input-medium" type="date"
                                    value="<?php echo $startdate; ?>" required />
                                <div style='padding:8px;'>s./d.</div>
                                <input id="enddate" name="enddate" class="form-control input-medium" type="date"
                                    value="<?php echo $enddate; ?>" required />
                                &nbsp;&nbsp;
                                <!-- <input type="submit" name="proses" class="btn btn-primary dropdown-toggle"
                                    value="Proses"> -->
                                    
                                <!-- <input type="submit" name="proses" class="btn btn-danger dropdown-toggle" target="_blank"
                                    value="Print"> -->

                                <a class="print_laporan_kunjungan btn btn-danger" href="#report" data-toggle="modal">Cetak</a>			
                                <!-- <input type="submit" name="proses" class="btn btn-success dropdown-toggle"
                                    value="Excel"> -->
                            </div>
                        </div>
                    </form>
                    <!-- <div class="btn-group" style="float:left;">
                        <a href="<?php echo base_url()."piutang/export/1?startdate=".$startdate."&enddate=".$enddate; ?>" class="btn btn-danger">Print</i></a>
                        <a href="<?php echo base_url()."piutang/export/2?startdate=".$startdate."&enddate=".$enddate; ?>"
                            class="btn btn-success">Excel</i></a>
                    </div> -->

                </div>
                <br>

                <!-- <div class="table-responsive">
                    <table id="keuangan-keluar-list" class="table table-striped table-hover table-bordered" style="overflow: auto; white-space: nowrap; display: inline-block;">
                    <thead>
                        <tr>
                            <th style="text-align: center">No. Member</th>
                            <th style="text-align: center">Cabang Awal</th>
                            <th style="text-align: center">Nama</th>
                            <th style="text-align: center">Jenis Kelamin</th>
                            <th style="text-align: center">Tanggal Lahir</th>
                            <th style="text-align: center">Umur</th>
                            <th style="text-align: center">Alamat</th>
                            <th style="text-align: center">No. Telepon</th>
                            <th style="text-align: center; width: 10px !important;" >Konsultasi / Tindakan</th>
                            <th style="text-align: center">Dokter</th>
                            <th style="text-align: center">Paramedis</th>
                            <th style="text-align: center">Tgl. Kunjungan Pertama</th>
                            <th style="text-align: center">Tgl. Kunjungan Terakhir</th>
                            <th style="text-align: center">Frekuensi Kunjungan</th>
                            <th style="text-align: center">Nilai Total Pembelanjaan</th>
                        
                        </tr>
                    </thead>
                    <tbody id="dataPiutang">
                        <?php                                      
                            $nomor = 1;
                            
                            
                            // echo "<pre>";
                            // print_r($keu);
                            // echo "</pre>";
                            
                            foreach ($keu as $row)
                            {   
									     ?>
                        <tr class="show1">
                            <td align="center"><?php echo $row->rekmed;?></td>
                            <td align="center"><?php echo $row->koders;?></td>
                            <td align="center"><?php echo $row->namapas;?></td>
                            <td align="center"><?php echo $row->jkel;?></td>
                            <td align="center"><?php echo date('d-m-Y',strtotime($row->tgllahir));?></td>
                            <td><?php echo $row->umur;?></td>
                            <td><?php echo $row->alamat;?></td>
                            <td><?php echo $row->telp;?></td>
                            <td><?php echo wordwrap($row->konsultasi_tindakan, 50,"<br>\n"); ?></td>
                            <td><?php echo wordwrap($row->nadokter, 50,"<br>\n"); ?></td>
                            <td>-</td>
                            <td><?php echo $row->tgl_kunjungan_pertama; ?></td>
                            <td><?php echo $row->tgl_kunjungan_terakhir; ?></td>
                            <td align="right"><?php echo number_format($row->frekuensi,2,'.',','); ?></td>
                            <td align="right"><?php echo number_format($row->nilai_pembelanjaan,2,'.',','); ?></td>
                        </tr>
                        <?php
                                $nomor++;
                            } ?>


                    </tbody>
                    <tfoot>

                        <td colspan="13" style="text-align:right">Total:</td>
                        <td style="text-align:right"></td>
                        <td style="text-align:right"></td>


                    </tfoot>

                </table>
                </div> -->
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
    return "" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}


$(document).ready(function() {		
    $('.print_laporan_kunjungan').on("click", function(){
		$('.modal-title').text('Laporan Kunjungan Pasien');
		
        var jenis_kunjungan = $('#jenis_kunjungan').val();
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();

        console.log(jenis_kunjungan + " - " + startdate + " - " + enddate);
 		
        // if (startdate=='' || startdate== null || enddate=='' || enddate== null){
        //     swal({
        //             title: "Tanggal",
        //             html: "<p>Mohon isi tanggal terlebih dahulu !</p>",
        //             type: "error",
        //             confirmButtonText: "OK" 
        //         }); 

        //     $('.modal-title').modal('hide');
        //     $('.modal').modal('hide');  
        //     return;
        // } else {
		    $("#simkeureport").html('<iframe src="<?php echo base_url();?>laporan_kunjungan/export/1?jenis_kunjungan='+jenis_kunjungan+'&startdate='+startdate+'&enddate='+enddate+'" frameborder="no" width="100%" height="520"></iframe>');
        // }
    });	
});

function activeBtn(cls) {
    console.log(cls);
    removeBtnBackground();
    $('.' + cls).toggleClass("btn-active");

    $('input[name="asal"]').val(cls);
}

function removeBtnBackground() {
    $('.semua').removeClass("btn-active");
    $('.poli').removeClass("btn-active");
    $('.inap').removeClass("btn-active");
}



var TableEditable = function() {

    return {

        //main function to initiate the module
        init: function() {
            function restoreRow(oTable, nRow) {
                
                    console.log('asdasdasd123123');
                console.log(oTable);
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                console.log(oTable);
                oTable.fnDraw();
            }


            var oTable = $('#keuangan-keluar-list').dataTable({
                "order": [[13, 'desc']],

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
                    // "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data : ",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "Sebelumnya",
                        "sNext": "Berikutnya"
                    }
                },
                // "aoColumnDefs": [{
                //     'bSortable': false,
                //     'aTargets': [0]
                // }],
                "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {

                    var iTotal = 0;
                    var iTotal1 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][13] * 1;
                        iTotal1 += aaData[i][14] * 1;
                    }


                    var iTot = 0;
                    var iTot1 = 0;
                    for (var i = iStart; i < iEnd; i++) {
                        var x = aaData[aiDisplay[i]][13];
                        // var y = Number(x.replace(/[^0-9\.]+/g, ""));
                        var y = Number(x.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot += y;

                        var x1 = aaData[aiDisplay[i]][14];
                        // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        var y1 = Number(x1.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot1 += y1;
                    }

                    var nCells = nRow.getElementsByTagName('td');
                    nCells[1].innerHTML = currencyFormat(iTot);
                    nCells[2].innerHTML = currencyFormat(iTot1);
                }
            });

            
                    console.log(oTable);
                    
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
                        url: "<?php echo base_url(); ?>hutang/hapus/" + mydata,
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

        $("#simkeureport").html('<iframe src="<?php echo base_url();?>hutang/cetak/' + param +
            '" frameborder="no" width="100%" height="420"></iframe>');
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

<style>
.btn-active {
    background: #ccc;
}
</style>