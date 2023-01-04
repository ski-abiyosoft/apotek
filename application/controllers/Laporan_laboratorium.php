<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_laboratorium extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2651');
		$this->load->model(array("M_bedah", "M_pasien_global", "M_cetak", "M_lab", "M_barang", "M_alkes_transaksi", "M_cetak"));
		$this->load->helper(array("app_global", "rsreport"));
	}

	public function index()
	{
		$data = [
			'title'				=> 'Laporan Labolatorium',
			'menu'				=> 'Laporan Labolatorium',
		];

        $start = $this->input->get('tgl_mulai');
        $end = $this->input->get('tgl_akhir');

        if (isset($start) && isset($end)) {
            $start = $start." 00:00:00";
            $end = $end." 23:59:59";
            
            $dataLab = $this->db->query("
                SELECT 
                tbl_hlab.nolaborat, 
                tbl_hlab.tgllab, 
                tbl_hlab.rekmed,
                tbl_hlab.namapas,
                tbl_hlab.diagnosa,
                tbl_dokter.nadokter,
                tbl_dlab.totalrp,
                tbl_tarifh.tindakan
                FROM tbl_hlab
                JOIN tbl_dokter ON tbl_hlab.drpengirim=tbl_dokter.kodokter
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hlab.nolaborat, tbl_tarifh.tindakan
            ")->result();

            $dataRekap = $this->db->query("
                SELECT 
                    tbl_tarifh.tindakan,
                    (
                        SELECT COUNT(tbl_hlab.id) 
                        FROM tbl_hlab 
                        WHERE tbl_hlab.jpas = 1 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as jumlah_rs,
                    (
                        SELECT SUM(tbl_dlab.totalrp) FROM tbl_hlab
                        JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                        WHERE tbl_hlab.jpas = 1 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as rupiah_rs,
                    (
                        SELECT COUNT(tbl_hlab.id)
                        FROM tbl_hlab
                        WHERE tbl_hlab.jpas = 2 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as jumlah_luar,
                    (
                        SELECT SUM(tbl_dlab.totalrp) FROM tbl_hlab
                        JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                        WHERE tbl_hlab.jpas = 2 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as rupiah_luar
                FROM `tbl_hlab`
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.kodetarif;
            ")->result();

            $rangkingPemeriksaan = $this->db->query("
                SELECT 
                    tbl_tarifh.tindakan, 
                    COUNT(tbl_dlab.id) as total_tindakan 
                FROM tbl_hlab
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.tindakan
                ORDER BY COUNT(tbl_dlab.id) DESC 
                LIMIT 10;
            ")->result();
          

            $omsetLab = $this->db->query("
                SELECT 
                    tbl_hlab.jpas, 
                    SUM(tbl_dlab.totalrp) as totalrp_pasien 
                FROM tbl_hlab
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hlab.jpas
                ORDER BY SUM(tbl_dlab.totalrp) DESC;
            ")->result();
            
            $data['start'] = $start;
            $data['end'] = $end;
            $data['dataLab'] = $dataLab;
            $data['dataRekap'] = $dataRekap;
            $data['rangkingPemeriksaan'] = $rangkingPemeriksaan;
            $data['omsetLab'] = $omsetLab;
        }

		$this->load->view('Lab/laporan', $data);
	}

    public function exportLab($mode)
    {
        $start = $this->input->get('tgl_mulai');
        $end = $this->input->get('tgl_akhir');

        if (isset($start) && isset($end)) {
            $start = $start." 00:00:00";
            $end = $end." 23:59:59";
            
            $dataLab = $this->db->query("
                SELECT 
                tbl_hlab.nolaborat, 
                tbl_hlab.tgllab, 
                tbl_hlab.rekmed,
                tbl_hlab.namapas,
                tbl_hlab.diagnosa,
                tbl_dokter.nadokter,
                tbl_dlab.totalrp,
                tbl_tarifh.tindakan
                FROM tbl_hlab
                JOIN tbl_dokter ON tbl_hlab.drpengirim=tbl_dokter.kodokter
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_hlab.nolaborat, tbl_tarifh.tindakan
            ")->result();

            $judul = "Laporan Laboratorium";

            $chari = "";
			
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
						<th style='text-align: center'>No Laborat</th>
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

            foreach ($dataLab as $data) {
                $chari .= "<tr>
                    <td>". (($data->nolaborat) ? $data->nolaborat : '-') ."</td>
                    <td>". (($data->tgllab) ? date('d-m-Y', strtotime($data->tgllab)) : '-') ."</td>
                    <td>". (($data->rekmed) ? $data->rekmed : '-') . "</td>
                    <td>". (($data->namapas) ? $data->namapas : '-') ."</td>
                    <td>". (($data->diagnosa) ? $data->diagnosa : '-') ."</td>
                    <td>". (($data->nadokter) ? $data->nadokter : '-') ."</td>
                    <td>". (($data->tindakan) ? $data->tindakan : '-') ."</td>
                    <td>". (($data->totalrp) ? $data->totalrp : '-') ."</td>
                </tr>";
            }

            $chari .= "</tbody>";
            $chari .= "</table>";

            if ($mode == "pdf") {
                $this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_transaksi_laboratorium.PDF', 10, 10, 10, 0);
            } else {
                header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_transaksi_laboratorium.xls');
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
                        SELECT COUNT(tbl_hlab.id) 
                        FROM tbl_hlab 
                        WHERE tbl_hlab.jpas = 1 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as jumlah_rs,
                    (
                        SELECT SUM(tbl_dlab.totalrp) FROM tbl_hlab
                        JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                        WHERE tbl_hlab.jpas = 1 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as rupiah_rs,
                    (
                        SELECT COUNT(tbl_hlab.id)
                        FROM tbl_hlab
                        WHERE tbl_hlab.jpas = 2 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as jumlah_luar,
                    (
                        SELECT SUM(tbl_dlab.totalrp) FROM tbl_hlab
                        JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                        WHERE tbl_hlab.jpas = 2 AND tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                    ) 
                    as rupiah_luar
                FROM `tbl_hlab`
                JOIN tbl_dlab ON tbl_hlab.nolaborat=tbl_dlab.nolaborat
                JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
                WHERE tbl_hlab.tgllab BETWEEN '".$start."' AND '".$end."'
                GROUP BY tbl_tarifh.kodetarif;
            ")->result();

            $judul = "Laporan Rekap Laboratorium";

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
                $this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_rekap_laboratorium.PDF', 10, 10, 10, 0);
            } else {
                header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_rekap_laboratorium.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
            }
        }
    }
}
