*** ���� ����� �� ...

// �ʼ�
1. Ŭ���� ����ε� ����
composer dump-autoload

2. ���̺귯�� ��ġ
composer require awobaz/compoships





// ���� ���� �� ��ġ
1. ���̺귯�� ��ġ
composer require guzzlehttp/guzzle

2. .env ���� ����
HOLIDAY_DATA_KEY=X5q3RV8Km5fwgK%2Fu22ghOh2Tbl8fH0YBygDPYaTg%2BUuHjLH3suTGLA8kKEWo1hI%2BPmyElwXM5mu4noGreJGmYg%3D%3D

3. config/app.php ���Ͽ� ���� ������ �߰�
    /**
     *  ------------------------------
     *  ���������� ������ ��ȸ�� ����Ű
     *  ------------------------------
     *
     *  ���������� �����͸� ��� ���� �ʿ��� ����Ű�� ����.
     *  ����Ű ��ȿ������ �߱� �� 2��. (2018�� ����)
     *
     *  ### ���������� ������ ȹ�� URL : https://www.data.go.kr/dataset/15012690/openapi.do
     */

    'holiday_data_key' => env('HOLIDAY_DATA_KEY', NULL),

4. �����ͺ��̽� ������
php artisan migrate:reset
php artisan migrate
php artisan db:seed

5. php artisan ��ɾ� ��� Ȯ��
php artisan list

holiday �׸� holiday:add �Լ��� �߰��Ǿ����� Ȯ��

6. �ش� ��ɾ� ����
php artisan holiday:add

7. �����ͺ��̽��� ������ ������ ����Ǿ��� �� Ȯ��
select * from schedules
