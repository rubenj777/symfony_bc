<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Product;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/product/like/{id}", name="like_product")
     * @param EntityManagerInterface $manager
     * @param Product $product
     * @param LikeRepository $repo
     * @return Response
     */
    public function likeProduct(EntityManagerInterface $manager,Product $product,LikeRepository $repo): Response
    {
        $like = $repo->findOneBy([
            'user'=>$this->getUser(),
            'product'=>$product
        ]);

        if($like) {
            $manager->remove($like);
            $liked = false;
        } else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setProduct($product);
            $manager->persist($like);
            $liked = true;
        }
        $manager->flush();
        $count = $repo->count(['product'=>$product]);
        $res =[
            'count'=>$count,
            'liked'=>$liked
        ];

        return $this->json($res, 200);
    }

    /**
     * @Route("/comment/like/{id}", name="like_comment")
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     * @param LikeRepository $repo
     * @return Response
     */
    public function likeComment(EntityManagerInterface $manager, Comment $comment,LikeRepository $repo): Response
    {
        $like = $repo->findOneBy([
            'user'=>$this->getUser(),
            'comment'=>$comment
        ]);

        if($like) {
            $manager->remove($like);
            $liked = false;
        } else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setComment($comment);
            $manager->persist($like);
            $liked = true;
        }
        $manager->flush();
        $count = $repo->count(['comment'=>$comment]);
        $res =[
            'count'=>$count,
            'liked'=>$liked
        ];

        return $this->json($res, 200);
    }
}
