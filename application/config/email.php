<?
//本機使用
if($_SERVER['HTTP_HOST'] == 'localhost'){

  $config['protocol'] = 'mail';
  $config['mailpath'] = '/usr/sbin/sendmail';
  $config['smtp_host'] = 'localhost';
  // $config['smtp_port'] = '25';
}
//正式上線
else{
  $config['protocol'] = 'mail';
  $config['mailpath'] = '/usr/sbin/sendmail';
  $config['smtp_host'] = 'localhost';
  // $config['smtp_port'] = '25';
}
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
