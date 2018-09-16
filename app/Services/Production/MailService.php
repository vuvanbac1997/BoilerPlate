<?php
namespace App\Services\Production;

use App\Services\MailServiceInterface;
use Aws\Ses\SesClient;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class MailService extends BaseService implements MailServiceInterface
{
    public function __construct()
    {
    }

    public function sendMail($title, $from, $to, $template, $data)
    {
        if (config('app.offline_mode')) {
            return true;
        }

        if (\App::environment() != 'production' && \App::environment() != 'development') {
            $title = '['.\App::environment().'] '.$title;
            $to    = [
                'address' => config('mail.tester'),
                'name'    => \App::environment().' Original: '.$to['address'],
            ];
        }

        $client = new SesClient([
            'credentials' => [
                'key'    => config('aws.key'),
                'secret' => config('aws.secret'),
            ],
            'region'      => config('aws.ses_region'),
            'version'     => 'latest',
        ]);

        try {
            $body    = \View::make($template, $data)->render();
            $sesData = [
                'Source'      => mb_encode_mimeheader($from['name']).' <'.$from['address'].'>',
                'Destination' => [
                    'ToAddresses' => [$to['address']],
                ],
                'Message'     => [
                    'Subject' => [
                        'Data'    => $title,
                        'Charset' => 'UTF-8',
                    ],
                    'Body'    => [
                        'Html' => [
                            'Data'    => $body,
                            'Charset' => 'UTF-8',
                        ],
                    ],
                ],
            ];
            $client->sendEmail($sesData);
        } catch (\Exception $e) {
            \Log::info($e);
        }

        return true;
    }

    public function sendEmailForgotPassWord($toUser, $token)
    {
        $subject  = trans('user.pages.emails.title.reset_email');
        $from     = config('mail.from');
        $to       = ['address' => $toUser->email, 'name' => $toUser->name];
        $template = 'emails.user.reset_password';
        $data     = [
            'subject' => $subject, 'token' => $token,
            'from'    => $from, 'to' => $to,
            'user'    => $toUser,
        ];
        $result   = $this->sendEmailCommon($subject, $from, $to, $template, $data);

        return $result;
    }

    private function sendEmailCommon($subject, $from, $to, $template, $data)
    {
        if (App::environment() === 'testing') {
            return true;
        } elseif (App::environment() === 'local') {
            $result = $this->sendMailLocal($template, $data);
        } else {
            $result = $this->sendMail($subject, $from, $to, $template, $data);
        }

        return $result;
    }

    private function sendMailLocal($template, $data)
    {
        Mail::send($template, $data, function ($message) use ($data) {
            $message->from($data['from']['address'], $data['from']['name']);
            $message->sender($data['from']['address'], $data['from']['name']);
            $message->subject($data['subject']);
            $message->to($data['to']['address'], $data['to']['name']);
        });
    }

    public function sendEmailContactUs($info)
    {
        $subject  = trans('user.pages.emails.title.contact_us');
        $from     = config('mail.from');
        $to       = config('mail.contact');
        $template = 'emails.user.contact_us';
        $data     = [
            'subject' => $subject,
            'from'    => $from, 'to' => $to,
            'user'    => $info,
        ];

        $result = $this->sendEmailCommon($subject, $from, $to, $template, $data);

        return $result;
    }
}
