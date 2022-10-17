<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
<script src="<?php echo base_url();?>assets/js/highcharts.js"></script>
<script src="<?php echo base_url();?>assets/js/exporting.js"></script>

<?php  
  
    
    foreach($report as $result){
        $bulan[] = $result->bulan; 
        $value[] = (float) $result->jumlah; 
	}
	
	
	
?>

<style type="text/css"/>

	
#sales-chart
{
  min-height: 300px;
}

</style>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat blue">
			<div class="visual">
				<i class="fa fa-barcodex"></i>
			</div>
			<div class="details">
				<div class="number">
				  <?php echo number_format($pasien,0, ',','.');?>
				</div>

				<div class="desc">
				  JUMLAH PASIEN
				</div>
			</div>

			<a data-toggle="modal" class="more" href="<?= base_url('pasien') ?>">
				 Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat green">
			<div class="visual">
				<i class="fa fa-printx"></i>
			</div>
			<div class="details">
				<div class="number">
				    <?php echo number_format($piutang,0, ',','.');?>
				</div>
				<div class="desc">
					PIUTANG USAHA (Rp)
				</div>
			</div>
			<a data-toggle="modal" class="more" href="<?= base_url('penjualan_faktur') ?>">
				 Lihat Rinci <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat yellow">
			<div class="visual">
				<i class="fa fa-shopping-cartx"></i>
			</div>
			<div class="details">
				<div class="number">
				    <?php echo number_format($hutang,0, ',','.');?>
				</div>
				<div class="desc">
					 HUTANG USAHA (Rp)
				</div>
			</div>
			<a data-toggle="modal" class="more" href="<?= base_url('pembelian_faktur') ?>">
				 Lihat Rinci <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat red">
			<div class="visual">
				<i class="fa fa-shopping-cartx"></i>
			</div>
			<div class="details">
				<div class="number">
				    <?php echo number_format(0,0, ',','.');?>
				</div>
				<div class="desc">
					 LABA/RUGI TAHUN INI
				</div>
			</div>
			<a data-toggle="modal" class="more" href="<?= base_url('akuntansi_lap') ?>">
				 Lihat Rinci <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	

</div>
<div class="clearfix"></div>
<div class="row">
<div class="col-md-12 col-sm-12">	
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-bar-chart-o"></i>Kunjungan Pasien
			</div>

		</div>
		<div class="portlet-body">			
            <!--div id="pasar-chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div-->			
			
				<div id="sales-chart"></div>
			
			
		</div>
	</div>
</div>
 
</div>


<script type="text/javascript">


$(function () {
    $('#sales-chart').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
                enabled: false,
                alpha: 10,
                beta: 25,
                depth: 70
            }
        },

        title: {
            text: '',
            style: {
                    fontSize: '18px',
                    fontFamily: 'Verdana, sans-serif'
            }
        },
        subtitle: {
           text: '<?php echo $tahun;?>',
           style: {
                    fontSize: '15px',
                    fontFamily: 'Verdana, sans-serif'
            }
        },
        plotOptions: {
            column: {
				stacking: 'normal',
                depth: 25
            }
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories:  <?php echo json_encode($bulan);?>
        },
        exporting: { 
            enabled: false 
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            },
        },
        tooltip: {
             formatter: function() {
                 return 'The value for <b>' + this.x + '</b> is <b>' + Highcharts.numberFormat(this.y,0) + '</b>, in '+ this.series.name;
             }
          },

        series: [{
            name: 'Bulan',
            data: <?php echo json_encode($value);?>,
            shadow : true,			
            dataLabels: {
                enabled: true,
                color: '#045396',
                align: 'center',
                formatter: function() {
                     return Highcharts.numberFormat(this.y, 0);
                }, // one decimal
                y: 0, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
		
		
		],

    });
});


</script>

<?php
  $this->load->view('template/footer');  
?>			
	