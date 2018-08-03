<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        
        $articles = $repo->findAll();
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }
    
    
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig', ['title' => 'Bienvenue dans ce blog !', 'age' => 31]);
    }
    
    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $requete, ObjectManager $manager){
        $article = new Article();
        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                         'attr' => [
                             'placeholder' => "Titre de l'article"
                         ]
                     ])
                     ->add('content', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article"
                         ]
                     ])
                     ->add('image', TextType::class,[
                         'attr' => [
                             'placeholder' => "Image de l'article"
                         ]
                     ])
                     ->add('save', SubmitType::class, [
                         'label' => 'Ajouter l\'article'
                     ])
                     ->getForm();
                     
                     
        
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article){
        
        //$article = $repo->find($id);
        return $this->render("blog/show.html.twig", [
            'article' => $article
        ]);
    }
    
}
