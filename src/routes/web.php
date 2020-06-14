<?php

    Route::group(['namespace' => 'Thewebdecode\Crudgen\Http\Controllers', 'middleware' => ['web']], function () {
        Route::get('crudgenerator', 'CrudgenController@index')->name('crudgen.index');
        Route::post('crudgenerator', 'CrudgenController@generate')->name('crudgen.generate');

        // Updates are checked 
        Route::get('checked-updates', 'CrudgenController@checkedUpdates')->name('crudgen.checked-updates');
    });