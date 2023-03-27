<?php

namespace App\Command;

use App\Service\FruitDatasetUpdateNotifier;
use App\Service\FruitDatasetUpdater;
use App\Service\FruityviceClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fruit:update',
    description: 'Fetch a new fruits dataset from fruityvice.com, update the local db, email the results.',
)]
class FruitUpdateCommand extends Command
{
    public function __construct(
        private readonly FruityviceClient $client,
        private readonly FruitDatasetUpdater $updater,
        private readonly FruitDatasetUpdateNotifier $notifier
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('no-email', null, InputOption::VALUE_NONE, 'Skip email notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $noEmail = $input->getOption('no-email');

        $dataset = $this->client->getFruitAll();
        [$new, $updated] = $this->updater->update($dataset);
        !$noEmail && $this->notifier->notify($new, $updated);

        $io->success(
            sprintf(
                "Fruits dataset updated!\nNew: %d, updated: %d. Email: %s.",
                count($new),
                count($updated),
                $noEmail ? 'not sent' : 'sent'
            )
        );

        return Command::SUCCESS;
    }
}
