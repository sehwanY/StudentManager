<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Professor;
use App\StudyClass;
use App\Student;

/**
 *  클래스명:               UsersTableSeeder
 *  설명:                   사용자 더미 데이터를 생성하는 시더
 *  만든이:                 3-WDJ 春目指し 1401213 이승민
 *  만든날:                 2018년 4월 25일
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 테이블 초기화

        // 01. 교수님 생성
        $professors = [
//            'ycjung'    => [
//                'name'              => '정영철',
//                'phone'             => '053-940-5765',
//                'email'             => 'ycjung@yjc.ac.kr',
//                'office'            => '본관 426호',
//                'photo'             => 'ycjung.jpg'
//            ],
//            'seohk17'   => [
//                'name'              => '서희경',
//                'phone'             => '053-940-5309',
//                'email'             => 'seohk17@yjc.ac.kr',
//                'office'            => '본관 323호',
//                'photo'             => 'hkseo.jpg'
//            ],
//            'xmaskjr'   => [
//                'name'              => '김종율',
//                'phone'             => '053-940-5301',
//                'email'             => 'xmaskjr@yjc.ac.kr',
//                'office'            => '본관 326호',
//                'photo'             => 'jykim.jpg'
//            ],
//            'figures'   => [
//                'name'              => '기쿠치',
//                'phone'             => '053-940-5318',
//                'email'             => 'figures@yjc.ac.kr',
//                'office'            => '본관 427호',
//                'photo'             => 'kikuti.jpg'
//            ],
//            'kjkim'      => [
//                'name'              => '김기종',
//                'phone'             => '053-940-5310',
//                'email'             => 'kjkim@yjc.ac.kr',
//                'office'            => '본관 309호',
//                'photo'             => 'kjkim.jpg'
//            ],
//            'scpack'      => [
//                'name'              => '박성철',
//                'phone'             => '053-940-5307',
//                'email'             => 'scpark@yjc.ac.kr',
//                'office'            => '본관 322호',
//                'photo'             => 'scpack.jpg'
//            ],
//            'gjchoe'      => [
//                'name'              => '최금주',
//                'phone'             => '053-000-0000',
//                'email'             => 'gjchoe@dummy.kr',
//                'office'            => '본관000호',
//                'photo'             => ''
//            ],
//            'komagata'    => [
//                'name'              => '고마가타 사토루',
//                'phone'             => '053-000-0000',
//                'email'             => 'skom1030@naver.com',
//                'office'            => '본관000호',
//                'photo'             => ''
//            ]

            'ycjung'    => [
                'name'              => 'ジョン・ヨンチョル',
                'phone'             => '053-940-5765',
                'email'             => 'ycjung@yjc.ac.kr',
                'office'            => '本館426号',
                'photo'             => 'ycjung.jpg'
            ],
            'seohk17'   => [
                'name'              => 'ソ・ヒギョン',
                'phone'             => '053-940-5309',
                'email'             => 'seohk17@yjc.ac.kr',
                'office'            => '本館323号',
                'photo'             => 'hkseo.jpg'
            ],
            'xmaskjr'   => [
                'name'              => 'キム・ゾンユル',
                'phone'             => '053-940-5301',
                'email'             => 'xmaskjr@yjc.ac.kr',
                'office'            => '本館326号',
                'photo'             => 'jykim.jpg'
            ],
            'figures'   => [
                'name'              => 'きくち',
                'phone'             => '053-940-5318',
                'email'             => 'figures@yjc.ac.kr',
                'office'            => '本館427号',
                'photo'             => 'kikuti.jpg'
            ],
            'kjkim'      => [
                'name'              => 'キム・キゾン',
                'phone'             => '053-940-5310',
                'email'             => 'kjkim@yjc.ac.kr',
                'office'            => '本館309号',
                'photo'             => 'kjkim.jpg'
            ],
            'scpack'      => [
                'name'              => 'バク・ソンチョル',
                'phone'             => '053-940-5307',
                'email'             => 'scpark@yjc.ac.kr',
                'office'            => '本館322号',
                'photo'             => 'scpack.jpg'
            ],
            'gjchoe'      => [
                'name'              => 'チェ・グンズ',
                'phone'             => '053-000-0000',
                'email'             => 'gjchoe@dummy.kr',
                'office'            => '本館000号',
                'photo'             => ''
            ],
            'komagata'    => [
                'name'              => '駒形　悟',
                'phone'             => '053-000-0000',
                'email'             => 'skom1030@naver.com',
                'office'            => '本館000号',
                'photo'             => ''
            ]
        ];

        foreach($professors as $id => $value) {
            // 사용자 부모 데이터 저장
            $user = new User();
            $user->fill([
                'id'        => $id,
                'password'  => password_hash('aaaa', PASSWORD_DEFAULT),
                'name'      => $value['name'],
                'email'     => $value['email'],
                'phone'     => $value['phone'],
                'type'      => 'professor',
                'photo'     => $value['photo']
            ])->save();

            // 교수 데이터 저장
            $professor = new Professor();
            $professor->fill([
                'id'        => $id,
                'office'    => $value['office'],
            ])->save();

            echo "professor {$user->name} is generated!!!\n";
        }

        // 02. 반 생성
        $class = new StudyClass();
        $class->fill([
            'tutor'         => 'ycjung',
            'name'          => '3-WDJ'
        ])->save();

        // 03. 학생 생성
        $students = [
//            1201224	    => '이세혁',
//            1301036	    => '김민수',
//            1301052	    => '김영문',
//            1301102	    => '박병옥',
//            1301143	    => '성형석',
//            1301151	    => '송솔',
//            1301235	    => '장세원',
//            1301238	    => '장준수',
//            1301240	    => '전상원',
//            1301264	    => '정현우',
//            1301281	    => '최민석',
//            1301282	    => '최병찬',
//            1301292	    => '하재형',
//            1401004	    => '강상운',
//            1401016	    => '권범수',
//            1401050	    => '김성준',
//            1401055	    => '김승목',
//            1401117	    => '류호형',
//            1401134	    => '박주용',
//            1401136	    => '박준상',
//            1401145	    => '박효동',
//            1401163	    => '손진호',
//            1401179	    => '안준휘',
//            1401185	    => '염세환',
//            1401213	    => '이승민',
//            1401228	    => '이준영',
//            1401280	    => '진성규',
//            1401290	    => '최요한',
//            1601005	    => '강성은',
//            1601012	    => '곽다희',
//            1601128	    => '성경임',
//            1601129	    => '성기혁',
//            1601145	    => '심유림',
//            1601155	    => '오윤정',
//            1601176	    => '윤진주',
//            1601193	    => '이성민',
//            1601204 	    => '이아름',
//            1601224	    => '이지윤',
//            1601228	    => '이하연',
//            1601230	    => '이혜미',
//            1601232	    => '이효진',
//            1601242	    => '장다연',
//            1601259	    => '정지민',
//            1601266	    => '조수진',
//            1601273	    => '주영호',
//            1601279	    => '최선주',
//            1601286	    => '최준규',
//            1601305	    => '황금비',

            1201224	    => 'イ・セヒョク',
            1301036	    => 'キム・ミンス',
            1301052	    => 'キム・ヨンムン',
            1301102	    => 'バク・ビョンオク',
            1301143	    => 'ソン・ヒョンソク',
            1301151	    => 'ソン・ソル',
            1301235	    => 'ジャン・セウォン',
            1301238	    => 'ジャン・ジュンス',
            1301240	    => 'ジョン・サンウァン',
            1301264	    => 'ジョン・ヒョンウ',
            1301281	    => 'チェ・ミンソク',
            1301282	    => 'チェ・ビョンチャン',
            1301292	    => 'ハ・ジェヒョン',
            1401004	    => 'カン・サンウン',
            1401016	    => 'クォン・ボムス',
            1401050	    => 'キム・ソンジュン',
            1401055	    => 'キム・スンモク',
            1401117	    => 'リュウ・ホヒョン',
            1401134	    => 'バク・ジュヨン',
            1401136	    => 'バク・ジュンサン',
            1401145	    => 'バク・ヒョドン',
            1401163	    => 'ソン・ジンホ',
            1401179	    => 'アン・ジュンフイ',
            1401185	    => 'ヨム・セファン',
            1401213	    => 'イ・スンミン',
            1401228	    => 'イ・ジュンヨン',
            1401280	    => 'ジン・ソンギュ',
            1401290	    => 'チェ・ヨハン',
            1601005	    => 'カン・ソンウン',
            1601012	    => 'クア・ダヒ',
            1601128	    => 'ソン・ギョンイム',
            1601129	    => 'ソン・ギヒョク',
            1601145	    => 'シム・ユリム',
            1601155	    => 'オ・ユンジョン',
            1601176	    => 'ユン・ジンジュ',
            1601193	    => 'イ・ソンミン',
            1601204 	=> 'イ・アルム',
            1601224	    => 'イ・ジユン',
            1601228	    => 'イ・ハヨン',
            1601230	    => 'イ・へミ',
            1601232	    => 'イ・ヒョジン',
            1601242	    => 'ジャン・ダヨン',
            1601259	    => 'ジョン・ジミン',
            1601266	    => 'ジョ・スジン',
            1601273	    => 'ジュ・ヨンホ',
            1601279	    => 'チェ・ソンジュ',
            1601286	    => 'チェ・ジュンギュ',
            1601305	    => 'ファン・グンビ'
        ];

        foreach($students as $id => $value) {
            // 사용자 부모 데이터 저장
            $user = new User();
            $user->fill([
                'id'        => $id,
                'password'  => password_hash('aaaa', PASSWORD_DEFAULT),
                'name'      => $value,
                'email'     => 'exam@exam.com',
                'phone'     => '000-1234-5678',
                'type'      => 'student'
            ])->save();

            // 교수 데이터 저장
            $student = new Student();
            $student->fill([
                'id'            => $id,
                'study_class'   => $class->id,
            ])->save();

            echo "student {$user->name} is generated!!!\n";
        }

        // 03. 관리자 생성
        $admin = new User();
        $admin->fill([
            'id'        => 'admin',
            'password'  => password_hash('aaaa', PASSWORD_DEFAULT),
            'name'      => '管理者',
            'email'     => 'root@root.com',
            'phone'     => '000-1234-5678',
            'type'      => 'admin'
        ])->save();

        echo "admin is generated!!!\n";
    }
}
