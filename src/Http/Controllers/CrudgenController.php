<?php

namespace Thewebdecode\Crudgen\Http\Controllers;

use GuzzleHttp\Client;
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
        $data['update'] = $this->checkForUpdates();
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
        $data['modelName'] = $request->model_name;
        $data['column_name'] = $request->column_name;
        $data['column_type'] = $request->column_type;
        $data['column_default'] = $request->column_default;
        $data['column_null'] = $request->column_null;
        $data['column_unsigned'] = $request->column_unsigned;
        $data['route_prefix'] = $request->route_prefix;
        $data['phpInit'] = '?php';
        $data['echoStarter'] = '{{';

        // return $data;

        // Set session for the Directories to fillit automatically
        if ($request->controller_dir) {
            session()->put('controller_dir', $request->controller_dir);
        }
        if ($request->model_dir) {
            session()->put('model_dir', $request->model_dir);
        }

        // Check if user set any directory
        $contollerDir = $request->controller_dir ?? null;
        $modelDir = $request->model_dir ?? null;
        $viewsDir = $request->views_dir ? $request->views_dir.'/'.$request->route_prefix : $request->route_prefix;
        $viewsDir = str_replace('/', '.', $viewsDir);
        $data['contollerDir'] = $contollerDir;
        $data['modelDir'] = $modelDir;  
        $data['viewsDir'] = str_replace('/', '.', $viewsDir);


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
        } else {
            File::put(base_path('app/Http/Controllers/'.$request->controller_name.'.php'), view('laravel-crudgen::inc.EmptyController', $data));
        }

        // Put Model file
        if ($modelDir != null) {
            File::put(base_path('app/'.$modelDir.'/'.$request->model_name.'.php'), view('laravel-crudgen::inc.EmptyModel', $data));
        } else {
            File::put(base_path('app/'.$request->model_name.'.php'), view('laravel-crudgen::inc.EmptyModel', $data));
        }    
        
        // Put Views files
        if ($viewsDir !== null) {
            File::put(base_path('resources/views/'.$viewsDir.'/index.blade.php'), view('laravel-crudgen::inc.index', $data));
            File::put(base_path('resources/views/'.$viewsDir.'/create.blade.php'), view('laravel-crudgen::inc.create', $data));
            File::put(base_path('resources/views/'.$viewsDir.'/show.blade.php'), view('laravel-crudgen::inc.show', $data));
            File::put(base_path('resources/views/'.$viewsDir.'/edit.blade.php'), view('laravel-crudgen::inc.edit', $data));
        }

        // Put Views files
        if ($request->column_name) {
            File::put(base_path('database/migrations/'.date('Y_m_d_his').'_create_'.strtolower(Str::plural($request->model_name)).'_table'.'.php'), view('laravel-crudgen::inc.empty-migration', $data));
        }

        return redirect(url('crudgenerator/?status=success'))->withSuccess('Crud Generated Successfully !');

    }




    public function checkedUpdates()
    {
        session()->put('checked_updates', true);
        return response()->json(['status' => 'success', 'message' => 'Dismissed update popup !']);
    }

    public function getInfo()
    {
        return json_decode(file_get_contents(base_path('packages/laravel-crudgen/src/data/info.json')));
    }

    public function checkForUpdates()
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
