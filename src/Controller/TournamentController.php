<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class TournamentController extends AbstractController
{
    #[Route('/', name: 'app_tournament_index', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournamentRepository->findAll(),
        ]);
    }

    #[Route('/tournament/new', name: 'app_tournament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TournamentRepository $tournamentRepository, GameRepository $gameRepository): Response
    {
        $tournament = new Tournament();

        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournamentRepository->add($tournament);
            //Add 6 games to tournament
            for($i = 1; $i <= 6  ; $i++){
                $game = new Game();
                $game->setTournament($tournament);
                $game->setDescription($i);
                $gameRepository->add($game);
                $tournament->addGame($game);
            }
            return $this->redirectToRoute('app_tournament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournament/new.html.twig', [
            'tournament' => $tournament,
            'form' => $form,
        ]);
    }

    #[Route('/tournament/{id}', name: 'app_tournament_show', methods: ['GET'])]
    public function show(Tournament $tournament): Response
    {
        /*dd($tournament);*/
        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
        ]);
    }

    #[Route('/tournament/{id}/edit', name: 'app_tournament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournament $tournament, TournamentRepository $tournamentRepository): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournamentRepository->add($tournament);
            return $this->redirectToRoute('app_tournament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournament/edit.html.twig', [
            'tournament' => $tournament,
            'form' => $form,
        ]);
    }

    #[Route('/tournament/{id}', name: 'app_tournament_delete', methods: ['POST'])]
    public function delete(Request $request, Tournament $tournament, TournamentRepository $tournamentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->request->get('_token'))) {
            $tournamentRepository->remove($tournament);
        }

        return $this->redirectToRoute('app_tournament_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/tournament/addUser/{id}', name: 'app_tournament_add_user', requirements : ['id'=> '\d+'], methods: ['POST'])]
    public function addUser(EntityManagerInterface $em, GameRepository $gameRepository, TournamentRepository $tournamentRepository, $id, Security $security): Response
    {
        
        $tournament = $tournamentRepository->find($id);
        
        $availableGames = $tournament->getGames()->toArray();

        $count = 1;
            foreach($availableGames as $game) {
                $gameArray = $game->getPlayers()->toArray();
                
                if ($count < 4) {

                    if( in_array($security->getUser(),$gameArray) && count($gameArray) >=2 ) {
                        //addflash RRROOOUUUGGGE avec render
                         dump("joueur déjà inscrit ou nbre de joueurs maximum atteint");
                    } else {
                         $game->addPlayer($security->getUser());
                         $em->flush();
                         $count++;
                         var_dump($count);
                    } 

                } 
            }

    
        return $this->redirectToRoute('app_tournament_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }
}
