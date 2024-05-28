<?php

class GitManager {
    private $repoDir;
    private $token;
    private $repoUrl;

    public function __construct($repo, $token) {
        // Directorio del repositorio
        $this->repoDir = __DIR__ . '/../../' . $repo;
        // Token de acceso personal
        $this->token = $token;
        // URL del repositorio con el token
        $this->repoUrl = "https://{$this->token}@github.com/ChemaSanchez11/{$repo}.git";
    }

    private function executeCommand($command) {
        $output = [];
        $returnVar = 0;
        // Ejecutar comando en el directorio del repositorio
        exec("cd {$this->repoDir} && $command", $output, $returnVar);
        return ['output' => $output, 'status' => $returnVar];
    }

    public function pull() {
        // Comando para resetear cambios locales
        $gitResetHard = 'git reset --hard HEAD';
        // Comando para hacer pull desde el repositorio remoto
        $gitPull = "git pull {$this->repoUrl}";

        // Resetea cualquier cambio local
        $resetResult = $this->executeCommand($gitResetHard);
        if ($resetResult['status'] !== 0) {
            return json_encode(['success' => false, 'output' => "Error ejecutando git reset: " . implode("\n", $resetResult['output'])]);
        }

        // Hace pull para traer los últimos cambios
        $pullResult = $this->executeCommand($gitPull);
        if ($pullResult['status'] !== 0) {
            return json_encode(['success' => false, 'output' => "Error ejecutando git pull: " . implode("\n", $pullResult['output'])]);
        }

        return json_encode(['success' => true, 'output' => implode("\n", $pullResult['output'])]);
    }

    public function reset() {
        // Comando para resetear cambios locales
        $gitResetHard = 'git reset --hard HEAD';

        // Resetea cualquier cambio local
        $resetResult = $this->executeCommand($gitResetHard);
        if ($resetResult['status'] !== 0) {
            return json_encode(['success' => false, 'output' => "Error ejecutando git reset: " . implode("\n", $resetResult['output'])]);
        }

        return json_encode(['success' => true, 'output' => implode("\n", $resetResult['output'])]);
    }

    public function getLogs($branch = 'main') {
        exec("cd {$this->repoDir} && git log -n 6 --pretty=format:\"%h - %s - %an - %ad\" $branch 2>&1", $output, $returnVar);

        // Inicializa un array para almacenar la información de los commits
        $commits = [];

        // Procesa cada línea para extraer la información del commit
        foreach ($output as $line) {
            // Divide la línea en partes usando ' - ' como delimitador
            $parts = explode(' - ', $line);

            // Crea un objeto DateTime a partir de la cadena de fecha
            $dateTime = DateTime::createFromFormat('D M j H:i:s Y O', $parts[3]);

            // Verifica si la conversión fue exitosa
            if ($dateTime !== false) {
                $parts[3] = $dateTime->format('d/m/Y H:i');
            }

            if (count($parts) === 4) {
                $commits[] = [
                    'hash' => $parts[0],
                    'message' => $parts[1],
                    'author' => $parts[2],
                    'date' => $parts[3]
                ];
            }
        }

        // Convierte el array de commits a JSON
        return json_encode(['success' => true, 'output' => $commits], JSON_PRETTY_PRINT);
    }
}