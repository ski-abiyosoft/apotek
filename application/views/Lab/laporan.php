<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
                <div class="caption">LAPORAN LABOLATORIUM KLINIK</div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <form action="<?php base_url('laporan_laboratorium') ?>" method="get">
                        <div class="form-group row">
                            <label for="tgllab" class="col-sm-3 col-form-label">Dari Tanggal</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?= isset($start) ? str_replace(' 00:00:00', '', $start) : ''?>" placeholder="" format="YYYY-MM-DD" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgllab" class="col-sm-3 col-form-label">Sampai Tanggal</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= isset($end) ? str_replace(' 23:59:59', '', $end) : '' ?>" placeholder="" format="YYYY-MM-DD" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Lihat laporan</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($rangkingPemeriksaan)) { ?>
            <?php
                $pemeriksaan = [];
                foreach ($rangkingPemeriksaan as $i => $data) {
                    $data_chart = [
                        'name' => strval($data->tindakan),
                        'y' => intval($data->total_tindakan),
                    ];

                    array_push($pemeriksaan, $data_chart);
                }
            ?>
            <div class="portlet-body">
                <div class="col-lg-6">
                    <div class="table-toolbar">
                        <h5 style="display:inline-block;color:green"><b>10 Besar Pemeriksaan</b> </h5>
                    </div>
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                    </figure>
                </div>
                <div class="col-lg-6">
                <div class="table-toolbar">
                    <h5 style="display:inline-block;color:green"><b>10 Besar Pemeriksaan</b> </h5>
                </div>
                <table class="table table-striped table-hover table-bordered" id="dataTableEx" name="table" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                        <tr>
                            <th style="text-align: center" class="title-white">Pemeriksaan</th>
                            <th style="text-align: center" class="title-white">QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rangkingPemeriksaan  as $data) {
                        ?>
                            <tr>
                                <td><?= $data->tindakan ?></td>
                                <td><?= $data->total_tindakan ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        <?php } ?>

        <?php if(isset($omsetLab)) { ?>
            <div class="col-lg-12 col-12">
                <div class="portlet-body">
                    <div class="col-lg-6">
                        <div class="table-toolbar">
                            <h5 style="display:inline-block;color:green"><b>Omset Lab</b> </h5>
                        </div>
                        <figure class="highcharts-figure">
                            <div id="container-omset"></div>
                        </figure>
                    </div>
                    <div class="col-lg-6">
                        <div class="table-toolbar">
                            <h5 style="display:inline-block;color:green"><b>Omset Lab</b> </h5>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="dataTableOmset" name="table" cellspacing="0" width="100%">
                            <thead class="breadcrumb">
                                <tr>
                                    <th style="text-align: center" class="title-white">Kategory</th>
                                    <th style="text-align: center" class="title-white">rupiah rp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($omsetLab as $data) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                if ($data->jpas == 1) {
                                                    echo 'Pasien RS';
                                                } elseif ($data->jpas == 2) {
                                                    echo 'Pasien Luar';
                                                }
                                            ?>
                                        </td>
                                        <td><?= $data->total_biaya_pasien ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

        <br>
        <?php if (isset($dataLab)) { ?>
            <div class="col-lg-12 col-12">
            <div class="divided"></div>
                <div class="portlet-body mt-5">
                    <div class="table-toolbar">
                        <h5 style="display:inline-block;color:green"><b>Laporan Detail Transaksi Laboratorium</b> </h5>
                    </div>
                    <div class="row">
                        <div class="input-daterange">
                            <div class="col-md-2">
                                <form action="<?= base_url('laporan_laboratorium/exportLab/pdf') ?>" method="get">
                                    <input type="hidden" class="form-control" name="tgl_mulai" value="<?= isset($start) ? str_replace(' 00:00:00', '', $start) : ''?>" placeholder="" format="YYYY-MM-DD" required>
                                    <input type="hidden" class="form-control" name="tgl_akhir" value="<?= isset($end) ? str_replace(' 23:59:59', '', $end) : '' ?>" placeholder="" format="YYYY-MM-DD" required>
                                    <button type="submit" class="btn green" style="margin-right:20px">
                                        <b>Export PDF</b>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= base_url('laporan_laboratorium/exportLab/excel') ?>" method="get">
                                    <input type="hidden" class="form-control" name="tgl_mulai" value="<?= isset($start) ? str_replace(' 00:00:00', '', $start) : ''?>" placeholder="" format="YYYY-MM-DD" required>
                                    <input type="hidden" class="form-control" name="tgl_akhir" value="<?= isset($end) ? str_replace(' 23:59:59', '', $end) : '' ?>" placeholder="" format="YYYY-MM-DD" required>
                                    <button type="submit" class="btn green" style="margin-right:20px">
                                        <b>Export Excel</b>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="table table-striped table-hover table-bordered" id="dataTableLab" name="table" cellspacing="0" width="100%">
                        <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center" class="title-white">No Laborat</th>
                                <th style="text-align: center" class="title-white">Tgl Periksa</th>
                                <th style="text-align: center" class="title-white">No RM</th>
                                <th style="text-align: center" class="title-white">Nama Pasien</th>
                                <th style="text-align: center" class="title-white">Diagnosa</th>
                                <th style="text-align: center" class="title-white">Dokter Pengirim</th>
                                <th style="text-align: center" class="title-white">tindakan</th>
                                <th style="text-align: center" class="title-white">Total rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($dataLab as $data) {
                            ?>
                                <tr>
                                    <td><?= $data->nolaborat ?></td>
                                    <td><?= date('d-m-Y', strtotime($data->tgllab)) ?></td>
                                    <td><?= $data->rekmed ?></td>
                                    <td><?= $data->namapas ?></td>
                                    <td><?= $data->diagnosa ?></td>
                                    <td><?= $data->nadokter ?></td>
                                    <td><?= $data->tindakan ?></td>
                                    <td><?= $data->total_biaya ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

        <br>
        <?php if (isset($dataRekap)) { ?>
            <div class="col-lg-12 col-12">
            <div class="divided"></div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <h5 style="display:inline-block;color:green"><b>Laporan Rekap Transaksi Laboratorium</b> </h5>
                    </div>
                    <div class="row">
                        <div class="input-daterange">
                            <div class="col-md-2">
                                <form action="<?= base_url('laporan_laboratorium/exportRekap/pdf') ?>" method="get">
                                    <input type="hidden" class="form-control" name="tgl_mulai" value="<?= isset($start) ? str_replace(' 00:00:00', '', $start) : ''?>" placeholder="" format="YYYY-MM-DD" required>
                                    <input type="hidden" class="form-control" name="tgl_akhir" value="<?= isset($end) ? str_replace(' 23:59:59', '', $end) : '' ?>" placeholder="" format="YYYY-MM-DD" required>
                                    <button type="submit" class="btn green" style="margin-right:20px">
                                        <b>Export PDF</b>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= base_url('laporan_laboratorium/exportRekap/excel') ?>" method="get">
                                    <input type="hidden" class="form-control" name="tgl_mulai" value="<?= isset($start) ? str_replace(' 00:00:00', '', $start) : ''?>" placeholder="" format="YYYY-MM-DD" required>
                                    <input type="hidden" class="form-control" name="tgl_akhir" value="<?= isset($end) ? str_replace(' 23:59:59', '', $end) : '' ?>" placeholder="" format="YYYY-MM-DD" required>
                                    <button type="submit" class="btn green" style="margin-right:20px">
                                        <b>Export Excel</b>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="table table-striped table-hover table-bordered" id="dataTableRe" name="table" cellspacing="0" width="100%">
                        <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center" class="title-white">Tindakan</th>
                                <th style="text-align: center" class="title-white">Jumlah RS</th>
                                <th style="text-align: center" class="title-white">Rupiah Rp RS</th>
                                <th style="text-align: center" class="title-white">Jumlah Luar</th>
                                <th style="text-align: center" class="title-white">Rupiah Rp Luar</th>
                                <th style="text-align: center" class="title-white">Total Jumlah</th>
                                <th style="text-align: center" class="title-white">Total Rupiah Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($dataRekap as $data) {
                            ?>
                                <tr>
                                    <td><?= $data->tindakan ?></td>
                                    <td><?= $data->jumlah_rs ?></td>
                                    <td><?= $data->rupiah_rs ?></td>
                                    <td><?= $data->jumlah_luar ?></td>
                                    <td><?= $data->rupiah_luar ?></td>

                                    <?php 
                                        $total_jumlah = intval($data->jumlah_rs) + intval($data->jumlah_luar); 
                                        $total_rupiah = intval($data->rupiah_rs) + intval($data->rupiah_rs);
                                    ?>

                                    <td><?= $total_jumlah ?></td>
                                    <td><?= $total_rupiah ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<br>
<br>
<?php
$this->load->view('template/footer');
?>

<script>
    $(document).ready(function() {
        $('#dataTableEx').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
        $('#dataTableRe').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
        $('#dataTableOmset').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
        $('#dataTableLab').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
    });
</script>

<?php if (isset($rangkingPemeriksaan)) {?>
<script>
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            align: 'left',
            text: '10 Besar Pemeriksaan'
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Jumlah Pemeriksaan'
            }
        },
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [
            {
                name: "Pemeriksaan",
                colorByPoint: true,
                data: [
                    <?php 
                    foreach ($pemeriksaan as $i => $data) {    
                    ?>
                    {
                        name : '<?php echo $data['name'] ?>',
                        y : <?php echo $data['y'] ?>
                    },
                    <?php } ?>
                ]
            }
        ],
    });
</script>
<?php } ?>

<?php if (isset($omsetLab)) { ?>
<script>
    Highcharts.chart('container-omset', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Grafik Omset Laboratorium'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [
            <?php foreach ($omsetLab as $i => $data) { ?>
                <?php 
                    if ($data->jpas == 1) {
                        $name = 'Pasien RS';
                    } else if ($data->jpas == 2) {
                        $name = 'Pasien Luar';
                    }

                    $total_biaya = intval($omsetLab[0]->total_biaya_pasien) + intval($omsetLab[0]->total_biaya_pasien); 

                    $percent = (intval($data->total_biaya_pasien) / $total_biaya) * 100;
                ?>
                {
                    name: '<?php echo $name;?>',
                    y: <?php echo $percent; ?>
                },
            <?php } ?>
        ]
    }]
});
</script>
<?php } ?>