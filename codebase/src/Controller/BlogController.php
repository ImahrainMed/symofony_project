<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;

use App\Form\CommentType;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BlogController extends AbstractController
{
    /** @var ArticleRepository  */
    private ArticleRepository $articleRepository;

    /*** @var CommentService */
    private CommentService $commentService;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;


    public function __construct( ArticleRepository $articleRepository , CommentService $commentService , CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->commentService = $commentService;
        $this->categoryRepository = $categoryRepository;

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
     * @Route("/search" ,name="app_blog_search" )
     * @param FormFactoryInterface $factory
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function Search(Request $request, UrlGeneratorInterface $urlGenerator):Response
    {
        /** Retrieve parameter from request */
        $param = $request->query->get("_search");
        $searchedArticle = $this->articleRepository->findByMultipleCriteria($param);

        /** @var Article[] $articles get related Articles */
        $catArticles = $this->categoryRepository->findBy(["name"=>$searchedArticle->getCategory()->getName()]);
        $comment = new Comment();
        $comment->setCreationDate(new \DateTime());
        $comment->setArticle($searchedArticle);
        $comment->setIsValid(false);
        $commentForm = $this->createForm(CommentType::class,$comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $this->commentService->saveComment($comment);
            $this->addFlash('success', 'your comment sent ');
            return new RedirectResponse($urlGenerator->generate('app_blog_search', ['_search' => $searchedArticle->getTitle()]));

        }


        return $this->render("blog/index.html.twig",[
            'article'=>$searchedArticle,
            'catArticles' => $catArticles,
            'commentForm'=> $commentForm->createView()
        ]);
    }



}
