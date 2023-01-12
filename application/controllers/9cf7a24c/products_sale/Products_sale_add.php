<?php
class Products_sale_add extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 各專案後臺資料夾
        $AdminName = $this->webmodel->BaseConfig();
        $this->AdminName = $AdminName['d_title'];

        $this->FunctionType = 'add';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname = $this->tableful->MenuidDb['d_dbname'];
        //後台基本設定
        $this->autoful->backconfig();

    }

    public function index($TID = '')
    {
        if (empty($TID)) {
            $this->useful->AlertPage('', '操作錯誤');
            exit();
        }

        $data = array();

        // 撈取上層標題
        $Tdata = $this->mymodel->OneSearchSql('products_sale_type', 'd_title,d_start,d_end', array('d_id' => $TID));
        if (empty($Tdata) || $Tdata['d_end'] < date('Y-m-d')) { // 專案結束 不給新增
            $this->useful->AlertPage('', '操作錯誤');
            exit();
        }

        $data['Uptitle'] = $Tdata['d_title'];
        $data['dbdata']['TID'] = $TID;
        $data['ChkEnd'] = false;

        // 特殊欄位處理
        $this->db->query('SET SESSION group_concat_max_len = 1000000');

        $Pdata = $this->mymodel->Writesql('
				SELECT GROUP_CONCAT(d.PID) as PID
				FROM products_sale s
				LEFT JOIN products_sale_detail d on d.SID=s.d_id
				LEFT JOIN products_sale_type t on t.d_id=s.TID
				where d.PID!="" and ((t.d_start BETWEEN "' . $Tdata['d_start'] . '" AND "'.$Tdata['d_end'].'") or (t.d_end BETWEEN "' . $Tdata['d_start'] . '" AND "'.$Tdata['d_end'].'")) and d.d_enable="Y"
				', '1');

        $Where = (!empty($Pdata['PID']) ? ' and d_id not in (' . $Pdata['PID'] . ')' : '');
        $this->tableful->TableTreat(1, $Where, 'd_model');

        // $this->load->view(''.$this->autoful->FileName.'/autopage/_info',$data);
        $this->load->view('' . $this->autoful->FileName . '/' . $this->DBname . '/_info', $data);
    }

    public function add()
    {
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel', 'webmodel');
        $check = new CheckInput;
        $url = $dbname = $msg = '';

        foreach ($this->tableful->Search as $key => $value) {
            if ($value[2] == '_CheckFile') {
                $check->fname[] = array($value[2], $key, $value[1]);
            } else {
                $check->fname[] = array($value[2], Comment::SetValue($key), $value[1]);
            }

        }

        $Cck = $check->main();
        if (!empty($Cck)) {
            echo $check->main($url);
            return '';
        }

        $post = (!empty($_POST)) ? $_POST : '';
        $d_id = (!empty($_POST['d_id'])) ? $_POST['d_id'] : '';
        $dbname = (!empty($_POST['dbname'])) ? $_POST['dbname'] : '';

        $dbdata = $this->useful->DB_Array($post, $d_id);

        $UnsetArray = array('d_id', 'dbname', 'BackPageid');
        $dbdata = $this->useful->UnsetArray($dbdata, $UnsetArray);

        /*特殊檢查位置*/
        $dbdata['PID'] = (!empty($dbdata['PID']) ? implode('@#', $dbdata['PID']) : '');
        /*特殊檢查位置*/

        $msg = $this->mymodel->InsertData($dbname, $dbdata);

        if (!empty($msg)) {
            /*特殊檢查位置*/
            if (!empty($post['PID'])) {
                $NewID = $this->mymodel->create_id;
                $Pdata = $this->mymodel->SelectSearch('products', '', 'd_id as PID,d_title,d_model,d_price1,d_price2,d_price3', 'where d_id in(' . implode(',', $post['PID']) . ')');
                foreach ($Pdata as $p) {
                    $p['SID'] = $NewID;
                    $p['d_create_time'] = date('ymdhis');
                    $this->mymodel->InsertData('products_sale_detail', $p);
                }
            }
            /*特殊檢查位置*/
            $this->useful->AlertPage($this->autoful->FileName . '/' . $dbname . '/' . $dbname . '/index/' . $dbdata['TID'], '新增完成');
        } else {
            $this->useful->AlertPage($this->autoful->FileName . '/' . $dbname . '/' . $dbname . '/index/' . $dbdata['TID'], '新增失敗');
        }
    }
}
