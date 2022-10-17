<!-- <script src="<?php echo base_url();?>assets/js/jquery.js"></script> -->
<!-- <script src="<?php echo base_url();?>assets/js/highcharts.js"></script> -->
<!-- <script src="<?php echo base_url();?>assets/js/exporting.js"></script> -->

<?php  
  
    
    foreach($report as $result){
        $bulan[] = $result->bulan; 
        $value[] = (float) $result->jumlah; 
	}
	
	
	
?>

<style type="text/css"/>

	
#kunjungan-pasien-chart
{
  min-height: 300px;
}

</style>

<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">KLINIK <small>Laporan Pendaftaran</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a class="title-white" href="../home.php">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                              Pendaftaran
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                              Laporan
							</a>
						</li>
					</ul>
				</div>
			</div>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">	
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bar-chart-o"></i>Grafik Kunjungan Pasien
                </div>
            </div>
            <div class="portlet-body">			
                <div id="kunjungan-pasien-chart"></div>						
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


$(function () {
    $('#kunjungan-pasien-chart').highcharts({
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
