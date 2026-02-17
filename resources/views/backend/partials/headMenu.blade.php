<ul class="menu" style="margin-top: 5px; margin-bottom: 5px;">
    <li class="menu_items" style="font-size: initial; font-weight: normal;">
        <button class="menu_links" style="color: #0D6EFD; border-color: #0D6EFD;" type="button">{{Auth::user()->name}}</button>

        <ul class="dropdown_menu" style="top: 53px;">
            <li class="dropdown_menu_items" style="height: 27px;">
                <a class="dropdown_menu_links {{ request()->routeIs('changeEmail') ? 'active' : '' }}" href="{{route('changeEmail')}}">Change email</a>
            </li>
            <li class="dropdown_menu_items">
                <a class="dropdown_menu_links {{ request()->routeIs('changePassword') ? 'active' : '' }}" href="{{route('changePassword')}}">Change password</a>
            </li>
            <li class="dropdown_menu_items">
                <button class="dropdown_menu_links" style="border: unset; padding: 0;" type="button" onclick="document.getElementById('logout-form').submit();">
                    Logout

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </button>
            </li>
        </ul>
    </li>
</ul>