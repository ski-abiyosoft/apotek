<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;- <span class="title-web">APOTEK <small>Daftar Stok</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url(); ?>farmasi_stock">Daftar Stok</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Stok</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    Daftar Stok
                </div>
                <div class="btn-group pull-right" style="margin-bottom:20px;">
                    <label>Gudang : </label>
                    <select class="form-control datepicker input-large select2_el_farmasi_depo" id="gudang" name="gudang" onchange="getstokgudang()"></select>
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <table border="0" width="100%">
                            <tr>
                                <td width="50%">
                                <button class="btn btn-success" onclick="location.reload()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
                                <button class="btn btn-secondary" onclick="valid_all()" id="valid_a"><i class="fa fa-retweet"></i> Valid Semua</button>
                                </td>
                                <td width="23%">
                                    &nbsp;
                                </td>
                                <td width="27%">
                                    <div class="btn-group " id="drp-down">
                                        <button class="btn dropdown-toggle" data-toggle="dropdown"><b>Cetak</b> <i class="fa fa-angle-down"></i></button>
                                        <ul class="dropdown-menu pull-center">
                                            <li>
                                                <a class="btn btn-sm red print_laporan" id="cetak" href="#report" data-toggle="modal"><b>PDF</b></a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm green " onclick="exp()" id="cetak" href="" data-toggle="modal"><b>Excel</b></a>
                                            </li>
                                            <!-- <li>
                                                <a onclick="exp()"> Export</a> -->
                                            <!-- <a href="<?php echo base_url() ?>master_poliexport"> Export</a> -->
                                            <!-- </li> -->
                                        </ul>
                                    </div>
                                </td>
                                
                            </tr>
                        </table>
                    
                </div>


                <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                    <thead class="breadcrumb header-custom">
                        <tr>
                            <th class="title-white" style="text-align: center">Cabang</th>
                            <th class="title-white" style="text-align: center">Kode Barang</th>
                            <th class="title-white" style="text-align: center">Nama Barang</th>
                            <!-- <th class="title-white" style="text-align: center">Saldo Awal</th>
                            <th class="title-white" style="text-align: center">Sesuai</th>
                            <th class="title-white" style="text-align: center">Terima</th>
                            <th class="title-white" style="text-align: center">Keluar</th>
                            <th class="title-white" style="text-align: center">Hasil SO</th> -->
                            <th class="title-white" style="text-align: center">Satuan</th>
                            <th class="title-white" style="text-align: center">Saldo Akhir</th>
                            <th class="title-white" style="text-align: center">Gudang</th>
                            <th width="10%" class="title-white" style="text-align: center">Aksi</th>
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

<div class="modal fade" id="loading" data-toggle="modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog vertical-align-center2" style="margin-top: 350px;">
        <table align="center">
            <tr>
                <td>Loading...</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr align="center">
                <td>
                    <img id="search1" height="50px" width="50px" src="<?php echo base_url(); ?>assets/img/loadinghar2.gif" />
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
$this->load->view('template/footer_tb');
$this->load->view('template/v_report');
?>


