<?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<style>
.select2-container {
    z-index: 99999;
}
</style>



<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Master <small>Data Barang Farmasi</small>
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
                    Data Barang
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
                    Daftar Barang Farmasi
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">


                    </div>
                    <button class="btn btn-success" onclick="add_bank()"><i class="glyphicon glyphicon-plus"></i> Data
                        Baru</button>
                    <div class="btn-group pull-right">
                        <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url()?>master_poliexport">
                                    Export
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center">Kode</th>
                            <th style="text-align: center">Nama Barang</th>
                            <th style="text-align: center">Satuan</th>
                            <th style="text-align: center">Harga Beli</th>
                            <th style="text-align: center">Harga Jual</th>
                            <th style="text-align: center;width:12%;">Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
            "url": "<?php echo site_url('master_barang/ajax_list')?>",
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
            },
            {
                "targets": [3, 4], //last column
                "className": "text-right",
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
        url: "/master_barang/ajax_edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="kode"]').val(data.kodebarang);
            $('[name="nama"]').val(data.namabarang);
            $('[name="namageneric"]').val(data.namageneric);
            $('#inventorycat option[value="' + data.icgroup + '"]').prop('selected', true);
            $('[name="satuan"]').val(data.satuan1);
            $('[name="satuan2"]').val(data.satuan2);
            $('[name="satuan3"]').val(data.satuan3);
            $('[name="qtysatuan2"]').val(data.satuan2qty);
            $('[name="qtysatuan3"]').val(data.satuan3qty);
            $('#satuan2opr option[value="' + data.satuan2opr + '"]').prop('selected', true);
            $('#satuan3opr option[value="' + data.satuan3opr + '"]').prop('selected', true);
            $('#vendor option[value="' + data.vendor_id + '"]').prop('selected', true);

            $('#pabrik option[value="' + data.pabrik + '"]').prop('selected', true);
            $('#golongan option[value="' + data.golongan + '"]').prop('selected', true);
            $('#kelasterapi option[value="' + data.kelasteraphi + '"]').prop('selected', true);
            $('#jenis option[value="' + data.jenisobat + '"]').prop('selected', true);

            $('[name="hargajual"]').val(data.hargajual);
            $('[name="hna"]').val(data.hargabeli);
            $('[name="hnappn"]').val(data.hargabelippn);
            $('[name="ppn"]').val(data.vat);
            $('[name="nilaipersediaan"]').val(data.hpp.split(".00").join(""));
            $('[name="jenisharga"]').val(data.hargatype);
            $('[name="kemasan"]').val(data.kemasan);
            $('[name="minstock"]').val(data.minstock);
            $('[name="leadtime"]').val(data.leadtime);
            $('[name="reorderlevel"]').val(data.reorder);

            gettarif();

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
    console.log($('#form').serialize());
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable 
    var namabrg = $('[name="nama"]').val();
    var url;

    if (save_method == 'add') {
        url = "<?php echo site_url('master_barang/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_barang/ajax_update')?>";
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
                swal({
                    title: "DATA MASTER BARANG",
                    html: 
                        "<p> Nama   : <b>"+namabrg+"</b> </p>"+ 
                        "<br>Berhasil di Simpan...",
                    type: "success",
                    confirmButtonText: "OK" 
                    }).then((value) => {
                        $('#modal_form').modal('hide');
                        reload_table();
                });	
                
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
            alert(textStatus);
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 

        }
    });
}

