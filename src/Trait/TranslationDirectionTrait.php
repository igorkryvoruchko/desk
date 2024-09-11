<?php

declare(strict_types=1);

namespace App\Trait;

use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

trait TranslationDirectionTrait
{
    use TranslationTrait;

    public static function getTranslatableEntityClass(): string
    {
        $explodedNamespace = explode('\\', __CLASS__);
        $entityClass = array_pop($explodedNamespace);
        // Remove Translation namespace
        array_pop($explodedNamespace);

        return '\\' . implode('\\', $explodedNamespace) . '\\' . substr($entityClass, 0, -11);
    }
}