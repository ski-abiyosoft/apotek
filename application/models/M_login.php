<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_login extends CI_Model {

	var $tabel_user="userlogin";
	var $details;
	
	
	function __construct()
	{
	
	parent::__construct();
	}

	function validate_user( $uid, $password, $cabang = null ) {
		$this->db->from( 'userlogin' );
		$this->db->where( 'uidlogin', $uid );
		$this->db->where( 'pwdlogin', md5( $password ) );
		$this->db->where( 'locked', 0 );
		$login = $this->db->get()->result();

		if ( is_array( $login ) && count( $login ) == 1 ) {
			$this->details = $login[ 0 ];
			$this->db->set('lastlogin', date('Y-m-d H:i:s') );
			$this->db->where('uidlogin', $uid);
			$this->db->update('userlogin');
			
			$this->set_session($cabang);
			return true;
		}
		return false;
	}
	
    function set_session($cabang) {
		$avatar_cabang=$this->db->query("SELECT*FROM tbl_namers where koders='$cabang'")->row();
		$this->session->set_userdata( array(
		    'username'       => $this->details->uidlogin,
			'is_logged_in'   => true,
			'nama_lengkap'   => $this->details->username,
			'cabb'  	     => $this->details->cabang,
			'photo'          => $this->details->avatar,
			'level'          => $this->details->level,
			'user_level'     => $this->details->user_level,
			'job_role'       => $this->details->job_role,
			'unit'           => $cabang,
			'shift'          => $shift,
			'promas'         => $promas,
			'avatar_cabang'  => $avatar_cabang->avatar,
			'namars'  		 => $avatar_cabang->namars,
			'lastlogin'      => $this->details->lastlogin,
			'menuapp'        => '',
			'submenuapp'     => '',
			"kdppk"			 => get_kdppk($cabang)
		) );
	}

	function cek_cabang($uname, $ucabang){
		$cek_cabang = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$uname' AND cabang LIKE '%$ucabang%'");

		if($cek_cabang->num_rows() == 0){
			return 0;
		} else {
			return 1;
		}
	}


}
?>