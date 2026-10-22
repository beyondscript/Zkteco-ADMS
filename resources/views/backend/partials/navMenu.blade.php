<div class="sidebar_brand" style="justify-content: center;">WORKSPACE</div>

<ul class="menu navigation_menu">
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}"><i class="fa fa-server" aria-hidden="true"></i> Devices</a>
    </li>
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('attendances') ? 'active' : '' }}" href="{{route('attendances')}}"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Attendances</a>
    </li>
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('errorLogs') ? 'active' : '' }}" href="{{route('errorLogs')}}"><i class="fa fa-file-text-o" aria-hidden="true"></i> Error logs</a>
    </li>
</ul>
