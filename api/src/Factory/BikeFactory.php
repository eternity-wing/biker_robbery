<?php

namespace App\Factory;

use App\Entity\Bike;
use App\Entity\Police;
use App\Exception\InvalidObjectException;
use App\Exception\TransactionException;
use App\Services\Doctrine\Utils as DoctrineUtils;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BikeFactory.
 *
 * @author Wings <Eternity.mr8@gmail.com>
 */
class BikeFactory extends BaseFactory
{
    /**
     * @var DoctrineUtils
     */
    private $doctrineUtils;

    /**
     * PoliceFactory constructor.
     */
    public function __construct(EntityManagerInterface $manager, ContainerInterface $container, ValidatorInterface $validator, DoctrineUtils $doctrineUtils)
    {
        parent::__construct($manager, $container, $validator);
        $this->doctrineUtils = $doctrineUtils;
    }

    /**
     * @throws InvalidObjectException
     */
    public function create(array $data): Bike
    {
        $bike = new Bike();
        $bike->setOwnerFullName($data['ownerFullName']);
        $bike->setIsResolved($data['isResolved']);
        $bike->setColor($data['color']);
        $bike->setLicenseNumber($data['licenseNumber']);
        $bike->setStealingDescription($data['stealingDescription']);
        $bike->setStealingDate($data['stealingDate']);
        $bike->setType($data['type']);
        $bike->setResponsible($data['responsible']);
        $this->validate($bike);
        $this->store($bike);

        return $bike;
    }

    public function store(Bike $bike)
    {
        $this->entityManager->persist($bike);
        $this->entityManager->flush();
    }

    /**
     * @throws ConnectionException
     */
    public function assignResponsible(Bike $bike, ?callable $exceptionCallback): void
    {
        try {
            $doesNotNeedResponsible = $bike->getIsResolved() || $bike->getResponsible();
            if ($doesNotNeedResponsible) {
                return;
            }
            $this->doctrineUtils->executeCallableInTransaction(static function (EntityManagerInterface $entityManager) use ($bike) {
                $availableOfficer = $entityManager->getRepository(Police::class)->findOneBy(['isAvailable' => true]);
                if ($availableOfficer instanceof Police) {
                    $availableOfficer->setIsAvailable(false);
                    $bike->setResponsible($availableOfficer);
                }
            });
        } catch (TransactionException $e) {
            $exceptionCallback($e);
        }
        $this->entityManager->refresh($bike);
    }

    /**
     * @throws ConnectionException
     */
    public function resolve(Bike $bike, ?callable $exceptionCallback): void
    {
        try {
            $this->doctrineUtils->executeCallableInTransaction(static function (EntityManagerInterface $entityManager) use ($bike) {
                $responsibleOfficer = $bike->getResponsible();
                $isNotEngagedOfficer = $bike->getIsResolved() || null === $responsibleOfficer;
                if ($isNotEngagedOfficer) {
                    return;
                }
                $entityManager->persist($responsibleOfficer);
                $bike->setIsResolved(true);
                $responsibleOfficer->setIsAvailable(true);
            });
        } catch (TransactionException $e) {
            $exceptionCallback($e);
        }
        $this->entityManager->refresh($bike);
    }

    /**
     * @throws ConnectionException
     */
    public function delete(Bike $bike, ?callable $exceptionCallback): void
    {
        try {
            $this->doctrineUtils->executeCallableInTransaction(static function (EntityManagerInterface $entityManager) use ($bike) {
                $responsibleOfficer = $bike->getResponsible();
                $isNotEngagedOfficer = $bike->getIsResolved() || null === $responsibleOfficer;
                if ($isNotEngagedOfficer) {
                    return;
                }
                $entityManager->persist($responsibleOfficer);
                $responsibleOfficer->setIsAvailable(true);
            });
        } catch (TransactionException $e) {
            $exceptionCallback($e);
        }
        $this->entityManager->remove($bike);
        $this->entityManager->flush();
    }
}
