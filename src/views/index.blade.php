
@extends('laravel-crudgen::layouts.app')
@section('title', 'Webdecode Laravel Crud Generator')

@section('scopedCss')
    <style>
        .table tr td {
            text-align: center
        }
    </style>
@endsection

@section('content')

        <!-- container starts here -->
        <div class="container py-3 bg-white" style="min-height: calc(100vh - 56px)">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            @if ($crudgen->status == 'ok' && !session()->has('checked_updates'))
                <div class="alert alert-success rounded-0" id="updatePopup">
                    <a href="javascript:void(0)" id="checkedUpdates" class="btn btn-danger btn-sm float-right">X</a>
                    <h4>{{ ucfirst($crudgen->message) }}</h4>
                    <p class="m-0">There is a new version available <b>v{{ $crudgen->new_version }}</b></p>
                    <p class="m-0">Update from <b>v{{ $crudgen->cur_version }}</b> => <b>v{{ $crudgen->new_version }}</b> <a href="{{ $crudgen->url }}" target="_blank" class="ml-3">Update Now <i class="fas fa-external-link-alt"></i></a></p>
                </div>
            @endif

            @if (!isset($status))
                <form action="{{ route('crudgen.generate') }}" method="POST" id="crudForm">
                    {{ csrf_field() }}
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="first-tab" data-toggle="tab" href="#first-tab-content" role="tab" aria-controls="first-tab-content" aria-selected="true">
                                Basic
                            </a>
                            <a class="nav-item nav-link" id="second-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                                Migrations (Database table columns)
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade pb-3 show active" id="first-tab-content" role="tabpanel" aria-labelledby="first-tab">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h4 class="card-title m-0">Routes Setup</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="route_prefix">Routes <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="route_prefix" value="{{ old('route_prefix') }}" name="route_prefix" placeholder="Route URL*" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h4 class="card-title m-0">Views Setup</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="views_dir">Directory for views </label>
                                                <input type="text" class="form-control" id="views_dir" value="{{ session()->has('views_dir') ? session()->get('views_dir') : '' }}" name="views_dir" placeholder="Directory for views">
                                                <small id="views_dir_info"></small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="layout">Views Layout to extend (Default: layouts.app) </label>
                                                <input type="text" class="form-control" id="layout" value="{{ old('layout') ?? 'layouts.app' }}" name="layout" placeholder="Views Layout to extend">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h4 class="card-title m-0">Controller Setup</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="controller_dir">Directory for controller </label>
                                                <input type="text" class="form-control" id="controller_dir" value="{{ session()->has('controller_dir') ? session()->get('controller_dir') : '' }}" name="controller_dir" placeholder="Directory for controller">
                                                <small id="controller_dir_info"></small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="controller_name">Controller Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="controller_name" value="{{ old('controller_name') }}" name="controller_name" placeholder="Controller Name*" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h4 class="card-title m-0">Model Setup</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="model_dir">Directory for model </label>
                                                <input type="text" class="form-control" id="model_dir" value="{{ session()->has('model_dir') ? session()->get('model_dir') : '' }}" name="model_dir" placeholder="Directory for model">
                                                <small id="model_dir_info"></small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="model_name">Model Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="model_name" value="{{ old('model_name') }}" name="model_name" placeholder="Model Name*" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group mt-3 text-right">
                                <button type="button" class="btn btn-primary" id="next" data-target="second-tab">Next &raquo;</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            @include('laravel-crudgen::partials.migrations')
                            <div class="form-group mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-primary" id="prev" data-target="first-tab"> &laquo; Back</button>
                                <button type="button" class="btn btn-primary" id="generate"> Generate <i class="fas fa-check"></i></button>
                            </div>
                        </div>  
                    </div>
                </form>
                <div>
                    <b class="text-danger">Note:</b> 
                    <ol type="i">
                        <li>
                            <code class="badge badge-light text-danger">id</code> and <code class="badge badge-light text-danger">timestamps</code> will be created automaticaly.
                        </li>
                        <li>
                            Use lowercase for the <code class="badge badge-light text-danger">Column names</code>.
                        </li>
                        <li>
                            For routes file, file must be in the routes directory (Not in sub directory).
                        </li>
                        <li>
                            For routes file, if file not be found then <code class="badge badge-light text-danger">web.php</code> file will be edited.
                        </li>
                    </ol>
                </div>
            @else
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @elseif(session()->has('alert'))
                    <div class="alert alert-danger">
                        {{ session()->get('alert') }}
                    </div>
                @elseif(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-success text-light">
                        <h3 class="card-title mb-0">Crud generated successfully !</h3>
                    </div>
                    <div class="card-body bg-dark text-light">
                        <ol type="1">
                            <li>
                                <p class="text-light">
                                    <b>Please copy route and put it in your <span class="px-2 py-1 rounded bg-danger text-light">web.php</span></b>
                                </p>
                                <blockquote class="text-light">
                                    <b>
                                        @if (isset($success))
                                            <span style="font-size: 18px">
                                                {!! $success !!}
                                            </span>
                                        @endif
                                    </b>
                                </blockquote>
                            </li>
                            <li>
                                <h4>Routes Name</h4>
                                @if (isset($routesToReturn))
                                    @foreach ($routesToReturn as $index => $route)
                                        <span style="font-size: 18px">{!! $route !!}</span> <br>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <b>Also run <span class="px-2 py-1 rounded bg-danger text-light">php artisan migrate</span></b>
                            </li>
                        </ol>
                        
                        <div class="pt-3">
                            <b>Note: For destroy route you must use a form with <span class="px-2 py-1 rounded bg-danger text-light">DELETE</span> method.</b>
                        </div>
                    </div>
                </div>

            @endif

            
            
        </div> <!-- /. container ends here -->

    
    
@endsection

@section('scopedJs')
    <script>
        $('#next, #prev').on('click', function() {
            $('#'+$(this).data('target')).click();
        });

        $('#checkedUpdates').on('click', function () {
            $.get("{{ route('crudgen.checked-updates') }}")
                .then(res => {
                    if (res.status == 'success') {
                        $('#updatePopup').fadeOut(1000);
                    }
                })
                .catch(err => console.error(err));
        });

        

        let i = 1;
        
        $(document).on('click', '.addMoreCols', function () {
            i = +i + 1;
            var el = `
                <tr>
                    <td>`+i+`</td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" name="column_name[]" id="column_name" required>
                        </div>
                    </td>
                    <td>
                        <div class="form-group m-0 p-0">
                            <select class="form-control" name="column_type[]" id="column_type" required>
                                <option value="integer">Int</option>
                                <option value="string">Varchar</option>
                                <option value="text">Text</option>
                                <option value="longText">Longtext</option>
                                <option value="json">Json</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" name="column_default[]" id="column_default">
                        </div>
                    </td>
                    <td>
                        <select class="form-control" name="column_null[]" id="column_null" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="column_unsigned[]" id="column_unsigned" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info rounded-circle addMoreCols" data-toggle="tooltip" title="Press SHIFT + > to add new row."><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-danger rounded-circle removeCol" data-toggle="tooltip" title="Press SHIFT + < to remove last row." onclick="$(this).parent().parent().remove(); i = i - 1">X</button>
                    </td>
                </tr>
            `;

            $('#tbody').append(el);

        })

        $(document).on('keypress', function (e) {
            // console.log(e.keyCode); // 60
            if (e.keyCode == 62) {
                $('#addMoreCols').click();
            } else if (e.keyCode == 60) {
                $('#tbody tr:last-child .removeCol').click();
            }
        });

        $('#generate').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please check all fields before continue !",
                showCancelButton: true,
                confirmButtonColor: 'var(--success)',
                cancelButtonColor: 'var(--danger)',
                confirmButtonText: 'Generate <i class="fas fa-check"></i>'
            }).then((result) => {
                if (result.value) {
                    $('#crudForm').submit();
                }
            })
        })


        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })

        $('#crudForm').on('input', function () {
            showControllerPath();
            showViewsPath();
            showModelPath();
        })

        function showControllerPath() {
            if ($('#controller_dir').val() != '' || $('#controller_dir').val() != null) {
                if ($('#controller_dir').val() == '' || $('#controller_dir').val() == null) {
                    var con_dir = '';
                } else {
                    var con_dir = $('#controller_dir').val()+'/';
                }
                if ($('#controller_name').val() == '' || $('#controller_name').val() == null) {
                    var con_name = '';
                } else {
                    var con_name = $('#controller_name').val()+'.php';
                }

                $('#controller_dir_info').html('<span class="text-success" style="font-weight: bold;">PROJECT_PATH/app/Http/Controllers/'+con_dir+con_name+'</span>')
            }
        }
        function showModelPath() {
            if ($('#model_dir').val() != '' || $('#model_dir').val() != null) {
                if ($('#model_dir').val() == '' || $('#model_dir').val() == null) {
                    var mod_dir = '';
                } else {
                    var mod_dir = $('#model_dir').val()+'/';
                }
                if ($('#model_name').val() == '' || $('#model_name').val() == null) {
                    var mod_name = '';
                } else {
                    var mod_name = $('#model_name').val()+'.php';
                }
                $('#model_dir_info').html('<span class="text-success" style="font-weight: bold;">PROJECT_PATH/app/'+mod_dir+mod_name+'</span>')
            }
        }
        function showViewsPath() {
            if ($('#views_dir').val() != '' || $('#views_dir').val() != null) {
                if ($('#views_dir').val() == '' || $('#views_dir').val() == null) {
                    var views_dir = '';
                } else {
                    var views_dir = $('#views_dir').val()+'/';
                }
                $('#views_dir_info').html('<span class="text-success" style="font-weight: bold;">PROJECT_PATH/resources/views/'+views_dir+$('#route_prefix').val()+'</span>')
            }
        }


    </script>
@endsection