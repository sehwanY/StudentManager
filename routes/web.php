<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 *  ## 테스트용
 */

Route::name('test.')->group(function() {
    // 세션 정보 호출
    Route::get('/session', [
        'as'    => 'session',
        'uses'  => 'HomeController@session',
    ]);

    // 요청 정보 확인
    Route::match(['GET', 'POST'], '/request', [
        'as'    => 'session',
        'uses'  => 'HomeController@request',
    ]);

    Route::match(['GET', 'POST'], '/test', [
        'as'    => 'index',
        'uses'  => function (){
            return view('test');
        }

//        'uses'  => 'HomeController@test'
    ]);
});

/**
 *  01. 홈 컨트롤러 라우팅
 */
Route::name('home.')->group(function() {

    // 전체 메인 페이지
    Route::get('/', [
        'as'    => 'index',
        'uses'  => 'HomeController@index'
    ]);

    // 언어 설정
    Route::get('language/{locale}', [
        'as'    => 'language',
        'uses'  => 'HomeController@setLanguage'
    ]);

    // 회원가입
    Route::post('/join', [
        'as'    => 'join',
        'uses'  => 'HomeController@join'
    ]);

    // 회원가입 여부 확인
    Route::get('/join/check', [
        'as'    => 'join.check',
        'uses'  => 'HomeController@checkJoin'
    ]);

    // 로그인 페이지
    Route::post('/login', [
        'as'    => 'login',
        'uses'  => 'HomeController@login'
    ]);

    // 로그아웃 기능
    Route::get('/logout', [
        'as'    => 'logout',
        'uses'  => 'HomeController@logout'
    ]);

    // 비밀번호 변경
    Route::group([
        'as'        => 'password_change.',
        'prefix'    => 'password_change'
    ], function() {
        // 비밀번호 교환 자격 확인
        Route::post('/verify', [
            'as'   => 'verify',
            'uses' => 'HomeController@verifyChangePasswordAuthority'
        ]);

        // 비밀변호 변경 웹페이지 호출
        Route::get('/page/{key}', [
            'as'    => 'page',
            'uses'  => function($key) {
                return view('change_password', ['key' => $key]);
            }
        ]);

        // 비밀번호 변경키 유효성 검증
        Route::post('/check', [
            'as'    => 'check',
            'uses'  => 'HomeController@checkVerifyKey'
        ]);

        // 비밀변호 변경
        Route::post('/change', [
            'as'    => 'active',
            'uses'  => 'HomeController@changePassword'
        ]);
    });




    // 하드웨어

    // 학생 인증
    Route::post('/check_student', [
        'as'    => 'check_student',
        'uses'  => 'HomeController@checkStudent'
    ]);

    // 오늘의 시간표 조회
    Route::get('/timetable', [
        'as'    => 'timetable',
        'uses'  => 'HomeController@getTimetableOfToday'
    ]);

    // 오늘의 출석기록 조회
    Route::get('/attendance', [
        'as'    => 'attendance',
        'uses'  => 'HomeController@getAttendanceRecordsOfToday'
    ]);


    /* 교수 권한이 있어야 접근 가능 */
    Route::middleware(['check.professor'])->group(function() {
        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 start ■■■■■■■■■■■■■■■■■■■■■■■■ */
        Route::get('/studentManagement/main', function(){
            return view('index');
        });

        Route::get('/studentManagement/grade', function(){
            return view('index');
        });

        Route::get('/studentManagement/comment', function(){
            return view('index');
        });
        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 end ■■■■■■■■■■■■■■■■■■■■■■■■ */
    });


    // 로그인 이후 사용 기능
    Route::middleware(['check.login'])-> group(function() {
        // 사용자 정보 획득
        Route::get('/info', [
            'as'    => 'info',
            'uses'  => 'HomeController@getUserInfo'
        ]);
    });
});



/**
 *  02. 학생 컨트롤러 기능
 */
