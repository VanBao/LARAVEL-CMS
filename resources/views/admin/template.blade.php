<!DOCTYPE html>
<html class='no-js' lang='en' ng-app="myApp">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <base href="{{url('/admin')}}" data-url="{{url('/admin')}}" />
        <meta charset='utf-8'>
        <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
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
            <a class='navbar-brand' href='.'><i class='fa fa-user'></i> Quản lý nội dung</a>
            <ul class="nav navbar-nav pull-right">
                <li>
                    <a class="hidden" title="Bấm Alt + F1" shortcut='alt+f1' <?=linkMenu($menuConfig); ?> ></a>
                </li>
                <li>
                    <form class="navbar-form searchAjax">
                        <button type="button" onclick="sitemap(this)" class="btn btn-primary">
                        <i class="fa fa-sitemap"></i> Cập nhật sitemap
                        </button>
                        <input class="form-control" placeholder="Tìm kiếm..." type="text" name="title">
                        <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </li>
                <li><a title="Alt + H" shortcut="alt+h" onclick="window.open('{{url('/')}}');"><i class="fa fa-home"></i> Xem trang chủ</a></li>
                <li><a title="Alt + V" onclick="viewPage()" shortcut="alt+v"><i class="fa fa-eye"></i> Xem trang hiện tại</a></li>
                <li><a href="logout.php" ><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
            </ul>
        </div>
        <div id='wrapper' class="contentAjax">
            @if ($menuPage)
            <div id="infoPage" data-title="{{$title}}" data-name="{{$name}}" data-table="<?=(isset($id))?'data':'menu'?>" data-idList="<?php if(isset($idList)){echo $idList;}?>" data-id="<?php if(isset($id)){echo $id;}?>" data-slug="<?php echo ($menuPage->file == 'config') ? 'cau-hinh/'.$slugCurrent->slugName: $slugCurrent->slugName; ?>"></div>
            @endif
            <section id='sidebar'>
                <ul id='dock' class="navAjax sortAjax" data-active='active'>
                    <?php $key=1; foreach($listMenuAdmin as $menu){ if($menu->file !=='search' && $menu->file !=='config' && ($author == 'admin' || (isset($author->type) && (in_array($menu->id,explode(',',$author->type))) )) ){
                        $listData = $db->listData($menu->id);
                        ?>
                    <li data-name="<?=$menu->name?>" class="launcher <?=returnWhere('active',$menu->id,$menuPage->id) ?>">
                        <i class="<?=returnIcon($menu->file) ?>"></i> 
                        <a title="Bấm Alt + <?=$key?>" shortcut='alt+<?=$key?>' <?=linkMenu($menu); ?> >
                        <?php if($menu->file == 'shop'){ echo 'Đơn hàng'; }else{ echo $menu->title ;}?>
                        <span class="spanAlert"><?php if(count($listData)){echo count($listData);}?></span>
                        </a>
                    </li>
                    <?php $key++;}} ?>
                </ul>
            </section>
            <div>
                <?php
                    $allListMenuParent = [];
                    if(isset($idMenu)){
                    $allListMenuParent = array_reverse($db->allListMenuParent($idMenu));
                    if(isset($idList) || $menuPage->menu_parent == '0' && !isset($id)){
                    unset($allListMenuParent[count($allListMenuParent) - 1]);
                    }
                    }
                    ?>
                <section id='tools'>
                    <ul class='breadcrumb' id='breadcrumb'>
                        <?php foreach($allListMenuParent as $menu) { if($menu){?>
                        <li>
                            <a <?php if($menu->menu_parent == '0' || $menu->menu_parent == '-1'){echo linkMenu($menu);
                                }else{ echo linkIdList($menu,$name);} ?> >
                            <?=$menu->title;?>        
                            </a>
                        </li>
                        <?php }} ?>
                        <li class='title'><?=$title;?></li>
                    </ul>
                </section>
                <!-- Content -->
                <div id='content'></div>
            </div>
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