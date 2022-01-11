<?php

/**
 * 15 puzzle game.
 * @author Dmitry Cheva <dmitry.cheva@gmail.com>
 * @todo create clickAction $request and $response as objects,
 * add validators and documentation for them (see /other/.../base/* classes)
 */

namespace PuzzleBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use PuzzleBundle\Entity\Puzzle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Class DefaultController Singe controller for this bundle
 * @package PuzzleBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @var object|Puzzle
     */
    private $puzzle;

    /**
     * Root action. Opens main page.
     * @Route("/")
     */
    public function indexAction()
    {
        // set template vars
        // use last or create new
        $this->puzzle = $this->getLast() ?? $this->newPuzzle();
        $state = $this->puzzle->getState();
        $free = $this->getFree($state);

        return $this->render('PuzzleBundle:Default:index.html.twig',
            [
                'puzzle' => $this->puzzle,
                'state' => $state,
                'free' => $free,
            ]);
    }

    /**
     * API action.
     * Process move, check win condition, return current state as Json
     * @Route("/click")
     */
    public function clickAction(Request $request)
    {
        $vars = $request->request->all()['request'];

        // validate parameters
        $vars = $this->validateVars($vars);

        // process move
        $this->puzzle = $this->processMove($vars);

        // check win condition
        $this->checkWin();

        // response
        $state = $this->puzzle->getState();
        $free = $this->getFree($state);
        $response = [
            'status' => 'ok',
            'data' => [
                'id' => $this->puzzle->getId(),
                'status' => $this->puzzle->getStatus(),
                'state' => $state,
                'steps' => $this->puzzle->getSteps(),
                'free' => $free,
            ]];


        $response = new Response(json_encode($response));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Check the bones are rearranged into order
     * @return bool
     */
    private function checkWin()
    {
        $state = $this->puzzle->getState();
        $won = true;
        $prev = 0;
        // To solve the puzzle, the numbers must be rearranged into order
        foreach ($state as $row) {
            foreach ($row as $col) {
                // null fix
                if ($col == null) {
                    $col = 16;
                }
                if ($prev > $col) {
                    $won = false;
                }
                $prev = $col;
            }
        }
        if ($won) {
            $this->puzzle->setStatus(false);
            $this->save();
        }
        return true;
    }

    /**
     * Router for Generate new
     * @Route("/new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $this->newPuzzle();
        return $this->redirect('/');
    }

    /**
     * Generate new puzzle with random bones
     * @return Puzzle
     */
    private function newPuzzle()
    {
        $this->puzzle = new Puzzle();
        $numIsntFree = $nums = [[]];

        // generate random state
        // shuffle bones
        // maybe use generators here, but code wil be harder
        $bones = array_keys(array_fill(1, 15, null));
        shuffle($bones);

        // fill desk
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $nums[$i][$j] = array_pop($bones);
                $numIsntFree[$i][$j] = true;
            }
        }
        $this->puzzle->setState($nums);

        // save
        $this->save();

        return $this->puzzle;
    }

    /**
     * get last object
     * @return object|Puzzle
     */
    private function getLast()
    {
        return $this
            ->getDoctrine()
            ->getRepository(Puzzle::class)
            ->findOneBy(['status' => true], ['id' => 'desc']);
    }

    /**
     * Get one object by id
     * @return object|Puzzle
     */
    private function getOne($pid)
    {
        return $this
            ->getDoctrine()
            ->getRepository(Puzzle::class)
            ->find($pid);
    }

    /**
     * @todo validate input vars
     * @param $vars
     * @return mixed
     */
    private function validateVars($vars)
    {
        return $vars;
    }

    /**
     * Process move from x,y to free cell
     * @param $vars (pid, row, col)
     * @return object|Puzzle
     */
    public function processMove($vars)
    {
        $this->puzzle = $this->getOne($vars['pid']);
        // @todo add check if exists

        $this->puzzle->setSteps($this->puzzle->getSteps() + 1);

        // move
        $state = $this->puzzle->getState();
        foreach ($state as $i => $row) {
            foreach ($row as $j => $col) {
                if ($col == null) {
                    // check if move is possible
                    if ($this->isPossible($vars['row'], $vars['col'], $i, $j)) {
                        $state[$i][$j] = $state[$vars['row']][$vars['col']];
                        $state[$vars['row']][$vars['col']] = null;
                    }
                }
            }
        }
        $this->puzzle->setState($state);

        // save
        $this->save();

        return $this->puzzle;
    }

    /**
     * Check is it possible to move from x,y to x1,y1 cell
     * @param integer $toRow
     * @param integer $toCol
     * @param integer $fromRow
     * @param integer $fromCol
     * @return bool
     */
    private function isPossible($toRow, $toCol, $fromRow, $fromCol)
    {
        if (
            $toCol == $fromCol && (
                $toRow - 1 == $fromRow || $toRow + 1 == $fromRow
            ) || (
                $toRow == $fromRow && (
                    $toCol - 1 == $fromCol || $toCol + 1 == $fromCol
                )
            )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get x,y of free cell
     * @param array $state
     * @return array
     */
    private function getFree($state)
    {
        foreach ($state as $i => $row) {
            foreach ($row as $j => $col) {
                if ($col == null) {
                    $free = [$i, $j];
                }
            }
        }
        return $free;
    }

    /**
     * Save entity
     * @return bool
     */
    private function save()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->puzzle);
        $entityManager->flush();
        return true;
    }

}
