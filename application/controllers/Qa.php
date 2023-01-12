<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qa extends CI_Controller {

	public function __construct(){
		parent::__construct();
        // 前台共用
        $this->autoful->FrontConfig();   
        // 網頁標題
        $this->NetTitle='常見問題 Q&A';
	}
    public function Index(){
        $data=array();
        // HotQa
        $data['HotQaData']=$this->mymodel->SelectSearch('qa','','d_id,d_title','where d_enable="Y" and d_hot="Y"','d_sort');
        // Qa
        $QaData=$this->mymodel->WriteSql('
        	select q.d_id,q.TID,q.d_title,qt.d_title as qttitle
        	from qa q
        	inner join qa_type qt on qt.d_id=q.TID
        	where q.d_enable="Y"
        	order by q.d_sort
        ');
        $QaArray=array();
        foreach ($QaData as $value) {
        	if(!empty($value))
        		$QaArray[$value['qttitle']][]=$value;
        }
        $data['QaData']=$QaArray;
        $this->load->view('front/qa',$data);
    }
    // 列表
    public function qa_list($TID=''){
    	if(empty($TID)){
    		$this->useful->AlertPage('','操作錯誤');
    		exit();
    	}
        $data=array();
        // 標題
        $data['TypeData']=$this->mymodel->OneSearchSql('qa_type','d_title',array('d_id'=>$TID));
        // QA資料
        $data['dbdata']=$this->mymodel->FrontSelectPage('qa','d_id,d_title','where d_enable="Y" and TID='.$TID.'','d_sort','10');

        $this->load->view('front/qa_list',$data);
    }
    // 內頁
    public function info($d_id=''){
    	if(empty($d_id)){
    		$this->useful->AlertPage('','操作錯誤');
    		exit();
    	}
        $data=array();
        // Qa
        $dbdata=$this->mymodel->OneSearchSql('qa','TID,d_title,d_content,d_question',array('d_id'=>$d_id,'d_enable'=>"Y"));
        if(empty($dbdata)){
            $this->useful->AlertPage('','操作錯誤');
            return '';
        }

        // 標題
        $data['TypeData']=$this->mymodel->OneSearchSql('qa_type','d_title',array('d_id'=>$dbdata['TID']));

        $data['dbdata']=$dbdata;
        // 相關問題
        if(!empty($dbdata['d_question'])){
	        $data['Qdata']=$this->mymodel->WriteSql('
	        	select d_id,d_title from qa where d_id in ('.str_replace('@#',',',$dbdata['d_question']).') and d_enable="Y"
	        ');
	    }

        $this->load->view('front/qa_info',$data);
    }
}