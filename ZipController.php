<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File; 
use ZipArchive;


class HomeController extends Controller
{
   

    public function download_zip(){
        $zip = new ZipArchive;
        $fileName = 'myNewFile.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(public_path('temp/'));
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
            $zip->close();
        }
        return response()->download(public_path($fileName));
    }


    public function create()
    {
        $filePath = 'app/course/files.zip';
        $zip = new \ZipArchive();
    
        if ($zip->open($filePath, \ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Cannot open ' . $filePath);
        }
    
        $this->addContent($zip, realpath('app/course'));
        $zip->close();
    }

    
   
    private function addContent(\ZipArchive $zip, string $path)
    {
      
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::FOLLOW_SYMLINKS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    
        while ($iterator->valid()) {
            if (!$iterator->isDot()) {
                $filePath = $iterator->getPathName();
                $relativePath = substr($filePath, strlen($path) + 1);
    
                if (!$iterator->isDir()) {
                    $zip->addFile($filePath, $relativePath);
                } else {
                    if ($relativePath !== false) {
                        $zip->addEmptyDir($relativePath);
                    }
                }
            }
            $iterator->next();
        }
    }
}
