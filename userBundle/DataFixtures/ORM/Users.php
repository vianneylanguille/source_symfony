<?php

namespace eclore\userBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use eclore\userBundle\Entity\User;

class Users implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $noms = array('winzou', 'John', 'Talus');

    foreach ($noms as $i => $nom) {
      // On crée l'utilisateur
      $users[$i] = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques
      $users[$i]->setUsername($nom);
      $users[$i]->setPassword($nom);
      $users[$i]->setEmail($nom);
      $users[$i]->setEnabled(false);

      // On le persiste
      $manager->persist($users[$i]);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}