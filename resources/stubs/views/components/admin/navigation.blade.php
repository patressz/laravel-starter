@props([
    'controls' => false,
])

<nav class="navigation">
    <header class="navigation__header">
        <a href="#" class="btn btn--sm btn--default">Domov</a>
        <span class="navigation__header-name">{{ auth()->user()->name }}</span>
    </header>

    <div class="navigation__menu">
        <div class="navigation__menu--top">
            <ul class="navigation__list">
                <li class="navigation__item">
                    <a href="#" @class([
                        'navigation__link',
                        'navigation__link--active' => Str::of(Route::currentRouteName())->after('admin.')->startsWith('example'),
                    ])>Example</a>
                </li>
            </ul>
        </div>

        <div class="navigation__menu--bottom">
            <ul class="navigation__list">
                <li class="navigation__item">
                    <form method="POST" action="{{ route('logout') }}" class="rounded-lg">
                        @csrf

                        <a class="navigation__link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            Odhlásiť sa
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="content">
    <header class="content__header container">
        <button type="button" class="content__header-toggle-button" onclick="document.body.classList.toggle('close')"></button>

        @if ($controls)
            {!! $controls !!}
        @endif
    </header>

    <main class="content__main container">
        @include('layouts.admin.messages')

        {!! $slot !!}
    </main>
</section>

@push('styles')
    @vite('resources/sass/admin/navigation.scss')
@endpush
