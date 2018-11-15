<?php

namespace App\DataFixtures;

use App\Entity\Coffee;
use App\Entity\CoffeeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
         * Création des CoffeeTypes
         */
        $coffeeTypes = array(
            new CoffeeType('ristretto'),
            new CoffeeType('espresso'),
            new CoffeeType('lungo')
        );

        foreach ($coffeeTypes as $coffeeType) {
            $manager->persist($coffeeType);
        }

        /*
         * Création des Coffees
         */
        $coffees = array(
            new Coffee("/static/coffee/ristretto-intenso.png", "Ristretto Intenso", "Exceptionnelement intense et onctueux", 12, $coffeeTypes[0], 0.3),
            new Coffee("/static/coffee/espresso-forte.png", "Espresso Forte", "Rond et équilibré", 7, $coffeeTypes[1], 0.3),
            new Coffee("/static/coffee/lungo-origin-guatemala.png", "Lungo Origin Guatemala", "Soyeux et affirmé", 6, $coffeeTypes[2], 0.35),
            new Coffee("/static/coffee/espresso-decaffeinato.png", "Espresso Decaffeinato", "Dense et puissant", 7, $coffeeTypes[1], 0.3),
            new Coffee("/static/coffee/espresso-vanilla.png", "Espresso Vanilla", "Aux arômes naturels de vanille", 7, $coffeeTypes[1], 0.35),
            new Coffee("/static/coffee/ristretto-origin-india.png", "Ristretto Origin India", "Intense et épicé", 10, $coffeeTypes[0], 0.35),
            new Coffee("/static/coffee/espresso-leggero.png", "Espresso Leggero", "Léger et rafraîchissant", 6, $coffeeTypes[1], 0.3),
            new Coffee("/static/coffee/lungo-forte.png", "Lungo Forte", "Élégant et torréfié", 4, $coffeeTypes[2], 0.3),
            new Coffee("/static/coffee/lungo-decaffeinato.png", "Lungo Decaffeinato", "Equilibré et complexe", 4, $coffeeTypes[2], 0.3),
            new Coffee("/static/coffee/espresso-caramel.png", "Espresso Caramel", "Aux arômes naturels de caramel", 7, $coffeeTypes[1], 0.35),
            new Coffee("/static/coffee/ristretto.png", "Ristretto", "Intense et persistant", 9, $coffeeTypes[0], 0.3),
            new Coffee("/static/coffee/espresso-origin-brazil.png", "Espresso Origin Brazil", "Doux et satiné", 4, $coffeeTypes[1], 0.35),
            new Coffee("/static/coffee/lungo-leggero.png", "Lungo Leggero", "Fleuri et Rafraîchissant", 2, $coffeeTypes[2], 0.3)
        );

        foreach ($coffees as $coffee) {
            $manager->persist($coffee);
        }
        $manager->flush();
    }
}
