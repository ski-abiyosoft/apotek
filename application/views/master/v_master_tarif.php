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
                <span class="title-web">Master <small>Tarif</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">

                <li>
                    <i class="fa fa-home"></i>
                    <a class="title-white" href="<?php echo base_url(); ?>dashboard">
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
                        Tarif
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption col-md-6">
                        Daftar Kode Tarif
                    </div>
                    <div class="col-md-2">
                        &nbsp;&nbsp;&nbsp; Cabang :
                    </div>
                    <div class="pull-right col-md-4">

                        <select class="form-control input-large" id="cabang" name="cabang" onchange="gettarifcabang()">
                        <option value="">-- Pilih Cabang --</option>

                        <?php 
                        $sql = $this->db->query("SELECT koders, namars from tbl_namers where koders= '$unit' order by namars")->result();
                        foreach($sql as $rows){
                        ?>

                        <option value="<?= $rows->koders?>"><?= $rows->namars?></option>
                        
                        <?php } ?>
                        </select>
                        <!-- <span> -->
                            <!--a class="btn btn-warning" href="<?php echo base_url(); ?>master_tarif_cabang">Daftar Tarif</a>
								</span-->

                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <button class="btn btn-success" onclick="add_bank()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
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

                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="breadcrumb header-custom">
                            <tr>
                                <th class="title-white" style="text-align: center" width="20%">Kode</th>
                                <th class="title-white" style="text-align: center">Tindakan</th>
                                <th class="title-white" style="text-align: center">Poli</th>
                                <th class="title-white" style="text-align: center">Akun Pendapaatan</th>
                                <th class="title-white" style="text-align: center; width:15%;">Aksi</th>
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
                    "url": "<?php echo site_url('master_tarif/ajax_list') ?>",
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
            $('#bodydetailtarif').html();
            $('#bodycostbhp').html('');
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
            initailizeSelect2_akunpendapatan();
        }

        function edit_data(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            initailizeSelect2_akunpendapatan();
            // console.log(id)

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('master_tarif/ajax_edit/') ?>" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.id);
                    $('[name="poli"]').val(data.kodepos);
                    $('[name="kode"]').val(data.kodetarif);
                    $('[name="nama"]').val(data.tindakan);
                    $('[name="akunpendapatan"]').append(
                        `
                            <option value="${data.accountno}">${data.accountno} - ${data.acname}</option>
                        `);
                    data.tidakaktif == 1 ? document.getElementById('tidakaktif').checked = true : document.getElementById('tidakaktif').checked = false;

                    $('#bodydetailtarif').html('')
                    for (let i = 0; i < data.detail_tarif.length; i++) {
                        $('#bodydetailtarif').append(
                            `
                            <tr>
                                <td width="5%">
                                    <span class="btn btn-danger btn-small" data-idtarif="${data.detail_tarif[i].id}" onclick="hapusTarif(this)"><i class="fa fa-trash"></i></span>
                                </td>
                                <td width="15%">
                                    <input name="idtarif[]" value="${data.detail_tarif[i].id}" type="hidden">
                                    <select name="cabang[]" id="cabang${i+1}" class="select2_el_cabangg form-control input-largex">
                                        <option value="${data.detail_tarif[i].koders}">${data.detail_tarif[i].koders}</option>
                                    </select>
                                </td>
                                <td width="15%">
                                    <select name="keltarif[]" id="keltarif${i+1}" class="select2_el_tarif form-control input-largex">
                                        <option value="${data.detail_tarif[i].cust_id}">${data.detail_tarif[i].cust_id}</option>
                                    </select>
                                </td>
                                <td width="10%"><input name="jasars[]" onchange="totallineTarif(${i+1})" value="${data.detail_tarif[i].tarifrspoli}" id="jasars${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="10%"><input name="jasadr[]" onchange="totallineTarif(${i+1})" value="${data.detail_tarif[i].tarifdrpoli}" id="jasadr${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="10%"><input name="jasaperawat[]" onchange="totallineTarif(${i+1})" value="${data.detail_tarif[i].feemedispoli}" id="jasaperawat${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="10%"><input name="bhp[]" onchange="totallineTarif(${i+1})" value="${data.detail_tarif[i].bhppoli}" id="bhp${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="10%"><input name="total[]" value="${data.detail_tarif[i].cost}" id="total${i+1}" type="text" class="form-control rightJustified" readonly></td>
                            </tr>
                            `
                        )
                        initailizeSelect2_cabangg();
                        initailizeSelect2_tarif();
                    }

                    $('#bodycostbhp').html('')
                    for (let i = 0; i < data.bhp.length; i++) {
                        $('#bodycostbhp').append(
                            `
                            <tr>
                                <td width="5%">
                                    <span class="btn btn-danger btn-small" data-idbhp="${data.bhp[i].id}" onclick="hapusBHP(this)"><i class="fa fa-trash"></i></span>
                                </td>
                                <td width="30%">
                                    <input name="idbhp[]" value="${data.bhp[i].id}" type="hidden">
                                    <select name="kodeobat[]" id="kodeobat${i+1}" class="select2_el_farmasi_barang form-control input-largex" onchange="showbarangname(this.value;cekstok(this.value)">
                                        <option value="${data.bhp[i].kodeobat}">${data.bhp[i].kodeobat}  |  ${data.bhp[i].namabarang}</option>
                                    </select>
                                </td>
                                <td width="10%"><input name="qty[]" onchange="totalline(${i+1});total()" value="${data.bhp[i].qty}" id="qty${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="10%"><input name="sat[]" id="sat${i+1}" type="text" class="form-control " value="${data.bhp[i].satuan}" onkeypress="return tabE(this,event)"></td>
                                <td width="10%"><input name="harga[]" onchange="totalline(${i+1})" value="${data.bhp[i].harga}" id="harga${i+1}" type="text" class="form-control rightJustified"></td>
                                <td width="20%"><input name="jumlah[]" value="${data.bhp[i].harga}" id="jumlah${i+1}" type="text" class="form-control rightJustified" size="40%" onchange="total()" readonly></td>
                            </tr>
                            `
                        )
                        initailizeSelect2_farmasi_barang();
                    }

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
                url = "<?php echo site_url('master_tarif/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('master_tarif/ajax_update') ?>";
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

        function gettarifcabang() {
            var cabang = $('#cabang').val();
            location.href = "<?php echo base_url('master_tarif_cabang/index_tarif/') ?>" + cabang;
        }
    </script>

    <script>
        $(document).ready(function() {

            $('.print_laporan').on("click", function() {
                $('.modal-title').text('MASTER');
                var no_daftar = this.id;
                $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>master_policetak/' + no_daftar + '" frameborder="no" width="100%" height="420"></iframe>');
            });


        });
    </script>

    <style>
        .select2-container {
            z-index: 99999;
        }
    </style>

    <div class="modal fade" id="modal_form2" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="container">
        <div class="modal-dialog modal-lg">
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
                                <label class="control-label col-md-3">Unit</label>
                                <div class="col-md-9">
                                    <select name="poli" class="form-control">
                                        <option value="">--- Pilih Unit ---</option>
                                        <?php foreach ($poli as $row) { ?>
                                            <option value="<?= $row->kodepos; ?>"><?= $row->namapost; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Kode</label>
                                <div class="col-md-9">
                                    <input name="kode" placeholder="Kode" class="form-control input-small" type="text" readonly>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Layanan / Tindakan</label>
                                <div class="col-md-9">
                                    <input name="nama" placeholder="Nama" class="form-control" maxlength="100" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Akun Pendapatan</label>
                                <div class="col-md-9">
                                    <!-- <select name="akunpendapatan" id="akunpendapatan" class="select2_el_akunpendapatan form-control" style="width: 100%;">
                                    </select> -->

                                    <!-- husain change -->
                                    <?php $sql = $this->db->query("SELECT accountno as id, concat(accountno, ' - ', acname) as text from tbl_accounting order by id")->result(); ?>
                                    <select name="akunpendapatan" id="akunpendapatan" class="select2_akunpendapatan form-control" style="width: 100%;" onchange="showoption()">
                                    <option value=""></option>
                                    <?php foreach($sql as $s) : ?>
                                        <option value="<?= $s->id; ?>"><?= $s->text; ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                    <!-- end husain -->
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-9" style="display: flex; gap: 5px;">
                                    <input name="tidakaktif" id="tidakaktif" data-menu="check" type="checkbox" value="1">
                                    <label for="tidakaktif"> Tidak dipakai </label>
                                </div>
                            </div>

                            <!-- Detail Tarif & Cost -->
                            <div class="tabbable tabbable-custom tabbable-full-width" id="modal-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#detailtarif" data-toggle="tab"> Detail Tarif </a>
                                    </li>
                                    <li>
                                        <a href="#costbhp" data-toggle="tab"> Cost dan BHP</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="tab-content">
                                <!-- Tab Detail Tarif -->
                                <div class="tab-pane active" id="detailtarif">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="datatableTarif" class="table table-hoverx table-stripedx table-borderdx table-condensed table-scrollable">
                                                <thead>
                                                    <th width="5%" style="text-align: center; "></th>
                                                    <th width="15%" style="text-align: center; ">Cabang</th>
                                                    <th width="15%" style="text-align: center;">Kelompok Tarif</th>
                                                    <th width="10%" style="text-align: center;">Jasa RS / Klinik</th>
                                                    <th width="10%" style="text-align: center;">Jasa DR</th>
                                                    <th width="10%" style="text-align: center;">Jasa Perawat</th>
                                                    <th width="10%" style="text-align: center;">BHP</th>
                                                    <th width="10%" style="text-align: center;">Total</th>
                                                </thead>
                                                <tbody id="bodydetailtarif">

                                                </tbody>
                                            </table>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <div class="wells">
                                                        <button type="button" onclick="tambahTarif()" class="btn green"><i class="fa fa-plus"></i> Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Cost & BHP-->
                                <div class="tab-pane" id="costbhp">
                                    <form id="frmtarif" class="form-horizontal" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
                                                    <thead>
                                                        <th width="5%" style="text-align: center; "></th>
                                                        <th width="10%" style="text-align: center;">Kode Barang</th>
                                                        <th width="10%" style="text-align: center;">Kuantitas</th>
                                                        <th width="10%" style="text-align: center;">Satuan</th>
                                                        <th width="10%" style="text-align: center;">Harga</th>
                                                        <th width="10%" style="text-align: center;">Total Harga</th>
                                                    </thead>
                                                    <tbody id="bodycostbhp">

                                                    </tbody>

                                                </table>
                                                <div class="row">
                                                    <div class="col-xs-9">
                                                        <div class="wells">
                                                            <button type="button" onclick="tambahBHP()" class="btn green"><i class="fa fa-plus"></i> Tambah</button>
                                                        </div>
                                                    </div>
                                                </div></br>
                                            </div>
                                        </div>
                                        <!-- Tab -->
                                        <div class="row">
                                            <div class="col-xs-4 invoice-block">
                                                <div class="well">
                                                    <table>
                                                        <tr>
                                                            <td width="40%"><strong>Total</strong></td>
                                                            <td width="1%"><strong>:</strong></td>
                                                            <td width="59%" align="right"><strong><span id="_vtotal"></span></strong></td>
                                                        </tr>
                                                        <input type="hidden" id="tersimpan">
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        </div><!-- /.modal-container -->
    </div><!-- /.modal -->

    <script>
        function showoption(){
            $(".select2-hidden-accessible").removeClass();
            $(".select2_akunpendapatan").select2();
        }
        $(".select2_akunpendapatan").select2();
        var idrow = "<?= $jumdatabhp + 1; ?>";
        var idroww = "<?= $jumdatatarif + 1; ?>";

        function tambahTarif() {
            var x = document.getElementById('datatableTarif').insertRow(idroww);
            var td1 = x.insertCell(0);
            var td2 = x.insertCell(1);
            var td3 = x.insertCell(2);
            var td4 = x.insertCell(3);
            var td5 = x.insertCell(4);
            var td6 = x.insertCell(5);
            var td7 = x.insertCell(6);
            var td8 = x.insertCell(7);

            td1.innerHTML = "<span class='btn btn-danger btn-small' data-idtarif='${data.detail_tarif[i].id}' onclick='hapusTarif()''><i class='fa fa-trash'></i></span>"
            td2.innerHTML = "<select name='cabang[]' id=cabang" + idroww + "' class='select2_el_cabangg form-control' ></select><input name='idtarif[]' value='0' type='hidden'>";
            td3.innerHTML = "<select name='keltarif[]' id=keltarif" + idroww + "' class='select2_el_tarif form-control' ></select>";
            td4.innerHTML = "<input name='jasars[]' id=jasars" + idroww + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
            td5.innerHTML = "<input name='jasadr[]' id=jasadr" + idroww + " onchange='totallineTarif(" + idrow + ")' type='text' class='form-control rightJustified'  >";
            td6.innerHTML = "<input name='jasaperawat[]' id=jasaperawat" + idrow + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
            td7.innerHTML = "<input name='bhp[]' id=bhp" + idroww + " onchange='totallineTarif(" + idroww + ")' type='text' class='form-control rightJustified'  >";
            td8.innerHTML = "<input name='total[]' id=total" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";

            initailizeSelect2_cabangg();
            initailizeSelect2_tarif();
            idroww++;
        }

        function tambahBHP() {
            var x = document.getElementById('datatable').insertRow(idrow);
            var td1 = x.insertCell(0);
            var td2 = x.insertCell(1);
            var td3 = x.insertCell(2);
            var td4 = x.insertCell(3);
            var td5 = x.insertCell(4);
            var td6 = x.insertCell(5);

            td1.innerHTML = "<span class='btn btn-danger btn-small' data-idbhp='${data.bhp[i].id}' onclick='hapusBHP()''><i class='fa fa-trash'></i></span>"
            td2.innerHTML = "<select name='kodeobat[]' id=kodeobat" + idrow + " onchange='showbarangname(this.value," + idrow + ");cekstok(this.value)' class='select2_el_farmasi_barang form-control' ></select><input name='idbhp[]' value='0' type='hidden'>";
            td3.innerHTML = "<input name='qty[]' id=qty" + idrow + " onchange='totalline(" + idrow + ")' value='1' type='text' class='form-control rightJustified'  >";
            td4.innerHTML = "<input name='sat[]' id=sat" + idrow + " type='text' class='form-control' >";
            td5.innerHTML = "<input name='harga[]' id=harga" + idrow + " onchange='totalline(" + idrow + ") value='0' type='text' class='form-control rightJustified' >";
            td6.innerHTML = "<input name='jumlah[]' id=jumlah" + idrow + " type='text' class='form-control rightJustified' size='40%' readonly>";

            initailizeSelect2_farmasi_barang();
            idrow++;
        }


        function hapusTarif(el = null) {
            if (el) {
                $.ajax({
                    url: "<?php echo site_url('master_tarif/delete_tarif') ?>/" + el.dataset.idtarif,
                    dataType: 'JSON',
                    success: function(data) {
                        el.parentElement.parentElement.remove();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                })
            }
            if (idroww > 1) {
                var x = document.getElementById('datatableTarif').deleteRow(idroww - 1);
                idroww--;
                total();
            }
        }

        function hapusBHP(el = null) {
            if (el) {
                $.ajax({
                    url: "<?php echo site_url('master_tarif/delete_bhp') ?>/" + el.dataset.idbhp,
                    dataType: 'JSON',
                    success: function(data) {
                        el.parentElement.parentElement.remove();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                })
            }
            if (idrow > 1) {
                var x = document.getElementById('datatable').deleteRow(idrow - 1);
                idrow--;
                total();
            }
        }

        function total() {
            var table = document.getElementById('datatable');
            var rowCount = table.rows.length;

            tjumlah = 0;
            for (var i = 1; i < rowCount; i++) {
                var row = table.rows[i];

                jumlah = row.cells[2].children[0].value;
                harga = row.cells[4].children[0].value;
                var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g, ""));
                var harga1 = Number(harga.replace(/[^0-9\.]+/g, ""));

                tjumlah = tjumlah + eval(jumlah1 * harga1);

                document.getElementById("_vtotal").innerHTML = formatCurrency1(tjumlah);

                if (tjumlah > 0) {
                    document.getElementById("btnsimpan").disabled = false;
                } else {
                    document.getElementById("btnsimpan").disabled = true;
                }
            }
        }

        function totalline(id) {
            var table = document.getElementById('datatable');
            var row = table.rows[id];
            var harga = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
            jumlah = row.cells[2].children[0].value * harga;

            tot = jumlah;

            row.cells[5].children[0].value = formatCurrency1(tot);
            total();
        }

        function totallineTarif(id) {
            var table = document.getElementById('datatableTarif');
            var row = table.rows[id];
            var jasars = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g, ""));
            var jasadr = Number(row.cells[4].children[0].value.replace(/[^0-9\.]+/g, ""));
            var jasaperawat = Number(row.cells[5].children[0].value.replace(/[^0-9\.]+/g, ""));
            var bhp = Number(row.cells[6].children[0].value.replace(/[^0-9\.]+/g, ""));
            totalTarif = jasars + jasadr + jasaperawat + bhp;

            tot = totalTarif;

            row.cells[7].children[0].value = formatCurrency1(tot);
        }

        function showbarangname(str, id) {
            var xhttp;
            var vid = id;
            $('#sat' + vid).val('');
            $('#harga' + vid).val(0);
            var customer = $('#cust').val();
            $.ajax({
                url: "<?php echo base_url(); ?>penjualan_faktur/getinfobarang/?kode=" + str,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#nama' + vid).val(data.namabarang);
                    $('#sat' + vid).val(data.satuan1);
                    $('#harga' + vid).val(formatCurrency1(data.hargajual));
                    totalline(vid);
                }
            });
        }
    </script>