<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionResource;
use App\Http\Resources\Api\ViewBookingResource;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use View;

class BookingTransactionController extends Controller
{
    public function booking_details(Request $request)
    {
        $request->validate([
            'booking_trx_id' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $booking = BookingTransaction::where('phone_number', $request->phone_number)
            ->where('booking_trx_id', $request->booking_trx_id)
            ->with(['officespace', 'officeSpace.city'])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new ViewBookingResource($booking);
    }

    // pembuatan form requsest adalah best practce untuk validasi
    public function store(StoreBookingTransactionRequest $request)
    {
        $validatedData = $request->validated();

        $officeSpace = OfficeSpace::find($validatedData['office_space_id']);

        $validatedData['is_paid'] = false;
        $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
        $validatedData['duration'] = $officeSpace->duration;
        $validatedData['ended_at'] = (new \DateTime(($validatedData['started_at'])))->modify("+ {$officeSpace->duration} days")->format('Y-m-d');

        $bookingTransaction = BookingTransaction::create($validatedData);

        // mengirimka response ke client dalam WA menggunakan twlio
        // twilio send message configuration
        $sid = getenv('TWILIO_ACCOUNT_SID');
        $token = getenv('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = getenv('TWILIO_PHONE_NUMBER');
        $twilio = new Client($sid, $token);

        // mengirimkan pesan ke nomor yang diinputkan
        $messageBody = "Hi {$bookingTransaction->name}, silahkan lakukan pembayaran ke rekening BCA 1234567890 a/n PT. Rent Office\n\n";
        $messageBody .= "Pesanan kantor {$bookingTransaction->officeSpace->name} anda sedang diproses, silahkan tunggu konfirmasi dari kami. TRX_ID: {$bookingTransaction->booking_trx_id}\n\n";
        $messageBody .= "Jika ada pertanyaan silahkan hubungi kami di nomor 081234567890";

        // Format phone number to E.164 format for Twilio
        $phoneNumber = $bookingTransaction->phone_number;
        // Remove any plus sign if it exists
        $phoneNumber = ltrim($phoneNumber, '+');
        // Add plus sign back
        $phoneNumber = '+' . $phoneNumber;

        try {
            $twilio->messages->create(
                $phoneNumber,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => $messageBody,
                ]
            );
        } catch (\Twilio\Exceptions\RestException $e) {
            // Log the error but don't stop the booking process
            \Log::error('Twilio message failed: ' . $e->getMessage());
        }

        // return response transaction booking
        $bookingTransaction->load('officespace');
        return new BookingTransactionResource($bookingTransaction);
    }
}
