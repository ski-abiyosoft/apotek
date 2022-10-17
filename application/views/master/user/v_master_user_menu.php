<link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/data-tables/DT_bootstrap.css"/>
<link href="<?php echo base_url();?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/font_css.css" rel="stylesheet" type="text/css"/>

                  <div class="note note-success">
						<p>
                             NAMA GRUP : <?php echo $namagrup;?><br>
						</p>
					</div>

                <div class="row">
				<div class="col-md-7 col-sm-12">
				  <form id="frmgrup" method="post" action="">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i>Daftar Menu Grup
							</div>
                            <div class="actions">
								<button type="submit" class="btn blue"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="t2">
							<thead>
							<tr>
								<th style="width1:8px;">
									<input type="checkbox" class="group-checkable" data-set="#t2 .checkboxes"/>
								</th>
								<th style="text-align: center">Kode</th>
								<th style="text-align: center">Nama Menu</th>
								<th style="text-align: center">Tambah</th>
								<th style="text-align: center">Edit</th>
								<th style="text-align: center">Hapus</th>
                                <th>&nbsp</th>
								
							</tr>
							</thead>
							<tbody>
							
							<?php
							   $no=1; 
                               foreach($modul as $row)
                               {   ?>
							<tr class="show1" id="row_<?php echo $row->nomor;?>">
                                <td>
									<input type="checkbox" class="checkboxes" value="<?php echo $row->nomor_modul;?>" name="<?php echo "notransG".$no;?>" id="<?php echo "notransG".$no;?>"/>
								</td>
								<td><?php echo $row->nomor_modul;?></td>
								<td><?php echo $row->nama;?></td>
								<td align="center"><input type="checkbox" class="checkboxes" value="1" <?php if($row->uadd==1) { ?>checked<?php }?> name="<?php echo "uadd".$no;?>"/></td>
								<td align="center"><input type="checkbox" class="checkboxes" value="1" <?php if($row->uedit==1) { ?>checked<?php }?> name="<?php echo "uedit".$no;?>"/></td>
								<td align="center"><input type="checkbox" class="checkboxes" value="1" <?php if($row->udel==1) { ?>checked<?php }?> name="<?php echo "udel".$no;?>"/></td>
                                <td align="center"><a class="delete" href="javascript:;">Hapus</a></td>
							</tr>
                            <?php $no++;} ?>

							</tbody>
							</table>
						</div>
					</div>
					
					<input type="hidden" name="nomorG" id="nomorG" value="<?php echo $idgrup;?>"/>
                    <input type="hidden" id="jumdataG" name="jumdataG" value="<?php echo $no-1; ?>" />

            	</form>
            	
				</div>
				<div class="col-md-5 col-sm-12">
                   <form id="frmmenu" method="post" action="">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs"></i>Daftar Menu Aplikasi
							</div>
							<div class="actions">
							
								<button type="submit" class="btn green"><i class="fa fa-plus"></i> Tambah</button>

							
							</div>
							
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="t3">
							<thead>
							<tr>
								<th class="table-checkbox">
									<input type="checkbox" class="group-checkable" data-set="#t3 .checkboxes"/>
								</th>
								<th style="text-align: center">Kode</th>
								<th style="text-align: center">Nama Menu Aplikasi</th>
							</tr>
							</thead>
							<tbody>
							<?php

                               $no = 1;
                               foreach($modulapp as $row)
                               {   ?>
                                <tr class="show1" id="row_<?php echo $row->kode;?>">
								<td>
									<input type="checkbox" class="checkboxes" value="<?php echo $row->kode;?>" name="<?php echo "notrans".$no;?>" id="<?php echo "notrans".$no;?>"/>
								</td>
								<td><?php echo $row->kode;?></td>
								<td><?php echo $row->nama;?></td>
							   </tr>
							   <?php $no++;} ?>



							</tbody>
							</table>
						</div>
					</div>
				</div>
				<input type="hidden" name="nomor" id="nomor" value="<?php echo $idgrup;?>"/>
                <input type="hidden" id="jumdata" name="jumdata" value="<?php echo $no-1; ?>" />

            	</form>


				</div>
			</div>
		</div>
		

	</div>
</div>


<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/data-tables/DT_bootstrap.js"></script>

<script>
$(document).ready(function(){
  $('#frmmenu').on('submit',function(e) {
	$.ajax({
		url: "<?php echo base_url();?>master_user_grup/tambah_item",
		data:$(this).serialize(),
		type:'POST',
		success:function(data){
		alert('Item Menu Sudah Ditambahkan ...');
		location.reload();
		
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
		}
		});
      e.preventDefault();

  });


});

$(document).ready(function(){
  $('#frmgrup').on('submit',function(e) {
   
	$.ajax({
		url: "<?php echo base_url();?>master_user_grup/update_item",
		data:$(this).serialize(),
		type:'POST',
		success:function(data){
		alert('Data sudah disimpan ...');
		location.reload();
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
		}
		});
      e.preventDefault();

  });


});




var TableManaged = function () {

    return {

        //main function to initiate the module
        init: function () {
            
            if (!jQuery().dataTable) {
                return;
            }

            // begin first table
            $('#t1').dataTable({
                "aoColumns": [
                  { "bSortable": false },
                  null,
                  { "bSortable": false, "sType": "text" },
                  null,
                  { "bSortable": false },
                  { "bSortable": false }
                ],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [0] },
                    { "bSearchable": false, "aTargets": [ 0 ] }
                ]
            });

            jQuery('#t1 .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("active");
                    } else {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("active");
                    }                    
                });
                jQuery.uniform.update(set);
            });

            jQuery('#t1').on('change', 'tbody tr .checkboxes', function(){
                 $(this).parents('tr').toggleClass("active");
            });

            jQuery('#t1_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#t1_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            //jQuery('#t1_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

            // begin second table
            var oTable = $('#t2').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
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
               "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [0] },
                    { "bSearchable": false, "aTargets": [ 0 ] }
                ]
            });

            jQuery('#t2 .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#t2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
            jQuery('#t2_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            jQuery('#t2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

            function deleteRow ( oTable, nRow)
            {
                if (confirm("Hapus Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = 'nomor=' + row_id.substring(4,10);
					var kode   = row_id.substring(4,10);

					
                    $.ajax( {
                        dataType: 'html',
                        type: "POST",
                        url: "<?php echo base_url();?>master_user_grup/hapus_menu/"+kode,
					
                        cache: false,
                        data: mydata,
                        success: function () {
                            oTable.fnDeleteRow( nRow );
                            oTable.fnDraw();
                            location.reload();
                        },
                        error: function() {},
                        complete: function() {}
                    } );

                }
            }

            $('#t2 a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


            // begin: third table
            $('#t3').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Cari:",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "Sebelumnya",
                        "sNext": "Berikutnya"
                    }
                },
                "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [0] },
                    { "bSearchable": false, "aTargets": [ 0 ] }
                ]
            });

            jQuery('#t3 .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#t3_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
            jQuery('#t3_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            jQuery('#t3_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        }

    };

}();

jQuery(document).ready(function() {       
  
   TableManaged.init();
  
 
});

</script>

