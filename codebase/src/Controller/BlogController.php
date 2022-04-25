<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class BlogController extends AbstractController
{
    /** @var ArticleRepository  */
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository )
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * return Blogs that are Trend
     * @return Response
     */
    public function trendingBlogs(): Response
    {
        $trendingArticles = $this->articleRepository->findBy(['trending' => true]);

        return $this->render('blog/trending.html.twig', [
            'trendingArticles' => $trendingArticles
        ]);
    }

    /**
     * return last six blogs
     * @return Response
     */
    public function lastSixBlogs(): Response
    {
        $lastSixArticles = $this->articleRepository->findBy([], ['creationDate' => 'ASC'], 6);

        return $this->render('blog/last6.html.twig', [
            'last6' => $lastSixArticles
        ]);
    }

    /**
     * return Blogs with popular flag is true
     * @return Response
     */
    public function popularBlogs(): Response
    {
        $mostPopularBlogs = $this->articleRepository->findBy(['popular' => true]);

        return $this->render('blog/popular.html.twig', [
            'popularBlogs' => $mostPopularBlogs
        ]);
    }

    /**
     * search by title / introduction / content
     * @Route("/search" ,name="app_blog_search" , methods={"GET"})
     * @param Article $article
     * @param FormFactoryInterface $factory
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function Search(FormFactoryInterface $factory ,Request $request):Response
    {
        /** Retrieve parameter from request */
        $param = $request->query->get("_search");
        $searchedArticle = $this->articleRepository->findByMultipleCriteria($param);
       /* $user = new User();
        $form = $factory->create(UserType::class,$user);
        $form->handleRequest($request);*/
       /* if($form->isSubmitted() && $form->isValid())
        {
            $searchedArticle->addComment($comment);
        }*/


        return $this->render("blog/index.html.twig",[
            'article'=>$searchedArticle,
            //'userForm'=> $form->createView()
        ]);
    }


}
