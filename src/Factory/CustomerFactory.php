<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Customer>
 *
 * @method        Customer|Proxy create(array|callable $attributes = [])
 * @method static Customer|Proxy createOne(array $attributes = [])
 * @method static Customer|Proxy find(object|array|mixed $criteria)
 * @method static Customer|Proxy findOrCreate(array $attributes)
 * @method static Customer|Proxy first(string $sortedField = 'id')
 * @method static Customer|Proxy last(string $sortedField = 'id')
 * @method static Customer|Proxy random(array $attributes = [])
 * @method static Customer|Proxy randomOrCreate(array $attributes = [])
 * @method static CustomerRepository|RepositoryProxy repository()
 * @method static Customer[]|Proxy[] all()
 * @method static Customer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Customer[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Customer[]|Proxy[] findBy(array $attributes)
 * @method static Customer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Customer[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
// * @phpstan-method        Proxy<Customer> create(array|callable $attributes = [])
// * @phpstan-method static Proxy<Customer> createOne(array $attributes = [])
// * @phpstan-method static Proxy<Customer> find(object|array|mixed $criteria)
// * @phpstan-method static Proxy<Customer> findOrCreate(array $attributes)
// * @phpstan-method static Proxy<Customer> first(string $sortedField = 'id')
// * @phpstan-method static Proxy<Customer> last(string $sortedField = 'id')
// * @phpstan-method static Proxy<Customer> random(array $attributes = [])
// * @phpstan-method static Proxy<Customer> randomOrCreate(array $attributes = [])
// * @phpstan-method static RepositoryProxy<Customer> repository()
// * @phpstan-method static list<Proxy<Customer>> all()
// * @phpstan-method static list<Proxy<Customer>> createMany(int $number, array|callable $attributes = [])
// * @phpstan-method static list<Proxy<Customer>> createSequence(iterable|callable $sequence)
// * @phpstan-method static list<Proxy<Customer>> findBy(array $attributes)
// * @phpstan-method static list<Proxy<Customer>> randomRange(int $min, int $max, array $attributes = [])
// * @phpstan-method static list<Proxy<Customer>> randomSet(int $number, array $attributes = [])
 */
final class CustomerFactory extends ModelFactory
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
            'city' => self::faker()->city(),
            'country' => self::faker()->country(),
            'email' => self::faker()->email(),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'phoneNumber' => self::faker()->phoneNumber(),
            'postCode' => self::faker()->postcode(),
            'street' => self::faker()->streetName(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Customer $customer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Customer::class;
    }
}
