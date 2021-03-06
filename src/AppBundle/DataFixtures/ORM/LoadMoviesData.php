<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 28/11/16
 * Time: 16:02
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Movie;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMoviesData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var $container ContainerInterface
     */
    private $container;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $movies = $this->container->get('app_movie_generator')->getMovies('star');
        $results = $movies->Search;

        foreach($results as $key => $movie) {
            $newMovie = new Movie();
            $newMovie->setId($movie->imdbID);
            $newMovie->setTitle($movie->Title);
            $newMovie->setYear($movie->Year);
            $newMovie->setCover($movie->Poster);
            $manager->persist($newMovie);
            if ($key % 10 == 0){
                $manager->flush();
            }
        }
        $manager->flush();
        $manager->clear();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}