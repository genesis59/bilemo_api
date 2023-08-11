<?php

namespace App\Paginator;

use App\Repository\CustomerRepository;
use App\Repository\SmartphoneRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Exception\BadMethodCallException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaginatorService
{
    private int $currentPage;
    private string $search;
    private int $limit;

    /**
     * @var array<object>
     */
    private array $data;
    private int $countItemsTotal;
    private int $lastPage;
    private string $currentRoute;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @param ServiceEntityRepositoryInterface $repository
     * @param Request $request
     * @param string $currentRoute
     * @return void
     */
    public function create(
        ServiceEntityRepositoryInterface $repository,
        Request $request,
        string $currentRoute
    ): void {
        $this->currentPage = intval($request->get('page', 1));
        $this->limit = intval($request->get('limit', $this->parameterBag->get('default_customer_per_page')));
        $this->search = $request->get('q', "");
        $this->currentRoute = $currentRoute;
        if ($this->currentPage < 1) {
            throw new BadRequestHttpException(
                $this->translator->trans('app.exception.bad_request_http_exception_page')
            );
        }
        if ($this->limit < 1) {
            throw new BadRequestHttpException(
                $this->translator->trans('app.exception.bad_request_http_exception_limit')
            );
        }
        if (
            (!$repository instanceof SmartphoneRepository && !$repository instanceof CustomerRepository) ||
            !method_exists($repository, "searchAndPaginate")
        ) {
            throw new BadMethodCallException(
                $this->translator->trans('app.exception.bad_method_call_exception_searchAndPaginate')
            );
        }
        $this->data = $repository->searchAndPaginate(
            $this->limit,
            ($this->currentPage - 1) * $this->limit,
            $this->search
        );
        if (count($this->data) === 0) {
            throw new NotFoundHttpException(
                $this->translator->trans('app.exception.not_found_http_exception_page'),
                null,
                0,
                ['page' => true]
            );
        }
        $this->countItemsTotal = count($repository->searchAndPaginate(null, null, $this->search));
        $this->lastPage = intval(ceil($this->countItemsTotal / $this->limit));
    }

    public function getUrlFirstPage(): string
    {
        return $this->urlGenerator->generate(
            $this->currentRoute,
            ['limit' => $this->limit]
        );
    }

    public function getUrlLastPage(): string
    {
        return $this->urlGenerator->generate(
            $this->currentRoute,
            ['limit' => $this->limit, "page" => $this->lastPage]
        );
    }

    public function getUrlNextPage(): ?string
    {
        return $this->currentPage < $this->lastPage ? $this->urlGenerator->generate(
            $this->currentRoute,
            ['limit' => $this->limit, "page" => $this->currentPage + 1]
        ) : null;
    }

    public function getUrlPreviousPage(): ?string
    {
        return $this->currentPage === 1 ? null : $this->urlGenerator->generate(
            $this->currentRoute,
            ['limit' => $this->limit, "page" => $this->currentPage - 1]
        );
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return array<object>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getCountItemsTotal(): int
    {
        return $this->countItemsTotal;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * @return string
     */
    public function getCurrentRoute(): string
    {
        return $this->currentRoute;
    }
}
