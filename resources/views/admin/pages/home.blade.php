<form role="form" method="POST" enctype="multipart/form-data">
  <?php foreach($listImageHome as $listImage){ $listName = $listImage->name; ?> 
    <div class="col-md-6">
      <label class="btn btn-info" style="width:100%;" for="fileListImg<?=$listName?>">
        <i class="fa fa-picture-o"></i> Up hình <?=$listImage->title?> ($list-><?=$listImage->name?>)
      </label>
      <input class="hidden" id="fileListImg<?=$listName?>" type="file" name="listImageType[<?=$listName?>][]" multiple="" accept="image/*" />
      <hr>
      <?php if(isset($list->$listName) && count($list->$listName)){ ?>
      <button class="btn btn-success selectAll" data-target="#<?=$listName?> > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>  
      <button class="btn btn-danger delAll"  data-target="#<?=$listName?> >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>

      <div class="box">
          <div class="box-body">
            <table id="<?=$listName?>" class="table <?=$listName?>">
              <thead>
                <tr>
                  <th width="100px"><i class="fa fa-picture-o"></i> Hình</th>
                  <th><i class="fa fa-link"></i> Link</th>
                  <th width="100px"><i class="fa fa-trash"></i> Xóa</th>
                </tr>
              </thead>
              <tbody class="sortAjax">
              <?php foreach($list->$listName as $data){
              ?>
              <tr align="center" data-name="data" data-id="<?=$data->id ?>">
                <td><img style="height:50px;" src="../upload/<?=$data->img ?>" class="img-responsive"></td>
                <td><input type="text" class="form-control" name="listRow[data][<?=$data->id ?>][link]" value="<?=$data->link ?>"  /></td>
                <td class="action">
                  <a <?=linkDelId($data->id); ?>><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
      </div>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="col-md-6">
  <?php if($configMenu->listF){ foreach($configMenu->listF as $data){ $dataCol = $data->type;?>
  <label><?=$data->title?></label>
    <?php switch ($data->type) {
      case 'ckeditor':
        ?>
        <textarea class="ckeditor" name="<?=$dataCol?>"><?=$menuPage->$dataCol ?></textarea>
        <?php
        break;
      case 'file':
        ?>
        <input name="file" type="file" >
        <p><?=$menuPage->file?></p>
        <?php
        break;
      default:
        ?>
        <input class="form-control <?=$data->type?>" value="<?=$purifier->purify($menuPage->$dataCol)?>" name="<?=$dataCol?>" />
        <?php
        break;
    }?>
  <?php }} ?>
  </div>
  <div class="col-md-4">
      <label for="inputDes">Mô tả: </label>
      <input id="inputDes" class="form-control" type="text" name="des" value="<?=$menuPage->des?>"/>
   </div>
   <div class="col-md-4">
      <label for="inputKeywords">Keywords: </label>
      <input id="inputKeywords" class="form-control" type="text" name="keywords" value="<?=$menuPage->keywords?>"/>
   </div>
   <?php if (canEditSlug): ?>
   <div class="col-md-4">
      <label for="inputKeywords">Đường dẫn: </label>
      <input id="slugId" class="form-control" type="text" name="listSlug[<?=$slugCurrent->id?>]" value="<?=$slugCurrent->slugName?>"/>
   </div>
   <?php endif; ?>
   <div class="col-md-12">
      <label>Giới thiệu: </label>
      <textarea class="ckeditor" name="content"><?=$menuPage->content ?></textarea>
   </div>
   <div class="col-md-12">
      <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
   </div>
</form>