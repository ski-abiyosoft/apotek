<?php

namespace Application\Requests;

require 'Request.php';

class PharmSettingStoreRequest extends Request
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function set_rules(): array
    {
        return [
            [
                'field' => 'koders',
                'label' => 'Cabang',
                'rules' => 'is_unique[tbl_hset_farma.koders]|required|alpha_numeric|max_length[3]'
            ],
            [
                'field' => 'uang_r',
                'label' => 'Uang Resep',
                'rules' => 'required|numeric|greater_than[0]'
            ],
            [
                'field' => 'uang_racik',
                'label' => 'Uang Racik',
                'rules' => 'required|numeric|greater_than[0]'
            ],
        ];
    }
}