<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditPasswordEdit;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    
    #[Route('/user/{id}/edit', name: 'user.edit', methods:['GET','POST'])]
    public function edit(User $user,Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login.user');
        }
        if($this->getUser() !== $user){
            return $this->redirectToRoute('login.user');
        }

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $manager->persist($user);


            $manager->flush();

            $this->addFlash(
                'success',
                'Modification ok'
            );
            return $this->redirectToRoute('app_home');
        }
        else{

            $this->addFlash(
                'warning',
                'Echec de lla modification '
            );
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/user/{id}/password/edit', name: 'user.edit.password', methods:['GET','POST'])]
    public function editpassword(User $user,Request $request, EntityManagerInterface $manager,  UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserEditPasswordEdit::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            if($hasher->isPasswordValid($user, $form->getData()['plainPassword'])){

                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPlainPassword(
                    $form->getData()['newPassword']
                    
                );
                //Sa marche mais on utilise l'autre
                // $user->setPassword(
                //     $hasher->hashPassword(
                //         $user,
                //         $form->getData()['newPassword']
                //     )
                // );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Modification password ok'
                );
                return $this->redirectToRoute('app_home');

            }else{

            $this->addFlash(
                'warning',
                'Echec de lla modification '
            );
            }
        }

       
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
