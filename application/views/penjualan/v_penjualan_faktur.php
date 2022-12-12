
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	<!-- <link href="<?php echo base_url('css/font_css.css')?>" rel="stylesheet" type="text/css"/> -->
	<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/select2/select2.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css')?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css"/>
	<style>
        .toolbar {
            float: left;
        }
    </style>
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Farmasi <small>Penjualan Resep</small>
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
                               Farmasi
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="<?php echo base_url();?>penjualan_faktur">
                               Data Penjualan
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
                        <div class="portlet-title">
							<div class="caption">Daftar E-RESEP</div>
						</div>
						<div class="portlet-body">
                            <div class="btn-group pull-right" style="margin-bottom:15px">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li>											
                                        <a data-toggle="modal" href="#eresepperiode">Ganti Periode Data</a>										
                                    </li>										
                                </ul>
                            </div>
							<table class="table table-striped table-hover table-bordered" id="datatable-eresep">
                                <thead>
                                    <tr class="page-breadcrumb breadcrumb title-white">
                                        <th>Proses</th>
                                        <th>Cabang</th>
                                        <th>Tgl Eresep</th>
                                        <th>No Eresep</th>
                                        <th>No Reg</th>
                                        <th>Rekmed</th>
                                        <th>Nama Pasien</th>
                                        <th>Resep Dokter</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($eresep as $rkey => $rval){ ?>
                                        <tr>
                                            <?php 
                                            $cek =  $this->session->userdata('user_level'); 
                                            if($cek==0){?> 
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-xs" >proses</button>
                                                </td>
                                            <?php }else{ ?>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-xs" onclick="window.open('/penjualan_faktur/entri/?eresep=true&noresep=<?= $rval->orderno ?>', '_blank')">proses</button>
                                                </td>
                                            <?php } ?>
                                            <td class="text-center"><?= $rval->koders ?></td>
                                            <td><?= date("d-m-Y", strtotime($rval->tglorder)) ?></td>
                                            <td><?= $rval->orderno ?></td>
                                            <td><?= $rval->noreg ?></td>
                                            <td><?= $rval->rekmed ?></td>
                                            <td><?= $rval->namapas ?></td>
                                            <td><?= $rval->kodepos == "" ? "-" : data_master("dokter", array("kodokter" => $rval->kodokter, "koders" => $rval->koders, "kopoli" => $rval->kodepos))->nadokter ?></td>
                                            <td><?= $rval->keterangan ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
							</table>
						</div>
                        
                        <hr />

						<div class="portlet-title">
							<div class="caption">
								Daftar  Faktur Penjualan 
								<span><b>
								<?php 
								//    echo $periode;?></b>
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

                                            <a href="<?php echo base_url()?>penjualan_faktur/entri/" class="btn btn-success">
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
									     <th class="title-white" style="text-align: center">No. Resep</th>
										 <th class="title-white" style="text-align: center">No. Register</th>
										 <th class="title-white" style="text-align: center">Rekmed</th>
										 <th class="title-white" style="text-align: center">Nama Pasien</th>
										 <th class="title-white" style="text-align: center">Tanggal</th>
										 <th class="title-white" style="text-align: center">Jumlah Rp</th>
										 <th class="title-white" style="text-align: center">No. Kwitansi</th>
                                         <th class="title-white" style="text-align: center">Status</th>
                                         <th class="title-white" style="text-align: center">Action</th>
										 <!-- <th>&nbsp;</th>
                                         <th>&nbsp;</th>                                          -->
                                     </tr>
                                     </thead>

                                    
                                     <tbody>
                                     <?php
                                      
                                       $nomor = 1;
                                       foreach ($keu as $row)
                                       {   
									     
									     ?>

                                     <tr class="show1" id="row_<?php echo $row->resepno;?>">
									     <td align="center"><?php echo $row->koders;?></td>	
									     <td align="center"><?php echo $row->username;?></td>										 
                                         <td align="center"><?php echo $row->resepno;?></td>										 
										 <td align="center"><?php echo $row->noreg;?></td>										 
										 <td align="center"><?php echo $row->rekmed;?></td>										 
										 <td align="center"><?php echo $row->namapas;?></td>										 
                                         <td align="center"><?php echo date('d-m-Y',strtotime($row->tglresep));?></td>										 
                                         <td align="right"><?php echo number_format($row->poscredit,0,',','.');?></td>
										 <td><?php echo $row->nokwitansi;?></td>
                                         
                                         <td style="text-align: center"><?php
                                                 if ($row->keluar=='0')
                                                 { ?>
										           <span class="label label-sm label-warning">
											          Belum Lunas
										           </span>
										           <?php
                                                 }else
                                                 if ($row->keluar=='1')
                                                 { ?>
                                                   <span class="label label-sm label-success">
                                                     Lunas
										           </span>

										           <?php
												 } ?> 
                                                 
                                         </td>
										
                                        <td style="text-align: center">
                                            <?php
                                               if ($row->keluar=='0')
                                                 { ?>
											 <a class="btn btn-sm btn-primary" href="<?php echo base_url()?>penjualan_faktur/edit/<?php echo $row->resepno;?>">
                                             <i class="glyphicon glyphicon-edit" title="Edit"></i></a>
                                            <?php }?>
                                            <?php
                                            if ($row->keluar=='1')
                                                { ?>
											 <a class="btn btn-sm btn-info" href="<?php echo base_url()?>penjualan_faktur/edit/<?php echo $row->resepno;?>/1"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            <?php }?>
                                           <?php
                                            if ($row->keluar=='0')
                                                { ?>
                                                <!-- <a class="btn btn-sm btn-danger" href="<?php echo base_url()?>penjualan_faktur/delete/<?php echo $row->resepno;?>/1" title="Delete"> -->
                                                <a class="btn btn-sm btn-danger" onclick="cekhapus('<?php echo $row->resepno;?>')" title="Delete">
                                                <i class="glyphicon glyphicon-remove"></i></a>

                                                <a class="btn btn-sm btn-warning" onclick="_urlcetak('<?php echo $row->resepno;?>')" title="Cetak">
                                                <i class="glyphicon glyphicon-print"></i></a>
                                            <?php }?>
                                        </td>
                                         
                                     </tr>
                                     <?php
                                          $nomor++;
                                     } ?>


                                     </tbody>
                                    

							</table>
						</div>
                        <br /><br />
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
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')?>" type="text/javascript"></script>

<script>

function currencyFormat (num) {
    //return "Rp" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    return "" + num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function cekhapus(noresep)
{

    swal({
		title: "Hapus Resep : <b>"+noresep+"</b>  ?",
		text: "",
		type: 'info',
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-success',
		confirmButtonColor: '#227dff',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya',     
		cancelButtonText: 'TIDAK',     
    }).then(function () {
            $.ajax( {
                dataType: 'html',
                type: "POST",
                url: "<?php echo base_url(); ?>penjualan_faktur/delete/"+noresep,	
                cache: false,
                success: function (data) {
                    swal({
                        title: "Resep Berhasil Di Hapus",
                        html: "<b>"+noresep+"</b> <br> ",
                        type: "success",
                        confirmButtonText: "OK" 
                    }).then((value) => {
						// bayar();
                        // var table = $('#keuangan-keluar-list').dataTable();
                        // table.ajax.reload();
						location.href = "<?php echo base_url()?>penjualan_faktur";
                        return;
                    });
                },
                    error:function(data){
                    swal('RESEP','Data gagal di hapus ...','');	
                            
                }
            } );
    }, function (dismiss) {
		if (dismiss === 'cancel') {
			return;
		}
	});
}

function _urlcetak(nobukti)
{
	var baseurl = "<?php echo base_url()?>";
	var ctk=baseurl+'penjualan_faktur/cetak/?nobukti='+nobukti;	
	window.open(ctk,'_blank');
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
                    },
					
                ]
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

            function deleteRow ( oTable, nRow)
            {
                if (confirm("Hapus Data Ini?")) {

                    var row_id = nRow.id;
                    var mydata = row_id.substring(4,30);
                    
                    $.ajax( {
                        dataType: 'html',
                        type: "POST",
                        url: "<?php echo base_url(); ?>penjualan_faktur/hapus/"+mydata,	
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

            $('#keuangan-keluar-list a.delete').live('click', function (e) {
                e.preventDefault();

                var nRow = $(this).parents('tr')[0];
                if ( nRow ) {
                        deleteRow(oTable, nRow);
                        nEditing = null;

                    }

                });


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
		$('.modal-title').text('penjualan');
		
		var param=this.id;				
				
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>penjualan_faktur/cetak/'+param+'" frameborder="no" width="100%" height="420"></iframe>');
		});

        $("#datatable-eresep").DataTable({
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],
            info: false,
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
                },
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"]
                ]
            },
        });

        $('#datatable-eresep_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#datatable-eresep_wrapper .dataTables_length select').addClass("form-control input-small  input-inline"); // modify table per page dropdown
        $('#datatable-eresep_wrapper .dataTables_length').attr("style", "float:left;margin-top:50px");
	});
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = '2~'+tgl1+'~'+tgl2; 
	location.href = "<?php echo base_url();?>penjualan_faktur/filter/"+str;
}

function filterdataEresep(){
    var tgl1 = document.getElementById("tanggale1").value;
	var tgl2 = document.getElementById("tanggale2").value;
    var str         = tgl1 +"~"+ tgl2;
    location.href   = "<?php echo base_url();?>penjualan_faktur/?filter-eresep="+str;
}

jQuery(document).ready(function() {       
   TableEditable.init();
//    ComponentsPickers.init();
  
});
</script>
