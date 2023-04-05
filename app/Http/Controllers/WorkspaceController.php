<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

use App\Models\Workspace;

class WorkspaceController extends Controller
{
    /**
     * Create new
     */
    public function createWorkspace(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'timezone' => 'required|string',
            
        );
        $messages = [

            'name.required' => '* Name is required',
            'name.string' => '* Invalid characters',
            'name.max' => '* name is too long',

            'timezone.required' => '* Timezone is required',
            'timezone.string' => '* Invalid characters',

            'logo.image' => 'File must be an image',
            'logo.mimes' => 'Image format must be of type jpg, png, jpeg, gif or svg',
            'logo.max' => 'Image size must be less than 2MB',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $workspace = new Workspace();
            $workspace->name = $request->name;
            $workspace->created_by = $request->created_by ? $request->created_by : null;
            $workspace->timezone = $request->timezone;
            
            if ($request->logo) {
                //image
                $imageName = time().'.'.$request->logo->extension();
                //store logo in folder
                $request->logo->storeAs('workspace', $imageName, 'public');
                $workspace->logo = $imageName;
            }
            
            $workspace->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Workspace created successfully',
                'data' => $workspace
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function singleWorkspace($id)
    {
        try {
            $workspace = Workspace::find($id);
            if(!isset($workspace)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Workspace Not Found',
                    'data' => []
                ]);
            }
            return response()->json([
                'status' => 'success',
                'data' => $workspace
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => "Something went wrong"
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function allWorkspace()
    {
        try {
            $workspaces = Workspace::all();
            return response()->json([
                'status' => 'success',
                'data' => $workspaces
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => "Something went wrong"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateWorkspace(Request $request, string $id)
    {
        $rules = array(
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'timezone' => 'required|string',
            
        );
        $messages = [

            'name.required' => '*Name is required',
            'name.string' => '* Invalid characters',
            'name.max' => '* name is too long',

            'timezone.required' => '* Timezone is required',
            'timezone.string' => '* Invalid characters',

            'logo.image' => 'File must be an image',
            'logo.mimes' => 'Image format must be of type jpg, png, jpeg, gif or svg',
            'logo.max' => 'Image size must be less than 2MB',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $workspace = Workspace::find($id);
            if(!isset($workspace)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Workspace Not Found',
                    'data' => []
                ]);
            }
    
            $workspace->name = $request->name;
            $workspace->timezone = $request->timezone;
            
            if ($request->logo) {
                $oldImage = $workspace->logo; //1.jpg
                if(Storage::disk('public')->exists('workspace/'.$oldImage)){
                    Storage::disk('public')->delete('workspace/'.$oldImage);
                }
                $imageName = time().'.'.$request->logo->extension();
                //store workspace in folder
                $request->logo->storeAs('workspace', $imageName, 'public');
                $workspace->logo = $imageName;
            }
            
            $workspace->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Workspace updated successfully',
                'data' => $workspace
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteWorkspace(string $id)
    {
        $workspace = Workspace::find($id);
        if(!isset($workspace)){
            return response()->json([
                'status' => 'error',
                'message' => 'Workspace Not Found',
                'data' => []
            ]);
        }
        $oldImage = $workspace->logo;
        if(Storage::disk('public')->exists('workspace/'.$oldImage)){
            Storage::disk('public')->delete('workspace/'.$oldImage);
        }
        $workspace->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully'
        ]);
    }
}
