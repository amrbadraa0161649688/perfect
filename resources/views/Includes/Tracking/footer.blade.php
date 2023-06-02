<footer>
    <div class="footer-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <div class="footer-contact">

                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <div class="footer-logo">
                        @auth
                            @if(session('company'))
                                <img src="{{session('company')['company_logo']}}" alt="">
                            @else
                                <img src="{{auth()->user()->company->company_logo}}" alt="">
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="col-12 col-lg-5">

                </div>
            </div>
        </div>
    </div>
    <div class="footer-socials">
        <div class="container-fluid">
            <div class="socials">
            </div>
        </div>
    </div>

</footer>