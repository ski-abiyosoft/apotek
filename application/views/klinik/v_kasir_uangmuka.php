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
                      <span class="title-web">KLINIK <small>Kasir - Uang Muka</small>
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
                               Uang Muka
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
								Daftar Uang Muka
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									<?php 
									//if($akses->uadd)
									{?>
									<?php 
									$cek =  $this->session->userdata('user_level'); 
									if($cek==0){?> 
									<?php }else{ ?>

										<a href="<?php echo base_url()?>kasir_uangmuka/entri" class="btn btn-success">
											<i class="fa fa-plus"></i>
										Transaksi Baru
										</a>

									<?php } ?>

									<?php } ?>	
								</div>	
								
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>											
											<a data-toggle="modal" href="#lupperiode">Filter Data</a>										
										</li>										
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr class="page-breadcrumb breadcrumb">
                                         <th class="title-white" style="text-align: center">Cab.</th>
										 <th class="title-white" style="text-align: center" width="150">User ID</th>
										 <th class="title-white" style="text-align: center">No. Kwitansi</th>
                                         <th class="title-white" style="text-align: center">Nama Pasien</th>
										 <th class="title-white" style="text-align: center">No. RM</th>
										 <th class="title-white" style="text-align: center">No. Reg</th>
                                         <th class="title-white" style="text-align: center">Tgl. Bayar</th>
										 <!-- <th class="title-white" style="text-align: center">Jenis Bayar</th> -->
										 <th class="title-white" style="text-align: center">Total</th>
										 <th class="title-white" style="text-align: center">Sisa</th>
									     <th class="title-white" style="text-align: center;width:20%;">Aksi</th>

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
  $this->load->view('template/v_periode');
?>
	

<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
	var display = $.fn.dataTable.render.number( '.', ',', 2, ' ' ).display;
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

       
        "ajax": {
            "url": "<?php echo site_url('kasir_uangmuka/ajax_list/1')?>",
            "type": "POST"
        },
		
        //scrollX: true,
		//"scrollY":        "200px",
        //"scrollCollapse": true,
        "paging":         true,
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "",
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
			   "targets": [8], //last column
			   "orderable": true, //set not orderable
			  
			   "className" : "text-right",
				render: function ( data, type, row ) {
				   return '<b>' + display(row[8]) + '</b>';
				   
		}},					   
			   
		
        ],

    });

    //datepicker
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
        url : "<?php echo site_url('kasir_uangmuka/ajax_edit/')?>/" + id,
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
        url = "<?php echo site_url('kasir_uangmuka/ajax_add')?>";
    } else {
        url = "<?php echo site_url('kasir_uangmuka/ajax_update')?>";
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
            url : "<?php echo site_url('kasir_uangmuka/ajax_delete')?>/"+id,
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
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var id   = 2; 
	var str  = id+'~'+tgl1+'~'+tgl2; 
	table.ajax.url("<?php echo base_url('kasir_uangmuka/ajax_list/')?>"+str).load();	 
}

function Batalkan(id) {		
	swal({
		//title: 'PENDAFTARAN',
		text: "Pembatalan Uang Muka ",
		type: 'warning',
		//input: 'text',
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger m-l-10',
		confirmButtonText: 'Ya, Batalkan',     
		cancelButtonText: 'Tidak',     
	}).then(function (alasan) {			
		$.ajax({
			type : 'POST',
			dataType : "json",
			data : {alasan : alasan},
			url : '<?php echo base_url()?>kasir_uangmuka/pembatalan/'+id,
			success:function(response){
				if(response.status == 1) {
					swal(
						'Updated!',
						'Pembatalan berhasil.',
						'success'
					)
				} else {
					swal(
						'Failed!',
						'Pembatalan gagal',
						'failed'
					)
				}
				reload_table();
			}
		});
	});
}

function send_wa(id, hp){	 
  swal({
		title: 'KIRIM WhatsApp',
		text: "Nomor HP : "+hp,
		type: 'info',
		
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger m-l-10',
		confirmButtonText: 'Ya, Kirim',     
		cancelButtonText: 'Tidak',     
	}).then(function () {			
		$.ajax({
			type : 'POST',
			dataType : "json",
			data : {id : id},
			url : '<?php echo base_url()?>kasir_uangmuka/send_wa',
			success:function(response){
				if(response.status == 1) {
					swal(
						'Sending!',
						'Kirim WhatsApp berhasil.',
						'success'
					)
				} else {
					swal(
						'Failed!',
						'Kirim WhatsApp gagal',
						'failed'
					)
				}
				//reload_table();
			}
		});
	});   
}

function send_email(id, email){	 
  swal({
		title: 'KIRIM Email',
		text: "Alamat : "+email,
		type: 'info',
		
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger m-l-10',
		confirmButtonText: 'Ya, Kirim',     
		cancelButtonText: 'Tidak',     
	}).then(function () {			
		$.ajax({
			type : 'POST',
			dataType : "json",
			data : {id : id},
			url : '<?php echo base_url()?>kasir_uangmuka/send_email',
			success:function(response){
				if(response.status == 1) {
					swal(
						'Sending!',
						'Kirim Email berhasil.',
						'success'
					)
				} else {
					swal(
						'Failed!',
						'Kirim Email gagal',
						'failed'
					)
				}
				//reload_table();
			}
		});
	});   
}
		  


</script>
