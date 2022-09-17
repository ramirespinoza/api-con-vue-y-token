<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\User;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    //** API METHODS */SS

    public function getCustomers(Request $request) {

        $userID = $request->user()->id;
        $customers = null;

        try {
            $customers = Customer::where('user_id', $userID)->get();

        } catch (\Throwable $th) {
            return response()->json([
                'customers' => $customers,
                'operation' => 'get',
                'status' => 'failed',
                'code' => '0'
            ]);    
        }
        

        return response()->json([
            'owner' => $request->user(),
            'customers' => $customers,
            'operation' => 'get',
            'status' => 'successful',
            'code' => '1'
        ]);        
    }

    public function deleteCustomer(Request $request, $id) {
        
        $userID = $request->user()->id;
        $customer = null;
        $match = ['user_id' => $userID, 'id' => $id];

        try {
            $customer = Customer::where($match)->first();
            //$customer = Customer::findOrFail($id);

            $customer->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'customer' => $customer,
                'operation' => 'delete',
                'status' => 'failed',
                'code' => '0'
            ]);    
        }
        

        return response()->json([
            'customer' => $customer,
            'operation' => 'delete',
            'status' => 'successful',
            'code' => '1'
        ]);        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
            //$customers = Customer::orderBy('id', 'DESC')->get();
            //$customers = DB::table('customer')
            //->join('users', 'customer.user_id', '=', 'users.id')
            //->where('countries.country_name', $country)
            //->get();

            $customers = Customer::orderBy('id', 'DESC')->get();

            foreach($customers as $customer){
                $customer->user_email = User::findOrFail($customer->user_id)->email;
            }
        

            return $customers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*    
        return response()->json([
                'response-all' => $request->all(),
                'response-user' => $request->user(),
                'response-solomail' => $request->user()->email,
                'respones-mail' => $request->email
            ]);
*/
       
            try {
                $this->validate($request, [
                    'name' => 'required',
                    'address' => 'required',
                    'phone_number' => 'required'
                ]);

                $form = [
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'user_id' => $request->user()->id
                ];
        
                Customer::create($form);
            } catch (\Throwable $th) {
                if($request->path() == 'api/create-customer') {
                    return response()->json([
                        'customer' => $request->all(),
                        'operation' => 'create',
                        'status' => 'failed',
                        'code' => '0'
                    ]);
                } else {
                    throw $th;;
                }
            }

            if($request->path() == 'api/create-customer') {
                return response()->json([
                    'customer' => $request->all(),
                    'operation' => 'create',
                    'status' => 'successful',
                    'code' => '1'
                ]);
            } else {
                return;
            }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        //Form
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            $userID = $request->user()->id;
            $match = [
                'id' => $id,
                'user_id' => $userID
            ];

            try {
                $this->validate($request, [
                    'name' => 'required',
                    'address' => 'required',
                    'phone_number' => 'required',
                ]);

                $form = [
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'user_id' => $userID
                ];
        
                $customer = Customer::where($match)->first();
                $customer->update($request->all());
        
            } catch (\Throwable $th) {
                if($request->path() == 'api/update-customer/' . $id) {
                    return response()->json([
                        'customer' => $request->all(),
                        'operation' => 'update',
                        'status' => 'failed',
                        'code' => '0'
                    ]);
                } else {
                    throw $th;
                }
            }
            
            if($request->path() == 'api/update-customer/' . $id) {
                return response()->json([
                    'customer' => $request->all(),
                    'operation' => 'update',
                    'status' => 'successful',
                    'code' => '1'
                ]);
            } else {
                return;
            }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();
    }
}
