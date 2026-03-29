<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function runSqlFile(PDO $pdo, string $path): void
{
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        throw new RuntimeException("Kan SQL-bestand niet lezen: {$path}");
    }

    $delimiter = ';';
    $buffer = '';

    foreach ($lines as $line) {
        $trim = trim($line);

        if ($trim === '' || str_starts_with($trim, '--')) {
            continue;
        }

        if (preg_match('/^DELIMITER\s+(.+)$/i', $trim, $m) === 1) {
            $delimiter = $m[1];
            continue;
        }

        $buffer .= $line . PHP_EOL;

        if (str_ends_with(rtrim($buffer), $delimiter)) {
            $statement = rtrim($buffer);
            $statement = substr($statement, 0, -strlen($delimiter));

            if (trim($statement) !== '') {
                $pdo->exec($statement);
            }

            $buffer = '';
        }
    }

    if (trim($buffer) !== '') {
        $pdo->exec($buffer);
    }
}

$base = __DIR__ . '/../database/createscripts';
runSqlFile($pdo, $base . '/create_script_jamin.sql');

foreach (glob($base . '/sp_*.sql') as $spFile) {
    runSqlFile($pdo, $spFile);
}

echo "Database en stored procedures zijn opnieuw geladen." . PHP_EOL;
