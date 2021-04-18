<?php

namespace App\DataFixtures;

use App\Entity\ClientType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientTypeFixtures extends Fixture
{

    private $nameType;

    public function __construct()
    {
        $this->nameType = ['indywidualny', 'firma'];
    }

    public function load(ObjectManager $manager)
    {
        foreach($this->nameType as $name) {
            $clientType = new ClientType();
            $clientType->setName($name);
            $manager->persist($clientType);
        }

        $manager->flush();
    }
}
