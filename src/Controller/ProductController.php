<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Form\ProductType;
use App\Form\SubCategoryType;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="show_product")
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        return $this->renderForm('product/show.html.twig', ['product'=>$product, 'form'=>$form]);
    }

    /**
     * @Route("/admin/product/new", name="new_product", priority="2")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('info', 'Le produit a bien été ajouté');
            return $this->redirectToRoute('app_home');
        }
        return $this->renderForm('product/new.html.twig', ['form'=>$form]);
    }


    /**
     * @Route("/admin/product/delete/{id}", name="delete_product")
     * @param EntityManagerInterface $manager
     * @param Product|null $product
     * @return RedirectResponse
     */
    public function delete(EntityManagerInterface $manager, Product $product = null): Response
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/product/edit/{id}", name="edit_product")
     * @param Product $product
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('show_product', ['id'=>$product->getId()]);
        }
        return $this->renderForm('product/new.html.twig', ['form'=>$form]);
    }

    /**
     * @Route("/admin/category/new", name="new_category")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function newCategory(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->renderForm('product/category.html.twig', ['form'=>$form]);
    }

    /**
     * @Route("/admin/subcategory/new", name="new_subcategory")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function newSubCategory(Request $request, EntityManagerInterface $manager)
    {
        $subcategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($subcategory);
            $manager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->renderForm('product/category.html.twig', ['form'=>$form]);
    }
}
