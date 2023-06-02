<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Entity\Smartphone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EntityRouteGenerator
{
    /**
     * @var array|string[]
     */
    private array $customerRoutes = [
        "self" => 'app_get_customer',
        "create" => 'app_create_customer',
        "update" => 'app_update_customer',
        "delete" => 'app_delete_customer'
    ];

    /**
     * @var array|string[]
     */
    private array $smartphoneRoutes = [
        "self" => "app_get_smartphone"
    ];

    /**
     * @var array|string[]
     */
    private array $resellerRoutes = [
        "create" => "app_create_reseller"
    ];

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @template T
     * @param T $entity
     * @return array<string,string>
     */
    public function getAllEntityRoutesList($entity): array
    {
        $routesList = [];
        $routes = $this->getRouteNames($entity);
        if (array_key_exists('self', $routes)) {
            $routesList['self'] = $this->getSelfEntityRoute($entity, $routes['self']);
        }
        if (array_key_exists('create', $routes)) {
            $routesList['create'] = $this->getCreateEntityRoute($entity, $routes['create']);
        }
        if (array_key_exists('update', $routes)) {
            $routesList['update'] = $this->getUpdateEntityRoute($entity, $routes['update']);
        }
        if (array_key_exists('delete', $routes)) {
            $routesList['delete'] = $this->getDeleteEntityRoute($entity, $routes['delete']);
        }
        return $routesList;
    }

    /**
     * @template T
     * @param T $entity
     * @return array|string[]
     */
    public function getRouteNames($entity): array
    {
        $routes = [];
        if ($entity instanceof Customer) {
            $routes = $this->customerRoutes;
        }
        if ($entity instanceof Smartphone) {
            $routes = $this->smartphoneRoutes;
        }
        if ($entity instanceof Reseller) {
            $routes = $this->resellerRoutes;
        }
        return $routes;
    }

    /**
     * @template T
     * @param T $entity
     * @param string $routeName
     * @return string
     */
    public function getSelfEntityRoute($entity, string $routeName): string
    {
        return $this->urlGenerator->generate(
            $routeName,
            ["uuid" => $entity->getUuid()]
        );
    }

    /**
     * @template T
     * @param T $entity
     * @param string $routeName
     * @return string
     */
    public function getUpdateEntityRoute($entity, string $routeName): string
    {
            return $this->urlGenerator->generate(
                $routeName,
                ["uuid" => $entity->getUuid()]
            );
    }

    /**
     * @template T
     * @param T $entity
     * @param string $routeName
     * @return string
     */
    public function getCreateEntityRoute($entity, string $routeName): string
    {
            return $this->urlGenerator->generate(
                $routeName
            );
    }

    /**
     * @template T
     * @param T $entity
     * @param string $routeName
     * @return string
     */
    public function getDeleteEntityRoute($entity, string $routeName): string
    {
            return $this->urlGenerator->generate(
                $routeName,
                ["uuid" => $entity->getUuid()]
            );
    }
}
