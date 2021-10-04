<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $drh=new User;
        $drh->setEmail('rh@humanbooster.com');
        $drh->setRoles(['ROLE_RH']);
        $drh->setPassword($this->encoder->hashPassword($drh, 'rh123@'));
        $drh->setLastname('Lahaie');
        $drh->setFirstname('Brigitte');
        $drh->setImage('drh.jpg');
        $drh->setContrat('CDI');
        $manager->persist($drh);

        $info=new User;
        $info->setEmail('info@humanbooster.com');
        $info->setRoles(['ROLE_INFO']);
        $info->setPassword($this->encoder->hashPassword($info, 'momo69'));
        $info->setLastname('Momo');
        $info->setFirstname('Ahmed');
        $info->setImage('info.jpg');
        $info->setContrat('Intérim');
        $info->setDateEnd(new \DateTime('08/10/2021'));
        $manager->persist($info);




        $manager->flush();
    }
}
