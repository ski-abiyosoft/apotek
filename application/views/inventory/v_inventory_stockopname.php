<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

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

    .modal-footer a {
        color: white;
        text-decoration: none;


    }

    div.tableContainer table {
        float: left
    }

    thead.fixedHeader tr {
        position: relative
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

    tbody.scrollContent td,
    tbody.scrollContent tr.normalRow td {
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
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Farmasi <small>Stock Opname</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Inventory</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Stock Opname</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title" style="padding-bottom:5px">
                <div class="caption">
                    Daftar Stock Opname
                </div>
                <div class="btn-group pull-right">
                    <!-- <label>Gudang  : </label>
                        <select class="form-control input-large select2_el_farmasi_depo" id="gudang" name="gudang" onchange="getsogudang()"></select> -->
                    <div class="row" style="margin-bottom: 30px;">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Gudang</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control input-large" id="gudang" name="gudang" onchange="getsogudang(); getstokgudang();" style="margin-top:-7px;">
                                        <option value="">-- Pilih --</option>
                                        <?php $sql = $this->db->query('SELECT depocode as id, keterangan as text from tbl_depo')->result(); ?>
                                        <?php foreach ($sql as $s) : ?>
                                            <option value="<?= $s->id; ?>"><?= $s->text; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Saldo akhir</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control input-large" id="tampilsaldo" name="tampilsaldo" onchange="getsaldoakhir();" style="margin-top:-7px;">
                                        <option value="0">Tampilkan Saldoakhir 0</option>
                                        <option value="1">Tidak Ditampilkan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-toolbar">
                    <table border='0' width="100%">
                        <tr>
                            <td width="50%">
                            <div class="btn-group">
                                <!-- <a href="<?php echo base_url() ?>inventory_tso/entri" class="btn btn-success"><i
                                        class="fa fa-plus"></i> Data Baru</a> -->
                            
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#SO"><i class="fa fa-plus"></i> <b>Data Baru</b></button>
                                <?php if ($this->session->userdata('user_level') >= 2) : ?>
                                    <button type="button" class="btn btn-info" style="margin-left: 5px;" onclick="approveall()"><i class="fa fa-check"></i><b> Approve Semua</b></button>
                                <?php endif; ?>
                                <!-- </a> -->
                            </div>
                            </td>
                            <td width="30%">
                            <div class="btn-group pull-right" id="drp-down">
                                <button class="btn dropdown-toggle" data-toggle="dropdown"><b>Cetak </b><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a class="btn btn-sm red print_laporan" id="cetak" href="#report" data-toggle="modal"><i class="fa fa-print"></i><b> PDF</b></a>
                                    </li>
                                </ul>
                            </div>
                            </td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                    </table>
                    
                    
                </div>

                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="title-white" style="text-align: center;">Cabang</th>
                            <th class="title-white" style="text-align: center">Username</th>
                            <th class="title-white" style="text-align: center;">Nama Gudang</th>
                            <th class="title-white" style="text-align: center;">Tanggal</th>
                            <th class="title-white" style="text-align: center;">Kode Barang</th>
                            <th class="title-white" style="text-align: center;">Nama Barang</th>
                            <th class="title-white" style="text-align: center">Satuan</th>
                            <th class="title-white" style="text-align: center">Qty</th>
                            <th class="title-white" style="text-align: center">Qty SO</th>
                            <th class="title-white" style="text-align: center">Status</th>
                            <th class="title-white" style="text-align: center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="SO" class="modal fade" role="dialog">
    <div class="modal-dialog modal-small">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><b>Masukan Password</b></h4>
            </div>
            <!-- body modal -->
            <form action="<?= site_url('Inventory_tso/validate_1'); ?>" method="POST">
                <div class="modal-body">
                    <!-- <label for="username">Username: </label>
                    <input type="username" name="username" id="username" required><br> -->
                    <label for="password">Passsword:</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <!-- footer modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" type="submit">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="SOX" class="modal fade" role="dialog">
    <div class="modal-dialog modal-small">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><b>Masukan Password</b></h4>
            </div>
            <!-- body modal -->
            <form method="POST" id="cekpass">
                <div class="modal-body">
                    <!-- <label for="username">Username: </label>
                    <input type="username" name="username" id="username" required><br> -->
                    <input type="hidden" name="id_edit" class="form-control" id="id_edit">
                    <label for="password">Passsword:</label>
                    <input type="password" name="password_edit" class="form-control" id="password_edit" required>
                </div>
                <!-- footer modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="ceksukses()" class="btn btn-primary">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php
$this->load->view('template/footer_tb');
$this->load->view('template/v_report');
?>

<!-- <?php if ($this->session->flashdata('ntf0')) { ?>
<script type="text/javascript">
$.gritter.add({
    title: '<b><?php echo 'Informasi Login' ?></b>',
    text: '<?php echo $this->session->flashdata('ntf0'); ?>',
    image: '<?php echo base_url('assets/img/logoi.png'); ?>',
    class_name: 'color-danger'
});
</script>
<?php } ?>
<?php if ($this->session->flashdata('ntf1')) { ?>
<script type="text/javascript">
$.gritter.add({
    title: '<b><?php echo 'Informasi Login' ?></b>',
    text: '<?php echo $this->session->flashdata('ntf1'); ?>',
    image: '<?php echo base_url('assets/img/logoi.png'); ?>',
    class_name: 'color-success'
});
</script>
<?php } ?> -->


<script type="text/javascript">
    $('#drp-down').hide();

    $(".select2_depo").select2({
        allowClear: true,
        multiple: false,
        placeholder: '--- Cari ---',
        // minimumInputLength: 1,
        // dropdownAutoWidth : true,
        // language: {
        // 	inputTooShort: function() {
        // 		return 'Ketikan Kode/Nama minimal 1 huruf';
        // 	}
        // },  
        // ajax: {
        // 	url: "<?php echo base_url(); ?>Inventory_tso/gudang",
        // 	type: "post",
        // 	dataType: 'json',
        // 	delay: 250,
        // 	data: function (params) {
        //   		return {
        //     		searchTerm: params.term
        //   		};
        // 	},
        // 	processResults: function (response) {
        //   		return {
        //      		results: response
        //   		};
        // 	},
        // 	cache: true
        // }
    });

    var save_method; //for save method string
    var table;

    var cabang = "<?= $cabang; ?>";

    $(document).ready(function() {
        $('.print_laporan').on("click", function() {
            var gudang = document.getElementById('gudang').value;
            $('.modal-title').text('CETAK LAPORAN STOK');
            $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>inventory_tso/cetak2/' + gudang +
                '" frameborder="no" width="100%" height="520"></iframe>');
        });

        //datatables
        table = $('#table').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('inventory_tso/ajax_list') ?>",
                "type": "POST"
            },

            "oLanguage": {
                "sEmptyTable": "Tidak ada data",
                "sInfoEmpty": "Tidak ada data",
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
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],

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

    function approveall() {
        swal({
            title: 'SETUJUI',
            html: "Setujui Semua Barang ? <strong><p>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Ya, Setuju',
            cancelButtonText: 'Batal'
        }).then(function() {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo base_url('Inventory_tso') ?>/approveall',
                success: function(response) {
                    if (response.status == '1') {
                        swal(
                            'SETUJUI!',
                            'Data berhasil disetujui.',
                            'success'
                        )
                    } else {
                        swal(
                            'SETUJUI!',
                            'Data gagal disetujui.',
                            'error'
                        )
                    }
                    reload_table();
                }
            });
        });
    }

    function approve(id, kode) {
        swal({
            title: 'SETUJUI',
            html: "Setujui Kode Barang : " + kode + " ? <strong><p>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Ya, Setuju',
            cancelButtonText: 'Batal'
        }).then(function() {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo base_url('Inventory_tso') ?>/approve/' + id,
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.status == '1') {
                        swal(
                            'SETUJUI!',
                            'Data berhasil disetujui.',
                            'success'
                        )
                    } else {
                        swal(
                            'SETUJUI!',
                            'Data gagal disetujui.',
                            'error'
                        )
                    }
                    reload_table();
                }
            });
        });
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function add_data() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    }

    function edit_data(id) {
        $('#SOX').modal('show');
        $('.modal-title').text('Ubah Data');
        $('#id_edit').val(id);
    }

    function ceksukses() {
        var id = $('#id_edit').val();
        var password = $('#password_edit').val();
        $.ajax({
            url: "<?= site_url('Inventory_tso/cek_password/?pass='); ?>" + password,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // console.log(data)
                if (data.status == 1) {
                    location.href = "<?php echo base_url('Inventory_tso/edit_data/') ?>" + id;
                } else {
                    location.href = "<?php echo base_url('Inventory_tso') ?>";
                }
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

        url = "<?php echo site_url('inventory_tso/save/1') ?>";
        /*
        if(save_method == 'add') {
            url = "<?php echo site_url('inventory_tso/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('inventory_tso/ajax_update') ?>";
        }
        */

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
        if (confirm('Yakin data barang dengan kode ' + id + ' ini akan dihapus ?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('inventory_tso/ajax_delete') ?>/" + id,
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

    // function getsogudang(){
    // 	var gudang = $('#gudang').val();	
    // 	var cabang = "<?= $cabang; ?>";	
    // 	table.ajax.url("<?php echo base_url('inventory_tso/ajax_list/?cabang=') ?>"+cabang+'&gudang='+gudang).load();
    // }
    function getsogudang() {
        $('#drp-down').show('200');
        var gudang = $('#gudang').val();
        table.ajax.url("<?php echo base_url('inventory_tso/ajax_list/?gudang=') ?>" + gudang).load();
    }

    function getsaldoakhir() {
        $('#drp-down').show('200');
        var tampilsaldo = $('#tampilsaldo').val();
        table.ajax.url("<?php echo base_url('inventory_tso/ajax_list/?tampilsaldo=') ?>" + tampilsaldo).load();
        // console.log(tampilsaldo)
    }
</script>

<script>
    jQuery(document).ready(function() {
        // ComponentsPickers.init();
    });
</script>