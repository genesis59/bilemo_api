<?php

namespace App\Factory;

use App\Entity\Screen;
use App\Repository\ScreenRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Screen>
 *
 * @method        Screen|Proxy create(array|callable $attributes = [])
 * @method static Screen|Proxy createOne(array $attributes = [])
 * @method static Screen|Proxy find(object|array|mixed $criteria)
 * @method static Screen|Proxy findOrCreate(array $attributes)
 * @method static Screen|Proxy first(string $sortedField = 'id')
 * @method static Screen|Proxy last(string $sortedField = 'id')
 * @method static Screen|Proxy random(array $attributes = [])
 * @method static Screen|Proxy randomOrCreate(array $attributes = [])
 * @method static ScreenRepository|RepositoryProxy repository()
 * @method static Screen[]|Proxy[] all()
 * @method static Screen[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Screen[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Screen[]|Proxy[] findBy(array $attributes)
 * @method static Screen[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Screen[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Screen> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Screen> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Screen> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Screen> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Screen> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Screen> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Screen> random(array $attributes = [])
// * @phpstan-method static Proxy<Screen> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Screen> repository()
// * @phpstan-method static list<Proxy<Screen>> all()
// * @phpstan-method static list<Proxy<Screen>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Screen>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Screen>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Screen>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Screen>> randomSet(int $number, array $attributes = [])
 */
final class ScreenFactory extends ModelFactory
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
            'diagonal' => self::faker()->randomNumber(2),
            'resolutionMainScreen' => self::faker()->lexify('1920x1080'),
            'touchScreen' => self::faker()->boolean(),
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Screen $screen): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Screen::class;
    }
}
