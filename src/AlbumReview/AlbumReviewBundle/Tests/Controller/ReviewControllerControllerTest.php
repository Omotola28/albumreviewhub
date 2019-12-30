<?php

namespace AlbumReview\AlbumReviewBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerControllerTest extends WebTestCase
{
    public function testCreatereview()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createReview');
    }

    public function testEditreview()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/editReview');
    }

    public function testDeletereview()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteReview');
    }

}
