<?php
class OrderExportful
{

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('MyModel/Webmodel', 'webmodel');
    }

    // 訂單匯出資料轉換  $Isreturn判斷是否為銷退單
    public function GetOrderExport($odata, $Isreturn = false, $Rdata_array = array())
    {
        $data_array = array();
        // 運費
        $freight = $this->CI->mymodel->WriteSql('select d_id,d_num,d_title from freight');
        $Fnum = array_column($freight, 'd_num', 'd_id');
        $Ftitle = array_column($freight, 'd_title', 'd_id');
        //p($odata);die;
        foreach ($odata as $key => $value) {

            $oddata = $BigFID = $Ffreight = array();
            $return_one = $return_total = 0;
            if ($Isreturn) {
                // 訂單運費總計
                $Ftotal = $this->CI->mymodel->WriteSql('select sum(case when d_pfreight_lv!=1 then `d_pfreight` else 0 end) as d_pfreight, sum(case when d_pfreight_lv=1 then `d_total` else 0 end) as d_total, sum(case when d_pfreight_lv=1 and (d_status=3 or d_status=4) then `d_total` else 0 end) as d_Rtotal from orders_detail where OID=' . $value['odid'], '1');
                // 原一般運費(離島加收)
                $One_out = abs($Ftotal['d_pfreight'] - ($value['d_bigfreight'] + $value['d_specfreight'] + $value['d_outisland']));
                // 原訂單內有買過一般運費商品 且 一般運費商品全退光，運費退還
                if ($Ftotal['d_total'] > 0 && $Ftotal['d_Rtotal'] == $Ftotal['d_total']) {
                    // 退還一般運費
                    $return_one = ($value['onefreight'] > 0 ? $value['onefreight'] : 0) + ($value['d_logistics'] == 2 ? $One_out : 0);
                }
            }

            $re_pid = explode(',', $value['re_pid']); // 退貨商品ID
            $all = explode(',', $value['odpid']); // 全商品ID
            // 一般訂單匯出 如有退貨已完成之訂單 或是 銷退單匯出 且退貨商品ID不為空 進行扣除退貨計算
            if (($value['d_orderstatus'] == 4 && !empty($re_pid)) || $Isreturn) {
                $all = array_diff($all, $re_pid); // 排除退貨ID
                $value['d_total'] -= $value['d_return_money'];
                $value['d_usebonus'] -= $value['d_return_reback'];
                $value['obonus'] = $value['d_return_point'];
            }
            // $id = PID+@#+SAID
            foreach ($all as $Dkey => $id) {

                $oddata[$id] = array(
                    'd_model' => explode(',', $value['odmodel'])[$Dkey],
                    'odtitle' => explode(',', $value['odtitle'])[$Dkey],
                    'stcode' => !empty(explode(',', $value['stcode'])[$Dkey]) ? explode(',', $value['stcode'])[$Dkey] : '',
                    'd_num' => explode(',', $value['odnum'])[$Dkey],
                    'd_price' => explode(',', $value['odprice'])[$Dkey],
                    'd_total' => explode(',', $value['odtotal'])[$Dkey],
                    'disprice' => 0, // 銷退金額
                );

                // 運費
                $FID = explode(',', $value['FID'])[$Dkey];
                // 大型運費統整 數量 總金額
                if (explode(',', $value['d_freight'])[$Dkey] != 0) {
                    $BigFID[$FID]['total'] = (empty($BigFID[$FID]['total']) ? 0 : $BigFID[$FID]['total']) + explode(',', $value['d_freight'])[$Dkey];
                    $BigFID[$FID]['count'] = (empty($BigFID[$FID]['count']) ? 0 : $BigFID[$FID]['count']) + explode(',', $value['odnum'])[$Dkey];
                }
            }

            $total = $value['d_total'];

            // 一般運費
            if ($value['onefreight'] != 0 && $return_one == 0) {
                $BigFID[1]['count'] = 1;
                $BigFID[1]['total'] = $value['onefreight'];
            }
            // 運費處理
            foreach ($BigFID as $lv => $F) {
                $Ffreight[$Fnum[$lv]] = array(
                    'd_model' => $Fnum[$lv],
                    'odtitle' => $Ftitle[$lv],
                    'stcode' => 'Z01',
                    'd_num' => $F['count'],
                    'd_price' => $F['total'] / $F['count'],
                    'd_total' => $F['total'],
                    'disprice' => ($total > $F['total']) ? $F['total'] : $total,
                );
                // 銷退單 之前訂單已有，則判定為折讓
                if (isset($Rdata_array[$key][$Fnum[$lv]])) {
                    $Ffreight[$Fnum[$lv]]['disprice'] = $Rdata_array[$key][$Fnum[$lv]]['disprice'] - $Ffreight[$Fnum[$lv]]['disprice'];
                    $Ffreight[$Fnum[$lv]]['type'] = 2;
                    $Ffreight[$Fnum[$lv]]['d_num'] = 0;
                } else {
                    $Ffreight[$Fnum[$lv]]['type'] = 1;
                }
                // 先扣除運費
                $total = ($total > $F['total']) ? $total - $F['total'] : 0;
            }

            // 得出實際付出現金 (已扣運費)，算出銷退金額
            $BonusArray = json_decode($value['d_bonusarr'], true);
            if (!empty($BonusArray)) {
                if (($value['d_orderstatus'] == 4 && !empty($re_pid)) || $Isreturn) {
                    $Disprice = $this->CI->autoful->CountBonus($BonusArray, $total, $re_pid, true);
                } else {
                    $Disprice = $this->CI->autoful->CountBonus($BonusArray, $total, array(), true);
                }
            } else {
                $Disprice['discount'] = array();
            }

            $oddata = $oddata + $Ffreight;

            foreach ($Disprice['discount'] as $pid => $dis) {
                // 銷退單 判定發生折讓之商品ID
                if (isset($Rdata_array[$key][$pid])) {
                    $oddata[$pid]['disprice'] = $Rdata_array[$key][$pid]['disprice'] - $dis;
                    $oddata[$pid]['type'] = 2;
                    unset($Rdata_array[$key][$pid]);
                    // 銷退單 排除不在退貨ID內之商品ID
                } else if ($Isreturn && !in_array($pid, $re_pid)) {
                    unset($Rdata_array[$key][$pid]);
                } else {
                    if(!empty($oddata[$pid])){
                        $oddata[$pid]['disprice'] = $dis;
                        $oddata[$pid]['type'] = 1;
                    }
                }
                // 排除銷退金額 < 0
                if ($Isreturn && $oddata[$pid]['disprice'] <= 0) {
                    unset($oddata[$pid]);
                }
            }

            // 排序
            if (!empty($Rdata_array)) {
                $oddata = $oddata + $Rdata_array[$key];
                ksort($oddata);
                $oddata = array_reverse($oddata);
            }
            // 銷退總金額
            $return_total = array_column($oddata, 'disprice');
            $return_total = array_sum($return_total);

            // 部門代號
            $department = $value['de_code'];
            if (empty($department)) {
              $Dcode = array(
                  '1420' => ['基隆市', '台北市', '臺北市', '新北市', '桃園縣', '新竹縣', '宜蘭縣', '花蓮縣', '台東縣', '臺東縣', '新竹市', '桃園市'],
                  '1520' => ['嘉義縣', '台南市', '臺南市', '高雄市', '屏東縣', '嘉義市'],
                  '1620' => ['苗栗縣', '雲林縣', '台中市', '臺中市', '彰化縣', '南投縣'],
              );
              foreach ($Dcode as $c => $addre) {
                  if (in_array($value['d_city'], $addre)) {
                      $department = $c;
                      break;
                  }
              }
          }

          $ExportDetail = array(
            '2202',
            $value['d_mcode'],
            $department,
            $value['sale_code'],
            'NTD',
            $value['cash_code'],
            ($value['d_pay'] == 3 ? $value['d_total'] : 0),
            ($value['d_invoice'] == 3 ? $value['d_icname'] : ''),    // （2聯發票不顯示）全名
            ($value['d_invoice'] == 3 ? $value['d_ium'] : ''),       // （2聯發票不顯示）統編
            $value['d_zip'] . $value['d_city'] . $value['d_area'] . $value['d_address'],
            $value['d_name'],
            $value['d_moblie'],
            $value['d_phone'],
            $value['d_content'],
            $value['OID'],
            $value['d_mphone'],
            $value['d_company_tel_area'] . $value['d_company_tel'],
            $value['d_mcode'],
            $value['d_pname'],
            $value['d_account'],
            $value['d_account'],
            $value['obonus'],
            $value['d_usebonus'],
            $value['pctitle'],
            $value['d_remit_account'],
        );
          if ($Isreturn) {
            array_unshift($ExportDetail, '2401', $value['RID']);
            array_push($ExportDetail, $return_total, $value['d_return_time'], ($value['d_return_total'] != $value['d_return_money'] + $value['d_return_reback'] ? 'Y' : 'N'));
        } else {
            array_push($ExportDetail, $value['d_total'], $value['Ocreate_time']);
        }

        array_push($ExportDetail, $oddata);

        $data_array[] = $ExportDetail;
    }
    return $data_array;
}

    // 下載EXCEL(自訂表單匯出)
