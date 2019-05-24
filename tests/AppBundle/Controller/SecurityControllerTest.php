<?php


namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class SecurityControllerTest extends WebTestCase
{

    /** @var Client */
    protected $client;

    /**
     * Retrieves the users list.
     */
    public function testlisteUsersAction()
    {

        $client = static::createClient();

        $client->request('GET', '/users/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


    }

    public function AjoutUserssAction()
    {

        $client = static::createClient();

        $client->request('POST', '/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


    }

}