<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CartBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aisel\FixtureBundle\DataFixtures\XMLFixtureData;
use Aisel\CartBundle\Entity\Cart;

/**
 * Cart fixtures
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class LoadCartData extends XMLFixtureData implements OrderedFixtureInterface
{

    protected $fixturesName = 'aisel_cart.xml';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (file_exists($this->fixturesFile)) {
            $contents = file_get_contents($this->fixturesFile);
            $XML = simplexml_load_string($contents);

            foreach ($XML->database->table as $table) {
                $cart = new Cart();
                $cart->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                $cart->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
                $manager->persist($cart);
                $manager->flush();
            }
            $this->addReference('cart_' . $table->column[0], $cart);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 200;
    }
}