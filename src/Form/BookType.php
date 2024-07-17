<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'titre',
            ])

            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
            ])

            ->add('cover', TextType::class, [
                'label' => 'Couverture'
            ])

            ->add('editedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de modification',
                'required' => false,
            ])

            ->add('plot', TextType::class, [
                'label' => 'Résumé',
            ])

            ->add('pageNumber', NumberType::class, [
                'label' => 'Nombre de pages',
            ])

            ->add('status', TextType::class, [
                'label' => 'Statut',
            ])

            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'label' => 'Éditeur',
                'choice_label' => 'name',
            ])

            ->add('authors', EntityType::class, [
                'class' => author::class,
                'label' => 'Auteur',
                'choice_label' => 'name',
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
