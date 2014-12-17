<?php
/**
 * Author: VincentBel
 * Date: 2014/12/17
 * Time: 10:00
 */

class Reservation_Number_Info_TableSeeder extends Seeder
{
    public function run()
    {
        DB::table('reservation_number_info')->delete();

        $visitInfos = DB::table('visit_info')
                ->join('doctor','visit_info.doctor_id', '=', 'doctor.doctor_id')
                ->join('department', 'doctor.department_id', '=', 'department.department_id')
                ->get();

        // 今天的日期
        $today = date("Y-m-d", time());

        // 今天是星期几
        $todayWeek  = (int)date('w', time());

        foreach ($visitInfos as $key => $visitInfo) {
            $reservationCycle = DB::table('hospital')
                ->where('hospital_id', '=', $visitInfo->hospital_id)
                ->pluck('reservation_cycle');


            // 今天到预约表中的星期还有几天
            $deltaWeek = (($visitInfo->week + 7) - $todayWeek) % 7;

            for ($i = 0; $i <= $reservationCycle; $i += 7) {

                $date = date('Y-m-d', strtotime($today. ' + '.($deltaWeek + $i).' days'));

                DB::table('reservation_number_info')->insert(array(
                    'doctor_id' => $visitInfo->doctor_id,
                    'department_id' => $visitInfo->department_id,
                    'date' => $date,
                    'start_time' => $visitInfo->start_time,
                    'end_time' => $visitInfo->end_time,
                    'total_number' => $visitInfo->reservation_number,
                    'remain_number' => $visitInfo->reservation_number,
                    'reservation_fee' => $visitInfo->reservation_fee
                ));
            }
        }
    }

}