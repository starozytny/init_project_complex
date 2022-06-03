<?php

namespace App\Command;

use App\Entity\Main\Notification;
use App\Entity\Main\User;
use App\Service\Data\Main\DataUser;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminUsersCreateCommand extends Command
{
    protected static $defaultName = 'admin:users:create';
    protected static $defaultDescription = 'Create an user and an admin.';
    private $em;
    private $databaseService;
    private $dataUser;

    public function __construct(EntityManagerInterface $entityManager, DatabaseService $databaseService, DataUser $dataUser)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->databaseService = $databaseService;
        $this->dataUser = $dataUser;
    }

    protected function configure()
    {
        $this
            ->addOption('fake', "f", InputOption::VALUE_NONE, 'Option shit values')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $this->databaseService->resetTable($io, "default", [Notification::class, User::class]);

        $users = [
            [
                'username' => 'shanbo',
                'firstname' => 'Dev',
                'lastname' => 'Shanbora',
                'email' => 'chanbora.chhun@outlook.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN', 'ROLE_DEVELOPER']
            ],
            [
                'username' => 'staro',
                'firstname' => 'Admin',
                'lastname' => 'Starozytny',
                'email' => 'starozytny@hotmail.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN']
            ],
            [
                'username' => 'shanks',
                'firstname' => 'User',
                'lastname' => 'Shanks',
                'email' => 'shanks@hotmail.fr',
                'roles' => ['ROLE_USER']
            ]
        ];

        $password = password_hash("azerty", PASSWORD_ARGON2I);

        $io->title('Création des utilisateurs');
        foreach ($users as $user) {
            $data = [
                "username" => $user['username'],
                "email" => $user['email'],
                "roles" => $user['roles'],
                "firstname" => $user['firstname'],
                "lastname" => $user['lastname'],
                "manager" => "default",
            ];

            $data = json_decode(json_encode($data));
            $obj = $this->dataUser->setData(new User(), $data);
            $obj->setPassword($password);

            $this->em->persist($obj);
            $io->text('USER : ' . $user['username'] . ' créé' );
        }

        if ($input->getOption('fake')) {
            $io->title('Création de 110 utilisateurs lambdas');
            $fake = Factory::create();
            for($i=0; $i<110 ; $i++) {
                $data = [
                    "username" => $fake->userName,
                    "email" => $fake->freeEmail,
                    "roles" => ['ROLE_USER'],
                    "firstname" => $fake->firstName,
                    "lastname" => $fake->lastName,
                    "manager" => "default",
                ];

                $data = json_decode(json_encode($data));
                $obj = $this->dataUser->setData(new User(), $data);
                $obj->setPassword($password);

                $this->em->persist($obj);
            }
            $io->text('USER : Utilisateurs fake créés' );
        }

        $this->em->flush();

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return 0;
    }
}
