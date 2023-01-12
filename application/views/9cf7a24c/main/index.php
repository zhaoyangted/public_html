<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/backend/Chart.js')?>"></script>
    <div class="indexView">
        <div class="indexView_block">
            <div class="indexView_chart row">
                <div class="col-md-9 col-sm-8">
                    <div class="indexView_title">
                        <div class="line l-blue"></div>
                        <i class='fas fa-chart-line'></i>
                        <sapn class="top-title">網站瀏覽統計</sapn>
                        <div class="indexView_block_search">
                            <form class="cd-form" method="post" enctype="multipart/form-data">
                                <div class="search-title">欄位搜尋：</div>
                                <select name="year" id="year">
                                    <? for($y=date('Y');$y>=2019;$y--):?>
                                        <option value="<?echo $y?>" <?echo ($y==$ChartData['NowYear'])?'selected':'';?>><?echo $y?>年</option>
                                    <?endfor;?>
                                </select>
                                <select name="month" id="month">
                                    <? for($m=1;$m<=12;$m++):?>
                                        <option value="<?echo $m?>" <?echo ($m==$ChartData['NowMonth'])?'selected':'';?>><?echo $m?>月</option>
                                    <?endfor;?>
                                </select>
                                <div class="search-btn">
                                    <input type="submit" value="查詢" class="inquire">
                                    <input type="button" value="當月" class="other" onclick="javascript:window.location.href='<? echo $_SERVER['REQUEST_URI']?>'">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="indexView_chart_wrap">
                        <canvas id="myChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <div class="indexView_chart_info">
                        <img class="t-deco" src="<?=CCODE::DemoPrefix.('/images/backend/img_peoDeco.png')?>">
                        <div class="title_block"><span>當月各周人數統計</span></div>
                        <ul class="indexView_chart_content">
                          <?php $View_total = array_sum($ChartData['WeekData']['WeekData1']); ?>
                            <?foreach ($ChartData['WeekData']['WeekData1'] as $key => $value):?>
                                <li>
                                    <div class="bar-text">
                                        <span class="week">第<?=$key+1?>周(<?=$ChartData['NowMonth']?>/<?=$ChartData['WeekData']['WeekArray'][$key][0]?>-<?=$ChartData['NowMonth']?>/<?=$ChartData['WeekData']['WeekArray'][$key][1]?>)</span>
                                        <span class="total"><?=$value?>人</span>
                                    </div>
                                    <div class="bar-chart">
                                        <div class="bar-chart_line" style="width:<?php echo empty($value)?0:round(($value/$View_total)*100) ?>%">
                                        </div>
                                    </div>
                                </li>
                            <?endforeach;?>
                        </ul>
                    </div>
                    <div class="indexView_chart_calc">
                        <div class="indexView_chart_bg">
                            <img class="chart_year_pic" src="<?=CCODE::DemoPrefix.('/images/backend/img_stat.png')?>">
                            <div class="indexView_chart_year">
                                <div class="text01"><?=$ChartData['NowYear']?>年網頁瀏覽總人數</div>
                                <div class="text02"><?=$ChartData['AllYear']['Allnum']?>人</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-6">
                <div class="indexView_block space">
                    <div class="indexView_title b-line">
                        <div class="line l-red"></div>
                        <i class='far fa-file-alt'></i>
                        <sapn class="top-title">聯絡表單</sapn>
                        <a href="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/contact/contact');?>" class="indexView_title_more">詳細內容</a>
                    </div>
                    <div class="indexView_contact">
                        <div class="indexView_contact_item">
                            <div class="status-box">
                                <div class="dot unprocessed"></div>
                                <div class="text">未處理</div>
                            </div>
                            <div class="quantity-box"><span class="unprocessed-number"><?=$ContactNum[0]['num']?></span>筆</div>
                        </div>
                        <div class="indexView_contact_item">
                            <div class="status-box">
                                <div class="dot complete"></div>
                                <div class="text">已完成</div>
                            </div>
                            <div class="quantity-box"><span class="complete-number"><?=$ContactNum[1]['num']?></span>筆
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="Order_form" method="post" action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/orders/orders');?>">
              <input type="hidden" name="d_orderstatus" id="orderstatus" value="" />
            </form>
            <div class="col-md-7 col-sm-6">
                <div class="indexView_block indexView_order">
                    <div class="indexView_title b-line">
                        <div class="line l-yellow"></div>
                        <i class='fas fa-money-check-alt'></i>
                        <sapn class="top-title">訂單狀態</sapn>
                        <a href="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/orders/orders');?>" class="indexView_title_more">訂單列表</a>
                    </div>
                    <div class="indexView_order_wrap">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" onclick="Order_link()" class="list-order block02">
                                    <div class="title o-color02">
                                        總訂單數量
                                    </div>
                                    <span class="o-number02">
                                        <?=$OrderNum[0]['num']?>筆
                                    </span>
                                    <span class="oi oi-chevron-right o-more02">
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="Order_link(1)" class="list-order block01">
                                    <div class="title o-color01">
                                        訂單未處理
                                    </div>
                                    <span class="o-number01">
                                        <?=$OrderNum[1]['num']?>筆
                                    </span>
                                    <span class="oi oi-chevron-right o-more01">
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <a href="javascript:void(0)" onclick="Order_link(5)" class="list-order block04">
                                    <div class="title o-color04">
                                        訂單退換貨
                                    </div>
                                    <span class="o-number04">
                                        <?=$OrderNum[2]['num']?>筆
                                    </span>
                                    <span class="oi oi-chevron-right o-more04">
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="Order_link(7)" class="list-order block03">
                                    <div class="title o-color03">
                                        訂單取消
                                    </div>
                                    <span class="o-number03">
                                        <?=$OrderNum[3]['num']?>筆
                                    </span>
                                    <span class="oi oi-chevron-right o-more03">
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li class="last-list">
                                <a href="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/plow/plow');?>" class="list-order block05">
                                    <div class="title o-color05">
                                        低庫存通知
                                    </div>
                                    <span class="o-number05">
                                        <?=$OrderNum[4]['num']?>筆
                                    </span>
                                    <span class="oi oi-chevron-right o-more05">
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <script>
              function Order_link(val) {
                $('#orderstatus').val(val);
                $('#Order_form').submit();
              }
            </script>
            <!-- <div class="col-md-7 col-sm-6">
                <div class="indexView_block indexView_fastLink">
                    <div class="indexView_title b-line">
                        <div class="line l-green"></div>
                        <i class='fas fa-th-large'></i>
                        <sapn class="top-title">快速連結</sapn>
                        <a href="javascript:void(0);" class="indexView_title_more icon-edit">編輯</a>
                    </div>
                    <div class="indexView_fastLink_wrap">
                        <ul>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg01">
                                        <div class="icon_item fast01">
                                            <i class='fas fa-sitemap'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color01">
                                        管理者專區
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg02">
                                        <div class="icon_item fast02">
                                            <i class='fas fa-bullhorn'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color02">
                                        最新消息管理
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg03">
                                        <div class="icon_item fast03">
                                            <i class='fas fa-shopping-cart'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color03">
                                        產品專區管理
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg04">
                                        <div class="icon_item fast04">
                                            <i class='far fa-address-card'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color04">
                                        關於JD
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg05">
                                        <div class="icon_item fast05">
                                            <i class='fas fa-cog'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color05">
                                        系統資料設定
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg06">
                                        <div class="icon_item fast06">
                                            <i class='far fa-handshake'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color06">
                                        合作機會專區
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg07">
                                        <div class="icon_item fast07">
                                            <i class='fas fa-donate'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color07">
                                        投資人管理
                                    </div>
                                </a>
                            </li>
                            <li class="indexView_fastLink_list">
                                <a class="indexView_fastLink_item" href="javascript:void(0);">
                                    <div class="bg_item bg08">
                                        <div class="icon_item fast08">
                                            <i class='	fas fa-plus'>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="indexView_fastLink_item_title t-color08">
                                        新增連結
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <script>
         var ChartDate=[];
         var ChartDate1=[];
        <?foreach ($ChartData['VisitNum'] as $key => $value):?>
            ChartDate.push('<?echo $value['d_date']?>');
            ChartDate1.push('<?echo $value['d_num']?>');
        <?endforeach;?>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ChartDate,
                datasets: [{
                    label: "網站瀏覽人數",
                    backgroundColor: 'rgb(246, 255, 196, .5)',
                    borderColor: 'rgb(73, 141, 155)',
                    data: ChartDate1,
                }]
            },
            options: {}
        });
        var option = {
            responsive: false,
            scales: {
                yAxes: [{
                    stacked: true,
                    gridLines: {
                        display: true,
                        color: "rgba(255,99,132,0.2)"
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        };
    </script>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
