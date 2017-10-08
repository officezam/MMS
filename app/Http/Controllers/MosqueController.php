<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mosque;
use App\NamazTime;

class MosqueController extends Controller
{

	public function __construct() {
		$this->mosque = new Mosque();
        $this->carbon = new Carbon();
        $this->namaztime = new NamazTime();
	}

	public function index(){
	    return view('backend.add_time');
    }
	public function saveMosque(Request $request){

		$carbon = new Carbon();

		$data = [
			'mosque_name' => $request->mosque_name,
			'city' => $request->city,
			'date' => $carbon->instance(new \DateTime($request->date))->toDateTimeString(),
			'fajar_time' => $carbon->instance(new \DateTime($request->fajar_time))->toDateTimeString(),
			'zuhar_time' => $carbon->instance(new \DateTime($request->zuhar_time))->toDateTimeString(),
			'asar_time' => $carbon->instance(new \DateTime($request->asar_time))->toDateTimeString(),
			'magrib_time' => $carbon->instance(new \DateTime($request->magrib_time))->toDateTimeString(),
			'esha_time' => $carbon->instance(new \DateTime($request->esha_time))->toDateTimeString(),
		];

		$userData = Mosque::create($data);

		return view('backend.add_mosque');
	}

	public function saveNamazTime(Request $request){

        $m_id = $request->m_id;

        if(empty($m_id))
        {
            $mosqueTableData = [
                'name' => $request->m_name,
                'keyword' => $request->m_keyword,
            ];
            $mosqueData = $this->mosque->create($mosqueTableData);
            $m_id = $mosqueData->id;
            $namazTimeData = [
                'm_id' => $m_id,
                'date' => $request->namaz_date,
                'fajar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->fajar_time")),
                'zuhar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->zuhar_time")),
                'jumma' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->jumma_time")),
                'asar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->asar_time")),
                'maghrib' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->magrib_time")),
                'esha' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->esha_time")),
            ];
            $namazTime = $this->namaztime->create($namazTimeData);
            return $m_id;

                $dataArray[] = ['title' => " = Fajar Time",'start' => $namazTime->fajar ];
            if(!empty($namazTime->zuhar)){
                $dataArray[] = ['title' => " = Zhuar Time",'start' => $namazTime->zuhar ];
            }else{
                $dataArray[] = ['title' => " = Jumma Time", 'start' => $namazTime->jumma];
            }
                $dataArray[] = ['title' => " = Asar Time",'start'       => $namazTime->asar ];
                $dataArray[] = ['title' => " = Maghrib Time",'start'    => $namazTime->maghrib ];
                $dataArray[] = ['title' => " = Esha Time",'start'       => $namazTime->esha ];

            return $dataArray;

        }else{
            return 'notEmpty';

        }

    }




    public function getNamazTime($namaz_id){

        $namazData  = $this->namaztime->where('m_id',$namaz_id)->get();
        $mosqueData = $this->mosque->find($namaz_id);


        foreach ($namazData as $namazTime)
        {
            $dataArray[] = (object)['title' => " = Fajar Time",'start' => $namazTime->fajar ];
            if(!empty($namazTime->zuhar))
            {
                $dataArray[] = (object)['title' => " = Zhuar Time",'start' => $namazTime->zuhar ];
            }else{
                $dataArray[] = (object)['title' => " = Jumma Time", 'start' => $namazTime->jumma];
            }
            $dataArray[] = (object)['title' => " = Asar Time",'start'       => $namazTime->asar ];
            $dataArray[] = (object)['title' => " = Maghrib Time",'start'    => $namazTime->maghrib ];
            $dataArray[] = (object)['title' => " = Esha Time",'start'       => $namazTime->esha ];
        }
dd($dataArray);
        return view('backend.update_time', compact('mosqueData','dataArray'));


    }




}