    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>

    <!-- <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet"> -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>"
        rel="stylesheet">

    <style>
.swal2-container {
    z-index: 1000000 !important;
}
    </style>

    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?php echo $this->session->userdata('unit'); ?>
                </span>
                -
                <span class="title-web">Master <small>Pengguna Aplikasi</small>
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
                        Pengguna Aplikasi
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
                        Daftar Pengguna Aplikasi
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">

                        </div>
                        <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i>
                            Data Baru</button>
                        <button class="btn btn-default" onclick="reload_table()"><i
                                class="glyphicon glyphicon-refresh"></i> Refresh</button>
                        <!--div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>master_user/export">
												 Export
											</a>
										</li>
									</ul> 
								</div-->
                    </div>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center">User ID</th>
                                <th style="text-align: center">Nama Lengkap</th>
                                <th style="text-align: center">Grup User</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Level</th>

                                <th style="text-align: center;width:16%;">Aksi</th>

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
            "url": "<?php echo site_url('master_user/ajax_list')?>",
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
        "columnDefs": [{
            "targets": [-1], //last column
            "orderable": false, //set not orderable
        }, ],

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
    $("input").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_data() {
    save_method = 'add';
    $('input:checkbox').removeAttr('checked');
    $("#nonmed").attr("checked", true).val();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('[name="uidlogin"]').attr('readonly', false);
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function edit_data(id) {
    save_method = 'update';
    $('input:checkbox').removeAttr('checked');
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url: "<?php echo site_url('master_user/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            if (data.status == "uidlength") {
                swal({
                    title: "Kesalahan",
                    html: "User ID maksimal 10 karakter",
                    type: "error",
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#aaa"
                });
            } else {
                $('[name="uidlogin"]').val(data.uidlogin).attr('readonly', true);
                $('[name="karyawan"]').val(data.username);
                $.each(data.cabang.split(","), function(i, keyword) {
                    $("#unit2[value='" + keyword + "']").attr("checked", true).val();
                }); 
                $('[name="grup"]').val(data.level);
                $('[name="aktif"]').val(data.locked);
                $('[name="shift"]').val(data.shift);
                $('[name="ulev"]').val(data.user_level);
                //$('[name="dob"]').datepicker('update',data.dob);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

                if (data.job_role == 1) {
                    $("#dr").attr("checked", true).val();
                } else if (data.job_role == 2) {
                    $("#param").attr("checked", true).val();
                } else {
                    $("#nonmed").attr("checked", true).val();
                }

                if (data.promas == 1) {
                    $("#all").attr("checked", true).val();
                } else if (data.promas == 2) {
                    $("#rj_igd").attr("checked", true).val();
                } else if (data.promas == 3) {
                    $("#ranap").attr("checked", true).val();
                } else {
                    $("#aps").attr("checked", true).val();
                }

            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}


function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}

function cekjobs(val) {

    // var status_job= val;
    $('[name="jobsrole"]').val(val);
}

function cek_promas(val) {

    // var status_job= val;
    $('[name="promass"]').val(val);
}



function save() {
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable 
    var url;

    if (save_method == 'add') {
        url = "<?php echo site_url('master_user/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_user/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $("#form").serialize(),
        dataType: "JSON",
        success: function(data) {
            if (data.status == "uidlength") {
                swal({
                    title: "Kesalahan",
                    html: "User ID maksimal 20 karakter",
                    type: "error",
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#aaa"
                });
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            } else {
                if (data.status) //if success close modal and reload ajax table
                {
                    if (data.info == 1) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else {
                        alert('User ID sudah terdaftar...')
                    }
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass(
                            'has-error'
                            ); //select parent twice to select div form-group class and add has-error class
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[
                            i]); //select span help-block class set text error string
                    }
                }
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 

        }
    });
}

