@include('layouts.front.header')
<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #app {
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }

    .content {
        flex: 1;

    }

    .footer {
        flex-shrink: 0;

    }
</style>
<div class="m-3">
    @yield('content')
</div>



@include('layouts.front.footer')
