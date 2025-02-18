<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Envía un correo de prueba';

    public function handle()
    {
        $this->info('Enviando correo de prueba...');

        try {
            Mail::raw('Correo de prueba desde Ciudad Limpia', function($message) {
                $message->to('test@example.com')
                        ->subject('Test Email');
            });

            $this->info('Correo enviado exitosamente!');
        } catch (\Exception $e) {
            $this->error('Error al enviar el correo:');
            $this->error($e->getMessage());
        }
    }
}
