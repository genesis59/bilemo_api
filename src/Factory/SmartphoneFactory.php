<?php

namespace App\Factory;

use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Smartphone>
 *
 * @method        Smartphone|Proxy create(array|callable $attributes = [])
 * @method static Smartphone|Proxy createOne(array $attributes = [])
 * @method static Smartphone|Proxy find(object|array|mixed $criteria)
 * @method static Smartphone|Proxy findOrCreate(array $attributes)
 * @method static Smartphone|Proxy first(string $sortedField = 'id')
 * @method static Smartphone|Proxy last(string $sortedField = 'id')
 * @method static Smartphone|Proxy random(array $attributes = [])
 * @method static Smartphone|Proxy randomOrCreate(array $attributes = [])
 * @method static SmartphoneRepository|RepositoryProxy repository()
 * @method static Smartphone[]|Proxy[] all()
 * @method static Smartphone[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Smartphone[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Smartphone[]|Proxy[] findBy(array $attributes)
 * @method static Smartphone[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Smartphone[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Smartphone> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Smartphone> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Smartphone> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Smartphone> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Smartphone> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Smartphone> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Smartphone> random(array $attributes = [])
// * @phpstan-method static Proxy<Smartphone> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Smartphone> repository()
// * @phpstan-method static list<Proxy<Smartphone>> all()
// * @phpstan-method static list<Proxy<Smartphone>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Smartphone>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Smartphone>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Smartphone>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Smartphone>> randomSet(int $number, array $attributes = [])
 */
final class SmartphoneFactory extends ModelFactory
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
            'batteryAutonomy' => self::faker()->randomNumber(2),
            'callAutonomy' => self::faker()->randomNumber(2),
            'depth' => self::faker()->randomNumber(1),
            'ecoRatingClimateRespect' => self::faker()->randomNumber(2),
            'ecoRatingDurability' => self::faker()->randomNumber(2),
            'ecoRatingRecyclability' => self::faker()->randomNumber(2),
            'ecoRatingRepairability' => self::faker()->randomNumber(2),
            'ecoRatingResourcesPreservation' => self::faker()->randomNumber(2),
            'endedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'height' => self::faker()->randomNumber(2),
            'indexRepairibility' => self::faker()->randomNumber(2),
            'microSdSlotMemory' => self::faker()->boolean(),
            'name' => self::faker()->name(),
            'operatingSystem' => self::faker()->userAgent(),
            'price' => self::faker()->randomFloat(7, 1000, 3000),
            'range' => RangeFactory::random(),
            'romMemory' => self::faker()->randomNumber(2),
            'screen' => ScreenFactory::random(),
            'sparePartsAvailibility' => self::faker()->randomNumber(2),
            'specificAbsorptionRateHead' => self::faker()->randomNumber(2),
            'specificAbsorptionRateMember' => self::faker()->randomNumber(2),
            'specificAbsorptionRateTrunk' => self::faker()->randomNumber(2),
            'startedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'technology' => self::faker()->lexify('5G'),
            'uuid' => Uuid::v4(),
            'weight' => self::faker()->randomNumber(2),
            'width' => self::faker()->randomNumber(2),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Smartphone $smartphone): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Smartphone::class;
    }
}
