<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $batchSize = 1000;

        for ($i = 0; $i < 1000000; $i++) {
            $book = new Book();
            $book->setTitle($faker->word);
            $book->setAuthor($faker->name);
            $book->setDescription($faker->text);
            $book->setIsbn($faker->isbn13());
            $book->setYear($faker->year());
            $book->setCover($faker->imageUrl());

            $manager->persist($book);

            if (($i + 1) % $batchSize === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
        $manager->clear();
    }

}
