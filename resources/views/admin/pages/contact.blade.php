@extends("admin.template")
@section("title")
{{$title}}
@endsection
@section("content")
@include("admin.components.infoPage")
@include("admin.components.sidebar")
@include("admin.components.breadcrumb")
@if(count($listData))
<div class='panel panel-default grid'>
   <table class='table'>
      <thead>
         <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Nội dung tin nhắn</th>
            <th>Ngày gửi</th>
            <th><i class="fa fa-trash"></i></th>
         </tr>
      </thead>
      <tbody align="center">
         @foreach($listData as $key => $data)
         <tr>
            <td>{{$key+1}}</td>
            <td>{{$data->title}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->content}}</td>
            <td>{{date("d/m/Y H:i:s", $data->time)}}</td>
            <td>
               <a {{linkDelId($data->id)}}><i class="fa fa-close"></i> Xóa</a>
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
</div>
@endif
<form role="form" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="table" value="menu"/>
    <input type="hidden" name="id" value="{{$menuPage->id}}">
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
@endsection