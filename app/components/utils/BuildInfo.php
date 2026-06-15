<?php

class BuildInfo
{
    public static function resolveGitDirectory($basePath)
    {
        $gitPath = $basePath . DIRECTORY_SEPARATOR . '.git';

        if (is_dir($gitPath)) {
            return $gitPath;
        }

        if (!is_readable($gitPath)) {
            return null;
        }

        $gitReference = trim((string) file_get_contents($gitPath));
        if (strpos($gitReference, 'gitdir:') !== 0) {
            return null;
        }

        $gitDirectory = trim(substr($gitReference, 7));
        if ($gitDirectory === '') {
            return null;
        }

        if ($gitDirectory[0] !== DIRECTORY_SEPARATOR && !preg_match('/^[A-Za-z]:[\\\\\/]/', $gitDirectory)) {
            $gitDirectory = $basePath . DIRECTORY_SEPARATOR . $gitDirectory;
        }

        return is_dir($gitDirectory) ? $gitDirectory : null;
    }

    public static function resolveBuildCommit($basePath)
    {
        foreach (['TAG_BUILD_COMMIT', 'GIT_COMMIT', 'COMMIT_SHA', 'SOURCE_COMMIT', 'BUILD_COMMIT'] as $environmentName) {
            $commit = trim((string) getenv($environmentName));
            if ($commit !== '') {
                return substr($commit, 0, 12);
            }
        }

        $gitDirectory = self::resolveGitDirectory($basePath);
        if ($gitDirectory === null) {
            return '';
        }

        $headFile = $gitDirectory . DIRECTORY_SEPARATOR . 'HEAD';
        if (!is_readable($headFile)) {
            return '';
        }

        $head = trim((string) file_get_contents($headFile));
        if ($head === '') {
            return '';
        }

        if (strpos($head, 'ref:') !== 0) {
            return substr($head, 0, 12);
        }

        $ref = trim(substr($head, 4));
        $refFile = $gitDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $ref);
        if (is_readable($refFile)) {
            return substr(trim((string) file_get_contents($refFile)), 0, 12);
        }

        $packedRefs = $gitDirectory . DIRECTORY_SEPARATOR . 'packed-refs';
        if (!is_readable($packedRefs)) {
            return '';
        }

        foreach (file($packedRefs, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if ($line[0] === '#') {
                continue;
            }

            if (preg_match('/^([a-f0-9]{40})\s+' . preg_quote($ref, '/') . '$/i', $line, $matches)) {
                return substr($matches[1], 0, 12);
            }
        }

        return '';
    }
}
