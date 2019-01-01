<section id='tools'>
    <ul class='breadcrumb' id='breadcrumb'>
        @foreach($allListParentMenu as $menu) 
        @if($menu)
        <li>
            <a @if($menu->menu_parent == 0 || $menu->menu_parent == -1) {{linkMenu($menu, 'admin/')}} @else {{linkMenuChild($menu, 'admin/')}} @endif>
                {{$menu->title}}        
            </a>
        </li>
        @endif
        @endforeach
        <li class='title'>{{$title}}</li>
    </ul>
</section>