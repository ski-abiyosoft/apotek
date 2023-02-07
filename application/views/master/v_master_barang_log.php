<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<style>
    .select2-container {
        z-index: 99999
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Master <small>Data Barang Logistik</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home title-white"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a class="title-white" href="#">Master</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a class="title-white" href="#">Data Barang</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    Daftar Barang Logistik
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">


                    </div>
                    <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data
                        Baru</button>
                    <div class="btn-group pull-right">
                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url() ?>master_poliexport">
                                    Export
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center;color:#fff">Kode</th>
                            <th style="text-align: center;color:#fff">Nama Barang</th>
                            <th style="text-align: center;color:#fff">Satuan</th>
                            <th style="text-align: center;width:12%;color:#fff">Aksi</th>

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
                "url": "<?php echo site_url('master_barang_log/ajax_list') ?>",
                "type": "POST"
            },

            "oLanguage": {
                "sEmptyTable": "Tidak ada data",
                "sInfoEmpty": "Tidak ada data",
                "sInfoFiltered": " - Dipilih dari _MAX_ data",
                "sSearch": "Pencarian Data:",
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



    function add_data() {
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
            url: "<?php echo site_url('master_barang_log/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                $('[name="id"]').val(data.id);
                $('[name="kode"]').val(data.kodebarang);
                $('[name="nama"]').val(data.namabarang);
                $('#inventorycat option[value="' + data.icgroup + '"]').prop('selected', true);
                $('[name="satuan"]').val(data.satuan1);
                $('[name="satuan2"]').val(data.satuan2);
                $('[name="faksatuan2"]').val(data.faktorsat2);
                $('[name="qtysatuan2"]').val(data.satuan2qty);
                $('[name="ppn"]').val(data.notax);
                $('[name="jenis"]').val(data.jenis);
                $('[name="hpp"]').val(Number(data.hpp));
                $('[name="satuan3"]').val(data.satuan3);
                $('[name="faksatuan3"]').val(data.faktorsat3);
                $('[name="qtysatuan3"]').val(data.satuan3qty);
                $('[name="hargajual"]').val(Number(data.hargajual));
                $('[name="hargabeli"]').val(Number(data.hargabeli));
                $('[name="hargabelippn"]').val(Number(data.hargabelippn));
                $('[name="jenisharga"]').val(data.jenisharga);
                $('[name="kemasan"]').val(data.kemasan);
                $('[name="minstock"]').val(data.minstock);
                $('[name="maxstock"]').val(data.maxstock);
                $('[name="subjenis"]').val(data.subjenis);
                $('[name="het"]').val(Number(data.het));
                if(data.jenisharga == 1) {
                    var cek1 = ((Number(data.hargajual) - Number(data.hargabelippn)) / Number(data.hargabelippn)) * 100;
                    $('#cek_persen').show();
                } else {
                    $('#cek_persen').hide();
                    var cek1 = 0;
                }
                $('[name="persen"]').val(cek1.toFixed(0));

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
            url = "<?php echo site_url('master_barang_log/ajax_add') ?>";
            judul = "TAMBAH BARANG";
        } else {
            url = "<?php echo site_url('master_barang_log/ajax_update') ?>";
            judul = "UBAH BARANG";
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
                    swal({
                        title: judul,
                        html: "Berhasil dilakukan !",
                        type: "success",
                        confirmButtonText: "OK"
                    }).then((value) => {
                        reload_table();
                    });
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
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
        if (confirm('Yakin data Barang dengan kode ' + id + ' ini akan dihapus ?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('master_barang_log/ajax_delete') ?>/" + id,
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
    $(document).ready(function() {

        $('.print_laporan').on("click", function() {
            $('.modal-title').text('MASTER');
            var no_daftar = this.id;
            $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>master_policetak/' + no_daftar +
                '" frameborder="no" width="100%" height="420"></iframe>');
        });
    });
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog" style="overflow:hidden;">
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Barang</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Kode&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="kode" id="kode" placeholder="Kode" class="form-control" maxlength="20" type="text" onkeyup="ubah_kode(this.value)">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Barang&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="nama" placeholder="Uraian" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-3">Inventory Cat&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <!-- <input name="inventorycat" placeholder="" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span> -->
                                        <select class="form-control" id="inventorycat" name="inventorycat">
                                            <option value="">--- Pilih ---</option>
                                            <?php $jenis = $this->db->query("SELECT*from tbl_setinghms where lset='GRID' AND KODESET<>'CSSD'")->result();
                                            foreach ($jenis as $row) {
                                            ?>
                                                <option value="<?= $row->kodeset; ?>"><?= $row->keterangan; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <!-- <input name="satuan" placeholder="" class="form-control" maxlength="100" type="text"> -->
                                        
                                        <select name="satuan" id="satuan" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                            <?php 
                                        $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup='SATUAN' AND apocode<>''")->result();
                                        foreach($data as $row){ ?>
                                        <option value="<?= $row->apocode;?>"><?= $row->aponame.' - [ '.$row->apocode.' ] ';?></option>
                                        <?php } ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan2&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <!-- <input name="satuan2" placeholder="" class="form-control" maxlength="100" type="text"> -->
                                        
                                        <select name="satuan2" id="satuan2" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                            <?php 
                                        $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup='SATUAN' AND apocode<>''")->result();
                                        foreach($data as $row){ ?>
                                        <option value="<?= $row->apocode;?>"><?= $row->aponame.' - [ '.$row->apocode.' ] ';?></option>
                                        <?php } ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <select type="text" class="form-control" name="faksatuan2" id="satuan3opr">
                                            <option value="">--- Pilih ---</option>
                                            <option value="1">x Kali</option>
                                            <option value="2">: Bagi</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="qtysatuan2" placeholder="" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan3&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <!-- <input name="satuan3" placeholder="" class="form-control" maxlength="100" type="text"> -->
                                        <select name="satuan3" id="satuan3" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                        <?php 
                                        $data = $this->db->query("SELECT * from tbl_barangsetup where apogroup='SATUAN' AND apocode<>''")->result();
                                        foreach($data as $row){ ?>
                                        <option value="<?= $row->apocode;?>"><?= $row->aponame.' - [ '.$row->apocode.' ] ';?></option>
                                        <?php } ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <select type="text" class="form-control" name="faksatuan3" id="satuan3opr">
                                            <option value="">--- Pilih ---</option>
                                            <option value="1">x Kali</option>
                                            <option value="2">: Bagi</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="qtysatuan3" placeholder="" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Kemasan&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="kemasan" id="kemasan" placeholder="" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Harga Beli&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <input name="hargabeli" id="hargabeli" placeholder="" class="form-control text-right" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-3">Dikenakan PPN&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <select name="ppn" id="ppn" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Beli+PPN&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="hargabelippn" id="hargabelippn" placeholder="" class="form-control text-right" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">HPP&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="hpp" id="hpp" placeholder="" class="form-control text-right" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">HET&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input id="het" name="het" placeholder="" class="form-control text-right" maxlength="100" type="number">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">


                                <div class="form-group">
                                    <label class="control-label col-md-3">Minimum Stock&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="minstock" placeholder="" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Maksimum Stock&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="maxstock" placeholder="" class="form-control" maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis</label>
                                    <div class="col-md-9">
                                        <input name="jenis" placeholder="" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Sub Jenis</label>
                                    <div class="col-md-9">
                                        <input name="subjenis" id="subjenis" placeholder="" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Harga&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-6">
                                        <select name="jenisharga" class="form-control" onchange="cek(this.value)">
                                            <option value="1">Prosentase</option>
                                            <option value="2" selected>Manual</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" id="cek_persen">
                                        <input type="text" name="persen" id="persen" class="form-control text-right" placeholder="%" onkeyup="dapetin(this.value)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Harga Jual&nbsp;<small style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="hargajual" id="hargajual" placeholder="" class="form-control text-right" maxlength="100" type="text">
                                        <span class="help-block"></span>
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

<script>
    //   initailizeSelect2_vendor();
    //initailizeSelect2_kasbank();

    $("#hargabeli").on("keyup focus click", function() {
        var hna = $(this).val();
        var ppn = $("#ppn").val();
        var ppnpersen = <?= str_replace(".00", "", $ppn->prosentase) ?> / 100;

        if (ppn == 1) {
            var hnappn = eval(hna) + (eval(hna) * ppnpersen);
            $("#hargabelippn").val(hnappn);
        } else {
            $("#hargabelippn").val(hna);
        }
    });

    $("#ppn").on("change", function() {
        var hna = $("#hargabeli").val();
        var ppn = $("#ppn").val();
        var ppnpersen = <?= str_replace(".00", "", $ppn->prosentase) ?> / 100;

        if (ppn == 1) {
            var hnappn = eval(hna) + (eval(hna) * ppnpersen);
            $("#hargabelippn").val(hnappn);
        } else {
            $("#hargabelippn").val(hna);
        }
    });
</script>

<script>
    $("#cek_persen").hide();
    
    function cek(param) {
        if(param == 1) {
            $("#cek_persen").show();
        } else {
            $("#cek_persen").hide();
        }
    }

    function dapetin(param) {
        var hnappn = Number(parseInt(($("#hargabelippn").val()).replaceAll(',','')));
        var het = Number(parseInt(($("#het").val()).replaceAll(',','')));
        var beli = Number(parseInt(($("#hargabelippn").val()).replaceAll(',','')));
        hj = (beli * (param / 100)) + beli;
        hnappn2 = het - beli;
        hna2 = hnappn2/beli * 100;
        if(param > hna2) {
            swal({
                title: "PERSENTASE",
                html: "Maksimal "+hna2.toFixed(0)+"%",
                type: "error",
                confirmButtonText: "OK"
            });
            $("#persen").val(0)
            $("#hargajual").val(0);
        } else {
            $("#hargajual").val(hj.toFixed(0));
        }
    }

    function ubah_kode(param) {
        let text_space = param.replaceAll(' ', '');
        let text = text_space.toUpperCase();
        $("#kode").val(text);
    }
</script>