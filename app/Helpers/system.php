<?php

use Illuminate\Support\Facades\Storage;

function uploadFile($nameFolder,$file)
{
    $fileName = time().'_'.$file->getClientOriginalName();

    return $file->storeAs($nameFolder,$fileName,'public');
}