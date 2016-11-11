<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/10/2016
 * Time: 10:52 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController
 * @package AppBundle\Controllerarticles
 */
class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="articles_list")
     */
    public function listAction(){

        $articles = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findAllByUser($this->getUser());

        return $this->render('article/list.html.twig', ['articles' => $articles]);
    }

    /**
     * @param Request $request
     * @Route("/articles/create", name="article_create")
     */
    public function createAction(Request $request){

        $form = $this->createForm(ArticleForm::class);
        $form->handleRequest($request);

        if($form->isValid()){
            $user = $this->getUser();

            /** @var Article $article */
            $article = $form->getData();
            $article->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', sprintf('Article created! You (%s) are amazing.', $this->getUser()->getEmail()));
            return $this->redirectToRoute("articles_list");
        }

        return $this->render('article/create.html.twig', ['articleForm' => $form->createView()]);
    }

    /**
     * @Route("/articles/edit/{id}", name="article_edit")
     */
    public function editAction(Article $article, Request $request){
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if($form->isValid()){
            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article edited!');
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/edit.html.twig', ['articleForm' => $form->createView()]);
    }


    /**
     * @Route(
     *     "/articles/{slug}.{_format}",
     *     defaults={"_format": "html"},
     *     requirements={"_format": "html|rss"},
     *     name="article_view"
     * )
     */
    public function viewAction($slug){

        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findOneBy(['slug' => $slug]);

        return $this->render('article/view.html.twig', [
            'article' => $article
        ]);
    }



    /**
     * @Route("/articles/confirm_delete/{id}", name="article_confirm_delete")
     */
    public function confirmDeleteAction($id){
        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->find($id);

        return $this->render('article/confirm_delete.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("articles/delete/{id}", name="article_delete")
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)
            ->find($id);
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Article deleted!');
        return $this->redirectToRoute('articles_list');

    }



}