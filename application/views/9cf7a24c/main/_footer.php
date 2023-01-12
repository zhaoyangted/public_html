<footer>
<div class="foot_wrap">
<div class="foot_wrap_info">
	<span class="text">
		程式維護-<a class="company" href="https://www.jddt.tw/" target="_blank">杰鼎數位科技股份有限公司</a>
	</span>
	<span class="phone"><a><img class="foot-icon" src="<?=CCODE::DemoPrefix."/images/backend/ico_phone.png"?>" alt="">0800-222-262</a></span>
	<span class="mail"><a href="mailto:vip@jddt.tw"><img class="foot-icon" src="<?=CCODE::DemoPrefix."/images/backend/ico_mail.png"?>" alt="">vip@jddt.tw</a></span>
	<span class="qa">
    <form id="support" target="_blank" action="http://support.jddt.tw/login" method="get" style="display:inline;">
      <input type="hidden" name="TOKEN" value="<?php echo !empty($_SESSION[CCODE::ADMIN]) ? 'D788333D2A4FE9BFFA72081E1AAB80EC' : '';?>">
      <a href="javascript:void(0)" onclick="javascript:document.forms['support'].submit()" target="_blank"><img class="foot-icon" src="<?=CCODE::DemoPrefix."/images/backend/ico_qa.png"?>" alt="">技術支援Q&A</a>
    </form>
  </span>
	<a href="https://line.me/R/ti/p/%40kqt5733z"><img height="36" border="0" alt="加入好友" src="<?=CCODE::DemoPrefix."/images/backend/img-line.png"?>"></a>
</div>
	</div>
</footer>
	</body>
	</html>
