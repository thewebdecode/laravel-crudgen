<?php

namespace Thewebdecode\Crudgen\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CrudgenController extends Controller
{

    public function __construct() {
        if (config('laravel-crudgen.auth_require')) {
            $this->middleware('auth');
        }
        // session()->put('locale', 'en');
        App::setLocale(session()->get('locale'));
    }

    // Show The index page to add details
    public function index(Request $request)
    {
        $data['request'] = $request;
        $data['crudgen'] = $this->getCrudgenInfo();
        return view('laravel-crudgen::index', $data);
    }

    // Generate all the crud files
    public function generate(Request $request)
    {

        $request->validate([
            'route_prefix' => 'required|string',
            'controller_name' => 'required|string',
            'model_name' => 'required|string'
        ]);

        // return $request->all();

        // Prepare data for the files
        $data['controllerName'] = $request->controller_name;
        $data['modelName'] = ucfirst($request->model_name);
        $data['column_name'] = $request->column_name;
        $data['column_type'] = $request->column_type;
        $data['column_default'] = $request->column_default;
        $data['column_null'] = $request->column_null;
        $data['column_unsigned'] = $request->column_unsigned;
        $data['route_prefix'] = strtolower(str_replace('-', '_', $request->route_prefix));
        $data['layout'] = $request->layout;
        $data['phpInit'] = '?php';
        $data['echoStarter'] = '{{';

        // return $data;

        // Set session for the Directories to fillit automatically
        if ($request->controller_dir) {
            session()->put('controller_dir', $request->controller_dir);
        } else {
            if (session()->has('controller_dir')) {
                session()->forget('controller_dir');
            }
        }
        if ($request->model_dir) {
            session()->put('model_dir', $request->model_dir);
        } else {
            if (session()->has('model_dir')) {
                session()->forget('model_dir');
            }
        }
        if ($request->views_dir) {
            session()->put('views_dir', $request->views_dir);
        } else {
            if (session()->has('views_dir')) {
                session()->forget('views_dir');
            }
        }

        // Check if user set any directory
        $contollerDir = $request->controller_dir ?? null;
        $modelDir = $request->model_dir ?? null;

        $viewsDir = $data['route_prefix'];
        $viewsDirPre = $request->views_dir ? strtolower($request->views_dir).'/' : '';


        $data['contollerDir'] = $contollerDir;
        $data['modelDir'] = $modelDir;
        $data['viewsDirFull'] = $request->views_dir ? str_replace('/', '.', $request->views_dir).'.'.$viewsDir : $viewsDir;


        // Check if selected direcotories are avlaible or not / Create directories
        if ($contollerDir !== null) {
            if(!File::isDirectory(base_path('app/Http/Controllers/'.$contollerDir))) {
                File::makeDirectory(base_path('app/Http/Controllers/'.$contollerDir), 0777, true, true);
            }
        }

        if ($modelDir !== null) {
            if(!File::isDirectory(base_path('app/'.$modelDir))) {
                File::makeDirectory(base_path('app/'.$modelDir), 0777, true, true);
            }
        }
        if ($viewsDir !== null) {
            if(!File::isDirectory(base_path('resources/views/'.$viewsDir))) {
                File::makeDirectory(base_path('resources/views/'.$viewsDir), 0777, true, true);
            }
        }


        // Check for directories and put files accordingly
        //************************************************/
        // Put Controller file
        if ($contollerDir !== null) {
            File::put(base_path('app/Http/Controllers/'.$contollerDir.'/'.$request->controller_name.'.php'), view('laravel-crudgen::inc.EmptyController', $data));
            $conNameForReturn = $contollerDir.'\\'.$request->controller_name;
        } else {
            File::put(base_path('app/Http/Controllers/'.$request->controller_name.'.php'), view('laravel-crudgen::inc.EmptyController', $data));
            $conNameForReturn = $request->controller_name;
        }

        // Put Model file
        if ($modelDir != null) {
            File::put(base_path('app/'.$modelDir.'/'.$request->model_name.'.php'), view('laravel-crudgen::inc.EmptyModel', $data));
        } else {
            File::put(base_path('app/'.$request->model_name.'.php'), view('laravel-crudgen::inc.EmptyModel', $data));
        }    
        
        // Put Views files
        if ($viewsDir !== null) {
            File::put(base_path('resources/views/'.$viewsDirPre.$viewsDir.'/index.blade.php'), view('laravel-crudgen::inc.index', $data));
            File::put(base_path('resources/views/'.$viewsDirPre.$viewsDir.'/create.blade.php'), view('laravel-crudgen::inc.create', $data));
            File::put(base_path('resources/views/'.$viewsDirPre.$viewsDir.'/show.blade.php'), view('laravel-crudgen::inc.show', $data));
            File::put(base_path('resources/views/'.$viewsDirPre.$viewsDir.'/edit.blade.php'), view('laravel-crudgen::inc.edit', $data));
        }

        // Put Views files
        if ($request->column_name) {
            File::put(base_path('database/migrations/'.date('Y_m_d_his').'_create_'.strtolower(Str::plural($request->model_name)).'_table'.'.php'), view('laravel-crudgen::inc.empty-migration', $data));
        }

        $success = "<span style=\"color: var(--blue)\">Route</span><span style=\"color: var(--pink)\">::</span><span style=\"color: var(--green)\">resource</span>(<span style=\"color: var(--yellow)\">'".$data['route_prefix']."'</span>, <span style=\"color: var(--yellow)\">'".$conNameForReturn."'</span>);";
        $status = 'success';
        $crudgen = $this->getCrudgenInfo();
        $routesToReturn = [
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".index'</span>);",
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".create'</span>);",
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".show'</span>, $".Str::singular(strtolower($data['modelName']))."->id);",
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".edit'</span>, $".Str::singular(strtolower($data['modelName']))."->id);",
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".update'</span>, $".Str::singular(strtolower($data['modelName']))."->id);",
            "<span style=\"color: var(--green)\">route</span>(<span style=\"color: var(--yellow)\">'".Str::plural(strtolower($data['modelName'])).".destroy'</span>, $".Str::singular(strtolower($data['modelName']))."->id);"
        ];
        return view('laravel-crudgen::index', compact(['success', 'status', 'crudgen', 'routesToReturn']));

    }




    public function checkedUpdates()
    {
        session()->put('checked_updates', true);
        return response()->json(['status' => 'success', 'message' => 'Dismissed update popup !']);
    }

    public function getInfo()
    {
        return json_decode(file_get_contents(__DIR__.'../../../data/info.json'));
    }

    public function getCrudgenInfo()
    {
        // $response = Http::get('https://jsonplaceholder.typicode.com/todos/1');

        // if ($response->status() === 200) {
        //     return response()->json(['version' => '1.0.5'], 200);
        // }

        $info = $this->getInfo();
        if ($info->version != '1.0.5') {
            return json_decode(json_encode(['status' => 'ok', 'cur_version' => $info->version, 'new_version' => '1.5', 'url' => 'https://packages.webdecode.in/crudgen/doc/update', 'message' => 'New version available !']));
        }

    }
    

}
