
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	<link href="<?php echo base_url('assets/css/font_css.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
	
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Kas/Bank <small>Mutasi Kas/Bank</small>
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
                               Keuangan
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                               Data Mutasi Kas/Bank
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
								Daftar Mutasi Kas/Bank -
								<span>
								<b>
								<?php 
								  $bulan  = $this->M_global->_periodebulan();
								  $nbulan = $this->M_global->_namabulan($bulan);
								echo 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();?></b>
                                </span>
								
								
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								
								
								<div class="btn-group">
								   <?php if($akses->uadd==1){ ?>
								    <a href="<?php echo base_url()?>keuangan_transfer/entri" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Data Baru
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
										<li>
											<a href="<?php echo base_url()?>keuangan_transfer/export<?php echo $_SERVER['REQUEST_URI']; ?>">
												 Export
											</a>
										</li>								
									</ul>
								</div>

							</div>
							<table class="table table-striped table-hover table-bordered" id="keuangan-transfer-list">
                            <thead class="page-breadcrumb breadcrumb" style="color:white;">
                                     <tr>
									     <tr>
                                         <th style="text-align: center">Cab.</th>
                                         <th style="text-align: center">User ID</th>
                                         <th style="text-align: center">Nomor</th>
                                         <th style="text-align: center">Tanggal</th>
                                         <th style="text-align: center">Bank (Keluar)</th>
                                         <th style="text-align: center">Bank (Masuk)</th>
                                         <th style="text-align: center">Keterangan</th>
                                         <th style="text-align: center">Total</th>
                                         <th>&nbsp</th>
                                         <th>&nbsp</th>
                                         <th>&nbsp</th>
                                     </tr>
                                     </tr>
                                     </thead>

                                    
                                     <tbody>
                                     <?php
                                      
                                       $nomor = 1;
                                       foreach ($keu as $row)
                                       {   ?>

										 <tr class="show1" id="row_<?php echo $row->nomutasi;?>">
                                             <td><center><?php echo $row->koders;?></center></td>
											 <td><center><?php echo $row->username;?></center></td> 
                                             <td align="center"><?php echo $row->nomutasi;?></td>
											 <td align="center"><?php echo date('d-m-Y',strtotime($row->tglmutasi));?></td>
											 <td><?php echo $row->acdari." | ".$row->acdari_name; ?></td>
											 <td><?php echo $row->acke." | ".$row->acke_name; ?></td>
											 <td><?php echo $row->keterangan;?></td>
											 <td align="right"><?php echo number_format($row->mutasirp,0,'.',',');?></td>
											 <td align="center">
                                                <!-- 
                                                <a class="btn btn-sm btn-primary" href="'.base_url("pendaftaran/edit/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 				  
                                                <a class="btn btn-sm btn-warning" href="'.base_url("pendaftaran/cetak2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
                                                <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>
                                                <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>
                                                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-remove"></i> </a> 
                                                -->
												<?php
												     if($akses->uedit==1)
													 { ?>
                                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url()?>keuangan_transfer/edit/<?php echo $row->nomutasi;?>" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 
												    <!-- <a href="<?php echo base_url()?>keuangan_transfer/edit/<?php echo $row->nomutasi;?>">Edit</a></td> -->
												<?php }?>
											 </td>
											 <td align="center">
											    <?php
												     if($akses->udel==1)
													 { ?>
                                                     <a class="btn btn-sm btn-warning print_laporan" id="<?php echo $row->nomutasi;?>" href="#report" title="Cetak"  data-toggle="modal"><i class="glyphicon glyphicon-print"></i></a>				   
													<!-- <a class="delete" href="javascript:">Hapus</a> -->
												<?php }?>
											 </td>
											 <td align="center" style="border-bottom: 1px solid #ddd;">
												<?php
													 { ?>
												    <!-- <a class="print_laporan" id="<?php echo $row->nomutasi;?>" href="#report" data-toggle="modal">Cetak</a>  -->
												    <a class="btn btn-sm btn-danger delete" href="javascript:void(0)" title="Hapus" ><i class="glyphicon glyphicon-remove"></i> </a> 
                                                <?php }?>
											 </td>

										 </tr>
                                     <?php
                                          $nomor++;
                                     } ?>


                                     </tbody>
                                     <tfoot>

                                            <td colspan="7" style="text-align:right">Total:</td>
                                            <td style="text-align:right"></td>
                                            <td colspan="2"></td>


                                    </tfoot>

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
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/core/app.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/core/app.js')?>"></script>

<script>


function currencyFormat (num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
	
var TableEditable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }


            var oTable = $('#keuangan-transfer-list').dataTable({
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
                    }
                ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                console.log(aaData);
                
                var iTotal = 0;
                for ( var i=0 ; i<aaData.length ; i++ )
                {
                    iTotal += aaData[i][7]*1;
                }


                var iTot = 0;
                for ( var i=iStart ; i<iEnd ; i++ )
                {

                    var x = aaData[aiDisplay[i]][7];
                    var y = Number(x.replace(/[^0-9\.]+/g,""));
                    iTot += y;
                }

                var nCells = nRow.getElementsByTagName('td');
                nCells[1].innerHTML = currencyFormat(iTot);
                }
                    });

                jQuery('#keuangan-transfer-list_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-transfer-list_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-transfer-list_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-transfer-list_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '','','',
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Batal</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            function deleteRow ( oTable, nRow)
            {
                if (confirm("Hapus Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = row_id.substring(4,30);
                    
                    $.ajax( {
                        dataType: 'html',
                        type: "POST",
                        url: "<?php echo base_url(); ?>keuangan_transfer/hapus/"+mydata,	
                        cache: false,
                        data: mydata,
                        success: function () {
                            oTable.fnDeleteRow( nRow );
                            oTable.fnDraw();
                        },
                        error: function() {},
                        complete: function() {}
                    } );

                }
            }

            $('#keuangan-transfer-list a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


            $('#keuangan-transfer-list a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-transfer-list a.edit').live('click', function (e) {
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
    $('.print_laporan').on("click", function(){
		$('.modal-title').text('KAS/BANK');
		
		var param=this.id;				
				
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>keuangan_transfer/cetak/'+param+'" frameborder="no" width="100%" height="520"></iframe>');
    });	
});
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = '2~'+tgl1+'~'+tgl2; 
	location.href = "<?php echo base_url();?>keuangan_transfer/filter/"+str;
}		


$(".dataTables_empty").css('boder-bottom','1px solid #ddd');

jQuery(document).ready(function() {       
   TableEditable.init();
//    ComponentsPickers.init();
});
</script>
