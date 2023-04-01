<?php

namespace App\Factory;

use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reseller>
 *
 * @method        Reseller|Proxy create(array|callable $attributes = [])
 * @method static Reseller|Proxy createOne(array $attributes = [])
 * @method static Reseller|Proxy find(object|array|mixed $criteria)
 * @method static Reseller|Proxy findOrCreate(array $attributes)
 * @method static Reseller|Proxy first(string $sortedField = 'id')
 * @method static Reseller|Proxy last(string $sortedField = 'id')
 * @method static Reseller|Proxy random(array $attributes = [])
 * @method static Reseller|Proxy randomOrCreate(array $attributes = [])
 * @method static ResellerRepository|RepositoryProxy repository()
 * @method static Reseller[]|Proxy[] all()
 * @method static Reseller[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reseller[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Reseller[]|Proxy[] findBy(array $attributes)
 * @method static Reseller[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Reseller[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Reseller> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Reseller> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Reseller> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Reseller> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Reseller> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Reseller> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Reseller> random(array $attributes = [])
// * @phpstan-method static Proxy<Reseller> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Reseller> repository()
// * @phpstan-method static list<Proxy<Reseller>> all()
// * @phpstan-method static list<Proxy<Reseller>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Reseller>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Reseller>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Reseller>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Reseller>> randomSet(int $number, array $attributes = [])
 */
final class ResellerFactory extends ModelFactory
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
            'company' => self::faker()->company(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email' => self::faker()->companyEmail(),
            'password' => self::faker()->password(),
            'roles' => ['ROLE_USER'],
            'uuid' => Uuid::v4()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Reseller $reseller): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reseller::class;
    }
}
