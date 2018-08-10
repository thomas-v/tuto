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
use App\Form\ArticleType;


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
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager){
        if(!$article){
            $article = new Article();
        }
        
        
        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class)
                     ->add('content', TextareaType::class)
                     ->add('image', TextType::class)
                     ->getForm();
        //$form = $this->createForm(ArticleType::class, $article);
        //bind        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            
            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
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
