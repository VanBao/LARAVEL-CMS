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
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        @php $listFileMenu = array() @endphp 
        @foreach ($listMenuAdminConfig as $menu)
          @if(isset($listFileMenu[$menu->file]))
            @php $listFileMenu[$menu->file]++ @endphp
          @else
            @php $listFileMenu[$menu->file] = 0 @endphp
          @endif
          @php $configMenu2 = $menu->File();@endphp
          @if($configMenu2  && $listFileMenu[$menu->file] < 1 && $menu->file !== 'search' && $menu->file !== '404')
          <li class="@if($menuPage->id == $menu->id) {{'active'}} @endif">
            <a {{linkMenu($menu,'cau-hinh/')}}>
              <i class="{!!getIcon($menu->file)!!}"></i> {{$menu->title}}
            </a>
          </li>
        @endif
        @endforeach
      </ul>
      <div style="padding:10px 0;">
          <div class="tab-pane in active">
             @include($template)
          </div>
      </div>
    </div>
    <div class="col-md-12">
      <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
    </div>
  </div>
</form>
</div>
@endsection
