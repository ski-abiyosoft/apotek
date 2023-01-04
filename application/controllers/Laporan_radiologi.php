<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_radiologi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2651');
		$this->load->model(array("M_cetak", "M_ro", "M_barang", "M_alkes_transaksi", "M_cetak"));
		$this->load->helper(array("app_global", "rsreport"));
	}

	public function index()
	{
		$data = [
			'title'				=> 'Laporan Radiologi',
			'menu'				=> 'Laporan Radiologi',
		];

        $start = $this->input->get('tgl_mulai');
        $end = $this->input->get('tgl_akhir');

        if (isset($start) && isset($end)) {
            $start = $start." 00:00:00";
            $end = $end." 23:59:59";
            
            $dataRadio = $this->db->query("
                SELECT 
                tbl_hradio.noradio, 
                tbl_hradio.tglradio, 
                tbl_hradio.rekmed,
                tbl_hradio.namapas,
                tbl_hradio.diagnosa,
                tbl_dokter.nadokter,
                tbl_dradio.total_biaya,
                tbl_tarifh.tindakan
                FROM tbl_hradio
                JOIN tbl_dokter ON tbl_hradio.drpengirim=tbl_dokter.kodokter
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                JOIN tbl_tarifh ON tbl_dradio.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hradio.noradio, tbl_tarifh.tindakan;
            ")->result();

            $dataRekap = $this->db->query("
                SELECT 
                tbl_tarifh.tindakan,
                (
                    SELECT COUNT(tbl_hradio.id) 
                    FROM tbl_hradio 
                    WHERE tbl_hradio.jpas = 1 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as jumlah_rs,
                (
                    SELECT SUM(tbl_dradio.total_biaya) FROM tbl_hradio
                    JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                    WHERE tbl_hradio.jpas = 1 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as rupiah_rs,
                (
                    SELECT COUNT(tbl_hradio.id)
                    FROM tbl_hradio
                    WHERE tbl_hradio.jpas = 2 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as jumlah_luar,
                (
                    SELECT SUM(tbl_dradio.total_biaya) FROM tbl_hradio
                    JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                    WHERE tbl_hradio.jpas = 2 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as rupiah_luar
                FROM `tbl_hradio`
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                JOIN tbl_tarifh ON tbl_dradio.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.kodetarif;
            ")->result();

            $rangkingPemeriksaan = $this->db->query("
                SELECT 
                tbl_tarifh.tindakan, 
                COUNT(tbl_dradio.id) as total_tindakan 
                FROM tbl_hradio
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                JOIN tbl_tarifh ON tbl_dradio.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.tindakan
                ORDER BY COUNT(tbl_dradio.id) DESC 
                LIMIT 10;
            ")->result();
          
            $omsetRadio = $this->db->query("
                SELECT 
                tbl_hradio.jpas, 
                SUM(tbl_dradio.total_biaya) as total_biaya_pasien 
                FROM tbl_hradio
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hradio.jpas
                ORDER BY SUM(tbl_dradio.total_biaya) DESC;
            ")->result();
            
            $data['start'] = $start;
            $data['end'] = $end;
            $data['dataRadio'] = $dataRadio;
            $data['dataRekap'] = $dataRekap;
            $data['rangkingPemeriksaan'] = $rangkingPemeriksaan;
            $data['omsetRadio'] = $omsetRadio;
        }

		$this->load->view('Ro2/laporan', $data);
	}

    public function exportLab($mode)
    {
        $start = $this->input->get('tgl_mulai');
        $end = $this->input->get('tgl_akhir');

        if (isset($start) && isset($end)) {
            $start = $start." 00:00:00";
            $end = $end." 23:59:59";
            
            $dataRadio = $this->db->query("
                SELECT 
                tbl_hradio.noradio, 
                tbl_hradio.tglradio, 
                tbl_hradio.rekmed,
                tbl_hradio.namapas,
                tbl_hradio.diagnosa,
                tbl_dokter.nadokter,
                tbl_dradio.total_biaya,
                tbl_tarifh.tindakan
                FROM tbl_hradio
                JOIN tbl_dokter ON tbl_hradio.drpengirim=tbl_dokter.kodokter
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                JOIN tbl_tarifh ON tbl_dradio.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hradio.noradio, tbl_tarifh.tindakan;
            ")->result();

            $judul = "Laporan Radiologi";

            $chari = "";
			
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
						<th style='text-align: center'>No Radiologi</th>
                        <th style='text-align: center'>Tgl Periksa</th>
                        <th style='text-align: center'>No RM</th>
                        <th style='text-align: center'>Nama Pasien</th>
                        <th style='text-align: center'>Diagnosa</th>
                        <th style='text-align: center'>Dokter Pengirim</th>
                        <th style='text-align: center'>tindakan</th>
                        <th style='text-align: center'>Total rp</th>				
					</tr>
				</thead>
				<tbody>";
            
            $nomor = 1;

            foreach ($dataRadio as $data) {
                $chari .= "<tr>
                    <td>". (($data->noradio) ? ($data->noradio) : '-' ) ."</td>
                    <td>". (($data->tglradio) ? date('d-m-Y', strtotime($data->tglradio)) : '-' ) ."</td>
                    <td>". (($data->rekmed) ? $data->rekmed : '-'). "</td>
                    <td>". (($data->namapas) ? $data->namapas : '-') ."</td>
                    <td>". (($data->diagnosa) ? $data->diagnosa : '-') ."</td>
                    <td>". (($data->nadokter) ? $data->nadokter : '-') ."</td>
                    <td>". (($data->tindakan) ? $data->tindakan : '-') ."</td>
                    <td>". (($data->total_biaya ? $data->total_biaya : '-')) ."</td>
                </tr>";
            }

            $chari .= "</tbody>";
            $chari .= "</table>";

            if ($mode == "pdf") {
                $this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_transaksi_radiologi.PDF', 10, 10, 10, 0);
            } else {
                header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_transaksi_radiologi.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
            }
        }
    }

    public function exportRekap($mode) 
    {
        $start = $this->input->get('tgl_mulai');
        $end = $this->input->get('tgl_akhir');

        if (isset($start) && isset($end)) {
            $start = $start." 00:00:00";
            $end = $end." 23:59:59";
            
            $dataRekap = $this->db->query("
                SELECT 
                tbl_tarifh.tindakan,
                (
                    SELECT COUNT(tbl_hradio.id) 
                    FROM tbl_hradio 
                    WHERE tbl_hradio.jpas = 1 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as jumlah_rs,
                (
                    SELECT SUM(tbl_dradio.total_biaya) FROM tbl_hradio
                    JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                    WHERE tbl_hradio.jpas = 1 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as rupiah_rs,
                (
                    SELECT COUNT(tbl_hradio.id)
                    FROM tbl_hradio
                    WHERE tbl_hradio.jpas = 2 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as jumlah_luar,
                (
                    SELECT SUM(tbl_dradio.total_biaya) FROM tbl_hradio
                    JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                    WHERE tbl_hradio.jpas = 2 AND tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                ) 
                as rupiah_luar
                FROM `tbl_hradio`
                JOIN tbl_dradio ON tbl_hradio.noradio=tbl_dradio.noradio
                JOIN tbl_tarifh ON tbl_dradio.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hradio.tglradio BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.kodetarif;
            ")->result();

            $judul = "Laporan Rekap Radiologi";

            $chari = "";
			
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
                        <th style='text-align: center'>Tindakan</th>
                        <th style='text-align: center'>Jumlah RS</th>
                        <th style='text-align: center'>Rupiah Rp RS</th>
                        <th style='text-align: center'>Jumlah Luar</th>
                        <th style='text-align: center'>Rupiah Rp Luar</th>
                        <th style='text-align: center'>Total Jumlah</th>
                        <th style='text-align: center'>Total Rupiah Rp</th>				
					</tr>
				</thead>
				<tbody>";
            
            $nomor = 1;

            foreach ($dataRekap as $i => $data) {
                $total_jumlah = intval($data->jumlah_rs) + intval($data->jumlah_luar); 
                $total_rupiah = intval($data->rupiah_rs) + intval($data->rupiah_rs);
               
                $chari .= "<tr>
                    <td>". (($data->tindakan) ? $data->tindakan : '-') ."</td>
                    <td>". (($data->jumlah_rs) ? $data->jumlah_rs : '-' ) ."</td>
                    <td>". (($data->rupiah_rs) ? $data->rupiah_rs : '-') . "</td>
                    <td>". (($data->jumlah_luar) ? $data->jumlah_luar : '-') ."</td>
                    <td>". (($data->rupiah_luar) ? $data->rupiah_luar : '-') ."</td>
                    <td>". (($total_jumlah) ? $total_jumlah : '-') ."</td>
                    <td>". (($total_rupiah) ? $total_rupiah : '-') ."</td>
                </tr>";
            }

            $chari .= "</tbody>";
            $chari .= "</table>";

            if ($mode == "pdf") {
                $this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_rekap_radiologi.PDF', 10, 10, 10, 0);
            } else {
                header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_rekap_radiologi.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
            }
        }
    }
}
