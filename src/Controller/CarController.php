<?php

namespace App\Controller;

use App\Form\CarType;
use App\Entity\Voiture;
use Doctrine\ORM\EntityManager;
use App\Repository\CarRepository;

use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class CarController extends AbstractController
{
    /**
     * @Route("/", name="car")
     */

    public function index(): Response {
        return $this->render("home.html.twig");
    }

    /**
     * @Route("/allCar", name="allCar")
     */

    public function allCar(VoitureRepository $rep): Response {
        $cars = $rep->findAll();

        return $this->render(
            "allCar.html.twig", 
            ["cars"=>$cars]
        );

    }

    /**
    * @Route("/detail/{id}", name="details")
    */

    public function oneCar (VoitureRepository $rep, $id) : Response {

        $oneCar = $rep->find($id);

        return $this->render("detail.html.twig", ["onecar"=>$oneCar]);
    }

    /**
     * @Route("/add", name="addCar")
     */

    public function addCar(Request $req , PersistenceManagerRegistry $doctrine){
 
         $car = new Voiture();
         $form = $this->createForm(CarType::class, $car);
         $form->handleRequest($req);
 
         if ($form->isSubmitted() && $form->isValid()) {
             
            $car = $form->getData();
             $entityManger = $doctrine->getManager();
             $entityManger->persist($car);
             $entityManger->flush();

            $this->addFlash('notice','Une nouvelle voiture a été ajoutée avec succès ...');

            return $this->redirectToRoute('allCar');
 
         }
 
         return $this->render('addCar.html.twig', [
             'form' => $form->createView()
         ]);
 
    }

    /**
     * @Route("/update/{id}", name="update")
     */

    public function update(Request $req , $id, EntityManagerInterface $em, PersistenceManagerRegistry $doctrine){
 
        $rep = $em->getRepository("App\Entity\Voiture");
        $car = $rep->find($id);
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($car);
            $em->flush();

            $this->addFlash('notice', 'Une voiture a été modifiée avec succès ...');

            return $this->redirectToRoute('allCar');
        }
        
        return $this->render('updateCar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function delete($id, EntityManagerInterface $em, PersistenceManagerRegistry $doctrine){
 
        $rep = $em->getRepository("App\Entity\Voiture");
        $data = $rep->find($id);
        

            $em = $doctrine->getManager();
            $em->remove($data);
            $em->flush();

            $this->addFlash('notice','Une voiture a été supprimée avec succès ...');

        
            return $this->redirectToRoute('allCar');
    }


}
