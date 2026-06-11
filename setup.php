<?php

echo "\n";
echo "  ╔══════════════════════════════════════════════╗\n";
echo "  ║          Helix-Forge v1.0.0                  ║\n";
echo "  ║  AI-native PHP framework for APIs & web apps  ║\n";
echo "  ╚══════════════════════════════════════════════╝\n\n";

// Copy .env if not exists
$envFile = __DIR__ . '/.env';
$envExample = __DIR__ . '/.env.example';

if (!file_exists($envFile) && file_exists($envExample)) {
    copy($envExample, $envFile);
    echo "  ✓ Created .env file\n";
}

// Ensure storage directories
$dirs = [
    __DIR__ . '/storage/framework/cache',
    __DIR__ . '/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
        echo "  ✓ Created " . str_replace(__DIR__ . '/', '', $dir) . "/\n";
    }
}

// SQLite database
$dbPath = __DIR__ . '/storage/database.sqlite';
if (!file_exists($dbPath)) {
    file_put_contents($dbPath, '');
    echo "  ✓ Created storage/database.sqlite\n";
}

echo "\n";
echo "  ──────────────────────────────────────────────\n";
echo "  🚀  Start the dev server:\n";
echo "     php helix serve\n\n";
echo "  🌐  Open in browser:\n";
echo "     http://127.0.0.1:8080\n\n";
echo "  📖  Read the docs:\n";
echo "     http://127.0.0.1:8080/docs\n\n";
echo "  ──────────────────────────────────────────────\n\n";
