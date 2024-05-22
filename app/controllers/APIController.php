<?php

$function = explode('api/', $_SERVER['REQUEST_URI'])[1];

switch ($function) {
    case 'renew':
        require_once (__DIR__ . '/../../functions/ssl_manager.php');
        $manager = new SSLCertificateManager();
        $privateKeyPEM = $manager->generateKeyPair();
        $certificatePEM = $manager->generateCertificate($privateKeyPEM, "avsa.dyndns.org");
        $manager->saveKeyAndCertificate($privateKeyPEM, $certificatePEM, "example.key", "example.crt");

        echo "Certificado generado exitosamente.\n";
        break;
}
