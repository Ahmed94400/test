<?php
// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function AjoutUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        //print_r($request);exit;

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            $user->getId();

            return new JsonResponse(['status'=>"success",'message' => 'Utilisateur ajouté',"user"=>$this->container->get('serializer')->serialize($user, 'json')], Response::HTTP_OK);
        } else {
            return new JsonResponse(['status'=>"error",'message' => 'Aucune données envoyé!'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}")
     */
    public function SupprimerUserAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if ($user) {
            $em->remove($user);
            $em->flush();
            return new JsonResponse(['status'=>"success",'message' => 'Utilisateur supprimé avec succès.'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['status'=>"error",'message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function modifierUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if (empty($user)) {
            return new JsonResponse(['status'=>"error",'message' => 'Utilisateur introuvable',"user"=>$this->container->get('serializer')->serialize($user, 'json')], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->merge($user);
            $em->flush();

            return new JsonResponse(['status' => "success", 'message' => 'Utilisateur modifié avec succès'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['status'=>"error",'message' => 'Utilisateur non modifié.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/")
     */
    public function listeUsersAction(Request $request)
    {
        $users=$this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();




        if (empty($users)) {
            return new JsonResponse(['status'=>"error",'message' => 'Aucun Utilisateur trouvé!'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status'=>"success",'message' => "Users List","users"=>$this->container->get('serializer')->serialize($users, 'json')], Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function listeunUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));


        if (empty($user)) {
            return new JsonResponse(['status'=>"error",'message' => 'Utilisateur non trouvé!'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status'=>"success",'message' => "User Infos","User"=>$this->container->get('serializer')->serialize($user, 'json')], Response::HTTP_OK);
    }
}
