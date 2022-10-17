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

            <!-- <form1 action="<?php echo base_url(); ?>piutang/tagihan_pelanggan_entry" method="POST"> -->
                <div class="portlet-title">
                    <div class="caption">
                        Daftar Piutang :

                        <span>
                            <b><?php 
                                    echo $cust_nama;?></b>
                        </span>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar" style="display: flex;justify-content: space-between;">

                        <form id="form-filter" action="<?php echo base_url(); ?>piutang/pilih_pasien_edit" method="POST">
                            <input type="hidden" id="cust_id" name="cust_id" value="<?php echo $cust_id; ?>" />
                            <input type="hidden" id="cust_nama" name="cust_nama" value="<?php echo $cust_nama; ?>" />
                            <div class="btn-group">
                                <?php if($akses->uadd){
                                
                                    // echo $asal;
                                ?>

                                <a class="btn btn-default semua <?php echo $asal == '' || $asal == 'semua' ? 'btn-active' : '' ?>"
                                    onclick="activeBtn('semua')">
                                    Semua
                                    <input type="hidden" id="asal" name="asal" />
                                </a>

                                <a class="btn btn-default poli <?php echo $asal == 'poli' ? 'btn-active' : '' ?>"
                                    onclick="activeBtn('poli')">
                                    Rawat Jalan
                                    <input type="hidden" id="asal" name="asal" />
                                </a>
                                <a class="btn btn-default inap <?php echo $asal == 'inap' ? 'btn-active' : '' ?>"
                                    onclick="activeBtn('inap')">
                                    Rawat Inap
                                    <input type="hidden" id="asal" name="asal" />
                                </a>
                                <?php } ?>

                                <div class="btn-group" style="display:flex;">
                                    <div style='margin-left:15px;padding:8px;'>Dari</div>
                                    <input id="startdate" name="startdate" class="form-control input-medium" type="date"
                                        value="<?php echo $startdate; ?>" required />
                                    <div style='padding:8px;'>s./d.</div>
                                    <input id="enddate" name="enddate" class="form-control input-medium" type="date"
                                        value="<?php echo $enddate; ?>" required />
                                    &nbsp;&nbsp;
                                    <input type="submit" name="proses" class="btn btn-primary dropdown-toggle"
                                        value="Proses">
                                </div>
                            </div>
                        </form>
                        <!-- <div class="btn-group" style="float:left;">
                            <a href="<?php echo base_url()."piutang/export/1?cust_id=".$cust_id."&asal=".$asal."&startdate=".$startdate."&enddate=".$enddate; ?>" class="btn btn-danger">Print</i></a>
                            <a href="<?php echo base_url()."piutang/export/2?cust_id=".$cust_id."&asal=".$asal."&startdate=".$startdate."&enddate=".$enddate; ?>"
                                class="btn btn-success">Excel</i></a>
                        </div> -->
                        
                        <div class="btn-group" style="float:left;">
                            <input id="search" name="search" class="form-control input-medium" type="text" />
                        </div>
                    </div>
                    <!-- <table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list"> -->
                    <table id="datatable" class="table table-striped table-hover table-bordered" >
                        <thead>
                            <!-- <tr> -->
                                <th style="text-align: center">Pilih</th>
                                <th style="text-align: center">No. Faktur</th>
                                <th style="text-align: center">No. Reg</th>
                                <th style="text-align: center">Rekmed</th>
                                <th style="text-align: center">Tgl AR</th>
                                <th style="text-align: center">No Kartu</th>
                                <th style="text-align: center">No Sep</th>
                                <th style="text-align: center">Nama Pasien</th>
                                <th style="text-align: center">Asal</th>
                                <th style="text-align: center">Jumlah Piutang</th>
                                <!-- <th>&nbsp;</th>
                                <th>&nbsp;</th> -->
                            <!-- </tr> -->
                        </thead>
                        <tbody id="dataPiutang">
                        
                            <?php                                      
                            $nomor = 0;
                            $total = 0;
                            

                            foreach ($keu as $row)
                            {   
                        ?>
                            <tr class="show1" id="row_<?php echo $row->noreg;?>">
                                <td style='text-align: center'>
                                    <!-- ."~".$row->rekmed -->
                                    <input type='checkbox' id='<?php echo $nomor; ?>' name='ditagihkan[]' value="'<?php echo $row->noreg; ?>'" > 
                                </td>
                                <td align="center"><?php echo $row->fakturno;?></td>
                                <td align="center">
                                    <?php echo $row->noreg;?>
                                    <input type="hidden" name='noreg[]' value="'<?php echo $row->noreg;?>'">    
                                </td>
                                <td align="center"><?php echo $row->rekmed;?></td>
                                <td align="center"><?php echo date('d-m-Y',strtotime($row->tglposting));?></td>
                                <td><?php echo $row->nocard;?></td>
                                <td><?php echo $row->nosep;?></td>
                                <td><?php echo $row->namapas;?></td>
                                <td><?php echo $row->asal == 'POLI' ? 'RAJAL' : 'RANAP'; ?></td>
                                <td align="right"><?php echo number_format($row->jumlahhutang,2,'.',','); ?></td>
                            </tr>
                            <?php
                                $nomor++;
                                $total += ($row->jumlahhutang);
                            } ?>


                        </tbody>
                    </table>
                </div>
                
            <!-- <form action="<?php echo base_url(); ?>piutang/tagihan_pelanggan_entry" method="POST"> -->
                <input type="hidden" id="cust_id" name="cust_id" value="<?php echo $cust_id; ?>" />
                <input type="hidden" id="cust_nama" name="cust_nama" value="<?php echo $cust_nama; ?>" />
                
                <input type="hidden" id="jenis" name="jenis" value="<?php echo $asal; ?>"/>
                <input id="startdate" name="startdate" class="form-control input-medium" type="hidden"
                    value="<?php echo $startdate; ?>" />
                <input id="enddate" name="enddate" class="form-control input-medium" type="hidden"
                    value="<?php echo $enddate; ?>" />
                <!-- <input type='hidden' id='ditagihkan2[]' name='ditagihkan2[]' class='ditagihkan2'> -->
                
                
                <div class="row form-actions">
                    <div class="col-xs-8">
                        <div class="wells">
                            <button type="button" class="btn blue" name="pilih_pasien" value="pilih_pasien" onclick="pilihpasien()"><i class="fa fa-save"></i>
                                Pilih Pasien</button>
                            <button type="button" class="btn green"
                                name="pilih_pasien" value="pilih_pasien" onclick="pilihsemuapasien()"><i class="fa fa-save"></i>&nbsp;<i class="fa fa-save"></i>
                                Pilih Semua Pasien</button>
                            <!-- <button type="button" class="btn yellow" onclick=""><i class="fa fa-print"></i> Cetak</button> -->
                            <input type="hidden" id="id" name="id" class="form-control rightJustified">

                            <h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span
                                    id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
                        </div>
                    </div>
                    <!-- 
                    <div class="col-xs-4 invoice-block">
                        <div class="well">
                            <table id="tabeltotal">
                                <tbody>
                                    <tr>
                                    <td width="40%"><strong>TOTAL</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="59" align="right"><strong><span id="_vtotalnet"><?php echo number_format($total,2,'.',','); ?></span></strong></td>
                                    </tr>
                                <input type='hidden' id='totalnetrp' name='totalnetrp' class="form-control">
                            </tbody></table>
                        </div>	
                    </div> 
                    -->
                    
                </div>
            <!-- </form> -->
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

