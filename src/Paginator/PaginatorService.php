<?php

namespace App\Paginator;

use Doctrine\Persistence\ManagerRegistry;

class PaginatorService
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * @param class-string<object> $className
     * @param int $page
     * @param int $limit
     * @return array<Object>
     */
    public function paginate(string $className, int $page, int $limit): array
    {
        return $this->managerRegistry->getRepository($className)
            ->findBy([], ['createdAt' => "DESC"], $limit, ($page - 1) * $limit);
    }
}
