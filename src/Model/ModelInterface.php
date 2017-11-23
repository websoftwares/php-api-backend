<?php
declare (strict_types = 1);
namespace ModusCreate\Model;

interface ModelInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function find(array $data) : array;
}
