<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cost_center_api extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->library("dpsAccounting/Services/some_service");
    }

    /**
     * Method for getting all data, we use data table for performance reason.
     * 
     * @return JSON
     */
    public function all ()
    {
        $is_logged_in = $this->session->userdata('level');

        if ($is_logged_in) {
            $result = $this->some_service->create_data_table_response((object) $this->input->get());
            
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(200)
                ->set_output(json_encode($result));
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * Method for saving data into table.
     * 
     * @return stdClass
     */
    public function save()
    {
        $is_logged_in = $this->session->userdata('level');

        if ($is_logged_in) {
            $data_set = (object) [
                "depid" => $this->input->post("depid"),
                "namadep" => $this->input->post("namadep")
            ];
            
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(200)
                ->set_output(json_encode($this->some_service->save($data_set)));
        }else {
            return $this->output
                ->set_content_type("application/json")
                ->set_status_header(403)
                ->set_output(json_encode(["metaCode" => 403, "message" => "Unauthorized action"]));
        }
    }

    /**
     * Method for destroying data from table.
     * 
     * @param int $id
     * @return stdClass
     */
    public function destroy (int $id): stdClass
    {
        return $this->some_service->destroy($id);
    }
}