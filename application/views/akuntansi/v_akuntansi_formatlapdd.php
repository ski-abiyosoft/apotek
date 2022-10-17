<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

		
	<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>

	
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Akuntansi <small>Format Laporan Keuangan</small>
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
							<a href="#" onClick="location.reload()">
                               Akuntansi
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                               Format Laporan Keuangan
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
							    Rincian Format Laporan Keuangan  
                                <span class="label label-sm label-warning">							
                                 (<?php echo $kode."/".$nama;?>)
								</span>
                                &nbsp ---> <?php echo $judul;?>
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">									
									<a class="btn green" data-toggle="modal" href="#perk">
										 Data Baru <i class="fa fa-plus"></i>
									</a>
																											
								</div>
								
								<?php if($jumdat==0) { ?>
								<div class="btn-group">
									
									<a class="btn blue" data-toggle="modal" href="#catatan">
										 Catatan LK<i class="fa fa-plus"></i>
									</a>
									
								</div>
								<?php } else { ?>																							
								<div class="btn-group">
									
									<a class="btn red" data-toggle="modal" href="#catataned" onclick="getcatatan(document.getElementById('nomorlap').value);">
										 Catatan LK <i class="fa fa-edit"></i>
									</a>									
								</div>
								<?php } ?>
								
							</div>
							<input type="hidden" id="nomorlap" value="<?php echo $nomor;?>"/>
							<table class="table table-striped table-hover table-bordered" id="akuntansi-formatlapdd">
                            <thead>
                                     <tr>
                                         <th style="text-align: center">#ID</th>
                                         <th style="text-align: center">Kode Perk</th>
                                         <th style="text-align: center">Nama Perkiraan</th>
										                                          
                                         <th style="text-align: center" >&nbsp</th>
                                         <th style="text-align: center">&nbsp</th>										 
                                     </tr>
                                     </thead>

                                    
                                     <tbody>
                                     <?php

                                       $nomor = 1;
                                       foreach($formatlap as $row)
                                       {   ?>

                                     <tr class="show1" id="row_<?php echo $row->nomor;?>">
                                         <td align="center"><?php echo $row->nomor;?></td>
                                         <td align="center"><?php echo $row->akun;?></td>
                                         <td align="left"><?php echo $row->namaakun;?></td>										 
                                         <td align="center"> <a class="edit" href="javascript:">Edit</a></td>
                                         <td align="center"><a class="delete" href="javascript:">Hapus</a></td>										 

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


                            <div class="modal fade" id="perk" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Rincian Kode Perkiraan</h4>
										</div>
										<div class="modal-body">
											 <select id="kodeakun" name="kodeakun" class="form-control select2mex" data-placeholder="Pilih..." >
            										<option value="">&nbsp</option>
                                                      <?php                                                            
            												foreach($coa as $row)
            												{ ?>
            													<option value="<?php echo $row->kodeakun;?>"><?php echo $row->kodeakun.' - '.$row->namaakun;?></option>
																
                                                            <?php } ?>
            										</select>
										</div>
										<div class="modal-footer">
	                                        
											<button type="button" id="btntutup" class="btn default" data-dismiss="modal">Tutup</button>
											<button type="button" id="btnsimpanakun" class="btn blue">Simpan</button>
											<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>									
										</div>
										
									</div>									
								</div>								
							</div>
							
							<div class="modal fade" id="catatan" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Catatan Laporan Keuangan</h4>
										</div>
										<div class="modal-body">
											 <textarea class="form-control" rows="10" id="keterangan" name="keterangan"></textarea>
										</div>
										<div class="modal-footer">	                                        
											<button type="button" id="btntutup" class="btn default" data-dismiss="modal">Tutup</button>
											<button type="button" id="btnsimpanclk" class="btn blue">Simpan</button>
											<h4><span id="error1" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success1" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>									
										</div>										
									</div>									
								</div>								
							</div>
							
							<div class="modal fade" id="catataned" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Catatan Laporan Keuangan</h4>
										</div>
										<div class="modal-body">
											 <div id="_catatanlk">
                                             </div>
										</div>
										<div class="modal-footer">	                                        
											<button type="button" id="btntutup" class="btn default" data-dismiss="modal">Tutup</button>
											<button type="button" id="btnsimpanclke" class="btn blue">Simpan</button>
											<h4><span id="error2" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success2" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>									
										</div>										
									</div>									
								</div>								
							</div>
							
	

<?php
  $this->load->view('template/footero');
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
    return "" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
	
var TableEditable = function () {
    var kodelap  = $("#kodelap").val();
	
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

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                jqTds[0].innerHTML = '<input type="text" class="form-control input-auto" value="' + aData[0] + '" readonly>';
                jqTds[1].innerHTML = '<input type="text" class="form-control input-auto" value="' + aData[1] + '">';
                jqTds[2].innerHTML = '<input type="text" class="form-control input-auto" value="' + aData[2] + '" readonly>';
				
                
                jqTds[3].innerHTML = '<a class="edit" href="">Simpan</a>';
                jqTds[4].innerHTML = '<a class="cancel" href="">Batal</a>';
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);

                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				
                
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 3, false);
                oTable.fnUpdate('<a class="delete" href="">Hapus</a>', nRow, 4, false);
                oTable.fnDraw();

                var row_id = nRow.id;

                var mydata = 'nomor=' + jqInputs[0].value +
                    '&akun=' +  jqInputs[1].value+                    
					'&nomorlap='+nomorlap;
					                    
                $.ajax( {
                    dataType: 'html',
                    type: "POST",
                    url: "akuntansi_formatlapdd_edit_save.php",
                    cache: false,
                    data: mydata,
                    success: function () {
                    },
                    error: function() {alert('Save failed.');},
                    complete: function() {}
                } );
            }


            function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				                
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 3, false);
                oTable.fnDraw();
            }
            var oTable = $('#akuntansi-formatlapdd').dataTable({
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

                jQuery('#akuntansi-formatlapdd_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#akuntansi-formatlapdd_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#akuntansi-formatlapdd_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#akuntansi-formatlapdd_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '',
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
                    var mydata = 'nomor=' + row_id.substring(4,30);

                    $.ajax( {
                        dataType: 'html',
                        type: "POST",
                        url: "akuntansi_formatlapdd_del.php",
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

            $('#akuntansi-formatlapdd a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


            $('#akuntansi-formatlapdd a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#akuntansi-formatlapdd a.edit').live('click', function (e) {
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

jQuery(document).ready(function() {          
   TableEditable.init();
});
</script>