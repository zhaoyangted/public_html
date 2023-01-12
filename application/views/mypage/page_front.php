<form class="cd-form" method="post" enctype="multipart/form-data" id="search_form">
<input type="hidden" name="ToPage" id="ToPage" value="<?=$CurrectPage?>">
<div class="page" style="margin-top:30px;">
	<?php if($CurrectPage>1):?>
		<a href="javascript:void(0);" onclick="changepage(<?=$CurrectPage-1?>)" class="w3-button w3-hover-red">&lt;</a>
	<?php endif;?>
	<?php if($TotalPage==0):?>
		<a href="javascript:void(0);" value="1" class="w3-button w3-hover-red">1</a>
	<?php else:?>
		<?php for($i=$PageToLink["pstar"];$i<=$PageToLink["pend"];$i++):?>
						<?php if ($i==$CurrectPage): ?>
							<a href="javascript:void(0);" class="current"><?php echo $i;?></a>
						<?php else: ?>
							<a href="javascript:void(0);" onclick="changepage(<?php echo $i;?>)"><?php echo $i;?></a>
						<?php endif; ?>
			<?php endfor;?>
	<?php endif;?>
	<?php if($CurrectPage<$TotalPage):?>
	<a href="javascript:void(0);" onclick="changepage(<?=$CurrectPage+1?>)">&gt;</a>
	<?php endif;?>
</div>
</form>
<script>
function changepage(Topage){
	$('#ToPage').val(Topage);
	$("#search_form").submit();
}
</script>
