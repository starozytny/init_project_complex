<?php


namespace App\Service;


use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseService
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function resetTable(SymfonyStyle $io, $manager, $list)
    {
        $em = $this->registry->getManager($manager);

        foreach ($list as $item) {
            $objs = $em->getRepository($item)->findAll();
            foreach($objs as $obj){
                $em->remove($obj);
            }

            $em->flush();
        }
        $io->text('Reset [OK]');
    }
}