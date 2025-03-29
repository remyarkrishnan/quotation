<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadRequirements_model extends CI_Model {

    private $table = 'tblleadrequirements'; // Table name

    public function __construct() {
        parent::__construct();
    }

    /**
     * Insert new lead requirement
     * @param array $data
     * @return int Inserted ID
     */
    public function add($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get all lead requirements
     * @return array
     */
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get lead requirement by ID
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    /**
     * Update lead requirement
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Delete lead requirement
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
