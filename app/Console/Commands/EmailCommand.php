<?php

namespace App\Console\Commands;

use App\Events\SendedNewsletter;
use App\Models\User;
use App\Notifications\NewsletterNotification;
use Illuminate\Console\Command;

class EmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter {emails?*}: Correos electronicos a los que enviar {--s|schedule}: si se ejecuta desde cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un correo';

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
    public function handle()
    {
        $emails = $this->argument("emails");
        $schedule = $this->option('schedule');

        $builder = User::query();

        if ($emails) {
            $builder->whereIn('email', $emails);
        }

        $count = $builder->count();

        $this->output->progressStart($count);

        if ($count && ($schedule || $this->confirm('Estas seguro que quieres realizar esta accion?'))) {
            $builder->whereNotNull('email_verified_at')
                ->each(function (User $user) {
                    $this->output->progressAdvance();
                    $user->notify(new NewsletterNotification());
                });
            $this->output->progressFinish();
            $this->comment("Emails enviados correctamente");

            event(new SendedNewsletter());
        }
    }
}
