<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

    <!--div class="page-content-wrapper">
		<div class="page-content"-->
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                        <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Master <small>Perkiraan</small></span>
                      <!-- Master <small>Perkiraan</small> -->
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
                               Master
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                               Perkiraan
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
								Daftar Perkiraan
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
								</div>
								 <?php if($akses->uadd==1){ ?>
								 <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
								 <?php } ?>
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>akuntansi_akun/export">
												 Export
											</a>
										</li>
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th style="text-align: center">Kode Akun</th>
                                         <th style="text-align: center">Nama Akun</th>
                                         <th style="text-align: center">Tipe Akun</th>
										 <th style="text-align: center">Level</th>
										 <th style="text-align: center;width:10%;">Aksi</th>

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
  $this->load->view('template/v_report');
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

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('akuntansi_akun/ajax_list')?>",
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
            "targets": [ 3 ], //last column
            "className" : "text-center",
        },
        ],

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
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function inisiasiFormSaldoBudget(){
    $('#isi-tahun').hide();
    $('#saldo-bulan-label').hide();
    $('#saldo-bulan').hide();
    $('#saldo-bulan-2').hide();
    $('#budget-label').hide();
    $('#budget').hide();
    $('#budget-2').hide();

    $('#btn-data-baru').show();
    $('#btn-simpan').hide();
    $('#tahun').val('');
    $('#validasiTahun').text('');

    $('#saldo01').val('');
    $('#saldo02').val('');
    $('#saldo03').val('');
    $('#saldo04').val('');
    $('#saldo05').val('');
    $('#saldo06').val('');
    $('#saldo07').val('');
    $('#saldo08').val('');
    $('#saldo09').val('');
    $('#saldo10').val('');
    $('#saldo11').val('');
    $('#saldo12').val('');

    $('#bg01').val('');
    $('#bg02').val('');
    $('#bg03').val('');
    $('#bg04').val('');
    $('#bg05').val('');
    $('#bg06').val('');
    $('#bg07').val('');
    $('#bg08').val('');
    $('#bg09').val('');
    $('#bg10').val('');
    $('#bg11').val('');
    $('#bg12').val('');
}

function edit_data(id, accountno)
{
    // console.log('cek : ' + accountno);
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('akuntansi_akun/ajax_edit_perkiraan/')?>" + id + "?accountno=" + accountno,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.data.id);
            $('[name="kodeakun"]').val(data.data.accountno);
            $('[name="namaakun"]').val(data.data.acname);
            $('[name="jenis"]').val(data.data.actype);
            $('[name="level"]').val(data.data.aclevel);
			$('[name="kasbank"]').val(data.data.kasbank);
			$('[name="aktif"]').val(data.data.aktif);
			
            setSelectTahun(data);
            
            inisiasiFormSaldoBudget();
            
            $("#tahunSaldoAwal").prop('disabled', false);
            $('#tahunSaldoAwal').on('change', function() {
                var value = $(this).val();

                if(value != ''){
                    $.ajax({
                        url : "<?php echo site_url('akuntansi_akun/get_data_tahun_saldo_awal'); ?>?tahun=" + value + "&accountno=" + accountno,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data)
                        {
                            $('#saldo01').val(data.data[0].saldo01);
                            $('#saldo02').val(data.data[0].saldo02);
                            $('#saldo03').val(data.data[0].saldo03);
                            $('#saldo04').val(data.data[0].saldo04);
                            $('#saldo05').val(data.data[0].saldo05);
                            $('#saldo06').val(data.data[0].saldo06);
                            $('#saldo07').val(data.data[0].saldo07);
                            $('#saldo08').val(data.data[0].saldo08);
                            $('#saldo09').val(data.data[0].saldo09);
                            $('#saldo10').val(data.data[0].saldo10);
                            $('#saldo11').val(data.data[0].saldo11);
                            $('#saldo12').val(data.data[0].saldo12);

                            $('#bg01').val(data.data[0].bg01);
                            $('#bg02').val(data.data[0].bg02);
                            $('#bg03').val(data.data[0].bg03);
                            $('#bg04').val(data.data[0].bg04);
                            $('#bg05').val(data.data[0].bg05);
                            $('#bg06').val(data.data[0].bg06);
                            $('#bg07').val(data.data[0].bg07);
                            $('#bg08').val(data.data[0].bg08);
                            $('#bg09').val(data.data[0].bg09);
                            $('#bg10').val(data.data[0].bg10);
                            $('#bg11').val(data.data[0].bg11);
                            $('#bg12').val(data.data[0].bg12);

                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error get data saldo from ajax');
                        }
                    });

                    $('#saldo-bulan-label').show();
                    $('#saldo-bulan').show();
                    $('#saldo-bulan-2').show();
                    
                    $('#budget-label').show();
                    $('#budget').show();
                    $('#budget-2').show();
                } else {
                    $('#saldo-bulan-label').hide();
                    $('#saldo-bulan').hide();
                    $('#saldo-bulan-2').hide();

                    $('#budget-label').hide();
                    $('#budget').hide();
                    $('#budget-2').hide();
                }

            });

            showTabelSaldoBudget(data);

            $('#modal_form_large').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function setSelectTahun(data){
    $('#tahunSaldoAwal').html('');
    var formoption = "<option value=''>-</option>";
    $.each(data.saldo_awal, function( key, value ) {     
        formoption += "<option value='" + value.tahun + "'>" + value.tahun + "</option>";
    });
    $('#tahunSaldoAwal').html(formoption);
}

