<?php

namespace App\Paginator;

use App\Entity\Smartphone;
use App\Repository\CustomerRepository;
use App\Repository\SmartphoneRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Exception\BadMethodCallException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaginatorService
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly TranslatorInterface $translator,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @param class-string<object> $className
     * @param string $page
     * @param string $limit
     * @param string|null $search
     * @return array<string,mixed>
     */
    public function paginate(string $className, string $page, string $limit, ?string $search = null): array
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
        $repository = $this->managerRegistry->getRepository($className);

        if (
            (!$repository instanceof SmartphoneRepository && !$repository instanceof CustomerRepository) ||
            !method_exists($repository, "searchAndPaginate")
        ) {
            throw new BadMethodCallException(
                $this->translator->trans('app.exception.bad_method_call_exception_searchAndPaginate')
            );
        }

        return [
            "_pagination" => $this->getItemsPagination($repository, $page, $limit, $search),
            "items" => [
                ...$repository->searchAndPaginate($limit, ($page - 1) * $limit, $search)
            ]
        ];
    }

    /**
     * @param ServiceEntityRepositoryInterface $repository
     * @param int $currentPage
     * @param int $limit
     * @param string|null $search
     * @return array<string,mixed>
     */
    public function getItemsPagination(
        ServiceEntityRepositoryInterface $repository,
        int $currentPage,
        int $limit,
        ?string $search
    ): array {

        if (
            (!$repository instanceof SmartphoneRepository && !$repository instanceof CustomerRepository) ||
            !method_exists($repository, "searchAndPaginate")
        ) {
            throw new BadMethodCallException(
                $this->translator->trans('app.exception.bad_method_call_exception_searchAndPaginate')
            );
        }
        $items = $repository->searchAndPaginate(null, null, $search);
        $lastPage = ceil(count($items) / $limit);
        return [
            "current_page_number" => $currentPage,
            "number_items_per_page" => $limit,
            "total_items_count" => count($items),
            "first_page_link" => $this->urlGenerator->generate('app_get_customers', ['limit' => $limit]),
            "last_page_link" => $this->urlGenerator->generate(
                'app_get_customers',
                ['limit' => $limit, "page" => $lastPage]
            ),
            "previous_page_link" => $currentPage === 1 ? null : $this->urlGenerator->generate(
                'app_get_customers',
                ['limit' => $limit,"page" => $currentPage - 1]
            ),
            "next_page_link" => $currentPage < $lastPage ? $this->urlGenerator->generate(
                'app_get_customers',
                ['limit' => $limit,"page" => $currentPage + 1]
            ) : null
        ];
    }
}
