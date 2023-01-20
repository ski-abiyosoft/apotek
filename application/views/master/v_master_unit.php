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
                <span class="title-web">Master <small>Data Master Cabang</small>
                </h3>
                <ul class="page-breadcrumb breadcrumb">

                    <li>
                        <i style="color:white;" class="fa fa-home"></i>
                        <a style="color:white;" class="title-white" href="<?php echo base_url();?>dashboard">
                        Awal
                        </a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a style="color:white;" class="title-white" href="#">
                        Master
                        </a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a style="color:white;" class="title-white" href="#">
                        Cabang
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
                            Daftar Cabang
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="btn-group">
                            
                                
                            </div>
                            <button class="btn btn-success" onclick="add_bank()"><i class="glyphicon glyphicon-plus"></i><b> Data Baru</b></button>
                            <div class="btn-group pull-right">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url()?>master_unit/export">
                                            Export
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                        <thead class="breadcrumb header-custom">
                            <tr>
                                <th class="title-white" style="text-align: center">Kode</th>
                                <th class="title-white" style="text-align: center">Nama Cabang</th>
                                <th class="title-white" style="text-align: center">Kepala Cabang</th>
                                <th class="title-white" style="text-align: center">Alamat</th>
                                <th class="title-white" style="text-align: center">Kota</th>
                                <th class="title-white" style="text-align: center">Telpon</th>
                                <th class="title-white" style="text-align: center">Whatsapp</th>
                                <th class="title-white" style="text-align: center">Gambar</th>
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
$this->load->view('template/v_report');
?>


<script type="text/javascript">
var save_method; //for save method string
var table;

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#div_preview_foto').css("display","block");
        $('#preview_img').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    } else {
        $('#div_preview_foto').css("display","none");
        $('#preview_img').attr('src', '');
    }
}

