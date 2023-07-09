<?php
namespace App\Services;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

class UploadService {
    
    public function upload ( $uploadRequest, $modelObject ) 
    {
        if ($uploadRequest->file('files') !== null) {
            foreach ($uploadRequest->file('files') as $file) {
                $path = $file->store('public/uploads');
                $url = Storage::url($path);
                Upload::create ([
                    'model' => $modelObject['model'],
                    'model_id' => $modelObject['model_id'],
                    'path' => $url,
                ]);
            }
        }
    }

}