Route::group([
    'as'        => 'student.',
    'prefix'    => 'student'
], function() {

    // 등교
    Route::post('/sign_in', [
        'as'    => 'sign_in',
        'uses'  => 'StudentController@signIn'
    ]);

    // 하교
    Route::post('/sign_out', [
        'as'    => 'sign_out',
        'uses'  => 'StudentController@signOut'
    ]);

    // 로그인 이후 사용 가능 기능
    Route::middleware(['check.student'])->group(function() {
        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 start ■■■■■■■■■■■■■■■■■■■■■■■■ */
        Route::get('/attendanceManagement', function(){
            return view('index');
        });

        Route::get('/gradeManagement', function(){
            return view('index');
        });

        Route::get('/userInfo', function(){
            return view('index');
        });

        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 end ■■■■■■■■■■■■■■■■■■■■■■■■ */

        // 학생 메인 페이지
        Route::get('/main', [
            'as'    => 'index',
            'uses'  => 'StudentController@index'
        ]);

        // 내 정보
        // 내 정보 관리
        Route::group([
            'as'        => 'info.',
            'prefix'    => 'info'
        ], function() {
            // 정보 조회
            Route::get('/select', [
                'as'    => 'select',
                'uses'  => 'StudentController@getMyInfo'
            ]);

            // 정보 갱신
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'StudentController@updateMyInfo'
            ]);
        });


        // 출결 관리

        // 출결 정보 열람
        Route::get('/attendance', [
            'as'    => 'attendance',
            'uses'  => 'StudentController@getMyAttendanceRecords'
        ]);



        // 강의 관리

        // 내 수강정보 열람
        Route::get('/subject', [
            'as'    => 'subject',
            'uses'  => 'StudentController@getMySubjectList'
        ]);

        // 강의 시간표 획득
        Route::get('/timetable', [
            'as'    => 'timetable',
            'uses'  => 'StudentController@getTimetable'
        ]);
    });
});



/**
 *  03. 교수 컨트롤러 기능
 */
