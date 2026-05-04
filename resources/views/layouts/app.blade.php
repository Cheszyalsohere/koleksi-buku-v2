<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- Style Global --}}
    @include('partials.style-global')

    {{-- Style Page --}}
    @yield('style-page')

</head>

<body>
    <div class="container-scroller">

        {{-- Navbar --}}
        @include('partials.navbar')

        <div class="container-fluid page-body-wrapper">

            {{-- Sidebar --}}
            @include('partials.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">

                    {{-- Content --}}
                    @yield('content')

                </div>

                {{-- Footer --}}
                @include('partials.footer')
            </div>
        </div>
    </div>

    {{-- Javascript Global --}}
    @include('partials.script-global')

    {{-- Javascript Page --}}
    @yield('script-page')

</body>

</html>