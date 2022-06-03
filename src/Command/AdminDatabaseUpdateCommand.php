<?php

namespace App\Command;

use App\Entity\Main\User;
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

        $noDuplication = [];
        $objs = $this->em->getRepository(User::class)->findAll();

        /** @var User $obj */
        foreach($objs as $obj){
            if(!in_array($obj->getManager(), $noDuplication)){
                $noDuplication[] = $obj->getManager();

                $command = $this->getApplication()->find('do:sc:up');
                $arguments = [
                    'command' => 'do:sc:up',
                    '--force' => true,
                    '--em' => $obj->getManager()
                ];
                $greetInput = new ArrayInput($arguments);
                try {
                    $command->run($greetInput, $output);
                } catch (\Exception $e) {
                    $io->error('Erreur run do:sc:up : ' . $e);
                }
            }
        }

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}
