<?php 
namespace App\Repositories;

use App\Events\CompletedPaymentEvent;
use App\Mail\CompletePaiment;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\JetonPack;
use App\Models\JetonTransaction;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentRepository
{
    public function __construct()
    {
        $secretKey = config('stripe.stripe.secret_key');

        if (!$secretKey) {
            throw new \Exception('Stripe secret key not set in configuration');
        }

        Stripe::setApiKey($secretKey);
    }

    public function createCheckoutSession($packId)
    {
      
        $jetonPack = JetonPack::find($packId);

        if (!$jetonPack) {
            throw new \Exception('Jeton pack not found');
        }

        $user = Auth::user();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $jetonPack->name,
                    ],
                    'unit_amount' => $jetonPack->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.front_url').'/dashboard',
            'cancel_url' => config('app.front_url').'/dashboard',
            'metadata' => [
                'user_id' => $user->id,
                'pack_id' => $packId,
            ],
        ]);
        
        return $session;
    }

    public function handleCheckoutSessionCompleted($sessionId)
    {
        $session = Session::retrieve($sessionId);
        $userId = $session->metadata->user_id;  
        $packId = $session->metadata->pack_id;

        $user = User::find($userId);

        $data = UserRepository::buyJetonPack($packId, $user);

        $pdf = PDF::loadView('pdf.receipt', ['user' => $user, 'pack' => $data['jetonPack'], 'transaction' => $data['transaction'] , 'transaction_type' => 'credit']);
        $pdfPath = 'receipts/' . uniqid() . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Media::create([
            'model_type' => JetonTransaction::class,
            'model_id' => $data['transactionId'],
            'file_name' => basename($pdfPath),
            'file_path' =>  config('constants.MEDIA_PATH') .'/storage/'. $pdfPath,
            'file_type' => 'application/pdf',
        ]);

        broadcast(new CompletedPaymentEvent($user));
        Mail::to($user->email)->send(new CompletePaiment($user));
        return $data['transaction'];
    }

    public function handlePaymentFailed($paymentIntentId)
    {

        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        $userId = $paymentIntent->metadata->user_id;
        
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        Log::error('Payment failed for user ID: ' . $userId);

        return $paymentIntent;
    }
}
