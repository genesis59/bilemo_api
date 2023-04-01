<?php

namespace App\Factory;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Brand>
 *
 * @method        Brand|Proxy create(array|callable $attributes = [])
 * @method static Brand|Proxy createOne(array $attributes = [])
 * @method static Brand|Proxy find(object|array|mixed $criteria)
 * @method static Brand|Proxy findOrCreate(array $attributes)
 * @method static Brand|Proxy first(string $sortedField = 'id')
 * @method static Brand|Proxy last(string $sortedField = 'id')
 * @method static Brand|Proxy random(array $attributes = [])
 * @method static Brand|Proxy randomOrCreate(array $attributes = [])
 * @method static BrandRepository|RepositoryProxy repository()
 * @method static Brand[]|Proxy[] all()
 * @method static Brand[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Brand[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Brand[]|Proxy[] findBy(array $attributes)
 * @method static Brand[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Brand[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Brand> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Brand> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Brand> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Brand> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Brand> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Brand> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Brand> random(array $attributes = [])
// * @phpstan-method static Proxy<Brand> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Brand> repository()
// * @phpstan-method static list<Proxy<Brand>> all()
// * @phpstan-method static list<Proxy<Brand>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Brand>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Brand>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Brand>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Brand>> randomSet(int $number, array $attributes = [])
 */
final class BrandFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->company(),
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Brand $brand): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Brand::class;
    }
}
