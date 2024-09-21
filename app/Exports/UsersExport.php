<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function __construct($request = null)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
     
        $user = Auth::user();
        $query = Ticket::with(['user:id,name', 'assignee'])->where('user_id', $user->id)->latest();
        if($this->request->start_date && $this->request->end_date){
            $query->whereDate('created_at','>=',$this->request->start_date)
            ->whereDate('created_at','<=',$this->request->end_date);
        }
        
        $result =$query->get();
        return $result;
    }
}
