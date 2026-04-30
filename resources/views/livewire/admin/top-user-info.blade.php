<div class="user-info-dropdown" wire:ignore>
    <div class="dropdown">

        <button class="dropdown-toggle no-arrow border-0 bg-transparent" type="button" data-toggle="dropdown">

            <img src="{{ $user->picture && file_exists(public_path('images/users/' . $user->picture))
                ? asset('images/users/' . $user->picture)
                : asset('images/users/default-avatar.png') }}"
                style="width:40px;height:40px;border-radius:50%;">
        </button>

        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                Profile
            </a>

            <a class="dropdown-item" href="{{ route('admin.settings') }}">
                Settings
            </a>

            <a class="dropdown-item" href="#">
                Help
            </a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-danger" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>

        </div>
    </div>

    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" class="d-none">
        @csrf
    </form>
</div>
