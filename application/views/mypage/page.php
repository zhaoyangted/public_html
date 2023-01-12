<input type="hidden" name="ToPage" id="ToPage" value="<?=$CurrectPage?>">
<nav aria-label="Page navigation example" class="page">
	<ul class="pagination">
	<? if($CurrectPage>1):?>
		<li class="page-item">
		  <a class="page-link" href="javascript:void(0);" onclick="changepage(<?=$CurrectPage-1?>)" aria-label="Previous">
		    <span aria-hidden="true">&laquo;</span>
		    <span class="sr-only">Previous</span>
		  </a>
		</li>
	<?endif;if($TotalPage==0):?>
		<li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
	<?else:for($i=$PageToLink["pstar"];$i<=$PageToLink["pend"];$i++):?>
	<?php if ($i==$CurrectPage): ?>
		<li class="page-item active"><a class="page-link" href="javascript:void(0);"><?php echo $i;?></a></li>
	<?php else: ?>
		<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changepage(<?php echo $i;?>)"><?php echo $i;?></a></li>
	<?php endif; ?>
	<? endfor;endif;if($CurrectPage<$TotalPage):?>
		<li class="page-item">
		  <a class="page-link" href="javascript:void(0);" onclick="changepage(<?=$CurrectPage+1?>)" aria-label="Next">
		    <span aria-hidden="true">&raquo;</span>
		    <span class="sr-only">Next</span>
		  </a>
		</li>
	<?endif;?>
	</ul>
</nav>
