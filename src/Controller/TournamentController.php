<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
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
            for ($i = 1; $i <= 6  ; $i++) {
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
    public function show(TournamentRepository $tournamentRepository, UserRepository $userRepository, $id): Response
    {
        $tournament = $tournamentRepository->find($id);
        $players = [];
        $pairs = $tournament->getPairs();
        foreach ($pairs as $index => $id) {
            array_push($players, $userRepository->find($id));
        }
        return $this->render('tournament/show.html.twig', compact('tournament', 'players'));
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
    public function addUser(EntityManagerInterface $em, GameRepository $gameRepository, TournamentRepository $tournamentRepository, UserRepository $userRepository, $id, Security $security): Response
    {
        //Check if user already exist in tournament (not more than 3 times)
        $tournament = $tournamentRepository->find($id);
    
        if(count($tournament->getPairs()) >= 4) {

            dd("Nbre de joueurs max atteint !");

        } elseif( in_array($security->getUser()->getId(), $tournament->getPairs())) {

            dd("joueur déjà inscrit");

        } else {

            $tournament->addPlayer($security->getUser()->getId());
            $tournament->setNbPlayers($tournament->getNbPlayers() +1);
            $em->flush();
            // dd($tournament->getPairs());
        }

        
        // Create pairs
        if(count($tournament->getPairs()) === 4) {

            $pairs = $tournament->getPairs();
            $arrayPairs = [];
            for ($i = 1; $i <= count($pairs); ++$i) {
                for ($j = $i+1; $j <= count($pairs); ++$j) {
                    $arrayPairs[] = [$i, $j];
                }
            }
            $availableGames = $tournament->getGames()->toArray();
            $n = 0;
            foreach ($availableGames as $game) {
                $player1Id = $arrayPairs[$n][0];
                $player2Id = $arrayPairs[$n][1];
    
                $player1 = $userRepository->find($player1Id);
                $player2 = $userRepository->find($player2Id);
                // array_push($game->getNbPlayers(), $player1, $player2);
                $game->addPlayer($player1);
                $game->addPlayer($player2);
                $em->flush();
                $n++;
            }
        } else {
            $players = [];
            $pairs = $tournament->getPairs();
            foreach ($pairs as $index => $id) {
                array_push($players, $userRepository->find($id));
            }
        return $this->render('tournament/show.html.twig', compact('tournament','id', 'players'));    
        }
        // dd($arrayPairs);

        // dd($availableGames);
        return $this->redirectToRoute('app_tournament_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }

    // public function makePairs(Tournament $tournament):array {

    //     $pairs = $tournament->getPairs();
    //     $arrayPairs = [];
    //     for ($i = 1; $i <= count($pairs); ++$i) {
    //         for ($j = $i+1; $j <= count($pairs); ++$j) {
    //             $arrayPairs[] = [$i, $j];
    //         }
    //     }

    //     return $arrayPairs;
    // }
}
