<?php

namespace App\Factory;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Picture>
 *
 * @method        Picture|Proxy create(array|callable $attributes = [])
 * @method static Picture|Proxy createOne(array $attributes = [])
 * @method static Picture|Proxy find(object|array|mixed $criteria)
 * @method static Picture|Proxy findOrCreate(array $attributes)
 * @method static Picture|Proxy first(string $sortedField = 'id')
 * @method static Picture|Proxy last(string $sortedField = 'id')
 * @method static Picture|Proxy random(array $attributes = [])
 * @method static Picture|Proxy randomOrCreate(array $attributes = [])
 * @method static PictureRepository|RepositoryProxy repository()
 * @method static Picture[]|Proxy[] all()
 * @method static Picture[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Picture[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Picture[]|Proxy[] findBy(array $attributes)
 * @method static Picture[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Picture[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Picture> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Picture> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Picture> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Picture> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Picture> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Picture> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Picture> random(array $attributes = [])
// * @phpstan-method static Proxy<Picture> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Picture> repository()
// * @phpstan-method static list<Proxy<Picture>> all()
// * @phpstan-method static list<Proxy<Picture>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Picture>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Picture>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Picture>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Picture>> randomSet(int $number, array $attributes = [])
 */
final class PictureFactory extends ModelFactory
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
            'fileName' => self::faker()->filePath(),
            'smartphone' => SmartphoneFactory::random(),
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Picture $picture): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Picture::class;
    }
}
