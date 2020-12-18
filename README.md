# Localtinymce
A simple, self-hosted TinyMCE with image uploads for Laravel Nova.

I needed a simple, zero-config WYSIWYG-field for Laravel Nova. Trix was too restrictive and other solutions were too messy. This is what I came up with. It's based on TinyMCE 5. This package supports image uploads (working out of the box, without dependencies or API-keys) and lets users edit HTML directly (so video embeds works fine). The toolbar can be configured.

### Installation

```bash
composer require gusmanson/localtinymce
```

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

By default this uses a sensible toolbar and no file uploads.
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

Uploading should just work out of the box! Enable it by using ->withFiles() on your field. Defaults expect you to have [symlinked](https://laravel.com/docs/8.x/filesystem#the-public-disk) the public disk. The implementation is very basic. This is what  happens in the upload controller:

```php
$name = $request->file('attachment')->store($field->storageDir, $field->storageDisk);
$url = Storage::disk( $field->storageDisk )->url( $name );
return response()->json([
  'url' => $url
]);;
```

### TinyMCE

Read about TinyMCE [here](https://www.tiny.cloud/).
