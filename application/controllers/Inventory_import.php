<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_import extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul import rekening koran dari file csv
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();			
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '539');	
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');	
		$usr = $this->session->userdata('username');		
		if(!empty($cek))
		{		
												
			$d['merk']    = $this->db->get('inv_merk')->result();
			$d['kateg']   = $this->db->get('inv_kategori')->result();
			
			$this->load->view('inventory/v_import',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
		
	public function import_barang_csv() {
		       $kateg=$this->input->post('kateg');
                //extract($this->xss_html_filter(array_merge($this->data)));
                $filename = $_FILES["file"]["name"];
                
                if($_FILES['file']['size'] > 0)
                {   
                    
                    $config['upload_path']          = './uploads/csv/barang';
                    $config['allowed_types']        = 'csv';
                    $this->load->library('upload', $config);

                    if ( ! $this->upload->do_upload('file')){
                            $error = array('error' => $this->upload->display_errors());
                            print($error['error']);
                            exit();
                    }
                    else{
                            $file_name=$this->upload->data('file_name');
                    }
                    
                  
                    $this->db->delete('inv_import',array(''));
                    $file = fopen('uploads/csv/barang/'.$file_name,"r");
                    
                    //Save flag
                    $flag='true';
                    $this->db->trans_begin();
                    $i=1;
                    while(($importdata = fgetcsv($file, NULL, ",")) !== FALSE){

					    /*
						  0=kode
						  1=nama
						  2=harga beli
						  3=harga jual
						  4=stok
						  5=satuan
						  
						*/
						
                        $data = array(
							'kode' => $importdata[0],
							'nama' => $importdata[1],
							'hpp' => !empty($importdata[2])?$importdata[2]:'0',
							'harga1' => !empty($importdata[3])?$importdata[3]:'0',
							'stok' => !empty($importdata[4])?$importdata[4]:'0',
							'satuan'  => $importdata[5],
							
						);
						
                        if(!empty($importdata[0])){						
						$this->db->insert('inv_import', $data);	
						}
				
				        
						$v_barang = $this->db->get_where('inv_barang', array('kodeitem' => $importdata[0]))->row();
					  
					    if($v_barang){
							$id_barang = $v_barang->kodeitem;
							
							$data_barang = array(
						   'namabarang' => $importdata[1],
						   'hargabeli' => !empty($importdata[2])?$importdata[2]:'',
						   'hargajual' => !empty($importdata[3])?$importdata[3]:'',
						   'stok' => !empty($importdata[4])?$importdata[4]:'',
						   'satuan' => $importdata[5],
						   
						   );
						   $this->db->update('inv_barang', $data_barang, array('kodeitem' => $id_barang));
					  
					  
						} else {
							$row = array(
						       'kdkategori' => $kateg,
						       'kodeitem'   => $importdata[0], 
                               'namabarang' => $importdata[1],
							   'hargabeli'  => !empty($importdata[2])?$importdata[2]:'',
							   'hargajual'  => !empty($importdata[3])?$importdata[3]:'',
							   'stok'       => !empty($importdata[4])?$importdata[4]:'',
							   'satuan'     => $importdata[5],
							   );
							   
							  if(!empty($importdata[0])){
						
								if(!$this->db->insert('inv_barang',$row)){
									$flag='false';
								}
								
								  
							  
								}  
							   
                        
						}
					  
					     
                        
                        
                        
                      
                        
                        //Compulsary records
                        if(empty($importdata[0])){
                          $flag='false';   
                        }


                        
                    }
                    
                    
                    if(!$flag){
                        $this->db->trans_rollback();
                        $qstring = '?status=err';
                    }else{
                        $this->db->trans_commit();
                      
						$qstring = '?status=succ';
                    }
                    fclose($file);
                }
            
            //unlink('uploads/csv/suppliers/'.$file_name);
			header('location:'.base_url().'inventory_import/'.$qstring);
        }	
	
	
	
	
}

