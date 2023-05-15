<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\NotificationRepository;
use DTApi\Repository\JobRepository;

/**
 * Class NotificationController
 * @package DTApi\Http\Controllers
 */
class NotificationController extends Controller
{
    protected $notificationrepository ;
    protected $jobRepository ;

    /**
     * JobController constructor.
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository, JobRepository $jobRepository)
    {
        $this->notificationrepository = $notificationRepository;
        $this->jobRepository = $jobRepository ;
    }
    

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->jobRepository->find($data['jobid']);
        $job_data = $this->jobRepository->jobToData($job);
        $this->notificationrepository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->jobRepository->find($data['jobid']);
        $job_data = $this->jobRepository->jobToData($job);

        try {
            $this->notificationrepository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        $adminSenderEmail = config('app.adminemail');
        $data = $request->all();

        $response = $this->notificationrepository->storeJobEmail($data);

        return response($response);
    }

}
