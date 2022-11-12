<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewLeaveApplicationRequest;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\ApplicationApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:leave-list|leave-create|leave-edit|leave-delete', ['only' => ['index','store']]);
        $this->middleware('permission:leave-create', ['only' => ['create','store']]);
        $this->middleware('permission:leave-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:leave-approve', ['only' => ['edit','update']]);
        $this->middleware('permission:leave-reject', ['only' => ['edit','update']]);
        $this->middleware('permission:leave-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user = Auth::user()->id;

        $leaves = LeaveApplication::with(['type'])->where('applier_user_id', $user)->latest()->paginate(5);
        return view('leaves.index',compact('leaves'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function allLeaves()
    {
        $leaves = LeaveApplication::with(['type'])->latest()->paginate(5);
        return view('leaves.index',compact('leaves'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $leave = LeaveApplication::where('id', $id)->with(['type', 'authorizer'])->first();
        $type = LeaveType::all();

        return view('leaves.edit',compact('leave', 'type'));
    }

    public function show(LeaveApplication $leave)
    {
        $leave->with(['type', 'authorizer']);
        return view('leaves.show',compact('leave'));
    }

    public function create()
    {
        $data['leaveTypes'] = LeaveType::all();
        return view('leaves.create', $data);
    }

    public function store(NewLeaveApplicationRequest $request)
    {
        $application = new LeaveApplication();

        $application->reason        = $request['reason'];
        $application->information   = $request['information'];
        $application->applier_user_id = Auth::id();
        $application->start_date    = $request['start_date'];
        $application->end_date      = $request['end_date'];
        $application->leave_type_id = $request['leave_type'];

        $application->save();

        Session::Flash('success', 'Application Submitted Successfully.');
        return redirect()->route('leaves.index');
    }

    public function update(Request $request, LeaveApplication $leave)
    {
        $leave->remarks = $request['remarks'];
        $leave->authorizer_user_id = Auth::id();

        if($request->has('reason') && $request['reason'] == 'approved') {
            $leave->status = 'approved';
        } else {
            $leave->status = 'rejected';
        }
        $leave->save();
        $leave->refresh();
        if($leave->status == 'approved') {
            Session::Flash('success', 'Application Approved Successfully.');
            Notification::send($leave->applier, new ApplicationApprovedNotification($leave));
        }else{
            Session::Flash('failed', 'Application Rejected Successfully.');
        }
        return redirect()->back();
    }

    public function destroy(LeaveApplication $leave)
    {
        $leave->delete();

        return redirect()->back()
                        ->with('success','Leave deleted successfully');
    }
}
