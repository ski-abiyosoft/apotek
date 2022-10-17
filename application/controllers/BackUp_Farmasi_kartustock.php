public function cetak()
{
$cek = $this->session->userdata('level');
$unit = $this->session->userdata('unit');
if(!empty($cek)){
$profile = $this->M_global->_LoadProfileLap();
$nama_usaha=$profile->nama_usaha;
$motto = '';
$alamat= '';
$namaunit = $this->M_global->_namaunit($unit);
//$data = explode("~",$x);
$barang = $this->input->get('barang');
$gudang = $this->input->get('gudang');
$cabang = $this->input->get('cabang');
$tgl1 = $this->input->get('tgl1');
$tgl2 = $this->input->get('tgl2');

$query_cab = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
$query_gud = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();

$namabarang = data_master('tbl_barang', array('kodebarang' => $barang))->namabarang;
//$namabarang = '';
$_tgl1 = date('Y-m-d',strtotime($tgl1));
$_tgl2 = date('Y-m-d',strtotime($tgl2));
$_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
$_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).'
'.date('Y',strtotime($tgl2));

{
$bulan = date('n',strtotime($tgl1));
$tahun = date('Y',strtotime($tgl2));
$query =
"SELECT * from tbl_barangstock
where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang'
-- AND tglso BETWEEN '$tgl1' AND '$tgl2'
AND tglso >'$_tgl1'
";


$lap = $this->db->query($query)->row();

if($lap){
$_tanggalawal = $lap->tglso;
$saldo = $lap->saldoakhir;
} else {
$_tanggalawal = '';
$saldo = 0;
}

$pdf=new simkeu_rpt();
$pdf->setID($nama_usaha,$motto,$alamat);
$pdf->setunit($namaunit);
$pdf->setjudul('KARTU STOCK');
$pdf->setsubjudul($_peri1);
$pdf->addpage("L","A4");
$pdf->setsize("L","A4");

$pdf->SetWidths(array(30,5,100));
$pdf->SetAligns(array('L','C','L'));
$border=array('','','');
$judul=array('Cabang',':',str_replace("KLINIK ESTETIKA dr. Affandi ", "", $query_cab->namars));
$pdf->FancyRow($judul, $border);

$judul=array('Gudang',':',$query_gud->keterangan);
$pdf->FancyRow($judul, $border);
$judul=array('Kode Barang',':',$barang);
$pdf->FancyRow($judul, $border);
$judul=array('Nama Barang',':',$namabarang);
$pdf->FancyRow($judul, $border);
$pdf->ln();
$pdf->SetWidths(array(40,20,30,30,30,20,20,20,30,30));
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
$judul=array('No. Bukti','Tanggal','Keterangan','Rekanan','Nilai Pembelian','Terima','Keluar','Saldo Akhir','Total Nilai
Persediaan','Nilai Persediaan');
$pdf->setfont('Times','B',10);
$pdf->row($judul);



$pdf->SetWidths(array(40,20,30,30,30,20,20,20,30,30));
$pdf->SetAligns(array('C','C','L','L','R','R','R','R','R','R'));
$pdf->setfont('Times','',9);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
$pdf->row(array(
'SALDO',
date('d-m-Y',strtotime($_tanggalawal)),
'SALDO AWAL '.date('d-m-Y',strtotime($_tanggalawal)),
'',
number_format(0,0,'.',','),
number_format(0,0,'.',','),
number_format(0,0,'.',','),
number_format($saldo,0,'.',','),
number_format(0,0,'.',','),
number_format(0,0,'.',',')

));

$mutasi =
"SELECT * from
(select
tbl_apohresep.tglresep as tanggal,
tbl_apohresep.koders,
tbl_apohresep.resepno as nomor,
tbl_apohresep.gudang as gudang,
tbl_apodresep.kodebarang,
0 as terima,
tbl_apodresep.qty as keluar,
tbl_apodresep.qty as qty,
tbl_apodresep.hpp as harga,
tbl_apohresep.rekmed as rekanan,
'PENJUALAN' as keterangan
from tbl_apohresep inner join
tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

union all

select
tbl_baranghreturbeli.retur_date as tanggal,
tbl_baranghreturbeli.koders,
tbl_baranghreturbeli.retur_no as nomor,
tbl_baranghreturbeli.gudang as gudang,
tbl_barangdreturbeli.kodebarang,
0 as terima,
tbl_barangdreturbeli.qty_retur as keluar,
tbl_barangdreturbeli.qty_retur as qty,
tbl_barangdreturbeli.price as harga,
tbl_baranghreturbeli.vendor_id as rekanan,
'RETUR PEMBELIAN' as keterangan
from tbl_baranghreturbeli inner join
tbl_barangdreturbeli on tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no

union all

select
tbl_apohmove.movedate as tanggal,
tbl_apohmove.koders,
tbl_apohmove.moveno as nomor,
tbl_apohmove.dari as gudang,
tbl_apodmove.kodebarang,
0 as terima,
tbl_apodmove.qtymove as keluar,
tbl_apodmove.qtymove as qty,
tbl_apodmove.harga as harga,
tbl_apohmove.mohonno as rekanan,
'MUTASI' as keterangan
from tbl_apohmove inner join
tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno

union all

select
tbl_apohproduksi.tglproduksi as tanggal,
tbl_apohproduksi.koders,
tbl_apohproduksi.prdno as nomor,
tbl_apohproduksi.gudang as gudang,
tbl_apodproduksi.kodebarang,
0 as terima,
tbl_apodproduksi.qty as keluar,
tbl_apodproduksi.qty as qty,
tbl_apodproduksi.harga as harga,
tbl_apohproduksi.gudang as rekanan,
'PRODUKSI' as keterangan
from tbl_apohproduksi inner join tbl_apodproduksi
on tbl_apohproduksi.prdno=tbl_apodproduksi.prdno

union all

select
tbl_apohex.tgl_ed as tanggal,
tbl_apohex.koders,
tbl_apohex.ed_no as nomor,
tbl_apohex.gudang as gudang,
tbl_apodex.kodebarang,
0 as terima,
tbl_apodex.qty as keluar,
tbl_apodex.qty as qty,
tbl_apodex.hpp as harga,
tbl_apohex.keterangan as rekanan,
'BARANG EXPIRE' as keterangan
from tbl_apohex inner join
tbl_apodex on tbl_apohex.ed_no=tbl_apodex.ed_no

union all

select
tbl_baranghterima.terima_date as tanggal,
tbl_baranghterima.koders,
tbl_baranghterima.terima_no as nomor,
tbl_baranghterima.gudang,
tbl_barangdterima.kodebarang,
tbl_barangdterima.qty_terima as terima,
0 keluar,
tbl_barangdterima.qty_terima as qty,
tbl_barangdterima.price as harga,
tbl_vendor.vendor_name as rekanan,
'PEMBELIAN' as keterangan
from tbl_baranghterima inner join
tbl_barangdterima on tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
inner join tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id

union all

select
tbl_apohmove.movedate as tanggal,
tbl_apohmove.koders,
tbl_apohmove.moveno as nomor,
tbl_apohmove.ke as gudang,
tbl_apodmove.kodebarang,
tbl_apodmove.qtymove as terima,
0 as keluar,
tbl_apodmove.qtymove as qty,
tbl_apodmove.harga as harga,
tbl_apohmove.mohonno as rekanan,
'MUTASI' as keterangan
from tbl_apohmove inner join
tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno

union all

select
tbl_apohreturjual.tglretur as tanggal,
tbl_apohreturjual.koders,
tbl_apohreturjual.returno as nomor,
tbl_apohreturjual.gudang as gudang,
tbl_apodreturjual.kodebarang,
tbl_apodreturjual.qtyretur as terima,
0 as keluar,
tbl_apodreturjual.qtyretur as qty,
tbl_apodreturjual.price as harga,
tbl_apohreturjual.rekmed as rekanan,
'RETUR JUAL' as keterangan
from tbl_apohreturjual inner join
tbl_apodreturjual on tbl_apohreturjual.returno=tbl_apodreturjual.returno

union all

select
tbl_apohproduksi.tglproduksi as tanggal,
tbl_apohproduksi.koders,
tbl_apohproduksi.prdno as nomor,
tbl_apohproduksi.gudang as gudang,
tbl_apohproduksi.kodebarang,
tbl_apohproduksi.qtyjadi as terima,
0 as keluar,
tbl_apohproduksi.qtyjadi as qty,
tbl_apohproduksi.hpp as harga,
tbl_apohproduksi.gudang as rekanan,
'PRODUKSI' as keterangan
from tbl_apohproduksi

) mutasi
where kodebarang = '$barang' and
koders = '$cabang' and
gudang = '$gudang' and
tanggal between '$_tgl1' and '$_tgl2'
ORDER BY terima DESC";


$nourut = 1;
$lap = $this->db->query($mutasi)->result();
foreach($lap as $db){
// $saldo = $saldo + $db->terima - $db->keluar ;
if($db->terima > 0){
$salakhir = number_format($saldo = $saldo + $db->keluar - $db->terima,0,'.',',');
} else
if($db->keluar > 0){
$salakhir = number_format($saldo = $saldo + $db->terima - $db->keluar,0,'.',',');
}

$nilai = $db->qty*$db->harga;
$pdf->row(array(
$db->nomor,
date('d-m-Y',strtotime($db->tanggal)),
$db->keterangan,
$db->rekanan,
number_format($nilai,0,'.',','),
number_format($db->terima,0,'.',','),
number_format($db->keluar,0,'.',','),
$salakhir,
number_format(0,0,'.',','),
number_format(0,0,'.',',')

));

$nourut++;
}

$pdf->SetTitle('KARTU STOCK ');
$pdf->AliasNbPages();
$pdf->output('FARMASI_KARTUSTOCK.PDF','I');
}
}
else
{

header('location:'.base_url());

}
}