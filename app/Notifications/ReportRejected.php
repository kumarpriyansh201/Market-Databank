<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportRejected extends Notification
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Report Rejected',
            'message' => 'Your report "' . $this->report->title . '" has been rejected.',
            'reason' => $this->report->rejection_reason,
            'report_id' => $this->report->id,
            'type' => 'report_rejected',
        ];
    }
}