<script type="text/javascript">
    function exp() {
        var gudang    = $('#gudang').val();
        var baseurl   = "<?php echo base_url() ?>";
        url           = baseurl + 'Farmasi_stock/cetak/' + gudang+'/2';
        window.open(url, '');
    }

    $('#drp-down').hide();
    var save_method; //for save method string
    var table;

    var cabang = "<?= $cabang; ?>";
    $(document).ready(function() {
        $("#valid_a").hide();

        $('.print_laporan').on("click", function() {
            var gudang = document.getElementById('gudang').value;
            $('.modal-title').text('CETAK LAPORAN STOK');
            $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>Farmasi_stock/cetak/' + gudang +'/1" frameborder="no" width="100%" height="520"></iframe>');
        });

        //datatables
        table = $('#table').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('farmasi_stock/ajax_list/?cabang=') ?>" + cabang + '&gudang=""',
                "type": "POST"
            },

            "oLanguage": {
                "sEmptyTable": "Tidak ada data",
                "sInfoEmpty": "Tidak ada data",
                "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
                "sSearch": "Pencarian Data : ",
                "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu": "_MENU_ Baris",
                "sZeroRecords": "<div class='text-center'>Tidak Ada Data</div>",
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
                },
                {
                    "orderable": true, //set not orderable			  
                    "className": "text-right",
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

    function show(id) {
        var baseurl = "<?php echo base_url() ?>";
        var param = '?id=' + id;
        hasil = baseurl + 'farmasi_stock/show/' + param;
        window.open(hasil, '_blank');
    }

    function add_bank() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    }


    function edit_data(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('master_tarif_cabang/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="cabang"]').val(data.koders);
                $('[name="poli"]').val(data.kodepos);
                $('[name="kode"]').val(data.kodetarif);
                $('[name="nama"]').val(data.tindakan);
                $('[name="klinik"]').val(data.tarifrspoli);
                $('[name="dokter"]').val(data.tarifdrpoli);
                $('[name="bhp"]').val(data.obatpoli);
                $('[name="perawat"]').val(data.feemedispoli);
                //$('[name="dob"]').datepicker('update',data.dob);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function save() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        var url;

        if (save_method == 'add') {
            url = "<?php echo site_url('master_tarif_cabang/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('master_tarif_cabang/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data) {

                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
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


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 

            }
        });
    }

    function delete_data(id) {
        if (confirm('Yakin data Bank dengan kode ' + id + ' ini akan dihapus ?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('master_tarif/ajax_delete') ?>/" + id,
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

    function getstokgudang() {
        $('#drp-down').show('200');
        var gudang = $('#gudang').val();
        var cabang = "<?= $cabang; ?>";
        table.ajax.url("<?php echo base_url('farmasi_stock/ajax_list/?cabang=') ?>" + cabang + '&gudang=' + gudang).load();
        $("#valid_a").show(200);
    }

    function valid(id) {
        $.ajax({
            url: "<?= site_url('Farmasi_stock/valid/') ?>" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 1) {
                    swal({
                        title: "VALID DATA",
                        html: "Berhasil dilakukan",
                        type: "success",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        location.href = "<?= base_url() ?>farmasi_stock";
                    });
                } else {
                    swal({
                        title: "VALID DATA",
                        html: "Gagal dilakukan",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                }
            }
        });
    }

    function valid_all() {
        var gudang = $('#gudang').val();
        swal({
            title: 'VALID SEMUA',
            html: "Yakin Ingin Memvalidkan gudang ini ?",
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-success',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function() {
            $('#loading').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            $.ajax({
                url: "<?= site_url('Farmasi_stock/valid_all/') ?>" + gudang,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        
                        swal({
                            title: "VALID DATA",
                            html: "Berhasil dilakukan",
                            type: "success",
                            confirmButtonText: "OK"
                        }).then((value) => {
                            location.href = "<?= base_url() ?>farmasi_stock";
                        });
                        $('#loading').modal('hide');
                    } else {
                        
                        swal({
                            title: "VALID DATA",
                            html: "Gagal dilakukan",
                            type: "error",
                            confirmButtonText: "OK"
                        });
                        $('#loading').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: false
                        });
                    }
                }
            });
        });
    }
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Tarif</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Cabang</label>
                            <div class="col-md-9">
                                <input name="cabang" placeholder="Kode" class="form-control input-small" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" placeholder="Kode" class="form-control input-small" maxlength="5" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Nama" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Poli</label>
                            <div class="col-md-9">
                                <input name="poli" placeholder="" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jasa Klinik</label>
                            <div class="col-md-9">
                                <input name="klinik" placeholder="" class="form-control rightJustified" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jasa Dokter</label>
                            <div class="col-md-9">
                                <input name="dokter" placeholder="" class="form-control rightJustified" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">BHP</label>
                            <div class="col-md-9">
                                <input name="bhp" placeholder="" class="form-control rightJustified" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jasa Perawat</label>
                            <div class="col-md-9">
                                <input name="perawat" placeholder="" class="form-control rightJustified" type="number">
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
        </div>
    </div>
</div>