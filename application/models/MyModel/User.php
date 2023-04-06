<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User extends CI_Model { 
 
    public function __construct() { 
        parent::__construct(); 
         
        // Load database library 
        $this->load->database(); 
 
        // Database table name 
        $this->tbl_name = 'member'; 
    } 
    /* 
     * Login user data 
     */ 
    function getUserByAccount($account = ""){ 
        if(!empty($id)){ 
            $query = $this->db->get_where($this->tbl_name, array('d_account' => $account)); 
            return $query->row_array(); 
        }else{ 
            $query = $this->db->get($this->tbl_name); 
            return $query->result_array(); 
        } 
    } 
    /* 
     * Fetch user data 
     */ 
    function getRows($id = ""){ 
        if(!empty($id)){ 
            $query = $this->db->get_where($this->tbl_name, array('d_id' => $id)); 
            return $query->row_array(); 
        }else{ 
            $query = $this->db->get($this->tbl_name); 
            return $query->result_array(); 
        } 
    } 
     
    /* 
     * Insert user data 
     */ 
    public function insert($data = array()) { 
        if(!array_key_exists('created', $data)){ 
            $data['created'] = date("Y-m-d H:i:s"); 
        } 
        if(!array_key_exists('modified', $data)){ 
            $data['modified'] = date("Y-m-d H:i:s"); 
        } 
        $insert = $this->db->insert($this->tbl_name, $data); 
        if($insert){ 
            return $this->db->insert_id(); 
        }else{ 
            return false; 
        } 
    } 
     
    /* 
     * Update user data 
     */ 
    public function update($data, $id) { 
        if(!empty($data) && !empty($id)){ 
            if(!array_key_exists('modified', $data)){ 
                $data['modified'] = date("Y-m-d H:i:s"); 
            } 
            $update = $this->db->update($this->tbl_name, $data, array('id' => $id)); 
            return $update?true:false; 
        }else{ 
            return false; 
        } 
    } 
     
    /* 
     * Delete user data 
     */ 
    public function delete($id){ 
        $delete = $this->db->delete($this->tbl_name, array('id' => $id)); 
        return $delete?true:false; 
    } 
 
} 