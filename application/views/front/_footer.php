<footer>
    <div class="footBOX">
        <div class="foot_LinkBox">
            <ul><a href="<? echo site_url('about') ?>">關於美麗平台</a></ul>
            <ul><a href="<? echo site_url('about#position') ?>">公司據點</a></ul>
            <ul><a href="<? echo site_url('news') ?>">最新消息</a></ul>
            <ul><a href="<? echo site_url('qa') ?>">常見問題 Q&A</a></ul>
            <ul><a href="<? echo site_url('cart') ?>">購物流程</a></ul>
            <ul><a href="<? echo site_url('clause') ?>">隱私權條款說明</a></ul>
            <ul><a href="<? echo site_url('sitemap') ?>">網站導覽</a></ul>
            <ul><a href="<? echo site_url('contact') ?>">聯絡我們</a></ul>
        </div>
        <div class="FUTALL">
            <div class="domeLogoBox"><img src="<? echo CCODE::DemoPrefix.('/images/front/beautygarage_logo02.svg')?>" alt=""></div>
            <div class="FUT01">
                <? if(!empty($WebConfigData[10])):?>
                    <ul class="foot_ConfTxBx"><img src="<? echo CCODE::DemoPrefix.('/images/front/foot_ic02.svg')?>" alt="">服務專線：<?echo $WebConfigData[10];?></ul>
                <? endif;?>
                <? if(!empty($WebConfigData[11])):?>
                    <ul class="foot_ConfTxBx"><img src="<? echo CCODE::DemoPrefix.('/images/front/foot_ic03.svg')?>" alt="">
                服務時間：<?echo strip_tags($WebConfigData[11]);?></ul>
                <? endif;?>
                <? if(!empty($WebConfigData[12])):?>
                    <ul class="foot_ConfTxBx"><img src="<? echo CCODE::DemoPrefix.('/images/front/foot_ic04.svg')?>" alt="">E-mail：<?echo $WebConfigData[12];?></ul>
                <? endif;?>
            </div>
        </div>
        <div class="foot_Copy">
            <ul>台灣美麗平台股份有限公司 Copyright © 2019 All Right Reserved. </ul>
        </div>
    </div>
</footer>