public function DownExcel($data_array = array(), $filename, $Type = '', $excelTemplate, $Isreturn = false)
{
    if ($Isreturn) {
        $Prow = 30;
    } else {
        $Prow = 27;
    }

        // 清空輸出緩沖區
    if (ob_get_length()) {
        ob_end_clean();
    }

        //欄位矩陣
    $row_n = array(
        '0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E',
        '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J',
        '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O',
        '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T',
        '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z',
    );

        // 載入PHPExcel類庫
    $this->CI->load->library('PHPExcel');
    $this->CI->load->library('PHPExcel/IOFactory');

        // 判斷 Excel 檔案是否存在
    if (!file_exists($excelTemplate)) {
        exit('Please run template.php first.' . EOL);
    }

        // 載入 Excel
    $objPHPExcel = IOFactory::load($excelTemplate);
        // 從第二行開始輸出數據內容
    $row = 2;

    foreach ($data_array as $key => $value) {

        foreach ($value as $pdkey => $pdvalue) {
            if ($pdkey != $Prow) {
                for ($i = 0; $i < count($value[$Prow]); $i++) {
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey, $row + $i)->setValueExplicit($pdvalue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            } else {
                foreach ($pdvalue as $pkey => $pvalue) {
                    if(!empty($pvalue['d_model'])){
                            // 商品處理
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey, $row)->setValueExplicit($pvalue['d_model'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 1, $row)->setValueExplicit($pvalue['odtitle'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 2, $row)->setValueExplicit($pvalue['stcode'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 3, $row)->setValueExplicit($pvalue['d_num'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 4, $row)->setValueExplicit($pvalue['d_price'], PHPExcel_Cell_DataType::TYPE_STRING);
                        if ($Isreturn) {
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 5, $row)->setValueExplicit($pvalue['disprice'], PHPExcel_Cell_DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 6, $row)->setValueExplicit($pvalue['type'], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 5, $row)->setValueExplicit($pvalue['d_total'], PHPExcel_Cell_DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey + 6, $row)->setValueExplicit($pvalue['disprice'], PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                        $row++;
                    }
                }
            }
        }

        $row++;
    }
        // 特殊處理
        //輸出excel文件
    $objPHPExcel->setActiveSheetIndex(0);

        // 設置HTTP頭
    if ($Type == 'csv') {
        $Httptype = 'text/x-csv';
        $Filetype = '.csv';
    } else {
        $Httptype = 'application/vnd.ms-excel';
        $Filetype = '.xls';
    }
    header('Content-Type: ' . $Httptype . '; charset=utf-8');
    header('Content-Disposition: attachment;filename="' . mb_convert_encoding($filename, "Big-5", "UTF-8") . $Filetype . '"');
    header('Cache-Control: max-age=0');

        // 第二個參數可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
    $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}
}
