<?php

namespace Gusmanson\Localtinymce;

use Laravel\Nova\Fields\Field;

use Laravel\Nova\Http\Requests\NovaRequest;

class Localtinymce extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'localtinymce';

    public $scriptUrl = 'vendor/localtinymce/tinymce/tinymce.min.js';

    public $withFiles = false;
    public $uploadUrl = false;

    public $storageDir = 'uploads';
    public $storageDisk = 'public';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct(null, $attribute, $resolveCallback);

        $this->hideFromIndex();
    }

    public function resolve($resource, $attribute = null)
    {
		parent::resolve($resource, $attribute);

		$this->prepareUploadUrl();

		$this->withMeta([
			'script_url' => url($this->scriptUrl),
			'upload_url' => $this->uploadUrl
		]);
    }

    public function withFiles($disk = null, $dir = null)
    {
        $this->withFiles = true;

        if (!is_null($disk)) {
        	$this->storageDisk = $disk;
        }

        if (!is_null($dir)) {
        	$this->storageDir = $dir;
        }

        return $this;
    }

    public function withUploadUrl($url)
    {
    	$this->uploadUrl = $url;
    }

    public function prepareUploadUrl()
    {
    	if ($this->withFiles) {
			if (! $this->uploadUrl) {
				$request = app(NovaRequest::class);
				$this->withUploadUrl( route('localtinymce.upload', [ 'resource' => $request->resource, 'field' => $this->attribute ]) );
			}
		} else {
			$this->uploadUrl = false;
		}
    }
}
