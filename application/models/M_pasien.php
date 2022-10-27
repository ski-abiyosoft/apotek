<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pasien extends CI_Model {

	var $table = 'tbl_pasien';
	var $column_order = array('idtr','koders','namapas','alamat','jkel','handphone','phone','noidentitas',null); 
	var $column_search = array('idtr','koders','namapas','alamat','jkel','handphone','phone','noidentitas');
	var $order = array('idtr' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query( $filter ="")
	{
		$this->db->from($this->table);
				
		if($filter !=""){
		   $param 		= explode('~',$filter);
		   $nama  		= $param[0];
		   $alamat		= $param[1];
		   $noid  		= $param[2];
		   $rekmed		= $param[3];
		   $tglLahir	= $param[4];
		   $noTelp		= $param[5];
		   $nocard		= $param[6];
		
			 
		   if($nama!="" || $alamat!="" || $noid!="" || $rekmed!=""
		   || $tglLahir!="" || $noTelp!="" || $nocard!=""){	 
		   if($nama !=""){	
		     $this->db->like('namapas', $nama);			
		   }
		   if($nocard !=""){			
				$this->db->like('nocard', $nocard);
		   }

           if($alamat !=""){	
		     $this->db->like('alamat', $alamat);			
		   }	

           if($rekmed !=""){	
		     $this->db->like('rekmed', $rekmed);			
		   }		   
		   
		   if($noid !=""){	
		     $this->db->like('noidentitas', $noid);			
		   }	
		   if($tglLahir !=""){	
		     $this->db->like('tgllahir', $tglLahir);			
		   }	
		   if($noTelp !=""){			
			 $this->db->like('handphone', str_replace(' ','', $noTelp));	
			 $this->db->or_like('handphone', '+'.str_replace(' ','', $noTelp));	
		   }
		   } else {
			 $this->db->where('idtr=-1');	  
		   }
		   
		   
		} else {
		   $this->db->where('idtr=-1');	
		}
        
		
		$i = 0;
		
	    
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket

				
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables( $filter )
	{
		// ini_set('memory_limit', '-1');
		$this->_get_datatables_query( $filter );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered( $filter )
	{
		$this->_get_datatables_query( $filter );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all( $filter )
	{
		$this->db->from($this->table);
		
		if($filter !=""){
		   $param = explode('~',$filter);
		   $nama  = $param[0];
		   $alamat= $param[1];
		   $noid  = $param[2];
		   $rekmed= $param[3];
		   $nocard= $param[6];
		
			 
		   if($nama!="" || $alamat!="" || $noid!="" || $rekmed!="" || $nocard!=""){	 
		   if($nama !=""){	
		     $this->db->like('namapas', $nama);			
		   }

           if($alamat !=""){	
		     $this->db->like('alamat', $alamat);			
		   }		   
		   
		   if($rekmed !=""){	
		     $this->db->like('rekmed', $rekmed);			
		   }
		   
		   if($noid !=""){	
		     $this->db->like('noidentitas', $noid);			
		   }		   
		   if($nocard !=""){	
		     $this->db->like('nocard', $nocard);			
		   }
		   } else {
			 $this->db->where('idtr=-1');	  
		   }
		   
		   
		} else {
		   $this->db->where('idtr=-1');	
		}
		
		
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('idtr',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$result = $this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('idtr', $id);
		$this->db->delete($this->table);
	}

}
