
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	<link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
	
			<!--div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  <span class="title-unit">
                            <?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Pembelian <small>Order Pembelian</small>
					</h3>
				</div>
			</div-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box yellow">
						<div class="portlet-title">
							<div class="caption">
								Pesanan Pembelian
							</div>

						</div>
						<div class="portlet-body">
                          <div class="tabbable-custom ">
								<ul class="nav nav-pills ">
									<li class="">
										<a href="#tab1" data-toggle="tab">
											 <i class="fa fa-list"></i>
										</a>
									</li>
									<li class="active">
										<a href="#tab2" data-toggle="tab">
											 *Data Baru
										</a>
									</li>
									
								</ul>
						
						  
						    <div class="tab-content">
								<div class="tab-pane" id="tab1">

						
									<div class="table-toolbar">
										
										
										<div class="btn-group">
											<a href="" onclick="location.reload()" class="btn btn-success">
											<i class="fa fa-refresh"></i>
											
											</a>
										</div>	

									</div>
									<table class="table table-striped table-hover table-bordered" id="keuangan-transfer-list">
									<thead>
											 <tr>
												 <tr>
												 <th style="text-align: center">Nomor</th>
												 <th style="text-align: center">Tanggal</th>
												 <th style="text-align: center">Pemasok</th>
												 <th style="text-align: center">Keterangan</th>
												 <th style="text-align: center">Status</th>
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

												 <tr class="show1" id="row_<?php echo $row->nomor;?>">
													 <td align="center"><?php echo $row->nomor;?></td>
													 <td align="center"><?php echo date('d-m-Y',strtotime($row->tanggal));?></td>
													 <td><?php echo $row->bank_sumber;?></td>
													 <td><?php echo $row->bank_tujuan;?></td>
													 <td><?php echo $row->uraian;?></td>
													 <td align="right"><?php echo number_format($row->jumlah,0,'.',',');?></td>
													 <td align="center">
														<?php
														 
															 { ?>
														<a href="<?php echo base_url()?>keuangan/keuangan_transfer/edit/<?php echo $row->nomor;?>">Edit</a></td>
														<?php }?>
													 </td>
													 <td align="center">
													   <?php
														  
															 { ?>
															<a class="delete" href="javascript:">Hapus</a>
														   <?php }?>
													 </td>
													 <td align="center">
														<?php
															 { ?>
														<a class="print_laporan" id="<?php echo $row->nomor;?>" href="#report" data-toggle="modal">Cetak</a> 
														<?php }?>
													 </td>

												 </tr>
											 <?php
												  $nomor++;
											 } ?>


											 </tbody>
											 <tfoot>

													<td colspan="5" style="text-align:right">Total:</td>
													<td style="text-align:right"></td>
													<td colspan="4"></td>


											</tfoot>

									</table>
								</div> <!--tab1-->
								
								<div class="tab-pane active" id="tab2">
								  <div class="row">
								  <div class="col-md-11">
												
								    <form id="frmkeuangan" class="form-horizontal" method="post">
											<div class="form-body">
											  <div class="row">
												  
												    <div class="col-md-6">
														<div class="form-group">
																<label class="col-md-3 control-label">Pemasok</label>
																<div class="col-md-9">
																  <select id="pemasok" name="pemasok" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
																  <option value="">&nbsp</option>
																<?php
																 foreach($pemasok as $row)
																  { ?>
																	<option value="<?php echo $row->kodeakun;?>"><?php echo $row->namaakun;?></option>
																<?php } ?>
																</select>
																</div>

														</div>
													</div>	
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor Bukti</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nomor;?>" onkeypress="return tabE(this,event)">
													        </div>

														</div>
													</div>
													
													
												</div>
												
												<div class="row">
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-6">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="text" value="" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
													    	   </div>
													        </div>
													        


														</div>
													</div>
                                                    

												</div>
												
												

												

											<!--div class="form-actions">
												<button type="button" onclick="save()" class="btn blue"><i class="fa fa-check"></i> Simpan</button>
												<button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-plus"></i> Data Baru</button>

											</div-->
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>
                                          </div>
										
									<div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>
										</div>
										<div class="tools">
											<a href="javascript:;" class="collapse">
											</a>
											<a href="#portlet-config" data-toggle="modal" class="config">
											</a>
										</div>
									</div>
									<div class="portlet-body">
	
										
										<div class="tabbable tabs-left">
											<ul class="nav nav-pills">
												<li class="active">
													<a href="#tab21" data-toggle="tab">
														 <i class="fa fa-edit"></i>
													</a>
												</li>
												<li>
													<a href="#tab22" data-toggle="tab">
														 <i class="fa fa-info"></i>
													</a>
												</li>
											</ul>
											
											<div class="tab-content">
												<div class="tab-pane active" id="tab21">

										
													<div class="table-toolbar">
														
														
														<div class="btn-group">
															<a href="" onclick="location.reload()" class="btn btn-success">
															<i class="fa fa-refresh"></i>
															
															</a>
														</div>	

													</div>
													<table class="table table-striped table-hover table-bordered" id="keuangan-transfer-list">
													<thead>
															 <tr>
																 <tr>
																 <th style="text-align: center">Nama Barang</th>
																 <th style="text-align: center">Kode</th>
																 <th style="text-align: center">Kuantitas</th>
																 <th style="text-align: center">Satuan</th>
																 <th style="text-align: center">Harga</th>
																 <th style="text-align: center">Diskon</th>
																 <th style="text-align: center">Total Harga</th>
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

																 <tr class="show1" id="row_<?php echo $row->nomor;?>">
																	 <td align="center"><?php echo $row->nomor;?></td>
																	 <td align="center"><?php echo date('d-m-Y',strtotime($row->tanggal));?></td>
																	 <td><?php echo $row->bank_sumber;?></td>
																	 <td><?php echo $row->bank_tujuan;?></td>
																	 <td><?php echo $row->uraian;?></td>
																	 <td align="right"><?php echo number_format($row->jumlah,0,'.',',');?></td>
																	 <td align="center">
																		<?php
																		 
																			 { ?>
																		<a href="<?php echo base_url()?>keuangan/keuangan_transfer/edit/<?php echo $row->nomor;?>">Edit</a></td>
																		<?php }?>
																	 </td>
																	 <td align="center">
																	   <?php
																		  
																			 { ?>
																			<a class="delete" href="javascript:">Hapus</a>
																		   <?php }?>
																	 </td>
																	 <td align="center">
																		<?php
																			 { ?>
																		<a class="print_laporan" id="<?php echo $row->nomor;?>" href="#report" data-toggle="modal">Cetak</a> 
																		<?php }?>
																	 </td>

																 </tr>
															 <?php
																  $nomor++;
															 } ?>


															 </tbody>
															 <tfoot>

																	<td colspan="5" style="text-align:right">Total:</td>
																	<td style="text-align:right"></td>
																	<td colspan="4"></td>


															</tfoot>

													</table>
												</div> <!--tab21-->
												
												<div class="tab-pane" id="tab22">
												   <div class="row">
												    <div class="col-md-6">
														<div class="form-group">
																<label class="col-md-3 control-label">Pemasok</label>
																<div class="col-md-9">
																  <select id="pemasok" name="pemasok" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
																  <option value="">&nbsp</option>
																<?php
																 foreach($pemasok as $row)
																  { ?>
																	<option value="<?php echo $row->kodeakun;?>"><?php echo $row->namaakun;?></option>
																<?php } ?>
																</select>
																</div>

														</div>
													</div>	
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor Bukti</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nomor;?>" onkeypress="return tabE(this,event)">
													        </div>

														</div>
													</div>
													
													
												</div>
												</div>
											</div>
										</div>	
									   </div>	
                                      </div>	 									   
										
                                    </form>
								   </div> 
								  </div> 
                                 </div> 

								 <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>
										</div>
										<div class="tools">
											<a href="javascript:;" class="collapse">
											</a>
											<a href="#portlet-config" data-toggle="modal" class="config">
											</a>
										</div>
									</div>
									<div class="portlet-body">
									
								 
								</div> <!--tab2-->
								
								
                               </div>
                            </div>							   
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

                var iTotal = 0;
                for ( var i=0 ; i<aaData.length ; i++ )
                {
                    iTotal += aaData[i][5]*1;
                }


                var iTot = 0;
                for ( var i=iStart ; i<iEnd ; i++ )
                {

                    var x = aaData[aiDisplay[i]][5];
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
                        url: "<?php echo base_url(); ?>keuangan/keuangan_transfer/hapus/"+mydata,	
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
				
		$("#MyModalBody").html('<iframe src="<?php echo base_url();?>keuangan/keuangan_transfer/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});

jQuery(document).ready(function() {       
   TableEditable.init();
  
});
</script>
