<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export 
{
    function pdf($form='',$uk='' , $judul='',$isi='',$jdlsave='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$tMargin='')
    {
        ini_set("memory_limit", "-1");
		ini_set("pcre.backtrack_limit", "100000000");
        ini_set("max_exwcution_time","300");
		
        $jam = date("H:i:s");
		if ($hal==''){
			$hal1=1;
		} 
		if($hal!==''){
			$hal1=$hal;
		}
		if ($font==''){
			$size=12;
		}else{
			$size=$font;
		} 

		if ($tMargin=='' ){
			$tMargin=16;
		}
		
		if($lMargin==''){
			$lMargin=15;
		}

		if($rMargin==''){
			$rMargin=15;
		}

        $this->mpdf = new \Mpdf\Mpdf( array(190,236),$size,'',$lMargin,$rMargin,$tMargin);

        $this->mpdf->AddPage($form,$uk);

		$this->mpdf->SetFooter('Tercetak {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');

		$this->mpdf->setTitle($judul);

        if (strlen($isi) > 100000){
            $pieces = ceil(strlen($isi) / 100000);

            for ($i = 0; $i < $pieces; $i++){
                $piece = substr($isi, ($i * 100000), 100000);

                if ($i == 0){
                    $this->mpdf->WriteHTML($piece, \Mpdf\HTMLParserMode::DEFAULT_MODE, true, false);
                    continue;
                }
                if ($i == $pieces - 1){
                    $this->mpdf->WriteHTML($piece, \Mpdf\HTMLParserMode::DEFAULT_MODE, false, true);
                    continue;
                }
                $this->mpdf->WriteHTML($piece, \Mpdf\HTMLParserMode::DEFAULT_MODE, false, false);
            }
        }else {
            $this->mpdf->WriteHTML($isi);
        }

		$this->mpdf->output($jdlsave,'I');
    }
}