
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
	<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css"/>
	
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Finance <small>Tukar Faktur</small>
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
                               Tukar Faktur
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
								Tukar Faktur
                                 <!-- -
								<span>
                                    <b>
								    <?php 
								        echo $periode;
                                    ?>
                                    </b>
                                </span> -->
							</div>
                            <!-- <div class="pull-right">
                                <div class="caption">
                                <?php 
                                    echo $vendor == '' ? 'Semua Vendor' : $vendor;
                                ?>
                                </div>
                            </div> -->
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								
								
								<div class="btn-group">
								  <?php if($akses->uadd){?>
								    <a href="<?php echo base_url()?>hutang_tukarfaktur/entri" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Data Baru
									</a>
								  <?php } ?>
								</div>	
								
								<div class="btn-group pull-right">
                                    <!-- <a href="<?php echo base_url('hutang/export?startdate='.$startdate.'&enddate='.$enddate.'&vendor='.$vendorid); ?>" target="_blank" class="btn btn-success"> 
                                        <i class="fa fa-download"></i> Excel
									</a> -->
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>											
											<a data-toggle="modal" href="#hperiode">Ganti Periode Data</a>										
										</li>										
									</ul>
								</div>

							</div>
                
                            <br>

							<table class="table table-striped table-hover table-bordered" id="keuangan-keluar-list">
                                <thead class="breadcrumb">
                                     <tr>
                                         <th style="text-align: center">Cab.</th>
                                         <th style="text-align: center">User ID</th>
										 <th style="text-align: center">No Tukar</th>
                                         <th style="text-align: center">Tgl Tukar Faktur</th>
										 <th style="text-align: center">Nama Vendor</th>
                                         <th style="text-align: center">Tgl Rencana Bayar</th>                                         
										 <th style="text-align: center">Total Rencana Bayar</th>
                                         <th>&nbsp;</th>
                                         <th>&nbsp;</th>
                                         <th>&nbsp;</th>                                         
                                     </tr>
                                     </thead>

                                    
                                     <tbody>
                                     <?php
                                      if($keu != ''){
                                       $nomor = 1;
                                       foreach ($keu as $row)
                                       {   
									     ?>

                                     <tr class="show1" id="row_<?php echo $row->id;?>">
									     <td align="center"><?php echo $row->koders;?></td>
									     <td align="center"><?php echo $row->username;?></td>
                                         <td align="center"><?php echo $row->notukar;?></td>
										 <td align="center"><?php echo date('d-m-Y',strtotime($row->tanggal));?></td>										 
                                         <td><?php echo $row->vendor_name;?></td>
										 <td align="center"><?php echo date('d-m-Y',strtotime($row->tglbayar));?></td>	
										 <td align="right"><?php echo number_format($row->totalsemua,0,'.',',');?></td>
                                         <td style="text-align: center">
                                            <!-- <a href="<?php echo base_url()?>hutang_tukarfaktur/edit/<?php echo $row->id;?>">Edit</a></td> -->
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url()?>hutang_tukarfaktur/edit/<?php echo $row->id;?>" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 
                                         </td>
                                         <td style="text-align: center">
                                            <!-- <a href="<?php echo base_url()?>hutang_tukarfaktur/cetak/<?php echo $row->id;?>" target="_blank">Cetak</a></td> -->
                                            
                                            <!-- <a href="#report" class="print_laporan" data-toggle="modal" id="<?php echo $row->id;?>">Cetak</a> -->
                                            <a class="btn btn-sm btn-warning print_laporan" id="<?php echo $row->id;?>" href="#report" title="Cetak"  data-toggle="modal"><i class="glyphicon glyphicon-print"></i></a>
                                         </td>
                                         <td style="text-align: center" >
                                            <!-- <a href="javascript:" title="Hapus" onclick="delete_data(<?php echo $row->id;?>, '<?php echo $row->notukar;?>')">Hapus</a> -->
                                            <a class="btn btn-sm btn-danger delete" href="javascript:void(0)" title="Hapus" onclick="delete_data(<?php echo $row->id;?>, '<?php echo $row->notukar;?>')"><i class="glyphicon glyphicon-remove"></i> </a> 
                                         </td>
                                         
                                     </tr>
                                     <?php
                                          $nomor++;
                                     } 
                                    }?>


                                     </tbody>
                                     <tfoot>

                                            <td colspan="6" style="text-align:right">Total:</td>
                                            <td style="text-align:right"></td>
                                            <td colspan="3"></td>


                                    </tfoot>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="hperiode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <span id="nopilih">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Periode Data</h4>											
            </div>
            <div class="modal-body">										 		  
            <form action="#" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Mulai</label>
                        <div class="col-md-6">
                        <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                        
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Sampai</label>
                        <div class="col-md-6">
                        <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                        
                        </div>
                    </div>	
                    <div class="form-group">
                        <label class="col-md-4 control-label">Vendor</label>
                        <div class="col-md-6">
                            <select style='color:black;' id="vendor" name="vendor" class="selectpicker" data-live-search="true" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                <option value=''>-- Pilih Vendor --</option>
                                <?php
                                    foreach($list_vendor as $key){
                                        echo "<option value='".$key->vendor_id."'>$key->vendor_name</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>										
            </form>
            </div>   
            <div class="modal-footer">
                <p align="center">
                <button type="button" id="btnfilter" class="btn green" onclick="filterdata()" data-dismiss="modal">Buka Data</button>		</p>																				 			
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
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/plugins/data-tables/jquery.dataTables.js')?>" type="text/javascript" > </script>
<script src="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.js')?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>" type="text/javascript"></script>


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css"> -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-select2.min.css')?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

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
                    }
                ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {

                var iTotal = 0;
				var iTotal1 = 0;
                for ( var i=0 ; i<aaData.length ; i++ )
                {
                    iTotal += aaData[i][6]*1;
					// iTotal1 += aaData[i][8]*1;
                }


                var iTot = 0;
				var iTot1 = 0;
                for ( var i=iStart ; i<iEnd ; i++ )
                {

                    var x = aaData[aiDisplay[i]][6];
                    var y = Number(x.replace(/[^0-9\.]+/g,""));
                    iTot += y;
					
					// var x1 = aaData[aiDisplay[i]][8];
                    // var y2 = Number(x1.replace(/[^0-9\.]+/g,""));
                    // iTot1 += y2;
                }

                var nCells = nRow.getElementsByTagName('td');
                nCells[1].innerHTML = currencyFormat(iTot);
				// nCells[2].innerHTML = currencyFormat(iTot1);
                }
                    });

                jQuery('#keuangan-keluar-list_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-keluar-list_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-keluar-list_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '','','',
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Batal</a>'
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


            $('#keuangan-keluar-list a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-keluar-list a.edit').live('click', function (e) {
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
		$('.modal-title').text('TUKAR FAKTUR');
		
		var param=this.id;				
				
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>hutang_tukarfaktur/cetak/'+param+'" frameborder="no" width="100%" height="520"></iframe>');
		});	
	});
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var vendor = document.getElementById("vendor").value;
	var str  = 'startdate='+tgl1+'&enddate='+tgl2+'&vendor='+vendor; 
	location.href = "<?php echo base_url();?>hutang_tukarfaktur/filter?"+str;
}	


function delete_data(id, nomor) {
    swal({
        title: 'TUKAR FAKTUR',
        html: "Data ini akan dihapus ? <strong><p>"+nomor,
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then(function () {
        $.ajax({
            type : 'POST',
            dataType : "json",
            url : '<?php echo base_url('hutang_tukarfaktur')?>/ajax_delete',
            data : {id : id, notukar: nomor},
            success:function(response){
                if(response.status == true) {
                    swal(
                        '',
                        'Data berhasil dihapus.',
                        ''
                    ).then((value) => {
						location.href = "<?php echo base_url()?>hutang_tukarfaktur";
				    });
                } else {
                    swal(
                        '',
                        'Data gagal dihapus.',
                        ''
                    ).then((value) => {
						location.href = "<?php echo base_url()?>hutang_tukarfaktur";
				    });
                }
                //dataTable.ajax.reload(null,false);
                // reload_table();
            }
        });
    }).catch(swal.noop);
}




jQuery(document).ready(function() {       
   TableEditable.init();
//    ComponentsPickers.init();
  
});
</script>