function delete_data(id) {
    if (confirm('Yakin data Pemakai dengan ID ' + id + ' ini akan dihapus ?')) {
        // ajax delete data to database
        $.ajax({
            url: "<?php echo site_url('master_user/ajax_delete')?>/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

    }
}
    </script>

    <script>
var status_job = '';

function edit_menu(id) {
    $('.modal-title2').text('MASTER');
    var nomor = id;
    $("#menuappbody").html('<iframe src="<?php echo base_url();?>master_user_grup/menu/' + nomor +
        '" frameborder="no" width="100%" height="420"></iframe>');
    $('#menuapp').modal('show');
}

$(document).ready(function() {

    $('.print_laporan').on("click", function() {
        $('.modal-title').text('MASTER');
        var nomor = this.id;
        $("#simkeureport").html('<iframe src="<?php echo base_url();?>master_user_grup/cetak/' + nomor +
            '" frameborder="no" width="100%" height="420"></iframe>');
    });

    $('.edit_menu').on("click", function() {
        $('.modal-title2').text('MASTER');
        var nomor = this.id;
        $("#menuappbody").html(
            '<iframe src="<?php echo base_url();?>anggaran/anggaran_lap/cetak/1" frameborder="no" width="100%" height="420"></iframe>'
        );
    });

});

function myShow() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
    </script>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Data Pengguna Aplikasi</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="id" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">User ID</label>
                                <div class="col-md-9">
                                    <input name="uidlogin" id="uidlogin" placeholder="ID Pemakai"
                                        class="form-control input-medium" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Password</label>
                                <div class="col-md-9">
                                    <input id="password" name="password" placeholder="Password" type="password"
                                        class="form-control input-medium">
                                    <span class="help-block"></span>
                                    <input type="checkbox" onclick="myShow()"> Show Password
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Lengkap</label>
                                <div class="col-md-9">
                                    <input name="karyawan" id="karyawan" placeholder="Nama Lengkap" class="form-control"
                                        type="text">
                                    <span class="help-block"></span>
                                </div>
                                <table class="table" border="0" width="100%">
                                    <!-- <div class="col-md-9"> -->
                                    <tr>
                                        <td style="border-top:1px solid white;" align="right" width="23%">
                                            <label>Jobs Role</label>
                                            <input type="hidden" name="jobsrole" id="jobsrole">
                                        </td>
                                        <td style="border-top:1px solid white;" align="right" width="10%">
                                            <input type="radio" id="dr" name="jobs" onclick="cekjobs(this.value)"
                                                value="1">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="dr">Dokter</label>
                                        </td>
                                        &nbsp;&nbsp;&nbsp;

                                        <td style="border-top:1px solid white;">
                                            <input type="radio" id="param" name="jobs" onclick="cekjobs(this.value)"
                                                value="2">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="param">Paramedis</label>
                                        </td>
                                        &nbsp;&nbsp;&nbsp;

                                        <td style="border-top:1px solid white;">
                                            <input selected="true" type="radio" id="nonmed" name="jobs" onclick="cekjobs(this.value)" value="3">
                                        </td>
                                        <td colspan="3" style="border-top:1px solid white;">
                                            <label for="nonmed">Non Medis/ User Biasa</label>
                                        </td>
                                    </tr>

                                    <!-- promas -->
                                    <tr>
                                        <td style="border-top:1px solid white;" align="right" width="23%">
                                            <label>Prosedur Masuk</label>
                                            <input type="hidden" name="promass" id="promass">
                                        </td>

                                        <td style="border-top:1px solid white;" align="right" width="10%">
                                            <input selected="true" type="radio" id="all" name="promas" onclick="cek_promas(this.value)"
                                                value="1">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="all" >ALL</label>
                                        </td>
                                        &nbsp;&nbsp;&nbsp;

                                        <td style="border-top:1px solid white;">
                                            <input type="radio" id="rj_igd" name="promas" onclick="cek_promas(this.value)" value="2">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="rj_igd" >RJ/IGD</label>
                                        </td>
                                        &nbsp;&nbsp;&nbsp;

                                        <td style="border-top:1px solid white;">
                                            <input type="radio" id="ranap" name="promas" onclick="cek_promas(this.value)" value="3">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="ranap" >RANAP</label>
                                        </td>                                        
                                        &nbsp;&nbsp;&nbsp;

                                        <td style="border-top:1px solid white;">
                                            <input type="radio" id="aps" name="promas" onclick="cek_promas(this.value)" value="4">
                                        </td>
                                        <td style="border-top:1px solid white;">
                                            <label for="aps" >APS</label>
                                        </td>
                                    </tr>
                                    <!-- </div> -->
                                </table>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Cabang</label>
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                        <div class="checkbox" style="margin-left:20px">
                                            <label><input type="checkbox" id="checkall">Pilih Semua</label>
                                        </div>
                                    </div>
                                    <?php 
									foreach($uid->result_array() as $db) {?>
                                    <div class="col-md-4">
                                        <input type="checkbox" value="<?= $db['koders'] ?>" id="unit2" name="cabang[]"
                                            class="checkitem"> <?= $db['koders'] ?>
                                    </div>
                                    <?php } ?>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pilihan Shift</label>
                                <div class="col-md-9">
                                    <select name="shift" class="form-control">
                                        <?php 
                                        foreach($shift->result_array() as $db) {?>
                                        <option value="<?php echo $db['kodeset'];?>"><?php echo $db['keterangan'];?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Grup Pengguna</label>
                                <div class="col-md-9">
                                    <select name="grup" class="form-control">
                                        <option value="">--Pilih Grup--</option>
                                        <?php 
									foreach($grup->result_array() as $db) {?>
                                        <option value="<?php echo $db['nomor'];?>"><?php echo $db['nmgrup'];?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Aktif/Non-Aktif</label>
                                <div class="col-md-9">
                                    <select name="aktif" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="0">Aktif</option>
                                        <option value="1">Non-Aktif</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">User Level</label>
                                <div class="col-md-9">
                                    <select name="ulev" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="0">Khusus</option>
                                        <option value="1">Owner</option>
                                        <option value="2">Supervisor</option>
                                        <option value="3">User</option>
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

    <script>
$('#checkall').click(function() {
    $('.checkitem').prop('checked', this.checked);
});
    </script>