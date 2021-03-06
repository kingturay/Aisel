<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CategoryBundle\Manager;

use \LogicException;

/**
 * Abstract Manager Class
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class AbstractNodeManager
{

    protected $em;

    protected $repository = null;

    protected $nodeEntity = null;

    /**
     * {@inheritDoc}
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Load node tree
     */
    public function load()
    {
        $nodes = $this
            ->em
            ->getRepository($this->repository)
            ->findAll();

        return $nodes;
    }

    /**
     * Save name for single node
     *
     * @param  array $params
     * @return object
     *
     * @throws LogicException
     */
    public function save($params)
    {
        if ($categoryId = $params['id']) {
            $node = $this
                ->em
                ->getRepository($this->repository)
                ->find($categoryId);

            if (!($node)) {
                throw new LogicException('Nothing was found');
            }
        }

        if ($params['name']) $node->setTitle($params['name']);
        $this->em->persist($node);
        $this->em->flush();

        return $node;
    }

    /**
     * Remove single node
     *
     * @param  array $params
     * @return object
     *
     * @throws LogicException
     */
    public function remove($params)
    {
        if ($categoryId = $params['id']) {
            $node = $this->em->getRepository($this->repository)->find($categoryId);

            if (!($node)) {
                throw new LogicException('Nothing found');
            }
        }

        $this->em->remove($node);
        $this->em->flush();

        return $node;
    }

    /**
     * Creates child node
     *
     * @param  array $params
     * @return object
     *
     * @throws LogicException
     */
    public function addChild($params)
    {
        if ($categoryId = $params['parentId']) {
            $nodeParent = $this->em->getRepository($this->repository)->find($categoryId);

            if (!($nodeParent)) {
                throw new LogicException('Nothing found');
            }
        }

        $node = new $this->nodeEntity();
        $node->setTitle($params['name']);
        $node->setParent($nodeParent);
        $node->setStatus(false);
        $node->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $node->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        $this->em->persist($node);
        $this->em->flush();

        return $node;
    }

    /**
     * Creates Node
     *
     * @param  array $params
     * @return object
     *
     * @throws LogicException
     */
    public function addSibling($params)
    {
        $node = new $this->nodeEntity();
        $node->setTitle($params['name']);
        $node->setStatus(false);
        $node->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $node->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        $this->em->persist($node);
        $this->em->flush();

        return $node;
    }

    /**
     * Update parent for Node
     *
     * @param  array $params
     * @return object
     *
     * @throws LogicException
     */
    public function updateParent($params)
    {
        if ($categoryParentId = $params['parentId']) {
            $nodeParent = $this->em->getRepository($this->repository)->find($categoryParentId);
            if (!($nodeParent)) {
                throw new LogicException('Nothing found');
            }
        }

        if ($categoryId = $params['id']) {
            $node = $this->em->getRepository($this->repository)->find($categoryId);
            if (!($node)) {
                throw new LogicException('Nothing found');
            }
        }

        $node->setParent($nodeParent);
        $this->em->persist($node);
        $this->em->flush();

        return $node;
    }

    /**
     * Update parent for Node
     *
     * @param  int $id
     * @return object
     *
     * @throws LogicException
     */
    public function getNode($id)
    {
        $node = $this
            ->em
            ->getRepository($this->repository)
            ->find($id);

        return $node;
    }

}
