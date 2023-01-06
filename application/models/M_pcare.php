<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class M_pcare extends CI_Model{

        public function get_data($_table, $options = "", $columns = ""){
            if($options == "" && $options == ""){
                return $this->db->get($_table);
            } else {
                $column_select  = implode(", ", $columns);

                $this->db->select($column_select)->from($_table);

                if($options->search != ""){
                    foreach($columns as $c){
                        $this->db->or_like($c, $options->search, "both");
                    }
                }

                if($options->length != -1){
                    $this->db->limit($options->length, $options->start);
                }
                
                return $this->db->get();
            }
        }

    }