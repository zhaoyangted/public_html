<?php
class Products_sale_edit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // 各專案後臺資料夾
        $AdminName = $this->webmodel->BaseConfig();
        $this->AdminName = $AdminName['d_title'];

        $this->FunctionType = 'edit';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname = $this->tableful->MenuidDb['d_dbname'];
        // 後台基本設定
        $this->autoful->backconfig();
    }

    public function index($d_id)
    {

        if (!empty($d_id)) {

            $data['d_id'] = $d_id;
            $dbdata = $this->mymodel->OneSearchSql($this->DBname, $this->tableful->SqlList, array('d_id' => $d_id));
            $data['dbdata'] = $dbdata;

            // 撈取上層標題
            $Tdata = $this->mymodel->OneSearchSql('products_sale_type', 'd_title,d_start,d_end', array('d_id' => $dbdata['TID']));
            if (empty($Tdata)) {
                $this->useful->AlertPage('', '操作錯誤');
                exit();
            } else if ($Tdata['d_end'] < date('Y-m-d')) { // 專案結束 畫面鎖死
                foreach ($this->tableful->Menu as $k => $v) {
                    if ($v['d_type'] != 9 && $v['d_type'] != 17) {
                        $this->tableful->Menu[$k]['d_type'] = 7;
                    }
                }
                $data['ChkEnd'] = true;
            } else {
                $data['ChkEnd'] = false;
            }

            // 特殊欄位處理
            $this->db->query('SET SESSION group_concat_max_len = 1000000');

            $Pdata = $this->mymodel->Writesql('
						SELECT GROUP_CONCAT(d.PID) as PID
						FROM products_sale s
						LEFT JOIN products_sale_detail d on d.SID=s.d_id
						LEFT JOIN products_sale_type t on t.d_id=s.TID
						where d.PID!="" and ((t.d_start BETWEEN "' . $Tdata['d_start'] . '" AND "'.$Tdata['d_end'].'") or (t.d_end BETWEEN "' . $Tdata['d_start'] . '" AND "'.$Tdata['d_end'].'")) and s.d_id!=' . $d_id . ' and d.d_enable="Y"
						', '1');

            $Where = (!empty($Pdata['PID']) ? ' and d_id not in (' . $Pdata['PID'] . ')' : '');
            $this->tableful->TableTreat(1, $Where, 'd_model');

            $data['Uptitle'] = $Tdata['d_title'];

            // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
            $this->load->view('' . $this->AdminName . '/' . $this->DBname . '/_info', $data);
        } else {
            $this->useful->AlertPage('', '操作錯誤');
        }

    }
    // 編輯
    public function edit()
    {
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel', 'webmodel');
        $check = new CheckInput;
        $url = $dbname = $msg = '';

        /*特殊檢查位置*/
        $Sdata = $this->mymodel->OneSearchSql($this->DBname, 'TID', array('d_id' => $_POST['d_id']));
        $Tdata = $this->mymodel->OneSearchSql('products_sale_type', 'd_title,d_start,d_end', array('d_id' => $Sdata['TID']));

        if ($Tdata['d_end'] < date('Y-m-d')) {
            $this->tableful->Search = array();
        }
        /*特殊檢查位置*/

        foreach ($this->tableful->Search as $key => $value) {
            if ($value[2] == '_CheckFile') {
                if ((empty($_POST['' . $key . '_ImgHidden']) and $value[0] == 8) or (empty($_POST['' . $key . '_Hidden']) and $value[0] == 14)) {
                    $check->fname[] = array($value[2], $key, $value[1]);
                }

            } else {
                $check->fname[] = array($value[2], Comment::SetValue($key), $value[1]);
            }

        }

        $Cck = $check->main('');
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
        // 未結束，可修改商品PID
        if ($Tdata['d_end'] >= date('Y-m-d') && !empty($dbdata['PID'])) {
            // 已開始
            if ($Tdata['d_start'] <= date('Y-m-d')) {
                $details = $this->mymodel->Writesql('SELECT GROUP_CONCAT(PID) as PIDs FROM products_sale_detail where SID=' . $d_id . ' group by SID', '1');
                $InsertPID = implode(',', array_diff($post['PID'], explode(',', $details['PIDs'])));
                if (!empty($InsertPID)) {
                    $Pdata = $this->mymodel->SelectSearch('products', '', 'd_id as PID,d_title,d_model,d_price1,d_price2,d_price3', 'where d_id in(' . $InsertPID . ')');
                    foreach ($Pdata as $p) {
                        $p['SID'] = $d_id;
                        $p['d_create_time'] = date('ymdhis');
                        $this->mymodel->InsertData('products_sale_detail', $p);
                    }
                }
                $this->mymodel->UpdateData('products_sale_detail', array('d_enable' => 'N', 'd_update_time' => date('ymdhis')), ' where SID=' . $d_id . ' and PID not in(' . implode(',', $post['PID']) . ')');
            } else { //未開始
                // 20200908-改寫
                $PID=$post['PID'];
                foreach ($PID as $PIDS) {
                    $PSDdata=$this->mymodel->OneSearchSql('products_sale_detail','d_id,PID',array('SID'=>$d_id,'PID'=>$PIDS));
                    if(empty($PSDdata['d_id'])){
                        $Pdata = $this->mymodel->OneSearchSql('products','d_id as PID,d_title,d_model,d_price1,d_price2,d_price3',array('d_id'=>$PIDS));
                        $Pdata['SID'] = $d_id;
                        $Pdata['d_create_time'] = date('ymdhis');
                        $this->mymodel->InsertData('products_sale_detail', $Pdata);
                    }
                }
                // 20200908-改寫

                $this->mymodel->DelectData('products_sale_detail', ' where SID =' . $d_id . ' and PID not in(' . implode(',', $post['PID']) . ')');
            }
        }
       
        /*特殊檢查位置*/

        $msg = $this->mymodel->UpdateData($dbname, $dbdata, ' where d_id=' . $d_id . '');

        $this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname . '/index/' . $dbdata['TID'], '修改成功');
    }
    // 刪除
    public function deletefile()
    {
        if ($_POST['deltype'] == 'Y') {
            $dbname = $_POST['dbname'];

            // $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'刪除成功');

            // 特殊處理
            $d_id = $_POST['d_id'];
            $Sdata = $this->mymodel->OneSearchSql('products_sale', 'TID,PID', array('d_id' => $d_id));
            if (!empty($Sdata['TID'])) {
                $TID = $Sdata['TID'];
            }

            $Tdata = $this->mymodel->OneSearchSql('products_sale_type', 'd_end', array('d_id' => $TID));
            if (empty($Tdata)) {
                $this->useful->AlertPage('', '操作錯誤');
                exit();
            } else if ($Tdata['d_end'] < date('Y-m-d')) {
                $this->useful->AlertPage('', '活動檔期已結束，為保留數據，故不支援刪除動作');
                exit();
            }

            $this->mymodel->DelectData($dbname, ' where d_id=' . $_POST['d_id'] . '');
            $this->mymodel->DelectData('products_sale_detail', ' where SID =' . $d_id);

            $this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname . '/index/' . $TID . '', '刪除成功');
        } else {
            $this->useful->AlertPage('', '操作錯誤');
        }

    }

}
?>
