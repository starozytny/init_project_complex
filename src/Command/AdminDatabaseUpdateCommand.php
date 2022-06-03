<?php

namespace App\Command;

use App\Entity\Society;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminDatabaseUpdateCommand extends Command
{
    protected static $defaultName = 'admin:database:update';
    protected static $defaultDescription = 'Updates databases';
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->em = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

//        $societies = $this->em->getRepository(Society::class)->findAll();
//        foreach($societies as $society){
//            $command = $this->getApplication()->find('do:sc:up');
//            $arguments = [
//                'command' => 'do:sc:up',
//                '--force' => true,
//                '--em' => $society->getManager()
//            ];
//            $greetInput = new ArrayInput($arguments);
//            try {
//                $command->run($greetInput, $output);
//            } catch (\Exception $e) {
//                $io->error('Erreur run do:sc:up : ' . $e);
//            }
//        }

        $command = $this->getApplication()->find('do:sc:up');
        $arguments = [
            'command' => 'do:sc:up',
            '--force' => true,
            '--em' => "client1"
        ];
        $greetInput = new ArrayInput($arguments);
        try {
            $command->run($greetInput, $output);
        } catch (\Exception $e) {
            $io->error('Erreur run do:sc:up : ' . $e);
        }

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}
