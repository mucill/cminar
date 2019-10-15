<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// use Slim\Middleware\HttpBasicAuthentication;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return 'Api v1.0';
    });

    $app->post("/login/", function (Request $request, Response $response){
        $sql = "SELECT * FROM registrations";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["data" => $result], 200);
    });

    $app->post("/regs/", function (Request $request, Response $response){
        $sql = "SELECT * FROM registrations";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["data" => $result], 200);
    });

    $app->post("/paymentstatus/", function (Request $request, Response $response){
        $pay = $request->getParsedBody();
        // die(var_dump($pay));
        $total = (int)$pay['pay_seminar'] + (int)$pay['pay_hotel'] + (int)$pay['pay_wisata'];
        $real_total = (int)$pay['total'];
        if($total == $real_total) {
            $faker = Faker\Factory::create();
            $today = date('YYYY-MM-DD H:i:s');
            $generateKey = $faker->regexify('[A-Z]{2}[0-9]{4}');
            $sql = "UPDATE registrations SET status='Pembayaran Diterima', updated_at='{$today}', pay_status='Lunas', pay_amount='{$total}', barcode='{$generateKey}' WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $params = [
                ":id" => $pay['id']
            ];
            
            if($stmt->execute($params)){
                return $response->withJson(["status" => "success", "data" => "1"], 200);
            }
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->post('/sendmail/', function(Request $request, Response $response) {
        $members = $request->getParsedBody();
        $mailBody = $this->view->render($response, 'email_registration.html',[
            'name' => $members['name'],
            'barcode' => $members['barcode'] 
        ]);
        $today = date('YYYY-MM-DD H:i:s');
        $sql = "UPDATE registrations SET 
                    status='Kode Registrasi Terkirim', 
                    confirm_sms='1',
                    updated_at='{$today}' 
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $params = [":id" => $members['id']];
        
        $stmt->execute($params);

        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = 0;                                 
            $mail->isSMTP();                                      
            $mail->Host = "mail.slimscommeet.web.id";
            $mail->SMTPAuth = true;                               
            $mail->Username = "2019@slimscommeet.web.id";                 
            $mail->Password = "slims1234562019";                           
            $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;

            $mail->setFrom('2019@slimscommeet.web.id', 'Panitia SLIMS COMMEET 2019');
            $mail->addAddress($members['email']);
            $mail->addBCC("2019@slimscommeet.web.id", $members['name']);

            $mail->isHTML(true); //send email in html formart
            $mail->Subject = 'KODE REGISTRASI SLIMS COMMEET 2019';
            $mail->Body    = $mailBody;
            $mail->send();

            return $response->withJson(["status" => "success", "data" => "1"], 200);
       }
       catch (Exception $e) {
           $error = $mail->ErrorInfo;
           return $response->withJson(["status" => "failed", "data" => "0"], 200);
       }
    });    

    $app->post('/ticket/', function(Request $request, Response $response) {
        $param = $request->getParsedBody("regcode");
        $sql = "SELECT * FROM registrations WHERE barcode = '{$param['regcode']}'";    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    }); 

    // $app->post('/attendant/', function(Request $request, Response $response) {
    $app->post('/attendant/{regcode}/', function(Request $request, Response $response, $args) {
        $barcode = $args["regcode"];
        // echo $barcode;
        $today = date('YYYY-MM-DD H:i:s');
        $sql = "UPDATE registrations SET 
                    status='Hadir',
                    confirm_attendant=1,
                    updated_at='{$today}' 
                WHERE barcode = '{$barcode}'";    
        $stmt = $this->db->prepare($sql);
        if($stmt->execute()) {
            return $response->withJson(["status" => "success", "data" => "1"], 200);
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    }); 

    // $app->get("/generatefake/", function (Request $request, Response $response){

    //     for($i=0; $i<=25; $i++) {
    //         $faker = Faker\Factory::create();
    //         $name = $faker->name();
    //         $phone = '08'.$faker->randomDigitNotNull().$faker->randomNumber($nbDigits = 8, $strict = false);
    //         // $photo = $faker->image($dir = '../../uploads', $width = 480, $height = 240);
    //         $photo = $faker->randomElement($array = ['', $faker->image($dir = '../../uploads', $width = 640, $height = 480, 'cats', false)]);
    //         $email = $faker->freeEmail();
    //         $fee_hotel = $faker->randomElement($array = ['0','225']);
    //         $fee_tour = $faker->randomElement($array = ['0','200']);
    //         $kinds = $faker->randomElement($array = ['umum','mahasiswa']);
    //         $fee_seminar = ($kinds == 'umum') ? '350' : '250';
    //         $fee_total = (int)$fee_hotel + (int)$fee_tour +  (int)$fee_seminar;
    //         $pay_status = $faker->randomElement($array = [null, 'Lunas']);
    //         $company = $faker->company();
    //         $jobs = $faker->randomElement($array = ['Pustakawan','Kepala Sekolah','Guru','Lainnya']);

    //         $sql = "INSERT INTO registrations (name, phone, email, company, fee_hotel, fee_tour, fee_seminar, fee_total, kinds, jobs, pay_status, pay_photo) VALUES (:name, :phone, :email, :company, :fee_hotel, :fee_tour, :fee_seminar, :fee_total, :kinds, :jobs, :pay_status, :pay_photo)";
    //         $data = [
    //             'name' => $name,
    //             'phone' => $phone,
    //             'email' => $email,
    //             'company' => $company,
    //             'fee_hotel' => $fee_hotel,
    //             'fee_tour' => $fee_tour,
    //             'fee_seminar' => $fee_seminar,
    //             'fee_total' => $fee_total,
    //             'kinds' => $kinds,
    //             'jobs' => $jobs,
    //             'pay_status' => $pay_status,
    //             'pay_photo' => $photo
    //         ];
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute($data);
    //     }
    //     return $response->withJson(["status"=>"success","data" => "1"], 200);
    // });
};