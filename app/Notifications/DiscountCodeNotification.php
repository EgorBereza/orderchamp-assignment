<?php
 
namespace App\Notifications;

use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
 
class DiscountCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private User $user;
    private DiscountCode $discountCode;


    public function __construct(User $user,DiscountCode $discountCode)
    {
        $this->user=$user;
        $this->discountCode=$discountCode;
        //$this->middleware('auth');
    }

    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via()
    {
        return ['mail'];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Discount Code')
        ->greeting('Hello Dear '.$this->user->name)
        ->line('Please receive the discount code with value of 5 euro for your future purchases')
        ->line('Discount code:'.$this->discountCode->uniqueCode);
    }


}