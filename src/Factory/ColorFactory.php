<?php

namespace App\Factory;

use App\Entity\Color;
use App\Repository\ColorRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Color>
 *
 * @method        Color|Proxy create(array|callable $attributes = [])
 * @method static Color|Proxy createOne(array $attributes = [])
 * @method static Color|Proxy find(object|array|mixed $criteria)
 * @method static Color|Proxy findOrCreate(array $attributes)
 * @method static Color|Proxy first(string $sortedField = 'id')
 * @method static Color|Proxy last(string $sortedField = 'id')
 * @method static Color|Proxy random(array $attributes = [])
 * @method static Color|Proxy randomOrCreate(array $attributes = [])
 * @method static ColorRepository|RepositoryProxy repository()
 * @method static Color[]|Proxy[] all()
 * @method static Color[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Color[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Color[]|Proxy[] findBy(array $attributes)
 * @method static Color[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Color[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Color> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Color> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Color> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Color> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Color> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Color> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Color> random(array $attributes = [])
// * @phpstan-method static Proxy<Color> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Color> repository()
// * @phpstan-method static list<Proxy<Color>> all()
// * @phpstan-method static list<Proxy<Color>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Color>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Color>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Color>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Color>> randomSet(int $number, array $attributes = [])
 */
final class ColorFactory extends ModelFactory
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
            'hex' => self::faker()->hexColor(),
            'name' => self::faker()->colorName(),
            'uuid' => Uuid::v4(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Color $color): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Color::class;
    }
}
