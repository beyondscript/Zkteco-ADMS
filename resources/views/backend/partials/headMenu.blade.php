<ul class="menu account_menu">
    <li class="menu_items">
        <button class="menu_links account_button" type="button">
            <i class="fa fa-user" aria-hidden="true"></i>
            {{Auth::user()->name}}
            <i class="fa fa-angle-down" aria-hidden="true"></i>
        </button>

        <ul class="dropdown_menu">
            <li class="dropdown_menu_items">
                <a class="dropdown_menu_links {{ request()->routeIs('changeEmail') ? 'active' : '' }}" href="{{route('changeEmail')}}">Change email</a>
            </li>
            <li class="dropdown_menu_items">
                <a class="dropdown_menu_links {{ request()->routeIs('changePassword') ? 'active' : '' }}" href="{{route('changePassword')}}">Change password</a>
            </li>
            <li class="dropdown_menu_items">
                <button class="dropdown_menu_links" type="button" onclick="document.getElementById('logout-form').submit();">
                    Logout

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </button>
            </li>
        </ul>
    </li>
</ul>
