<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class GameController extends AbstractController
{
    #[Route('/game/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/game/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GameRepository $gameRepository): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->add($game);
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/game/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, GameRepository $gameRepository, $id): Response
    {
        $gameWithPlayer = $gameRepository->getGameAndPlayers($id);
        /*dd($gameWithPlayer);*/
        $form = $this->createForm(GameType::class, $gameWithPlayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->add($game);
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/edit.html.twig', [
            'games' => $gameWithPlayer,
            'form' => $form,
        ]);
    }

    #[Route('/game/delete/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game);
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/game/addUser/{id}', name: 'app_game_add_user', requirements : ['id'=> '\d+'], methods: ['POST'])]
    // public function addUser(EntityManagerInterface $em, GameRepository $gameRepository, TournamentRepository $tournamentRepository, $id, Security $security): Response
    // {
    //     $game = $gameRepository->find($id);

    //     if(!$game){
    //         throw $this->createNotFoundException();
    //     }

    //     $tournamentId = $game->getTournament()->getId();
        
    //     $tournament = $tournamentRepository->find($tournamentId);
        
    //     $availableGames = $tournament->getGames()->toArray();

    //     $players = $game->getPlayers()->toArray();

    //     $count = 0;
        
    //     // foreach($availableGames as $game ) {
    //     //    dump($game->getPlayers()->toArray());
           
    //     // } 
        

    //     // foreach( $players as $player){
    //     //     if($player === $security->getUser()){
    //     //         //addflash RRROOOUUUGGGE avec render
    //     //         dump('nope!');
    //     //         exit;
    //     //     }
    //     //  }

    //     // dump($availableGames);
    //     // exit();

    //     // // $game->addPlayer($security->getUser());
    //     // // $em->flush();

    //     return $this->redirectToRoute('app_tournament_show', ['id'=>$tournamentId], Response::HTTP_SEE_OTHER);
    // }


}
