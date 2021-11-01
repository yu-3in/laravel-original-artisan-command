<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ArtisanMigrateFreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:f {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migrations';

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
                $cmd = 'migrate:fresh';
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
