<?php

namespace Database\Seeders\App;

use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        NotificationTemplate::truncate();

        NotificationEvent::withoutGlobalScope('name')->get()->map(function (NotificationEvent $event) {
            if ($event->name != 'user_invitation' && $event->name != 'password_reset' && $event->name != 'invoice_sent' && $event->name != 'user_invitation_canceled') {

                $mail = NotificationTemplate::query()->create([
                    'subject' => 'New Year Campaign Has Started',
                    'default_content' => '<h2>Happy New Year {name}</h2>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::create([
                    'subject' => null,
                    'default_content' => 'Happy New Year Folks {name}',
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $sms = NotificationTemplate::create([
                    'subject' => null,
                    'default_content' => 'Happy New Year {name}',
                    'custom_content' => null,
                    'type' => 'sms'
                ]);

                $event->templates()->attach(
                    [$database->id, $sms->id, $mail->id]
                );

            } else if ($event->name == 'user_invitation') {
                $mail = NotificationTemplate::create([
                    'subject' => 'User invitation form {company_name}',
                    'default_content' => '<p></p>
                    <p>
                        <span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br>
                    </p>
                    <p>
                        Hope this mail finds you well and healthy. We are informing you that you\'ve been invited to our application by {action_by}. It\'ll be a great opportunity to work with you.
                    </p><br>
                    
                    <p>
                        <a href="{invitation_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Accept Invitation</a>
                    </p><br>

                    <p></p>
                    <p>Thanks &amp; Regards,</p>
                    <p>{company_name}</p>',

                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );

            } else if ($event->name == 'user_invitation_canceled') {
                $mail = NotificationTemplate::create([
                    'subject' => 'Your invitation from {company_name} has been canceled',
                    'default_content' => '<p>Hi,</p>
                    <p>We are sorry to inform you that the invitation you have received from us has been canceled due to some reason. We hope to work with you in the future time in better condition</p><br>
                    <p>Thanks for being with us<br>{company_name}</p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );

            } else if ($event->name == 'password_reset') {
                $mail = NotificationTemplate::create([
                    'subject' => 'Password reset link provided by {company_name}',
                    'default_content' => '
                     <p></p>
                     <p>
                        <span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br></p><p>Your request for reset password has been approved from {company_name}. Press the button below to reset the password.
                     </p><br>
                    <p><a href="{reset_password_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Reset password</a></p><br>We are highly expecting you as soon as possible. Hope you\'ll join us.
                    <p></p><p>Thanks for being with us.
                    </p><p>Regards,</p><p>{company_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'invoice_sent') {
                $mail = NotificationTemplate::create([
                    'subject' => 'Invoice {invoice_number} for {invoice_date}',
                    'default_content' => '<p></p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hello,</span><br></p>
                    <p>Hope you’re doing well.<br>
                        Please see attached invoice {invoice_number}.<br>
                        Don’t hesitate to contact us if you have any questions.
                    </p>
                    <p></p><p>Thanks for being with us.
                    </p><p>Regards,</p><p>{company_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            }
        });
        $this->enableForeignKeys();
    }
}
