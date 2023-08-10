<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Brands extends CI_Model { 
 
    public function __construct() { 
        parent::__construct(); 
         
        // Load database library 
        $this->load->database(); 
 
        // Database table name 
        $this->tbl_name = 'products_brand'; 
    } 
    /* 
     * Login brands data 
     */ 
    function getAllBrands($id=''){ 
        $brand_list=array();
        if(!empty($id)){ 
            //$query = $this->db->get_where($this->tbl_name, array('d_id' => $id,'d_enable'=>'Y')); 
            $query = $this->db->query("SELECT d_id, d_title from products_brand WHERE d_id=$id AND d_enable='Y'");
            return $brand_list=$query->row_array(); 
        }else{ 
            $query = $this->db->get_where($this->tbl_name,array('d_enable'=>'Y')); 
            //return $query->result_array(); 
            //print_r ($query->result());
            foreach ($query->result() as $row) {
                //echo $row->d_id;
                $query = $this->db->query("SELECT * from products where BID=$row->d_id and d_enable='Y'");
                //print_r($query->num_rows());
                array_push($brand_list,(object)['img'=>$row->img,'d_id'=>$row->d_id,'d_title'=>$row->d_title,'d_num'=>$query->num_rows()]);
            }
        
        //print_r ($brand_list);
        usort($brand_list,function($a,$b){
            return $b->d_num - $a->d_num;
        });
        //print_r ($list);
        $brand_list=array_slice($brand_list,0,12);
        return $brand_list;}
    } 
    /* 
     * Fetch product data for brand
     */
    function getProductByBrand($bid=''){
        $query = $db->query(`SELECT * FROM products where BID=$bid and d_enable="Y" `);

        return $query->getFieldCount();
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
    public function deleteBrand($id){ 
        $delete = $this->db->delete($this->tbl_name, array('id' => $id)); 
        return $delete?true:false; 
    } 
 
} 