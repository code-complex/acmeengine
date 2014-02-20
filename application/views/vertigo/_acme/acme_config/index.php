 <div>
	<!-- CABEÇALHO DO MÓDULO e MENUS -->
	<div id="module_header">
		<div id="module_rotule" class="inline top">
			<h2 class="inline top font_shadow_gray"><a class="black" href="<?php echo URL_ROOT . '/' . $this->controller ?>"><?php echo lang($this->lang_key_rotule); ?></a></h2>
			<?php if($this->url_img != '') {?>
			<img src="<?php echo $this->url_img ?>" />
			<?php } ?>
		</div>
		<!-- MENUS DO MODULO -->
		<?php if(count($this->menus) > 0){ ?>
		<div id="module_menus" class="inline top">
			<?php foreach($this->menus as $menu) { ?>
			<div class="inline top module_menu_item" title="<?php echo get_value($menu, 'description')?>">
				<?php if(get_value($menu, 'url_img') != '') { ?>
				<img class="inline top" src="<?php echo $this->tag->tag_replace(get_value($menu, 'url_img'))?>" />
				<?php } ?>
				<h6 class="inline top"><a href="<?php echo $this->tag->tag_replace(get_value($menu, 'link'))?>" <?php echo(get_value($menu, 'target') != '' ? 'target="' . get_value($menu, 'target') . '"' : '')?> <?php echo(get_value($menu, 'javascript') != '' ? get_value($menu, 'javascript') : '')?>><?php echo lang(get_value($menu, 'lang_key_rotule'))?></a></h6>
			</div>
			<?php } ?>
		</div>
		<?php } ?>		
	</div>
	
	<!-- DESCRICAO DO MODULO -->
	<div id="module_description"><?php echo nl2br($this->description); ?></div>
	
	<!-- LISTAGEM DE MENUS -->
	<br />
	<h4 class="font_shadow_gray"><?php echo lang('Variáveis e Constantes Registradas')?></h4>
	<hr style="margin-bottom:10px;" />
	<div id="box_group_view">
	<?php 
	$i = 0;
	foreach($configs as $key => $value) { ?>	
		<div <?php echo ($i % 2 == 0) ? 'class="odd"' : ''; ?>>
			<div id="label_view" class="break_word"><?php echo $key ?></div>
			<div id="field_view" style="max-width:700px;">
			<?php 
				if(is_array($value)) {
					print_r($value);
				} elseif(stristr($key, 'PASS')) {
					if(strlen($value) > 3)
					{
						echo '&bull;&bull;&bull;' . substr($value, 3, (strlen($value) - 3));
					} elseif(strlen($value) > 1) {
						for($i = 0; $i < strlen($value); $i++)
							echo '&bull;';
					}
				} else {
					echo htmlentities($value);
				}
			?>
			</div>
		</div>
	<?php $i++; } ?>
	</div>
</div>