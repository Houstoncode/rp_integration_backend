<?php

namespace App\Http\Controllers;

use App\Http\Resources\PastJobResource;
use App\Http\Resources\StaffResource;
use App\Models\PastJob;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return StaffResource::collection(Staff::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $pastJobs = $request->get('past_jobs');

        $staff = Staff::query()->create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'middle_name' => $request->get('middle_name'),
            'birthday' => $request->get('birthday'),
            'gender' => $request->get('gender'),
        ]);

        foreach ($pastJobs as $pastJob) {
            PastJob::query()->create([
                'staff_id' => $staff->id,
                'name' => $pastJob['name'],
                'start_date' => $pastJob['start_date'],
                'end_date' => $pastJob['end_date'],
            ]);
        }

        return response()->json(
            ['data' => [
                'staff' => StaffResource::collection([$staff])->first()
                ]
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Staff $staff
     * @return JsonResponse
     */
    public function update(Request $request, Staff $staff): JsonResponse
    {
        $pastJobs = $request->get('past_jobs');

        $staff->update([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'middle_name' => $request->get('middle_name'),
            'birthday' => $request->get('birthday'),
            'gender' => $request->get('gender'),
        ]);

        foreach ($pastJobs as $pastJob) {
            if(isset($pastJob['id'])) {
                $findPastJob = PastJob::query()->where('id', '=', $pastJob['id'])->first();

                if($findPastJob) {
                    $findPastJob->update([
                        'id' => $findPastJob->id,
                        'staff_id' => $staff->id,
                        'name' => $pastJob['name'],
                        'start_date' => $pastJob['start_date'],
                        'end_date' => $pastJob['end_date'],
                    ]);
                } else {
                    PastJob::query()->create([
                        'staff_id' => $staff->id,
                        'name' => $pastJob['name'],
                        'start_date' => $pastJob['start_date'],
                        'end_date' => $pastJob['end_date'],
                    ]);
                }
            } else {
                PastJob::query()->create([
                    'staff_id' => $staff->id,
                    'name' => $pastJob['name'],
                    'start_date' => $pastJob['start_date'],
                    'end_date' => $pastJob['end_date'],
                ]);
            }
        }

        return response()->json(
            ['data' => [
                'staff' => StaffResource::collection([$staff])->first()
            ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Staff $staff
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Staff $staff): JsonResponse
    {
        $deleted = $staff->delete();

        return response()->json(['data' => ['deleted' => $deleted]]);
    }

    public function destroyJobs(Request $request): JsonResponse {
        $removedJobs = $request->get('removed');

        foreach ($removedJobs as $removeJob) {
            $pastJob = PastJob::query()->where('id', '=', $removeJob)->first();
            $pastJob->delete();
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
