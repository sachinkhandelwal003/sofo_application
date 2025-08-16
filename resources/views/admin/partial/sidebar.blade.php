<nav id="sidebar">
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories ps ps--active-y" id="accordionExample">
        <li class="menu @routeis('admin.dashboard') active @endrouteis">
            <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-house"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('admin.vendor') active @endrouteis">
            <a href="{{ route('admin.vendor') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-store"></i>
                    <span>Vendor Listing</span>
                </div>
            </a>
        </li>
         <li class="menu @routeis('admin.app-user') active @endrouteis">
            <a href="{{ route('admin.app-user') }}" class="dropdown-toggle">
                <div class="">
                  <i class="fa-solid fa-users"></i>
                    <span>Users Listing</span>
                </div>
            </a>
        </li>
   <li class="menu @routeis('admin.categories') active @endrouteis">
            <a href="{{ route('admin.categories') }}" class="dropdown-toggle">
                <div class="">
    <i class="fa-solid fa-layer-group"></i>
                    <span>Category</span>
                </div>
            </a>
        </li>
        @if(Helper::userCan([102,103]))
        <li class="menu @routeis('admin.roles,admin.users') active @endrouteis">
            <a href="#master" data-bs-toggle="collapse" aria-expanded="{{ Helper::routeis('admin.roles,admin.users') }}"
                class="dropdown-toggle">
                <div class="">
                    <i class="fa-solid fa-sparkles"></i>
                    <span>Master</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.roles,admin.users') show @endrouteis" id="master"
                data-bs-parent="#accordionExample">
               
                @if(Helper::userCan(102))
                <li class="@routeis('admin.roles') active @endrouteis">
                    <a href="{{ route('admin.roles') }}">Roles</a>
                </li>
                @endif
                @if(Helper::userCan(103))
                <li class="@routeis('admin.users') active @endrouteis">
                    <a href="{{ route('admin.users') }}">Sub Admins</a>
                </li>
                @endif
                
            </ul>
        </li>
        @endif

        <!-- @if(Helper::userCan([104]))
        <li class="menu @routeis('admin.categories,admin.stores') active @endrouteis">
            <a href="#static_content" data-bs-toggle="collapse"
                aria-expanded="{{ Helper::routeis('admin.categories,admin.stores') }}"
                class="dropdown-toggle">
                <div class="">
                    <i class="fa-sharp fa-solid fa-photo-film"></i>
                    <span>Content</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.categories,admin.stores') show @endrouteis"
                id="static_content" data-bs-parent="#accordionExample">

              
                @if(Helper::userCan(104))
                <li class="@routeis('admin.cms') active @endrouteis">
                    <a href="{{ route('admin.cms') }}">CMS</a>
                </li>
                @endif
            </ul>
        </li> -->
        @endif

        @if(Helper::userCan([104]))
        <li class="menu @routeis('admin.stores') active @endrouteis">
            <a href="#store" data-bs-toggle="collapse"
                aria-expanded="{{ Helper::routeis('admin.stores') }}"
                class="dropdown-toggle">
                <div class="">
                <i class="fa-solid fa-store"></i>
                    <i class="store-icon fa-sharp fa-solid"></i>
                    <span>Store</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.stores') show @endrouteis"
                id="store" data-bs-parent="#accordionExample">

              
                @if(Helper::userCan(104))
                <li class="@routeis('admin.stores') active @endrouteis">
                    <a href="{{ route('admin.stores') }}">Store Listing</a>
                </li>
                @endif

            </ul>
        </li>
        @endif



        @if(Helper::userCan([105,106]))
        <li class="menu @routeis('admin.states,admin.cities') active @endrouteis">
            <a href="#location_content" data-bs-toggle="collapse" aria-expanded="{{ Helper::routeis('admin.states,admin.cities') }}"
                class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-location-dot"></i>
                    <span>Location</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.states,admin.cities') show @endrouteis" id="location_content"
                data-bs-parent="#accordionExample">
                {{-- @if(Helper::userCan(105))
                <li class="@routeis('admin.states') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.states') }}">States</a>
                </li>
                @endif --}}

                {{-- @if(Helper::userCan(106))
                <li class="@routeis('admin.cities') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.cities') }}">Cities</a>
                </li>
                @endif --}}
               
                @if(Helper::userCan(106))
                <li class="@routeis('admin.countries') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.countries') }}">Countries</a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(Helper::userCan(101))
        <li class="menu @routeis('admin.setting') active @endrouteis">
            <a href="#setting" data-bs-toggle="collapse" aria-expanded="{{ Helper::routeis('admin.setting') }}"
                class="dropdown-toggle">
                <div class="">
                    <i class="fa fa-cog my-auto"></i>
                    <span>App Setting</span>
                </div>
                <div><i class="fa-solid fa-chevron-right"></i></div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.setting') show @endrouteis" id="setting"
                data-bs-parent="#accordionExample">
                @foreach(config('constant.setting_array', []) as $key => $setting)
                <li class="@if(request()->path() == 'admin/setting/'.$key) active @endif">
                    <a class="nav-link" href="{{ route('admin.setting', ['id' => $key]) }}">
                        {{ $setting }}
                    </a>
                </li>
                @endforeach
            </ul>
        </li>

        <li class="menu">
            <a href="{{route('admin.database_backup')}}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-database"></i>
                    <span>Database Backup</span>
                </div>
            </a>
        </li>

        <li class="menu  @routeis('admin.server-control') active @endrouteis">
            <a href="{{ route('admin.server-control') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-server"></i>
                    <span>Server Control Panel</span>
                </div>
            </a>
        </li>
        @endif
    </ul>
</nav>