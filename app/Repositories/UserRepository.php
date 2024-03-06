<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordResetToken;
use App\Exceptions\GlobalException;
use App\Mail\UserResetPasswordMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User|Exception
     */
    public static function getUser()
    {
        $user = auth()->user();
        if (!$user) {
            throw new Exception('user not found', 404);
        }
        return $user;
    }

    /**
     * Update a user.
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUserDetail($validated)
    {
        $user = auth()->user();
        if (!$user) {
            throw new GlobalException('No authenticated user found');
        }

        $attributesToUpdate = ['first_name', 'last_name'];
        foreach ($attributesToUpdate as $attribute) {
            if (isset($validated[$attribute])) {
                $user->{$attribute} = $validated[$attribute];
            }
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (!empty($validated['profile_picture'])) {
            $image_url = $this->updateUserProfilePicture($user, $validated['profile_picture']);
            $user['profile_picture'] = $image_url;
        }

        return [
            'success' => true,
            'message' => 'User updated successfully.',
            'user' => $user,
        ];
    }

    protected function updateUserProfilePicture($user, $profilePicture)
    {
        $storedPath = $profilePicture->store('profile_pictures', 'public');
        $fullUrl = Storage::url($storedPath);

        $mediaData = [
            'file_name' => $user->id . '_profile_picture',
            'file_path' => $fullUrl,
            'file_type' => $profilePicture->getClientMimeType(),
        ];

        $media = MediaRepository::attachMediaToModel($user, $mediaData);

        if (!$media) {
            throw new Exception('Error adding media to user');
        }

        return $fullUrl;
    }

    /**
     * Send a password reset link.
     *
     * @param array $validated
     * @return void
     */
    public static function forgotPassword($validated)
    {
        $email = $validated['email'];
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new GlobalException('No authenticated user found');
        }

        $token = hash('sha256', Str::random(60));

        $passwordResetToken = new PasswordResetToken();
        $passwordResetToken->email = $email;
        $passwordResetToken->token = $token;
        $passwordResetToken->save();

        //Mail::to($email)->send(new UserResetPasswordMail($token, $email));

        return [
            'success' => true,
            'message' => 'Password reset link sent successfully.',
        ];
    }

    /**
     * Reset a user password.
     *
     * @param int $id
     * @return User|Exception
     */
    public static function resetPassword($validated, $resetPasswordToken)
    {
        $token = PasswordResetToken::where('token', $resetPasswordToken)
            ->whereNull('deleted_at') 
            ->first();
        
        $tokenExpirationTime = $token->created_at + (30 * 60);
        if (!$token || Carbon::now()->timestamp > $tokenExpirationTime ) {
            throw new GlobalException('Invalid or expired token');
        }

        $user = User::where('email',$token->email)->first();

        if (!$user) {
            throw new GlobalException('No authenticated user found');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        $token->deleted_at = time();
        $token->save();

        return [
            'success' => true,
            'message' => 'Password reset successfully.',
        ];
    }

    //TODO: refactor this method to use the AdminRepository
    /**
     * Delete a user.
     *
     * @param int $id
     * @return void
     */
    /* public static function deleteUserById(int $id): void
    {
        try {
            if (!$id) {
                throw new NotFoundUserException();
            }
            $user = User::find($id);
            if (!$user) {
                throw new NotFoundUserException();
            }
            $user->deleted_at = time();
            $user->save();
            MediaRepository::detachMediaFromModel($user);
        } catch (NotFoundUserException) {
            Log::error('DeleteUser: User not found');
            throw new NotFoundUserException();
        } catch (DetachMediaException) {
            Log::error('DeleteUser: Error detaching media from user');
            throw new DetachMediaException();
        } catch (\Exception $e) {
            Log::error('DeleteUser: Error deleting user' . $e->getMessage());
            throw new GlobalException();
        }
    }*/
}
