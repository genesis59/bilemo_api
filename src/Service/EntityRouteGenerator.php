<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EntityRouteGenerator
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @template T
     * @param T $entity
     * @param array<string,string> $routes
     * @return array<string,string>
     */
    public function getAllEntityRoutesList($entity, array $routes): array
    {
        $routesList = [];
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
