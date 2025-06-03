<?php
  $file_path = 'jmeter-docx.docx';
  $token = 'api_token';

  if (file_exists($file_path)) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://v2.convertapi.com/convert/docx/to/pdf');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    $headers = array(
        'Accept: application/octet-stream',
        'Authorization: Bearer ' . $token
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $postData = array(
        'file' => new CURLFile($file_path),
        'storefile' => 'false'
    );
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    print("Converting...\n");
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
