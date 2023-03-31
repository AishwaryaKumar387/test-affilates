<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Affiliate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index($id, $items = 10)
    {
        $id = base64_decode($id);
        $data['items'] = $items;
        $data['affiliate'] = Affiliate::whereId($id)->withTrashed()->first();
        if($data['affiliate']){
            $data['lists'] = ApiKey::whereAffiliateId($id)->orderBy('id', 'DESC')->withTrashed()->paginate($items);
            $data['page_title'] = 'App Data - API Keys List of '.$data['affiliate']->name;
            $data['page_description'] = 'App Data - API Keys  List of '.$data['affiliate']->name;
            return view('api-key.index', $data);
        }
        abort(404);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'affiliate_id'  => 'required|exists:affiliates,id',
            ]
        );

        $request->merge(['key' => self:: keyGen()]);
        $save = ApiKey::create($request->all());

        if ($save) {
            return redirect()->back()->with('success', 'API Key Added');
        }
        return redirect()->back()->with('error', 'Something Went wrong');
    }

    public function status(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = ApiKey::find($id);
            if ($find) {
                $find->status = $find->status == 1 ? 0 : 1;
                $find->save();
                return response()->json(['success' => true, 'message' => 'API Key Status Changed'], 200);
            }
            return response()->json(['success' => false, 'message' => 'API Key Not Found'], 404);
        }
        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = ApiKey::find($id);
            if ($find) {
                $find->delete();
                return response()->json(['success' => true, 'message' => 'API Key Deleted'], 200);
            }
            return response()->json(['success' => false, 'message' => 'API Key Not Found'], 404);
        }
        abort(403);
    }

    public function permanentDelete(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = ApiKey::onlyTrashed()->find($id);
            if ($find) {
                $find->forceDelete();
                return response()->json(['success' => true, 'message' => 'API Key Deleted Permanently'], 200);
            }
            return response()->json(['success' => false, 'message' => 'API Key Not Found'], 404);
        }
        abort(403);
    }

    public function restore(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = ApiKey::onlyTrashed()->find($id);
            if ($find) {
                $find->restore();
                return response()->json(['success' => true, 'message' => 'API Key Restored'], 200);
            }
            return response()->json(['success' => false, 'message' => 'API Key Not Found'], 404);
        }
        abort(403);
    }

    private function keyGen(){
        $KEY = Str::upper(Str::random(5)).'-'.Str::upper(Str::random(5)).'-'.Str::upper(Str::random(5)).'-'.Str::upper(Str::random(5));
        if(ApiKey::whereKey($KEY)->exists()){
            self:: keyGen();
        }
        return $KEY;
    }
}
