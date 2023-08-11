<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ResellerDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'app.constraint.reseller.email.not_blank')]
        #[Assert\Email(message: 'app.constraint.reseller.email.email')]
        #[Assert\Length(max:180, maxMessage: 'app.constraint.reseller.email.length_max_message')]
        private readonly ?string $email = null,
        #[Assert\NotBlank(message: 'app.constraint.reseller.password.not_blank')]
        #[Assert\NotCompromisedPassword(message: 'app.constraint.reseller.password.not_compromised_password')]
        #[Assert\Regex(
            pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-.,?;:§+!*$@%_])([-.,?;:§+!*$@%_\w]{8,255})$/',
            message: 'app.constraint.reseller.password.regex'
        )]
        private readonly ?string $password = null,
        #[Assert\NotBlank(message: 'app.constraint.reseller.company.not_blank')]
        private readonly ?string $company = null
    ) {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }
}
