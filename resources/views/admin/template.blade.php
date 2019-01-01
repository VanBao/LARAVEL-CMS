@if(!Request::ajax())
<!DOCTYPE html>
<html class='no-js' lang='en' ng-app="myApp">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <base href="{{$baseUrl}}" current-url="{{$currentUrl}}"/>
    <meta charset='utf-8'>
    <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit: @yield('title')</title>
    <meta content='Viettech' name='author'>
    <link href="{{URL::asset('public/admin/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('public/admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('public/admin/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('public/admin/plugins/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/datatables/buttons.dataTables.min.css')}}">
    <link href="{{URL::asset('public/admin/assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('public/admin/assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{URL::asset('public/admin/plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/jQueryUI/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('public/admin/plugins/selectBox/js/bootstrap-multiselect.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/selectBox/css/bootstrap-multiselect.css')}}" type="text/css"/>
</head>
<body class='main page'>
    <!-- Navbar -->
    <div class='navbar navbar-default' id='navbar'>
        <a class='navbar-brand' href=''><i class='fa fa-user'></i> Quản lý nội dung</a>
        <ul class="nav navbar-nav pull-right">
            <li>
                <form class="navbar-form searchAjax">
                    <button type="button" onclick="sitemap(this)" class="btn btn-primary">
                        <i class="fa fa-sitemap"></i> Cập nhật sitemap
                    </button>
                    <input class="form-control" placeholder="Tìm kiếm..." type="text" name="title">
                    <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </li>
            <li><a onclick="window.open('{{url('/')}}');"><i class="fa fa-home"></i> Xem trang chủ</a></li>
            <li><a onclick="viewPage()"><i class="fa fa-eye"></i> Xem trang hiện tại</a></li>
            <li><a href="{{route('admin.logout')}}" ><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div id='wrapper' class="contentAjax">
        @yield("content")
    </div>
    <!-- include plugins -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/admin/plugins/jQueryUI/jquery-ui.css')}}">
    <script src="{{URL::asset('public/admin/plugins/ckeditor/ckeditor.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/shortcut.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/table2excel.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/nprogress/nprogress.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{URL::asset('public/admin/plugins/ace/ace.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/admin/plugins/tag/bootstrap-tagsinput.css')}}">
    <!-- include custom js -->
    <script src="{{URL::asset('public/admin/assets/js/file.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('public/admin/assets/js/style.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('public/admin/assets/js/custom.js')}}" type="text/javascript"></script>
</body>
</html>
@else
@yield("content")
@endif