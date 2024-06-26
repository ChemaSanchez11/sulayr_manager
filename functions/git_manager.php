<?php

class GitManager {
    private $repoDir;
    private $token;
    private $repoUrl;

    public function __construct($repo) {
        // Directorio del repositorio
        $this->repoDir = __DIR__ . '/../../' . $repo;
        // Token de acceso personal
        $this->token = getenv('GIT_TOKEN');
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
        // Ruta del repositorio
        $repoDir = escapeshellarg($this->repoDir);

        // Obtiene los commits locales
        exec("cd $repoDir && git log --pretty=format:\"%h - %s - %an - %ad\" $branch 2>&1", $localCommitsOutput, $localCommitsReturnVar);

        if ($localCommitsReturnVar !== 0) {
            return json_encode(['success' => false, 'error' => 'Failed to get local git logs', 'output' => $localCommitsOutput], JSON_PRETTY_PRINT);
        }

        // Obtiene los commits remotos
        exec("cd $repoDir && git log --pretty=format:\"%h - %s - %an - %ad\" origin/$branch 2>&1", $remoteCommitsOutput, $remoteCommitsReturnVar);

        if ($remoteCommitsReturnVar !== 0) {
            return json_encode(['success' => false, 'error' => 'Failed to get remote git logs', 'output' => $remoteCommitsOutput], JSON_PRETTY_PRINT);
        }

        // Inicializa arrays para almacenar la información de los commits locales y remotos
        $localCommits = [];
        $remoteCommits = [];

        // Procesa los commits locales
        foreach ($localCommitsOutput as $line) {
            $parts = explode(' - ', $line);
            if (count($parts) === 4) {
                $localCommits[] = [
                    'hash' => $parts[0],
                    'message' => $parts[1],
                    'author' => $parts[2],
                    'date' => $parts[3],
                    'update' => true
                ];
            }
        }

        // Procesa los commits remotos
        foreach ($remoteCommitsOutput as $line) {
            $parts = explode(' - ', $line);
            if (count($parts) === 4) {
                $remoteCommits[] = [
                    'hash' => $parts[0],
                    'message' => $parts[1],
                    'author' => $parts[2],
                    'date' => $parts[3],
                    'update' => false
                ];
            }
        }

        // Filtra los commits que están en el remoto pero no en el local
        $newCommits = array_filter($remoteCommits, function($commit) use ($localCommits) {
            foreach ($localCommits as $localCommit) {
                if ($commit['hash'] === $localCommit['hash']) {
                    return false;
                }
            }
            return true;
        });

        // Combina los nuevos commits con los commits locales
        $commits = array_merge(array_values($newCommits), $localCommits);

        // Marca el último commit local con last_update
        if (!empty($localCommits)) {
            $commits[count($newCommits)]['last_update'] = true;
        }

        // Devuelve el resultado en JSON
        return json_encode([
            'success' => true,
            'output' => $commits
        ], JSON_PRETTY_PRINT);
    }

}