Route::group([
    'as'        => 'professor.',
    'prefix'    => 'professor'
], function() {

    // 로그인 이후 사용 가능 기능
    Route::middleware(['check.professor'])->group(function() {

        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 start ■■■■■■■■■■■■■■■■■■■■■■■■ */
        Route::get('/studentManagement', function(){
            return view('index');
        });

        Route::get('/gradeCheck', function(){
            return view('index');
        });

        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 End ■■■■■■■■■■■■■■■■■■■■■■■■ */

        // 교수 메인 페이지
        Route::get('/main', [
            'as'    => 'index',
            'uses'  => 'ProfessorController@index'
        ]);

        // 지도교수 여부 확인
        Route::post('/is_tutor', [
            'as'    => 'is_tutor',
            'uses'  => 'ProfessorController@isTutor'
        ]);

        // 내 담당강의 시간표 확인
        Route::get("/timetable", [
            'as'    => 'timetable',
            'uses'  => 'ProfessorController@getTimetable'
        ]);

        // 내 정보 관리
        Route::group([
            'as'        => 'info.',
            'prefix'    => 'info'
        ], function() {
            // 정보 조회
            Route::get('/select', [
                'as'    => 'select',
                'uses'  => 'ProfessorController@getMyInfo'
            ]);

            // 정보 갱신
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'ProfessorController@updateMyInfo'
            ]);
        });



        // 강의 관리
        Route::group([
            'as'        => 'subject.',
            'prefix'    => 'subject'
        ], function() {
            // 강의목록 조회
            Route::get('/list', [
                'as'    => 'list',
                'uses'  => 'ProfessorController@getMySubjectList'
            ]);

            // 해당 강의의 학생목록 조회
            Route::get('/join_list', [
                'as'    => 'join_list',
                'uses'  => 'ProfessorController@getJoinListOfSubject'
            ]);

            // 강의 관리 관련 기능
            Route::group([
                'as'        => 'manage.',
                'prefix'    => 'manage'
            ], function() {

                // 학업성취도 반영비 관련 기능

                // 해당 강의의 성적별 학업성취도 반영비율 조회
                Route::get('/reflection_select', [
                    'as'    => 'reflection_select',
                    'uses'  => 'ProfessorController@getAchievementReflections'
                ]);

                // 해당 강의의 성적별 학업성취도 반영비율 수정
                Route::post('/reflection_update', [
                    'as'    => 'reflection_update',
                    'uses'  => 'ProfessorController@updateAchievementReflections'
                ]);
            });



            // 성적 관련 기능
            Route::group([
                'as'        => 'score.',
                'prefix'    => 'score'
            ], function() {

                // 해당 과목에서 제출된 성적 목록 조회
                Route::get('/list', [
                    'as'    => 'list',
                    'uses'  => 'ProfessorController@getScoresList'
                ]);

                // 해당 성적 유형에서 학생들이 취득한 성적 확인
                Route::get('/gained_scores', [
                    'as'    => 'gained_scores',
                    'uses'  => 'ProfessorController@getGainedScoreList'
                ]);

                // 해당 학생의 성적 갱신
                Route::post('/update', [
                    'as'    => 'update',
                    'uses'  => 'ProfessorController@updateGainedScore'
                ]);

                // 성적 등록 Excel 파일 생성
                Route::post('/excel/download', [
                    'as'    => 'excel_download',
                    'uses'  => 'ProfessorController@downloadScoreForm'
                ]);

                // Excel 파일을 이용한 성적 등록
                Route::post('/excel/upload', [
                    'as'    => 'excel_upload',
                    'uses'  => 'ProfessorController@uploadScoresAtExcel'
                ]);

                // 웹 인터페이스를 통한 직접 성적 등록
                Route::post('/upload', [
                    'as'    => 'directly_upload',
                    'uses'  => 'ProfessorController@uploadScores'
                ]);
            });
        });



        // 수강학생별 상세 관리
        Route::group([
            'as'        => 'detail',
            'prefix'    => 'detail'
        ], function() {
            // 지정한 학생의 정보 조회
            Route::get('/info', [
                'as'    => 'info',
                'uses'  => 'ProfessorController@getInfoOfStudent'
            ]);

            // 지정한 학생의 수강 목록 중 자신의 강의를 조회
            Route::get('/join_list', [
                'as'    => 'join_list',
                'uses'  => 'ProfessorController@getJoinListOfStudent'
            ]);

            // 지정한 학생이 해당 강의에서 취득한 성적 목록 조회
            Route::get('/score', [
                'as'    => 'score',
                'uses'  => 'ProfessorController@detailScoresOfStudent'
            ]);


            // 코멘트 관리
            Route::group([
                'as'     => 'comment.',
                'prefix' => 'comment'
            ], function() {
                // 코멘트 조회
                Route::get('/select', [
                    'as'    => 'select',
                    'uses'  => 'ProfessorController@selectComment'
                ]);

                // 코멘트 등록
                Route::post('/insert', [
                    'as'    => 'insert',
                    'uses'  => 'ProfessorController@insertComment'
                ]);

                // 코멘트 수정
                Route::post('/update', [
                    'as'    => 'update',
                    'uses'  => 'ProfessorController@updateComment'
                ]);

                // 코멘트 삭제
                Route::post('/delete', [
                    'as'    => 'delete',
                    'uses'  => 'ProfessorController@deleteComment'
                ]);
            });
        });
    });
});


/**
 *  04. 지도교수 컨트롤러 기능
 */
