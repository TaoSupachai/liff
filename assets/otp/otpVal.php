<?php
    // header('Content-Type: application/json');
    // $data = json_decode(file_get_contents("php://input"), true);
    // $code = $data['code'];
    // $otpCheck = $data['otpval'];
    // $tel = $data['tel'];

    $code = @$_POST['code'];
    $otpCheck = @$_POST['otpval'];
    $tel = @$_POST['tel'];

    $data_array =  array(
        "otp_id" => $otpCheck, "code" => $code
    );
    $data = json_encode($data_array);

    try {
        $url = "http://www.molinkapi.com/molink_otp_service/sms.asmx/OTPValidate";

        $curl = curl_init();

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

        $message = $response['d']['Result']['message'];
        $codeOtp = $response['d']['Result']['code'];

        if ($message == null && $codeOtp == null) {
            if (!empty($tel)) {
                echo json_encode([
                    'otpcode' => $otpCheck,
                    'otp' => true,
                    'status' => 'success',
                    'tel' => $tel,
                    'error' => null
                ]);
            }else {
                echo json_encode([
                    'otpcode' => null,
                    'otp' => false,
                    'tel' => $tel,
                    'status' => 'unsuccess',
                    'error' => 'กรุณากรอกเบอร์โทรศัพท์ด้วย',
                ]);
            }
        } else {
            echo json_encode('unsuccess');
        }
    } catch (\Exception $e) {
        echo json_encode(['message' => 'ระบบขัดข้องโปรดติดต่อ IT CPCROP', 'error' => $e]);
    }