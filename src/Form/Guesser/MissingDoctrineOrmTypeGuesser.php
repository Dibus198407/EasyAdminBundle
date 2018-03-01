<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Form\Guesser;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;

class MissingDoctrineOrmTypeGuesser extends DoctrineOrmTypeGuesser
{
    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property)
    {
        if (null !== $metadataAndName = $this->getMetadata($class)) {
            /** @var ClassMetadataInfo $metadata */
            list($metadata) = $metadataAndName;

            switch ($metadata->getTypeOfField($property)) {
                case 'datetime_immutable': // available since Doctrine 2.6
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\DateTimeType', array(), Guess::HIGH_CONFIDENCE);
                case 'date_immutable': // available since Doctrine 2.6
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\DateType', array(), Guess::HIGH_CONFIDENCE);
                case 'time_immutable': // available since Doctrine 2.6
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\TimeType', array(), Guess::HIGH_CONFIDENCE);
                case Type::SIMPLE_ARRAY:
                case Type::JSON_ARRAY:
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\CollectionType', array(), Guess::MEDIUM_CONFIDENCE);
                case 'json': // available since Doctrine 2.6.2
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\TextareaType', array(), Guess::MEDIUM_CONFIDENCE);
                case Type::OBJECT:
                case Type::BLOB:
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\TextareaType', array(), Guess::MEDIUM_CONFIDENCE);
                case Type::GUID:
                    return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\TextType', array(), Guess::MEDIUM_CONFIDENCE);
            }
        }

        return parent::guessType($class, $property);
    }
}
