<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->autoful->FrontConfig();
        // 網頁標題
        $this->NetTitle = '最新消息';
    }
    // 列表
    public function Index($TID = '0')
    {
        $data = array();
        if (!empty($TID)) {
            $dbdata = $this->mymodel->OneSearchSql('news_type', '*', array('d_id' => $TID, 'd_enable' => "Y"));
            // 各頁面的SEO
            $this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
            $this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
            $this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');
            if (empty($dbdata)) {
                $this->useful->AlertPage('', '操作錯誤');
                return '';
            }
        }
        $data['News_type'] = $this->mymodel->SelectSearch('news_type', '', 'd_id,d_title,d_color', 'where d_enable="Y"', 'd_sort');

        // 20191021-多時間判斷
        $Where = ' and d_date<=now() ';

        $data['NewsData'] = !empty($data['News_type']) ? $this->mymodel->FrontSelectPage('news', '*,SUBSTR(d_date, 1,10) as d_date', 'where d_enable="Y" and TID IN (' . implode(",", array_column($data['News_type'], 'd_id')) . ')' . (!empty($TID) ? ' and TID="' . $TID . '"' : '') . $Where, 'd_sort, d_date desc', 6) : array();
        $data['TID'] = !empty($dbdata) ? $dbdata['d_title'] : '';

        $this->load->view('front/news', $data);
    }
    // 內頁
    public function info($d_id = '')
    {
        $data = array();

        // News
        $dbdata = $this->mymodel->OneSearchSql('news', '*,SUBSTR(d_date, 1,10) as d_date', array('d_id' => $d_id, 'd_enable' => "Y", 'd_date<' => 'now()'));
        echo $dbdata;
        $category = !empty($dbdata) ? $this->mymodel->OneSearchSql('news_type', 'd_id,d_title,d_color', array('d_id' => $dbdata['TID'], 'd_enable' => "Y")) : array();
        if (empty($d_id) || empty($dbdata) || empty($category)) {
            $this->useful->AlertPage('', '操作錯誤');
            return '';
        }

        // 各頁面的SEO
        $this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
        $this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
        $this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');

        $data['category'] = $category;
        $data['dbdata'] = $dbdata;

        $this->load->view('front/news_info', $data);
    }
}