function delete_data(id) {
    if (confirm('Yakin data Barang dengan kode ' + id + ' ini akan dihapus ?')) {
        // ajax delete data to database
        $.ajax({
            url: "<?php echo site_url('master_barang/ajax_delete')?>/" + id,
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
        $("#simkeureport").html('<iframe src="<?php echo base_url();?>master_policetak/' + no_daftar +
            '" frameborder="no" width="100%" height="420"></iframe>');
    });
});
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog" style="overflow:hidden;">
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Barang</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Kode&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="kode" placeholder="Kode" class="form-control" maxlength="20"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Barang&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="nama" placeholder="Uraian" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Generic&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="namageneric" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Inventory Cat&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <!-- <input name="inventorycat" placeholder="" class="form-control" maxlength="100" type="text">

                                <span class="help-block"></span> -->

                                        <select class="form-control" id="inventorycat" name="inventorycat">
                                            <option value="">--- Pilih ---</option>
                                            <?php $jenis = $this->db->query("SELECT*from tbl_setinghms where lset='GRID' AND KODESET<>'CSSD'")->result();
                                    foreach($jenis as $row){
                                    ?>
                                            <option value="<?= $row->kodeset;?>"><?= $row->keterangan;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="satuan" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan2</label>
                                    <div class="col-md-3">
                                        <input name="satuan2" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <select type="text" class="form-control" name="satuan2opr" id="satuan2opr">
                                            <option value="1">x Kali</option>
                                            <option value="2">: Bagi</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="qtysatuan2" value="0" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Satuan3</label>
                                    <div class="col-md-3">
                                        <input name="satuan3" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <select type="text" class="form-control" name="satuan3opr" id="satuan3opr">
                                            <option value="1">x Kali</option>
                                            <option value="2">: Bagi</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="qtysatuan3" value="0" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Kemasan&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="kemasan" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">HNA&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <input name="hna" id="hna" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-3">Dikenakan PPN&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-3">
                                        <select name="ppn" class="form-control" id="ppn">
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">HNA+PPN&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input id="hnappn" name="hnappn" placeholder="" class="form-control"
                                            maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Harga&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="jenisharga" class="form-control">
                                            <option value="1">Prosentase</option>
                                            <option value="2" selected>Manual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Harga Jual&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="hargajual" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Jual Jaminan</label>
                                    <div class="col-md-9">
                                        <input name="jualjaminan" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Nilai Persediaan Satuan&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="nilaipersediaan" placeholder="" class="form-control"
                                            maxlength="100" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Status Barang&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="status" class="form-control">
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>


                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Set</label>
                                    <div class="col-md-9">
                                        <select name="set" class="form-control">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Kelas Terapi&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="kelasterapi" id="kelasterapi" class="form-control">
                                            <?php 
								$data = $this->db->query("select * from tbl_barangsetup where apogroup='KELAS_TERAPI'")->result();
								foreach($data as $row){ ?>
                                            <option value="<?= $row->apocode;?>"><?= $row->aponame;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Golongan&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="golongan" id="golongan" class="form-control">
                                            <?php 
								$data = $this->db->query("select * from tbl_barangsetup where apogroup='GOLONGAN_OBAT'")->result();
								foreach($data as $row){ ?>
                                            <option value="<?= $row->apocode;?>"><?= $row->aponame;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Pabrik&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="pabrik" id="pabrik" class="form-control">
                                            <?php 
								$data = $this->db->query("select * from tbl_barangsetup where apogroup='PABRIK_OBAT'")->result();
								foreach($data as $row){ ?>
                                            <option value="<?= $row->apocode;?>"><?= $row->aponame;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select name="jenis" id="jenis" class="form-control">
                                            <?php 
								$data = $this->db->query("select * from tbl_barangsetup where apogroup='JENIS_OBAT'")->result();
								foreach($data as $row){ ?>
                                            <option value="<?= $row->apocode;?>"><?= $row->aponame;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Main Vendor&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <select id="vendor" name="vendor" class="form-control input-large">
                                            <?php 
								$data = $this->db->query("SELECT * from tbl_vendor ORDER BY vendor_name")->result();
								foreach($data as $row){ ?>
                                            <option value="<?= $row->vendor_id;?>"><?= $row->vendor_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Lead Time&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="leadtime" value="0" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Disc Cash</label>
                                    <div class="col-md-9">
                                        <input name="disccash" placeholder="" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Minimum Stock&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="minstock" value="0" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Reorder Level&nbsp;<small
                                            style="color:red">*</small></label>
                                    <div class="col-md-9">
                                        <input name="reorderlevel" value="0" class="form-control" maxlength="100"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Stock</label>
                                    <div class="col-md-9">
                                        <!-- <select name="jenisstock" class="form-control">
                                        </select> -->
                                        <select style='color:black;' id="vendor" name="jenisstock" class="selectpicker form-control"
                                            data-live-search="true" data-placeholder="Pilih..."
                                            onkeypress="return tabE(this,event)">
                                            <option value=''>-- Pilih Vendor --</option>
                                            <?php
                                            $query = $this->db->query("SELECT jenisstock FROM tbl_barang GROUP BY jenisstock");
                                             foreach($query as $dt){
                                             echo "<option>$dt->jenisstock</option>";
                                    }
                                ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Margin Harga</label>
                                    <div class="col-md-9">
                                        <table id="datatable_margin" class="table table-bordered">
                                            <thead>
                                                <th>Cabang</th>
                                                <th>Margin %</th>
                                                <th>Harga Jual</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
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
initailizeSelect2_vendor();
//initailizeSelect2_kasbank();

function gettarif() {
    var xhttp;
    var str = $('[name="kode"]').val();
    $('#datatable_margin tbody').empty();
    if (str == "") {

    } else {
        $.ajax({
            url: "<?php echo base_url();?>master_barang/getdatamargin/?barang=" + str,
            type: "GET",
            success: function(data) {
                $('#datatable_margin tbody').append(data);
            }
        });
    }
}


function calculate(id, margin) {
    var hna = eval($('#hnappn').val());
    var hjual = hna + (hna * (margin / 100));
    $('#td_data_' + id + '_3').val(hjual);
}

$("#hna").on("keyup focus click", function() {
    var hna = $(this).val();
    var ppn = $("#ppn").val();
    var ppnpersen = <?= str_replace(".00", "", $ppn->prosentase) ?> / 100;

    if (ppn == 1) {
        var hnappn = eval(hna) + (eval(hna) * ppnpersen);
        $("#hnappn").val(hnappn);
    } else {
        $("#hnappn").val(hna);
    }
});

$("#ppn").on("change", function() {
    var hna = $("#hna").val();
    var ppn = $("#ppn").val();
    var ppnpersen = <?= str_replace(".00", "", $ppn->prosentase) ?> / 100;

    if (ppn == 1) {
        var hnappn = eval(hna) + (eval(hna) * ppnpersen);
        $("#hnappn").val(hnappn);
    } else {
        $("#hnappn").val(hna);
    }
});
</script>