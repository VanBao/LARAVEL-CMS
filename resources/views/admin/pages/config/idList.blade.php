<?php
	$configMenu = $db->alone_data_where('file','file',$page->file);
	$listConfigCheck = $db->list_data_where_where('config','file','idList','type','');
	$listConfigAdd = $db->list_data_where_where('config','file','idList','type','add');
	$listSizeThumbnail = array('maxWidth','maxHeight');
	$listType = array('text','file','content');
	$listCols = $db->listCols('data');
	for($i= 0; $i< 14; $i++){
		unset($listCols[$i]);
	}
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default grid">
			<table class="table notSl">
				<thead>
				  <tr>
				    <th>Mô tả</th>
				    <th>$configMenu->*</th>
				    <th width="100px">Hiển thị</th>
				  </tr>
				</thead>
				<tbody align="center">
					<?php foreach($listConfigCheck as $data){
						$nameConfig = $data->name;
					?>
				 	<tr data-id="<?=$data->id?>" align="center">
				 		<td>
				 			<label class="control-label" for="f<?=$data->id?>"><?=$data->title?>:</label>
				 		</td>
				 		<td>
				 			<?=$nameConfig?>
				 		</td>
				 		<td>
				 			<div class="onoffswitch">
								<input type="hidden" name="listRow[file][<?=$configMenu->id ?>][<?=$nameConfig?>]" value="0" />
								<input type="checkbox" <?=returnWhere('checked',$configMenu->$nameConfig,1) ?> name="listRow[file][<?=$configMenu->id ?>][<?=$nameConfig?>]" class="onoffswitch-checkbox <?=$data->group?>" data-name="<?=$data->name?>" id="f<?=$data->id ?>" value="1" />
								<label class="onoffswitch-label" for="f<?=$data->id ?>"></label>
							</div>
				 		</td>
				 	</tr>
				 	<?php } foreach($listSizeThumbnail as $data){ ?>
				 	<tr>
				 		<td><label class="control-label" for="<?=$data?>">Giới hạn kích thước ảnh thumbnail</label></td>
				 		<td><?=$data?></td>
				 		<td>
				 			<input class="form-control" type="text" name="listRow[file][<?=$configMenu->id ?>][<?=$data?>]" value=<?=$configMenu->$data?>>
				 		</td>
				 	</tr>
					 <?php } ?>
				</tbody>
			</table>

		</div>
		<hr>

		<?php foreach($listConfigAdd as $configAdd){
			$selectType = ($configAdd->name == 'listCheck') ? false : true;
			$listData = $db->list_data_where_where('file_data','parent',$configMenu->id,'group',$configAdd->name);
		?>
		<div class="panel panel-default grid">
			<div class="panel-heading">
		        <button data-group="<?=$configAdd->name?>" <?=linkAdd('file_data','parent',$configMenu->id)?>><i class="fa fa-plus"></i> Thêm <?=$configAdd->title?></button>
		        <button class="btn btn-success selectAll" data-target="#tableMenu<?=$configMenu->id?> > tbody > tr" type="button">
		          <i class="fa fa-check-square-o"></i> Chọn tất cả
		        </button> 
		        <button class="btn btn-danger delAll" data-table="file_data" data-target="#tableMenu<?=$configMenu->id?> >tbody > tr.selected" type="button">
		          <i class="fa fa-trash"></i> Xóa đã chọn
		        </button>
	      	</div>
	      	<?php if(count($listData)){ ?>
			<table class="table" id="tableMenu<?=$configMenu->id?>">
				<thead>
		          <tr>
		            <th>Tiêu đề</th>
		            <th>$page->*</th>
		            <?php if($selectType){ ?>
		            <th width="150px">Loại nội dung</th>
		            <?php } ?>
		            <th width="50px">Xóa</th>
		          </tr>
		        </thead>
		        <tbody align="center">
		        <?php foreach($listData as $data){ ?>    
		          <tr data-id="<?=$data->id?>">
		            <td>
		              <input class="form-control" type="text" name="listRow[file_data][<?=$data->id?>][title]" value="<?=$data->title?>">
		            </td>
		            <td class="form-inline checkSelect">
		              <div class="form-group">
		                <select class="form-control" name="listRow[file_data][<?=$data->id?>][col]" >
		                  <?php foreach($listCols as $col){ ?>
		                  <option <?=returnWhere('selected',$col->Field,$data->col)?>>
		                    <?=$col->Field?>
		                  </option>
		                  <?php } ?>
		                </select>
		              </div>
		            </td>
		            <?php if($selectType){ ?>
		            <td class="form-inline">
		              <div class="form-group">
		                <select class="form-control" name="listRow[file_data][<?=$data->id?>][type]" >
		                  <?php foreach($listType as $type){ ?>
		                  <option <?=returnWhere('selected',$type,$data->type)?>>
		                    <?=$type?>
		                  </option>
		                  <?php } ?>
		                </select>
		              </div>
		            </td>
		            <?php } ?>
		            <td>
		              <button <?=linkDel('file_data',$data->id)?>>
		                <i class="fa fa-trash"></i>
		              </button>
		            </td>
		          </tr>
		        <?php } ?>
		        </tbody>
			</table>
			<?php } ?>
		</div>
		<?php } ?>
	
	</div>
	<div class="col-md-6">
		<div data-show="default">
			<div class="col-md-12">
				<label>Head</label>
				<div id="headHtml" class="codeEditor" style="height:150px;"><?=$configMenu->headHtml?></div>
				<input for="headHtml" type="hidden" name="listRow[file][<?=$configMenu->id?>][headHtml]" value="<?=$configMenu->headHtml?>">
			</div>
			<div class="col-md-12">
				<fieldset>
					<legend>Html danh mục/sản phẩm:</legend>
					<label>List - var_dump($data,$menuParent->name)</label>
					<div id="listHtml" class="codeEditor" style="height:150px;"><?=$configMenu->listHtml?></div>
					<input for="listHtml" type="hidden" name="listRow[file][<?=$configMenu->id?>][listHtml]" value="<?=$configMenu->listHtml?>">

					<label>Box - var_dump($page,$name)</label>
					<div id="boxHtml" class="codeEditor" style="height:200px;"><?=$configMenu->boxHtml?></div>
					<input for="boxHtml" type="hidden" name="listRow[file][<?=$configMenu->id?>][boxHtml]" value="<?=$configMenu->boxHtml?>">
				</fieldset>
			</div>

			<div class="col-md-12">
				<label>Footer</label>
				<div id="footerHtml" class="codeEditor" style="height:150px;"><?=$configMenu->footerHtml?></div>
				<input for="footerHtml" type="hidden" name="listRow[file][<?=$configMenu->id?>][footerHtml]" value="<?=$configMenu->footerHtml?>">
			</div>
		</div>

		<div data-show="customHtml" class="col-md-12">
			<label>Custom Html ($menuPage,$idList,$id)</label>
			<div id="customHtml" class="codeEditor" style="height:650px;"><?=$configMenu->customHtml?></div>
			<input for="customHtml" type="hidden" name="listRow[file][<?=$configMenu->id?>][customHtml]" value="<?=$configMenu->customHtml?>">
		</div>
</div>
<script type="text/javascript">
	$(function(){
		function showTab(){
			$('[data-show]').hide();
			if($('input:checkbox[data-name=customTemplate]').is(":checked") == false){
				$('[data-show=default]').show();
			}else{
				$('[data-show=customHtml]').show();
			}
		}
		showTab();
		$('input:checkbox').change(function(){
			showTab();
		})
		$('input:checkbox.only').click(function(){
			if($(this).is(':checked')){
				$.each($('input:checkbox').not(this).not('.all'),function(index,value){
					$(this).prop('checked', false);
				});
			}
		});
		$('input:checkbox').not('.only').not('.all').click(function(){
			$('input:checkbox.only').prop('checked', false);
		});
	})
</script>