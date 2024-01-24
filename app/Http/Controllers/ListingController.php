<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // Show and get all listings
    public function index () {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(5)
        ]);
    }

    public function create (Listing $listing) {
        return view('listings.create');
    }

    public function store (Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'tags' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id'] = auth()->id();
        try {
            Listing::create($formFields);
            return redirect('/')->with('message', 'Listing created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create listing');
        }
    }

     //show single listing
     public function show (Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //show edit form;
    public function edit(Listing $listing) {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    //edit;
    public function update (Request $request, Listing $listing){
        // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $formFields = $request->validate([
            'title' => 'required',
            'tags' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        try {
            $listing->update($formFields);
            return redirect('/')->with('message', 'Listing updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create listing');
        }
    }
     //delete listing;
     public function destroy(Listing $listing) {
         // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
       $listing->delete();
       return redirect('/')->with('message', 'Listing deleted successfully');
    }

    public function manage(){
        return view ('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
