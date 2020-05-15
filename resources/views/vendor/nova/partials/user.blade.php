<dropdown-trigger class="h-9 flex items-center">
    <img
        src="{{ $user->getFirstMediaUrl('avatar', 'medium') }}"
        class="rounded-full w-8 h-8 mr-3"
        alt="{{ $user->name }}"
    />

    <span class="text-90">
        {{ $user->name }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
        <li>
            <a href="{{ "/nova/resources/users/{$user->id}" }}" class="block no-underline text-90 hover:bg-30 p-3">
                Profile
            </a>
        </li>
        <li>
            <a href="{{ route('nova.logout') }}" class="block no-underline text-90 hover:bg-30 p-3">
                {{ __('Logout') }}
            </a>
        </li>
    </ul>
</dropdown-menu>
