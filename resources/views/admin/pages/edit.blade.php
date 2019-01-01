@extends("admin.template")
@section("title")
{{$title}}
@endsection
@section("content")
@include("admin.components.infoPage")
@include("admin.components.sidebar")
@include("admin.components.breadcrumb")
<div id="content">
@if(isset($page))
  <form role="form" method="POST" class="form-horizontal" enctype="multipart/form-data">
    <div class="col-md-6">
        <input type="hidden" name="table" value="data"/>
        <input type="hidden" name="id" value="{{$page->id}}"/>
        <div class="form-group">
          <label class="control-label col-md-3">Tiêu đề</label>
          <div class="col-md-9">
            <input class="form-control" value="{{$page->title}}" name="title"/>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">Mô tả</label>
          <div class="col-md-9">
            <input class="form-control" value="{{$page->des}}" name="des"/>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">Đường dẫn</label>
          <div class="col-md-9">
            <input id="slugId" class="form-control" type="text" name="listSlug[{{$currentSlug->id}}]" value="{{$currentSlug->slugName}}"/>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">Keywords</label>
          <div class="col-md-9">
            <input class="form-control" value="{{$page->keywords}}" data-role="tagsinput" name="keywords"/>
            <script src="plugins/tag/bootstrap-tagsinput.js"></script>
          </div>
        </div>
        @if($menuPage->file == 'product')
            @if(($filter))
          <div class="form-group">
            <label class="control-label col-md-3"><?= $filter->title ?></label>
            <div class="col-md-9">
              <select class="form-control filtersBox" multiple onchange="getSelect('filter','.filtersBox')">
                @foreach($listMenuFilter as $menuFilter)
                <optgroup label="{{$menuFilter->title}}">
                @php $listDataFilter = $menuFilter->Data; @endphp 
                @foreach($listDataFilter as $data)
                <option {{returnWhereArray('selected',$data->id,$page->filter)}} value="{{$data->id}}">{{$data->title}}</option>
                @endforeach
                 </optgroup>
                @endforeach
              </select>
            </div>
          </div>
          <input type="hidden" name="filter" value="{{$page->filter}}">
          <script type="text/javascript">
              $(document).ready(function() {
                  $('.filtersBox').multiselect();
                  $('.multiselect').click(function (e) { 
                    e.preventDefault();
                    $('.multiselect-container').toggle(200);
                  });
              });
          </script>  
          @endif
          @endif

        @foreach($configMenu->listF as $data)
          @php $dataCol = $data->col; @endphp
        <div class="form-group">
            @switch ($data->type)
              @case('content')
                <label class="control-label col-md-3">{{$data->title}}</label>
                <div class="col-md-12">
                  <textarea class="ckeditor" name="{{$dataCol}}">{{$page->$dataCol}}</textarea>
                </div>
                @break
              @case('file')
                <label class="control-label col-md-3">{{$data->title}}</label>
                <div class="col-md-9">
                  <input name="{{$dataCol}}" type="file" >
                  <a target="_blank" href="{{URL::asset('storage/app/public/<?=$page->$dataCol?>')}}">{{$page->$dataCol}}</a>
                </div>
              @break
              @default
                <label class="control-label col-md-3">{{$data->title}}</label>
                <div class="col-md-9">
                  <input class="form-control {{$data->type}}" value="{{$page->$dataCol}}" name="{{$dataCol}}" />
                </div>
                @break
            @endswitch
        </div>
        @endforeach
        
        @foreach($configMenu->listCheck as $check) 
          @php $dataCol = $check->col; @endphp
        <div class="form-group">
          <label class="control-label col-md-3" for="switch{{$check->col}}">{{$check->title}}:</label>
          <div class="col-md-9">
            <div class="onoffswitch">
              <input type="hidden" name="{{$check->col}}" value="0" />
              <input type="checkbox" @if(intval($page->$dataCol) == 1) {{'checked'}} @endif  name="{{$check->col}}" class="onoffswitch-checkbox" id="switch{{$check->col}}" value="1" />
              <label class="onoffswitch-label" for="switch{{$check->col}}"></label>
              <p class="hidden">{{$page->$dataCol}}</p>
            </div>
          </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-6">
      <div class="text-center">
        <label class="btn btn-info" style="width:100%;" for="fileImg">
          <i class="fa fa-upload"></i> Ảnh đại diện (Rộng:{{$configMenu->maxWidth}}px Cao:{{$configMenu->maxHeight}}px)
        </label>
        <hr>
        <img height="100" onclick="$('#input{{$page->id}}').click();" id="image{{$page->id}}" src="{{URL::asset('storage/app/public/'.$page->img)}}">
        <input class="hidden" id="fileImg" accept="image/*" name="img" type="file" id="input{{$page->id}}" onchange="readIMG(this,'{{'image'.$page->id}}');">
      </div>
      <hr>
      @if($configMenu->slide)
        <input id="fileListSlide" class="hidden" type="file" name="slideData[]" multiple="" accept="image/*" />
        <label class="btn btn-info" for="fileListSlide"><i class="fa fa-upload"></i> Up hình slide : </label>
        <button class="btn btn-success selectAll" data-target="#tableSlide > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>  
        <button class="btn btn-danger delAll"  data-target="#tableSlide >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>
        <div class="box">
            <div class="box-body">
              <table id="tableSlide" class="table slide">
                <thead>
                  <tr>
                    <th width="10px">#</th>
                    <th width="100px"><i class="fa fa-picture-o"></i> Hình</th>
                    <th>Tiêu đề</th>
                    <th width="100px"><i class="fa fa-trash"></i> Xóa</th>
                  </tr>
                </thead>
                <tbody class="sortAjax">
                @foreach($listSlide as $key=>$data)
                <tr align="center" data-name="data" data-id="{{$data->id}}">
                  <td>{{$key+1}}</td>
                  <td><img style="height:50px;" src="{{URL::asset('storage/app/public/'.$data->img)}}" class="img-responsive"></td>
                  <td><input class="form-control" type="text" name="listRow[data][{{$data->id}}][title]" value="{{$data->title}}" /></td>
                  <td class="action">
                    <a {!!linkDelId($data->id)!!}><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>
      @endif
      @if($configMenu->slide2)
        <input id="fileListSlide2" class="hidden" type="file" name="slide2Data[]" multiple="" accept="image/*" />
        <label class="btn btn-info" for="fileListSlide2"><i class="fa fa-upload"></i> Up hình slide2 : </label>
        <button class="btn btn-success selectAll" data-target="#tableSlide2 > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>  
        <button class="btn btn-danger delAll"  data-target="#tableSlide2 >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>
        <div class="box">
            <div class="box-body">
              <table id="tableSlide2" class="table slide2">
                <thead>
                  <tr>
                    <th width="10px">#</th>
                    <th width="100px"><i class="fa fa-picture-o"></i> Hình</th>
                    <th>Tiêu đề</th>
                    <th width="100px"><i class="fa fa-trash"></i> Xóa</th>
                  </tr>
                </thead>
                <tbody class="sortAjax">
                @foreach($listSlide2 as $key=>$data)
                <tr align="center" data-name="data" data-id="{{$data->id}}">
                  <td>{{$key+1}}</td>
                  <td><img style="height:50px;" src="{{URL::asset('storage/app/public/'.$data->img)}}" class="img-responsive"></td>
                  <td><input class="form-control" type="text" name="listRow[data][{{$data->id}}][title]" value="{{$data->title}}" /></td>
                  <td class="action">
                    <a {!!linkDelId($data->id)!!}><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>
      @endif

    </div>
    @if($configMenu->tab)
    <div class="col-md-12">
        <div>
          <a {!!linkAdd('data', 'parent', $page->id)!!}><i class="fa fa-plus"></i> Thêm tab con</a>
          <br>
          <ul class="nav nav-tabs">
            @foreach($listData as $key=>$data)
              <li class="@if($key == 0) {{'active'}} @endif"><a data-toggle="tab" href="#tab{{$data->id}}">{{$data->title}}</a></li>
            @endforeach
          </ul>
          <br>
          <div class="tab-content">
            @foreach($listData as $key=>$data)
              <div id="tab{{$data->id}}" class="tab-pane fade @if($key == 0) {{'in active'}} @endif">
                <a {!!linkDelId($data->id)!!}><i class="fa fa-trash"></i> Xóa</a>
                <br>
                  <label>Tiêu đề:</label>
                  <input type="text" value="{{$data->title}}" name="listRow[data][{{$data->id}}][title]" class="form-control" /><br>
                  <label>Nội dung:</label>
                  <textarea name="listRow[data][{{$data->id}}][content]" class="ckeditor">{{$data->content}}</textarea><br>
                </div>
           @endforeach
          </div>
        </div>
    </div>
    @else
    <div class="col-md-12">
      <label>Nội dung:</label>
      <textarea class="ckeditor" name="content">{{$page->content}}</textarea>
    </div>
    @endif
  <div class="col-md-12">
    <button type="submit" value="info" class="btn btn-success form-control"> <i class="fa fa-save"></i> Lưu (Alt + S)</button>
  </div>
