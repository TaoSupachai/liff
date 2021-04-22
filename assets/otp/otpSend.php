<?php
    
        $tel = @$_POST['tel'];

    try {
        $url = "http://www.molinkapi.com/molink_otp_service/sms.asmx/OTPSend";
        $curl = curl_init();
        $data_array =  array(
            "username" => "admin_otp", "password" => "admin#crop2019", "txtMobile" => $tel, "sender" => "CPCROP", "txtSMS" => 'รหัสยืนยันตนเพื่อเข้าสู่ระบบคือ ${code}'
        );
        $data = json_encode($data_array);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);

        $response = json_decode($result, true);
        $otp_id = $response['d']['Result']['otp_id'];

        echo json_encode([
            "tel" => $tel,
            "otpresponse" => $otp_id,
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        echo json_encode(
            [
                'message' => 'ระบบขัดข้องโปรดติดต่อ IT CPCROP', 
                'error' => $e,
                'otpresponse' => null,
                'status' => 'unsuccess'
            ]
        );
    }