<script type="text/javascript">
	var $rows = $('#dataPiutang tr'); 
    
    $('#search').keyup(function(){
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>

<script>

function currencyFormat(num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function pilihpasien(){
    var dt = $('input[name^="ditagihkan"]:checked').map(function(){return $(this).val();}).get();    
    console.log(dt);
    
    var cust_id     = '<?php echo $cust_id; ?>';
    var cust_nama   = '<?php echo $cust_nama; ?>';
    var jenis       = '<?php echo $asal; ?>';
    var startdate   = '<?php echo $startdate; ?>';
    var enddate     = '<?php echo $enddate; ?>';

    // var startdate2 = $('#startdate').val();
    // var enddate2 = $('#enddate').val();

    
    // if(new Date(startdate).getTime() < new Date(startdate2).getTime()){
    //     startdate = startdate2;
    // }
    
    // if(new Date(enddate).getTime() > new Date(enddate2).getTime()){
    //     enddate = enddate2;
    // }

    var url = "<?php echo base_url().'piutang/tagihan_pelanggan_edit'; ?>";
    location.href = url +'?pilih_pasien=1&cust_id=' + cust_id + '&cust_nama=' + cust_nama + '&jenis=' + jenis + '&startdate=' + startdate + '&enddate=' + enddate + '&ditagihkan=' + dt;
    
}


function pilihsemuapasien(){
    var dt = $('input[name^="noreg"]').map(function(){return $(this).val();}).get();    
    console.log(dt);
    
    var cust_id     = '<?php echo $cust_id; ?>';
    var cust_nama   = '<?php echo $cust_nama; ?>';
    var jenis       = '<?php echo $asal; ?>';
    var startdate   = '<?php echo $startdate; ?>';
    var enddate     = '<?php echo $enddate; ?>';
    
    var url = "<?php echo base_url().'piutang/tagihan_pelanggan_edit'; ?>";
    location.href = url +'?pilih_pasien=1&cust_id=' + cust_id + '&cust_nama=' + cust_nama + '&jenis=' + jenis + '&startdate=' + startdate + '&enddate=' + enddate + '&ditagihkan=' + dt;
    
}

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


// var cls = '<?php echo $asal; ?>';
// activeBtn(cls);

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
                    var iTotal1 = 0;
                    var iTotal2 = 0;
                    var iTotal3 = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        iTotal += aaData[i][9] * 1;
                        iTotal1 += aaData[i][10] * 1;
                        iTotal2 += aaData[i][11] * 1;
                        iTotal3 += aaData[i][12] * 1;
                    }


                    var iTot = 0;
                    var iTot1 = 0;
                    var iTot2 = 0;
                    var iTot3 = 0;
                    for (var i = iStart; i < iEnd; i++) {
                        var x = aaData[aiDisplay[i]][9];
                        // var y = Number(x.replace(/[^0-9\.]+/g, ""));
                        var y = Number(x.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot += y;

                        var x1 = aaData[aiDisplay[i]][10];
                        // var y2 = Number(x1.replace(/[^0-9\.]+/g, ""));
                        var y1 = Number(x1.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot1 += y1;

                        var x2 = aaData[aiDisplay[i]][11];
                        // var y2 = Number(x2.replace(/[^0-9\.]+/g, ""));
                        var y2 = Number(x2.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot2 += y2;

                        var x3 = aaData[aiDisplay[i]][12];
                        // var y3 = Number(x3.replace(/[^0-9\.]+/g, ""));
                        var y3 = Number(x3.replace(/^\.|[^-?\d\.]|\.(?=.*\.)|^0+(?=\d)/g, ''));
                        iTot3 += y3;
                    }

                    var nCells = nRow.getElementsByTagName('td');
                    nCells[1].innerHTML = currencyFormat(iTot);
                    nCells[2].innerHTML = currencyFormat(iTot1);
                    nCells[3].innerHTML = currencyFormat(iTot2);
                    nCells[4].innerHTML = currencyFormat(iTot3);
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