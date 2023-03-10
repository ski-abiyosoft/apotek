<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include __DIR__ . "/../Requests/PharmSettingStoreRequest.php";

class Master_seting_r extends CI_Controller {

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('dps/Pharmset_Model','pharm_set');
		$this->load->model('dps/Branch_Model','branch');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1200');
	}

    /**
     * Method for showing all settings data
     */
	public function index() 
    {
		$cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');

		if(!empty($cek)) {
            if ($this->session->userdata("user_level") <= 2) {
                $cabang = $this->branch->select("koders, namars")->get()->result();
            }else {
                $cabang = $this->branch->find($this->session->userdata('unit'));
            }

            $data = [
                'cabang' => $cabang,
                'settings' => $this->pharm_set->select("id, koders, uang_r, uang_racik")->get()->result()
            ];
            
			$this->load->view('master/v_master_uangr', $data);
		} else {
			header('location:'.base_url());
		}			
	}

    /**
     * Method for storing data
     */
    public function store() 
    {
        $cek = $this->session->userdata('level');

        if (!empty($cek)) {
            $request = new Application\Requests\PharmSettingStoreRequest($this->input->post());
            $validated = $request->validated();

            if (isset($validated->message)) {
                return $this->output->set_content_type('application/json')
                        ->set_status_header(422)
                        ->set_output(json_encode($validated));
            }

            $this->pharm_set->koders = $validated['koders'];
            $this->pharm_set->uang_r = $validated['uang_r'];
            $this->pharm_set->uang_racik = $validated['uang_racik'];
            $this->pharm_set->save();

            return $this->output->set_content_type('application/json')
                    ->set_status_header(201)
                    ->set_output(json_encode($this->pharm_set));
        } else {
            header('location:' . base_url());
        }
    }

    /**
     * Method for finding data
     * 
     * @param int $id
     */
    public function show(int $id) 
    {
        return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->pharm_set->find($id)));
    }

    /**
     * Method for updating setting data
     * 
     * @param int $id
     * @return object
     */
    public function update(int $id)
    {
        $cek = $this->session->userdata('level');

        if (!empty($cek)) {
            $settings = $this->pharm_set->find($id);
            $settings->uang_r = $this->input->post('uang_r');
            $settings->uang_racik = $this->input->post('uang_racik');
            $settings->save();

            return $this->output->set_content_type('application/json')
                    ->set_status_header(201)
                    ->set_output(json_encode($settings));
        } else {
            header('location:' . base_url());
        }
    }

    /**
     * Method for destroying data
     * 
     * @param int $id
     */
    public function destroy(int $id) 
    {
        $cek = $this->session->userdata('level');

        if (!empty($cek)) {
            $settings = $this->pharm_set->find($id);
            $settings->destroy();
    
            return $this->output->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($this->pharm_set));
        } else {
            header('location:' . base_url());
        }
    }
}