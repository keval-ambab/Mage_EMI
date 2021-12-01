<?php
// app/code/Ambab/RestApi/Api/ProductRepositoryInterface.php

namespace Ambab\RestApi\Api;

/**
 * Interface ProductRepositoryInterface
 *
 * @api
 */
interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Ambab\RestApi\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);

    /**
     * Return a list of the filtered products.
     *
     * @return \Ambab\RestApi\Api\ResponseItemInterface[]
     */
    public function getList();

    /**
     * Set descriptions for the products.
     *
     * @param \Ambab\RestApi\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
}