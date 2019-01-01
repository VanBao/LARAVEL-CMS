 <section id='sidebar'>
    <ul id='dock' class="navAjax sortAjax" data-active='active'>
        @foreach($listMenuAdmin as $menu)
        @if($menu->file !== 'search' && $menu->file !== 'config')
        <li data-name="{{$menu->name}}" class="launcher @if($menu->id == $menuPage->id) {{'active'}} @endif">
            <i class="{{getIcon($menu->file)}}"></i> 
            <a {{linkMenu($menu, 'admin/')}}>
                {{$menu->title}}
                @php $total = $menu->Data->count() @endphp 
                <span class="spanAlert">@if($total != 0) {{$total}}@endif</span>
            </a>
        </li>
        @endif
        @endforeach
    </ul>
</section>