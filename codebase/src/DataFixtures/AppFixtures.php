<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Blogger;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Types\This;

class AppFixtures extends Fixture
{

    private const CATEGORIES = ['Sport','Travel','Business','Culture'];
    private const ARTILCES = 12;
    private const BLOGGERS = 6;
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $count = \count(self::CATEGORIES);
        for($i=0;$i<$count;$i++)
        {
            $category = new Category();
            $category->setName(self::CATEGORIES[$i])
                     ->setHome(\rand(0,1));
            $manager->persist($category);
            $this->addReference('CAT'.$i,$category);
        }

        for ($i=0;$i<self::BLOGGERS;$i++)
        {
            $blogger = (new Blogger())
                ->setFullName(\sprintf("%s %s",$this->faker->firstName(),$this->faker->lastName()))
                ->setProfession($this->faker->jobTitle())
                ->setProfilePic('https://picsum.photos/300/300?random='.\rand(1,100));
            $manager->persist($blogger);
            $this->addReference('BLOGGER'.$i,$blogger);
        }



        for ($i=0;$i<self::ARTILCES;$i++)
        {
            $article = (new Article())
                ->setTitle($this->faker->text(15))
                ->setContent($this->faker->sentences(4,true))
                ->setCreationDate(new \DateTime())
                ->setPicture('https://picsum.photos/300/200?random='.\rand(1,100))
                ->setPopular(\rand(0,1))
                ->setTrending(\rand(0,1))
                ->setIntroduction($this->faker->sentence())
                ->setVisibility(false);
            for($j=0;$j<\rand(1,$count);$j++)
            {
                /** @var Category $category */
                $category = $this->getReference('CAT'.$j);
                $article->setCategory($category);
            }

            for($j=0;$j<\rand(1,self::BLOGGERS);$j++)
            {
                /** @var Blogger $blogger */
                $blogger = $this->getReference('BLOGGER'.$j);
                $article->setBlogger($blogger);
            }

            $manager->persist($article);
            $this->addReference('ART'.$i,$article);

        }


        $manager->flush();
    }
}
