    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	
	

	
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">KLINIK <small>Edit / View Data Pasien</small>
					</h3>
                      <ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>home">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                               Klinik
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                               Data Pasien
							</a>
						</li>
					</ul>
				</div>
			</div>
			
			<!--div class="row">
			  <div class="col-md-12">
			     <div class="portlet box yellow">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-reorder"></i>Pencarian
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="panel-group accordion" id="accordion3">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1">
											 Cari data pasien
										</a>
										</h4>
									</div>
									<div id="collapse_3_1" class="panel-collapse in">
										<div class="panel-body">
											 <form action="#" class="form-horizontal">
		        
												<div class="form-group">
													<label class="col-md-3 control-label">Nama</label>
													<div class="col-md-9">
													  <input id="carinama" name="carinama" class="form-control" type="text"  />
													  
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">Alamat</label>
													<div class="col-md-9">
													   <input id="carialamat" name="carialamat" class="form-control" type="text" value="" />
													</div>
												</div>	
												<div class="form-group">
													<label class="col-md-3 control-label">No. Identitas</label>
													<div class="col-md-9">
													   <input id="carinoid" name="carinoid" class="form-control" type="text" value="" />
													</div>
												</div>				
											</form>
										</div>
										<div class="panel-footer">
										   <p align="center">
										   <button type="button" id="btnfilter" class="btn green" onclick="filterdata()" data-dismiss="modal">Cari Data</button>
										   </p> 
										</div>
										
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
			  
			  </div>
			</div-->
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Daftar Pasien
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								  <?php 
								  //if($akses->uadd)
								  {?>
								    <!--a href="<?php echo base_url()?>pasien/entri" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Data Baru
									</a-->
								  <?php } ?>	
								</div>	
								
                                <?php 
                                    $cek =  $this->session->userdata('user_level'); 
                                    if($cek==0){?> 
                                    <?php }else{ ?>
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
                                    <ul class="dropdown-menu pull-right">
                                        <li>	
                                            <a data-toggle="modal" href="#luppasien">Filter Data</a>										
                                        </li>										
                                    </ul>

								</div>
                                
                                <?php } ?>
							</div>
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th class="title-white" style="text-align: center">Cabang</th>
                                         <th class="title-white" style="text-align: center">No. Rek Med</th>
                                         <th class="title-white" style="text-align: center;width:10%;">Nama Pasien</th>
										 <th class="title-white" style="text-align: center">JK</th>
                                         <th class="title-white" style="text-align: center;width:20%;">Alamat</th>
										 <th class="title-white" style="text-align: center">Tmpt Lahir</th>
										 <th class="title-white" style="text-align: center">Tgl Lahir</th>
                                         <th class="title-white" style="text-align: center">Handphone</th>
										 <th class="title-white" style="text-align: center">No. Identitas</th>
                                         <th class="title-white" style="text-align: center;width:10%;">Aksi</th>

                                     </tr>
                                </thead>
								
                                <tbody>
								
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
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_caripasien');
?>

<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

       
        "ajax": {
            "url": "<?php echo site_url('pasien/ajax_list/?jenis=1')?>",
            "type": "POST"
        },
		
        //scrollX: true,
		"scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         false,
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "",
                    "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
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
        ],

    });

    // datepicker
    // $('.datepicker').datepicker({
    //     autoclose: true,
    //     format: "yyyy-mm-dd",
    //     todayHighlight: true,
    //     orientation: "top auto",
    //     todayBtn: true,
    //     todayHighlight: true,  
    // });

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
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('pasien/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="kode"]').val(data.koders);
            $('[name="nama"]').val(data.namars);
            $('[name="alamat"]').val(data.alamat);
            $('[name="kota"]').val(data.kota);
			$('[name="telpon"]').val(data.phone);
          
			
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('pasien/ajax_add')?>";
    } else {
        url = "<?php echo site_url('pasien/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_data(id)
{
    if(confirm('Yakin data Cabang dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('pasien/ajax_delete')?>/"+id,
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

function filterdata(){	
	var nama = $("#carinama").val();
	var alamat = $("#carialamat").val();
	var noid = $("#carinoid").val();
	var rekmed = $("#rekmed").val();
	var tglLahir = $("#tglLahir").val();
	var noTlp = $("#noTlp").val();
	var nocard = $("#nocard").val();
	var str = "?nama="+nama+"&alamat="+alamat+"&noid="+noid+"&rekmed="+rekmed+"&tglLahir="+tglLahir+"&noTlp="+noTlp+"&nocard="+nocard;
	table.ajax.url("<?= base_url();?>pasien/ajax_list/"+str).load();	 
}

</script>
