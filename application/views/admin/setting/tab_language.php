<?php 
  $list_field_langs = [
      ['name'=>'company'],
      ['name'=>'address'],
      ['name'=>'copyright'],
  ];
 
 ?>
 <style>
 	.flex-box{
 		display: flex;
 		flex-wrap: wrap
 	}
 	.col-flex{
 		flex: auto;
 	}
 	.w-100{
 		width: 100%;
 	}
 </style>
<div class="tab-pane" id="<?= $target ?>">
	<div class="flex-box">
		<?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
		<fieldset class="col-flex">
			<legend for=""><?= $lang_name ?></legend>
			<?php foreach($list_field_langs as $key=>$item): ?>
	            <div class="form-group">
	              <?php 
	                $name = "textlang[{$item['name']}][{$lang_code}]";
	                $value= !empty($textlang[$item['name']][$lang_code])? $textlang[$item['name']][$lang_code] : '';
	                echo form_input($name,$value,['class'=>'form-control w-100','placeholder'=>$item['name']]);
	              ?>
	            </div>
	          <?php endforeach ?>
		</fieldset>
		<?php } ?>
	</div>

</div>