<h1 style="display: none;">{{ config('app.name', 'Laravel') }}</h1>
<nav class="navbar navbar-expand-lg bg-green default-margin-bottom">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
    <button class="btn btn-toogle" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-ellipsis-v"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto navbar-right">
            @if (Auth::guest())
                <li class="nav-item"><a href="{{ route('login') }}"
                                        class="nav-link primary-color">{{__('login.login')}}</a></li>
                <li class="nav-item"><a href="{{ route('register') }}"
                                        class="nav-link primary-color">{{__('login.register')}}</a></li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="{{ url('accounts') }}" class="dropdown-item">
                            {{__('accounts.title')}}
                        </a>
                        <a href="{{ url('transactions') }}" class="dropdown-item">
                            {{__('transactions.title')}}
                        </a>
                        @if (Auth::user()->hasRole('admin'))
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('translations') }}" class="dropdown-item">
                                {{__('translations.title')}}
                            </a>
                            <a href="{{ url('users') }}" class="dropdown-item">
                                {{__('users.show_title')}}
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            {{__('login.logout')}}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>