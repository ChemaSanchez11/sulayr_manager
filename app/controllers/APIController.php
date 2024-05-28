<?php

header('Content-Type: application/json');
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
    case 'git_pull':
        require_once (__DIR__ . '/../../functions/git_manager.php');

        $params = $_POST;

        $repo = $params['repository'];
        $token = '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';

        $gitManager = new GitManager($repo, $token);
        echo $gitManager->pull();
        break;
    case 'git_reset':
        require_once (__DIR__ . '/../../functions/git_manager.php');

        $params = $_POST;

        $repo = $params['repository'];
        $token = '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';

        $gitManager = new GitManager($repo, $token);
        echo $gitManager->reset();
        break;
    case 'git_log':
        require_once (__DIR__ . '/../../functions/git_manager.php');

        $params = $_POST;

        $repo = $params['repository'];
        $token = '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';

        $gitManager = new GitManager($repo, $token);
        echo $gitManager->getLogs();
        break;
}
