<?php

namespace Application\Requests;

class Request
{
    private $CI;
    private $validator;
    protected $data_set;

    /**
     * We load CI validation classes here
     */
    public function __construct(array $data)
    {
        $this->CI =& get_instance();
        $this->CI->config->set_item('language', "indonesian");
        $this->CI->load->library('form_validation');
        $this->validator = $this->CI->form_validation;
        $this->data_set = $data;
        $this->validator->set_data($data);
    }

    /**
     * Validate the given data_set
     */
    public function validated()
    {
        $this->validator->set_rules($this->set_rules());

        if ($this->validator->run() === false) {
            return (object) [
                "message" => "Validasi gagal",
                "errors" => $this->validator->error_array()
            ];
        }else {
            return $this->data_set;
        }
    }
}