Route::group([
    'as'        => 'tutor.',
    'prefix'    => 'tutor'
], function() {
    // 로그인 이후 사용 기능
    Route::middleware(['check.professor', 'check.tutor'])->group(function() {

        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 start ■■■■■■■■■■■■■■■■■■■■■■■■ */
        Route::get('/attendance', function(){
            return view('index');
        });

        Route::get('/alertStudentSetting',function() {
            return view('index');
        });

        Route::get('/studentManagement', function(){
            return view('index');
        });

        Route::get('/studentAnalyticPrediction', function(){
            return view('index');
        });

        Route::get('/studentAnalyticPredictionSetting', function(){
            return view('index');
        });

        Route::get('/classAnalyticPrediction',function() {
            return view('index');
        });

        Route::get('/userInfo',function() {
            return view('index');
        });
        
        /* ■■■■■■■■■■■■■■■■■■■■■■■■ 경로추가 End ■■■■■■■■■■■■■■■■■■■■■■■■ */


        // 메인

        // 메인화면 출력
        Route::get('/main', [
            'as'    => 'index',
            'uses'  => 'TutorController@index'
        ]);



        // 출결 관리
        Route::group([
            'as'        => 'attendance.',
            'prefix'    => 'attendance'
        ], function() {
            // 오늘 출결기록 조회
            Route::get('/today', [
                'as'    => 'today',
                'uses'  => 'TutorController@getAttendanceRecordsOfToday'
            ]);

            // 출결알림 관리
            Route::group([
                'as'     => 'care.',
                'prefix' => 'care'
            ], function() {
                // 알림 저장
                Route::post('/insert', [
                    'as'    => 'insert',
                    'uses'  => 'TutorController@setNeedCareAlert'
                ]);

                // 알림 조회
                Route::get('/select', [
                    'as'    => 'select',
                    'uses'  => 'TutorController@getNeedCareAlertList'
                ]);

                // 알림 삭제
                Route::post('/delete', [
                    'as'    => 'delete',
                    'uses'  => 'TutorController@deleteNeedCareAlert'
                ]);
            });
        });



        // 내 지도반 관리
        Route::group([
            'as'        => 'class.',
            'prefix'    => 'class'
        ], function() {
            // 학생 목록 조회
            Route::get('/student_list', [
                'as'    => 'student_list',
                'uses'  => 'TutorController@getMyStudentsList'
            ]);

            // 강의 목록 조회
            Route::get('/subject_list', [
                'as'    => 'subject_list',
                'uses'  => 'TutorController@selectSubjectsList'
            ]);

            // 지도반 이름 변경
            Route::post('/update_name', [
                'as'    => 'update_name',
                'uses'  => 'TutorController@updateClassName'
            ]);

            // 지도반 등하교 시간 갱신
            Route::post('/update_time', [
                'as'    => 'update_time',
                'uses'  => 'TutorController@updateSignInOutTime'
            ]);

            // 일정 관리
            Route::group([
                'as'        => 'schedule.',
                'prefix'    => 'schedule'
            ], function() {
                // 일정 조회
                Route::get("/select", [
                    'as'    => 'select',
                    'uses'  => 'TutorController@selectSchedule'
                ]);

                // 일정 삽입
                Route::post("/insert", [
                    'as'    => 'select',
                    'uses'  => 'TutorController@insertSchedule'
                ]);

                // 일정 갱신
                Route::post("/update", [
                    'as'    => 'select',
                    'uses'  => 'TutorController@updateSchedule'
                ]);

                // 일정 삭제
                Route::post("/delete", [
                    'as'    => 'select',
                    'uses'  => 'TutorController@deleteSchedule'
                ]);
            });
        });



        // 학생 분석
        Route::group([
            'as'        => 'analyse.',
            'prefix'    => 'analyse'
        ], function() {
            // 학생 분류기준 조회
            Route::get('/select_criteria', [
                'as'    => 'select_criteria',
                'uses'  => 'TutorController@getCriteriaOfEvaluation'
            ]);

            // 학생 분류기준 수정
            Route::post('/update_criteria', [
                'as'    => 'update_criteria',
                'uses'  => 'TutorController@updateCriteriaOfEvaluation'
            ]);

            // 알고리즘에 의해 분류된 학생 목록 조회
            Route::get('/student_list', [
                'as'    => 'student_list',
                'uses'  => 'TutorController@getStudentListOfType'
            ]);

            // 분석 조건으로 사용할 속성 목록 획득
            Route::get('/option_list', [
                'as'    => 'option_list',
                'uses'  => 'TutorController@getOptionForStudent'
            ]);

            // 조건 조합에 의한 분석 결과 반환
            Route::get('/result', [
                'as'    => 'result',
                'uses'  => 'TutorController@getDataOfGraph'
            ]);
        });



        // 학생별 상세 관리
        Route::group([
            'as'        => 'detail.',
            'prefix'    => 'detail'
        ], function() {

            // 해당 학생의 출결 관리
            Route::group([
                'as'        => 'attendance.',
                'prefix'    => 'attendance'
            ], function() {
                // 해당 학생의 출결기록 갱신
                Route::post('update', [
                    'as'    => 'update',
                    'uses'  => 'TutorController@updateReasonOfAttendance'
                ]);

                // 해당 학생의 출석 통계 조회
                Route::get('/stat', [
                    'as'    => 'stat',
                    'uses'  => 'TutorController@getDetailsOfAttendanceStats'
                ]);

                // 해당 학생의 출석 데이터 목록 획득
                Route::get('/list', [
                    'as'    => 'list',
                    'uses'  => 'TutorController@getDetailsOfAttendanceRecords'
                ]);

                // 해당 학생의 출결 분석 결과 획득
                Route::get('/analyse', [
                    'as'    => 'analyse',
                    'uses'  => 'TutorController@getDetailsOfAnalyseAttendance'
                ]);

                // 모바일 : 출결 그래프 획득
                Route::get('/graph', [
                    'as'    => 'graph',
                    'uses'  => 'TutorController@getGraphOfAttendance'
                ]);
            });

            // 해당 학생의 수강목록 조회
            Route::get('/join_list', [
                'as'    => 'join_list',
                'uses'  => 'TutorController@getDetailsOfSubjects'
            ]);

            // 학생이 해당 강의에서 취득한 성적통계 획득
            Route::get('/subject_stat', [
                'as'    => 'subject_stat',
                'uses'  => 'TutorController@getDetailsOfScoreStat'
            ]);

            // 학생이 해당 강의에서 취득한 성적 목록 조회
            Route::get('/subject_scores', [
                'as'    => 'subject_scores',
                'uses'  => 'TutorController@getDetailsOfScoreList'
            ]);

            // 학생 관심도 수정
            Route::post('/attention_level/update', [
                'as'    => 'attention_update',
                'uses'  => 'TutorController@setAttentionLevel'
            ]);
        });
    });
});


