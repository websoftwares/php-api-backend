<?php
declare (strict_types = 1);
namespace ModusCreate\Model;

use ModusCreate\Repository\RepositoryInterface;

abstract class ModelAbstract implements ModelInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