</form>
@else
@php 
  $colList = ($configMenu->showList)?8:12;
@endphp
<form role="form" method="POST" class="form-horizontal" enctype="multipart/form-data">
  @if($configMenu->showList)
  <div class="col-md-4">
    <div class="panel panel-default grid">
      <div class="panel-heading">
        <span ><i class="fa fa-cog"></i> Quản lí danh mục</span>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          @if($page->menu_parent != 0)
          <div class="form-group">
            <label class="control-label col-md-4">Tiêu đề: </label>
            <input type="hidden" name="table" value="menu"/>
            <input type="hidden" name="id" value="{{$idList}}"/>
            <div class="col-md-8">
              <input type="text" value="{{$page->title}}" name="title" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-4">Đường dẫn: </label>
            <div class="col-md-8">
              <input id="slugId" class="form-control" type="text" name="listSlug[{{$currentSlug->id}}]" value="{{$currentSlug->slugName}}"/>
            </div>
          </div>
          @endif
          @if($configMenu->showImage)
          <div class="form-group">
            <label for="imgMenu" class="control-label col-md-4">
              <center>
                <img style="max-height:50px" src="{{URL::asset('storage/app/public/'.$menuChild->img)}}" />
              </center>
            </label>
            <div class="col-md-8">
              <label class="btn btn-info btn-sm" style="width:100%" for="imgMenu"><i class="fa fa-upload"></i> Up hình danh mục</label>
              <input class="hidden" id="imgMenu" type="file" name="img" class="form-control" />
            </div>
          </div>
          @endif
          <div class="col-md-12"> 
            <a {!!linkAddMenu($menuPage->id)!!}>
              <i class="fa fa-plus"></i> Thêm danh mục con
            </a>
            <ul class="tree">
              <li class="root">
                <ul class="tree sortAjax">
                  @if($configMenu->multiMenu)
                      
                  @else
                     
                  @endif
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  <div class="col-md-{{$colList}}">
      <div class="box">
        @if(($configMenu->showList == '1' && $idList !== $menuPage->id ) || ($configMenu->showList !== '1') && ($configMenu->onlyContent !== '1'))
          <div class="box-header">
            <h3 class="box-title">
              @if(!count($listMenuChild))
              <a {!!linkAdd('data', 'menu', $idList)!!}>
                <i class="fa fa-plus"></i> Thêm bài viết : {{$menuChild->title}} ({{count($listData)}})
              </a>
              <div class="pull-right">
                <label for="file-upload" class="custom-file-upload btn btn-info btn-sm">
                    <i class="fa fa-upload"></i> Up dữ liệu (*.xls)
                </label>
                <input class="hidden" id="file-upload" name='importFile' accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" type="file"/>
                <button data-target=".tableData{{$idList}} >tbody > tr.selected" type="button" data-menu="{{$idList}}" class="exportAll btn btn-primary btn-sm">
                  <i class="fa fa-download"></i> Xuất dữ liệu (*.xls)
                </button>
              </div>
              @endif

              @if(count($listData))
              <button class="btn btn-warning btn-sm selectAll" data-target=".tableData{{$idList}} > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>
              <button class="btn btn-danger btn-sm delAll"  data-target=".tableData{{$idList}} >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa <i class="fa-fw fa fa-check-square-o"></i></button>
              @endif
            </h3>
          </div>
        @endif
        @if(count($listData))
          <div class="box-body">
            <table {!!returnWhere('id="tableData" ',$configMenu->orderProduct,0)!!} class="table tableData{{$idList}}">
              <thead>
                <tr>
                  <th width="10px">#</th>
                  <th width="100px"><i class="fa fa-picture-o"></i></th>
                  <th><i class="fa fa-info"></i> Tiêu đề</th>
                  @if($configMenu->listCheck)
                  @foreach($configMenu->listCheck as $check)
                  <th>{{$check->title}}</th>
                  @endforeach
                  @endif
                  <th><i class="fa fa-list"></i></th>
                  <th width="100px"><i class="fa fa-hand-pointer-o"></i></th>
                </tr>
              </thead>
              <tbody {!!returnWhere(' class="sortAjax"',$configMenu->orderProduct,1)!!}>
              @foreach($listData as $key=>$data)
              @php $menuParent = $data->Menu; @endphp
              <tr align="center" data-id="{{$data->id}}">
                <td>{{$key+1}}</td>
                <td><a {!!linkId($data,$menuPage->name, 'admin/')!!}><img style="height:50px;" src="{{URL::asset('storage/app/public/'.$data->img)}}" class="img-responsive"></a></td>
                <td>
                  <input type="text" value="{{$data->title}}" name="listRow[data][{{$data->id}}][title]" class="form-control" />
                  <p class="hidden">{{$data->title}}</p>
                </td>
                @if($configMenu->listCheck)
                @foreach($configMenu->listCheck as $check)
                @php $checkName = $check->col @endphp
                <td>
                  <div class="onoffswitch">
                    <input type="hidden" name="listRow[data][{{$data->id}}][{{$checkName}}]" value="0" />
                    <input type="checkbox" {!!returnWhere('checked',$data->$checkName,1) !!} name="listRow[data][{{$data->id}}][{{$checkName}}]" class="onoffswitch-checkbox" id="switch{{$checkName.$data->id}}" value="1" />
                    <label class="onoffswitch-label" for="switch{{$checkName.$data->id}}"></label>
                    <p class="hidden">{{$data->$checkName}}</p>
                  </div>
                </td>
                @endforeach
                @endif
                <td><a {!!linkMenuChild($menuParent,$name,'admin/')!!} >{{$menuParent->title}}</a></td>
                <td class="action">
                  <a {!!linkId($data, $menuPage->name, 'admin/')!!}} class="btn btn-warning"><i class="fa fa-edit"></i></a>
                  <a {!!linkDelId($data->id)!!}><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
  </div>
  <div class="col-md-12">
    <button type="submit" class="btn btn-success form-control" >
      <i class="fa fa-save"></i> Lưu (Alt + S)
    </button>
  </div>
  @if($menuPage->id == $idList)
    <input type="hidden" name="table" value="menu"/>
    <input type="hidden" name="id" value="{{$menuPage->id}}"/>
    @if($configMenu->showImageMenu)
    <div class="col-md-12">
      <label for="input{{$menuPage->id}}" class="btn btn-info" style="width:100%"><i class="fa fa-upload"></i> Up ảnh danh mục {{$menuPage->title}}</label>
      <img height="100" width="100%" onclick="$('#input{{$menuPage->id}}').click();" id="image{{$menuPage->id}}" src="{{URL::asset('storage/app/public/'.$menuPage->img)}}">
      <input class="hidden" accept="image/*" name="img" type="file" id="input{{$menuPage->id}}" onchange="readIMG(this,'{{'image'.$menuPage->id }}');">
    </div>
    @endif
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
      <label>Nội dung: </label>
      <textarea name="content" class="ckeditor">{{$menuPage->content}}</textarea>
    </div>
    @if($configMenu->onlyContent == '1')
      <div class="col-md-12" style="margin-top:20px;">
        <ul class="nav nav-tabs">
          @foreach($configMenu->listF as $key=>$data)
            <li class="{{returnWhere('active',$key,0)}}"><a data-toggle="tab" href="#tab{{$data->id}}">{{$data->title}}</a></li>
          @endforeach
        </ul>
        <br>
        <div class="tab-content">
          @foreach($configMenu->listF as $key=>$data)
           @php $dataCol = $data->col; @endphp
            <div id="tab{{$data->id}}" class="tab-pane fade {{returnWhere('in active',$key,0)}}">
              <textarea name="{{$dataCol}}" class="ckeditor">{{$page->$dataCol}}</textarea><br>
            </div>
         @endforeach
        </div>
      </div>
    @endif
  @endif
</form>
@endif
</div>
@endsection