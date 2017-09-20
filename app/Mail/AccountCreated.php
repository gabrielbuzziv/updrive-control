<?php

namespace App\Mail;

use App\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountCreated extends Mailable
{

    use Queueable, SerializesModels;

    public $account;
    public $url;

    /**
     * AccountCreated constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $front_url = env('FRONTEND');
        $this->account = $account;
        $this->url = "http://{$this->account->slug}.{$front_url}/#/registrar?email={$this->account->email}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Seja bem vindo ao UP Drive';

        return $this
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($subject)
            ->view('emails.default', [
                'subject'       => $subject,
                'description'   => "
                    Parabéns por dar o primeiro passo, gostariamos de desejar as boas vindas.<br><br>
                    
                    O UP Drive é uma ferramenta desenvolvida para acelerar e facilitar o envio de documentos 
                    para seus clientes, de maneira segura, simples e rápida você envia documentos e os rastreia 
                    para garantir que toda a documentação chegou as mãos do cliente.<br>
                     
                     <ul>
                        <li>Envie documentos de maneira tão simples quanto enviar e-mails.</li>
                        <li>Saiba quando seu cliente baixou os documentos.</li>
                        <li>Protocolos de entrega automáticos.</li>
                        <li>Seja avisado quando seu cliente esquecer de abrir um documento.</li>
                     </ul>
                ",
                'action_button' => [
                    'href' => $this->url,
                    'text' => 'Começar agora',
                ],
                'regards'       => [
                    'name'  => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS'),
                ],
            ]);
    }
}
