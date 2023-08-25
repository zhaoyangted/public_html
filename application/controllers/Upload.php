<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        //$this->load->library('Mylib/aws3');
    }

    public function index() {
        $this->load->view('front/upload_form', array('error' => ' ' ));
    }

    public function do_upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 100;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
    
        $this->load->library('Mylib/aws3', $config);
    
        if ( ! $this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('upload_form', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());
    
            $file_path = $data['upload_data']['full_path'];
            $file_name = $data['upload_data']['file_name'];
    
            $client = S3Client::factory();
    
            $bucket_name = 'bgtwmedia';
            $key_name = 'uploads/' . $file_name;
    
            try {
                $result = $client->putObject(array(
                    'Bucket' => $bucket_name,
                    'Key'    => $key_name,
                    'SourceFile' => $file_path,
                ));
    
                echo "File uploaded successfully.";
            } catch (Exception $e) {
                echo "Error uploading file: " . $e->getMessage();
            }
        }
    }
}