<?php

namespace App\Controller\Reseller;

use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResellerCreateController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('/api/auth/signup', name: 'app_create_reseller', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ResellerRepository $resellerRepository,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        TagAwareCacheInterface $cache
    ): JsonResponse {

        if ($request->getContent() === "") {
            throw new BadRequestHttpException();
        }

        /** @var Reseller $reseller */
        $reseller = $serializer->deserialize($request->getContent(), Reseller::class, 'json', [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            "groups" => "post:reseller"
        ]);

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
        $cache->invalidateTags(['customersCache']);
        return $this->json($reseller, Response::HTTP_CREATED, [], ['groups' => 'read:reseller']);
    }
}
