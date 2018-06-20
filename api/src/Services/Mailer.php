<?php
namespace App\Services;

use Psr\Container\ContainerInterface;
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24/10/2017
 * Time: 10:39
 */
class Mailer
{
    const SERVICE_NAME = 'app.mailer';
    private $mailer;
    private $container;

    public function __construct(ContainerInterface $container, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param $subject
     * @param $mailTo
     * @param $template
     * @param null $data
     * @return int
     */
    public function sendMail($subject, $mailTo, $template, $data = null)
    {
        $container = $this->container;
        $user = $container->get('security.token_storage')->getToken()->getUser();
        $mail = $this->mailer;
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom([$user->getEmail() => $user->getPrenom() . " " . $user->getNom()])
            ->setTo($mailTo)
            ->setBody(
                $template,
                'text/html'
            );
        if (isset($data['pj'])) {
            foreach ($data['pj'] as $fichier) {
                if ($fichier instanceof \Swift_Attachment) {
                    $message->attach($fichier);
                } else {
                    $message->attach(\Swift_Attachment::fromPath($fichier));
                }
            }
        }
        if (isset($data['cc'])) {
            foreach ($data['cc'] as $cc) {
                if ($cc) {
                    $message->addCc($cc);
                }
            }
        }
        if (isset($data['cci'])) {
            foreach ($data['cci'] as $cci) {
                if ($cci) {
                    $message->addBcc($cci);
                }
            }
        }
        return $mail->send($message);
    }

    /**
     * @param $subject
     * @param $mailTo
     * @param $template
     * @param null $dataFrom
     * @return null
     */
    public function sendMailGrid($subject, $mailTo, $template, $dataFrom = null)
    {
        $container = $this->container;
        $data = [];
        if (isset($dataFrom['pj'])) {
            foreach ($dataFrom['pj'] as $fichier) {
                $attachments = new \stdClass();
                if ($fichier instanceof \Swift_Attachment) {
                    $attachments->type = $fichier->getContentType();
                    $attachments->filename = $fichier->getFilename();
                    $attachments->content = base64_encode($fichier->getBody());
                } else {
                    $fileObject = \Swift_Attachment::fromPath($fichier);
                    $attachments->type = $fileObject->getContentType();
                    $attachments->filename = $fileObject->getFilename();
                    $attachments->content = base64_encode(file_get_contents($fichier));
                }
                $data['attachments'][] = $attachments;
            }
        }
        $data['personalizations'] = [];
        $pres = new \stdClass();
        foreach ($mailTo as $destinataire) {
            if ($destinataire) {
                $to = new \stdClass();
                $to->email = $destinataire;
                $pres->to[] = $to;
            }
        }
        if (isset($dataFrom['cc'])) {
            foreach ($dataFrom['cc'] as $ccMail) {
                if ($ccMail) {
                    $cc = new \stdClass();
                    $cc->email = $ccMail;
                    $pres->cc[] = $cc;
                }
            }
        }
        if (isset($dataFrom['cci'])) {
            foreach ($dataFrom['cci'] as $cciMail) {
                if ($cciMail) {
                    $cci = new \stdClass();
                    $cci->email = $cciMail;
                    $pres->bcc[] = $cci;
                }
            }
        }
        $pres->subject = $subject;
        if (isset($dataFrom['send_at'])) {
            $dateRelance = new \DateTime($dataFrom['send_at']->format("Y-m-d H:i:s"), new \DateTimeZone('Europe/Rome'));
            $pres->send_at = $dateRelance->getTimestamp();
        }
        $data['personalizations'][] = $pres;
        $from = new \stdClass();
        $from->email = "no-replay@neobe.com";
        $from->name = "NEOBE";
        $data['from'] = $from;
        $data['content'] = [];
        $content = new \stdClass();
        $content->type = "text/html";
        $content->value = $template;
        $data['content'][] = $content;
        $body = json_encode($data);
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            "Authorization" => "Bearer " . $container->getParameter('api_key_send_grid')
        ];
        \Unirest\Request::verifyPeer(false);
        $response = \Unirest\Request::post('https://api.sendgrid.com/v3/mail/send', $headers, $body);
        $response->code;
        $response->headers;
        $response->body;
        $response->raw_body;
        if (isset($response->headers['X-Message-Id'])) {
            return $response->headers['X-Message-Id'];
        }
        return null;
    }


}
