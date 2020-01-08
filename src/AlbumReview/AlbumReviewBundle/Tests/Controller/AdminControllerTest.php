<?php

namespace AlbumReview\AlbumReviewBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testViewusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/viewUsers');
    }

}
