<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'minMessage' => 'Le titre doit faire au-moins {{ limit }} caractères'])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Article',
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name');
                },
                'autocomplete' => true
            ])
            ->add('premium', CheckboxType::class, [
                'label' => 'Premium',
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('tags', EntityType::class, [
                'choice_label' => 'name',
                'class' => Tag::class,
                'label' => 'Tags',
                'multiple' => true,
                'by_reference' => false,
                'autocomplete' => true
            ])
            ->add('featured', CheckboxType::class, [
                'label' => 'Mis en avant',
                'required' => false
            ])
//            ->add('tags', TagAutocompleteField::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
                /** @var Post $post */
                $post = $event->getData();

                // Conversion du prix en euros
                $price = $post->getPrice() / 100;

                // Mise à jour de l'entité et des données
                $post->setPrice($price);
                $event->setData($post);
            })
            /* TODO
             *
             * Rajouter l'événement PRE_SUBMIT et modifier le prix qui a été envoyé (* 100)
             */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
