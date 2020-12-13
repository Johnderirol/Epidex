<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\Rayon;
use App\Entity\Skill;
use App\Entity\Mission;
use Cocur\Slugify\Slugify;
use App\Entity\Collaborateur;
use App\Entity\Evaluation;
use App\Entity\Secteur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CollaborateurFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder; 
    }
    
    public function load(ObjectManager $manager)
    {
        
        $faker = Factory::create('fr_FR');
        
        //Nous gérons les Collaborateurs
        $collabs = []; 
            
        $genres = ['male','female'];
            
        for($i=1; $i <=mt_rand(3, 20); $i++) {
        
        $collab = new Collaborateur();
            
        $genre = $faker->randomElement($genres);
            
        $picture = 'https://randomuser.me/api/portraits/';
        $pictureId = $faker->numberBetween(1,99) . '.jpg';
            
        //if($genre == "male") $picture = $picture . 'men/' . $pictureId;
        //else ($genre == "female") $picture = $picture . 'women/' . $pictureId;
        $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId; 
            
            
        $nom = $faker->lastName();
        $prenom = $faker->firstName($genre);
        $matricule = ($faker->numberBetween($min = 100000, $max = 700000));    
        $statut = array("Employé(e)", "Agent de Maitrise", "Cadre");
        $hash = $this->encoder->encodePassword($collab, 'password');
        
        
        $collab ->setNom($nom)
                ->setPrenom($prenom)
                ->setMatricule($matricule)
                ->setEmail($faker->email)
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)). '</p>')
                ->setHash($hash)
                ->setStatut($statut[array_rand($statut, 1)])
                ->setPicture($picture);
            
        $manager->persist($collab);
        $collabs[] = $collab;
              
        }

        //Nous gérons les utilisateurs
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new Collaborateur();
        $adminUser->setPrenom('David')
                    ->setNom('Larralde')
                    ->setMatricule('359485')
                    ->setEmail('david.larralde@leroymerlin.fr')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture('https://i.pinimg.com/originals/40/58/0f/40580f2655bd32d3158f55b74ce82eb1.jpg')
                    ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)). '</p>')
                    ->setStatut('Employé(e)')
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);

        $manager->flush();
    }
}
