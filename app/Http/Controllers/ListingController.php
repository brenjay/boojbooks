<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\Listing;
use App\Models\Book;

class ListingController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'has_owner']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return $request->user()->listings();
        return Inertia::render('Listing', [
            'listings' => $request->user()->listings()->get()
         ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required']
        ])->validate();
        
        //$request->request->add(['user_id' => $request()->user()->id]);

        $listing = new Listing;
        $listing->name = $request->name;
        $listing->user_id = $request->user()->id;
        $listing->save();
  
        return redirect()->back()
                    ->with('message', 'Listing Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Listing $listing)
    {   

        return Inertia::render('ListingDetail', [
                'listing' => $listing,
                'books' => $listing->books()->orderBy('list_order')->get(),
             ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listing $listing)
    {
        Validator::make($request->all(), [
            'name' => ['required']
        ])->validate();
  
        if ($request->has('id')) {
            $listing->update($request->all());
            return redirect()->back()
                    ->with('message', 'Listing Updated Successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Listing $listing)
    {
        if ($request->has('id')) {
            $listing->delete();
            return redirect()->back()->with('message', 'List Deleted Successfully.');
        }
    }

}
