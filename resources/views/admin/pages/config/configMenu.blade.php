<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default grid">
      <div class="panel-heading">
        <button {!!linkAddMenu('0')!!} data-name="none" data-file="content">
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
          @foreach($listMenu as $key => $menu) 
           @if(checkObject($listFile,'file',$menu->file))
          <tr data-id="{{$menu->id}}">
            <td>{{$menu->file}}</td>
            <td>
              <input type="text" value="{{$menu->title}}" name="listRow[menu][{{$menu->id}}][title]" class="form-control" />
            </td>
            <td>
              <input type="text" value="{{$menu->ico}}" name="listRow[menu][{{$menu->id}}][ico]" class="form-control" />
            </td>
            <td>{{$menu->name}}</td>
            <td>
              <select class="form-control selectIcon" name="listRow[menu][{{$menu->id}}][file]">
                @foreach($listFile as $file)
                <option @if($file->file == $menu->file) {{'selected'}} @endif value="{{$file->file}}">
                  {{$file->title}}
                </option>
                @endforeach
              </select>
            </td>
            <td>
              <div class="onoffswitch">
                <input type="hidden" name="listRow[menu][{{$menu->id}}][hide]" value="0" />
                <input type="checkbox" @if($menu->hide == 1) {{'checked'}} @endif name="listRow[menu][{{$menu->id}}][hide]" class="onoffswitch-checkbox" id="switchhide{{$menu->id}}" value="1" />
                <label class="onoffswitch-label" for="switchhide{{$menu->id}}"></label>
              </div>
            </td>
            <td>
              <button {!!linkDelId($menu->id, 'menu')!!}>
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-4">
    <fieldset>
      <legend>Danh sách $listMenu hiển thị: </legend>
      @foreach($listMenu2 as $menu)
      <p><b>${{'menu'.ucfirst($menu->file)}}</b> = <i class="fa fa-list-alt"></i> {{$menu->title}}</p>
      @endforeach
    </fieldset>
  </div>
 
</div>