<?php

namespace Gusmanson\Localtinymce;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

use Laravel\Nova\Http\Requests\NovaRequest;

class UploadController extends Controller {

	public  function __invoke(NovaRequest $request)
    {
    	$field = $request->newResource()
	        ->availableFields($request)
	        ->findFieldByAttribute($request->field, function () {
	            abort(404);
	        });

    	$name = $request->file('attachment')->store($field->storageDir, $field->storageDisk);
    	$url = Storage::disk( $field->storageDisk )->url( $name );

        return response()->json([
        	'url' => $url
        ]);;
    }
}