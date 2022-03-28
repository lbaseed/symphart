<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Article;
    use Doctrine\Persistence\ManagerRegistry;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    

    class ArticleController extends AbstractController {

        /**
         * @Route("/")
         * @Method({{ "GET" }})
         */        
        public function index(){
            // return new Response("Hello");

            $articles = ["Article One", "Article Two"];

            return $this->render("articles/index.html.twig", ["articles"=>$articles]);
        }

        /**
         * @Route("/article/store")
         */

        public function store(ManagerRegistry $doctrine){

            
            $entityManager = $doctrine->getManager();

            $article = new Article();
            $article->setTitle("Article One");
            $article->setBody("Article One Body text");
            // $article->setCreateAt(date("Y-m-d H:i:s"));

            $entityManager->persist($article);

            $entityManager->flush();

            return new Response("Article with ID: ".$article->getId()." Saved ");

        }


    }