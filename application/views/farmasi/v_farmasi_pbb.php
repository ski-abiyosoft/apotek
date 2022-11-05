<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	

<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    
<style>	
div.tableContainer {
    clear: both;
    border: 1px solid #963;
    height: 285px;
    overflow: auto;
    width: 100%
}

html>body div.tableContainer {
    overflow: hidden;
    width: 100%
}


div.tableContainer table {
    float: left;	
}


html>body div.tableContainer table {

}


thead.fixedHeader tr {
    position: relative;
}

thead.fixedHeader th {
    background: #C96;
    border-left: 1px solid #EB8;
    border-right: 1px solid #B74;
    border-top: 1px solid #EB8;	
    padding: 4px 3px;
    text-align: left
}

html>body tbody.scrollContent {
    display: block;
    height: 262px;
    overflow: auto;
    width: 100%
}

html>body thead.fixedHeader {
    display: table;
    overflow: auto;
    width: 100%
}


tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
    background: #FFF;
    border-bottom: none;
    border-left: none;
    border-right: 1px solid #CCC;
    border-top: 1px solid #DDD;
    padding: 2px 3px 3px 4px
}


tbody.scrollContent tr.alternateRow td {
    background: #EEE;
    border-bottom: none;
    border-left: none;
    border-right: 1px solid #CCC;
    border-top: 1px solid #DDD;
    padding: 2px 3px 3px 4px
}
</style>	
	
  
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?> 
            </span>
            - 
            <span class="title-web">Farmasi <small>Permohonan Barang ke Gudang</small>
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
                <a class="title-white" href="#">
                    Farmasi
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">
                    Permohonan Barang 
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
                    Daftar Permohonan Barang - <?= $periode ?>
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">
                    <?php 
                    $cek =  $this->session->userdata('user_level'); 
                    if($cek==0){?> 
                    <?php }else{ ?>

                        <a href="<?php echo base_url()?>farmasi_pbb/entri" class="btn btn-success">
                        <i class="fa fa-plus"></i>
                        Data Baru
                        </a>

                    <?php } ?>
                    
                    </div>	
                    <!--button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button-->
                    <button class="btn btn-default" onclick="location.href='<?= $_SERVER['REQUEST_URI'] ?>'"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
                    
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
                            <tr class="page-breadcrumb breadcrumb">
                                <th class="title-white" style="text-align: center;width:10%">Cabang</th>
                                <th class="title-white" style="text-align: center;width:10%">User ID</th>
                                <th class="title-white" style="text-align: center;width:15%">No. Permohonan</th>
                                <th class="title-white" style="text-align: center;width:10%">Tanggal</th>
                                <th class="title-white" style="text-align: center;width:10%">Dari Gudang</th>
                                <th class="title-white" style="text-align: center;width:10%">Ke Gudang</th>
                                <th class="title-white" style="text-align: center;width:10%">Keterangan</th>
                                <th class="title-white" style="text-align: center;width:7%;">Aksi</th>

                            </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($list as $lkey => $lval){
                                $gdari  = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$lval->dari'")->row();
                                $gke    = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$lval->ke'")->row();

                        ?>
                        <tr>
                            <td><?= $lval->koders ?></td>
                            <td><?= $lval->username ?></td>
                            <td><?= $lval->mohonno ?></td>
                            <td><?= date("d/m/Y", strtotime($lval->tglmohon)) ?></td>
                            <td>
                                <?php if($gdari) : ?>
                                    <?= $gdari->keterangan ?>
                                <?php else : ?>
                                    <button class="btn btn-danger" disabled>GUDANG HILANG</button>
                                <?php endif; ?>
                            </td>
                            <td><?= $gke->keterangan ?></td>
                            <td><?= $lval->keterangan ?></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" onclick="location.href='/farmasi_pbb/edit/<?= $lval->id ?>'"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-warning btn-sm" onclick="window.open('/farmasi_pbb/cetak/?id=<?= $lval->mohonno ?>', 'open')"><i class="fa fa-print"></i></button>
                                <button class="btn btn-danger btn-sm" onclick="delete_data(<?= $lval->id ?>)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
  $this->load->view('template/footero');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>
	
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>


<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    // table = $('#table').DataTable({ 
    $('#table').DataTable({ 

        // "processing": true, //Feature control the processing indicator.
        // "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        // "ajax": {
        //     "url": "<?php echo site_url('farmasi_pbb/ajax_list/1')?>",
        //     "type": "POST"
        // },
		
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

function cetaklembarso()
{    
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#cetaklembarso').modal('show'); // show bootstrap modal
    $('.modal-title').text('Lembar Stock Opname'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inventory_mutasi_gudang/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="kodeitem"]').val(data.kodeitem);
            $('[name="satuan1"]').val(data.satuan1);
			$('[name="satuan2"]').val(data.satuan2);
			$('[name="satuan3"]').val(data.satuan3);
			$('[name="satuan4"]').val(data.satuan4);
			$('[name="qty1"]').val(data.qty1);
            $('[name="qty2"]').val(data.qty2);
			$('[name="qty3"]').val(data.qty3);
			$('[name="qty4"]').val(data.qty4);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

// function reload_table()
// {
//     table.ajax.reload(null,false); //reload datatable ajax 
// }

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('inventory_mutasi_gudang/ajax_add')?>";
    } else {
        url = "<?php echo site_url('inventory_mutasi_gudang/ajax_update')?>";
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
    if(confirm('Yakin data barang dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('farmasi_pbb/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                location.href='/farmasi_pbb';
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	// var id   = 2; 
	// var str  = id+'~'+tgl1+'~'+tgl2;
    var str  = tgl1+'~'+tgl2; 
    location.href='/farmasi_pbb/filter/'+ str;
	// table.ajax.url("<?php echo base_url('farmasi_pbb/ajax_list/')?>"+str).load();		
}

</script>

<script>
	
   jQuery(document).ready(function() {
        ComponentsPickers.init();
   });
	
</script>	





