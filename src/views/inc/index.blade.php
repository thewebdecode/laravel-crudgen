@php echo "@extends('layouts.app')\n\n"; @endphp
@php echo "@section('title', 'title')\n"; @endphp

@php echo "@section('scopedCss')\n"; @endphp
    {{ $echoStarter }}-- Styles for this page only --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@php echo "@endsection\n"; @endphp

@php echo "@section('content')\n"; @endphp

    <div class="container-fluid">
        <h3>Manage all {{ ucfirst(Illuminate\Support\Str::plural(strtolower($modelName))) }}</h3>
        <table class="table table-bordered table-striped table-hover" id="dataTables">
            <thead>
                <tr>
                    <th scope="col">#</th>
@foreach ($column_name as $key => $col_name)
@if ($col_name != null || $col_name != '')
                    <th scope="col">{{ ucfirst(strtolower($col_name)) }}</th>
@endif
@endforeach
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @php echo "@forelse($".Illuminate\Support\Str::plural(strtolower($modelName))." as $"."index => $".Illuminate\Support\Str::singular(strtolower($modelName)).")\n";@endphp
                    <tr>
                        <td>{{ $echoStarter }}@php echo '++$index }}';@endphp</td>
@foreach ($column_name as $key => $col_name)
@if ($col_name != null || $col_name != '')
                        <td>{{ $echoStarter }} ${{ Illuminate\Support\Str::singular(strtolower($modelName)) }}->{{ strtolower($col_name) }} }}</td>
@endif
@endforeach
                        <td>
                            <a href="#" class="btn btn-primary btn-sm">Edit</a>
                            <a href="#" class="btn btn-info btn-sm">View</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @php echo "@empty\n"; @endphp
                    <tr>
                        <td colspan="{{ count($column_name) + 1 }}"><h3>No data available !</h3></td>
                    </tr>
                @php echo "@endforelse\n"; @endphp
            </tbody>
        </table>
    </div>

@php echo "@endsection\n"; @endphp

@php echo "@section('scopedJs')\n"; @endphp
    {{ $echoStarter }}-- Scripts for this page only --}}
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables').DataTable( {
                "searching":   true,
                "paging":   true,
                "ordering": true,
                "info":     true
            });
        });
    </script>
@php echo "@endsection"; @endphp