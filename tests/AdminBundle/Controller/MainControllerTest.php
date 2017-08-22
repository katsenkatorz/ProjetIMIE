<?php

namespace Tests\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use AdminBundle\Entity\Parameters;
use UserBundle\Entity\User;

class MainControllerTest extends WebTestCase
{
    private $client;

    private $em;

    /**
     * Appeler avant chaque test
     */
    protected function setUp()
    {
        parent::setUp();
        $_SERVER["HTTP_USER_AGENT"] = "titi";

        // On crée un nouveau client
        $this->client = static::createClient();

        // On récupère l'entityManager
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();

        static $metadatas;

        // On récupère les métadatas si ils ne sont pas déjà présent
        if(!isset($metadatas))
            $metadatas = $this->em->getMetadataFactory()->getAllMetadata();

        // On récupère un utilitaire pour les schemas
        $schemaTool = new SchemaTool($this->em);

        // On vide la base de données
        $schemaTool->dropDatabase();

        // On recréer les tables vides si on a des métadatas
        if(!empty($metadatas))
            $schemaTool->createSchema($metadatas);
    }

    /** @test */
    public function mention_legale_action_with_good_parameter_table()
    {
        $parameter1 = new Parameters;
        $parameter1
            ->setLabel('Param1')
            ->setValue('toto');

        $parameter2 = new Parameters;
        $parameter2
            ->setLabel('Param2')
            ->setValue('toto');

        $parameter3 = new Parameters;
        $parameter3
            ->setLabel('Param3')
            ->setValue('toto');

        $parameter4 = new Parameters;
        $parameter4
            ->setLabel('Mentions Légales')
            ->setValue('toto');

        $this->em->persist($parameter1);
        $this->em->persist($parameter2);
        $this->em->persist($parameter3);
        $this->em->persist($parameter4);
        $this->em->flush();

        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/parameter/mentionLegale');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Mentions Légales', $crawler->filter("h2")->text());
    }

    /**
     * @test
     */
    public function mention_legale_action_with_wrong_parameter_table()
    {
        $parameter1 = new Parameters;
        $parameter1
            ->setLabel('Mentions Légales')
            ->setValue('toto');

        $parameter2 = new Parameters;
        $parameter2
            ->setLabel('Param2')
            ->setValue('toto');

        $parameter3 = new Parameters;
        $parameter3
            ->setLabel('Param3')
            ->setValue('toto');

        $parameter4 = new Parameters;
        $parameter4
            ->setLabel('')
            ->setValue('toto');

        $this->em->persist($parameter1);
        $this->em->persist($parameter2);
        $this->em->persist($parameter3);
        $this->em->persist($parameter4);
        $this->em->flush();

        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/parameter/mentionLegale');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotContains('Mentions Legales', $crawler->filter("h2")->text());
    }

    /**
     * @test
     */
    public function mention_legale_action_without_parameter_table()
    {
        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/parameter/mentionLegale');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Paramètre "Mentions légales" non présent dans la base de données', $crawler->filter("h2")->text());
    }

    /**
     * @test
     */
    public function user_action_when_downgrade_admin_with_another_admin()
    {
        $userTest1 = new User();
        $userTest1->setUsername("userAdmin")
            ->setUsernameCanonical("useradmin")
            ->setPlainPassword("azerty")
            ->setEmail("tito@gmail.com")
            ->setEmailCanonical("tito@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_ADMIN"]);

        $userTest2 = new User();
        $userTest2->setUsername("user")
            ->setUsernameCanonical("user")
            ->setPlainPassword("azerty")
            ->setEmail("tata@gmail.com")
            ->setEmailCanonical("tata@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_USER"]);

        $this->em->persist($userTest1);
        $this->em->persist($userTest2);
        $this->em->flush();

        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/user');

        $button = $crawler->selectButton("down")->eq(0);

        $form = $button->form([
            'userId'  => "1",
        ]);

        $this->client->submit($form);

        $roleDiv = $crawler->filter(".user")->eq(0)->text();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Utilisateur', $roleDiv);
    }

    /**
     * @test
     */
    public function user_action_when_downgrade_admin_without_another_admin()
    {
        $userTest1 = new User();
        $userTest1->setUsername("userAdmin")
            ->setUsernameCanonical("useradmin")
            ->setPlainPassword("azerty")
            ->setEmail("tito@gmail.com")
            ->setEmailCanonical("tito@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_USER"]);

        $userTest2 = new User();
        $userTest2->setUsername("user")
            ->setUsernameCanonical("user")
            ->setPlainPassword("azerty")
            ->setEmail("tata@gmail.com")
            ->setEmailCanonical("tata@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_USER"]);

        $this->em->persist($userTest1);
        $this->em->persist($userTest2);
        $this->em->flush();

        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/user');

        $button = $crawler->selectButton("down");

        $form = $button->form([
            'userId'  => "3",
        ]);

        $this->client->submit($form);

        $roleDiv = $crawler->filter(".admin")->text();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Administrateur', $roleDiv);
    }


    /**
     * @test
     */
    public function user_action_when_upgrade_user()
    {
        $userTest1 = new User();
        $userTest1->setUsername("userAdmin")
            ->setUsernameCanonical("useradmin")
            ->setPlainPassword("azerty")
            ->setEmail("tito@gmail.com")
            ->setEmailCanonical("tito@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_ADMIN"]);

        $userTest2 = new User();
        $userTest2->setUsername("user")
            ->setUsernameCanonical("user")
            ->setPlainPassword("azerty")
            ->setEmail("tata@gmail.com")
            ->setEmailCanonical("tata@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_USER"]);

        $this->em->persist($userTest1);
        $this->em->persist($userTest2);
        $this->em->flush();

        $this->doLogin("admin", "azerty");

        $crawler = $this->client->request('GET', '/admin/user');

        $button = $crawler->selectButton("up")->eq(0);

        $form = $button->form([
            'userId'  => "2",
        ]);

        $this->client->submit($form);

        $roleDiv = $crawler->filter(".admin")->eq(1)->text();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Administrateur', $roleDiv);
    }

    /*
     * Réalise le login
     */
    private function doLogin($username, $password)
    {
        $user = new User;
        $user->setUsername("admin")
            ->setUsernameCanonical("admin")
            ->setPlainPassword("azerty")
            ->setEmail("toto@gmail.com")
            ->setEmailCanonical("toto@gmail.com")
            ->setEnabled(true)
            ->setRoles(["ROLE_ADMIN"]);

        $this->em->persist($user);
        $this->em->flush();

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form([
            '_username'  => $username,
            '_password'  => $password,
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        return $crawler;
    }

    /**
     * Appeler après chaque test
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->client = null;
        $this->em->close();
        $this->em = null;
    }
}