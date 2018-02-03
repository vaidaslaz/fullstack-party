<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Controller;

use App\Repository\IssueRepository;
use Github\Api\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * App\Controller\IssueController
 */
class IssueController extends Controller
{
    /**
     * Index action
     *
     * @param IssueRepository $issueRepository
     * @param string          $state
     * @param int             $page
     *
     * @Route("/{state}/{page}",
     *     defaults={"state" = "open", "page" = 1},
     *     requirements={"state" = "open|closed", "page" = "\d+"}
     * )
     * @Template()
     *
     * @return array
     *
     * @throws \App\Exception\UserNotFoundException
     */
    public function listAction(IssueRepository $issueRepository, string $state, int $page)
    {
        return [
            'open_issues'   => $issueRepository->countOpenIssues(),
            'closed_issues' => $issueRepository->countClosedIssues(),
            'issues'        => $issueRepository->getList($state, $page),
            'state'         => $state,
            'page'          => $page,
        ];
    }

    /**
     * @param Issue  $issueApi
     * @param string $user
     * @param string $repo
     * @param int    $number
     *
     * @Route("/issue/{user}/{repo}/{number}",
     *     requirements={"user" = "[\w\-]+", "repo" = "[\w\-]+", "number" = "\d+"}
     * )
     * @Route("/issue/{path}",
     *     name="app_issue_issue_path",
     *     requirements={"path" = "([\w\-]+\/){2}\d+"},
     *     defaults={"user" = null, "repo" = null, "number" = null}
     * )
     *
     * @Template()
     *
     * @return array
     */
    public function issueAction(Issue $issueApi, ?string $user, ?string $repo, ?int $number)
    {
        return [
            'issue'    => $issueApi->show($user, $repo, $number),
            'comments' => $issueApi->comments()->all($user, $repo, $number),
        ];
    }
}
