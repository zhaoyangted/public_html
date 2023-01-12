<nav class="pd-catalog" role="navigation">
  <ul class="nav__list">
    <?$i=1;foreach ($this->Menu as $mkey => $mvalue):?>
      <li>
        <input id="group-<?echo $i?>" type="checkbox" hidden />
        <label for="group-<?echo $i?>"><span class="fas fa-angle-right"></span><?echo $mkey?></label>
        <ul class="group-list">
          <?foreach ($mvalue as $key => $value):
              if(!empty($value['Subdata'])):
          ?>
            <li>
              <input id="sub-group-<?echo $key?>" type="checkbox" hidden />
              <label for="sub-group-<?echo $key?>"><span class="fas fa-angle-right"></span><?echo $value['d_title']?></label>
              <ul class="sub-group-list">
                <?foreach ($value['Subdata'] as $tvalue):?>
                  <li><a href="<?echo site_url('products/products_list/'.$tvalue['d_id'].'')?>"><?echo $tvalue['d_title']?></a></li>
                <?endforeach;?>
              </ul>
            </li>
          <?else:?>
            <li><a href="<?echo site_url('products/products_list/'.$value['d_id'].'')?>"><?echo $value['d_title']?></a></li>
          <?endif;$i++;endforeach;?>
        </ul>
      </li>
    <?endforeach;?>
  </ul>
</nav>

<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/pd-catalog.css')?>"/>