$(document).ready(function() {

//datatables
table = $('#table').DataTable({ 

    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "<?php echo site_url('master_unit/ajax_list')?>",
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
    ],

});

$("#filefoto").change(function() {
        readURL(this);
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



function add_bank()
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
    url : "<?php echo site_url('master_unit/ajax_edit/')?>" + id,
    type: "POST",
    dataType: "JSON",
    success: function(data)
    {
        $('[name="kode"]').val(data.data.koders);
        $('[name="nama"]').val(data.data.namars);
        $('[name="alamat"]').val(data.data.alamat);
        $('[name="kota"]').val(data.data.kota);
        $('[name="telpon"]').val(data.data.phone);
        $('[name="wa"]').val(data.data.whatsapp);
        $('[name="fax"]').val(data.data.fax);
        $('[name="pejabat1"]').val(data.data.pejabat1);
        $('[name="pejabat2"]').val(data.data.pejabat2);
        $('[name="pejabat3"]').val(data.data.pejabat3);
        $('[name="pejabat4"]').val(data.data.pejabat4);
        $('[name="jabatan1"]').val(data.data.jabatan1);
        $('[name="jabatan2"]').val(data.data.jabatan2);
        $('[name="jabatan3"]').val(data.data.jabatan3);
        $('[name="jabatan4"]').val(data.data.jabatan4);
        $('[name="bank1"]').val(data.data.ketbank);
        $('[name="bank2"]').val(data.data.ketbank2);
        $('[name="bank3"]').val(data.data.ketbank3);
        $('[name="namaapotek"]').val(data.data.namaapotik);
        $('[name="apoteker"]').val(data.data.apoteker);
        $('[name="jabatan"]').val(data.data.jabatan);
        $('[name="noijin"]').val(data.data.noijin);
        $('[name="npwp"]').val(data.data.npwp);
        $('[name="pkp"]').val(data.data.pkpno);
        $('[name="pkpdate"]').val(data.data.tglpkp);
        $('[name="wahost"]').val(data.data.wahost);
        $('[name="watoken"]').val(data.data.watoken);
        $('#div_preview_foto').css("display","block");
        $('#preview_img').attr('src',data.foto_encoded);
        // $('[name="filefoto"]').attr(data.avatar);
    
        
                
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

function cek_size(params) {
    alert(this.files[0].size)
}

function save()
{
    $('input[type=file][data-max-size]').each(function(){
        if(typeof this.files[0] !== 'undefined'){
            var max = parseInt($(this).attr('max-size'),10),
            mySize = this.files[0].size;
            if(mySize > 100000){
                console.log(mySize)
                swal({
                    title: "MASTER CABANG",
                    html: "Ukuran gambar terlalu besar, maksimal ukuran 100 Kb",
                    type: "error",
                    confirmButtonText: "OK" 
                })
                $("#filefoto").val("");
                return mySize;
            } 
            else {
                var file_data   = $('#filefoto').prop('files')[0];
                    var form_data   = new FormData();
                    form_data.append('filefoto', file_data);
                        
                    var kodecab     = $('[name="kode"]').val();
                    var namacab     = $('[name="nama"]').val();

                // if (kodecab=='' || kodecab== null){
                // swal({
                //         title: "KODE",
                //         html: "<p>HARUS DI ISI !</p>",
                //         type: "error",
                //         confirmButtonText: "OK" 
                //     });    
                // return;
                // } 

                // if (namacab=='' || namacab== null){
                // swal({
                //         title: "NAMA CABANG",
                //         html: "<p>HARUS DI ISI !</p>",
                //         type: "error",
                //         confirmButtonText: "OK" 
                //     });    
                // return;
                // } 

                // if (logocab=='' || logocab== null){
                // swal({
                //         title: "LOGO",
                //         html: "<p>HARUS DI ISI !</p>",
                //         type: "error",
                //         confirmButtonText: "OK" 
                //     });    
                // return;
                // } 


                $('#btnSave').text('saving...'); //change button text
                $('#btnSave').attr('disabled',true); //set button disable 
                var url;

                if(save_method == 'add') {
                    url = "<?php echo site_url('master_unit/ajax_add')?>";
                    url2 = "<?php echo site_url('master_unit/upload')?>";
                } else {
                    url = "<?php echo site_url('master_unit/ajax_update')?>";
                    url2 = "<?php echo site_url('master_unit/upload')?>";
                    
                }
                // console.log($('#form').serialize());
                // ajax adding data to database

                var form = $('#form')[0];
                    var data = new FormData(form);
                $.ajax({
                    url           : url,
                    type          : "POST",
                    enctype       : 'multipart/form-data',
                    // data: $('#form').serialize(),
                    data          : data,
                    dataType      : "JSON",
                    processData: false, // false, it prevent jQuery form transforming the data into a query string
                    contentType   : false,
                    cache         : false,
                    timeout       : 600000,
                    success: function(data)
                    {

                        if(data.status=="1") //if success close modal and reload ajax table
                        {
                            // ajax upload foto
                            // $.ajax({				
                            //     url           : url2,
                            //     data          : form_data,
                            //     // data: $('#form').serialize() + "&form_data=" + form_data
                            //     type          : 'POST',
                            //     cache         : false,
                            //     contentType   : false,
                            //     processData   : false,
                            //     success:function(data){ 
                            //         if(data==1){                         
                            //             swal({
                            //                 title   : "MASTER CABANG",
                            //                 html    : "<p> Kode   : <b>"+kodecab+"</b> </p>"+
                            //                 "Nama   : " + namacab+"<br>"+
                            //                 "BERHASIL TERSIMPAN ",
                            //                 type    : "success",
                            //                 confirmButtonText   : "OK"
                            //                 }).then((value) => {
                            //                     $('#modal_form').modal('hide');
                            //                     reload_table();
                            //             });	
                            //         }else{
                                        
                            //             swal({
                            //                 title   : "MASTER CABANG",
                            //                 html    : "<p> Kode   : <b>"+kodecab+"</b> </p>"+
                            //                 "Nama   : " + namacab+"<br>"+
                            //                 "GAGAL TERSIMPAN ",
                            //                 type    : "error",
                            //                 confirmButtonText: "OK" 
                            //                 }).then((value) => {
                            //                     $('#modal_form').modal('hide');
                            //                     // reload_table();
                            //             });	
                            //         }			
                                                        
                            
                            //     },
                            //     error:function(data){
                            //         swal('MASTER CABANG','Data gagal disimpan ...','');   	
                            //     }
                            // });
                            swal({
                                title   : "MASTER CABANG",
                                html    : "<p> Kode   : <b>"+kodecab+"</b> </p>"+
                                "Nama   : " + namacab+"<br>"+
                                "BERHASIL TERSIMPAN ",
                                type    : "success",
                                confirmButtonText   : "OK"
                                }).then((value) => {
                                    $('#modal_form').modal('hide');
                                    reload_table();
                            });	

                        } else if(data.status=="2"){
                            
                            swal({
                                title: "MASTER CABANG",
                                html: "<p> Nama   : <b>"+kodecab+"</b> </p>"+ 
                                "Nama :  " + namacab+"<br>"+
                                "ADA DUPLIKAT, SILAHKAN UBAH KODE ",
                                type: "error",
                                confirmButtonText: "OK" 
                                }).then((value) => {
                                    return;
                            });	
                        } else{
                            swal({
                                title: "MASTER CABANG",
                                html: "<p> Nama   : <b>"+kodecab+"</b> </p>"+ 
                                "Nama :  " + namacab+"<br>"+
                                "GAGAL SIMPAN, SILAHKAN CEK KEMBALI ",
                                type: "error",
                                confirmButtonText: "OK" 
                                }).then((value) => {
                                    return;
                            });	
                        }
                        $('#btnSave').text('Simpan'); //change button text
                        $('#btnSave').attr('disabled',false); //set button enable 


                    },
                        error:function(data){
                            swal('MASTER CABANG','Data gagal disimpan ...','');   
                            $('#btnSave').text('save'); //change button text
                            $('#btnSave').attr('disabled',false); //set button enable 	
                    }
                });
            }
        }
    });
    
}

function delete_data(id)
{
if(confirm('Yakin data Cabang dengan kode '+id+' ini akan dihapus ?'))
{
    // ajax delete data to database
    $.ajax({
        url : "<?php echo site_url('master_unit/ajax_delete')?>/"+id,
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


</script>

<script>
$(document).ready(function() {
    
    $('.print_laporan').on("click", function(){
    $('.modal-title').text('MASTER');
    var no_daftar= this.id;
    $("#simkeureport").html('<iframe src="<?php echo base_url();?>master_unit/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
    });	
});

</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
<div class="modal-dialog-full">
    <div class="modal-content">
        <div class="modal-header header-custom">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title"><b>Data Cabang</b></h3>
        </div>
        <div class="modal-body form">
            <form action="#" id="form" class="form-horizontal"  method="post"  enctype="multipart/form-data">
            <input type="hidden" value="" name="id"/> 
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Kode</label>
                            <div class="col-md-8">
                                <input name="kode" placeholder="Kode" class="form-control" maxlength="3" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Nama Cabang</label>
                            <div class="col-md-8">
                                <input name="nama" placeholder="Nama Cabang" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Alamat</label>
                            <div class="col-md-8">
                                <textarea name="alamat" placeholder="Alamat Cabang" class="form-control" maxlength="255" ></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Kota</label>
                            <div class="col-md-8">
                                <input name="kota" placeholder="Nama Kota" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Telpon</label>
                            <div class="col-md-8">
                                <input name="telpon" placeholder="Nomor Telepon" class="form-control" maxlength="50" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Whatsapp</label>
                            <div class="col-md-8">
                                <input name="wa" placeholder="Nomor WA" class="form-control" maxlength="50" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Fax</label>
                            <div class="col-md-8">
                                <input name="fax" placeholder="Nomor Fax" class="form-control" maxlength="50" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Pejabat 1</label>
                            <div class="col-md-8">
                                <input name="pejabat1" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Pejabat 2</label>
                            <div class="col-md-8">
                                <input name="pejabat2" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Pejabat 3</label>
                            <div class="col-md-8">
                                <input name="pejabat3" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Pejabat 4</label>
                            <div class="col-md-8">
                                <input name="pejabat4" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jabatan 1</label>
                            <div class="col-md-8">
                                <input name="jabatan1" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jabatan 2</label>
                            <div class="col-md-8">
                                <input name="jabatan2" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jabatan 3</label>
                            <div class="col-md-8">
                                <input name="jabatan3" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jabatan 4</label>
                            <div class="col-md-8">
                                <input name="jabatan4" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>                    
                </div>

                <h3 class="form-section"><b>Rekening Bank</b></h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Rekening 1</label>
                            <div class="col-md-8">
                                <input name="bank1" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Rekening 2</label>
                            <div class="col-md-8">
                                <input name="bank2" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Rekening 3</label>
                            <div class="col-md-8">
                                <input name="bank3" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>					   
                </div>
                
                <h3 class="form-section"><b>Apotek</b></h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Nama Apotek</label>
                            <div class="col-md-8">
                                <input name="namaapotek" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Apoteker</label>
                            <div class="col-md-8">
                                <input name="apoteker" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jabatan</label>
                            <div class="col-md-8">
                                <input name="jabatan" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>                    
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">No. Ijin</label>
                            <div class="col-md-8">
                                <input name="noijin" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">NPWP</label>
                            <div class="col-md-8">
                                <input name="npwp" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">PKP</label>
                            <div class="col-md-8">
                                <input name="pkp" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4">PKP Date</label>
                            <div class="col-md-8">
                                <input name="pkpdate" placeholder="" class="form-control" type="date">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h3 class="form-section"><b>Whatsapp Server</b></h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Host</label>
                            <div class="col-md-8">
                                <input name="wahost" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Token</label>
                            <div class="col-md-8">
                                <input name="watoken" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>       
                </div>

                <h3 class="form-section"><b>LOGO</b></h3>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-md-3 text-md-right" for="foto">LOGO</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="form-group" id="div_preview_foto" style="display: none;">
                                    <label for="lbl_password_lama" class="form-control-label">Preview : </label>
                                    <div>
                                        
                                    </div>
                                    <img id="preview_img" src="#" alt="Preview Foto" width="100" class="rounded-circle shadow-sm img-thumbnail"/>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-9">
                                    <input type="file" data-max-size="2048" name="filefoto" id="filefoto" accept=".jpg,.jpeg,.png">
                                    
                                </div>
                            </div>
                            
                        </div>
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
