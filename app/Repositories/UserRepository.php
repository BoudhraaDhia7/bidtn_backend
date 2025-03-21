<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\JetonPack;
use Illuminate\Support\Str;
use App\Models\JetonTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordResetToken;
use App\Exceptions\GlobalException;
use App\Helpers\MediaHelpers;
use App\Mail\CompletePaiment;
use App\Mail\UserResetPasswordMail;
use App\Models\Media;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return [
            'user' => $user,
        ];
    }

    /**
     * Update a user.
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUserDetail($validated, $user = null)
    {   
        DB::beginTransaction();
        if (empty($user)) {
            throw new GlobalException('No authenticated user found.');
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
            $imageToDeatch = $user->media->pluck('id')->toArray();
            if (count($imageToDeatch) > 0) {
                MediaRepository::detachMediaFromModel($user, $user->id, $imageToDeatch);
            }

            $mediaData = MediaHelpers::storeMedia($validated['profile_picture'], 'profile_pictures', $user);
            MediaRepository::attachMediaToModel($user, $mediaData);
        }

        DB::commit();
        return [
            'success' => true,
            'message' => 'User updated successfully.',
            'user' => $user,
        ];
    }

    /**
     * Update a user profile picture.
     *
     * @param User $user
     * @param UploadedFile $profilePicture
     * @return string
     */
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

        Mail::to($email)->send(new UserResetPasswordMail($token, $email));

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
        $token = PasswordResetToken::where('token', $resetPasswordToken)->first();
        
        $tokenExpirationTime = $token->created_at + 30 * 60;
        if (!$token || Carbon::now()->timestamp > $tokenExpirationTime) {
            throw new GlobalException('Invalid or expired token');
        }

        $user = User::where('email', $token->email)->first();

        if (!$user) {
            throw new GlobalException('No authenticated user found');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        $token->deleted_at = time();
        $token->save();

       return AuthRepository::authenticate([
            'email' => $user->email,
            'password' => $validated['password'],
        ]);
    }

    /*
     * Methode to buy jetons pack
     * @param array $validated
     * @return array
     */

    public static function buyJetonPack($packId, $user = null)
    {
        if (!$user) {
            throw new GlobalException('No authenticated user found');
        }
        $jetonPack = JetonPack::where('id', $packId)->first();

        if (!$jetonPack) {
            throw new GlobalException('Jeton pack not found');
        }

        $user->balance += $jetonPack->amount;
        $user->save();

        $transaction = new JetonTransaction();
        $transaction->user_id = $user->id;
        $transaction->jeton_pack_id = $jetonPack->id;
        $transaction->amount = $jetonPack->amount;
        $transaction->save();

        return [
            'user' => $user,
            'transactionId' => $transaction->id,
            'transaction' => $transaction,
            'jetonPack' => $jetonPack,
        ];
    }

    public static function ExchangeJeton($amount, $user)
    {
        DB::beginTransaction();
        $user->balance -= $amount;
        $user->save();

        $transaction = new JetonTransaction();
        $transaction->user_id = $user->id;
        $transaction->jeton_pack_id = null;
        $transaction->amount = $amount;
        $transaction->transaction_type = 'debit';
        $transaction->save();

        //remove 15% of the amount
        $amount = $amount - ($amount * 0.15);

        $pdf = PDF::loadView('pdf.receipt', ['user' => $user, 'pack' => null, 'transaction' => $transaction , 'amount' => $amount , 'transaction_type' => 'debit']);
        $pdfPath = 'receipts/' . uniqid() . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Media::create([
            'model_type' => JetonTransaction::class,
            'model_id' => $transaction->id,
            'file_name' => basename($pdfPath),
            'file_path' =>  config('constants.MEDIA_PATH') .'/storage/'. $pdfPath,
            'file_type' => 'application/pdf',
        ]);
        Mail::to($user->email)->send(new CompletePaiment($user));
        DB::commit();

        return [
            'user' => $user,
        ];
    }
}