function showTabelSaldoBudget(data){
    $('#saldo_awal').empty();
    $.each(data.saldo_awal, function( key, value ) {
        $('#saldo_awal').append("<tr>\
                                <td style='text-align: center'>"+value.tahun+"</td>\
                                <td style='text-align: center'>"+value.saldo01+"</td>\
                                <td style='text-align: center'>"+value.saldo02+"</td>\
                                <td style='text-align: center'>"+value.saldo03+"</td>\
                                <td style='text-align: center'>"+value.saldo04+"</td>\
                                <td style='text-align: center'>"+value.saldo05+"</td>\
                                <td style='text-align: center'>"+value.saldo06+"</td>\
                                <td style='text-align: center'>"+value.saldo07+"</td>\
                                <td style='text-align: center'>"+value.saldo08+"</td>\
                                <td style='text-align: center'>"+value.saldo09+"</td>\
                                <td style='text-align: center'>"+value.saldo10+"</td>\
                                <td style='text-align: center'>"+value.saldo11+"</td>\
                                <td style='text-align: center'>"+value.saldo12+"</td>\
                                </tr>");
    });

    $('#budgeting').empty();
    $.each(data.saldo_awal, function( key, value ) {
        $('#budgeting').append("<tr>\
                                <td style='text-align: center'>"+value.tahun+"</td>\
                                <td style='text-align: center'>"+value.bg01+"</td>\
                                <td style='text-align: center'>"+value.bg02+"</td>\
                                <td style='text-align: center'>"+value.bg03+"</td>\
                                <td style='text-align: center'>"+value.bg04+"</td>\
                                <td style='text-align: center'>"+value.bg05+"</td>\
                                <td style='text-align: center'>"+value.bg06+"</td>\
                                <td style='text-align: center'>"+value.bg07+"</td>\
                                <td style='text-align: center'>"+value.bg08+"</td>\
                                <td style='text-align: center'>"+value.bg09+"</td>\
                                <td style='text-align: center'>"+value.bg10+"</td>\
                                <td style='text-align: center'>"+value.bg11+"</td>\
                                <td style='text-align: center'>"+value.bg12+"</td>\
                                </tr>");
    });
}

function reset_form_saldo_budget_baru(){
    $('#isi-tahun').hide();
    $('#saldo-bulan-label').hide();
    $('#saldo-bulan').hide();
    $('#saldo-bulan-2').hide();
    $("#tahunSaldoAwal").prop('disabled', false);

    $('#budget-label').hide();
    $('#budget').hide();
    $('#budget-2').hide();

    $('#btn-data-baru').show();
    $('#btn-simpan').hide();
}

