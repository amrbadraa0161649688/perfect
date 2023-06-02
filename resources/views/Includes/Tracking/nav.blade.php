<nav class="navbar navbar-expand-md fixed-top" id="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('home')}}">
            @auth
                @if(session('company'))
                    <img src="{{session('company')['company_logo']}}" alt="">
                @else

                    <img src="{{auth()->user()->company->company_logo}}" alt="">
                @endif
            @endauth
        </a>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">

                </li>
            </ul>
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    @if(app()->getLocale() == 'en')
                        <a class="nav-link font-ar" href="{{ route('locale','ar') }}">
                            <i class="fal fa-globe"></i>
                            <span>عربي</span>
                        </a>
                    @else
                        <a class="nav-link font-ar" href="{{ route('locale','en') }}">
                            <i class="fal fa-globe"></i>
                            <span>English</span>
                        </a>
                    @endif

                </li>
                <li class="nav-item">
                </li>
            </ul>
        </div>
    </div>

</nav>