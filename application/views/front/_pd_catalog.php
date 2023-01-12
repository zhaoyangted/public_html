<nav class="pd-catalog" role="navigation">
    <ul class="nav__list">
      <?foreach ($this->Menu as $key => $value):
          if(!empty($value['Subdata'])):
      ?>
        <li>
          <input id="group-<?echo $key?>" type="checkbox" hidden <?php echo !empty($TypeData)&&($value['d_id']==$TypeData['TTID']||$value['d_id']==$TypeData['d_id']) ? 'checked' :''; ?>/>
          <label for="group-<?echo $key?>"><span class="fas fa-angle-right"></span><?echo $value['d_title']?></label>
          <ul class="group-list">
            <?foreach ($value['Subdata'] as $tvalue):?>
              <li><a href="<?echo site_url('products/products_list/'.$tvalue['d_id'].'')?>"><?echo $tvalue['d_title']?></a></li>
            <?endforeach;?>
          </ul>
        </li>
      <?else:?>
        <li><a href="<?echo site_url('products/products_list/'.$value['d_id'].'')?>"><?echo $value['d_title']?></a></li>
      <?endif;endforeach;?>
    </ul>
  </nav>
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/pd-catalog.css')?>"/>
