<?php

namespace App\Command\Fake;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FakeStripeProductCommand extends Command
{
    protected static $defaultName = 'fake:stripe:product';
    protected static $defaultDescription = 'Test fake product stripe API';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stripe = new \Stripe\StripeClient('sk_test_51Kb5F2HZRd7sklEaGiVozh4jIRk65gWOCFqZqNFJCDUTkDmlZItmyvHEcm7rvdCnca7xuVNZMrIdfqNIRzEq1CiB00pLy13SpI');

        $est = $stripe->prices->create(
            [
                'currency' => 'usd',
                'unit_amount' => 120000,
                'product_data' => ['name' => 'stand up paddleboard'],
            ]
        );

        $io->text($est);

        $io->text('PRODUIT : Produit créé, vérifier sur le dashboard de stripe.' );

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}
