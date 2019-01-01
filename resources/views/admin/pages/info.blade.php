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
  <div class="row">
    @foreach($listData as $data)
    <div class="col-md-6">
      @switch ($data->type)
      @case('img')
      <label class="btn btn-info btn-sm" for="input{{$data->id}}">
        <i class="fa fa-upload"></i>
        Up ảnh {{$data->title}}
      </label>
      @if ($data->content != '')
      <label for="" onclick="delImgInfo('#input{{$data->name}}','#input<?=$data->id ?>','{{$data->name}}')" class="btn btn-danger btn-sm">
        <i class="fa fa-trash"></i>
        Xóa
      </label>
      @endif
      <div class="clearfix"></div>
      <img class="img-thumbnail" onclick="$('#input{{$data->id}}').click();" style="max-height:100px;" id="input{{$data->name}}" {!!srcImg($data, 'content')!!}>        
      <input class="hidden" accept="image/*" name="info[{{$data->name}}]" type="file" id="input{{$data->id}}" onchange="readIMG(this,'{{'input'.$data->name}}');"/>
      @break;
      @case('content')
      <label for="input<?=$data->id?>">{{$data->title}}</label>
      <textarea class="ckeditor" name="listRow[page][{{$data->id}}][content]">{{$data->content}}</textarea>
      @break;
      @case('file')
      <label class="btn btn-info btn-sm" for="input<?=$data->id?>">
        <i class="fa fa-upload"></i>
        Up file {{$data->title}}
      </label>
      @if ($data->content != '')
      <label for="" onclick="delImgInfo('#input{{$data->name}}','#input{{$data->id}}','{{$data->name}}')" class="btn btn-danger btn-sm">
        <i class="fa fa-trash"></i>
        Xóa
      </label>
      @endif
      <div class="clearfix"></div>
      <input type="text" readonly="" value="{{baseUrl.'upload/'.$data->content}}" class="form-control">
      <input class="hidden" name="info[{{$data->name}}]" type="file" id="input{{$data->id}}" onchange="readIMG(this,'{{'input'.$data->name }}');"/>
      @break
      @case('switch')
      <label for="input{{$data->id}}">{{$data->title}}</label>
      <div class="onoffswitch">
        <input type="hidden" name="listRow[page][{{$data->id}}][content]" value="0" />
        <input type="checkbox" @if(intval($data->content) == 1) {{"checked"}} @endif name="listRow[page][{{$data->id}}][content]" class="onoffswitch-checkbox" id="switch{{$data->name}}" value="1" />
        <label class="onoffswitch-label" for="switch{{$data->name}}"></label>
        <p class="hidden">{{$data->content}}</p>
      </div>
      @break
      @default
      <label for="input{{$data->id}}">{{$data->title}}</label>
      <input type="{{$data->type}}" name="listRow[page][{{$data->id}}][content]" class="form-control" id="input{{$data->id}}" value="{{$data->content}}" placeholder="Nhập nội dung"/>
      @break
      @endswitch
    </div>
    @endforeach
  </div>
  <div class="row">
    <div class="col-md-12">
      <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
    </div>
  </div>
</form>
</div>
@endsection
