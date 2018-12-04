<?php
  $listData = $db->list_data_order('page','type','ASC');
  $listFile = $db->list_data_where('file','hide','0');
?>

<form role="form" method="POST" enctype="multipart/form-data">
<div class="row">
  <?php foreach($listData as $data){ ?>
  <div class="col-md-6">
    <?php switch ($data->type) {
      case 'img':
        ?>
        <label class="btn btn-info btn-sm" for="input<?=$data->id?>">
          <i class="fa fa-upload"></i>
          Up ảnh <?=$data->title?>
        </label>
        <?php if ($data->content != ''): ?>
        <label for="" onclick="delImgInfo('#input<?=$data->name ?>','#input<?=$data->id ?>','<?= $data->name ?>')" class="btn btn-danger btn-sm">
          <i class="fa fa-trash"></i>
          Xóa
        </label>
        <?php endif ?>
        <div class="clearfix"></div>
        <img class="img-thumbnail" onclick="$('#input<?=$data->id ?>').click();" style="max-height:100px;" id="input<?=$data->name ?>" src="../upload/<?=$data->content?>">        
        <input class="hidden" accept="image/*" name="info[<?=$data->name ?>]" type="file" id="input<?=$data->id ?>" onchange="readIMG(this,'<?='input'.$data->name ?>');"/>
        <?php
        break;
      case 'content':?>
        <label for="input<?=$data->id?>"><?=$data->title?></label>
        <textarea class="ckeditor" name="<?=$data->name?>"><?=$data->content?></textarea>
      <?php break;
      case 'file': ?>
        <label class="btn btn-info btn-sm" for="input<?=$data->id?>">
          <i class="fa fa-upload"></i>
          Up file <?=$data->title?>
        </label>
        <?php if ($data->content != ''): ?>
        <label for="" onclick="delImgInfo('#input<?=$data->name ?>','#input<?=$data->id ?>','<?= $data->name ?>')" class="btn btn-danger btn-sm">
          <i class="fa fa-trash"></i>
          Xóa
        </label>
        <?php endif ?>
        <div class="clearfix"></div>
        <input type="text" readonly="" value="<?php echo baseUrl.'upload/'.$data->content ?>" class="form-control">
        <input class="hidden" name="info[<?=$data->name ?>]" type="file" id="input<?=$data->id ?>" onchange="readIMG(this,'<?='input'.$data->name ?>');"/>
        
      <?php break;
      case 'switch': ?>
        <label for="input<?=$data->id?>"><?php echo $data->title ?></label>
        <div class="onoffswitch">
          <input type="hidden" name="<?=$data->name?>" value="0" />
          <input type="checkbox" <?=returnWhere('checked',$data->content,1) ?> name="<?=$data->name?>" class="onoffswitch-checkbox" id="switch<?=$data->name?>" value="1" />
          <label class="onoffswitch-label" for="switch<?=$data->name?>"></label>
          <p class="hidden"><?=$data->content?></p>
        </div>
      <?php break;
      default:
        ?>
        <label for="input<?=$data->id?>"><?=$data->title?></label>
        <input type="<?=$data->type?>" name="<?=$data->name?>" class="form-control" id="input<?=$data->id?>" value="<?=$data->content?>" placeholder="Nhập nội dung"/>
        <?php
        break;
    } ?>
    <div>
        
    </div>
  </div>
  <?php } ?>
</div>
<div class="row">
  <div class="col-md-12">
    <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
  </div>
</div>
</form>