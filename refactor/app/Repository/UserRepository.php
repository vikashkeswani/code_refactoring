
<?php
namespace DTApi\Repository;

/**
 * Class BookingRepository
 * @package DTApi\Repository
 */
class UserRepository extends BaseRepository
{

    public function userLoginFailed()
    {
        $throttles = Throttles::where('ignore', 0)->with('user')->paginate(15);

        return ['throttles' => $throttles];
    }
    
}