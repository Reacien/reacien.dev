<?php

/**
 * Build Info — exposes the active git commit, branch, build number,
 * and a "last push" timestamp to PHP templates.
 *
 * Resolution order (first hit wins):
 *   1. BUILD.json at the project root (written by GitHub Actions on push to main)
 *   2. The deployed .git directory (HEAD ref + mtime)
 *   3. Static defaults from site/config/config.php (reacien.version / reacien.commit)
 *
 * Result is memoised for the request lifetime.
 */

if (!function_exists('reacienBuildInfo')) {
    function reacienBuildInfo(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $kirby   = kirby();
        $rootDir = $kirby->root('index');

        $info = [
            'sha'       => null,
            'short'     => option('reacien.commit', 'dev'),
            'version'   => option('reacien.version', 'v0.0.0'),
            'branch'    => null,
            'run'       => null,
            'timestamp' => null,
            'source'    => 'config',
        ];

        // (1) BUILD.json written by the deploy workflow
        $buildJson = $rootDir . '/BUILD.json';
        if (is_file($buildJson)) {
            $raw = @file_get_contents($buildJson);
            $data = $raw ? @json_decode($raw, true) : null;
            if (is_array($data)) {
                $info = array_merge($info, $data, ['source' => 'BUILD.json']);
                if (!empty($info['sha'])) {
                    $info['short'] = substr($info['sha'], 0, 7);
                }
                return $cache = $info;
            }
        }

        // (2) Local .git directory (useful in dev / when .git is deployed)
        $gitHead = $rootDir . '/.git/HEAD';
        if (is_file($gitHead)) {
            $head = trim(@file_get_contents($gitHead) ?: '');
            $hash = null;
            $branch = null;
            $refMtime = null;

            if (strpos($head, 'ref: ') === 0) {
                $ref = trim(substr($head, 5));
                $branch = basename($ref);
                $refFile = $rootDir . '/.git/' . $ref;
                if (is_file($refFile)) {
                    $hash = trim(@file_get_contents($refFile) ?: '');
                    $refMtime = @filemtime($refFile) ?: null;
                } else {
                    // Packed refs fallback
                    $packed = $rootDir . '/.git/packed-refs';
                    if (is_file($packed)) {
                        foreach (file($packed, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                            if (substr($line, 0, 1) === '#' || substr($line, 0, 1) === '^') {
                                continue;
                            }
                            [$sha, $r] = preg_split('/\s+/', $line, 2) + [null, null];
                            if ($r === $ref) { $hash = $sha; break; }
                        }
                    }
                }
            } else {
                $hash = $head;
            }

            if ($hash) {
                $info = array_merge($info, [
                    'sha'       => $hash,
                    'short'     => substr($hash, 0, 7),
                    'branch'    => $branch,
                    'timestamp' => $refMtime,
                    'source'    => 'git',
                ]);
            }
        }

        return $cache = $info;
    }
}

if (!function_exists('reacienRelativeTime')) {
    function reacienRelativeTime(?int $timestamp): string
    {
        if (!$timestamp) {
            return 'just now';
        }
        $diff = time() - $timestamp;
        if ($diff < 0)      return 'just now';
        if ($diff < 60)     return $diff . 's ago';
        if ($diff < 3600)   return floor($diff / 60) . 'm ago';
        if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        if ($diff < 2592000) return floor($diff / 604800) . 'w ago';
        return floor($diff / 2592000) . 'mo ago';
    }
}

Kirby::plugin('reacien/build-info', []);