/**
 *  05. 관리자 컨트롤러 기능
 */
Route::group([
    'as'        => 'admin.',
    'prefix'    => 'admin'
], function() {

    // 로그인 이후 사용 가능 기능
    Route::middleware(['check.admin'])->group(function() {
        // 관리자 메인 페이지
        Route::get('/main', [
            'as'    => 'index',
            'uses'  => 'AdminController@index'
        ]);


        // 회원관리
        // 학생 관리
        Route::group([
            'as'        => 'student.',
            'prefix'    => 'student'
        ], function() {
            // 학생 목록 획득
            Route::get('/select_list', [
                'as'    => 'select_list',
                'uses'  => 'AdminController@selectStudentsList'
            ]);

            // 학생별 상세 정보 획득
            Route::get('/select_info', [
                'as'    => 'select_info',
                'uses'  => 'AdminController@selectStudentInfo'
            ]);

            // 내 지도반 학생 등록
            Route::post('/insert', [
                'as'    => 'insert',
                'uses'  => 'AdminController@insertStudent'
            ]);

            // 내 지도반 학생 등록을 위한 엑셀 양식 출력
            Route::get('/get_form', [
                'as'    => 'get_form',
                'uses'  => 'AdminController@getStudentRegisterExcel'
            ]);

            // 엑셀 파일을 통한 내 지도반 학생 등록
            Route::post('/import', [
                'as'    => 'import',
                'uses'  => 'AdminController@importStudentRegisterExcel'
            ]);

            // 학생 지도반 수정
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'AdminController@updateStudent'
            ]);

            // 학생 삭제
            Route::post('/delete', [
                'as'    => 'delete',
                'uses'  => 'AdminController@deleteStudent'
            ]);
        });

        // 교수 관리
        Route::group([
            'as'        => 'professor.',
            'prefix'    => 'professor'
        ], function() {
            // 교수 목록 획득
            Route::get('/select_list', [
                'as'    => 'select_list',
                'uses'  => 'AdminController@selectProfessors'
            ]);

            // 해당 교수에 대한 상세 정보 획득
            Route::get('/select_info', [
                'as'    => 'select_info',
                'uses'  => 'AdminController@selectProfessorInfo'
            ]);

            // 교수 정보 수정
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'AdminController@updateProfessor'
            ]);

            // 교수 삭제
            Route::post('/delete', [
                'as'    => 'delete',
                'uses'  => 'AdminController@deleteProfessor'
            ]);
        });

        // 지도반 관리
        Route::group([
            'as'        => 'class.',
            'prefix'    => 'class'
        ], function() {
            // 지도반 목록 조회
            Route::get('/select_list', [
                'as'    => 'select_list',
                'uses'  => 'AdminController@selectClassesList'
            ]);

            // 지도반 등록
            Route::post('/insert', [
                'as'    => 'insert',
                'uses'  => 'AdminController@insertClass'
            ]);

            // 지도반 삭제
            Route::post('/delete', [
                'as'    => 'delete',
                'uses'  => 'AdminController@deleteClass'
            ]);
        });


        // 각 지도반별 강의 관리
        Route::group([
            'as'        => 'subject.',
            'prefix'    => 'subject'
        ], function() {
            // 강의목록 조회
            Route::get('/select_list', [
                'as'    => 'select_list',
                'uses'  => 'AdminController@selectSubjects'
            ]);

            // 자체 인터페이스를 이용한 강의 등록
            Route::post('/insert', [
                'as'    => 'insert',
                'uses'  => 'AdminController@insertSubjects'
            ]);

            // 강의 등록을 위한 엑셀 양식 출력
            Route::get('/get_form', [
                'as'    => 'get_form',
                'uses'  => 'AdminController@getSubjectRegisterExcel'
            ]);

            // 엑셀 파일을 이용한 강의 등록
            Route::post('/import', [
                'as'    => 'import',
                'uses'  => 'AdminController@importSubjects'
            ]);

            // 강의 수정
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'AdminController@updateSubject'
            ]);

            // 강의 삭제
            Route::post('/delete', [
                'as'    => 'delete',
                'uses'  => 'AdminController@deleteSubject'
            ]);


            // 수강목록 관리
            Route::group([
                'as'     => 'join_list.',
                'prefix' => 'join_list'
            ], function() {
                // 조회
                Route::get('/select', [
                    'as'    => 'select',
                    'uses'  => 'AdminController@selectJoinList'
                ]);

                // 등록
                Route::post('/insert', [
                    'as'    => 'insert',
                    'uses'  => 'AdminController@insertJoinList'
                ]);

                // 삭제
                Route::post('/delete', [
                    'as'    => 'delete',
                    'uses'  => 'AdminController@deleteJoinList'
                ]);
            });


            // 지도반별 시간표 관리
            Route::group([
                'as'        => 'timetable.',
                'prefix'    => 'timetable'
            ], function() {
                // 시간표 조회
                Route::get('/select', [
                    'as'    => 'select',
                    'uses'  => 'AdminController@selectTimetable'
                ]);

                // 자체 인터페이스를 이용한 시간표 등록
                Route::post('/insert', [
                    'as'    => 'insert',
                    'uses'  => 'AdminController@insertTimetables'
                ]);

                // 시간표 등록을 위한 엑셀 양식 받기
                Route::get('/get_form', [
                    'as'    => 'get_form',
                    'uses'  => 'AdminController@getTimetableRegisterExcel'
                ]);

                // 엑셀 양식을 이용해 시간표 등록
                Route::post('/import', [
                    'as'    => 'import',
                    'uses'  => 'AdminController@importTimetablesRegister'
                ]);
            });
        });


        // 일정 관리
        Route::group([
            'as'        => 'schedule.',
            'prefix'    => 'schedule'
        ], function() {
            // 일정 조회
            Route::get('/select', [
                'as'    => 'select',
                'uses'  => 'AdminController@selectCommonSchedule'
            ]);

            // 일정 등록
            Route::post('/insert', [
                'as'    => 'insert',
                'uses'  => 'AdminController@insertCommonSchedule'
            ]);

            // 일정 갱신
            Route::post('/update', [
                'as'    => 'update',
                'uses'  => 'AdminController@updateCommonSchedule'
            ]);

            // 일정 삭제
            Route::post('/delete', [
                'as'    => 'delete',
                'uses'  => 'AdminController@deleteCommonSchedule'
            ]);
        });
    });
});
