<?php
namespace App\Serializer;

use App\Entity\Contract\TranslatableInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TranslatableNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($data, $format, $context);

        $newTranslations = [];
        foreach ($data['translations'] as $translation) {
            if (isset($translation['locale'])) {
                $newTranslations[$translation['locale']] = $translation;
                unset($newTranslations[$translation['locale']]['locale']);
            }
        }
        $data['translations'] = $newTranslations;

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof TranslatableInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            TranslatableInterface::class => true,
        ];
    }
}