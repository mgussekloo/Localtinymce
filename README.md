# Localtinymce
A simple, self-hosted TinyMCE with image uploads for Laravel Nova.

I needed a simple, zero-config TinyMCE for Laravel Nova and this is what I came up with.

### Installation

Publish the TinyMCE JS and CSS files.

```bash
artisan vendor:publish --tag=public --force
```

### Usage

```php
use Gusmanson\Localtinymce\Localtinymce;
```
```php
public function fields(Request $request)
{
    return [
        ID::make('ID', 'id')->sortable(),
        Text::make('Name', 'name'),
        Localtinymce::make('Information', 'information')->withFiles('public');
    ];
}
```

### Options

By default, this uses a sensible toolbar and no file uploads.
You can use withFiles() and withToolbar() to configure these options.

```php
    public function withFiles($disk = null, $dir = null)
    {
      // The default storage disk is 'public', the default storage dir is 'uploads'. 
    }

    public function withToolbar($toolbar)
    {
      // The default toolbar is: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image code'
      // 'image' will be removed if file upload isn't enabled
    }
```

### Uploading

Uploading works very, very simple. This is what  happens in the upload controller:

```php
$name = $request->file('attachment')->store($field->storageDir, $field->storageDisk);
$url = Storage::disk( $field->storageDisk )->url( $name );
return response()->json([
  'url' => $url
]);;
```php
