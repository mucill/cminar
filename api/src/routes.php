<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return 'Api v1.0';
    });

    // $app->get("/regs", function (Request $request, Response $response){
    //     $sql = "SELECT * FROM registrations";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();
    //     return $response->withJson(["status" => "success", "data" => $result], 200);
    // });    

    $app->post("/regs/checkmail", function (Request $request, Response $response){
        $param = $request->getParsedBody("email");
        $sql = "SELECT id FROM registrations WHERE email LIKE '%{$param['email']}%'";    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() == 0) {
            return $response->withJson(["status" => "success", "data" => ['id' => $stmt->rowCount()]], 200);
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->post("/regs/checkphone", function (Request $request, Response $response){
        $param = $request->getParsedBody("phone");
        if(substr($param['phone'],0,1) != 0) {
            return $response->withJson(["status" => "invalid", "data" => "0"], 200);
        }
        $sql = "SELECT id FROM registrations WHERE phone = '{$param['phone']}'";    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() == 0) {
            return $response->withJson(["status" => "success", "data" => ['id' => $stmt->rowCount()]], 200);
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->post("/regs/", function (Request $request, Response $response){
        $members = $request->getParsedBody();
        $sql = "INSERT INTO registrations (
                    name, 
                    company,
                    jobs,
                    phone,
                    email,
                    kinds,
                    fee_seminar,
                    fee_hotel,
                    fee_tour,
                    fee_total,
                    created_at,
                    updated_at
                ) VALUE (
                    :name,
                    :company,
                    :jobs,
                    :phone,
                    :email,
                    :kinds,
                    :fee_seminar,
                    :fee_hotel,
                    :fee_tour,
                    :fee_total,
                    :created_at,
                    :updated_at
                )";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":name" => $members['name'],
            ":email" => $members['email'],
            ":phone" => $members['phone'],
            ":company" => $members['company'],
            ":jobs" => $members['jobs'],
            ":kinds" => $members['kinds'],
            ":fee_seminar" => $members['seminar'],
            ":fee_hotel" => $members['penginapanfee'],
            ":fee_tour" => $members['wisatafee'],
            ":fee_total" => $members['totalfee'],
            ":created_at" => date('Y/m/d H:i:s'),
            ":updated_at" => date('Y/m/d H:i:s'),
        ];    
        if($stmt->execute($data)) {
            // $mailBody = $this->view->render($response, 'emails/reg_email.html.twig',[
            //     'name' => $members['name'],
            //     'totalfee' => $members['totalfee'].'.000' 
            // ]);
            // $mail = new PHPMailer(true);
            // try{
            //     $mail->SMTPDebug = 0;                                 
            //     $mail->isSMTP();                                      
            //     $mail->Host = "mail.slimscommeet.web.id";
            //     $mail->SMTPAuth = true;                               
            //     $mail->Username = "2019@slimscommeet.web.id";                 
            //     $mail->Password = "slims1234562019";                           
            //     $mail->SMTPSecure = 'tls';                            
            //     $mail->Port = 587;

            //     $mail->setFrom('2019@slimscommeet.web.id', 'Panitia SLIMS COMMEET 2019');
            //     $mail->addAddress($members['email']);
            //     $mail->addBCC("2019@slimscommeet.web.id", $members['name']);

            //     $mail->isHTML(true); //send email in html formart
            //     $mail->Subject = 'BUKTI DAFTAR SLIMS COMMEET 2019';
            //     $mail->Body    = $mailBody;
            //     $mail->send();

            //     //return Email sent
            // }
            // catch (Exception $e) {
            //     $error = $mail->ErrorInfo;
            //     //return error here
            // }
            return $response->withJson(["status" => "success", "data" => "1"], 200);
        }        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->post('/payprove/', function(Request $request, Response $response) {
        $param = $request->getParsedBody("phone");
        $container['upload_directory'] = __DIR__.'/../uploads';
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['userfile'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $filename = sprintf('%s.%0.8s', $param["phone"], $extension);            
            $directory = $this->get('settings')['upload_directory'];
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

            $sql = "UPDATE registrations SET pay_photo=:pay_photo WHERE phone=:phone";
            $stmt = $this->db->prepare($sql);
            $params = [
                ":phone" => $param["phone"],
                ":pay_photo" => $filename
            ];
            
            if($stmt->execute($params)){
                return $response->withJson(["status" => "success", "data" => "1"], 200);
            }            
            return $response->withJson(["status" => "failed", "data" => "0"], 200);
        }
    });  
     
    $app->post('/sendmail/', function(Request $request, Response $response) {
        $members = $request->getParsedBody();
        $mailBody = $this->view->render($response, 'email_registration.html',[
            'name' => $members['name'],
            'totalfee' => $members['totalfee'].'.000' 
        ]);
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
            $mail->Subject = 'BUKTI DAFTAR SLIMS COMMEET 2019';
            $mail->Body    = $mailBody;
            $mail->send();
            return $response->withJson(["status" => "success", "data" => 1], 200);
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

    $app->get('/ticket/barcode/{regcode}', function(Request $request, Response $response, $args) {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        return 'data:image/png;base64,' . base64_encode($generator->getBarcode($args["regcode"], $generator::TYPE_CODE_128)); 
    }); 

    $app->post('/mailticket/', function(Request $request, Response $response) {
        $param = $request->getParsedBody();
        $sql = "SELECT * FROM registrations WHERE barcode = '{$param['regcode']}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) {            
            $result = $stmt->fetch();
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $mailBody = $this->view->render($response, 'ticket.html',[
                'name' => $result["name"],
                'kinds' => $result["kinds"],
                'company' => $result["company"],
                'barcode_id' => $result["barcode"],
                'barcode' => 'data:image/png;base64,' . base64_encode($generator->getBarcode($result["barcode"], $generator::TYPE_CODE_128)) 
            ]);

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetFont('Anonymous Pro');
            $mpdf->WriteHTML($mailBody);
            $mpdf->Output(__DIR__.'/../tmp/tiket-'.$result["barcode"].'.pdf', 'F');

            $message = '<p>Hai '.$result["name"].',</p>
            <p>
            Berikut ini adalah <strong>TIKET PESERTA SLIMS COMMEET 2019</strong>. Silahkan cetak dan dibawa pada saat regitrasi peserta.<br/>
            Terima kasih dan sampai jumpa di <strong>SLIMS COMMEET 2019</strong>.
            </p>
            <p>Salam, <br>Panitia SLIMS COMMEET 2019</p>';
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
                $mail->addAddress($param['email']);
    
                $mail->isHTML(true); //send email in html formart
                $mail->Subject = 'TIKET PESERTA SLIMS COMMEET 2019';
                $mail->Body = $message;
                $mail->AddAttachment(__DIR__.'/../tmp/tiket-'.$result["barcode"].'.pdf');
                $mail->send();
                
                unlink(__DIR__.'/../tmp/tiket-'.$result["barcode"].'.pdf');    
                return $response->withJson  (["status" => "success", "data" => "1"], 200);
           }
           catch (Exception $e) {
               $error = $mail->ErrorInfo;
               return $response->withJson(["status" => "failed", "data" => "0"], 200);
           }            
        }
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });     
};

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}
