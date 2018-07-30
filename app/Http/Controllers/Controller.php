<?php

namespace App\Http\Controllers;

use App\Exceptions\NotValidatedException;
use App\Term;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Mockery\Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    const TERM_TYPE     = ['1st_term', 'summer_vacation', '2nd_term', 'winter_vacation'];
    const SCORE_TYPE    = ['final', 'midterm', 'homework', 'quiz'];
    const ADA_TYPE      = ['lateness', 'early_leave', 'absence'];
    const REASON_TYPE   = ['good','unreason','personal','sick','hometown','accident','disaster','etc'];

    // 01. 공통 메서드 선언
    public function getDailyValue($argDate = null) {
        // 01. 변수 선언
        $pervDay = null;
        $thisDay = null;
        $nextDay = null;

        if(is_null($argDate)) {
            // 매개인자가 없는 경우
            $thisDay = today();

        } else if(preg_match("/((19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/", $argDate)) {
            // 매개인자가 있는 경우 => 지정 형식이 맞는지 검사
            $thisDay = Carbon::parse($argDate);

        } else {
            // 형식을 지키지 않은 경우 => 예외처리

        }

        // 전날
        $prevDay = $thisDay->copy()->subday();

        // 다음날 => 다음날이 현재보다 과거 시점일 때만 할당
        if($thisDay->copy()->addDay()->lt(today())) {
            $nextDay = $thisDay->copy()->addDay();
        }

        return [
          'prev'            => $prevDay,
          'this'            => $thisDay,
          'this_format'     => __('interface.Y-m-d', ['year' => $thisDay->year, 'month' => $thisDay->month, 'day' => $thisDay->day]),
          'next'            => $nextDay
        ];
    }

    /**
     * 함수명:                         getWeeklyValue
     * 함수 설명:                      지난 주, 이번 주, 다음 주 값을 반환
     * 만든날:                         2018년 4월 08일
     *
     * 매개변수 목록
     * @param $argThisWeek:
     *
     * 지역변수 목록
     * null
     *
     * 반환값
     * @return                         array
     *
     * 예외
     * @throws ErrorException
     */
    public function getWeeklyValue($argThisWeek = null) {
        // 01. 변수 선언
        $thisWeek = null;
        $prevWeek = null;
        $nextWeek = null;

        // 매개인자가 없는 경우
        if(is_null($argThisWeek)) {
            // 이번주
            $thisWeek = today();

            // 매개인자가 있는 경우 => 데이터 형식을 지키고 있을 때
        } else if(preg_match("/^(19|20)\d{2}-[0-9]{1,2}$/", $argThisWeek)) {
            // 매개 데이터 분해
            $data = explode('-', $argThisWeek);

            // 이번주
            $thisWeek = Carbon::createFromDate($data[0], 1, 1);
            while($thisWeek->weekOfYear < $data[1]) {
                $thisWeek->addWeek();
            }
        } else {
            //throw new ErrorException('aaa');
        }
        $thisWeek->endOfWeek();

        // 지난주
        $prevWeek = $thisWeek->copy()->subWeek();

        // 기준 시간대가 이번주보다 과거인 경우 다음주 생성
        if(today()->endOfWeek()->gt($thisWeek->copy()->endOfWeek())) {
            // 다음주
            $nextWeek = $thisWeek->copy()->addWeek();
        }

        return [
            'prev'          => $prevWeek,
            'this'          => $thisWeek,
//            'this_format'   => "{$thisWeek->year}년 {$thisWeek->month}월 {$thisWeek->weekOfMonth}주차",
            'this_format'   => __('interface.Y-m-w', ['year' => $thisWeek->year, 'month' => $thisWeek->month, 'week' => $thisWeek->weekOfMonth]),
            'next'          => $nextWeek
        ];
    }

    /**
     * 함수명:                         getMonthlyValue
     * 함수 설명:                      지난 달, 이번 달, 다음 달 값을 반환
     * 만든날:                         2018년 4월 08일
     *
     * 매개변수 목록
     * @param $argThisMonth:           기준 달 값
     *
     * 지역변수 목록
     * null
     *
     * 반환값
     * @return                         array
     *
     * 예외
     * @throws ErrorException
     */
    public function getMonthlyValue($argThisMonth = null) {
        // 01. 변수 선언
        $thisMonth = null;
        $prevMonth = null;
        $nextMonth = null;

        // 매개인자가 없는 경우
        if(is_null($argThisMonth)) {
            // 이번주
            $thisMonth = today();

            // 매개인자가 있는 경우 => 데이터 형식을 지키고 있을 때
        } else if(preg_match("/^(19|20)\d{2}-(0[1-9]|1[012])$/", $argThisMonth)) {
            // 매개 데이터 분해
            $data = explode('-', $argThisMonth);

            // 이번주
            $thisMonth = Carbon::createFromDate($data[0], $data[1], 1);
        } else {
            throw new NotValidatedException(__('response.wrong_format'));
        }

        // 지난달
        $prevMonth = $thisMonth->copy()->subMonth();

        // 기준 시간대가 이번달보다 과거인 경우 다음달 생성
        if(today()->startOfMonth()->gt($thisMonth->copy()->startOfMonth())) {
            // 다음달
            $nextMonth = $thisMonth->copy()->addMonth();
        }


        return [
            'prev'          => $prevMonth,
            'this'          => $thisMonth,
//            'this_format'   => "{$thisMonth->year}년 {$thisMonth->month}월",
            'this_format'   => __('interface.Y-m', ['year' => $thisMonth->year, 'month' => $thisMonth->month]),
            'next'          => $nextMonth
        ];
    }

    /**
     * 함수명:                         getTermValue
     * 함수 설명:                      지난 학기, 이번 학기, 다음 학기 값을 반환
     * 만든날:                         2018년 4월 08일
     *
     * 매개변수 목록
     * @param $argThisTerm:            기준 학기
     *
     * 지역변수 목록
     * null
     *
     * 반환값
     * @return                         array
     *
     * 예외
     * @throws ErrorException
     */
    public function getTermValue($argThisTerm = null){
        // 01. 변수 선언
        $year       = null;
        $term       = null;
        $nowTerm    = null;
        $thisTerm   = null;
        $prevTerm   = null;
        $nextTerm   = null;

        // 현재 학기 설정
        $nowTerm    = Term::thisTerm();
        $termOrder  = [
            '1st_term'          => 1,
            'summer_vacation'   => 2,
            '2nd_term'          => 3,
            'winter_vacation'   => 4
        ];

        if(is_null($argThisTerm)) {
            $year = $nowTerm->year;
            $term = $nowTerm->term;

        } else if(preg_match("/(19|20)\d{2}-((1st|2nd)_term|(summer|winter)_vacation)/", $argThisTerm)) {
            $data = explode('-', $argThisTerm);

            $year = $data[0];
            $term = $data[1];
        } else {
            //throw new ErrorException();
            throw new Exception();
        }

        // 조회학기 설정
        $thisTerm = "{$year}-{$term}";

        // 지난 학기 설정
        switch($term) {
            case '1st_term':
                $prevTerm = ($year - 1).'-winter_vacation';
                break;
            case 'summer_vacation':
                $prevTerm = "{$year}-1st_term";
                break;
            case '2nd_term':
                $prevTerm = "{$year}-summer_vacation";
                break;
            case 'winter_vacation':
                $prevTerm = "{$year}-2nd_term";
                break;
        }

        // 조회 연도와 학기가 현재 연도 학기보다 크다면 => 다음학기 생성하지 않음
        if(!($year >= today()->year && $termOrder[$term] >= $termOrder[$nowTerm->term])) {
            switch($term) {
                case '1st_term':
                    $nextTerm = "{$year}-summer_vacation";
                    break;
                case 'summer_vacation':
                    $nextTerm = "{$year}-2nd_term";
                    break;
                case '2nd_term':
                    $nextTerm = "{$year}-winter_vacation";
                    break;
                case 'winter_vacation':
                    $nextTerm = ($year + 1)."-1st_term";
                    break;
            }
        }

        // 이번 학기에 대한 출력 양식 지정
        $temp = explode('-', $thisTerm);
        $thisTermFormat = __('interface.Y-t', ['year' => $temp[0], 'term' => __("interface.{$temp[1]}")]);

        // 반환
        return [
            'prev'          => $prevTerm,
            'this'          => $thisTerm,
            'this_format'   => $thisTermFormat,
            'next'          => $nextTerm
        ];
    }

    // 연도에 대한 페이지네이션 값을 반환
    public function getYearValue($argThisYear = null) {
        // 01. 변수 설정
        $prevYear = null;
        $thisYear = null;
        $nextYear = null;

        // 이번 연도 설정
        if(is_null($argThisYear)) {
            $thisYear = today();
        } else if(preg_match("#^(19|20)\d{2}$#", $argThisYear)) {
            $thisYear = Carbon::createFromDate($argThisYear, 1, 1);
        } else {
            //throw new ErrorException();
        }

        // 지난 연도 설정
        $prevYear = $thisYear->copy()->subYear();

        // 설정한 이번 연도가 현재보다 과거인 경우 => 다음 연도 설정
        if(today()->year > $thisYear->year) {
            $nextYear = $thisYear->copy()->addYear();
        }

        // 반환
        return [
            'prev'  => $prevYear,
            'this'  => $thisYear,
            'next'  => $nextYear
        ];
    }



    // 엑셀 관련 함수
    public function getType($argType) {
        switch(strtolower($argType)) {
            case 'xlsx':
                return \Maatwebsite\Excel\Excel::XLSX;
                break;

            case 'xls':
                return \Maatwebsite\Excel\Excel::XLS;
                break;
            case 'csv':
                return \Maatwebsite\Excel\Excel::CSV;
                break;
            default:
                return \Maatwebsite\Excel\Excel::XLSX;
        }
    }
}
