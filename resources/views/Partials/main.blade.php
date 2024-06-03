@include('Partials.header')

@include('Partials.navbar')

@include('Partials.sidebar')

<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">
    <div class="container-fluid">
        {{-- <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <p class="mb-0">Your business dashboard template</p>
                </div>
            </div>
        </div> --}}

        @yield('content')

        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Card Title</h4>
                    </div>
                    <div class="card-body">
                        Card Body
                    </div>
                    <div class="card-footer">
                        Card footer
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
<!--**********************************
            Content body end
        ***********************************-->

@include('Partials.footer')