function isi_saldo_budget_baru(){
    inisiasiFormSaldoBudget();
    
    $('#isi-tahun').show();
    $('#saldo-bulan-label').show();
    $('#saldo-bulan').show();
    $('#saldo-bulan-2').show();
    
    $('#budget-label').show();
    $('#budget').show();
    $('#budget-2').show();
    $("#tahunSaldoAwal").prop('disabled', true);
    // $('#form-tambah-saldo-budget').show();

    $('#btn-data-baru').hide();
    $('#btn-simpan').show();

    $("#btnSave").prop('disabled', true);
}

function save_saldo_budget_baru(){
    var tahun = $('#tahun').val();
    var accountno = $('[name="kodeakun"]').val();

    if(tahun == ''){
        $('#validasiTahun').text('Mohon, isi tahun terlebih dahulu!');
    } else {
        $.ajax({
            url : "<?php echo site_url('akuntansi_akun/cek_tahun'); ?>?tahun=" + tahun + "&accountno=" + accountno,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                if(data.jml > 0){
                    $('#validasiTahun').text('Maaf, tahun yang Anda masukkan sudah ada sebelumnya.');
                } else {
                    console.log($('#form-edit').serialize());

                    $.ajax({
                        url : "<?php echo site_url('akuntansi_akun/add_saldo_budget_baru')?>",
                        type: "POST",
                        data: $('#form-edit').serialize(),
                        dataType: "JSON",
                        success: function(data)
                        {
                            console.log(data);
                            setSelectTahun(data)
                            showTabelSaldoBudget(data)
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error adding / update data');
                            // $('#btnSave').text('save'); //change button text
                            // $('#btnSave').attr('disabled',false); //set button enable 

                        }
                    });
                    
                    reset_form_saldo_budget_baru();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error cek tahun');
            }
        });
    }

}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    
    // console.log($('#form-edit').serialize());
    
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    var formInput = $('#form').serialize();
    if(save_method == 'add') {
        url = "<?php echo site_url('akuntansi_akun/ajax_add')?>";
    } else {
        url = "<?php echo site_url('akuntansi_akun/ajax_update')?>";
        formInput = $('#form-edit').serialize();
    }
    

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: formInput,
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                // $('#modal_form').modal('hide');
                
                $('#modal_form_large').modal('hide');
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
    if(confirm('Yakin data akun dengan id '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('akuntansi_akun/ajax_delete')?>/"+id,
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
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>akuntansi_akun/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Akun</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Akun</label>
                            <div class="col-md-9">
                                <input name="kodeakun" placeholder="Kode Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Akun</label>
                            <div class="col-md-9">
                                <input name="namaakun" placeholder="Nama Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tipe Akun</label>
                            <div class="col-md-9">
                                 <select id="jenis" name="jenis" class="form-control input-mediumx select2" data-placeholder="Pilih..." required>
            											
								<?php 
									foreach($tipeakun as $row){?>
									<option value="<?php echo $row['actype'];?>"><?php echo $row['typename'];?></option>
								<?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label class="control-label col-md-3">Akun Level</label>
                            <div class="col-md-9">
                                <select id="level" name="level" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="">&nbsp;</option>						
								<option value="1">1</option>						
								<option value="2">2</option>						
								<option value="3">3</option>						
								<option value="4">4</option>						
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Kas/Bank</label>
                            <div class="col-md-9">
                                <select id="kasbank" name="kasbank" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Tidak</option>						
								<option value="1">Ya</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Aktif</label>
                            <div class="col-md-9">
                                <select id="aktif" name="aktif" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Ya</option>						
								<option value="1">Tidak</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Bootstrap modal -->
<div class="modal fade bd-example-modal-lg" id="modal_form_large" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Akun</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form-edit" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Akun</label>
                            <div class="col-md-9">
                                <input name="kodeakun" placeholder="Kode Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Akun</label>
                            <div class="col-md-9">
                                <input name="namaakun" placeholder="Nama Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tipe Akun</label>
                            <div class="col-md-9">
                                 <select id="jenis" name="jenis" class="form-control input-mediumx select2" data-placeholder="Pilih..." required>
            											
								<?php 
									foreach($tipeakun as $row){?>
									<option value="<?php echo $row['actype'];?>"><?php echo $row['typename'];?></option>
								<?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label class="control-label col-md-3">Akun Level</label>
                            <div class="col-md-9">
                                <select id="level" name="level" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="">&nbsp;</option>						
								<option value="1">1</option>						
								<option value="2">2</option>						
								<option value="3">3</option>						
								<option value="4">4</option>						
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Kas/Bank</label>
                            <div class="col-md-9">
                                <select id="kasbank" name="kasbank" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Tidak</option>						
								<option value="1">Ya</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Aktif</label>
                            <div class="col-md-9">
                                <select id="aktif" name="aktif" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Ya</option>						
								<option value="1">Tidak</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tahun<br/>(Saldo Awal & Budgeting)</label>
                            <div class="col-md-9">
                                <select id="tahunSaldoAwal" name="tahunSaldoAwal" class="form-control input-mediumx" data-placeholder="Pilih..." ></select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- <div style='float:right;'> -->
                        
                        <div class="modal-footer" style="margin-bottom: -40px;">
                            <button type="button" class="btn btn-success" id="btn-data-baru" onclick="isi_saldo_budget_baru()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                            <button type="button" class="btn btn-info" id="btn-simpan" onclick="save_saldo_budget_baru()"><i class="glyphicon glyphicon-check"></i> Simpan</button>
                            <button type="button" class="btn btn-danger" id="btn-batal" onclick="reset_form_saldo_budget_baru()"><i class="glyphicon glyphicon-times"></i> Reset</button>
                        </div>
                        <!-- </div> -->
                        <div class="form-group" id="isi-tahun" style='display:none;'>
                            <div class="col-md-12">
                                <div class="col-md-2" style='padding-left: 0px;'>
                                    <label class="control-label"><b>Tahun</b></label>
                                </div>
                                <div class="col-md-4">
                                    <input id="tahun" name="tahun" placeholder="Tahun" class="form-control" type="text">
                                </div>
                                <div class="col-md-6" style='padding-left: 0px;'>
                                    <label id="validasiTahun" class="control-label" style="color:red;"><b></b></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="saldo-bulan-label" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label"><b>Saldo Awal</b></label>
                            </div>
                        </div>
                        <div class="form-group" id="saldo-bulan" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label col-md-2"><center>Jan</center></label>
                                <label class="control-label col-md-2"><center>Feb</center></label>
                                <label class="control-label col-md-2"><center>Mar</center></label>
                                <label class="control-label col-md-2"><center>Apr</center></label>
                                <label class="control-label col-md-2"><center>Mei</center></label>
                                <label class="control-label col-md-2"><center>Jun</center></label>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <input id="saldo01" name="saldo01" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo02" name="saldo02" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo03" name="saldo03" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo04" name="saldo04" placeholder="" class="form-control" type="number" min='0'>
                                </div> 
                                <div class="col-md-2">
                                    <input id="saldo05" name="saldo05" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo06" name="saldo06" placeholder="" class="form-control" type="number" min='0'>
                                </div> 
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="saldo-bulan-2" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label col-md-2"><center>Jul</center></label>
                                <label class="control-label col-md-2"><center>Sep</center></label>
                                <label class="control-label col-md-2"><center>Agt</center></label>
                                <label class="control-label col-md-2"><center>Okt</center></label>
                                <label class="control-label col-md-2"><center>Nov</center></label>
                                <label class="control-label col-md-2"><center>Des</center></label>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <input id="saldo07" name="saldo07" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo08" name="saldo08" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo09" name="saldo09" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo10" name="saldo10" placeholder="" class="form-control" type="number">
                                </div> 
                                <div class="col-md-2">
                                    <input id="saldo11" name="saldo11" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="saldo12" name="saldo12" placeholder="" class="form-control" type="number">
                                </div> 
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="budget-label" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label"><b>Budgeting</b></label>
                            </div>
                        </div>
                        <div class="form-group" id="budget" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label col-md-2"><center>Jan</center></label>
                                <label class="control-label col-md-2"><center>Feb</center></label>
                                <label class="control-label col-md-2"><center>Mar</center></label>
                                <label class="control-label col-md-2"><center>Apr</center></label>
                                <label class="control-label col-md-2"><center>Mei</center></label>
                                <label class="control-label col-md-2"><center>Jun</center></label>
                            </div>
                            <div class="col-md-12">
                                
                                <div class="col-md-2">
                                    <input id="bg01" name="bg01" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="bg02" name="bg02" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="bg03" name="bg03" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="bg04" name="bg04" placeholder="" class="form-control" type="number" min='0'>
                                </div> 
                                <div class="col-md-2">
                                    <input id="bg05" name="bg05" placeholder="" class="form-control" type="number" min='0'>
                                </div>
                                <div class="col-md-2">
                                    <input id="bg06" name="bg06" placeholder="" class="form-control" type="number" min='0'>
                                </div> 
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="budget-2" style='display:none;'>
                            <div class="col-md-12">
                                <label class="control-label col-md-2"><center>Jul</center></label>
                                <label class="control-label col-md-2"><center>Sep</center></label>
                                <label class="control-label col-md-2"><center>Agt</center></label>
                                <label class="control-label col-md-2"><center>Okt</center></label>
                                <label class="control-label col-md-2"><center>Nov</center></label>
                                <label class="control-label col-md-2"><center>Des</center></label>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <input id="bg07" name="bg07" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="bg08" name="bg08" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="bg09" name="bg09" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="bg10" name="bg10" placeholder="" class="form-control" type="number">
                                </div> 
                                <div class="col-md-2">
                                    <input id="bg11" name="bg11" placeholder="" class="form-control" type="number">
                                </div>
                                <div class="col-md-2">
                                    <input id="bg12" name="bg12" placeholder="" class="form-control" type="number">
                                </div> 
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body">
                <h4>Saldo Awal (Jika Ada)</h4>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center">Tahun</th>
                                <th style="text-align: center">Jan</th>
                                <th style="text-align: center">Feb</th>
                                <th style="text-align: center">Mar</th>
                                <th style="text-align: center">Apr</th>
                                <th style="text-align: center">Mei</th>
                                <th style="text-align: center">Jun</th>
                                <th style="text-align: center">Jul</th>
                                <th style="text-align: center">Agt</th>
                                <th style="text-align: center">Sep</th>
                                <th style="text-align: center">Okt</th>
                                <th style="text-align: center">Nov</th>
                                <th style="text-align: center">Des</th>
                            </tr>
                    </thead>
                    <tbody id="saldo_awal">
                        <!-- <tr>
                                <td style="text-align: center">Tahun</td>
                                <td style="text-align: center">Jan</td>
                                <td style="text-align: center">Feb</td>
                                <td style="text-align: center">Mar</td>
                                <td style="text-align: center">Apr</td>
                                <td style="text-align: center">Mei</td>
                                <td style="text-align: center">Jun</td>
                                <td style="text-align: center">Jul</td>
                                <td style="text-align: center">Agt</td>
                                <td style="text-align: center">Sep</td>
                                <td style="text-align: center">Okt</td>
                                <td style="text-align: center">Nov</td>
                                <td style="text-align: center">Des</td>
                            </tr> -->
                    </tbody>
                </table>
                <br>
                <h4>Budgeting / Anggaran (Jika Ada)</h4>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center">Tahun</th>
                                <th style="text-align: center">Jan</th>
                                <th style="text-align: center">Feb</th>
                                <th style="text-align: center">Mar</th>
                                <th style="text-align: center">Apr</th>
                                <th style="text-align: center">Mei</th>
                                <th style="text-align: center">Jun</th>
                                <th style="text-align: center">Jul</th>
                                <th style="text-align: center">Agt</th>
                                <th style="text-align: center">Sep</th>
                                <th style="text-align: center">Okt</th>
                                <th style="text-align: center">Nov</th>
                                <th style="text-align: center">Des</th>
                            </tr>
                    </thead>
                    
                    <tbody id="budgeting">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->