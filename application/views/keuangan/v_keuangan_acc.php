
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
	
	
  			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Keuangan <small>Approval Penerimaan  Kas/Bank</small>
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
                               Appr. Penerimaan
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
								Daftar Penerimaan Kas/Bank
							</div>
								</a>

						
						</div>
						<div class="portlet-body">
      	
							<table class="table table-striped table-hover table-bordered" id="keuangan-acc">
                            <thead>
                                     <tr>
                                         <th style="text-align: center">Register</th>
                                         <th style="text-align: center">Tanggal</th>
                                         <th style="text-align: center">Uraian</th>
                                         <th style="text-align: center">Jumlah</th>
										 <th style="text-align: center">UserID</th>
                                         <th style="text-align: center">Status</th>
                                         <th style="text-align: center">Pimpinan</th>
                                         <th style="text-align: center">Kasir</th>
                                         
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <?php
                                       
                                       $nomor = 1;
                                       foreach($keu as $row)
                                       {   ?>

                                     <tr class="show1" id="row_<?php echo $row->terima_register;?>">                                         
										 
										 <td align="center"> <a href="<?php echo base_url()?>keuangan/keuangan_masuk/edit/<?php echo $row->terima_register;?>"><?php echo $row->terima_register;?></a></td>
                                         <td align="center"><?php echo date('d-m-Y',strtotime($row->terima_tanggal));?></td>
                                         <td><?php echo $row->terima_uraian;?></td>
                                         <td align="right"><?php echo number_format($row->jumlah,2,',','.');?></td>
										 <td><?php echo $row->terima_userentry;?></td>
                                         <td align="center"><?php
                                                 if ($row->terima_status=='1')
                                                 { ?>
										           <span  class="label label-sm label-warning">
											          Menunggu Acc
										           </span>
										           <?php
                                                 } else
                                                 if ($row->terima_status=='2')
                                                 { ?>
                                                   <span class="label label-sm label-success">
											          Disetujui
										           </span>

										           <?php
                                                 } ?>

                                         </td>

                                      
                                         <td align="center">
                                            <?php if($row->terima_status!='2')
                                            { ?>
											<a class="delete" href="javascript:;">Setujui</a>
										   </a>
										   <?php } else {
                                            echo $row->terima_acc1_user.",".date('d-m-Y',strtotime($row->terima_acc1));
										   }?>
										 </td>
										 <td align="center">
										    <?php if($row->terima_status=='2')
                                            { ?>
											<a class="delete" href="javascript:;">Penerimaan</a>
										   </a>
										   <?php } else {
										   if(!empty($row->terima_acc2_user))
										   {
                                            echo $row->terima_acc2_user.",".date('d-m-Y',strtotime($row->terima_acc2));
                                           }
										   }?>
										 </td>

                                     </tr>
                                     <?php
                                          $nomor++;
                                     } ?>
                                     
                                    


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
jQuery(document).ready(function() {       
   TableEditable.init();
});

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

           
            var oTable = $('#keuangan-acc').dataTable({
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
                ]
            });

            jQuery('#keuangan-acc_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#keuangan-acc_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#keuangan-acc_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#keuangan-acc_new').click(function (e) {
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
                if (confirm("Proses Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = row_id.substring(4,50);

					
                    $.ajax( {
                        dataType: 'html',
                        type: "POST",                        
						url: "<?php echo base_url(); ?>keuangan/keuangan_acc/approv/"+mydata,	
                        cache: false,
                        data: mydata,
                        success: function () {							
                            location.reload();
                            oTable.fnDraw();
                        },
                        error: function() {},
                        complete: function() {}
                    } );

                }
            }

            $('#keuangan-acc a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


            $('#keuangan-acc a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#keuangan-acc a.edit').live('click', function (e) {
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

</script>
</body>
</html>
