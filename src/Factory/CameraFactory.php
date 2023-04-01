<?php

namespace App\Factory;

use App\Entity\Camera;
use App\Repository\CameraRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Camera>
 *
 * @method        Camera|Proxy create(array|callable $attributes = [])
 * @method static Camera|Proxy createOne(array $attributes = [])
 * @method static Camera|Proxy find(object|array|mixed $criteria)
 * @method static Camera|Proxy findOrCreate(array $attributes)
 * @method static Camera|Proxy first(string $sortedField = 'id')
 * @method static Camera|Proxy last(string $sortedField = 'id')
 * @method static Camera|Proxy random(array $attributes = [])
 * @method static Camera|Proxy randomOrCreate(array $attributes = [])
 * @method static CameraRepository|RepositoryProxy repository()
 * @method static Camera[]|Proxy[] all()
 * @method static Camera[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Camera[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Camera[]|Proxy[] findBy(array $attributes)
 * @method static Camera[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Camera[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Camera> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Camera> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Camera> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Camera> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Camera> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Camera> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Camera> random(array $attributes = [])
// * @phpstan-method static Proxy<Camera> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Camera> repository()
// * @phpstan-method static list<Proxy<Camera>> all()
// * @phpstan-method static list<Proxy<Camera>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Camera>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Camera>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Camera>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Camera>> randomSet(int $number, array $attributes = [])
 */
final class CameraFactory extends ModelFactory
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
            'flash' => self::faker()->boolean(),
            'flashBack' => self::faker()->boolean(),
            'name' => self::faker()->firstNameFemale(),
            'numericZoom' => self::faker()->randomNumber(2),
            'numericZoomBack' => self::faker()->boolean(),
            'resolution' => self::faker()->randomNumber(2),
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Camera $camera): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Camera::class;
    }
}
