
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
            @if ($crudgen->status == 'ok' && !session()->has('checked_updates'))
                <div class="alert alert-success rounded-0" id="updatePopup">
                    <a href="javascript:void(0)" id="checkedUpdates" class="btn btn-danger btn-sm float-right">X</a>
                    <h4>{{ ucfirst($crudgen->message) }}</h4>
                    <p class="m-0">There is a new version available V {{ $crudgen->new_version }}</p>
                    <p class="m-0">Update from V {{ $crudgen->cur_version }} => V {{ $crudgen->new_version }} <a href="{{ $crudgen->url }}" target="_blank">Update Now <i class="fas fa-external-link-alt"></i></a></p>
                </div>
            @endif

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
                                <div class="form-group">
                                    <label for="route_prefix">Routes <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="route_prefix" value="{{ old('route_prefix') }}" name="route_prefix" placeholder="Route URL*" required>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title m-0">Define Directories (Default: laravel's default) <button type="button" class="btn p-1" data-toggle="tooltip" title="Define your required directory name for the controller model and views where you want to put them." style="cursor: normal; box-shadow: none"><i class="fas fa-question-circle text-info"></i></button></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="controller_dir">Directory for controller </label>
                                            <input type="text" class="form-control" id="controller_dir" value="{{ session()->has('controller_dir') ? session()->get('controller_dir') : '' }}" name="controller_dir" placeholder="Directory for controller">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="model_dir">Directory for model </label>
                                            <input type="text" class="form-control" id="model_dir" value="{{ session()->has('model_dir') ? session()->get('model_dir') : '' }}" name="model_dir" placeholder="Directory for model">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="views_dir">Directory for views </label>
                                            <input type="text" class="form-control" id="views_dir" value="{{ old('views_dir') }}" name="views_dir" placeholder="Directory for views">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">Controller Setup</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="controller_name">Controller Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="controller_name" value="{{ old('controller_name') }}" name="controller_name" placeholder="Controller Name*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">Model Setup</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="model_name">Model Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="model_name" value="{{ old('model_name') }}" name="model_name" placeholder="Model Name*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-3 text-right">
                                <button type="button" class="btn btn-primary" id="next" data-target="second-tab">Next &raquo;</button>
                            </div>
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

        $('[data-toggle="tooltip"]').tooltip();

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
                        <button type="button" class="btn btn-info rounded-circle addMoreCols"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-danger rounded-circle" onclick="$(this).parent().parent().remove(); i = i - 1">X</button>
                    </td>
                </tr>
            `;

            $('#tbody').append(el);

        })


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

        
    </script>
@endsection