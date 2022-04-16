<?php

    require "./lib/PHPmailer/Exception.php";
    require "./lib/PHPmailer/OAuth.php";
    require "./lib/PHPmailer/PHPMailer.php";
    require "./lib/PHPmailer/POP3.php";
    require "./lib/PHPmailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {

        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array( "codigo_status" => null, "descricao_status" => "");
        

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }


        public function mensagemValida() {
            if (empty($this->para) or empty($this->assunto) or empty($this->mensagem)) {
                return false;
            } else {
                return true;
            }

        }



    }

    $mensagem = new Mensagem();

    $mensagem->__set("para", $_POST['para']);
    $mensagem->__set("assunto", $_POST['assunto']);
    $mensagem->__set("mensagem", $_POST['mensagem']);

    if (!$mensagem->mensagemValida()) {
        echo "Mensagem não é valida";
        header("Location: index.php");
    } 
        
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'emailtest@gmail.com';     //Seu Email  !!!!!!!     //SMTP username
        $mail->Password   = '*****************';       // Sua Senha !!!!!!!     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('emailtest@gmail.com', 'By Jhonatha');
        $mail->addAddress($mensagem->__get("para"));     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get("assunto");
        $mail->Body    = $mensagem->__get("mensagem");
        $mail->AltBody = 'Seu Cliente de E-mail não é Suportado!';

        $mail->send();

        $mensagem->status["codigo_status"] = 1;
        $mensagem->status["descricao_status"] = "E-mail enviado com sucesso :)";

    } catch (Exception $e) {
        $mensagem->status["codigo_status"] = 2;
        $mensagem->status["descricao_status"] = "Não foi possível enviar a mesagem. Detalhes do Error: {$mail->ErrorInfo}";

    }


?>

<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

    <body>
        
        <div class="container">

            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="./img/logo.png" alt="" width="72" height="72">
                <h2>Send Mail</h2>
                <p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">

                    <?php if ($mensagem->status["codigo_status"] == 1) {  ?>
                        
                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso :)</h1>
                            <p><?= $mensagem->status["descricao_status"] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?> 

                    <?php if ($mensagem->status["codigo_status"] == 2) {  ?>
                        
                        <div class="container">
                            <h1 class="display-4 text-danger">Falha ao Enviar :(</h1>
                            <p><?= $mensagem->status["descricao_status"] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?> 

                </div>
            </div>
        </div>

    </body>

</html>
