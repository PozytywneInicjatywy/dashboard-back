<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$kernel = new \PozytywneInicjatywy\Dashboard\Kernel('dev', true);
$kernel->boot();

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
