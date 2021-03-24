<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::simplePaginate(10);
        return view('admin.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email',
            'website' => 'required | url',
            'logo' => 'required | file',
        ]);

        $logo = $request->file('logo');
        $slug = str_slug($request->name);

        if (isset($logo))
        {
            $currentDate = Carbon::now()->toDateString();
            $logoname = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $logo->getClientOriginalExtension();
            if (!file_exists('storage/app/public'))
            {
                mkdir('storage/app/public', 0777, true);
            }
            //unlink('storage/app/public/' . $admission->logo);
            $logo->move('storage/app/public', $logoname);
        }
        else {
            $logoname = 'default.png';
        }

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->logo = $logoname;
        $company->save();
        return redirect()->route('admin.company.index')->with('successMsg', 'Company Saved Successfully!');

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
        $company = Company::findOrFail($id);
        return view('admin.company.edit', compact('company'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'website' => 'required | url',
            //'logo' => 'required | file',
        ]);

        $logo = $request->file('logo');
        $slug = str_slug($request->name);
        $company = Company::findOrFail($id);

        if (isset($logo))
        {
            $currentDate = Carbon::now()->toDateString();
            $logoname = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $logo->getClientOriginalExtension();
            if (!file_exists('storage/app/public'))
            {
                mkdir('storage/app/public', 0777, true);
            }
            //unlink('storage/app/public/' . $admission->logo);
            $logo->move('storage/app/public', $logoname);
        }
        else {
            $logoname = 'default.png';
        }

        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->logo = $logoname;
        $company->save();
        return redirect()->route('admin.company.index')->with('successMsg', 'Company Updated Successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect()->route('admin.company.index')->with('successMsg', 'Company Deleted Successfully!');
    }
}
