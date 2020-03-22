<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Police;
use App\Factory\PoliceFactory;

/**
 * Class PoliceDataPersister.
 */
class PoliceDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var DataPersisterInterface
     */
    private $decorated;

    /**
     * @var PoliceFactory
     */
    private $policeFactory;

    /**
     * PoliceDataPersister constructor.
     */
    public function __construct(DataPersisterInterface $decorated, PoliceFactory $policeFactory)
    {
        $this->decorated = $decorated;
        $this->policeFactory = $policeFactory;
    }

    /**
     * @param $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Police;
    }

    /**
     * @param $data
     *
     * @return object|void
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function persist($data, array $context = [])
    {
        $result = $this->decorated->persist($data, $context);
        $itemOperationName = $context['item_operation_name'] ?? null;
        $graphqlOperationName = $context['graphql_operation_name'] ?? null;

        $isModificationItemOperation = in_array($itemOperationName, ['post', 'put', 'patch']);
        $isModificationGraphQlOperation = in_array($graphqlOperationName, ['create', 'update']);

        $isItemOperationNeedsAssigningResponsibility = $isModificationItemOperation || $isModificationGraphQlOperation;
        if ($isItemOperationNeedsAssigningResponsibility) {
            $this->policeFactory->assignResponsibility($result, null);
        }

        return $result;
    }

    /**
     * @param $data
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function remove($data, array $context = [])
    {
        $this->policeFactory->delete($data, null);
    }
}
