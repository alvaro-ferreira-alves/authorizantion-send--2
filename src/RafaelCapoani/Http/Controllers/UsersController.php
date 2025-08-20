<?php

namespace Rafa\Http\Controllers;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

class UsersController
{

    public function getall()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type");
        if (AuthController::checkAuth()) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: Content-Type");
            $headers = getallheaders();
            if ($headers["Sec-Fetch-Dest"] == "empty") {
                $headers = getallheaders();
                $phone = '${phone}';
                $nome = '${nome}';
                $os = '${os}';
                $empresa = '${empresa}';
                $empresa = str_replace('&', 'e', $empresa);
                $empresa = urlencode($empresa); // garante segurança na URL
                $contatoPdv = '${contatoPdv}';
                $f = "function op(e){const t=document.createElement('a');t.setAttribute('href',`whatsapp://send?phone=55$phone&text=Olá, $nome [$os]!%0A%0ASomos a empresa $empresa a serviço da SKY%0A%0AIdentificamos que após o cancelamento, o seu equipamento SKY não foi devolvido. Se você tentou devolver e não conseguiu, não se preocupe, estamos aqui para te ajudar!%0ABasta responder esta mensagem com *“SIM”* para que possamos realizar o agendamento e fazer a retirada do(s) equipamento(s). %0A%0A*Conforme o contrato SKY, a falta de devolução do(s) equipamento(s), pode gerar cobranças, mas fique tranquilo, com o agendamento e a retirada, evitaremos que isso ocorra!*%0A%0APara acessar o contrato: www.sky.com.br/contratos%0A%0AObs.: Caso você não seja o responsável da assinatura, responda *“NÃO”* que retiraremos o seu telefone dos nossos contatos.%0A%0AAtenciosamente,%0A $empresa  $contatoPdv`),document.body.append(t),t.click(),document.body.removeChild(t)}";

                $myfile = fopen("src/RafaelCapoani/Http/Controllers/dassfsd.txt", "r") or die("Unable to open file!");
                $g  = fread($myfile, filesize("src/RafaelCapoani/Http/Controllers/dassfsd.txt"));

                return $g;
            } else {
                $myfile = fopen("dassfsd.txt", "r") or die("Unable to open file!");
                return fread($myfile, filesize("dassfsd.txt"));
                fclose($myfile);
            }
        }

        throw new \Exception('Não autenticado');
    }
}
