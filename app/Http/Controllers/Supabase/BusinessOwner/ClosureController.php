<?php

namespace App\Http\Controllers\Supabase\BusinessOwner;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class ClosureController extends BaseSupabaseController
{
    public function index()
    {
        $ownerId = $this->getOwnerDetails()[0]['owner_id'] ?? null;
        $hotel_owner = $this->getTableData('Business', ['owner_id' => 'eq.' . $ownerId]);
        $hotelID = $hotel_owner[0]['hotel_id'] ?? null;

        $schedule = $this->getTableData(
            'Room_Schedule',
            ['Room.hotel_id' => 'eq.' . $hotelID],
            '*,Room(*)'
        );



        return view('business_owner.schedule', compact('schedule'));
    }

    public function getOwnerDetails()
    {
        $userId = session('user_id');
        $result = $this->getTableData('Bussiness_Owner', ['user_id' => 'eq.' . $userId]);
        return $result;
    }

    public function getRooms()
    {
        $ownerId = $this->getOwnerDetails()[0]['owner_id'] ?? null;
        $hotel_owner = $this->getTableData('Business', ['owner_id' => 'eq.' . $ownerId]);
        $hotelID = $hotel_owner[0]['hotel_id'] ?? null;

        $room = $this->getTableData('Room', ['hotel_id' => 'eq.' . $hotelID], 'RoomNo,RoomNumber');

        return response()->json($room);
    }

    public function addClosure(Request $request)
    {

        $request->validate([
            'room_no' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $schedule = $this->supabase->get('Room_Schedule', [], 'schedule_id');
        $latest = collect($schedule)->sortByDesc('schedule_id')->first();
        $newNum = $latest
            ? str_pad(intval(substr($latest['schedule_id'], 3)) + 1, 5, '0', STR_PAD_LEFT)
            : '10000';

        $newScheduleId = 'RMS' . $newNum;

        $scheduleData = [
            'schedule_id' => $newScheduleId,
            'RoomNo' => $request->room_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'description' => $request->description,
        ];

        $this->supabase->insert('Room_Schedule', $scheduleData);

        return redirect()->back()->with('success', 'Room closure added successfully!');
    }

    public function getClosures(Request $request)
    {
        $roomNo = $request->query('room');
        $data = $this->getTableData('Room_Schedule', ['RoomNo' => 'eq.' . $roomNo], "start_date, end_date,RoomNo");
        $closures = [];
        foreach ($data as $item) {
            if (!empty($item['start_date']) && !empty($item['end_date'])) {
                $start = new \DateTime($item['start_date']);
                $end = new \DateTime($item['end_date']);
                while ($start <= $end) {
                    $closures[] = $start->format('Y-m-d');
                    $start->modify('+1 day');
                }
            }
        }
        return response()->json($closures);
    }


    public function destroy($id)
    {
        $deleted =$this->deleteRecord('Room_Schedule', ['schedule_id' =>  $id]);
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Schedule deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'Failed to delete schedule'], 500);
    }
}
