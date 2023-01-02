<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_template_cetak extends CI_Model {
     function __construct()
     {
     parent::__construct();
     $this->load->model("M_cetak");
     }
     function template($judul, $body, $position, $date, $cekpdf)
     {
          $param       = $judul;
          $unit        = $this->session->userdata('unit');
          $avatar      = $this->session->userdata('avatar_cabang');
          $kop         = $this->M_cetak->kop($unit);
          $profile     = data_master('tbl_namers', array('koders' => $unit));
          $namars      = $kop['namars'];
          $alamat      = $kop['alamat'];
          $alamat2     = $kop['alamat2'];
          $alamat3     = $profile->kota;
          $kota        = $kop['kota'];
          $phone       = $kop['phone'];
          $whatsapp    = $kop['whatsapp'];
          $npwp        = $kop['npwp'];
          $chari       = '';
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
               <thead>
                    <tr>
                         <td rowspan=\"6\" align=\"center\">
                              <img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
                         </td>
                         <td colspan=\"20\" style=\"100%\">
                              <b>
                                   <tr>
                                        <td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
                                   </tr>
                                   <tr>
                                        <td style=\"font-size:9px;\">$alamat</td>
                                   </tr>
                                   <tr>
                                        <td style=\"font-size:9px;\">$alamat2</td>
                                   </tr>
                                   <tr>
                                        <td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
                                   </tr>
                                   <tr>
                                        <td style=\"font-size:9px;\">No. NPWP : $npwp</td>
                                   </tr>
                              </b>
                         </td>
                    </tr>
               </table>";
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
                         <td> &nbsp; </td>
                    </tr> 
               </table>";
                                   
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                    <tr>
                         <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                    </tr> 
               </table>";
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                    <tr>
                         <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                    </tr> 
               </table>";
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">     
                    <tr>
                         <td>&nbsp;</td>
                    </tr> 
               </table>";
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                    <tr>
                         <td colspan=\"20\" width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>$param</b></td>
                    </tr>
                    <tr>
                         <td colspan=\"20\" width=\"15%\" style=\"text-align:center; font-size:12px;\">$date</td>
                    </tr>
               </table>";
          $chari .= "
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
                         <td> &nbsp; </td>
                    </tr> 
               </table>";
          $chari .= $body;
          $data['prev']   = $chari;
          $judul          = $param;

          switch ($cekpdf) {
               case 0;
                    echo ("<title>$judul</title>");
                    echo ($chari);
                    break;

               case 1;
                    echo ("<title>$judul</title>");
                    $this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
                    break;

               case 2;
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Content-Type: application/vnd-ms-excel");
                    header("Content-Disposition: attachment; filename= $judul.xls");
                    $this->load->view('app/master_cetak', $data);
                    break;
                    
               case 3;
                    echo ("<title>$judul</title>");
                    echo ($chari);
                    echo "<script>window.print();</script>";
               break;
          }
          // $this->M_cetak->mpdf($position, 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
     }
}