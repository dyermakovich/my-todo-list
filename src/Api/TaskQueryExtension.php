<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use App\Entity\Task;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class TaskQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if(Task::class === $resourceClass){
            $user = $this->security->getUser();
            $alias = $queryBuilder->getRootAliases()[0];
            if(isset($user) && ($user instanceof User)) {
                $queryBuilder->andWhere(sprintf("%s.createdBy = %s", $alias, $user->getId()));
            } else {
                $queryBuilder->andWhere(sprintf("%s.id is null", $alias));
            }
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        // TODO: Implement applyToItem() method.
    }
}