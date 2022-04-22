<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private CategoryRepository $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/category/{id}", name="app_category")
     * @param Category $category
     * @return Response
     */
    public function index(Category $category): Response
    {
        return $this->render('category/index.html.twig', [

            'category' => $category
        ]);
    }

        /**
     * return categories with flag home true
     *
     * @return Response
     */
    public function allowedCategories():Response
    {
        $allowedCategories = $this->categoryRepository->findBy(['home'=>true]);

        return $this->render('category/allowedhome.html.twig' , [
            'allowedHome' => $allowedCategories
        ]);
    }
}
