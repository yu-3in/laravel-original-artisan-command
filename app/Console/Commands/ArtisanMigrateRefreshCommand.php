<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ArtisanMigrateRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rf {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and re-run all migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $config = Config::get('migration');
        $migrations = $config['files'];
        $base_path = $config['base_path'];
        $prefix = $config['prefix'];
        $suffix = $config['suffix'];

        $is_first = true;

        foreach ($migrations as $migration) {
            $path = $base_path . $prefix . $migration . $suffix;

            if ($is_first) {
                $cmd = 'migrate:refresh';
                $is_first = false;
            } else {
                $cmd = 'migrate';
            }

            $this->call($cmd, [
                '--path' => $path,
            ]);
        }

        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        return Command::SUCCESS;
    }
}
