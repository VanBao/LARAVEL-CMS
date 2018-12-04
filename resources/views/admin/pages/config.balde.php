<?php
  $listFile = $db->list_data_where('file','hide','0');
  if(!isset($page)){
    $page = $db->alone_data_where('menu','file','config');
  }
  $filePath = 'include/'.$menuPage->file.'/'.$page->file.'.php'; 
?>

<form role="form" method="POST" enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        <?php $listFileMenu = []; foreach($allListMenu as $menu){
          if(isset($listFileMenu[$menu->file])){
            $listFileMenu[$menu->file]++;
          }else{
            $listFileMenu[$menu->file] = 0;
          }
          $configMenu = $db->alone_data_where('file','file',$menu->file);
          if(!$configMenu || $configMenu->type !=='block' && $listFileMenu[$menu->file] < 1){ ?>
          <li class="<?=returnWhere('active',$page->id,$menu->id)?>">
            <a <?=linkIdList($menu,$name,'cau-hinh/')?>>
              <i class="<?=returnIcon($menu->file)?>"></i> <?=$menu->title?>
            </a>
          </li>
        <?php }} ?>
      </ul>

      <div style="padding:10px 0;">
          <div class="tab-pane in active">
          <?php if(file_exists($filePath)){ include($filePath); }else{
            include('include/'.$menuPage->file.'/idList.php'); } ?>
          </div>
      </div>
    </div>

    <div class="col-md-12">
      <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
    </div>

  </div>
</form>