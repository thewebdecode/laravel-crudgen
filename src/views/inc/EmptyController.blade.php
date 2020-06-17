<{{ $phpInit }}

namespace App\Http\Controllers{{ $contollerDir != null ? '\\'.ucfirst($contollerDir) : '' }};

use Illuminate\Http\Request;
use App\{{ $modelDir ? ucfirst($modelDir).'\\' : '' }}{{ $modelName }};
use App\Http\Controllers\Controller;

class {{ $controllerName }} extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['{{ Illuminate\Support\Str::plural(strtolower($modelName)) }}'] = {{ ucfirst($modelName) }}::get();
        return view('{{ $viewsDirFull }}.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('{{ $viewsDirFull }}.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
@foreach ($column_name as $index => $col_name)
@if (($col_name != null || $col_name != '') && $column_null[$index] == 0)
@php if($index != (count($column_name) - 1)){$comma = ',';}else{$comma = '';}@endphp
            '{{ $col_name }}' => 'required'{{ $comma }}
@endif
@endforeach
        ]);

        $res = {{ ucfirst($modelName) }}::create([
@foreach($column_name as $index => $col_name)
@if (($col_name != null || $col_name != '') && $column_null[$index] == 0)
@php if($index != (count($column_name) - 1)){$comma = ',';}else{$comma = '';}@endphp
            '{{ $col_name }}' => $request->{{ $col_name }}{{ $comma }}
@endif
@endforeach
        ]);

        if($res){
            return back()->withSuccess('Record created successfully !');
        } else {
            return back()->withAlert('Thre was a problem creating this record !');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['{{ Illuminate\Support\Str::singular(strtolower($modelName)) }}'] = {{ ucfirst($modelName) }}::whereId($id)->first();
        return view('{{ $viewsDirFull }}.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['{{ Illuminate\Support\Str::singular(strtolower($modelName)) }}'] = {{ ucfirst($modelName) }}::whereId($id)->first();
        return view('{{ $viewsDirFull }}.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
@foreach ($column_name as $index => $col_name)
@if (($col_name != null || $col_name != '') && $column_null[$index] == 0)
@php if($index != (count($column_name) - 1)){$comma = ',';}else{$comma = '';}@endphp
            '{{ $col_name }}' => 'required'{{ $comma }}
@endif
@endforeach
        ]);

        $res = {{ ucfirst($modelName) }}::whereId($id)->update([
@foreach($column_name as $index => $col_name)
@if (($col_name != null || $col_name != '') && $column_null[$index] == 0)
@php if($index != (count($column_name) - 1)){$comma = ',';}else{$comma = '';}@endphp
            '{{ $col_name }}' => $request->{{ $col_name }}{{ $comma }}
@endif
@endforeach
        ]);

        if($res){
            return back()->withSuccess('Record updated successfully !');
        } else {
            return back()->withAlert('Thre was a problem updating this record !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = {{ ucfirst($modelName) }}::whereId($id)->first()->delete();
        if($res){
            return back()->withSuccess('Record deleted successfully !');
        } else {
            return back()->withAlert('Thre was a problem deleting this record !');
        }
    }

}
