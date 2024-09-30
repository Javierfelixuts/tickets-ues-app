<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\Exportable;

//START COLUMM B1
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Shared\Date;
//add sign to de excel
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UsersExport implements FromCollection, WithDrawings, WithColumnFormatting, WithCustomStartCell

{
    use Exportable;
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
    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            /* 'C' => NumberFormat::FORMAT_CURRENCY_EUR_INTEGER, */
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function drawings()
    {
        // sign from requester
        $imagePath = storage_path('app/public/sign_1.jpg');
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($imagePath);
        $drawing->setHeight(50);
        $drawing->setCoordinates('O3');

        // image from Asignee
        $drawing2 = new Drawing();
        $drawing2->setName('Other image');
        $drawing2->setDescription('This is a second image');
        $drawing2->setPath($imagePath);
        $drawing2->setHeight(50);
        $drawing2->setCoordinates('O8');

        return [$drawing, $drawing2];
    }
}
