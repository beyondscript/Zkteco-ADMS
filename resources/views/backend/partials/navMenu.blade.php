<ul class="menu">
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">Devices</a>
    </li>
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('attendances') ? 'active' : '' }}" href="{{route('attendances')}}">Attendances</a>
    </li>
    <li class="menu_items">
        <a class="menu_links {{ request()->routeIs('errorLogs') ? 'active' : '' }}" href="{{route('errorLogs')}}">Error logs</a>
    </li>
</ul>