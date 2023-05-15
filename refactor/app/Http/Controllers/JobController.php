
<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class UserController
 * @package DTApi\Http\Controllers
 */
class JobController extends Controller
{

    protected $jobRepository ;

    /**
     * JobController constructor.
     * @param JobRepository $jobRepository
     */
    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }
    

/**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if($user_id = $request->get('user_id')) {

            $response = $this->jobRepository->getUsersJobs($user_id);

        }
        elseif($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID'))
        {
            $response = $this->jobRepository->getAllJobs($request);
        }

        return response($response);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        $response = $this->jobRepository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

        return response($response);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if($user_id = $request->get('user_id')) {

            $response = $this->jobRepository->getUsersJobsHistory($user_id, $request);
            return response($response);
        }

        return null;
    }


     /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->jobRepository->acceptJob($data, $user);

        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->jobRepository->acceptJobWithId($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->jobRepository->cancelJobAjax($data, $user);

        return response($response);
    }

      /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data = $request->all();

        $response = $this->jobRepository->endJob($data);

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->jobRepository->getPotentialJobs($user);

        return response($response);
    }

      /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->jobRepository->with('translatorJobRel.user')->find($id);

        return response($job);
    }

}
