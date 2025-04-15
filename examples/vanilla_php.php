<?php
  $file_path = 'C:\my_file.docx';
  $secret = 'XXXXXXXXXXXXXXXX';
  
  if (file_exists($file_path)) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/octet-stream'));
    curl_setopt($curl, CURLOPT_URL, "https://v2.convertapi.com/convert/docx/to/pdf?secret=".$secret);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CurlFile($file_path)), 'storefile' => 'false');
    $result = curl_exec($curl);
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
        file_put_contents("result.pdf", $result);
    } else {
        print("Server returned error:\n".$result."\n");
    }
  } else {
    print('File does not exist: '.$file_path."\n");
  }
?>
