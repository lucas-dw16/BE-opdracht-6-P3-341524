<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportStoredProcedures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import-sp {path=database/createscripts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import .sql files from the createscripts folder into the connected database (will strip DELIMITER statements)';

    public function handle(): int
    {
        $relative = $this->argument('path');
        $path = base_path($relative);

        if (!is_dir($path)) {
            $this->error("Path not found: {$path}");
            return 1;
        }

        $files = glob($path . DIRECTORY_SEPARATOR . '*.sql');
        if (empty($files)) {
            $this->info('No .sql files found in ' . $path);
            return 0;
        }

        foreach ($files as $file) {
            $this->line("Importing: {$file}");
            $sql = file_get_contents($file);
            if ($sql === false) {
                $this->error('Failed to read ' . $file);
                continue;
            }

            // Remove DELIMITER markers (MySQL client syntax) and convert custom delimiters ($$) to standard semicolons.
            $sql = preg_replace('/DELIMITER\s+\$\$/i', '', $sql);
            $sql = str_replace('$$', ';', $sql);

            try {
                DB::unprepared($sql);
                $this->info("Imported: {$file}");
            } catch (\Exception $e) {
                $this->error('Error importing ' . $file . ': ' . $e->getMessage());
                return 1;
            }
        }

        $this->info('All SQL files imported successfully.');
        return 0;
    }
}
