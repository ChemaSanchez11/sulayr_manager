<?php

class SSLCertificateManager {
    public function generateKeyPair() {
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            'config' => 'C:\xampp\apache\conf\openssl.cnf'
        );
        $privateKey = openssl_pkey_new($config);
        openssl_pkey_export($privateKey, $privateKeyPEM);
        return $privateKeyPEM;
    }

    public function generateCertificate($privateKeyPEM, $commonName) {
        $dn = array(
            "commonName" => $commonName,
        );
        $csr = openssl_csr_new($dn, $privateKeyPEM, ['config' => 'C:\xampp\apache\conf\openssl.cnf']);
        var_dump(openssl_error_string());
        $x509 = openssl_csr_sign($csr, null, $privateKeyPEM, 365, ['config' => 'C:\xampp\apache\conf\openssl.cnf']);
        openssl_x509_export($x509, $certificatePEM);
        return $certificatePEM;
    }

    public function saveKeyAndCertificate($privateKeyPEM, $certificatePEM, $keyFilename, $certFilename) {
        file_put_contents(__DIR__ . 'prueba.txt', 'hola');
        file_put_contents($keyFilename, $privateKeyPEM);
        file_put_contents($certFilename, $certificatePEM);
    }
}