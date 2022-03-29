<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Article;
    use Doctrine\Persistence\ManagerRegistry;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class ArticleController extends AbstractController {

        /**
         * @Route("/", name="article_list")
         * @Method({{ "GET" }})
         */        
        public function index(ManagerRegistry $doctrine){
            // return new Response("Hello");

            $articles = $doctrine->getRepository(Article::class)->findAll();

            return $this->render("articles/index.html.twig", ["articles"=>$articles]);
        }


        /**
         * @Route("/article/new", name="new_article")
         * Method({ "GET", "POST" })
         */
        public function new(Request $request, ManagerRegistry $doctrine) {

            $article = new Article();

            // create form
            $form = $this->createFormBuilder($article)
            ->add("title", TextType::class, ["attr" => ["class" => "form-control"]])
            ->add("body", TextareaType::class, [
                "attr" => ["class" => "form-control"],
                "required" => false
                ])
            ->add("save", SubmitType::class, [
                "attr" => ["class" => "btn btn-primary mt-3"],
                "label" => "create"
            ])->getForm();

            // handling form request
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid() ) {
                $article = $form->getData();

                $entityManager = $doctrine->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute("article_list");
            }


            // render form
            return $this->render("articles/new.html.twig", [
                "form" => $form->createView()
            ]);
        }
        
        /**
         * @Route("/article/{id}", name="article_show")
         */
        public function show(ManagerRegistry $doctrine, $id) {

            $article = $doctrine->getRepository(Article::class)->find($id);

            return $this->render("articles/show.html.twig", ["article"=>$article]);

        }

        /**
         * @Route("/article/{id}/delete", name="delete_article")
         */
        public function destroy(ManagerRegistry $doctrine, $id) {

            // search article
            $article = $doctrine->getRepository(Article::class)->find($id);

            // now delete the article
            $entityManager = $doctrine->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            // send response to client

            $response = new Response();
            $response->send();


        }
        
        /**
         * @Route("/article/store", name="article_store")
         */

        public function store(ManagerRegistry $doctrine){

            
            $entityManager = $doctrine->getManager();

            $article = new Article();
            $article->setTitle("Article Three");
            $article->setBody("Article Three Body text");
            // $article->setCreateAt(date("Y-m-d H:i:s"));

            $entityManager->persist($article);

            $entityManager->flush();

            return new Response("Article with ID: ".$article->getId()." Saved ");

        }


    }