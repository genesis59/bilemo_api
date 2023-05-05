<?php

namespace App\Paginator;

use App\Entity\Smartphone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaginatorService
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @param class-string<object> $className
     * @param string $page
     * @param string $limit
     * @param string|null $search
     * @return array<Object>
     */
    public function paginate(string $className, string $page, string $limit, string $search = null): array
    {
        $page = intval($page);
        $limit = intval($limit);
        if ($page < 1) {
            throw new BadRequestHttpException(
                $this->translator->trans('app.exception.bad_request_http_exception_page')
            );
        }
        if ($limit < 1) {
            throw new BadRequestHttpException(
                $this->translator->trans('app.exception.bad_request_http_exception_limit')
            );
        }

        return $this->managerRegistry->getRepository($className)
            ->searchAndPaginate($limit, ($page - 1) * $limit, $search);
    }
}
