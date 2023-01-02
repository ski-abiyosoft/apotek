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
            </div>

            <div class="portlet-body">
                <div class="table-toolbar">
                    <table border='0' width="100%">
                        <tr>
                            <td width="50%">
                            <div class="btn-group">
                                
                                
                                <!-- <?php if ($this->session->userdata('user_level') >= 2) : ?>
                                    <button type="button" class="btn btn-info" style="margin-left: 5px;" onclick="approveall()"><i class="fa fa-lock"></i><b> Kunci Semua</b></button>
                                <?php endif; ?> -->
                                <!-- </a> -->
                            </div>
                            </td>
                            <td width="30%">
                            </td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                    </table>
                    
                    
                </div>

                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="page-breadcrumb breadcrumb">
                            <th class="title-white" style="text-align: center;">Cabang</th>
                            <th class="title-white" style="text-align: center">Nama Cabang</th>
                            <th class="title-white" style="text-align: center;">Tanggal</th>
                            <th class="title-white" style="text-align: center;">Jam Mulai</th>
                            <th class="title-white" style="text-align: center;">Jam Selesai</th>
                            <th class="title-white" style="text-align: center;">Username</th>
                            <th class="title-white" style="text-align: center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header header-custom">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title"><b>Data Cabang</b></h3>
        </div>
        <div class="modal-body form">
            <form action="#" id="form_data" class="form-horizontal"  method="post"  enctype="multipart/form-data">
            <input type="hidden" value="" name="id"/> 
            <div class="form-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Kode</label>
                            <div class="col-md-8">
                                <input name="kode" placeholder="Kode" class="form-control" maxlength="3" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Nama Cabang</label>
                            <div class="col-md-8">
                                <input name="nama" placeholder="Nama Cabang" class="form-control" maxlength="100" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Tanggal</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="tgl_so" id="tgl_so">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jam Mulai</label>
                            <div class="col-md-8">
                                <input type="time" class="form-control" name="jamm" id="jamm">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Jam Selesai</label>
                            <div class="col-md-8">
                                <input type="time" class="form-control" name="jams" id="jams">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label col-md-4">Status</label>
                            <div class="col-md-8">
                                <select name="status" id="status" class="form-control">
                                    <option value="1">LOCK</option>
                                    <option value="0">OPEN</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>

                
                    
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><b>Simpan</b></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Batal</b></button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<?php
$this->load->view('template/footer_tb');
$this->load->view('template/v_report');
?>



<script type="text/javascript">
    $('#drp-down').hide();


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
                "url": "<?php echo site_url('lock_so/ajax_list') ?>",
                "type": "POST"
            },

            "oLanguage": {
                "sEmptyTable"   : "Tidak ada data",
                "sInfoEmpty"    : "Tidak ada data",
                "sInfoFiltered" : " - Dipilih dari _TOTAL_ data",
                "sSearch"       : "Pencarian Data : ",
                "sInfo"         : " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu"   : "_MENU_ Baris",
                "sZeroRecords"  : "Tida ada data",
                "oPaginate": {
                    "sPrevious"   : "Sebelumnya",
                    "sNext"       : "Berikutnya"
                }
            },

            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [-1], //last column
                // "orderable": false, //set not orderable
            }, ],

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

        $.ajax({
            url : "<?php echo site_url('lock_so/ajax_edit/')?>" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="kode"]').val(data.koders);
                $('[name="nama"]').val(data.nm_rs);
                $('[name="tgl_so"]').val(data.statustgl);
                $('[name="jamm"]').val(data.jamm);
                $('[name="jams"]').val(data.jams);
                $('#status option[value="' + data.status + '"]').prop('selected', true);
            
            
                
                        
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Data Jadwal'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
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

        url = "<?php echo site_url('lock_so/save') ?>";

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_data').serialize(),
            dataType: "JSON",
            success: function(data) {

                if (data.status==1) //if success close modal and reload ajax table
                {
                    swal({
                        title: "JADWAL SO",
                        html: 
                            "<p> Kode   : <b>"+data.koders+"</b> </p>"+ 
                            "<br>Berhasil di Simpan...",
                        type: "success",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                            $('#modal_form').modal('hide');
                            reload_table();
                    });	
                    $('#modal_form').modal('hide');
                    reload_table();
                } else { 
                    swal({
                        title: "JADWAL SO",
                        html: 
                            "<p> Kode   : <b>"+data.koders+"</b> </p>"+ 
                            "<br>Gagal di Simpan...",
                        type: "error",
                        confirmButtonText: "OK" 
                        }).then((value) => {
                            $('#modal_form').modal('hide');
                            reload_table();
                    });	
                    $('#modal_form').modal('hide');
                    reload_table();
                }
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                // swal({
                //         title: "JADWAL SO ",
                //         html: 
                //             "<p> Kode   : <b>"+data.koders+"</b> </p>"+ 
                //             "<br>Gagal di Simpan...",
                //         type: "error",
                //         confirmButtonText: "OK" 
                // }).then((value) => {
                // });	
                //     return;

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

</script>

<script>
    jQuery(document).ready(function() {
        // ComponentsPickers.init();
    });
</script>