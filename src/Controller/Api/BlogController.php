<?php


namespace App\Controller\Api;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page":5}, requirements={"page"="\d+"})
     */
        public function list($page, Request $request)
        {
            $limit = $request->get('limit',10);
            $repository = $this->getDoctrine()->getRepository(BlogPost::class);
            $items = $repository->findAll();

            return $this->json(
                [
                    'page' => $page,
                    'limit' => $limit,
                    'data' => array_map(function (BlogPost $item) {
                        return $this->generateURL('blog_by_slug',['slug' =>$item ->getSlug()]);
                        }, $items)
                ]
            );
        }
    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return $this->json(
            self:POSTS
            $repository = $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug)
    {
        return $this->json(
            $repository = $this->getDoctrine()->getRepository(BlogPost::class)->findBy(['slug' => $slug])
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete()
    {

    }

}