<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return sprintf('(%s) %s', $user->getFirstName(), $user->getEmail());
                },
                'placeholder' => 'Choose an author',
                'choices' => $this->userRepository->findAllEmailAlphabetical(),
                'invalid_message' => 'Please select a user from the drop down.'
            ])
            ->add('image', ChoiceType::class, [
                'choices' => [
                    'Asteroid' => 'asterpod.jpeg',
                    'Lightspeed' => 'lightspeed.png',
                    'Mercury' => 'mercury.jpg',
                ],
                'placeholder' => 'Choose an image',
            ])
            ->add('publishedAt', null, [
                'widget' => 'single_text',
                'data' => new \DateTime(),

            ])
            ->add('published')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Article::class,
        ]);
    }
}