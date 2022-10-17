<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">


<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?= $this->session->userdata("unit"); ?>
            </span>&nbsp;
            -
            &nbsp;<span class="title-web"><?= $menu; ?> <small> <?= $title; ?></small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home" style="color:#fff"></i>&nbsp;<a href="<?php echo base_url(); ?>dashboard" class="title-white">Awal</a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
            <li><a href="#" class="title-white"><?= $menu; ?> </a></a>&nbsp;<i class="fa fa-angle-right" style="color:#fff"></i></li>
            <li><a href="#" class="title-white"><?= $title; ?> </a></a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">RADIOLOGI</div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                20 Order </div>
                            <div class="desc" style="font-weight:bold">BELUM DIPROSES</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                30 Photo </div>
                            <div class="desc" style="font-weight:bold">SUDAH DIPROSES</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                10 Photo </div>
                            <div class="desc" style="font-weight:bold">SELESAI ISI HASIL</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual"><i class="fa fa-printx"></i></div>
                        <div class="details">
                            <div class="number">
                                10 Photo </div>
                            <div class="desc" style="font-weight:bold">BELUM ISI HASIL</div>
                        </div>
                        <a data-toggle="modal" class="more" href="">
                            Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="portlet-body">
            <div class="table-toolbar">
                <h5 style="display:inline-block;color:green"><b>DAFTAR ORDER DARI UNIT LAIN</b> </h5>
            </div>
            <div style="display:overlay ;" >

            <table class="table table-striped table-hover table-bordered" id="dataTableEx" name="table" cellspacing="0" width="100%">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center" class="title-white">Proses</th>
                        <th style="text-align: center" class="title-white">No Order</th>
                        <th style="text-align: center" class="title-white">Tanggal dan Jam Order</th>
                        <th style="text-align: center" class="title-white">No Mr</th>
                        <th style="text-align: center" class="title-white">Nama Pasien</th>
                        <th style="text-align: center" class="title-white">Pemeriksa</th>
                        <th style="text-align: center" class="title-white">Asal</th>
                        <th style="text-align: center" class="title-white">Permintaan Dari Dr</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($orderUnit as $data) {
                    ?>
                        <tr>
                            <td> <button class="btn green btn-sm">Proses</button> </td>
                            <td><?= $data->orderno ?></td>
                            <td><?= substr($data->tglorder, 0, 10)  ?> <?= $data->jamorder ?></td>
                            <td><?= $data->rekmed ?></td>
                            <td><?= $data->namapas ?></td>
                            <?php $queryTindakan = $this->db->query("SELECT * FROM tbl_eradio where notr='$data->orderno'")->result() ?>
                            <td>
                                <?php
                                foreach ($queryTindakan as $value) { ?>
                                    <li><?= $value->tindakan ?></li>
                                <?php } ?>

                            </td>
                            <td><?= $data->asal ?></td>
                            <td><?= $data->nadokter ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
        <br>

        <!-- 3 -->
        <div class="portlet-body">
            <div class="table-toolbar">
                <h5 style="display:inline-block;color:green"><b>DAFTAR PEMERIKSAAN</b> </h5>
            </div>
            <div class="row">
                <div class="input-daterange">
                    <div class="col-md-2">
                        <a href="<?= base_url() ?>ro/addDataPemeriksaan" class="btn green" style="margin-right:20px">
                            <i class="fa fa-plus" aria-hidden="true"></i> <b>Tambah Pemeriksaan</b>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="start_date" id="start_date" class="form-control" autocomplete="off" value="<?= date('Y-m-d') ?>" />
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="end_date" id="end_date" class="form-control" autocomplete="off" value="<?= date('Y-m-d') ?>" />
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="button" name="search" id="search" value="Search" class="btn btn-info" />
                    <input type="button" name="reset" id="reset" value="Reset" class="btn btn-secondary" />
                </div>
            </div>

            <br>

            <table id="order_data" class="table table-striped table-hover table-bordered" id="table" name="table" style="overflow: auto; white-space: nowrap;" cellspacing="0" width="100%">
                <thead class="breadcrumb">
                    <tr>
                        <th style="text-align: center" class="title-white">Aksi</th>
                        <th style="text-align: center" class="title-white">Hasil</th>
                        <th style="text-align: center" class="title-white">No Radio</th>
                        <th style="text-align: center" class="title-white">No Order</th>
                        <th style="text-align: center" class="title-white">Tanggal dan Jam</th>
                        <th style="text-align: center" class="title-white">No Rm</th>
                        <th style="text-align: center" class="title-white">Nama Pasien</th>
                        <!-- <th style="text-align: center" class="title-white">Pemeriksaan</th> -->
                        <th style="text-align: center" class="title-white">Dr Pengirim</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>
</div>
<br>
<br>
<?php
$this->load->view('template/footer');
?>

<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: "yyyy-mm-dd",
            autoclose: true
        });

        fetch_data('no');

        function fetch_data(is_date_search, start_date = '', end_date = '') {
            var dataTable = $('#order_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                // "order": [],
                "ajax": {
                    url: "<?= base_url() ?>ro/getDataPemeriksaan",
                    type: "POST",
                    data: {
                        is_date_search: is_date_search,
                        start_date: start_date,
                        end_date: end_date
                    }
                }
            });
        }

        $('#search').click(function() {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#order_data').DataTable().destroy();
                fetch_data("yes", start_date, end_date);
            } else {
                alert("Both Date is Required");
            }
        });

        $('#reset').click(function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            $('#start_date').val(today);
            $('#end_date').val(today);
            $('#order_data').DataTable().destroy();
            fetch_data('no');
        });



    });
</script>


<script>
    $(document).ready(function() {
        $('#dataTableEx').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
    });
</script>