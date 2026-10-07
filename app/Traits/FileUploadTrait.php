<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait FileUploadTrait {

    public function uploadFile(UploadedFile $file, ?string $oldPath = null, string $path) : ?string
    {
        if (!$file) {
            return null;
        }

        // File default yg tidak boleh dihapus
        $ignorePath = [
            "/uploads/default/avatar.png",
            "/uploads/default/logo.png",
            "/uploads/default/banner.png",
            "/uploads/default/brand.png",
        ];

        // hapus file lama kecuali default
        if ($oldPath && File::exists(public_path($oldPath)) && !in_array($oldPath, $ignorePath)) {
            File::delete(public_path($oldPath));
        }

        $folderPath = public_path("uploads/" . $path);

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }

        $filename = Str::uuid() . "." . $file->getClientOriginalExtension();

        $file->move($folderPath, $filename);

        return "uploads/" . $path . "/" . $filename;
    }

    public function uploadPrivateFile(UploadedFile $file, ?string $oldPath = null) : ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $filename = Str::uuid() . "." . $file->getClientOriginalExtension();
        $path = $file->storeAs('document', $filename, 'local');

        return $path;
    }

    public function deleteFile(string $path)
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
            return true;
        }

        return false;
    }
}
