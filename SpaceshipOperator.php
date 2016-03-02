<?php

class Spaceship {
    public $name;
    public $maxSpeed;

    public function __construct($name, $maxSpeed) {
        $this->name = $name;
        $this->maxSpeed = $maxSpeed;
    }
}

$spaceships = [
    new Spaceship('Rebel Transport', 20),
    new Spaceship('Millenium Falcon', 80),
    new Spaceship('X-Wing Starfighter', 80),
    new Spaceship('TIE Bomber', 60),
    new Spaceship('TIE Fighter', 100),
    new Spaceship('Imperial Star Destroyer', 60),
];

// Sort the spaceships by name (in ascending order)
usort($spaceships, function ($ship1, $ship2) {
    return $ship1->name <=> $ship2->name;
});

echo $spaceships[0]->name; // "Imperial Star Destroyer"

// Sort the spaceships by speed (in descending order)
// Notice how we switch the position of $ship1 and $ship2
usort($spaceships, function ($ship1, $ship2) {
    return $ship2->maxSpeed <=> $ship1->maxSpeed;
});

echo $spaceships[0]->name; // "TIE Fighter"