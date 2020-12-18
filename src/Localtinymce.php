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

    public $toolbar = 'undo redo | formatselect | bold italic backcolor |
    alignleft aligncenter alignright alignjustify |
    bullist numlist outdent indent |
    removeformat | link image code';

    public $scriptUrl = 'vendor/localtinymce/tinymce/tinymce.min.js';

    public $withFiles = false;
    public $uploadUrl = false;

    public $storageDir = 'uploads';
    public $storageDisk = 'public';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->hideFromIndex();
    }

    public function resolve($resource, $attribute = null)
    {
		parent::resolve($resource, $attribute);

		$this->prepareUploadUrl();

		$this->withMeta([
			'script_url' => url($this->scriptUrl),
			'upload_url' => $this->uploadUrl,
			'toolbar' => $this->toolbar
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

    public function withToolbar($toolbar)
    {
    	$this->toolbar = $toolbar;
    }

    public function withUploadUrl($url)
    {
    	$this->uploadUrl = $url;
    }

    // ---
    //
    private function prepareUploadUrl()
    {
    	if ($this->withFiles) {
			if (! $this->uploadUrl) {
				$request = app(NovaRequest::class);
				$this->withUploadUrl( route('localtinymce.upload', [ 'resource' => $request->resource, 'field' => $this->attribute ]) );
			}
		} else {
			$this->uploadUrl = false;
		}

		if (! $this->uploadUrl) {
			$this->toolbar = str_replace(' image ', ' ', $this->toolbar);
		}
    }
}
