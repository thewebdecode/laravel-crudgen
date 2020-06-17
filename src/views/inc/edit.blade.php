
@php echo "@extends('layouts.app')\n\n"; @endphp
@php echo "@section('title', 'title')\n"; @endphp

@php echo "@section('scopedCss')\n"; @endphp
    {{ $echoStarter }}-- Styles for this page only --}}
@php echo "@endsection\n"; @endphp

@php echo "@section('content')\n"; @endphp

    <div class="container-fluid">
        <h3>Create new {{ strSingular(strtolower($modelName)) }}</h3>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create new {{ strSingular(strtolower($modelName)) }}</h5>
            </div>
            <div class="card-body">
                @@if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ $echoStarter }} session()->get('success') }}
                    </div>
                @@elseif (session()->has('alert'))
                    <div class="alert alert-danger">
                        {{ $echoStarter }} session()->get('alert') }}
                    </div>
                @@endif
                @@foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $echoStarter }} $error }}
                    </div>
                @@endforeach
                <form action="{{ $echoStarter }} route('{{ strPlural(strtolower($modelName)) }}.update', ${{ strSingular(strtolower($modelName)) }}->id) }}" method="POST">
                    <div class="row">
@foreach ($column_name as $key => $col_name)
@if ($col_name != null || $col_name != '')
@php if($column_null[$key] == 0){$asterisk = '<span class="text-danger">*</span>'; $required = 'required';}else{$asterisk = ''; $required = '';} @endphp
@if ($column_type[$key] == 'json' || $column_type[$key] == 'text' || $column_type[$key] == 'longText')
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="{{ strtolower($col_name) }}">{!! ucfirst(strtolower($col_name)).' '. $asterisk !!}</label>
                                <textarea class="form-control" name="{{ strtolower($col_name) }}" id="{{ strtolower($col_name) }}" placeholder="{{ ucfirst(strtolower($col_name)) }}" rows="5" {{ $required }}>{{ $echoStarter }} old('{{ strtolower($col_name) }}') ?? ${!! strSingular(strtolower($modelName)).'->'.strtolower($col_name) !!} }}</textarea>
                            </div>
                        </div>
@else
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="{{ strtolower($col_name) }}">{!! ucfirst(strtolower($col_name)).' '. $asterisk !!}</label>
                                <input type="text" class="form-control" name="{{ strtolower($col_name) }}" id="{{ strtolower($col_name) }}" value="{{ $echoStarter }} old('{{ strtolower($col_name) }}') ?? ${!! strSingular(strtolower($modelName)).'->'.strtolower($col_name) !!} }}" placeholder="{{ strtolower($col_name) }}" {{ $required }}>
                            </div>
                        </div>
@endif
@endif
@endforeach
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@php echo "@endsection\n"; @endphp

@php echo "@section('scopedJs')\n"; @endphp
    {{ $echoStarter }}-- Scripts for this page only --}}
@php echo "@endsection"; @endphp