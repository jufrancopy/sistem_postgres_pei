@include('layouts.includes.cabecera')
<body style="background: transparent;">
    <div class="content" style="padding: 5px;">
        <div class="container-fluid" style="padding: 0;">
            @if (session('info'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    </div>
                </div>
            @endif
            @if (count($errors))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <main class="py-2">
                @yield('content')
            </main>
        </div>
    </div>
    
    @include('layouts.includes.pie')
</body>
</html>
