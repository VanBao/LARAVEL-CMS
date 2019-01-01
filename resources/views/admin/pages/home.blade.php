@extends("admin.template")
@section("title")
{{$title}}
@endsection
@section("content")
@include("admin.components.infoPage")
@include("admin.components.sidebar")
@include("admin.components.breadcrumb")
<div id="content">
  <form role="form" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="table" value="menu"/>
    <input type="hidden" name="id" value="{{$menuPage->id}}">
    @foreach($listImageHome as $key => $value)
    <div class="col-md-12">
      <label class="btn btn-info" style="width:100%;" for="fileListImg{{$key}}">
        <i class="fa fa-picture-o"></i> Up hình {{$value->listTitle}}
      </label>
      <input class="hidden" id="fileListImg{{$key}}" type="file" name="listImageType[{{$key}}][]" multiple="" accept="image/*" />
      <hr>
      @if(count($value->listImg))
      <button class="btn btn-success selectAll" data-target="#{{$key}} > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>  
      <button class="btn btn-danger delAll"  data-target="#{{$key}} >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>

      <div class="box">
        <div class="box-body">
          <table id="{{$key}}" class="table {{$key}}">
            <thead>
              <tr>
                <th width="100px"><i class="fa fa-picture-o"></i> Hình</th>
                <th><i class="fa fa-link"></i> Link</th>
                <th width="100px"><i class="fa fa-trash"></i> Xóa</th>
              </tr>
            </thead>
            <tbody class="sortAjax">
              @foreach($value->listImg as $image)
              <tr align="center" data-name="data" data-id={{$image->id}}">
                <td><img style="height:50px;" {!!srcImg($image)!!} class="img-responsive"></td>
                <td><input type="text" class="form-control" name="listRow[data][{{$image->id}}][link]" value="{{$image->link}}"  /></td>
                <td class="action">
                  <a {!!linkDelId($image->id)!!}><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif
    </div>
    @endforeach
    <div class="col-md-4">
      <label for="inputDes">Mô tả: </label>
      <input id="inputDes" class="form-control" type="text" name="des" value="{{$menuPage->des}}"/>
    </div>
    <div class="col-md-4">
      <label for="inputKeywords">Keywords: </label>
      <input id="inputKeywords" class="form-control" type="text" name="keywords" value="{{$menuPage->keywords}}"/>
    </div>
    <div class="col-md-4">
      <label for="inputKeywords">Đường dẫn: </label>
      <input id="slugId" class="form-control" type="text" name="listSlug[{{$currentSlug->id}}]" value="{{$currentSlug->slugName}}"/>
    </div>
    <div class="col-md-12">
      <label>Giới thiệu: </label>
      <textarea class="ckeditor" name="content">{!!$menuPage->content!!}</textarea>
    </div>
    <div class="col-md-12">
      <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
    </div>
  </form>
</div>
@endsection
