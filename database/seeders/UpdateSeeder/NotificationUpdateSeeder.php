<?php
namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationAudience;
use App\Models\Core\Setting\NotificationEvent;
use App\Models\Core\Setting\NotificationSetting;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
class NotificationUpdateSeeder extends Seeder
{
    use DisableForeignKeys;

    protected array $new_events = [
        'user_create',
    ];

    protected array $system_notificaiton_not_allowed_list = [
        'user_create'
    ];

    protected array $mail_notificaiton_not_allowed_list = [
    ];

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->newNotificationSeeder();

        $this->enableForeignKeys();
    }


    public function newNotificationSeeder() {
        $appTypeId = Type::findByAlias('app')->id;

        $new_events = $this->new_events ?? [];
        foreach ($new_events as $event) {
            $eventData = [
                'name' => $event,
                'type_id' => $appTypeId,
            ];
            $event = NotificationEvent::query()->create($eventData);
            [$name, $action] = explode('_', $event->name);

            $templates = [
                'system' => '',
                'subject' => '',
                'content' => ''
            ];
            if (array_key_exists($event->name, $this->template())) {
                $templates = $this->template()[$event->name];
            }

            $channels = [];

            $system_notificaiton_not_allowed_list = $this->system_notificaiton_not_allowed_list ?? [];
            if (!in_array($event->name, $system_notificaiton_not_allowed_list)) {
                $database = NotificationTemplate::create([
                    'subject' => null,
                    'default_content' => strtr($templates['system'], [
                        '{resource}' => $name
                    ]),
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach(
                    [$database->id]
                );
                $channels[] = 'database';
            }

            $mail_notificaiton_not_allowed_list = $this->mail_notificaiton_not_allowed_list ?? [];
            if (!in_array($event->name, $mail_notificaiton_not_allowed_list)) {
                $mail = NotificationTemplate::query()->create([
                    'subject' => strtr($templates['subject'], [
                        '{resource}' => $name,
                        '{app_name}' => $event->type->alias == 'app' ? '{app_name}' : '{brand_name}'
                    ]),
                    'default_content' => strtr($templates['content'], [
                        '{resource}' => $name,
                        '{button_label}' => 'View ' . ucfirst($name)
                    ]),
                    'custom_content' => null,
                    'type' => 'mail'
                ]);
                $event->templates()->attach(
                    [$mail->id]
                );
                $channels[] = 'mail';
            }

            if(count($channels) === 0) continue;

            $adminRoles = Role::query()
                ->where('is_admin', 1)
                ->whereHas('type', function (Builder $query) {
                    $query->where('alias', 'app');
                })->get()
                ->pluck('id');

            $notification_setting = NotificationSetting::query()->create([
                'notification_event_id' => $event->id,
                'notify_by' => $channels
            ]);

            $notification_setting->audiences()->saveMany([
                new NotificationAudience([
                    'audience_type' => 'roles',
                    'audiences' => $adminRoles
                ])
            ]);
        }
    }

    public function template()
    {
        return [
            'user_create' => [
                'system' => '',
                'subject' => 'Account has been created form {app_name}',
                'content' => '<p><img src="{company_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>Hope this mail finds you well and healthy. You have been added to our company as an employee.
<p>Your Login credentials are below, </p> 
<p>Email : {email} </p> 
<p>Password : {password}</p>
<br>
<p>Please use these credentials to login into your account.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Go to your account</a></p><br>
<p>You can change your password from your account password settings.</p>
Hope you will find useful!
<p></p><p>Thanks &amp; Regards,
</p><p>{app_name}</p>',
            ],
        ];
    }
}
