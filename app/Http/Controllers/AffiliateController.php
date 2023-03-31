<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{

    public function index(Request $request, $items = 10)
    {
        $data['items'] = $items;
        $data['lists'] = Affiliate::whereNull('affiliate_id')->where('name', 'LIKE', "%{$request->q}%")->withCount(['subAffiliates', 'keys'])->orderBy('id', 'DESC')->withTrashed()->paginate($items);
        $data['lists']->appends(['q' => $request->q])->links();
        $data['page_title'] = 'App Data - Affiliate List';
        $data['page_description'] = 'App Data - Affiliate List';
        return view('affiliate.index', $data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'          => 'required',
                'company_name'  => 'required',
                'address'       => 'required',
                'phone'         => 'required|min:10|max:10',
            ]
        );

        $save = Affiliate::create($request->all());

        if ($save) {
            return redirect()->back()->with('success', 'Affiliate Added');
        }
        return redirect()->back()->with('error', 'Something Went wrong');
    }

    public function add(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $data['id'] = $id;
            return response()->json(['success' => true, 'message' => 'Affiliate Add', 'data' => view('sub-affiliate.add', $data)->render()]);
        }
        abort(403);
    }

    public function edit(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Affiliate::find($id);
            if ($find) {
                $data['affiliate'] = $find;
                return response()->json(['success' => true, 'message' => 'Affiliate Edit', 'data' => view('affiliate.edit', $data)->render()]);
            }
            return response()->json(['success' => false, 'message' => 'Affiliate Not Found'], 404);
        }
        abort(403);
    }

    public function update(Request $request)
    {
        $id = base64_decode($request->id);

        $this->validate(
            $request,
            [
                'name'          => 'required',
                'company_name'  => 'required',
                'address'       => 'required',
                'phone'         => 'required|min:10|max:10',
            ]
        );

        $find = Affiliate::find($id);
        if ($find) {
            
            $find->name         = $request->name;
            $find->company_name = $request->company_name;
            $find->address      = $request->address;
            $find->phone        = $request->phone;

            $find->save();
            return redirect()->back()->with('success', 'Affiliate Updated');
        }
        return redirect()->back()->with('error', 'Affiliate Not Found');
    }

    public function status(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Affiliate::find($id);
            if ($find) {
                $find->status = $find->status == 1 ? 0 : 1;
                $find->save();
                return response()->json(['success' => true, 'message' => 'Affiliate Status Changed'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Affiliate Not Found'], 404);
        }
        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Affiliate::find($id);
            if ($find) {
                $find->delete();
                return response()->json(['success' => true, 'message' => 'Affiliate Deleted'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Affiliate Not Found'], 404);
        }
        abort(403);
    }

    public function permanentDelete(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Affiliate::onlyTrashed()->find($id);
            if ($find) {
                $find->forceDelete();
                return response()->json(['success' => true, 'message' => 'Affiliate Deleted Permanently'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Affiliate Not Found'], 404);
        }
        abort(403);
    }

    public function restore(Request $request, $id)
    {

        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Affiliate::onlyTrashed()->find($id);
            if ($find) {
                $find->restore();
                return response()->json(['success' => true, 'message' => 'Affiliate Restored'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Affiliate Not Found'], 404);
        }
        abort(403);
    }
}
