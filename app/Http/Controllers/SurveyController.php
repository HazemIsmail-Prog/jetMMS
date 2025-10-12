<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Department;
use App\Models\Status;
use App\Models\CancelSurvey;
use Illuminate\Http\Request;
use App\Enums\CancelReasonEnum;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\CancelSurveyResource;

class SurveyController extends Controller
{

    public function cancelSurveyIndex(Request $request)
    {
        // check if user has permission to view cancel surveys
        if (!auth()->user()->hasPermission('cancel_surveys_menu')) {
            return redirect()->route('dashboard')->with('error', __('messages.you_do_not_have_permission_to_view_cancel_surveys'));
        }

        if($request->wantsJson()) {
            $surveys = CancelSurvey::query()
                ->with('order.customer')
                ->with('order.phone')
                ->with('order.department')
                ->when($request->order_id, function ($query) use ($request) {
                    $query->where('order_id', $request->order_id);
                })
                ->when($request->reasons, function ($query) use ($request) {
                    $query->whereIn('cancel_reason', $request->reasons);
                })
                ->when($request->department_ids, function ($query) use ($request) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $query->whereIn('department_id', $request->department_ids);
                    });
                })
                ->when($request->start_created_at, function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_created_at);
                })
                ->when($request->end_created_at, function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_created_at);
                })
                ->latest()
                ->paginate(10);
            return CancelSurveyResource::collection($surveys);
        }

        // for Filter
        $reasons = collect(CancelReasonEnum::cases())->map(fn($status) => [
            'id' => $status->value,
            'name' => $status->title()
        ])->toArray();

        $departments = DepartmentResource::collection(Department::where('is_service', 1)->get());

        return view('pages.surveys.cancel-index', compact('reasons', 'departments'));
    }

    public function cancelSurveyPage($encryptedOrderId)
    {
        $order = Order::find(decrypt($encryptedOrderId));
        $reasons = collect(CancelReasonEnum::cases())->map(fn($status) => [
            'id' => $status->value,
            'name' => $status->title()
        ])->toArray();
        return view('pages.surveys.cancel-form', compact('order', 'reasons'));
    }

    public function storeCancelSurvey(Order $order, Request $request)
    {

        // check if order has cancel survey
        if ($order->cancelSurvey) {
            return response()->json(['error' => __('messages.order_already_has_cancel_survey')], 400);
        }

        $validatedData = $request->validate([
            'cancelReason' => 'required|string|max:255',
            'otherReason' => 'required_if:cancelReason,other|max:1000'
        ]);

        $order->cancelSurvey()->create([
            'cancel_reason' => $validatedData['cancelReason'],
            'other_reason' => $validatedData['otherReason']
        ]);

        return response()->json(['message' => __('messages.survey_cancelled_successfully')]);
    }


    public function sendMultipleSurveyMessage(Request $request)
    {

        // check if the user has the permission to send survey messages
        if (!auth()->user()->hasPermission('orders_send_survey')) {
            return response()->json(['error' => __('messages.you_do_not_have_permission_to_send_survey_messages')], 403);
        }

        $receivers = $this->getSurveyReceiversByStatus($request->surveys);

        $errors = [];

        if (!empty($receivers['cancelled'])) {
            $response = $this->sendSurveyMessages($receivers['cancelled'], config('services.wati.cancel_survey_template_name'));
            $jsonResponse = json_decode($response);
            if ($jsonResponse->error) {
                $errors[] = $jsonResponse->error;
            }
        }

        if (!empty($receivers['completed'])) {
            $response = $this->sendSurveyMessages($receivers['completed'], config('services.wati.complete_survey_template_name'));
            $jsonResponse = json_decode($response);
            if ($jsonResponse->error) {
                $errors[] = $jsonResponse->error;
            }
        }

        if (!empty($errors)) {
            return response()->json(['error' => implode(', ', $errors)], 500);
        }

        return response()->json(['message' => __('messages.survey_messages_sent_successfully')]);
    }

    private function getSurveyReceiversByStatus(array $surveys): array
    {
        $receivers = [
            'cancelled' => [],
            'completed' => []
        ];

        foreach ($surveys as $survey) {
            $receiver = $this->formatReceiver($survey);

            if ($survey['status_id'] == Status::CANCELLED) {
                $receivers['cancelled'][] = $receiver;
            } elseif ($survey['status_id'] == Status::COMPLETED) {
                $receivers['completed'][] = $receiver;
            }
        }

        return $receivers;
    }

    private function formatReceiver(array $survey): array
    {
        return [
            'whatsappNumber' => '965' . $survey['phone']['number'],
            'customParams' => [
                [
                    'name' => 'order_number',
                    'value' => $survey['id']
                ],
                [
                    'name' => 'link',
                    'value' => $this->getLinkForStatus($survey)
                ]
            ]
        ];
    }

    private function getLinkForStatus(array $survey): string
    {
        return $survey['status_id'] == Status::CANCELLED
            ? config('services.wati.cancel_survey_base_url') . encrypt($survey['id'])
            : config('services.wati.complete_survey_base_url') . encrypt($survey['id']);
    }

    private function sendSurveyMessages(array $receivers, string $templateName)
    {
        $WATI_BASE_URL = config('services.wati.base_url');
        $WATI_TOKEN = config('services.wati.bearer_token');
        $endpoint = '/api/v2/sendTemplateMessages';
        $fullUrl = $WATI_BASE_URL . $endpoint;
        $postFields = [
            'template_name' => $templateName,
            'broadcast_name' => $templateName,
            'receivers' => $receivers
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$WATI_TOKEN,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
