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
                        <span class="title-web">Master <small>Dokter</small>
                    </h3>
                        <ul class="page-breadcrumb breadcrumb">

                        <li>
                            <i class="fa fa-home"></i>
                            <a class="title-white" href="<?php echo base_url();?>dashboard">
                                Awal
                            </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a class="title-white" href="#">
                                Master
                            </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a class="title-white" href="#">
                                Dokter
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption">
                                Daftar Dokter
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <div class="btn-group">
                                
                                    
                                </div>
                                <button class="btn btn-success" onclick="add_dokter()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                
                            </div>
                            <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                                <thead class="breadcrumb">
                                        <tr>
                                            <th class="title-white" style="text-align: center">Cabang</th>
                                            <th class="title-white" style="text-align: center">Kode</th>
                                            <th class="title-white" style="text-align: center">Nama</th>
                                            <th class="title-white" style="text-align: center;width:10;">Aksi</th>

                                        </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption">
                                Dokter Poli
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <div class="btn-group">
                                
                                    
                                </div>
                                <button class="btn btn-success" onclick="add_poli()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                
                            </div>
                            <table id="table_poli" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                                <thead class="breadcrumb header-custom">
                                        <tr>
                                            <th class="title-white" style="text-align: center">Dokter</th>
                                            <th class="title-white" style="text-align: center">Kode</th>
                                            <th class="title-white" style="text-align: center">Nama Poli</th>
                                            <th class="title-white" style="text-align: center;width:5;">Aksi</th>

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
    var table_poli;

    $(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_dokter/ajax_list')?>",
            "type": "POST"
        },
        
        "oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Cari :",
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

    table_poli = $('#table_poli').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_dokter/poli_list')?>",
            "type": "POST"
        },
        
        "oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Cari :",
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



    function add_dokter()
    {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('[name="statusicd"]').val(0);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    }

    function add_poli()
    {
    save_method = 'add';
    $('#form_poli')[0].reset(); // reset form on modals
    var kode = $('#id_dokter_pilih').val();  
    var nama = $('#nama_dokter_pilih').val();  
    $('#kode_dokter').val(kode);
    $('#nama_dokter').val(nama);

    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_poli').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Dokter Poli'); // Set Title to Bootstrap modal title
    }

    function edit_data(id)
    {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('[name="statusicd"]').val(id);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_dokter/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data.cabang)
            $('#KodeDokter').val(data.kodokter);
            $('#Alamat').val(data.alamat);
            $('#NamaDokter').val(data.nadokter);
            $('#NIKIdentitas').val(data.nik);
            $('#SIP').val(data.nosip);
            $('#Status').val(data.status)
            $('#NPWP').val(data.npwp)
            $('#NoHP').val(data.hp)
            $('#tglmasuk').val(moment(data.tglmasuk).format('YYYY-MM-DD'));
            $('#tglberhenti').val(moment(data.tglberhenti).format('YYYY-MM-DD'));

            if (data.unit.length > 0){
                var unitStatus = document.querySelectorAll('[name="status_unit[]"]')

                if (data.unit.length > unitStatus.length) {
                    var selisih = data.unit.length - unitStatus.length

                    for (var i = 0; i < selisih; i++){
                        tambah_unit()
                    }
                }else if(data.unit.length < unitStatus.length) {
                    var selisih = unitStatus.length - data.unit.length

                    for (var i = 0; i < selisih; i++){
                        hapusBaris_unit()
                    }
                }

                var unitStatus = document.querySelectorAll('[name="status_unit[]"]')
                var button = document.querySelectorAll('.hapus-unit')
                
                for (var i = 0; i < unitStatus.length; i++){
                    unitStatus[i].value = data.unit[i].kopoli
                    button[i].dataset.kodokter = data.kodokter
                    button[i].dataset.kopoli = data.unit[i].kopoli
                }
            }

            if (data.cabang.length > 0){
                var unitCabang = document.querySelectorAll('[name="status_lokasi[]"]')
                if (data.cabang.length > unitCabang.length) {
                    var selisih = data.cabang.length - unitCabang.length

                    for (var i = 0; i < selisih; i++){
                        tambah_lokasi()
                    }
                }else if(data.cabang.length < unitCabang.length) {
                    var selisih = unitStatus.length - data.unit.length

                    for (var i = 0; i < selisih; i++){
                        hapusBaris_lokasi()
                    }
                }

                unitCabang = document.querySelectorAll('[name="status_lokasi[]"]')
                var button = document.querySelectorAll('.hapus-lokasi')
                for (var i = 0; i < unitCabang.length; i++){
                    unitCabang[i].value = data.cabang[i].koders
                    button[i].dataset.kodokter = data.kodokter
                    button[i].dataset.koders = data.cabang[i].koders
                }
            }

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

    function reload_table_poli()
    {
    table_poli.ajax.reload(null,false); //reload datatable ajax 
    }

    function save()
    {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled',true); //set button disable 
        var url;

        if(save_method == 'add') {
            url = "<?php echo site_url('master_dokter/ajax_add')?>";
        } else {
            url = "<?php echo site_url('master_dokter/ajax_update')?>";
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
                    if(data.status==true){
                        $('#modal_form').modal('hide')                            
                        swal({
                            title: "MASTER DOKTER",
                            html: "Berhasil Tersimpan",
                            type: "success",
                            confirmButtonText: "OK" 
                            }).then((value) => {
                                    location.href = "<?php echo base_url()?>master_dokter";
                        });
                        reload_table();
                    }else{
                        swal({
                            title: "MASTER DOKTER",
                            html: "Gagal Tersimpan !!",
                            type: "error",
                            confirmButtonText: "OK" 
                            }).then((value) => {
                                    // location.href = "<?php echo base_url()?>master_dokter";
                                    return;
                        });
                        // $('#modal_form').modal('hide');
                        reload_table();
                        
                    }
                }
                if (data.inputerror)
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

    function save_poli()
    {
    $('#btnSavepoli').text('saving...'); //change button text
    $('#btnSavepoli').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_dokter/ajax_add_poli')?>";
    } else {
        url = "<?php echo site_url('master_dokter/ajax_update_poli')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_poli').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_poli').modal('hide');
                reload_table_poli();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSavepoli').text('Simpan'); //change button text
            $('#btnSavepoli').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSavepoli').text('save'); //change button text
            $('#btnSavepoli').attr('disabled',false); //set button enable 

        }
    });
    }

    function delete_data(id)
    {
    if(confirm('Yakin data Dokter dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_dokter/ajax_delete')?>/"+id,
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

    function delete_data_poli(id)
    {
    if(confirm('Yakin data Dokter Poli dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_dokter/ajax_delete_poli')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_poli').modal('hide');
                reload_table_poli();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
    }


    function detil_dokter( kode, nama ){
        $('#id_dokter_pilih').val(kode);    
        $('#nama_dokter_pilih').val(nama);      
        table_poli.ajax.url("<?php echo base_url('master_dokter/poli_list/')?>"+kode).load();
    }

    var idrowunit   = 2;
    
    function tambah_unit(data = null){
        var formRow = document.getElementById('unit_prak1')
        var unitBody = document.getElementById('unit_row')
        unitBody.appendChild(formRow.cloneNode(true))
    }

    function hapusBaris_unit(el = null){
        var unitFormInput = document.querySelectorAll('.unit-form-input')
        if (el) {
            if (el.dataset.kodokter && el.dataset.kopoli) {
                $.ajax({
                    url: '<?= base_url('master_dokter') ?>' + '/hapus_unit',
                    data: {
                        kodokter: el.dataset.kodokter,
                        kopoli: el.dataset.kopoli
                    },
                    success: function (data) {
                        el.parentElement.parentElement.remove()
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        alert('Error deleting data');
                    } 
                })
            }else {
                if (unitFormInput.length > 1) el.parentElement.parentElement.remove()
            }

            return
        }
        if (unitFormInput.length > 1) unitFormInput[0].remove()
    }

    var idrowlokasi   = 2;
    
    function tambah_lokasi(){
        var formRow = document.getElementById('lokasi_prak1')
        var unitBody = document.getElementById('lokasi_row')
        unitBody.appendChild(formRow.cloneNode(true))
    }

    function hapusBaris_lokasi(el){
        var unitFormInput = document.querySelectorAll('.lokasi-form-input')
        if (el) {
            if (el.dataset.kodokter && el.dataset.koders) {
                $.ajax({
                    url: '<?= base_url('master_dokter') ?>' + '/hapus_lokasi',
                    data: {
                        kodokter: el.dataset.kodokter,
                        koders: el.dataset.koders
                    },
                    success: function (data) {
                        el.parentElement.parentElement.remove()
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        alert('Error deleting data');
                    } 
                })
            }else {
                if (unitFormInput.length > 1) el.parentElement.parentElement.remove()
            }
            return
        }
        if (unitFormInput.length > 1) unitFormInput[0].remove()
    }

    </script>

    <script>
    $(document).ready(function() {
        
        $('.print_laporan').on("click", function(){
        $('.modal-title').text('MASTER');
        var no_daftar= this.id;
        $("#simkeureport").html('<iframe src="<?php echo base_url();?>master_policetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
        });	
    });

    </script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Dokter</h3>
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal" method = "post">
                    <input type="hidden" value="" name="id"/> 
                    <input type="hidden" class="form-control" id="statusicd" name="statusicd"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Kode Dokter</label>
                                    <div class="col-md-5">
                                    <input name="KodeDokter" id="KodeDokter" value="AUTO" class="form-control input-small" maxlength="100" type="text" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label class="control-label col-md-5">Alamat</label>
                                    <div class="col-md-5">
                                        <input name="Alamat" id="Alamat" placeholder="Alamat" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Nama Dokter</label>
                                    <div class="col-md-5">
                                        <input name="NamaDokter" id="NamaDokter" placeholder="NamaDokter" class="form-control" maxlength="100" type="text" autofocus>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Tanggal Masuk</label>
                                    <div class="col-md-5">
                                        <input name="tglmasuk" id="tglmasuk" value = "<?php echo date('Y-m-d') ?>" placeholder="tglmasuk" class="form-control" type="date" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">NIK / Identitas</label>
                                    <div class="col-md-5">
                                        <input name="NIKIdentitas" id="NIKIdentitas" placeholder="NIK" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Status</label>
                                    <div class="col-md-5">
                                        <select name="Status" id="Status" placeholder="Status" class="form-control">
                                            <option selected>Status</option>
                                            <option value="ON">ON</option>
                                            <option value="OFF">OFF</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5  ">SIP</label>
                                    <div class="col-md-5">
                                        <input name="SIP" id="SIP" placeholder="SIP" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Tanggal Berhenti</label>
                                    <div class="col-md-5">
                                        <input name="tglberhenti" id="tglberhenti" placeholder="tglberhenti" class="form-control" type="date" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>		                                
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">NPWP</label>
                                    <div class="col-md-5">
                                        <input name="NPWP" id="NPWP" placeholder="NPWP" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>		                    
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">No HP</label>
                                    <div class="col-md-5">
                                        <input name="NoHP" id="NoHP" placeholder="NoHP" class="form-control" maxlength="100" type="number">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>	
                        </div>	
                            
                        <div class="portlet box blue">
                        <div class="portlet-title">
                            
                        <div class="caption">
                            <i class="fa fa-reorder"></i>Data
                        </div>
                    
                    </div>
                
                <div class="portlet-body form">									
                    <div class="form-body">
                    <div class="tabbable tabbable-custom tabbable-full-width" id="modal-tabs">
                    <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                        <!-- Tabs navs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="">
                                <a class="nav-link " id="unit-tab" data-toggle="tab" href="#unitpraktek" role="tab" aria-controls="home" aria-selected="true">Unit Praktek</a>
                            </li>
                            <li class="active">
                                <a class="nav-link active" id="lokasi-tab" data-toggle="tab" href="#lokasipraktek" role="tab" aria-controls="profile" aria-selected="false">Lokasi Praktek / Cabang</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane" id="unitpraktek" role="tabpanel" aria-labelledby="unit-tab">
                                <table id="datatable_diagnosa" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                                    <h4 style="color:green"><b>UNIT PRAKTEK</b></h4>

                                    <thead class="page-breadcrumb breadcrumb">
                                        <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                        <th class="title-white" width="30%" style="text-align: center">UNIT PRAKTEK </th>
                                    </thead>

                                    <tbody id="unit_row">
                                        <tr class="unit-form-input" id="unit_prak1">
                                            <td align="center" >
                                                <button type='button' onclick="hapusBaris_unit(this)" class='btn red hapus-unit'><i class='fa fa-trash-o'></i>
                                                </button>
                                            </td>
                                            <td>
                                            <select name="status_unit[]" id="status_unit1" class="form-control">
                                                <option selected>Unit Praktek</option>
                                                <?php 
                                                    $poli = $this->db->query("SELECT * from tbl_namapos")->result();
                                                    foreach($poli as $row) { ?>
                                                    <option value="<?= $row->kodepos;?>"><?= $row->namapost;?></option>
                                                    <?php } ?>
                                            </select>
                                            </td>
                                        </tr>
                                        
                                    </tbody>                
                                </table>
                                <table>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <div class="wells">
                                                        <button type="button" onclick="tambah_unit()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Unit</b> </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none;">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab-pane active" id="lokasipraktek" role="tabpanel" aria-labelledby="lokasi-tab">
                            <table id="datatable_diagnosa" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable" border="1">
                                    <h4 style="color:green"><b>LOKASI PRAKTEK</b></h4>
                                    <thead class="page-breadcrumb breadcrumb">
                                        <th class="title-white" width="5%" style="text-align: center">Delete</th>
                                        <th class="title-white" width="30%" style="text-align: center">LOKASI PRAKTEK </th>
                                    </thead>

                                    <tbody id="lokasi_row">
                                        <tr class="lokasi-form-input" id="lokasi_prak1">
                                            <td align="center" >
                                                <button type='button' onclick="hapusBaris_lokasi(this)" class='btn red hapus-lokasi'><i class='fa fa-trash-o'></i>
                                                </button>
                                            </td>
                                            <td>
                                            <select name="status_lokasi[]" id="status_lokasi1" placeholder="Status" class="form-control">
                                            <option selected>Lokasi Praktek</option>
                                                <?php 
                                                    $namers = $this->db->query("select * from tbl_namers")->result();
                                                    foreach($namers as $row) { ?>
                                                    <option value="<?= $row->koders;?>"><?= $row->namars;?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block"></span>
                                            </td>
                                        </tr>
                                    </tbody>                
                                </table>
                                <table>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <div class="wells">
                                                        <button type="button" onclick="tambah_lokasi()" class="btn green"><i class="fa fa-plus"></i> <b>Tambah Lokasi</b> </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none;">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        


                        <!-- Tabs content -->
                        
                    <div class="row">
                    <div class="col-xs-8">                                       
                </div>												
            </div>							
        </div>	
        </div>  
        </div>                    
        </div>
        </div>
            </form>
        </div>
     </div>

                    
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><b>Simpan</b></button>
                <!-- <button type="button" id="btnSave" onclick="savex()" class="btn btn-primary">Simpan</button> -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <div class="modal fade" id="modal_form_poli" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Dokter Poli</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_poli" class="form-horizontal">
                    <input type="hidden" value="" name="id_poli"/> 
                    <input type="hidden" value="" id="id_dokter_pilih"/> 
                    <input type="hidden" value="" id="nama_dokter_pilih"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input id="kode_dokter" name="kode_dokter" placeholder="Kode" class="form-control input-small" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Dokter</label>
                            <div class="col-md-9">
                                <input id="nama_dokter" name="nama_dokter" placeholder="Nama" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label class="control-label col-md-3">Poli</label>
                            <div class="col-md-9">
                                <select name="poli" class="form-control">
                                    <option value="">--- Pilih Poli ---</option>
                                    <?php 
                                    $poli = $this->db->query("select * from tbl_namapos")->result();
                                    foreach($poli as $row) { ?>
                                    <option value="<?= $row->kodepos;?>"><?= $row->namapost;?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                                        
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSavepoli" onclick="save_poli()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
