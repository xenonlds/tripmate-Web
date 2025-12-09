<?php

namespace App\Http\Controllers\Supabase\BusinessOwner;


use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class RoomController extends BaseSupabaseController
{
    protected $table = 'Room';

    public function getOwnerDetails()
    {
        $userId = session('user_id');
        $result = $this->getTableData('Bussiness_Owner', ['user_id' => 'eq.' . $userId]);
        return $result;
    }

    public function index(Request $request)
    {

        $ownerId = $this->getOwnerDetails()[0]['owner_id'] ?? null;
        $hotel_owner = $this->getTableData('Business', ['owner_id' => 'eq.' . $ownerId]);
        $hotelID = $hotel_owner[0]['hotel_id'] ?? null;

        $page = $request->query('page', 1);
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $rooms = $this->getTableData(
            $this->table,
            ['hotel_id' => 'eq.' . $hotelID],
            'roomType_id,RoomNo,hotel_id,status,created_at,amenities,floor,size,capacity,RoomNumber, RoomType(*)',
            $limit,
            $offset,
            'RoomNumber',
            true
        );


        $allRooms = $this->getTableData($this->table, ['hotel_id' => 'eq.' . $hotelID]);
        $total = count($allRooms);
        $totalPages = ceil($total / $limit);

        return view('business_owner.room', compact('rooms', 'ownerId', 'page', 'totalPages', 'total'));
    }

    public function show($roomId)
    {
        $room = $this->getTableData($this->table, ['RoomNo' => 'eq.' . $roomId], '*, RoomType(*)');
        if (empty($room)) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        return response()->json($room[0]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'room-number' => 'required|string|max:50',
            'room-type' => 'required|string|max:100',
            'floor' => 'required|integer|min:0',
            'capacity' => 'required|integer|min:1',
            'size' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'bed-type' => 'required|string|max:100',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        try {

            $ownerId = $this->getOwnerDetails()[0]['owner_id'] ?? null;
            $hotel_owner = $this->getTableData('Business', ['owner_id' => 'eq.' . $ownerId]);
            $hotelID = $hotel_owner[0]['hotel_id'] ?? null;

            if (!$hotelID) {
                throw new \Exception('Hotel not found for this owner.');
            }

            $room = $this->supabase->get('RoomType', [], 'roomType_id');
            $latestType = collect($room)->sortByDesc('roomType_id')->first();
            $newNum = $latestType ? str_pad(intval(substr($latestType['roomType_id'], 3)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newRoomTypeId = 'RMT' . $newNum;

            $roomTypeData = [
                'roomType_id' => $newRoomTypeId,
                'RoomPrice'   => $validated['price'],
                'type_name'   => $validated['room-type'],
                'bedType'     => $validated['bed-type'],
                'RoomDesc'    => $validated['description'] ?? '',
            ];
            $this->supabase->insert('RoomType', $roomTypeData);


            $roomID = $this->supabase->get('Room', [], 'RoomNo');
            $latestRoom = collect($roomID)->sortByDesc('RoomNo')->first();
            $newNums = $latestRoom ? str_pad(intval(substr($latestRoom['RoomNo'], 3)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newRoomId = 'K' . $newNums;

            $roomData = [
                'RoomNo'       => $newRoomId,
                'roomType_id'  => $newRoomTypeId,
                'hotel_id'     => $hotelID,
                'status'       => 'available',
                'amenities'    => json_encode($validated['amenities'] ?? []),
                'floor'        => $validated['floor'],
                'size'         => $validated['size'],
                'capacity'     => $validated['capacity'],
                'RoomNumber'   => $validated['room-number'],
                'created_at'   => now(),
            ];
            $this->supabase->insert('Room', $roomData);

            return redirect()->back()->with('success', 'Room added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($roomNo)
    {
        $room = $this->getTableData('Room', ['RoomNo' => 'eq.' . $roomNo], '*, RoomType(*)');
        if (empty($room)) {
            return redirect()->back()->with('error', 'Room not found.');
        }
        $room = $room[0];
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        return view('form.update.update_room', compact('room'));
    }

    public function updateRoom(Request $request, $roomNo)
    {
        $validated = $request->validate([
            'room-number' => 'required|string|max:50',
            'room-type' => 'required|string|max:100',
            'floor' => 'required|integer|min:0',
            'capacity' => 'required|integer|min:1',
            'size' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'bed-type' => 'required|string|max:100',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $room = $this->getTableData('Room', ['RoomNo' => 'eq.' . $roomNo]);
            if (empty($room)) {
                throw new \Exception('Room not found.');
            }

            $roomTypeId = $room[0]['roomType_id'];

            $this->updateRecord('RoomType', ['roomType_id' => $roomTypeId], [
                'RoomPrice' => $validated['price'],
                'type_name' => $validated['room-type'],
                'bedType' => $validated['bed-type'],
                'RoomDesc' => $validated['description'] ?? '',
            ]);

            // Update Room table
            $this->updateRecord('Room', ['RoomNo' => $roomNo], [
                'RoomNumber' => $validated['room-number'],
                'floor' => $validated['floor'],
                'capacity' => $validated['capacity'],
                'size' => $validated['size'],
                'amenities' => json_encode($validated['amenities'] ?? []),
            ]);

            return redirect('/business_owner/room')->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function destroy($staffId)
    {
        $this->deleteRecord($this->table, ['staff_id' => $staffId]);
        return redirect()->back()->with('success', 'Staff deleted successfully.');
    }
}
