<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default grid">
      <div class="panel-heading">
        <button <?=linkAddMenu('0')?> data-name="none" data-file="content">
          <i class="fa fa-plus"></i> Thêm menu
        </button>
        <button class="btn btn-success selectAll" data-target="#tableMenu > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button> 
        <button class="btn btn-danger delAll" data-table="menu"  data-target="#tableMenu >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>
      </div>
      <table class="table" id="tableMenu">
        <thead>
          <tr>
            <th>Loại file</th>
            <th>Tiêu đề</th>
            <th>Fa Icon</th>
            <th>Tên định dạng</th>
            <th width="200px">Loại trang</th>
            <th width="100px">Ẩn</th>
            <th width="50px">Xóa</th>
          </tr>
        </thead>
        <tbody align="center">
          <?php $listMenu = $db->listMenuChild('0');
          foreach($listMenu as $key=>$menu){ if(checkObject($listFile,'file',$menu->file)){ ?>
          <tr data-id="<?=$menu->id?>">
            <td><?=$menu->file?></td>
            <td>
              <input type="text" value="<?=$menu->title ?>" name="listRow[menu][<?=$menu->id ?>][title]" class="form-control" />
            </td>
            <td>
              <input type="text" value="<?=$menu->ico ?>" name="listRow[menu][<?=$menu->id ?>][ico]" class="form-control" />
            </td>
            <td><?=$menu->name ?></td>
            <td>
              <select class="form-control selectIcon" name="listRow[menu][<?=$menu->id ?>][file]">
                <?php foreach($listFile as $file){ ?>
                <option <?=returnWhere('selected',$file->file,$menu->file) ?> value="<?=$file->file ?>">
                  <?=$file->title ?>
                </option>
                <?php } ?>
              </select>
            </td>
            <td>
              <div class="onoffswitch">
                <input type="hidden" name="listRow[menu][<?=$menu->id ?>][hide]" value="0" />
                <input type="checkbox" <?=returnWhere('checked',$menu->hide,1)?> name="listRow[menu][<?=$menu->id ?>][hide]" class="onoffswitch-checkbox" id="switchhide<?=$menu->id ?>" value="1" />
                <label class="onoffswitch-label" for="switchhide<?=$menu->id ?>"></label>
              </div>
            </td>
            <td>
              <button <?=linkDelMenu($menu->id)?>>
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php } }?>
        </tbody>
      </table>
    </div>
  </div>

  <?php
    $listMenu = $db->list_data_where_where_order("menu","menu_parent",0,'hide',0,'pos','ASC');
  ?>
  <div class="col-md-4">
    <fieldset>
      <legend>Danh sách $listMenu hiển thị: </legend>
      <?php foreach($listMenu as $menu){ $nameMenu = 'menu'.ucfirst($menu->file); ?>
      <p><b>$<?=$nameMenu?></b> = <i class="fa fa-list-alt"></i> <?=$menu->title?></p>
      <?php } ?>
    </fieldset>
  </div>
</div>