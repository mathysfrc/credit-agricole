<?php

namespace App\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserVerificationListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        // Vérifie si l'entité est un utilisateur et si le champ isVerified est modifié
        if ($entity instanceof \App\Entity\User && $args->hasChangedField('isVerified')) {
            $isVerified = $args->getNewValue('isVerified');

            // Si isVerified est vrai, envoie un e-mail
            if ($isVerified) {
                $this->envoyerEmailVerification($entity->getEmail());
            }
        }
    }

    private function envoyerEmailVerification($email)
    {
        // Logique pour envoyer un e-mail
        $email = (new Email())
            ->from('contact@isf-boutique.fr')
            ->to($email)
            ->subject('Vérification de l\'utilisateur')
            ->html($this->generateVerificationEmailContent());

        $this->mailer->send($email);
    }

    private function generateVerificationEmailContent()
    {
        // Générer le contenu HTML de l'e-mail
        $content = '
            <html>
                <head>
                    <style>
                        body {
                            font-family: "Arial", sans-serif;
                            background-color: #111827;
                            color: #FFFFFF;
                            padding: 20px;
                        }
    
                        p {
                            margin-bottom: 10px;
                        }
    
                        .message {
                            font-size: 16px;
                            font-weight: bold;
                        }
    
                        .signature {
                            margin-top: 20px;
                            font-style: italic;
                        }
                    </style>
                </head>
                <body>
                <img class="h-12 w-auto" src="{{ asset(\'build/images/ISF.svg\') }}" alt="ISF">
                <p class="message">Merci de vous être inscrit sur notre site. Votre compte a été vérifié avec succès.</p>
                    <p>Continuez à profiter de nos services!</p>
                    <p class="signature">Cordialement,<br> L\'équipe de ISF Boutique</p>
                </body>
            </html>
        ';
    
        return $content;
    }
}