<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * Cette Url sera chargé de rédiriger les personnes selon leur role
     */
    #[Route('/', name: 'direction', methods:'GET')]
    public function index()
    {

        if($this->getUser()){
            $user = $this->getUser();
        
            // dd($user->getEmail());
            $roles = $user->getRoles();

            if (in_array('ROLE_ADMIN', $roles)) {
            
                // dd("admin");
                return $this->redirectToRoute("app_home");
                
            } else if(in_array('ROLE_USER', $roles)) {
                // dd("user ");
                return $this->redirectToRoute("app_home");
            }
            else if(in_array('ROLE_ENTREPRISE', $roles)) {
                // dd("entreprise");
                return $this->redirectToRoute("app_home");
            }
            else{
                // dd("autre");
                return $this->redirectToRoute("app_home");
            }
        }
        else{
            return $this->redirectToRoute("app_home");
        }
        
    }
    
    #[Route('/home', name: 'app_home', methods:'GET')]
    public function home(UserRepository $userRepository){
        
        // $users = $userRepository->findBy([],[], 5);
        $users = $userRepository->findAll();

        
    
        
        return $this->render('pages/home/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/show/{id}/portofolio', name: 'show.user.portofilio', methods:'GET')]
    public function portofoliouser(UserRepository $userRepository, User $user){
        
        $users = $userRepository->find($user);

        
    
        dd($users);
        // return $this->render('', [
        //     'users' => $users,
        // ]);
    }
}
