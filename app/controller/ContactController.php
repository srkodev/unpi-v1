<?php
namespace App\Controller;

use Exception;

class ContactController {
    private $resendApiKey;
    private $fromEmail;
    private $toEmail;
    private $fromName;

    public function __construct() {
        // Configuration depuis le fichier config.php
        $this->resendApiKey = RESEND_API_KEY;
        $this->fromEmail = CONTACT_FROM_EMAIL;
        $this->toEmail = CONTACT_TO_EMAIL; // Email de destination - modifiable dans config.php
        $this->fromName = CONTACT_FROM_NAME;
    }

    /**
     * Traiter l'envoi du formulaire de contact
     */
    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['success' => false, 'message' => 'Méthode non autorisée']);
        }

        // Validation des données
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = $this->validateForm($name, $email, $subject, $message);
        if (!empty($errors)) {
            return $this->jsonResponse(['success' => false, 'message' => implode(', ', $errors)]);
        }

        // Envoi de l'email via Resend
        $emailSent = $this->sendEmailViaResend($name, $email, $subject, $message);

        if ($emailSent) {
            return $this->jsonResponse(['success' => true, 'message' => 'Votre message a été envoyé avec succès !']);
        } else {
            return $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message. Veuillez réessayer.']);
        }
    }

    /**
     * Valider les données du formulaire
     */
    private function validateForm($name, $email, $subject, $message) {
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Le nom est requis';
        }

        if (empty($email)) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format d\'email invalide';
        }

        if (empty($subject)) {
            $errors[] = 'Le sujet est requis';
        }

        if (empty($message)) {
            $errors[] = 'Le message est requis';
        }

        return $errors;
    }

    /**
     * Envoyer l'email via l'API Resend
     */
    private function sendEmailViaResend($name, $email, $subject, $message) {
        $data = [
            'from' => $this->fromName . ' <' . $this->fromEmail . '>',
            'to' => [$this->toEmail], // Email de destination
            'subject' => '[Contact Site] ' . $subject,
            'html' => $this->buildEmailTemplate($name, $email, $subject, $message),
            'reply_to' => $email // Permet de répondre directement à l'expéditeur
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.resend.com/emails');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->resendApiKey,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Log pour debug
        error_log("Resend API Response: " . $response);
        error_log("HTTP Code: " . $httpCode);

        return $httpCode === 200;
    }

    /**
     * Construire le template HTML de l'email
     */
    private function buildEmailTemplate($name, $email, $subject, $message) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Nouveau message de contact</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #2c3e50; }
                .value { margin-top: 5px; padding: 10px; background-color: white; border-left: 3px solid #3498db; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Nouveau message de contact</h1>
                    <p>Reçu depuis le site FDPCI Aube</p>
                </div>
                <div class="content">
                    <div class="field">
                        <div class="label">Nom :</div>
                        <div class="value">' . htmlspecialchars($name) . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Email :</div>
                        <div class="value">' . htmlspecialchars($email) . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Sujet :</div>
                        <div class="value">' . htmlspecialchars($subject) . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Message :</div>
                        <div class="value">' . nl2br(htmlspecialchars($message)) . '</div>
                    </div>
                </div>
                <div class="footer">
                    <p>Ce message a été envoyé depuis le formulaire de contact du site FDPCI Aube</p>
                    <p>Vous pouvez répondre directement à cet email</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Changer l'email de destination
     * @param string $newEmail Nouvel email de destination
     */
    public function setDestinationEmail($newEmail) {
        if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->toEmail = $newEmail;
            return true;
        }
        return false;
    }

    /**
     * Obtenir l'email de destination actuel
     */
    public function getDestinationEmail() {
        return $this->toEmail;
    }

    /**
     * Réponse JSON
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 