<?php

namespace App\Http\Controllers\Supabase\GUEST;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GUESTController extends BaseSupabaseController
{
    private function geocodeAddress($fullAddress)
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'header' => 'User-Agent: TripMate/1.0',
                    'timeout' => 5
                ]
            ]);

            $url = "https://nominatim.openstreetmap.org/search?" . http_build_query([
                'q' => $fullAddress,
                'format' => 'json',
                'limit' => 1
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                Log::warning('Geocoding failed for address: ' . $fullAddress);
                return ['lat' => 0.0, 'lng' => 0.0];
            }

            $data = json_decode($response, true);

            if (!empty($data)) {
                return [
                    'lat' => (float)$data[0]['lat'],
                    'lng' => (float)$data[0]['lon']
                ];
            }

            return ['lat' => 0.0, 'lng' => 0.0];
        } catch (\Exception $e) {
            Log::error('Geocoding error: ' . $e->getMessage());
            return ['lat' => 0.0, 'lng' => 0.0];
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_type' => 'required|string|in:Hotel,Restaurant',
            'business_name' => 'required|string|max:255',
            'business_license_no' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'country' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'description' => 'required|string',
        ]);

        try {
            // Start transaction (if your Supabase wrapper supports it)
            // Otherwise handle rollback manually

            // === 1. GENERATE USER ID ===
            $users = $this->supabase->get('User', [], 'user_id');
            $latestUser = collect($users)->sortByDesc('user_id')->first();
            $newUserNum = $latestUser ? str_pad(intval(substr($latestUser['user_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newUserId = 'UU' . $newUserNum;

            // === 2. INSERT USER FIRST (No foreign key dependencies) ===
            $userData = [
                'user_id' => $newUserId,
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'role' => 'owner',
                'status' => 'pending',
                'created_at' => now()->toIso8601String(),
            ];

            $userResponse = $this->supabase->insert('User', $userData);

            if (isset($userResponse['error'])) {
                throw new \Exception('User insert failed: ' . $userResponse['error']['message']);
            }

            // === 3. GENERATE BUSINESS OWNER ID ===
            $owner = $this->supabase->get('Bussiness_Owner', [], 'owner_id');
            $latestOwner = collect($owner)->sortByDesc('owner_id')->first();
            $newOwnerNum = $latestOwner ? str_pad(intval(substr($latestOwner['owner_id'], 5)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newOwnerId = 'BOSMY' . $newOwnerNum;

            // === 4. INSERT BUSINESS OWNER (Depends on User) ===
            $ownerData = [
                'owner_id' => $newOwnerId,
                'business_name' => $validated['business_name'],
                'user_id' => $newUserId,
                'contact_number' => $validated['phone'], // FIXED: was 'phone'
                'business_license_no' => $validated['business_license_no'],
                'type' => $validated['business_type'],
                'registration_date' => now()->toIso8601String(),
                'apply_status' => 'Pending',
                'mother_country' => $validated['country'],
                'main_city' => $validated['city'],
            ];

            $ownerResponse = $this->supabase->insert('Bussiness_Owner', $ownerData);

            if (isset($ownerResponse['error'])) {
                throw new \Exception('Business Owner insert failed: ' . $ownerResponse['error']['message']);
            }

            // === 5. GENERATE ADDRESS ID ===
            $address = $this->supabase->get('Address', [], 'address_id');
            $latestAddress = collect($address)->sortByDesc('address_id')->first();
            $newAddressNum = $latestAddress ? str_pad(intval(substr($latestAddress['address_id'], 5)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newAddressId = 'ADRMY' . $newAddressNum;

            // Get coordinates
            $coords = $this->geocodeAddress($validated['address']);

            // === 6. INSERT ADDRESS (Now owner_id exists) ===
            $addressData = [
                'address_id' => $newAddressId,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['zip'],
                'latitude' => $coords['lat'],
                'longitude' => $coords['lng'],
                'owner_id' => $newOwnerId,
            ];

            $addressResponse = $this->supabase->insert('Address', $addressData);

            if (isset($addressResponse['error'])) {
                throw new \Exception('Address insert failed: ' . $addressResponse['error']['message']);
            }

            // === 7. GENERATE BUSINESS ID ===
            $business = $this->supabase->get('Business', [], 'business_id');
            $latestBusiness = collect($business)->sortByDesc('business_id')->first();
            $newBusinessNum = $latestBusiness ? str_pad(intval(substr($latestBusiness['business_id'], 3)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newBusinessId = 'BUS' . $newBusinessNum;

            // === 8. CREATE HOTEL OR RESTAURANT RECORD ===
            if ($validated['business_type'] == 'Hotel') {
                // Generate Hotel ID
                $hotelID = $this->supabase->get('Hotel', [], 'hotel_id');
                $latestHotel = collect($hotelID)->sortByDesc('hotel_id')->first();
                $newHotelNum = $latestHotel ? str_pad(intval(substr($latestHotel['hotel_id'], 5)) + 1, 3, '0', STR_PAD_LEFT) : '001';
                $newHotelId = 'HOTMY' . $newHotelNum;

                // Insert Hotel
                $hotelData = [
                    'hotel_id' => $newHotelId,
                    'name' => $validated['business_name'],
                    'state' => $validated['state'],
                    'status' => 'pending',
                    'created_at' => now()->toIso8601String(),
                ];

                $hotelResponse = $this->supabase->insert('Hotel', $hotelData);

                if (isset($hotelResponse['error'])) {
                    throw new \Exception('Hotel insert failed: ' . $hotelResponse['error']['message']);
                }

                // Insert Business with hotel_id
                $businessData = [
                    'business_id' => $newBusinessId,
                    'owner_id' => $newOwnerId,
                    'hotel_id' => $newHotelId,
                ];

                // Insert AddressState linking
                $addressStateData = [
                    'hotel_id' => $newHotelId,
                    'address_id' => $newAddressId,
                ];
                $this->supabase->insert('AddressState', $addressStateData);
            } else {
                // Generate Restaurant ID
                $restID = $this->supabase->get('Restaurant', [], 'restaurant_id');
                $latestRestaurant = collect($restID)->sortByDesc('restaurant_id')->first();
                $newResNum = $latestRestaurant ? str_pad(intval(substr($latestRestaurant['restaurant_id'], 5)) + 1, 3, '0', STR_PAD_LEFT) : '001';
                $newResId = 'RESMY' . $newResNum;

                // Insert Restaurant
                $restaurantData = [
                    'restaurant_id' => $newResId,
                    'name' => $validated['business_name'],
                    'state' => $validated['state'],
                    'address' => $validated['address'],
                    'status' => 'pending',
                ];

                $restaurantResponse = $this->supabase->insert('Restaurant', $restaurantData);

                if (isset($restaurantResponse['error'])) {
                    throw new \Exception('Restaurant insert failed: ' . $restaurantResponse['error']['message']);
                }

                // Insert Business with restaurant_id
                $businessData = [
                    'business_id' => $newBusinessId,
                    'owner_id' => $newOwnerId,
                    'restaurant_id' => $newResId,
                ];

                // Insert AddressState linking
                $addressStateData = [
                    'restaurant_id' => $newResId,
                    'address_id' => $newAddressId,
                ];
                $this->supabase->insert('AddressState', $addressStateData);
            }

            // === 9. INSERT BUSINESS ===
            $businessResponse = $this->supabase->insert('Business', $businessData);

            if (isset($businessResponse['error'])) {
                throw new \Exception('Business insert failed: ' . $businessResponse['error']['message']);
            }

            Log::info('Business registration successful', [
                'user_id' => $newUserId,
                'owner_id' => $newOwnerId,
                'business_id' => $newBusinessId
            ]);

            return redirect()->back()->with('success', 'Business registration submitted successfully! We will review your application shortly.');
        } catch (\Exception $e) {
            Log::error('Business registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }


    public function index()
    {
        try {
    
            $owners = $this->supabase->get('Bussiness_Owner', [], '*');

            $businessList = [];

            foreach ($owners as $owner) {
       
                $addresses = $this->supabase->get('Address', [
                    'owner_id' => $owner['owner_id']
                ], '*');

                $address = $addresses[0] ?? null;

       
                $businesses = $this->supabase->get('Business', [
                    'owner_id' => $owner['owner_id']
                ], '*');

                $business = $businesses[0] ?? null;

           
                $businessName = $owner['business_name'];
                $businessType = $owner['type'];
                $status = $owner['apply_status'];

                if ($business) {
                    if (!empty($business['hotel_id'])) {
                        $hotels = $this->supabase->get('Hotel', [
                            'hotel_id' => $business['hotel_id']
                        ], '*');
                        $hotel = $hotels[0] ?? null;
                        if ($hotel) {
                            $businessName = $hotel['name'];
                            $status = $hotel['status'];
                        }
                    } elseif (!empty($business['restaurant_id'])) {
                        $restaurants = $this->supabase->get('Restaurant', [
                            'restaurant_id' => $business['restaurant_id']
                        ], '*');
                        $restaurant = $restaurants[0] ?? null;
                        if ($restaurant) {
                            $businessName = $restaurant['name'];
                            $status = $restaurant['status'];
                        }
                    }
                }

                $businessList[] = [
                    'owner_id' => $owner['owner_id'],
                    'business_id' => $business['business_id'] ?? null,
                    'business_name' => $businessName,
                    'type' => $businessType,
                    'location' => $address ? $address['city'] : 'N/A',
                    'status' => $status,
                    'created_at' => $owner['registration_date'] ?? null,
                ];
            }

         
            usort($businessList, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            return view('admin.BusinessManagement', [
                'businesses' => $businessList,
                'adminName' => session('admin_name', 'Admin')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch business list: ' . $e->getMessage());
            return view('admin.BusinessManagement', [
                'businesses' => [],
                'adminName' => session('admin_name', 'Admin'),
                'error' => 'Failed to load business data'
            ]);
        }
    }

   
    public function show($ownerId)
{
    try {

        $owner = $this->getTableData('Bussiness_Owner', [
            'owner_id' => "eq.$ownerId"
        ]);

        if (!$owner || count($owner) == 0) {
            throw new \Exception("Owner not found");
        }
        $owner = $owner[0]; 

       
        $user = $this->getTableData('User', [
            'user_id' => "eq." . $owner['user_id']
        ]);
        $user = $user[0];

   
        $address = $this->getTableData('Address', [
            'owner_id' => "eq.$ownerId"
        ]);

      
        $businessDetails = []; 


        return view('admin.BussineeDetails', compact(
            'owner',
            'user',
            'address',
            'businessDetails'
        ));

    } catch (\Exception $e) {
        return redirect()->route('admin.business.index')
            ->with('error', 'Error loading business details: ' . $e->getMessage());
    }
}


    
    public function approve($ownerId)
    {
        try {
            $result = $this->updateRecord(
                'Bussiness_Owner',
                ['owner_id' => $ownerId],
                ['apply_status' => 'Approved']
            );

            $userData = $this->getTableData(
                'Bussiness_Owner',
                ['owner_id' => "eq.$ownerId"],
                'user_id'
            );
            $userId = $userData[0]['user_id'];

            $this->updateRecord(
                'User',
                ['user_id' => $userId],
                ['status' => 'active']
            );

            $businessList = $this->getTableData(
            'Business',
            ['owner_id' => "eq.$ownerId"],
            'hotel_id, restaurant_id'
        );

        if (!empty($businessList)) {

            foreach ($businessList as $business) {

                if (!empty($business['hotel_id'])) {
                    $this->updateRecord(
                        'Hotel',
                        ['hotel_id' => $business['hotel_id']],
                        ['status' => 'activate']
                    );
                }

                if (!empty($business['restaurant_id'])) {
                    $this->updateRecord(
                        'Restaurant',
                        ['restaurant_id' => $business['restaurant_id']],
                        ['status' => 'activate']
                    );
                }
            }
        }
            
            return redirect()->route('admin.business.index', $ownerId)
                ->with('success', 'Business approved successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.business.index', $ownerId)
                ->with('error', 'Error approving business: ' . $e->getMessage());
        }
    }
    
    public function decline($ownerId)
{
    try {

        $this->updateRecord(
            'Bussiness_Owner',
            ['owner_id' => $ownerId],
            ['apply_status' => 'Declined']
        );

        $userData = $this->getTableData(
                'Bussiness_Owner',
                ['owner_id' => "eq.$ownerId"],
                'user_id'
            );
            $userId = $userData[0]['user_id'];
            
            $this->updateRecord(
                'User',
                ['user_id' => $userId],
                ['status' => 'inactive']
            );

        $businessList = $this->getTableData(
            'Business',
            ['owner_id' => "eq.$ownerId"],
            'hotel_id, restaurant_id'
        );

        if (!empty($businessList)) {

            foreach ($businessList as $business) {

                if (!empty($business['hotel_id'])) {
                    $this->updateRecord(
                        'Hotel',
                        ['hotel_id' => $business['hotel_id']],
                        ['status' => 'Declined']
                    );
                }

                if (!empty($business['restaurant_id'])) {
                    $this->updateRecord(
                        'Restaurant',
                        ['restaurant_id' => $business['restaurant_id']],
                        ['status' => 'Declined']
                    );
                }
            }
        }

        return redirect()->route('admin.business.index', $ownerId)
            ->with('success', 'Business declined successfully.');

    } catch (\Exception $e) {
        return redirect()->route('admin.business.index', $ownerId)
            ->with('error', 'Error declining business: ' . $e->getMessage());
    }
}

}
