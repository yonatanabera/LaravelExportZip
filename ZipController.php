<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File; 
use ZipArchive;


class HomeController extends Controller
{
   
    public function copy(){
        File::copyDirectory(public_path('temp/eapi/'), public_path('temp/nob/'));
        dd("public file copied");
    }


    
    /**
     * @throws RuntimeException If the file cannot be opened
     */
    public function create()
    {
        $filePath = 'temp/files.zip';
        $zip = new \ZipArchive();
    
        if ($zip->open(public_path($filePath), \ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Cannot open ' . $filePath);
        }
    
        $this->addContent($zip, public_path('temp'));
        $zip->close();
        return "ZIp file created";

        return response()->download(public_path($filePath));
    }

    
    /**
     * This takes symlinks into account.
     *
     * @param ZipArchive $zip
     * @param string     $path
     */
    private function addContent(\ZipArchive $zip, string $path)
    {
        /** @var SplFileInfo[] $files */
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



    public function extractFile(){
       $zip= new ZipArchive; 
       if($zip->open('temp/files.zip')===TRUE){
           $zip->extractTo('Gelila');
           $zip->close();
           return "Completed";
       }else{
           return "Error";
       }
    }

}
