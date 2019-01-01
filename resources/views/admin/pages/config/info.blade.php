<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default grid">
      <div class="panel-heading">
        <button {!!linkAdd('page')!!}><i class="fa fa-plus"></i> Thêm thông tin</button>
        <button class="btn btn-success selectAll" data-target="#tableMenu > tbody > tr" type="button">
          <i class="fa fa-check-square-o"></i> Chọn tất cả
        </button> 
        <button class="btn btn-danger delAll" data-table="page" data-target="#tableMenu >tbody > tr.selected" type="button">
          <i class="fa fa-trash"></i> Xóa đã chọn
        </button>
      </div>

      <table class="table" id="tableMenu">
        <thead>
          <tr>
            <th>Tiêu đề</th>
            <th>$infoPage-></th>
            <th width="150px">Loại nội dung</th>
            <th>Giá trị</th>
            <th width="50px">Xóa</th>
          </tr>
        </thead>
        <tbody align="center">
        @foreach($listData as $data)  
          <tr data-id="<?=$data->id?>">
            <td>
              <input class="form-control" type="text" name="listRow[page][{{$data->id}}][title]" value="{{$data->title}}">
            </td>
            <td>
              <input class="form-control" type="text" name="listRow[page][{{$data->id}}][name]" value="{{$data->name}}">
            </td>
            <td class="form-inline">
              <div class="form-group">
                <select class="form-control" name="listRow[page][<?=$data->id?>][type]" >
                  @foreach($listType as $type)
                  <option @if($type == $data->type) {{'selected'}} @endif>
                    {{$type}}
                  </option>
                  @endforeach
                </select>
              </div>
            </td>
            <td>
              <input class="form-control" type="text" name="listRow[page][{{$data->id}}][content]" value="{{$data->content}}">
            </td>
            <td>
              <button {!!linkDelId($data->id, 'page')!!}>
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>