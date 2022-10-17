<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan_rk_import extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
	}
		
	function import_rk()
	{
		$jumdata=0;
		$bank=$this->input->post('bank'); 
		$pasar=$this->input->post('pasar'); 
		
		$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv',  'text/plain');
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
          
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
           
            fgetcsv($csvFile);			
            
            $this->db->truncate('tr_rk1');
			
			
			$jumdata=1;	
            while(($line = fgetcsv($csvFile)) !== FALSE){	
				if($line[3]=='D')
				{
				  $debet = $line[4];
                  $kredit= 0;				  
				} else{
				  $debet = 0;
                  $kredit= $line[4];				  
				}
			   
				 $data = array(
				'kode_bank' => $bank,
                'kode_unit' => $pasar,				
				'trxid' => $line[0],
                'tanggal' => $line[1] ,
                'keterangan' => $line[6],
                'debet' => str_replace(',','',$debet),
				'kredit' => str_replace(',','',$kredit));
				
				if(!empty($line[1]))
				{
                  $this->db->insert('tr_rk1', $data);
				  
				  if ($this->db->query("select * from tr_rk where trxid = '$line[0]' and tanggal='$line[1]' and keterangan='$line[6]' and kode_unit='$pasar' and kode_bank='$bank'")->num_rows()<1)
				  $this->db->insert('tr_rk', $data);
				  $jumdata++;
				} 			
            }
            
            
            fclose($csvFile);

            $qstring = '?status=succ';
			
			
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
	
	header('location:'.base_url().'keuangan/keuangan_rk_import/'.$qstring);
	}


}
