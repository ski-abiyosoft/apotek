<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	

<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">   
<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/css/datepicker.css')?>" rel="stylesheet" type="text/css" />
    

        <div class="row">
            <div class="col-md-12">
                <h3 class="page-title">
                    Payroll <small>Data Karyawan</small>
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
                        <a href="#">
                            Karyawan
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
                            Daftar Karyawan
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="btn-group">
                                <!--button id="master-bank_new" class="btn green">
                                Data Baru <i class="fa fa-plus"></i>
                                </button-->
                                
                                
                            </div>
                            <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                            <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>								
                        </div>
                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                    <tr>
                                        <th style="text-align: center;width:5%">NIK</th>
                                        <th style="text-align: center;width:40%">Nama</th>                                         
                                        <th style="text-align: center;width:10%">No. KTP</th>                                         
                                        <!--th style="text-align: center;width:10%">Jabatan</th>  
                                        <th style="text-align: center;width:10%">Cabang</th-->  
                                        <th style="text-align: center;width:16%">Aksi</th>

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
$this->load->view('template/footer');
$this->load->view('template/v_report');
?>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/js/date.format.js')?>"></script>

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
        "url": "<?php echo site_url('hrd_karyawan/ajax_list')?>",
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

//datepicker
$('.datepicker').datepicker({
    autoclose: true,
    format: "dd-mm-yyyy",
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

function edit_data(id)
{
save_method = 'update';
$('#form')[0].reset(); // reset form on modals
$('.form-group').removeClass('has-error'); // clear error class
$('.help-block').empty(); // clear error string

//Ajax Load data from ajax
$.ajax({
    url : "<?php echo site_url('hrd_karyawan/ajax_edit/')?>/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {			
        var tgllahir = new Date(data.tgllahir).format('dd-mm-yyyy');
        var tglmasuk = new Date(data.tglmasuk).format('dd-mm-yyyy');
        var tglkeluar = new Date(data.tglkeluar).format('dd-mm-yyyy');
        
        $('[name="id"]').val(data.id);
        $('[name="nik"]').val(data.nip);
        $('[name="nama"]').val(data.nama);
        $('[name="cabang"]').val(data.unit_id);
        $('[name="noktp"]').val(data.noktp);
        $('[name="jabatan"]').val(data.jabatan_id);
        $('[name="departemen"]').val(data.departemen_id);
        $('[name="agama"]').val(data.agama_id);
        $('[name="alamat1"]').val(data.alamat1);
        $('[name="alamat2"]').val(data.alamat2);
        $('[name="rt"]').val(data.rt);
        $('[name="rw"]').val(data.rw);
        $('[name="kelurahan"]').val(data.kelurahan);
        $('[name="kota"]').val(data.kota);
        $('[name="hp"]').val(data.hp);
        $('[name="referensi"]').val(data.referensi);
        $('[name="tgllahir"]').val(tgllahir);			
        $('[name="tglmasuk"]').val(tglmasuk);
        $('[name="tglkeluar"]').val(tglkeluar);
        $('[name="kelamin"]').val(data.kelamin);
        $('[name="gapok"]').val(data.gapok);
        $('[name="tunjanganpph"]').val(data.tunjanganpph);
        $('[name="lembur"]').val(data.uanglembur);
        $('[name="transport"]').val(data.uangtransport);
        $('[name="pulsa"]').val(data.uangpulsa);
        $('[name="makan"]').val(data.uangmakan);
        $('[name="jkm"]').val(data.jkm);
        $('[name="jkk"]').val(data.jkk);
        $('[name="askes"]').val(data.askes);
        $('[name="alasankeluar"]').val(data.alasanberhenti);
        $('[name="wargenegara"]').val(data.wargenegara);
        $('[name="grossup"]').val(data.grossup);
        $('[name="ptkp"]').val(data.ptkp_id);
        
        
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
    url = "<?php echo site_url('hrd_karyawan/ajax_add')?>";
} else {
    url = "<?php echo site_url('hrd_karyawan/ajax_update')?>";
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
if(confirm('Yakin data Karyawan dengan kode '+id+' ini akan dihapus ?'))
{
    // ajax delete data to database
    $.ajax({
        url : "<?php echo site_url('hrd_karyawan/ajax_delete')?>/"+id,
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

function cetaklap(str) {
var xhttp;
if (str == "") {
document.getElementById("txtHint").innerHTML = "";
return;
}
        
xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("txtHint").innerHTML = this.responseText;
}
};  

xhttp.open("GET", "<?php echo base_url(); ?>hrd_karyawan/cetak/"+str, true);  
xhttp.send();
}


</script>

<script>
$(document).ready(function() {
    
    $('.print_laporan').on("click", function(){
    $('.modal-title').text('MASTER');
    var no_daftar= this.id;
    $("#simkeureport").html('<iframe src="<?php echo base_url();?>hrd_karyawan/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
    });	
});

jQuery(document).ready(function() {
ComponentsPickers.init();
});
</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
<div class="modal-dialog modal-full">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Data Karyawan</h3>
        </div>
        <div class="modal-body form">
            <form action="#" id="form" class="form-horizontal">
                <input type="hidden" value="" name="id"/> 
                <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                Profil Karyawan
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab">
                                Gaji Karyawan
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">	
                    <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">NIK</label>
                        <div class="col-md-3">
                            <input name="nik" placeholder="Nomor Karyawan" class="form-control input-small" maxlength="10" type="text">
                            <span class="help-block"></span>
                        </div>
                        
                        <label class="control-label col-md-3">Warga Negara</label>
                        <div class="col-md-3">
                            <select name="wargenegara" class="form-control">
                                <option value="WNI">WNI</option>
                                <option value="WNA">WNA</option>
                                
                            </select>
                        </div>
                        
                    </div>
                    </div> 
                    <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Grossup Pajak</label>
                        <div class="col-md-3">
                            <select name="grossup" class="form-control">
                                <option value="Y">Ya</option>
                                <option value="N">Tidak</option>
                                
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <label class="control-label col-md-3">PTKP</label>
                        <div class="col-md-3">
                            <select name="ptkp" class="form-control">
                                <?php 
                                $ptkp = $this->db->get('ms_ptkp')->result(); 
                                foreach($ptkp as $db) {?>
                                <option value="<?php echo $db->id_ptkp;?>"><?php echo $db->kode;?></option>
                                <?php } ?> 
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    </div>	
                    </div>	
                    
                    <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Nama</label>
                        <div class="col-md-9">
                            <input name="nama" placeholder="nama" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    </div>	
                    <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">No. KTP</label>
                        <div class="col-md-9">
                            <input name="noktp" placeholder="Nomor KTP (16 Digit)" class="form-control" maxlength="16" type="text">
                            
                        </div>
                    </div>
                    </div>	
                    </div>	
                    
                    <div class="row">
                    <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Kelamin</label>
                        <div class="col-md-9">
                            <select name="kelamin" class="form-control">
                                
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                                
                            </select>
                            
                        </div>
                    </div>
                    </div>
                    
                    <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Departemen</label>
                        <div class="col-md-9">
                            <select name="departemen" class="form-control">
                                <option value="">--Kode--</option>
                                <?php 
                                foreach($dep->result_array() as $db) {?>
                                <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    </div>
                    </div>
                    
                <div class="row">
                    <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Jabatan</label>
                        <div class="col-md-9">
                            <select name="jabatan" class="form-control">
                                <option value="">--Pilih --</option>
                                <?php 
                                foreach($jab->result_array() as $db) {?>
                                <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    </div>	
                    
                    <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Agama</label>
                        <div class="col-md-9">
                            <select name="agama" class="form-control">
                                <option value="">--Pilih --</option>
                                <?php 
                                foreach($agm->result_array() as $db) {?>
                                <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="row">
                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Alamat 1</label>
                        <div class="col-md-9">
                            <input name="alamat1" placeholder="Alamat1" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Alamat 2</label>
                        <div class="col-md-9">
                            <input name="alamat2" placeholder="Alamat2" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">RT</label>
                        <div class="col-md-3">
                            <input name="rt" placeholder="RT" class="form-control" type="text" maxlength="3">
                            
                        </div>
                        <label class="control-label col-md-3">RW</label>
                        <div class="col-md-3">
                            <input name="rw" placeholder="RW" class="form-control" type="text" maxlength="3">
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Kelurahan</label>
                        <div class="col-md-3">
                            <input name="kelurahan"  class="form-control" type="text" maxlength="50">
                            
                        </div>
                        <label class="control-label col-md-3">Kota</label>
                        <div class="col-md-3">
                            <input name="kota"  class="form-control" type="text" maxlength="50">
                            
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Tgl. Lahir</label>
                        <div class="col-md-3">
                            <div class="input-icon">
                            <i class="fa fa-calendar"></i>
                            <input id="tgllahir" name="tgllahir" class="form-control date-picker" type="text"  data-date-format="dd-mm-yyyy" data-date-viewmode="years" />
                            </div> 
                        </div>
                        <label class="control-label col-md-3">Tgl. Masuk</label>
                        <div class="col-md-3">
                            <div class="input-icon">
                            <i class="fa fa-calendar"></i>
                            <input id="tglmasuk" name="tglmasuk" class="form-control date-picker" type="text"  data-date-format="dd-mm-yyyy" data-date-viewmode="years" />
                            </div> 
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">No. HP</label>
                        <div class="col-md-3">
                            <input name="hp"  class="form-control" type="text" maxlength="50">
                            
                        </div>
                        <label class="control-label col-md-3">Referensi</label>
                        <div class="col-md-3">
                            <input name="referensi"  class="form-control" type="text" maxlength="50">
                            
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6">	
                    <div class="form-group">
                        <label class="control-label col-md-3">Tgl. Keluar</label>
                        <div class="col-md-3">
                            <div class="input-icon">
                            <i class="fa fa-calendar"></i>
                            <input id="tglkeluar" name="tglkeluar" class="form-control date-picker" type="text"  data-date-format="dd-mm-yyyy" data-date-viewmode="years" />
                            </div> 
                        </div>
                        <label class="control-label col-md-3">Alasan Keluar</label>
                        <div class="col-md-3">
                            <select name="alasankeluar" class="form-control">
                                
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                
                            </select>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">	
                    
                </div>
                </div>
                
                </div> <!-- tab 1-->
                
                <div class="tab-pane" id="tab2">	
                        <div class="row">
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Gaji Pokok</label>
                                <div class="col-md-6">
                                    <input name="gapok"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Tunjangan PPH</label>
                                <div class="col-md-6">
                                    <input name="tunjanganpph"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Uang Lembur</label>
                                <div class="col-md-6">
                                    <input name="lembur"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Uang Transport</label>
                                <div class="col-md-6">
                                    <input name="transport"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Uang Pulsa</label>
                                <div class="col-md-6">
                                    <input name="pulsa"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Uang Makan</label>
                                <div class="col-md-6">
                                    <input name="makan"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">JKM</label>
                                <div class="col-md-6">
                                    <input name="jkm"  class="form-control rightJustified" type="text" onClick="formatCurrency(this)">
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">JKK</label>
                                <div class="col-md-6">
                                    <input name="jkk"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label class="control-label col-md-3">Askes</label>
                                <div class="col-md-6">
                                    <input name="askes"  class="form-control rightJustified" onClick="formatCurrency(this)" type="text">
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-6">	
                            
                        </div>
                    </div>
                </div>
                
                </div> <!-- tab content-->
                </div> <!-- tab-->	
                            
                
                    
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
