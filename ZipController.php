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
}
