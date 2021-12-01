<?php
// app/code/Ambab/RestApi/Model/Api/ProductRepository.php

namespace Ambab\RestApi\Model\Api;

use Ambab\RestApi\Api\ProductRepositoryInterface;
use Ambab\RestApi\Api\RequestItemInterfaceFactory;
use Ambab\RestApi\Api\ResponseItemInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProductRepository
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    private $productAction;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Ambab\RestApi\Api\RequestItemInterfaceFactory
     */
    private $requestItemFactory;

    /**
     * @var \Ambab\RestApi\Api\ResponseItemInterfaceFactory
     */
    private $responseItemFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Ambab\RestApi\Api\RequestItemInterfaceFactory $requestItemFactory
     * @param \Ambab\RestApi\Api\ResponseItemInterfaceFactory $responseItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Action $productAction,
        CollectionFactory $productCollectionFactory,
        RequestItemInterfaceFactory $requestItemFactory,
        ResponseItemInterfaceFactory $responseItemFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->requestItemFactory = $requestItemFactory;
        $this->responseItemFactory = $responseItemFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritDoc}
     *
     * @param int $id
     * @return \Ambab\RestApi\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id)
    {
        $collection = $this->getProductCollection()
            ->addAttributeToFilter('entity_id', ['eq' => $id]);

        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        $product = $collection->getFirstItem();
        if (!$product->getId()) {
            throw new NoSuchEntityException(__('Product not found'));
        }

        return $this->getResponseItemFromProduct($product);
    }

    /**
     * {@inheritDoc}
     *
     * @return \Ambab\RestApi\Api\ResponseItemInterface[]
     */
    public function getList()
    {
        $collection = $this->getProductCollection();

        $result = [];
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($collection->getItems() as $product) {
            $result[] = $this->getResponseItemFromProduct($product);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Ambab\RestApi\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products)
    {
        foreach ($products as $product) {
            $this->setDescriptionForProduct(
                $product->getId(),
                $product->getDescription()
            );
        }
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private function getProductCollection()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create();

        $collection
            ->addAttributeToSelect(
                [
                    'entity_id',
                    ProductInterface::SKU,
                    ProductInterface::NAME,
                    'description'
                ],
                'left'
            );

        return $collection;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Ambab\RestApi\Api\ResponseItemInterface
     */
    private function getResponseItemFromProduct(ProductInterface $product)
    {
        /** @var \Ambab\RestApi\Api\ResponseItemInterface $responseItem */
        $responseItem = $this->responseItemFactory->create();

        $responseItem->setId($product->getId())
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setDescription($product->getDescription());

        return $responseItem;
    }

    /**
     * Set the description for the product.
     *
     * @param int $id
     * @param string $description
     * @return void
     */
    private function setDescriptionForProduct(int $id, string $description)
    {
        $this->productAction->updateAttributes(
            [$id],
            ['description' => $description],
            $this->storeManager->getStore()->getId()
        );
    }
}