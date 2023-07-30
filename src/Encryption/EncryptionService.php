<?php

namespace App\Encryption;

use Exception;
use SodiumException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class EncryptionService
{
    public const ENCRYPT_METHOD_NAME = 'encrypt';
    public const DECRYPT_METHOD_NAME = 'decrypt';
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws SodiumException
     * @throws Exception
     */
    public function encrypt(string $data): string
    {
        $secretKey = $this->getKey();
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($data, $nonce, $secretKey);
        $result = sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
        $this->clear([$nonce,$secretKey,$ciphertext]);
        return $result;
    }

    /**
     * @throws SodiumException
     */
    public function decrypt(string $encrypted): string
    {
        $secretKey = $this->getKey();
        $ciphertext = sodium_base642bin($encrypted, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce = mb_substr($ciphertext, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($ciphertext, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $secretKey);
        if ($plaintext === false) {
            throw new BadRequestHttpException($this->translator->trans('app.exception.crypt_error'));
        }
        $this->clear([$nonce,$secretKey,$ciphertext]);
        return $plaintext;
    }

    /**
     * @param mixed[] $datas
     * @return void
     * @throws SodiumException
     */
    private function clear(array $datas): void
    {
        foreach ($datas as $data) {
            sodium_memzero($data);
        }
    }

    /**
     * @throws SodiumException
     */
    private function getKey(): string
    {
        /** @var string $secretKeyHex */
        $secretKeyHex = $this->parameterBag->get('sodium_key');
        $this->clear([$secretKeyHex]);
        return sodium_hex2bin($secretKeyHex);
    }
}
