<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/*Sobreescribe los mensajes pero no la plantilla para mail ResetPassword*/
class MyResetPassword extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Recuperar contraseña')
                ->greeting('Hola')
                ->line('Recibió este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
                ->action('Recuperar contraseña', url('password/reset', $this->token))
                ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna acción adicional.');
    }

}
