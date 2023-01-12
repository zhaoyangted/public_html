<?php

class Captcha_img {

  public $img_width  = 125;
  public $img_height = 35;
  public $rand_string  = 'QWERTYUPASDFGFGHKZXCBNM1234567890';
  public $word_count;
  public $word       = '';
  public $font       = '/css/main/SourceCodePro-Regular.ttf';
  public $size       = 18;
  private $_tmp_rand_width_count = 0;

  public function __construct()
  {
    $this->word_count = rand( 2 , 3);
  }

  public function rand_word () {

    $rand_count = strlen($this->rand_string) - 1;
    $rand_string  = str_shuffle($this->rand_string);
    for ($i = 0; $i < $this->word_count; $i ++) {

      $this->word .= $rand_string[rand(0, $rand_count)];
    }

    return $this->word;
  }

  private function rand_color () {

    return rand(0, 125);
  }

  private function rand_width() {

    $count = $this->_tmp_rand_width_count;
    $this->_tmp_rand_width_count ++;
    $start = ((int)$this->img_width / $this->word_count) * $count + 5;
    $end   = ((int)$this->img_width / $this->word_count) * ($count + 1) - 20;
    
    return rand($start, $end);
  }

  private function rand_height() {

    return rand((int)$this->img_height, (int)$this->img_height);
  }

  private function rand_angle() {

    return rand(-20, 20);
  }

  public function create_img () 
  {

   header("Content-type: image/PNG");
   $word       = $this->word;

   $im         = imagecreate($this->img_width, $this->img_height);
   $font_color = NUll;
   $bg_color   = imagecolorallocate($im, 255, 255, 255);
   $red = imagecolorallocate($im, 200, 200, 200);
       //邊框 上下左右
   imagefill($im, 0, 0, $bg_color);
   imageline($im, 0, 0, $this->img_width, 0, $red);

   imageline($im, 0, 1, $this->img_width, 1, $red);
   imageline($im, 0, $this->img_height - 1, $this->img_width, $this->img_height - 1, $red);
   imageline($im, 0, $this->img_height - 2, $this->img_width, $this->img_height - 2, $red);

   imageline($im, 0, 0, 0, $this->img_height, $red);
   imageline($im, 1, 0, 1, $this->img_height, $red);

   imageline($im, $this->img_width - 1, 0, $this->img_width - 1, $this->img_height, $red);
   imageline($im, $this->img_width - 2, 0, $this->img_width - 2, $this->img_height, $red);

   for ($i = 0; $i < strlen($this->word); $i++) {

     $font_color = imagecolorallocate($im, $this->rand_color(), $this->rand_color(), $this->rand_color());
     imagettftext($im, $this->size, $this->rand_angle(), $this->rand_width(), $this->img_height -10, $font_color, $this->font, $this->word[$i]);
   }
   
   imagepng($im);
   ImageDestroy($im);
 }
}