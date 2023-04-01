<?php

namespace App\Factory;

use App\Entity\Range;
use App\Repository\RangeRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Range>
 *
 * @method        Range|Proxy create(array|callable $attributes = [])
 * @method static Range|Proxy createOne(array $attributes = [])
 * @method static Range|Proxy find(object|array|mixed $criteria)
 * @method static Range|Proxy findOrCreate(array $attributes)
 * @method static Range|Proxy first(string $sortedField = 'id')
 * @method static Range|Proxy last(string $sortedField = 'id')
 * @method static Range|Proxy random(array $attributes = [])
 * @method static Range|Proxy randomOrCreate(array $attributes = [])
 * @method static RangeRepository|RepositoryProxy repository()
 * @method static Range[]|Proxy[] all()
 * @method static Range[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Range[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Range[]|Proxy[] findBy(array $attributes)
 * @method static Range[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Range[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Range> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Range> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Range> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Range> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Range> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Range> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Range> random(array $attributes = [])
// * @phpstan-method static Proxy<Range> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Range> repository()
// * @phpstan-method static list<Proxy<Range>> all()
// * @phpstan-method static list<Proxy<Range>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Range>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Range>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Range>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Range>> randomSet(int $number, array $attributes = [])
 */
final class RangeFactory extends ModelFactory
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
            'brand' => BrandFactory::random(),
            'name' => self::faker()->words(2, true),
            'description' => self::faker()->text(1500),
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Range $range): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Range::class;
    }
}
