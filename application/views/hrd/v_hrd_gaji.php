    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	
	
    <!--div class="page-content-wrapper">
		<div class="page-content"-->
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Payroll <small>Gaji Karyawan</small>
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
							<a href="#">
                               Payroll
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url();?>hrd_gaji">
                               Daftar Gaji Karyawan
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
								Daftar Gaji Karyawan
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									<!--button id="master-bank_new" class="btn green">
									Data Baru <i class="fa fa-plus"></i>
									</button-->
									
									
								</div>
								
								<div class="btn-group">
								  <?php if($akses->uadd==1){ ?>
								    <a href="<?php echo base_url()?>hrd_gaji/add" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Proses Gaji
									</a>
									
								  <?php } ?>
								</div>
								<div class="btn-group">
								  <?php if($akses->uadd==1){ ?>
								    
									<a href="<?php echo base_url()?>hrd_gaji/pph" class="btn btn-warning">
									<i class="fa fa-money"></i>
                                    Hitung PPH
									</a>
								  <?php } ?>
								</div>
							    <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
								
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
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                     <tr>
                                         <th style="text-align: center; width:5%">Bulan</th>
                                         <th style="text-align: center; width:5%">Tahun</th>
										 <th style="text-align: center; width:10%">Tanggal</th>
                                         <th style="text-align: center; width:10%">NIK</th>
										 <th style="text-align: center; width:30%">Nama Karyawan</th>
										 <th style="text-align: center; width:15%">THP</th>
									     <th style="text-align: center;width:10%;">Aksi</th>

                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
								<tfoot>
								   <tr>
									  <td colspan="5">Total</td>							  
									  <td></td>
									  
								   </tr>
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
  $this->load->view('template/v_periode_bulan'); 
?>
	
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/core/app.js')?>"></script>


<script type="text/javascript">
var save_method; //for save method string
var sfilter;
var table;

$(document).ready(function() {

    var display = $.fn.dataTable.render.number( '.', ',', 2, ' ' ).display;
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('hrd_gaji/ajax_list/1')?>",
		    "type": "POST"
        },
		
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
				
		"aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],		

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
		{  
			   "targets": [0,1,2,3], //last column
			   "orderable": true, //set not orderable
			  
			   "className" : "text-center",
				
			},
		{  
			   "targets": [5], //last column
			   "orderable": true, //set not orderable
			  
			   "className" : "text-right",
				render: function ( data, type, row ) {
				   return '<b>' + display(row[5]) + '</b>';
				   
				}					   
			   
			},
		
        ],
		"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
				
				thp = api
					.column( 5 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );

	 
				// Update footer
				$( api.column( 5 ).footer() ).html(
					'<b>'+ formatCurrency1(thp) +'</b>'
				);
				
				

			},	

    });
	
	
	
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_data()
{
  
	
	$.ajax({
            url : "<?php echo site_url('akuntansi_ju/jurnalentry')?>/",
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
    });
		
}

function edit_data( id )
{
	$.ajax({
            url : "<?php echo site_url('akuntansi_jurnal')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
    });
		
}

function convert_excel(type, fn, dl) {
    var elt = document.getElementById('table');
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || ('Jurnal-Report.' + (type || 'xlsx')));
}
$(".btnExport_1").click(function(event) { 
 convert_excel('xlsx');
});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}



function delete_data(id)
{
    if(confirm('Yakin data Gaji dengan Nomor  '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('hrd_gaji/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

$(document).ready(function() {
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('BUKU BESAR');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>akuntansi_ju/jurnalentry" frameborder="no" width="100%" height="420"></iframe>');
		});		
	});
	
	
function filterdata()
{
	var bulan = document.getElementById("bulan").value;
	var tahun = document.getElementById("tahun").value;
	var id   = 2; 
	var str  = id+'~'+bulan+'~'+tahun; 
	table.ajax.url("<?php echo base_url('hrd_gaji/ajax_list/')?>"+str).load();		
}

	
jQuery(document).ready(function() {
        ComponentsPickers.init();

});


   
</script>




