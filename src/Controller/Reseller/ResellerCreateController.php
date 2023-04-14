<?php

namespace App\Controller\Reseller;

use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResellerCreateController extends AbstractController
{
    #[Route('/api/auth/signup', name: 'app_create_reseller', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ResellerRepository $resellerRepository,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        TranslatorInterface $translator
    ): JsonResponse {

        if ($request->getContent() === "") {
            throw new NotEncodableValueException();
        }
        /** @var Reseller $reseller */
        $reseller = $serializer->deserialize($request->getContent(), Reseller::class, 'json');
        $reseller->setCreatedAt(new \DateTimeImmutable());
        $reseller->setRoles(['ROLE_USER']);
        $reseller->setPassword($passwordHasher->hashPassword($reseller, $reseller->getPassword()));
        $reseller->setUuid(Uuid::v4());
        $errors = $validator->validate($reseller);
        if ($errors->count() > 0) {
            $jsonErrors = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $jsonErrors[$error->getPropertyPath()] = $translator->trans($error->getMessageTemplate());
            }
            throw new UnprocessableEntityHttpException('My custom error message', null, 0, ['errors' => $jsonErrors]);
        }
        $resellerRepository->save($reseller, true);

        return $this->json($reseller, Response::HTTP_CREATED, [], ['groups' => 'read:reseller']);
    }
}
