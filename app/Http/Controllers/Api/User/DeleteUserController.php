<?php
//TODO: Refactor this class to the admin repository
namespace App\Http\Controllers\Api\User;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use App\Http\Controllers\Controller;


class DeleteUserController extends Controller
{
    use GlobalResponse;

    /**
     * Delete a user.
     *
     * This endpoint is responsible for soft deleting a user based on the provided ID.
     * It performs the deletion via the UserRepository and handles exceptions accordingly.
     * This process can result in various responses depending on the outcome of the delete attempt.
     *
     * @param int $id User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
   // #[OA\Delete(path: '/api/user/{id}', tags: ['User'], description: 'Delete a user', responses: [new OA\Response(response: Response::HTTP_OK, description: 'User deleted successfully'), new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'User not found'), new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')])]
    public function __invoke(int $id) //: JsonResponse
    {
        //TODO : Refactor this method to use the admin repository
        /*try {
            UserRepository::deleteUserById($id);
            return $this->GlobalResponse('user_deleted', Response::HTTP_OK);
        } catch (NotFoundUserException $e) {
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->GlobalResponse('internal_server_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }*/
    }
}
