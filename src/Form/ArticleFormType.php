<?php


namespace App\Form;


use App\Entity\Article;
#use App\Entity\User;
use App\Repository\UserRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
#use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
#use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ArticleFormType constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();
        $location = $article ? $article->getLocation() : null;

        //dd($options);
        //dd($article);
        //dd($isEdit);

        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy!',
            ])
            // @See https://symfony.com/doc/current/bundles/FOSCKEditorBundle/index.html
            ->add('content', CKEditorType::class, [
                'config' => [
                    'toolbar' => 'full'
                ]
            ])
            /*
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return sprintf('(%s) %s', $user->getFirstName(), $user->getEmail());
                },
                'placeholder' => 'Choose an author',
                'choices' => $this->userRepository->findAllEmailAlphabetical(),
                'invalid_message' => 'Please select a user from the drop down.'
            ])
            */
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit
            ])
            ->add('image', ChoiceType::class, [
                'choices' => [
                    'Asteroid' => 'asteroid.jpeg',
                    'Lightspeed' => 'lightspeed.png',
                    'Mercury' => 'mercury.jpg',
                ],
                'placeholder' => 'Choose an image',
            ])
            ->add('location', ChoiceType::class, [
                'placeholder' => 'Choose a location',
                'choices' => [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space',
                ],
                'required' => false,
            ])
            /*
            ->add('publishedAt', null, [
                'widget' => 'single_text',
                'data' => new \DateTime(),

            ])
            */

            ->add('published')
        ;

        if ($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                'widget' => 'single_text',
            ]);
        }

        if ($location)
        {
            $builder->add('specificLocationName', ChoiceType::class, [
                'placeholder' => 'Where exactly?',
                'choices' => $this->getLocationNameChoices($location),
                'required' => false,
            ]);
        }

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                //dd($event);
                $form = $event->getForm();
                $this->setupSpecificLocationNameField(
                  $form->getParent(),
                  $form->getData()
                );
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }

    /**
     * @param string $location
     * @return mixed
     */
    private function getLocationNameChoices(string $location)
    {
        $planets = [
          'Mercury',
          'Venus',
          'Earth',
          'Mars',
          'Jupiter',
          'Saturn',
          'Uranus',
          'Neptune',
          'Pluto',
        ];

        $stars = [
          'Polaris',
          'Sirius',
          'Alpha Centauari A',
          'Alpha Centauari B',
          'Betelgeuse',
          'Rigel',
          'Other',
        ];

        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationNameChoices[$location] ?? null;
    }

    /**
     * @param FormInterface $form
     * @param string|null $location
     */
    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {

        if (null === $location ?? null)
        {
            $form->remove('specificLocationName');

            return;
        }

        $choices = $this->getLocationNameChoices($location);

        if (null === $choices ?? null)
        {
            $form->remove('specificLocationName');

            return;
        }

        $form->add('specificLocationName', ChoiceType::class, [
            'placeholder' => 'Where exactly?',
            'choices' => $choices,
            'required' => false,
        ]);
    }
}