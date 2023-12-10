<?php

use Illuminate\Support\Facades\Storage;
function uploadFile($nameFolder, $file)
{
    // Kiểm tra sự tồn tại của thư mục trước khi lưu trữ
    if (!Storage::exists($nameFolder)) {
        Storage::makeDirectory($nameFolder, 0755, true); // Tạo thư mục nếu không tồn tại
    }
    $fileName = time() . '_' . $file->getClientOriginalName();

    // Lưu trữ tệp tin và kiểm tra kết quả
    $result = $file->storeAs($nameFolder, $fileName, 'public');
    // Kiểm tra kết quả và trả về đường dẫn hoặc null nếu có lỗi
    return $result ? Storage::url($result) : null;
}
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}
function generateRandomString($length = 50)
{
    $characters =  '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return date("YmdHis") . $randomString;
}
