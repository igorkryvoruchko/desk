<?php

declare(strict_types=1);

namespace App\Trait;

use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

trait TranslatableDirectionTrait
{
    use TranslatableTrait;

    public static function getTranslationEntityClass(): string
    {
        $explodedNamespace = explode('\\', __CLASS__);
        $entityClass = array_pop($explodedNamespace);

        return '\\' . implode('\\', $explodedNamespace) . '\\Translation\\' . $entityClass . 'Translation';
    }
}
