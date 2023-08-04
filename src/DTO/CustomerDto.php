<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'app.constraint.customer.first_name.not_blank')]
        #[Assert\Length(
            min:1,
            max:255,
            minMessage:'app.constraint.customer.first_name.length_min_message',
            maxMessage: 'app.constraint.customer.first_name.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-]+$/i',
            message: 'app.constraint.customer.first_name.regex'
        )]
        private readonly ?string $firstName,
        #[Assert\NotBlank(message: 'app.constraint.customer.last_name.not_blank')]
        #[Assert\Length(
            min: 1,
            max:255,
            minMessage: 'app.constraint.customer.last_name.length_min_message',
            maxMessage: 'app.constraint.customer.last_name.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-]+$/i',
            message: 'app.constraint.customer.last_name.regex'
        )]
        private readonly ?string $lastName,
        #[Assert\NotBlank(message: 'app.constraint.customer.email.not_blank')]
        #[Assert\Email(message: 'app.constraint.customer.email.email')]
        #[Assert\Length(max:255, maxMessage: 'app.constraint.customer.email.length_max_message')]
        private readonly ?string $email,
        #[Assert\NotBlank(message: 'app.constraint.customer.phone_number.not_blank')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'app.constraint.customer.phone_number.length_min_message',
            maxMessage: 'app.constraint.customer.phone_number.length_max_message'
        )]
        private readonly ?string $phoneNumber,
        #[Assert\NotBlank(message: 'app.constraint.customer.street.not_blank')]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'app.constraint.customer.street.length_min_message',
            maxMessage: 'app.constraint.customer.street.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
            message: 'app.constraint.customer.last_name.regex'
        )]
        private readonly ?string $street,
        #[Assert\NotBlank(message: 'app.constraint.customer.city.not_blank')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'app.constraint.customer.city.length_min_message',
            maxMessage: 'app.constraint.customer.city.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
            message: 'app.constraint.customer.last_name.regex'
        )]
        private readonly ?string $city,
        #[Assert\NotBlank(message: 'app.constraint.customer.country.not_blank')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'app.constraint.customer.country.length_min_message',
            maxMessage: 'app.constraint.customer.country.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
            message: 'app.constraint.customer.last_name.regex'
        )]
        private readonly ?string $country,
        #[Assert\NotBlank(message: 'app.constraint.customer.postcode.not_blank')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'app.constraint.customer.postcode.length_min_message',
            maxMessage: 'app.constraint.customer.postcode.length_max_message'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9-]+$/i',
            message: 'app.constraint.customer.last_name.regex'
        )]
        private readonly ?string $postCode
    ) {
    }
    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return string|null
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }
}
