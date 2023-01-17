<?php
/**
 * Base class for this library repository
 * 
 * Author: tripletTrouble (DPS) https://github.com/triplettrouble
 */

 class Repository
 {
    private $_CI;
    private $db;
    
    /**
     * Constructor method, here we load all CI core system such as, database, or something
     * 
     */
    public function __construct()
    {
        $this->_CI  = &get_instance();

        // Bind CI instance to this class property
        $this->db   = $this->_CI->db;
    }

    /**
     * Method for saving data into database, we use id as primary key, so please sure that
     * your table has id column
     * 
     * @param stdClass $data_set
     * @return object
     */
    public function save(stdClass $data_set)
    {
        // If the $data_set has id property
        if (property_exists($data_set, 'id')){
            $id = $data_set->id;
            unset($data_set->id);

            // Update database
            return $this->db->update($this->table, $data_set, ['id' => $id]);
        }

        // Otherwise, insert into table
        return $this->db->insert($this->table, $data_set);
    }

    /**
     * Method for updating data in the table, this method is created for handling table that 
     * its primary key is not ID
     * 
     * @param stdClass $data_set
     * @param array $where_clause
     * @return bool
     */
    public function update(stdClass $data_set, array $where_clause): bool
    {
        return $this->db->update($this->table, $data_set, $where_clause);
    }

    /**
     * Method for handling save or update based on given data
     * 
     * @param stdClass $data_set
     * @param array $unique_column
     */
    public function save_or_update (stdClass $data_set, array $unique_column)
    {
        $where_clause = [];
        $column_str   = implode(",", $unique_column);

        foreach ($unique_column as $value) {
            $where_clause[$value] = $data_set->$value;
        }

        $is_exists = $this->db->select($column_str)->where($where_clause)->get($this->table)->row();

        if ($is_exists) {
            return $this->update($data_set, $where_clause);
        }

        return $this->save($data_set);
    }

    /**
     * Method for getting all data from database
     * 
     * @param int $limit, $offset
     * @return array
     */
    public function findAll($limit = NULL, $offset = NULL)
    {
        return $this->db->get($this->table, $limit, $offset)->result();
    }

    /**
     * Method for getting record from table using its id
     * 
     * @param int $id
     * @return mixed stdClass || null
     */
    public function find(int $id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    /**
     * Method for starting select query
     * 
     * @param string $select_query
     * @return db
     */
    public function select(string $select_query = '*')
    {
        return $this->db->select($select_query)->from($this->table);
    }

    /**
     * Method for getting list of table fields
     * 
     */
    public function get_fields ()
    {
        return $this->db->list_fields($this->table);
    }

    /**
     * Method for processing data table request
     * 
     * @return array
     */
    public function create_data_table(stdClass $request, array $where_clause = []): array
    {
        $sEcho = $request->sEcho;
        $columns = $request->columns;
        $limit = intval($request->iDisplayLength); 
        $offset = intval($request->iDisplayStart); 
        $search_value = $request->sSearch; 
        $order_column = $request->iSortCol_0; 
        $order_type = $request->sSortDir_0;

        $columns_str    = implode(',', $columns);
        $total_count = $this->select("COUNT(*) as jumlah");

        if (count($where_clause) > 0) $total_count = $total_count->where($where_clause);

        $total_count = $total_count->get()->row()->jumlah;

        $filtered_count = $this->select("COUNT(*) as jumlah");

        if (count($where_clause) > 0) $filtered_count = $filtered_count->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($columns as $key => $column) {
                if ($key == 0) $where .= "$column like '%{$search_value}%'";
                else $where .= "or $column like '%{$search_value}%'";
            }

            $filtered_count = $filtered_count->where("($where)");
        }

        $filtered_count = $filtered_count->get()->row()->jumlah;
        $filtered_data  = $this->select("$columns_str");

        if (count($where_clause) > 0) $filtered_data = $filtered_data->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($columns as $key => $column) {
                if ($key == 0) $where .= "$column like '%{$search_value}%'";
                else $where .= "or $column like '%{$search_value}%'";
            }

            $filtered_data = $filtered_data->where("($where)");
        }

        $filtered_data = $filtered_data->limit($limit, $offset)->order_by($columns[$order_column], $order_type)->get()->result();

        return [
            'iTotalRecords' => $total_count,
            'iTotalDisplayRecords' => $filtered_count,
            'sEcho' => intval($sEcho),
            'aaData' => $filtered_data
        ];
    }
 }