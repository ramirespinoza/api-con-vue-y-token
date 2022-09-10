<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    //** API METHODS */SS

    public function deleteCustomer(Request $request, $id) {
        
        try {
            $customer = Customer::findOrFail($id);

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
        
            $customers = Customer::orderBy('id', 'DESC')->get();

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
       
            try {
                $this->validate($request, [
                    'name' => 'required',
                    'address' => 'required',
                    'phone_number' => 'required',
                ]);
        
                Customer::create($request->all());
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
            try {
                $this->validate($request, [
                    'name' => 'required',
                    'address' => 'required',
                    'phone_number' => 'required',
                ]);
        
                Customer::find($id)->update($request->all());
        
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
